<?php
/**
 * External Functions.
 *
 * @since      1.3.2
 * @package    RevivePress
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

/**
 * Call the republish function directly
 * 
 * @since 1.3.2
 * @param int    $post_id  Post ID
 * @param array  $args     Republish args
 */
if ( ! function_exists( 'revivepress_republish_post' ) ) {
    function revivepress_republish_post( $post_id, $args = array() ) {
        $post = get_post( absint( $post_id ) );

		if ( ! $post instanceof WP_Post ) {
			return;
		}

        $defaults = array(
            'method'  => 'republish', // Republish method, accepts 'republish' or 'clone' (clone - premium only)
            'single'  => false,       // Republish based on meta, defaults to false
            'instant' => true,        // On demand republish, defaults to true
        );
        $args = wp_parse_args( $args, $defaults );

        $args['post_id']  = $post->ID;
        $args['external'] = true;

        do_action( 'wpar/process_republish_post', $args ); // Don't use this action directly as it may create issues in future versions
    }
}