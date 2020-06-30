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
use  Wpar\Helpers\Logger ;
use  Wpar\Helpers\SettingsData ;
use  Wpar\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class PostRepublish
{
    use 
        HelperFunctions,
        Hooker,
        Logger,
        SettingsData
    ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'init', 'republish_init' );
        $this->filter( 'cron_schedules', 'global_cron_schedules' );
        $this->action( 'wpar/run_global_republish', 'run_republish_process' );
        $this->action( 'wpar/global_republish_single_post', 'trigger_republish' );
        $this->action( 'wp_ajax_wpar_process_clear_cron', 'clear_cron' );
    }
    
    /**
     * Create custom WP Cron intervals.
     */
    public function global_cron_schedules( $schedules )
    {
        $schedules['wpar_global_cron'] = [
            'interval' => $this->do_filter( 'global_cron_custom_interval', $this->get_data( 'wpar_minimun_republish_interval', 43200 ) ),
            'display'  => __( 'Global Republish' ),
        ];
        return $schedules;
    }
    
    /**
     * Initialize auto republish.
     */
    public function republish_init()
    {
        
        if ( $this->check_global_republish_enabled() ) {
            
            if ( !wp_next_scheduled( 'wpar/run_global_republish' ) ) {
                wp_schedule_event( time(), 'wpar_global_cron', 'wpar/run_global_republish' );
                // stop 1st republish
                set_transient( 'wpar_global_republish_done', true, 10 );
            }
        
        } else {
            if ( wp_next_scheduled( 'wpar/run_global_republish' ) ) {
                wp_clear_scheduled_hook( 'wpar/run_global_republish' );
            }
            $this->delete_scheduled_cron();
        }
    
    }
    
    /**
     * Run auto republish process.
     */
    public function run_republish_process()
    {
        $wpar_days = $this->get_data( 'wpar_days' );
        $timestamp = current_time( 'timestamp', 0 );
        $day = lcfirst( date( 'D', $timestamp ) );
        $cur_time = strtotime( date( 'H:i:s', $timestamp ) );
        $start_time = strtotime( $this->get_data( 'wpar_start_time', '05:00:00' ) );
        $end_time = strtotime( $this->get_data( 'wpar_end_time', '23:59:59' ) );
        if ( !empty($wpar_days) && in_array( $day, $wpar_days ) && !$this->has_future_posts( $timestamp ) ) {
            if ( $cur_time >= $start_time && $cur_time <= $end_time ) {
                if ( $this->do_filter( 'run_global_republish_process', true ) ) {
                    
                    if ( get_transient( 'wpar_global_republish_done' ) === false ) {
                        // run post republish query
                        $this->get_old_posts( $timestamp );
                        // lock republish query
                        set_transient( 'wpar_global_republish_done', true, 10 );
                    }
                
                }
            }
        }
    }
    
    /**
     * Check if has any future posts.
     * 
     * @since v1.1.7
     */
    private function has_future_posts( $timestamp )
    {
        // get future posts
        $posts = get_posts( [
            'numberposts' => -1,
            'sort_order'  => 'ASC',
            'post_status' => 'future',
            'date_query'  => [
            'year'  => date( 'Y', $timestamp ),
            'month' => date( 'm', $timestamp ),
            'day'   => date( 'd', $timestamp ),
        ],
        ] );
        if ( !empty($posts) && count( $posts ) > 0 && $this->do_filter( 'has_future_post_check', false ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Get eligible posts.
     */
    private function get_old_posts( $timestamp )
    {
        $wpar_overwrite = $this->get_data( 'wpar_exclude_by_type', 'none' );
        $wpar_gap = $this->get_data( 'wpar_republish_post_age', 120 );
        $wpar_orderby = $this->get_data( 'wpar_republish_orderby' );
        $wpar_order = $this->get_data( 'wpar_republish_method', 'old_first' );
        $wpar_action = $this->get_data( 'wpar_republish_action', 'repost' );
        
        if ( !empty($this->get_data( 'wpar_post_types' )) ) {
            $post_types = $this->get_data( 'wpar_post_types' );
            if ( is_array( $post_types ) ) {
                $post_types = array_unique( $post_types );
            }
        }
        
        $wpar_filter_taxonomy = $this->get_data( 'wpar_post_taxonomy', [] );
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
                    'relation' => 'AND',
                    [
                    'key'     => '_wpar_global_republish_pending',
                    'compare' => 'NOT EXISTS',
                ],
                    [
                    'relation' => 'OR',
                    [
                    'key'     => '_wpar_post_republish_occurrence',
                    'compare' => 'NOT EXISTS',
                ],
                    [
                    'key'     => '_wpar_post_republish_occurrence',
                    'value'   => '3',
                    'compare' => '<=',
                ],
                ],
                ];
            } else {
                $args['meta_query'] = [ [
                    'key'     => '_wpar_global_republish_pending',
                    'compare' => 'NOT EXISTS',
                ] ];
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
            $posts = get_posts( $args );
            if ( !empty($posts) ) {
                foreach ( $posts as $post ) {
                    
                    if ( $this->do_filter( 'run_global_republish_cron', true, $post->ID ) ) {
                        $wpar_action = $this->do_filter( 'global_republish_action', $wpar_action, $post->ID );
                        
                        if ( !wp_next_scheduled( 'wpar/global_republish_single_post', [ $post->ID, $wpar_action ] ) ) {
                            wp_schedule_single_event( $this->generate_next_schedule( $timestamp ), 'wpar/global_republish_single_post', [ $post->ID, $wpar_action ] );
                            $this->scheduled_cron( $post->ID, $wpar_action );
                            $this->update_meta( $post->ID, '_wpar_global_republish_pending', 'yes' );
                            $this->update_meta( $post->ID, '_wpar_global_republish_datetime', $this->generate_next_schedule( $timestamp, 'local' ) );
                        }
                    
                    }
                    
                    //error_log( $post->ID );
                }
            }
        }
    
    }
    
    /**
     * Generate Single cron time.
     * 
     * @param int   $timestamp  Local Timestamp
     * 
     * @return int  Generated UTC timestamp
     */
    private function generate_next_schedule( $timestamp, $type = 'GMT' )
    {
        $cur_time = strtotime( date( 'H:i:s', $timestamp ) );
        $start_time = strtotime( $this->get_data( 'wpar_start_time', '05:00:00' ) );
        $end_time = strtotime( $this->get_data( 'wpar_end_time', '23:59:59' ) );
        $slop = $this->get_data( 'wpar_random_republish_interval', 14400 );
        $time_diff = $end_time - $cur_time;
        $gap = mt_rand( 0, $slop );
        $datetime = $timestamp + $gap;
        while ( $slop > $time_diff ) {
            $datetime = $timestamp + $gap;
            if ( $time_diff > $gap ) {
                break;
            }
            $gap = mt_rand( 0, $slop );
        }
        if ( $type == 'local' ) {
            return date( 'Y-m-d H:i:s', $datetime );
        }
        return get_gmt_from_date( date( 'Y-m-d H:i:s', $datetime ), 'U' );
    }
    
    /**
     * Run post update process.
     * 
     * @param int   $post_id  Post ID
     * @param bool  $single   Check if it is a single republish event
     * @param bool  $instant  Check if it is one click republish event
     * @param int   $slop     Single Republish Randomness in seconds
     */
    protected function update_old_post(
        $post_id,
        $single = false,
        $instant = false,
        $slop = 300
    )
    {
        $post = get_post( $post_id );
        $timestamp = current_time( 'timestamp', 0 );
        $pub_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
        if ( empty($pub_date) || $post->post_status !== 'future' ) {
            $this->update_meta( $post->ID, '_wpar_original_pub_date', $post->post_date );
        }
        $this->custom_post_types_events( $post );
        $new_time = $this->get_publish_time(
            $post->ID,
            $single,
            $instant,
            $slop
        );
        $args = [
            'ID'            => $post->ID,
            'post_date'     => $new_time,
            'post_date_gmt' => get_gmt_from_date( $new_time ),
        ];
        $args = $this->do_filter(
            'update_process_args',
            $args,
            $post->ID,
            $post
        );
        //error_log( print_r( $args, true ) );
        wp_update_post( $args );
        $this->update_meta( $post_id, '_wpar_republish_meta_query', $new_time );
        $this->delete_meta( $post->ID, '_wpar_global_republish_pending' );
        $this->delete_meta( $post->ID, '_wpar_global_republish_datetime' );
        $this->remove_scheduled_cron( $post->ID );
        $this->do_action( 'clear_site_cache' );
    }
    
    /**
     * Trigger post update process.
     * 
     * @since v1.1.7
     * @param int     $post_id  Post ID
     * @param string  $action   Post Republhs Action
     */
    public function trigger_republish( $post_id, $action = 'repost' )
    {
        
        if ( get_transient( 'wpar_global_republish_single_post_done' ) === false ) {
            if ( $action == 'repost' ) {
                $this->update_old_post( $post_id );
            }
            // lock republish query
            set_transient( 'wpar_global_republish_single_post_done', true, 10 );
        }
    
    }
    
    /**
     * Get new post published time.
     * 
     * @since v1.1.7
     * @param int   $post_id  Post ID
     * @param bool  $single   Check if it is a single republish event
     * @param bool  $instant  Check if it is one click republish event
     * @param int   $slop     Single Republish Randomness in seconds
     * 
     * @return string
     */
    private function get_publish_time(
        $post_id,
        $single,
        $instant,
        $slop
    )
    {
        $post = get_post( $post_id );
        $timestamp = current_time( 'timestamp', 0 );
        
        if ( $this->get_data( 'wpar_republish_post_position', 'one' ) == 'one' ) {
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
        
        return $new_time;
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
    
    /**
     * Cron cleanup on request.
     */
    public function clear_cron()
    {
        // security check
        check_ajax_referer( 'wpar_admin_nonce', 'security' );
        if ( isset( $_POST['global'] ) && sanitize_text_field( $_POST['global'] ) === 'yes' ) {
            if ( wp_next_scheduled( 'wpar/run_global_republish' ) ) {
                wp_clear_scheduled_hook( 'wpar/run_global_republish' );
            }
        }
        wp_send_json_success();
        die;
    }

}