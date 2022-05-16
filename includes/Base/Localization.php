<?php
/**
 * Localization loader.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Base;

use RevivePress\Helpers\Hooker;
use RevivePress\Base\BaseController;

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
	public function register() {
		$this->action( 'plugins_loaded', 'load_textdomain' );
	}

	/**
     * Initialize plugin for localization.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-auto-republish', false, dirname( $this->plugin ) . '/languages/' ); 
	}
}