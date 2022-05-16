<?php

/**
 * Fetch eligible posts.
 *
 * @since      1.2.0
 * @package    RevivePress
 * @subpackage RevivePress\Core
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Core;

use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\Schedular ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class FetchPosts
{
    use  HelperFunctions, Hooker, Schedular ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'init', 'generate_task' );
        $this->action( 'wpar/global_republish_flat_posts', 'query_posts' );
        $this->action( 'wpar/global_republish_flat_posts_completed', 'complete' );
    }
    
    /**
     * Generate Action event if not already exists.
     */
    public function generate_task()
    {
        $transient_name = $this->get_daily_allowed();
        if ( false === get_transient( $transient_name ) ) {
            set_transient( $transient_name, [], DAY_IN_SECONDS );
        }
        if ( $this->is_enabled( 'enable_plugin', true ) ) {
            $this->check_and_create_tasks();
        }
    }
    
    /**
     * Run post fetching process.
     */
    public function check_and_create_tasks()
    {
        
        if ( $this->valid_next_run() ) {
            update_option( 'wpar_last_global_cron_run', $this->current_timestamp() );
            $this->create_tasks();
        }
    
    }
    
    /**
     * Get eligible posts.
     */
    private function create_tasks()
    {
        $post_types = $this->get_data( 'wpar_post_types', [ 'post' ] );
        
        if ( !empty($post_types) ) {
            $counter = 0;
            foreach ( $post_types as $post_type ) {
                
                if ( !$this->has_future_posts( $post_type ) ) {
                    $counter++;
                    $this->set_single_action( time() + 30 * ($counter / 2), 'wpar/global_republish_flat_posts', [ $post_type ] );
                }
            
            }
            // Check for posts.
            $this->set_single_action( time() + 30 * (($counter + 1) / 2), 'wpar/global_republish_flat_posts_completed' );
        }
    
    }
    
    /**
     * Get eligible post ids for every available post types
     *
     * @param string $post_type WordPress post types
     */
    public function query_posts( $post_type )
    {
        $timestamp = $this->current_timestamp();
        $tax_filter = $this->get_data( 'wpar_exclude_by_type', 'none' );
        $taxonomies = $this->get_data( 'wpar_post_taxonomy', [] );
        $post_age = $this->get_data( 'wpar_republish_post_age', 120 );
        $orderby = $this->get_data( 'wpar_republish_orderby', 'date' );
        $order = $this->get_data( 'wpar_republish_method', 'old_first' );
        $post_age_seconds = $post_age * DAY_IN_SECONDS;
        $cats = $tags = $terms = [];
        $args = [
            'post_status' => 'publish',
            'post_type'   => $post_type,
            'numberposts' => 5,
            'orderby'     => $orderby,
            'fields'      => 'ids',
        ];
        
        if ( !empty($order) ) {
            $args['order'] = 'ASC';
            if ( $order == 'new_first' ) {
                $args['order'] = 'DESC';
            }
        }
        
        if ( $args['orderby'] != 'date' ) {
            $args['orderby'] = 'date';
        }
        
        if ( isset( $post_age_seconds ) ) {
            $before_date = gmdate( 'Y-m-d H:i:s', strtotime( "-{$post_age_seconds} seconds", $timestamp ) );
            $args['date_query'][]['before'] = $this->do_filter( 'post_before_date', $before_date, $timestamp );
        }
        
        
        if ( !in_array( $post_type, [ 'post', 'page', 'attachment' ] ) ) {
            $args['meta_query'] = [
                'relation' => 'AND',
                [
                'key'     => 'wpar_global_republish_status',
                'compare' => 'NOT EXISTS',
            ],
                [
                'key'     => '_wpar_post_republish_occurrence',
                'compare' => 'NOT EXISTS',
            ],
            ];
        } else {
            $args['meta_query'] = [ [
                'key'     => 'wpar_global_republish_status',
                'compare' => 'NOT EXISTS',
            ] ];
        }
        
        
        if ( $tax_filter != 'none' && !empty($taxonomies) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $get_item = explode( '|', $taxonomy );
                $type = $get_item[0];
                $taxonomy_name = $get_item[1];
                $term_id = $get_item[2];
                if ( $post_type === $type && is_object_in_taxonomy( $post_type, $taxonomy_name ) ) {
                    
                    if ( $taxonomy_name == 'category' ) {
                        $cats[] = $term_id;
                    } elseif ( $taxonomy_name == 'post_tag' ) {
                        $tags[] = $term_id;
                    } else {
                    }
                
                }
            }
            
            if ( $tax_filter == 'include' ) {
                if ( !empty($cats) ) {
                    $args['category__in'] = wp_parse_id_list( $cats );
                }
                if ( !empty($tags) ) {
                    $args['tag__in'] = wp_parse_id_list( $tags );
                }
            } elseif ( $tax_filter == 'exclude' ) {
                if ( !empty($cats) ) {
                    $args['category__not_in'] = wp_parse_id_list( $cats );
                }
                if ( !empty($tags) ) {
                    $args['tag__not_in'] = wp_parse_id_list( $tags );
                }
            }
        
        }
        
        $args = $this->do_filter( 'query_args', $args, $post_type );
        //error_log( print_r( $args, true ) );
        // get posts
        $post_ids = $this->get_posts( $args );
        // store post ids
        $this->store_post_ids( $post_ids, $post_type );
    }
    
    /**
     * Complete
     */
    public function complete()
    {
        $timestamp = $this->current_timestamp();
        $post_types = $this->get_data( 'wpar_post_types', [ 'post' ] );
        $number_posts = $this->do_filter( 'number_of_posts', 1 );
        $orderby = $this->get_data( 'wpar_republish_orderby', 'date' );
        $order = $this->get_data( 'wpar_republish_method', 'old_first' );
        $include_ids = $this->filter_post_ids( $this->get_data( 'force_include' ) );
        $exclude_ids = $this->filter_post_ids( $this->get_data( 'wpar_override_category_tag' ) );
        $post_ids = get_option( 'wpar_global_republish_post_ids' );
        if ( !$post_ids || !is_array( $post_ids ) ) {
            $post_ids = [];
        }
        
        if ( !empty($post_ids) ) {
            if ( !empty($include_ids) ) {
                $post_ids = array_merge( $post_ids, $include_ids );
            }
            if ( !empty($exclude_ids) ) {
                $post_ids = array_diff( $post_ids, $exclude_ids );
            }
            $args = [
                'post_type'   => $post_types,
                'post_status' => 'publish',
                'post__in'    => wp_parse_id_list( $post_ids ),
                'numberposts' => $number_posts,
                'orderby'     => $orderby,
                'fields'      => 'ids',
            ];
            
            if ( !empty($order) ) {
                $args['order'] = 'ASC';
                if ( $order == 'new_first' ) {
                    $args['order'] = 'DESC';
                }
            }
            
            if ( $args['orderby'] != 'date' ) {
                $args['orderby'] = 'date';
            }
            $args = $this->do_filter( 'post_query_args', $args );
            //error_log( print_r( $args, true ) );
            // get the required date time
            $datetime = $this->next_schedule( $timestamp, 'local' );
            $schedule = get_gmt_from_date( $datetime, 'U' );
            $filtered_post_ids = $this->do_filter( 'filtered_post_ids', $this->get_posts( $args ) );
            
            if ( !empty($filtered_post_ids) ) {
                $counter = 0;
                foreach ( $filtered_post_ids as $key => $post_id ) {
                    
                    if ( $key > 0 ) {
                        $counter++;
                        $schedule = $schedule + 30 * ($counter / 2);
                    }
                    
                    // delete previosly scheduled hook if exists any.
                    $this->unschedule_all_actions( 'wpar/global_republish_single_post', [ $post_id ] );
                    // schedule single post republish event
                    $this->set_single_action( $schedule, 'wpar/global_republish_single_post', [ $post_id ] );
                    // update required post metas
                    $this->update_meta( $post_id, 'wpar_global_republish_status', 'pending' );
                    $this->update_meta( $post_id, '_wpar_global_republish_datetime', $datetime );
                    // update reference
                    $this->set_limit( $post_id );
                }
            }
        
        }
        
        // delete temp storage
        delete_option( 'wpar_global_republish_post_ids' );
    }
    
    /**
     * Generate Single cron time.
     * 
     * @param int     $timestamp Local Timestamp
     * @param array   $weekdays  Available weekdays
     * @param string  $format    Datetime format
     * 
     * @return int|string  Generated UTC timestamp
     */
    private function next_schedule( $timestamp, $format = 'GMT' )
    {
        $current_date = gmdate( 'Y-m-d', $timestamp );
        $slop = $this->get_data( 'wpar_random_republish_interval', 14400 );
        $timestamp = $timestamp + wp_rand( 30, $slop );
        $time_based = $this->get_data( 'republish_time_specific', 'no' );
        
        if ( $time_based == 'yes' ) {
            $start_time_input = $this->get_data( 'wpar_start_time', '05:00:00' );
            $end_time_input = $this->get_data( 'wpar_end_time', '23:59:59' );
            $start_time = strtotime( $start_time_input );
            $end_time = strtotime( $end_time_input );
            
            if ( $start_time <= $end_time ) {
                $start_time = strtotime( $current_date . ' ' . $start_time_input );
                $end_time = strtotime( $current_date . ' ' . $end_time_input );
            } else {
                $start_time = strtotime( $current_date . ' ' . $start_time_input );
                $end_time = strtotime( '+1 day', strtotime( $current_date . ' ' . $end_time_input ) );
            }
            
            
            if ( $timestamp >= $start_time && $timestamp <= $end_time ) {
                $final_timestamp = $timestamp;
            } else {
                $rand_time = $start_time + wp_rand( 300, 900 );
                $final_timestamp = strtotime( gmdate( 'Y-m-d', $timestamp ) . ' ' . gmdate( 'H:i:s', $rand_time ) );
            }
        
        } else {
            $final_timestamp = $timestamp;
        }
        
        $weekdays = $this->get_data( 'wpar_days', [
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat'
        ] );
        
        if ( !in_array( lcfirst( gmdate( 'D', $final_timestamp ) ), $weekdays ) ) {
            $i = 1;
            while ( $i <= 7 ) {
                $next_timestamp = strtotime( '+' . $i . ' days', $final_timestamp );
                $next_date = lcfirst( gmdate( 'D', $next_timestamp ) );
                if ( in_array( $next_date, $weekdays ) ) {
                    break;
                }
                $i++;
            }
            $final_timestamp = $next_timestamp;
        }
        
        $formatted_date = gmdate( 'Y-m-d H:i:s', $final_timestamp );
        if ( $format == 'local' ) {
            return $formatted_date;
        }
        return get_gmt_from_date( $formatted_date, 'U' );
    }
    
    /**
     * Check if current run is actually eligible.
     */
    private function valid_next_run()
    {
        $last = get_option( 'wpar_last_global_cron_run' );
        $current_time = $this->current_timestamp();
        $interval = $this->get_data( 'wpar_minimun_republish_interval', 3600 );
        $proceed = false;
        // switch
        if ( $this->slot_available() ) {
            
            if ( false === $last ) {
                $proceed = true;
            } elseif ( is_numeric( $last ) ) {
                if ( $current_time - $last >= $interval ) {
                    $proceed = true;
                }
            }
        
        }
        return $proceed;
    }
    
    /**
     * Check if weekdays are available.
     * 
     * @return bool
     */
    private function slot_available()
    {
        $timestamp = $this->current_timestamp();
        $weekdays = $this->get_data( 'wpar_days' );
        $next_date = strtolower( gmdate( 'D', $timestamp ) );
        $available = false;
        if ( !empty($weekdays) && in_array( $next_date, $weekdays ) ) {
            $available = true;
        }
        $time_based = $this->get_data( 'republish_time_specific', 'no' );
        
        if ( $available && $time_based == 'yes' ) {
            $start_time_input = $this->get_data( 'wpar_start_time', '05:00:00' );
            $end_time_input = $this->get_data( 'wpar_end_time', '23:59:59' );
            $current_date = gmdate( 'Y-m-d', $timestamp );
            $start_time = strtotime( $start_time_input );
            $end_time = strtotime( $end_time_input );
            
            if ( $start_time <= $end_time ) {
                $start_time = strtotime( $current_date . ' ' . $start_time_input );
                $end_time = strtotime( $current_date . ' ' . $end_time_input );
            } else {
                $start_time = strtotime( $current_date . ' ' . $start_time_input );
                $end_time = strtotime( '+1 day', strtotime( $current_date . ' ' . $end_time_input ) );
            }
            
            $available = false;
            if ( $timestamp >= $start_time && $timestamp <= $end_time ) {
                $available = true;
            }
        }
        
        return $available;
    }
    
    /**
     * Check if has any future posts.
     * 
     * @since 1.1.7
     */
    private function has_future_posts( $post_type )
    {
        $can_check = $this->do_filter( 'has_future_post_check', false, $post_type );
        if ( !$can_check ) {
            return false;
        }
        // cureent timestmap
        $timestamp = $this->current_timestamp();
        // get future posts
        $posts = $this->do_filter( 'has_future_post_args', $this->get_posts( [
            'numberposts' => -1,
            'post_type'   => $post_type,
            'sort_order'  => 'ASC',
            'post_status' => 'future',
            'fields'      => 'ids',
            'date_query'  => [
            'year'  => gmdate( 'Y', $timestamp ),
            'month' => gmdate( 'n', $timestamp ),
            'day'   => gmdate( 'j', $timestamp ),
        ],
        ] ), $post_type );
        if ( !empty($posts) && count( $posts ) > 0 ) {
            return true;
        }
        return false;
    }
    
    /**
     * Store Post IDs
     * 
     * @since 1.3.0
     */
    private function store_post_ids( $ids, $post_type )
    {
        $post_ids = get_option( 'wpar_global_republish_post_ids' );
        if ( !$post_ids || !is_array( $post_ids ) ) {
            $post_ids = [];
        }
        $post_ids = $this->do_filter( 'post_ids_before_store', $post_ids, $post_type );
        update_option( 'wpar_global_republish_post_ids', wp_parse_id_list( array_merge( $post_ids, $ids ) ) );
    }
    
    /**
     * Set per day limit
     * 
     * @since 1.3.0
     */
    private function set_limit( $post_id )
    {
        $transient_name = $this->get_daily_allowed();
        $transient = get_transient( $transient_name );
        if ( !$transient ) {
            $transient = [];
        }
        $transient[] = $post_id;
        set_transient( $transient_name, $transient, DAY_IN_SECONDS );
    }

}