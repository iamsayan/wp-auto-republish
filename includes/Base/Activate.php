<?php
/**
 * Activation.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Base;

/**
 * Activation class.
 */
class Activate
{
	/**
	 * Run plugin activation process.
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		
		// register action
		do_action( 'wpar/after_plugin_activate' );

		flush_rewrite_rules();
	}
}