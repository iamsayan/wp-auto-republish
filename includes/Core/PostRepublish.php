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

use WP_Post;
use RevivePress\Helpers\Hooker;
use RevivePress\Helpers\Logger;
use RevivePress\Helpers\Scheduler;
use RevivePress\Helpers\HelperFunctions;
defined( 'ABSPATH' ) || exit;
/**
 * Republication class.
 */
class PostRepublish {
    use HelperFunctions;
    use Hooker;
    use Logger;
    use Scheduler;

    /**
     * Register functions.
     */
    public function register() {
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
    public function call_republish( array $args ) {
        $method = $args['method'];
        if ( 'republish' === $method ) {
            $this->update_old_post( $args['post_id'], $args['single'] );
        }
    }

    /**
     * Trigger post update process.
     * 
     * @param int   $post_id Post ID
     *
     *@since 1.1.7
     */
    public function do_republish( int $post_id ) {
        // delete data.
        $this->delete_meta( $post_id, 'wpar_global_republish_status' );
        $this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
        $this->remove_meta( $post_id );
        // Republish.
        $this->handle( $post_id );
    }

    /**
     * Delete post meta data flags.
     * 
     * @param int   $post_id Post ID
     *
     *@since 1.5.1
     */
    public function remove_meta( int $post_id ) {
        $this->delete_meta( $post_id, 'wpar_republish_as_action_id' );
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
    private function handle( int $post_id ) {
        $action = 'repost';
        if ( $action === 'repost' ) {
            $this->update_old_post( $post_id );
        }
    }

    /**
     * Run post update process.
     * 
     * @param int   $post_id  Post ID
     * @param bool  $single   Check if it is a single republish event
     *
     * @return int $post_id
     */
    public function update_old_post( int $post_id, bool $single = false ): int {
        $post = \get_post( $post_id );
        if ( ! $post ) {
            return 0;
        }
        $new_time = $this->get_publish_time( $post->ID, $single );
        $pub_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
        if ( ! $pub_date && $post->post_status !== 'future' ) {
            $this->update_meta( $post->ID, '_wpar_original_pub_date', $post->post_date );
        }
        $this->update_meta( $post->ID, '_wpar_last_pub_date', $post->post_date );
        \kses_remove_filters();
        $args = array(
            'ID'            => $post->ID,
            'post_date'     => $new_time,
            'post_date_gmt' => get_gmt_from_date( $new_time ),
        );
        $args = $this->do_filter(
            'update_process_args',
            $args,
            $post->ID,
            $post
        );
        $result = wp_update_post( $args );
        if ( is_wp_error( $result ) ) {
            \kses_init_filters();
            return 0;
        }
        $this->do_action( 'clear_site_cache' );
        // update reference
        $this->update_meta( $post->ID, 'wpar_republish_meta_query', $new_time );
        $this->log( $post->ID, array(
            'action'    => 'republish',
            'timestamp' => $new_time,
            'info'      => __( 'Post Updated.', 'wp-auto-republish' ),
        ) );
        $this->do_action(
            'old_post_republished',
            $post->ID,
            $new_time,
            'republish'
        );
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
    private function get_publish_time( int $post_id, bool $single = false, bool $scheduled = false ): string {
        $post = \get_post( $post_id );
        if ( ! $post ) {
            return current_time( 'mysql' );
        }
        $timestamp = $this->current_timestamp();
        $interval = MINUTE_IN_SECONDS * $this->do_filter( 'second_position_interval', wp_rand( 1, 15 ) );
        $position = $this->get_data( 'wpar_republish_post_position', 'one' );
        if ( ! isset( $new_time ) ) {
            if ( $position === 'one' ) {
                $datetime = $this->get_meta( $post_id, '_wpar_global_republish_datetime' );
                if ( ! empty( $datetime ) && $timestamp >= strtotime( $datetime ) ) {
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
                if ( ! empty( $lastposts ) ) {
                    $lastpost = $lastposts[0];
                    $post_date = get_the_date( 'U', $lastpost );
                    $post_date = $post_date + $interval;
                    $new_time = date( 'Y-m-d H:i:s', $post_date );
                }
            }
        }
        if ( ! isset( $new_time ) ) {
            $new_time = current_time( 'mysql' );
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
