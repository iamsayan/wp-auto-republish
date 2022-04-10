<?php
/**
 * Uninstallation hook.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Base;

/**
 * Uninstall class.
 */
class Uninstall
{
	/**
	 * Run plugin uninstallation process.
	 */
	public static function uninstall() {
		$option_name = 'wpar_plugin_settings';
		if ( ! is_multisite() ) {
			$options = get_option( $option_name );
			if ( isset( $options['wpar_remove_plugin_data'] ) && ( $options['wpar_remove_plugin_data'] == 1 ) ) {
				// delete plugin settings
				self::remove_options();
			}
		} else {
			global $wpdb;
			
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$options = get_option( $option_name );
				if ( isset( $options['wpar_remove_plugin_data'] ) && ( $options['wpar_remove_plugin_data'] == 1 ) ) {
					// delete plugin settings
					self::remove_options();
				}
			}
			switch_to_blog( $original_blog_id );
		}
	}

	/**
	 * Run plugin uninstallation process.
	 */
	public static function remove_options() {
		delete_option( 'wpar_plugin_settings' );
		delete_option( 'wpar_republish_log_history' );
		delete_option( 'wpar_dashboard_widget_options' );
		delete_option( 'wpar_last_global_cron_run' );
		delete_option( 'wpar_global_republish_post_ids' );
		delete_option( 'wpar_social_credentials' );
	}
}