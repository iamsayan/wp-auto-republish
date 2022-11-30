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
		// register action.
		do_action( 'wpar/plugin_activate' );

		// flush permalinks
		flush_rewrite_rules();
	}
}