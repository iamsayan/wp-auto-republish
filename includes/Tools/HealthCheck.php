<?php

/**
 * The file for Cron Health check.
 *
 * @since      1.2.2
 * @package    RevivePress
 * @subpackage Wpar\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace Wpar\Tools;

use  Wpar\Helpers\Hooker ;
use  Wpar\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Health check class.
 */
class HealthCheck
{
    use  HelperFunctions, Hooker ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'init', 'generate_task' );
        $this->action( 'wpar/process_health_check', 'do_health_check' );
    }
    
    /**
     * Initialize health check tasks.
     */
    public function generate_task()
    {
        $interval = $this->do_filter( 'health_check_cron_interval', 30 );
        if ( !$this->has_next_action( 'wpar/process_health_check' ) ) {
            $this->set_recurring_action( strtotime( '+30 minutes' ), MINUTE_IN_SECONDS * $interval, 'wpar/process_health_check' );
        }
    }
    
    /**
     * Run the event once.
     */
    public function do_health_check()
    {
        // global republish
        $this->regenerate_task( $this->get_data( 'wpar_post_types', [ 'post' ] ) );
        $this->remove_metas( $this->get_data( 'wpar_post_types', [ 'post' ] ) );
    }
    
    /**
     * Re-Generate missed events.
     * 
     * @param array   $post_types  Available Post Types
     * @param string  $type        Cron Type
     * @param bool    $single      Single Cron
     */
    private function regenerate_task( $post_types, $single = false )
    {
        $type = ( $single ? 'single' : 'global' );
        $key = '_wpar_global_republish_datetime';
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields'      => 'ids',
            'meta_query'  => [
            'relation' => 'AND',
            [
            'key'     => 'wpar_' . $type . '_republish_status',
            'compare' => 'EXISTS',
        ],
            [
            'key'     => $key,
            'value'   => current_time( 'mysql' ),
            'compare' => '>',
            'type'    => 'DATETIME',
        ],
        ],
        ];
        $args = $this->do_filter( $type . '_health_check_args', $args );
        //error_log( print_r( $args, true ) );
        $posts = get_posts( $args );
        if ( !empty($posts) ) {
            foreach ( $posts as $post_id ) {
                if ( !$single ) {
                    // check if global cron event is not exists
                    
                    if ( !$this->get_next_action( 'wpar/global_republish_single_post', [ $post_id ] ) ) {
                        // get republish time from post meta
                        $datetime = $this->get_meta( $post_id, '_wpar_global_republish_datetime' );
                        // schedule single post republish event
                        $this->set_single_action( get_gmt_from_date( $datetime, 'U' ), 'wpar/global_republish_single_post', [ $post_id ] );
                    }
                
                }
            }
        }
    }
    
    /**
     * Delete missed events post metas and publish them.
     * 
     * @param array   $post_types  Available Post Types
     * @param string  $type        Action Type
     * @param bool    $single      Single Event
     */
    private function remove_metas( $post_types, $single = false )
    {
        $type = ( $single ? 'single' : 'global' );
        $key = '_wpar_global_republish_datetime';
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields'      => 'ids',
            'meta_query'  => [
            'relation' => 'AND',
            [
            'key'     => 'wpar_' . $type . '_republish_status',
            'compare' => 'EXISTS',
        ],
            [
            'key'     => $key,
            'value'   => current_time( 'mysql' ),
            'compare' => '<=',
            'type'    => 'DATETIME',
        ],
        ],
        ];
        $args = $this->do_filter( $type . '_remove_metas_args', $args );
        //error_log( print_r( $args, true ) );
        $posts = get_posts( $args );
        if ( !empty($posts) ) {
            foreach ( $posts as $post_id ) {
                if ( !$single ) {
                    // check if global cron event is not exists
                    
                    if ( !$this->get_next_action( 'wpar/global_republish_single_post', [ $post_id ] ) ) {
                        // delete old post meta
                        $this->delete_meta( $post_id, 'wpar_global_republish_status' );
                        $this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
                    }
                
                }
            }
        }
    }

}