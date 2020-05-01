<?php
/**
 * Localization loader.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Inc\Base;

use Inc\Helpers\Hooker;
use Inc\Base\BaseController;

defined( 'ABSPATH' ) || exit;

/**
 * Localizationclass.
 */
class Localization extends BaseController
{
	use Hooker;

	/**
	 * Register functions.
	 */
	public function register() 
	{
		$this->action( 'plugins_loaded', 'load_textdomain' );
	}

	/**
	 * Load textdomain.
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain( 'wp-auto-republish', false, dirname( $this->plugin ) . '/languages/' ); 
	}
}