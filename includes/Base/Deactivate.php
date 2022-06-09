<?php
/**
 * Deactivation.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Base;

/**
 * Deactivation class.
 */
class Deactivate
{
	/**
	 * Run plugin deactivation process.
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		delete_option( 'wpar_plugin_dismiss_rating_notice' );
		delete_option( 'wpar_plugin_no_thanks_rating_notice' );
		delete_option( 'wpar_plugin_installed_time' );
		delete_option( 'revivepress_hide_permalink_notice' );
		delete_option( 'wpar_next_scheduled_timestamp' );

		$permalink_structure = get_option( 'permalink_structure' );
		$permalink_structure = str_replace( [ '%wpar_', '%rvp_' ], '%', $permalink_structure );
		update_option( 'permalink_structure', $permalink_structure );

		// register action
		do_action( 'wpar/after_plugin_deactivate' );

		flush_rewrite_rules();
		wp_cache_flush();
	}
}