<?php

/**
 * Database Action links.
 *
 * @since      1.2.0
 * @package    RevivePress
 * @subpackage RevivePress\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Tools;

use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\Scheduler ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Database Action links class.
 */
class DatabaseActions
{
    use  HelperFunctions, Hooker, Scheduler ;
    /**
     * Register functions.
     */
    public function register() {
        $this->action( 'wpar/remove_post_metadata', 'run_cleanup' );
        $this->action( 'wpar/remove_post_meta_task', 'run_cleanup_task' );
        $this->action( 'wpar/deschedule_posts', 'deschedule_posts' );
        $this->action( 'wpar/deschedule_posts_task', 'deschedule_posts_task' );
    }
    
    /**
     * Post meta cleanup.
     */
    public function run_cleanup() {
        $post_types = $this->get_post_types( true );
        $args = [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => [ 'publish', 'future', 'private' ],
            'fields'      => 'ids',
        ];
        $post_ids = $this->get_posts( $args );
        $this->schedule_batch_actions( $post_ids, 'wpar/remove_post_meta_task' );
    }
    
    /**
     * Post meta cleanup task.
     */
    public function run_cleanup_task( array $post_ids ) {
        if ( ! empty($post_ids) ) {
            foreach ( $post_ids as $post_id ) {
                // Remove schedules
                $this->unschedule_all_actions( 'wpar/global_republish_single_post', [ $post_id ] );
                // Remove metas
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
    public function deschedule_posts() {
        $post_types = $this->get_post_types( true );
        $args = $this->do_filter( 'deschedule_posts_args', [
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => [ 'publish', 'future', 'private' ],
            'fields'      => 'ids',
            'meta_query'  => [
				[
					'key'     => '_wpar_original_pub_date',
					'compare' => 'EXISTS',
				],
			],
        ] );
        $post_ids = $this->get_posts( $args );
        $this->schedule_batch_actions( $post_ids, 'wpar/deschedule_posts_task' );
    }
    
    /**
     * Remove actions.
     */
    public function deschedule_posts_task( array $post_ids ) {
        if ( ! empty($post_ids) ) {
            foreach ( $post_ids as $post_id ) {
                // get original published date
                $pub_date = $this->get_meta( $post_id, '_wpar_original_pub_date' );
                // update posts
                \wp_update_post( [
                    'ID'            => $post_id,
                    'post_date'     => $pub_date,
                    'post_date_gmt' => \get_gmt_from_date( $pub_date ),
                ] );
                // delete old meta
                $this->delete_meta( $post_id, '_wpar_original_pub_date' );
            }
        }
    }

}