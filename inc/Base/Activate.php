<?php
/**
 * Activation.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Wpar\Base;

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
		
		flush_rewrite_rules();
	}
}