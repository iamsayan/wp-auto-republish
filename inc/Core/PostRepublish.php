<?php

/**
 * The Main file.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Core
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Core;

use  Wpar\Helpers\Hooker ;
use  Wpar\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class PostRepublish
{
    use  Hooker, SettingsData ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'init', 'republish_init' );
    }
    
    /**
     * Initialize auto republish.
     */
    public function republish_init()
    {
        $wpar_days = $this->get_data( 'wpar_days' );
        $cur_date = current_time( 'timestamp', 0 );
        $day = lcfirst( date( 'D', $cur_date ) );
        $cur_time = strtotime( date( 'H:i:s', $cur_date ) );
        $start_time = ( !empty($this->get_data( 'wpar_start_time' )) ? strtotime( $this->get_data( 'wpar_start_time' ) ) : strtotime( '05:00:00' ) );
        $end_time = ( !empty($this->get_data( 'wpar_end_time' )) ? strtotime( $this->get_data( 'wpar_end_time' ) ) : strtotime( '23:59:59' ) );
        if ( $this->check_global_republish() ) {
            if ( $this->generate_next_schedule() ) {
                if ( !empty($wpar_days) && in_array( $day, $wpar_days ) ) {
                    
                    if ( $cur_time >= $start_time && $cur_time <= $end_time ) {
                        update_option( 'wpar_last_update', time() );
                        $this->republish_old_post();
                    }
                
                }
            }
        }
    }
    
    /**
     * Get eligible posts.
     */
    private function republish_old_post()
    {
        $timestamp = current_time( 'timestamp', 0 );
        $time = current_time( 'mysql' );
        $wpar_overwrite = $this->get_data( 'wpar_exclude_by_type' );
        $wpar_gap = $this->get_data( 'wpar_republish_post_age' );
        $wpar_orderby = $this->get_data( 'wpar_republish_orderby' );
        $wpar_order = $this->get_data( 'wpar_republish_method' );
        
        if ( !empty($this->get_data( 'wpar_post_types' )) ) {
            $post_types = $this->get_data( 'wpar_post_types' );
            if ( is_array( $post_types ) ) {
                $post_types = array_unique( $post_types );
            }
        }
        
        $wpar_filter_taxonomy = ( !empty($this->get_data( 'wpar_post_taxonomy' )) ? $this->get_data( 'wpar_post_taxonomy' ) : [] );
        $wpar_omit_override = $this->get_data( 'wpar_override_category_tag' );
        $wpar_omit_override = preg_replace( [
            '/[^\\d,]/',
            '/(?<=,),+/',
            '/^,+/',
            '/,+$/'
        ], '', $wpar_omit_override );
        if ( empty($post_types) || !is_array( $post_types ) ) {
            return;
        }
        $query = $cats = $tags = $terms = [];
        foreach ( $post_types as $post_type ) {
            $args = [
                'post_status' => 'publish',
                'post_type'   => $post_type,
                'numberposts' => -1,
                'date_query'  => [ [
                'before' => $this->do_filter( 'post_before_date', date( 'Y-m-d', strtotime( "-{$wpar_gap} days", $timestamp ) ), $timestamp ),
            ] ],
            ];
            if ( !in_array( $post_type, [ 'post', 'page', 'attachment' ] ) ) {
                $args['meta_query'] = [
                    'relation' => 'OR',
                    [
                    'key'     => '_wpar_post_republish_occurrence',
                    'compare' => 'NOT EXISTS',
                ],
                    [
                    'relation' => 'AND',
                    [
                    'key'     => '_wpar_post_republish_occurrence',
                    'compare' => 'EXISTS',
                ],
                    [
                    'key'     => '_wpar_post_republish_occurrence',
                    'value'   => '3',
                    'compare' => '<=',
                ],
                ],
                ];
            }
            if ( $post_type == 'post' && $wpar_overwrite != 'none' ) {
                
                if ( !empty($wpar_filter_taxonomy) ) {
                    foreach ( $wpar_filter_taxonomy as $category ) {
                        $get_item = explode( '|', $category );
                        $type = $get_item[0];
                        $term_name = $get_item[1];
                        $term_id = $get_item[2];
                        if ( $post_type === $type && is_object_in_taxonomy( $post_type, $term_name ) ) {
                            
                            if ( $term_name == 'category' ) {
                                $cats[] = $term_id;
                            } elseif ( $term_name == 'post_tag' ) {
                                $tags[] = $term_id;
                            }
                        
                        }
                    }
                    
                    if ( $wpar_overwrite == 'include' ) {
                        if ( !empty($cats) ) {
                            $args['category__in'] = $cats;
                        }
                        if ( !empty($tags) ) {
                            $args['tag__in'] = $tags;
                        }
                    } elseif ( $wpar_overwrite == 'exclude' ) {
                        if ( !empty($cats) ) {
                            $args['category__not_in'] = $cats;
                        }
                        if ( !empty($tags) ) {
                            $args['tag__not_in'] = $tags;
                        }
                    }
                
                }
            
            }
            //error_log( print_r( $args, true ) );
            // store post objects into an array
            $query[] = get_posts( $args );
        }
        // merge all existing arrays
        $posts_list = array_merge( ...$query );
        $post_ids = wp_list_pluck( $posts_list, 'ID' );
        //error_log( print_r( $posts_list, true ) );
        
        if ( !empty($post_ids) ) {
            $args = [
                'post_type'   => 'any',
                'post_status' => 'publish',
                'post__in'    => $post_ids,
                'numberposts' => 1,
                'orderby'     => 'date',
            ];
            if ( !empty($wpar_order) ) {
                
                if ( $wpar_order == 'new_first' ) {
                    $args['order'] = 'DESC';
                } elseif ( $wpar_order == 'old_first' ) {
                    $args['order'] = 'ASC';
                }
            
            }
            if ( !empty($wpar_orderby) ) {
                $args['orderby'] = $wpar_orderby;
            }
            if ( !empty($wpar_omit_override) ) {
                
                if ( $wpar_overwrite == 'include' ) {
                    $args['post__in'] = array_diff( $post_ids, explode( ',', $wpar_omit_override ) );
                } elseif ( $wpar_overwrite == 'exclude' ) {
                    $args['post__in'] = array_unique( array_merge( $post_ids, explode( ',', $wpar_omit_override ) ) );
                }
            
            }
            //error_log( print_r( $args, true ) );
            $getposts = get_posts( $args );
            if ( !empty($getposts) ) {
                foreach ( $getposts as $post ) {
                    // run post republish process
                    $this->update_old_post( $post->ID );
                    //error_log( $post->ID );
                }
            }
        }
    
    }
    
    /**
     * Build post update process.
     * 
     * @param int   $post_id Post ID
     * @param bool  $single  Check if it is single republish event
     */
    protected function update_old_post( $post_id, $single = false, $instant = false )
    {
        $post = get_post( $post_id );
        $timestamp = current_time( 'timestamp', 0 );
        $pub_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
        if ( empty($pub_date) ) {
            $this->update_meta( $post->ID, '_wpar_original_pub_date', $post->post_date );
        }
        $this->custom_post_types_events( $post );
        
        if ( $this->get_data( 'wpar_republish_post_position' ) == 1 ) {
            $new_time = current_time( 'mysql' );
        } else {
            $lastposts = get_posts( [
                'post_type'   => $post->post_type,
                'numberposts' => 1,
                'offset'      => 1,
                'post_status' => 'publish',
                'order'       => 'DESC',
            ] );
            
            if ( !empty($lastposts) ) {
                foreach ( $lastposts as $lastpost ) {
                    $post_date = strtotime( $lastpost->post_date );
                    $new_time = date( 'Y-m-d H:i:s', mktime(
                        date( 'H', $post_date ),
                        date( 'i', $post_date ) + 5,
                        date( 's', $post_date ),
                        date( 'm', $post_date ),
                        date( 'd', $post_date ),
                        date( 'Y', $post_date )
                    ) );
                }
            } else {
                $new_time = current_time( 'mysql' );
            }
        
        }
        
        $args = [
            'ID'            => $post->ID,
            'post_date'     => $new_time,
            'post_date_gmt' => get_gmt_from_date( $new_time ),
        ];
        //error_log( print_r( $args, true ) );
        wp_update_post( $args );
        $this->do_action( 'clear_site_cache' );
    }
    
    /**
     * Generate global republish schedules.
     */
    private function generate_next_schedule()
    {
        $last = get_option( 'wpar_last_update' );
        $interval = $this->get_data( 'wpar_minimun_republish_interval' );
        $slop = $this->get_data( 'wpar_random_republish_interval' );
        $time = time();
        
        if ( false === $last ) {
            $ret = true;
        } elseif ( is_numeric( $last ) ) {
            
            if ( $time - $last > $interval + mt_rand( 0, $slop ) ) {
                $ret = true;
            } else {
                $ret = false;
            }
        
        }
        
        return $ret;
    }
    
    /**
     * Custom post type support.
     *
     * @param object $post WP Post object.
     */
    private function custom_post_types_events( $post )
    {
        $repeat = $this->get_meta( $post->ID, '_wpar_post_republish_occurrence' );
        
        if ( !empty($repeat) && is_numeric( $repeat ) ) {
            $repeat++;
        } else {
            $repeat = 1;
        }
        
        if ( !in_array( $post->post_type, [ 'post', 'page', 'attachment' ] ) ) {
            $this->update_meta( $post->ID, '_wpar_post_republish_occurrence', $repeat );
        }
    }

}