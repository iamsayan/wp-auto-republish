<?php
/**
 * Rewrite post permalinks.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core\Premium
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Core;

use RevivePress\Helpers\Hooker;
use RevivePress\Helpers\SettingsData;

defined( 'ABSPATH' ) || exit;

/**
 * Permalink Rewrite class.
 */
class RewritePermainks
{
	use Hooker, SettingsData;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'init','rewrite_tag' );
        $this->action( 'template_redirect', 'check_rewrite' );
        $this->filter( 'post_link', 'filter_post_link', 10, 2 );
        $this->filter( 'post_type_link', 'filter_post_link', 10, 2 );
        $this->filter( 'available_permalink_structure_tags', 'available_tags' );
	}

	/**
	 * Register rewrite tags.
	 */
	public function rewrite_tag() {
		add_rewrite_tag( '%rvp_year%', '([0-9]{4})' );
		add_rewrite_tag( '%rvp_monthnum%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_day%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_hour%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_minute%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_second%', '([0-9]{1,2})' );
	}

	/**
	 * Check and throw 404 error if the actual date doesn't match.
	 * 
	 * @since 1.4.5
	 */
	public function check_rewrite() {
		if ( is_single() ) {
			$permalink_structure = get_option( 'permalink_structure' );
			// bail if tag is not present in the url
			if ( false === strpos( $permalink_structure, '%rvp_' ) ) {
				return;
			}

			$original_date = $this->get_meta( get_the_ID(), '_wpar_original_pub_date' );
			if ( ! $original_date ) {
				$original_date = get_the_date( 'Y-m-d H:i:s', get_the_ID() );
			}

			// This is not an API call because the permalink is based on the stored post_date value,
        	// which should be parsed as local time regardless of the default PHP timezone.
			$date = explode( ' ', str_replace( [ '-', ':' ], ' ', $original_date ) );

			$exit = false;
			$rewritecode = [
				'rvp_year',
				'rvp_monthnum',
				'rvp_day',
				'rvp_hour',
				'rvp_minute',
				'rvp_second',
			];

			foreach ( $rewritecode as $key => $slug ) {
				$datetime = get_query_var( $slug );
				if ( ! empty( $datetime ) && ( intval( $datetime ) !== intval( $date[ $key ] ) ) ) {
					$exit = true;
					break;
				}
			}

			if ( $exit ) {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}
	}

	/**
	 * Filter post permalinks.
	 *
	 * @param string $permalink   Original post permalink.
	 * @param object $post        WordPress Post object.
	 *
	 * @return string             Filtered permalink
	 */
	public function filter_post_link( $permalink, $post ) {
		// bail if tag is not present in the url
		if ( false === strpos( $permalink, '%rvp_' ) ) {
			return $permalink;
		}

		$original_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
		if ( ! $original_date ) {
		    $original_date = $post->post_date;
		}

		// This is not an API call because the permalink is based on the stored post_date value,
        // which should be parsed as local time regardless of the default PHP timezone.
		$date = explode( ' ', str_replace( [ '-', ':' ], ' ', $original_date ) );

		$rewritecode = [
			'%rvp_year%',
			'%rvp_monthnum%',
			'%rvp_day%',
			'%rvp_hour%',
			'%rvp_minute%',
			'%rvp_second%',
		];

		$rewritereplace = [
			$date[0],
            $date[1],
            $date[2],
            $date[3],
            $date[4],
            $date[5],
		];

		return str_replace( $rewritecode, $rewritereplace, $permalink );
	}

	/**
	 * Filter post permalink available tags lists.
	 * 
	 * @since 1.3.3
	 * @param array $tags   Existing tags.
	 *
	 * @return array        Filtered tags array
	 */
	public function available_tags( $tags ) {
		$available_tags = array(
			/* translators: %s: Permalink structure tag. */
			'rvp_year'     => __( '%s (The year of the post, four digits, for example 2004.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_monthnum' => __( '%s (Month of the year, for example 05.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_day'      => __( '%s (Day of the month, for example 28.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_hour'     => __( '%s (Hour of the day, for example 15.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_minute'   => __( '%s (Minute of the hour, for example 43.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_second'   => __( '%s (Second of the minute, for example 33.)' ),
		);
		
		return array_merge( $tags, $available_tags );
	}
}