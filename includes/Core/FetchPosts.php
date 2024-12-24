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
use  RevivePress\Helpers\Scheduler ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class FetchPosts
{
    use  HelperFunctions ;
    use  Hooker ;
    use  Scheduler ;

    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'init', 'process_start' );
        $this->action( 'wpar/global_republish_flat_posts', 'query_posts' );
        $this->action( 'wpar/global_republish_flat_posts_completed', 'complete' );
        $this->action( 'wpar/process_flat_batches', 'set_schedule' );
    }
    
    /**
     * Generate Action event if not already exists.
     */
    public function process_start()
    {
        if ( ! $this->is_enabled( 'enable_plugin', true ) ) {
            return;
        }
        $timestamp = $this->current_timestamp();
        $last_scheduled = get_option( 'wpar_next_scheduled_timestamp' );
        
        if ( ! $last_scheduled || ! is_numeric( $last_scheduled ) ) {
            $this->schedule_date();
        } else {
            $interval = $this->get_data( 'republish_interval_days', '1' );
            $last_timestamp = strtotime( date( 'Y-m-d', $last_scheduled ) . ' 00:00:00' );
            if ( $timestamp - $last_timestamp > $interval * DAY_IN_SECONDS ) {
                $this->schedule_date();
            }
        }
        
        $timestamp = date( 'd/m/Y', $timestamp );
        $next_date = get_option( 'wpar_next_eligible_date' );
        if ( $next_date && $next_date == $timestamp ) {
            $this->check_and_create_tasks();
        }
    }
    
    /**
     * Save the date if republish is possible on that date.
     */
    public function schedule_date()
    {
        $timestamp = $this->current_timestamp();
        $auto_forward = $this->do_filter( 'enable_auto_forward', true );
        if ( false === $auto_forward ) {
            update_option( 'wpar_next_scheduled_timestamp', $timestamp, false );
        }
        $weekdays = $this->get_data( 'wpar_days', array(
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat',
        ) );
        
        if ( in_array( lcfirst( date( 'D', $timestamp ) ), $weekdays, true ) ) {
            update_option( 'wpar_next_scheduled_timestamp', $timestamp, false );
            update_option( 'wpar_next_eligible_date', date( 'd/m/Y', $timestamp ), false );
        } else {
            delete_option( 'wpar_next_eligible_date' );
        }
    }
    
    /**
     * Run post fetching process.
     */
    public function check_and_create_tasks()
    {
        $in_process = get_transient( 'wpar_in_progress' );
        
        if ( $this->valid_next_run() && ! $in_process ) {
            // Lock the process to prevent duplicate schedules for 30 seconds.
            set_transient( 'wpar_in_progress', true, 30 );
            // Update timestamp reference.
            update_option( 'wpar_last_global_cron_run', $this->current_timestamp(), false );
            // Create Tasks.
            $this->create_tasks();
        }
    }
    
    /**
     * Get eligible posts.
     */
    private function create_tasks()
    {
        $post_types = $this->get_data( 'wpar_post_types', array( 'post' ) );
        $counter = 0;
        // delete storage if exists.
        delete_option( 'wpar_global_republish_post_ids' );
        if ( ! empty($post_types) ) {
            foreach ( $post_types as $post_type ) {
                
                if ( ! $this->has_future_posts( $post_type ) ) {
                    ++$counter;
                    $this->schedule_single_action( time() + 10 * ($counter / 2), 'wpar/global_republish_flat_posts', array( $post_type ) );
                }            
}
        }
        // Check for posts.
        
        if ( $counter > 0 ) {
            $this->schedule_single_action( time() + 10 * (($counter + 1) / 2), 'wpar/global_republish_flat_posts_completed' );
        } else {
            delete_transient( 'wpar_in_progress' );
        }
    }
    
    /**
     * Get eligible post ids for every available post types
     *
     * @param string $post_type WordPress post types
     */
    public function query_posts( string $post_type )
    {
        $timestamp = $this->current_timestamp();
        $tax_filter = $this->get_data( 'wpar_exclude_by_type', 'none' );
        $taxonomies = $this->get_data( 'wpar_post_taxonomy', array() );
        $post_age = $this->get_data( 'wpar_republish_post_age', 120 );
        $orderby = $this->get_data( 'wpar_republish_orderby', 'date' );
        $order = $this->get_data( 'wpar_republish_method', 'old_first' );
        $post_age_seconds = $post_age * DAY_IN_SECONDS;
        $args = array(
            'post_status' => 'publish',
            'post_type'   => $post_type,
            'numberposts' => -1,
            'orderby'     => $orderby,
            'fields'      => 'ids',
            'meta_query'  => array(
				array(
					'key'     => 'wpar_global_republish_status',
					'compare' => 'NOT EXISTS',
				),
			),
        );
        if ( ! empty($order) ) {
            $args['order'] = ( $order == 'new_first' ? 'DESC' : 'ASC' );
        }
        
        if ( isset( $post_age_seconds ) ) {
            $before_date = date( 'Y-m-d H:i:s', strtotime( "-{$post_age_seconds} seconds", $timestamp ) );
            $args['date_query'][0]['before'] = $this->do_filter( 'post_before_date', $before_date, $timestamp );
        }
        
        $cats = $tags = $terms = array();
        
        if ( $tax_filter != 'none' && ! empty($taxonomies) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $taxonomy_data = $this->process_taxonomy( $taxonomy );
                $taxonomy_name = $taxonomy_data[0];
                $term_id = $taxonomy_data[1];
                if ( is_object_in_taxonomy( $post_type, $taxonomy_name ) ) {
                    
                    if ( 'category' === $taxonomy_name ) {
                        $cats[] = $term_id;
                    } elseif ( 'post_tag' === $taxonomy_name ) {
                        $tags[] = $term_id;
                    } elseif ( revivepress_fs()->can_use_premium_code__premium_only() ) {
                        $terms[ $taxonomy_name ][] = $term_id;
                    }                
}
            }
            if ( ! empty($cats) ) {
                
                if ( 'include' === $tax_filter ) {
                    $args['category__in'] = wp_parse_id_list( $cats );
                } else {
                    $args['category__not_in'] = wp_parse_id_list( $cats );
                }            
}
            if ( ! empty($tags) ) {
                
                if ( 'include' === $tax_filter ) {
                    $args['tag__in'] = wp_parse_id_list( $tags );
                } else {
                    $args['tag__not_in'] = wp_parse_id_list( $tags );
                }            
}
        }
        
        $args = $this->do_filter( 'query_args', $args, $post_type );
        // get posts
        $post_ids = $this->get_posts( $args );
        // store post ids
        $this->store_post_ids( $post_ids, $post_type );
    }
    
    /**
     * Complete republish tasks.
     */
    public function complete()
    {
        $timestamp = $this->current_timestamp();
        $post_types = $this->get_data( 'wpar_post_types', array( 'post' ) );
        $number_posts = $this->get_data( 'number_of_posts', 1 );
        $orderby = $this->get_data( 'wpar_republish_orderby', 'date' );
        $order = $this->get_data( 'wpar_republish_method', 'old_first' );
        $include_ids = $this->filter_post_ids( $this->get_data( 'force_include' ) );
        $exclude_ids = $this->filter_post_ids( $this->get_data( 'wpar_override_category_tag' ) );
        $post_ids = get_option( 'wpar_global_republish_post_ids' );
        if ( ! $post_ids || ! is_array( $post_ids ) ) {
            $post_ids = array();
        }
        
        if ( ! empty($post_ids) ) {
            if ( ! empty($include_ids) ) {
                $post_ids = array_merge( $post_ids, $include_ids );
            }
            if ( ! empty($exclude_ids) ) {
                $post_ids = array_diff( $post_ids, $exclude_ids );
            }
            $args = array(
                'post_type'   => $post_types,
                'post_status' => 'any',
                'post__in'    => wp_parse_id_list( $post_ids ),
                'numberposts' => $this->do_filter( 'number_of_posts', $number_posts ),
                'orderby'     => $orderby,
                'fields'      => 'ids',
            );
            if ( ! empty($order) ) {
                $args['order'] = ( $order == 'new_first' ? 'DESC' : 'ASC' );
            }
            $args = $this->do_filter( 'post_query_args', $args );
            $post_ids = $this->do_filter( 'filtered_post_ids', $this->get_posts( $args ) );
            $this->schedule_batch_actions( $post_ids, 'wpar/process_flat_batches' );
        }
        
        // delete temp storage
        delete_option( 'wpar_global_republish_post_ids' );
        // remove transient
        delete_transient( 'wpar_in_progress' );
    }
    
    /**
     * Generate Single cron time.
     * 
     * @since 1.3.4
     * @param array   $post_ids  Post IDs
     */
    public function set_schedule( array $post_ids )
    {
        $timestamp = $this->current_timestamp();
        $utc_timestamp_raw = $this->next_schedule( $timestamp );
        $utc_timestamp = $utc_timestamp_raw;
        $counter = 0;
        foreach ( $post_ids as $key => $post_id ) {
            
            if ( $key > 0 ) {
                ++$counter;
                $utc_timestamp = $utc_timestamp_raw + 30 * ($counter / 2);
            }
            
            // delete previosly scheduled hook if exists any.
            $this->unschedule_all_actions( 'wpar/global_republish_single_post', array( $post_id ) );
            // schedule single post republish event
            $action_id = $this->schedule_single_action( $utc_timestamp, 'wpar/global_republish_single_post', array( $post_id ) );
            // Convert to local timestamp
            $local_datetime = get_date_from_gmt( date( 'Y-m-d H:i:s', $utc_timestamp ) );
            // update required post metas
            $this->update_meta( $post_id, 'wpar_global_republish_status', 'pending' );
            $this->update_meta( $post_id, '_wpar_global_republish_datetime', $local_datetime );
            // store action id
            $this->update_meta( $post_id, 'wpar_republish_as_action_id', $action_id );
            // update reference
            $this->set_limit( $post_id );
        }
    }
    
    /**
     * Generate Single cron time.
     * 
     * @param int     $timestamp Local Timestamp
     * @param string  $format    Datetime format
     * 
     * @return int|string  Generated UTC timestamp
     */
    private function next_schedule( int $timestamp, string $format = 'GMT' )
    {
        $slop = $this->get_data( 'wpar_random_republish_interval', 3600 );
        $timestamp = $timestamp + wp_rand( 30, $slop );
        $formatted_date = date( 'Y-m-d H:i:s', $timestamp );
        return ( 'local' === $format ? $formatted_date : get_gmt_from_date( $formatted_date, 'U' ) );
    }
    
    /**
     * Check if current run is actually eligible.
     */
    private function get_interval()
    {
        $interval = $this->get_data( 'wpar_minimun_republish_interval', 3600 );
        return ( $interval >= 86400 ? 3600 : $interval );
    }
    
    /**
     * Check if current run is actually eligible.
     */
    private function valid_next_run()
    {
        $last = get_option( 'wpar_last_global_cron_run' );
        $current_time = $this->current_timestamp();
        $interval = $this->get_interval();
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
        $weekdays = $this->get_data( 'wpar_days', array(
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat',
        ) );
        if ( ! in_array( lcfirst( date( 'D', $timestamp ) ), $weekdays, true ) ) {
            return false;
        }
        $time_based = $this->get_data( 'republish_time_specific', 'no' );
        $available = true;
        
        if ( $time_based == 'yes' ) {
            $start_time_input = $this->get_data( 'wpar_start_time', '05:00:00' );
            $end_time_input = $this->get_data( 'wpar_end_time', '23:59:59' );
            $current_date = date( 'Y-m-d', $timestamp );
            $current_timestamp = strtotime( date( 'H:i:s', $timestamp ) );
            $start_time = strtotime( $start_time_input );
            $end_time = strtotime( $end_time_input );
            $available = false;
            
            if ( $start_time <= $end_time ) {
                if ( $timestamp >= $start_time && $timestamp <= $end_time ) {
                    $available = true;
                }
            } else {
                $day_start = strtotime( '00:00:00' );
                $day_end = strtotime( '23:59:59' );
                
                if ( $current_timestamp >= $day_start && $current_timestamp <= $end_time ) {
                    $available = true;
                } elseif ( $current_timestamp >= $start_time && $current_timestamp <= $day_end ) {
                    $available = true;
                }            
}        
}
        
        return $available;
    }
    
    /**
     * Check if has any future posts.
     * 
     * @since 1.1.7
     */
    private function has_future_posts( string $post_type )
    {
        $can_check = $this->do_filter( 'has_future_post_check', false, $post_type );
        if ( ! $can_check ) {
            return false;
        }
        // current timestamp
        $timestamp = $this->current_timestamp();
        $args = $this->do_filter( 'has_future_post_args', array(
            'numberposts' => -1,
            'post_type'   => $post_type,
            'sort_order'  => 'ASC',
            'post_status' => 'future',
            'fields'      => 'ids',
            'date_query'  => array(
				'year'  => date( 'Y', $timestamp ),
				'month' => date( 'n', $timestamp ),
				'day'   => date( 'j', $timestamp ),
			),
        ), $post_type );
        // get future posts
        $posts = $this->get_posts( $args );
        if ( ! empty($posts) && count( $posts ) > 0 ) {
            return true;
        }
        return false;
    }
    
    /**
     * Store Post IDs
     * 
     * @since 1.3.0
     */
    private function store_post_ids( array $ids, string $post_type )
    {
        $post_ids = get_option( 'wpar_global_republish_post_ids' );
        if ( ! $post_ids || ! is_array( $post_ids ) ) {
            $post_ids = array();
        }
        $post_ids = $this->do_filter( 'post_ids_before_store', $post_ids, $post_type );
        update_option( 'wpar_global_republish_post_ids', \wp_parse_id_list( array_merge( $post_ids, $ids ) ), false );
    }
    
    /**
     * Set per day limit
     * 
     * @since 1.3.0
     */
    private function set_limit( int $post_id )
    {
        $timestamp = $this->current_timestamp();
        $transient_name = 'wpar_daily_' . date( 'Y_m_d', $timestamp );
        $numbers_proceed = get_transient( $transient_name );
        if ( ! $numbers_proceed ) {
            $numbers_proceed = array();
        }
        $numbers_proceed[] = $post_id;
        set_transient( $transient_name, $numbers_proceed, DAY_IN_SECONDS );
    }
}