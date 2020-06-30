<?php
/**
 * Deactivation.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Wpar\Base;

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

		flush_rewrite_rules();

		delete_option( 'wpar_plugin_dismiss_rating_notice' );
		delete_option( 'wpar_plugin_no_thanks_rating_notice' );
		delete_option( 'wpar_plugin_installed_time' );
	}
}