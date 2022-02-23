<?php

/**
 * Misc Action links.
 *
 * @since      1.2.0
 * @package    RevivePress
 * @subpackage Wpar\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace Wpar\Base;

use  Wpar\Helpers\Hooker ;
use  Wpar\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Misc Action links class.
 */
class MiscActions
{
    use  HelperFunctions, Hooker ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'wpar/after_plugin_uninstall', 'meta_cleanup', 30 );
        $this->action( 'wpar/after_plugin_uninstall', 'remove_actions', 5 );
        $this->action( 'wpar/remove_post_metadata', 'meta_cleanup', 20 );
        $this->action( 'wpar/remove_post_metadata', 'remove_actions', 5 );
        $this->action( 'wpar/deschedule_posts', 'deschedule_posts' );
    }
    
    /**
     * Post meta cleanup.
     */
    public function meta_cleanup()
    {
        $post_types = array_keys( $this->get_post_types() );
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => [ 'publish', 'future', 'private' ],
            'fields'      => 'ids',
        ];
        $posts = get_posts( $args );
        if ( !empty($posts) ) {
            foreach ( $posts as $post_id ) {
                $metas = get_post_custom( $post_id );
                foreach ( $metas as $key => $values ) {
                    if ( strpos( $key, 'wpar_' ) !== false ) {
                        if ( $key != '_wpar_original_pub_date' ) {
                            $this->delete_meta( $post_id, $key );
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Remove actions.
     */
    public function remove_actions()
    {
        $post_types = array_keys( $this->get_post_types() );
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields'      => 'ids',
            'meta_query'  => [
            'relation' => 'OR',
            [
            'key'     => 'wpar_global_republish_status',
            'compare' => 'EXISTS',
        ],
            [
            'key'     => 'wpar_single_republish_status',
            'compare' => 'EXISTS',
        ],
        ],
        ];
        $args = $this->do_filter( 'remove_actions_args', $args );
        //error_log( print_r( $args, true ) );
        $posts = get_posts( $args );
        if ( !empty($posts) ) {
            foreach ( $posts as $post_id ) {
                // get republish time from post meta
                $this->unschedule_all_actions( 'wpar/global_republish_single_post', [ $post_id ] );
            }
        }
    }
    
    /**
     * Remove actions.
     */
    public function deschedule_posts()
    {
        $post_types = array_keys( $this->get_post_types() );
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => [ 'publish', 'future', 'private' ],
            'fields'      => 'ids',
            'meta_query'  => [ [
            'key'     => '_wpar_original_pub_date',
            'compare' => 'EXISTS',
        ] ],
        ];
        $args = $this->do_filter( 'deschedule_posts_args', $args );
        //error_log( print_r( $args, true ) );
        $posts = get_posts( $args );
        if ( !empty($posts) ) {
            foreach ( $posts as $post_id ) {
                // get original published date
                $pub_date = $this->get_meta( $post_id, '_wpar_original_pub_date' );
                // update posts
                wp_update_post( [
                    'ID'            => $post_id,
                    'post_date'     => $pub_date,
                    'post_date_gmt' => get_gmt_from_date( $pub_date ),
                ] );
                // delete old meta
                $this->delete_meta( $post_id, '_wpar_original_pub_date' );
            }
        }
    }

}