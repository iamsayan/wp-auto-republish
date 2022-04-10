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
        $this->filter( 'post_link', 'filter_post_link', 10, 2 );
        $this->filter( 'post_type_link', 'filter_post_link', 10, 2 );
	}

	/**
	 * Register rewrite tags.
	 */
	public function rewrite_tag() {
		add_rewrite_tag( '%rvp_year%', '([0-9]{4})' );
		add_rewrite_tag( '%rvp_monthnum%', '([0-9]{2})' );
		add_rewrite_tag( '%rvp_day%', '([0-9]{2})' );
		add_rewrite_tag( '%rvp_hour%', '([0-9]{2})' );
		add_rewrite_tag( '%rvp_minute%', '([0-9]{2})' );
		add_rewrite_tag( '%rvp_second%', '([0-9]{2})' );
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
}