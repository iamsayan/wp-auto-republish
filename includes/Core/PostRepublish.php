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
use  RevivePress\Helpers\Scheduler ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class PostRepublish
{
    use  HelperFunctions ;
    use  Hooker ;
    use  Scheduler ;

    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'wpar/global_republish_single_post', 'do_republish' );
        $this->action( 'wpar/process_republish_post', 'call_republish' );
        $this->action( 'wpar/as_action_removed', 'remove_meta' );
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
                false,
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
        // delete data.
        $this->delete_meta( $post_id, 'wpar_global_republish_status' );
        $this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
        $this->remove_meta( $post_id );
        // Republish.
        $this->handle( (int) $post_id );
    }
    
    /**
     * Delete post meta data flags.
     * 
     * @since 1.5.1
     * @param int   $post_id   Post ID
     */
    public function remove_meta( $post_id )
    {
        $this->delete_meta( $post_id, 'wpar_republish_as_action_id' );
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
        if ( $action === 'repost' ) {
            $this->update_old_post( $post_id );
        }
    }
    
    /**
     * Run post update process.
     * 
     * @param int   $post_id        Post ID
     * @param bool  $single         Check if it is a single republish event
     * @param bool  $instant        Check if it is one click republish event
     * @param bool  $only_update    Check if it is update date/time event
     * @param bool  $external       Check if it is external custom event
     * 
     * @return int $post_id
     */
    public function update_old_post(
        int $post_id,
        bool $single = false,
        bool $instant = false,
        bool $only_update = false,
        bool $external = false
    )
    {
        $post = \get_post( $post_id );
        $new_time = $this->get_publish_time( $post->ID, $single );
        
        if ( ! $only_update ) {
            $pub_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
            if ( ! $pub_date && $post->post_status !== 'future' ) {
                $this->update_meta( $post->ID, '_wpar_original_pub_date', $post->post_date );
            }
            $this->update_meta( $post->ID, '_wpar_last_pub_date', $post->post_date );
        }
        
        // remove kses filters
        \kses_remove_filters();
        $args = array(
            'post_date'     => $new_time,
            'post_date_gmt' => get_gmt_from_date( $new_time ),
        );
        $args = array_merge( array(
            'ID' => $post->ID,
        ), $args );
        $args = $this->do_filter(
            'update_process_args',
            $args,
            $post->ID,
            $post
        );
        wp_update_post( $args );
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
            if ( ! empty($datetime) && $timestamp >= strtotime( $datetime ) ) {
                $new_time = $datetime;
            }
        } else {
            $args = (array) $this->do_filter( 'republish_position_args', array(
                'post_type'   => $post->post_type,
                'numberposts' => 1,
                'offset'      => 1,
                'post_status' => 'publish',
                'order'       => 'DESC',
                'orderby'     => 'date',
                'fields'      => 'ids',
            ), $post );
            $lastposts = $this->get_posts( $args );
            if ( ! empty($lastposts) ) {
                foreach ( $lastposts as $lastpost ) {
                    $post_date = get_the_date( 'U', $lastpost );
                    $post_date = $post_date + $interval;
                    $new_time = date( 'Y-m-d H:i:s', $post_date );
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
}