<?php

/**
 * The Main file.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Core;

use  WP_Post ;
use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\Schedular ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class PostRepublish
{
    use  HelperFunctions, Hooker, Schedular ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'wpar/global_republish_single_post', 'do_republish' );
        $this->action( 'wpar/process_republish_post', 'call_republish' );
    }
    
    /**
     * Trigger on external API call.
     * 
     * @since 1.3.2
     * @param array   $args   Republish params
     */
    public function call_republish( array $args )
    {
        $method = $args['method'];
        if ( 'republish' === $method ) {
            $post_id = $this->update_old_post(
                $args['post_id'],
                $args['single'],
                $args['instant'],
                true
            );
        }
    }
    
    /**
     * Trigger post update process.
     * 
     * @since 1.1.7
     * @param int   $post_id   Post ID
     */
    public function do_republish( $post_id )
    {
        // check if given post is not published.
        if ( 'publish' === get_post_status( $post_id ) ) {
            $this->handle( (int) $post_id );
        }
        // delete metas
        $this->delete_meta( $post_id, 'wpar_global_republish_status' );
        $this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
        $this->delete_meta( $post_id, 'wpar_filter_republish_status' );
        $this->delete_meta( $post_id, '_wpar_filter_republish_datetime' );
        $this->delete_meta( $post_id, 'wpar_republish_rule_action' );
    }
    
    /**
     * Handle Trigger post update process.
     *
     * Override this method to perform any actions required
     * during the async request.
     */
    private function handle( int $post_id )
    {
        $action = $this->do_filter(
            'republish_action',
            'repost',
            false,
            $post_id
        );
        if ( $action == 'repost' ) {
            $this->update_old_post( $post_id );
        }
    }
    
    /**
     * Run post update process.
     * 
     * @param int   $post_id  Post ID
     * @param bool  $single   Check if it is a single republish event
     * @param bool  $instant  Check if it is one click republish event
     * @param bool  $external Check if it is external custom event
     * 
     * @return int $post_id
     */
    public function update_old_post(
        int $post_id,
        bool $single = false,
        bool $instant = false,
        bool $external = false
    )
    {
        $post = \get_post( $post_id );
        $pub_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
        if ( !$pub_date && $post->post_status !== 'future' ) {
            $this->update_meta( $post->ID, '_wpar_original_pub_date', $post->post_date );
        }
        $this->update_meta( $post->ID, '_wpar_last_pub_date', $post->post_date );
        $new_time = $this->get_publish_time( $post->ID, $single );
        // remove kses filters
        \kses_remove_filters();
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
        $this->set_occurence( $post );
        $this->do_action( 'clear_site_cache' );
        // reinit kses filters
        \kses_init_filters();
        return $post_id;
    }
    
    /**
     * Get new post published time.
     * 
     * @since 1.1.7
     * @param int   $post_id   Post ID
     * @param bool  $single    Check if a single republish event
     * @param bool  $scheduled Check if scheduled republish event
     * 
     * @return string
     */
    private function get_publish_time( int $post_id, bool $single = false, bool $scheduled = false )
    {
        $post = \get_post( $post_id );
        $timestamp = $this->current_timestamp();
        $interval = MINUTE_IN_SECONDS * $this->do_filter( 'second_position_interval', wp_rand( 1, 15 ) );
        $new_time = current_time( 'mysql' );
        
        if ( $this->get_data( 'wpar_republish_post_position', 'one' ) == 'one' ) {
            $datetime = $this->get_meta( $post_id, '_wpar_global_republish_datetime' );
            if ( !empty($datetime) && $timestamp >= strtotime( $datetime ) ) {
                $new_time = $datetime;
            }
        } else {
            $lastposts = $this->get_posts( [
                'post_type'   => $post->post_type,
                'numberposts' => 1,
                'offset'      => 1,
                'post_status' => 'publish',
                'order'       => 'DESC',
                'orderby'     => 'date',
                'fields'      => 'ids',
            ] );
            if ( !empty($lastposts) ) {
                foreach ( $lastposts as $lastpost ) {
                    $post_date = get_the_date( 'U', $lastpost );
                    $post_date = $post_date + $interval;
                    $new_time = gmdate( 'Y-m-d H:i:s', $post_date );
                }
            }
        }
        
        return $this->do_filter(
            'next_scheduled_timestamp',
            $new_time,
            $post_id,
            $single,
            $scheduled
        );
    }
    
    /**
     * Custom post type support.
     *
     * @param object $post WP Post object.
     */
    private function set_occurence( WP_Post $post )
    {
        $repeat = $this->get_meta( $post->ID, '_wpar_post_republish_occurrence' );
        
        if ( !empty($repeat) && is_numeric( $repeat ) ) {
            $repeat++;
        } else {
            $repeat = 1;
        }
        
        $this->update_meta( $post->ID, '_wpar_post_republish_occurrence', $repeat );
    }

}