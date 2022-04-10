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
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wp-auto-republish' );

		unload_textdomain( 'wp-auto-republish' );
		if ( false === load_textdomain( 'wp-auto-republish', WP_LANG_DIR . '/plugins/wp-auto-republish-' . $locale . '.mo' ) ) {
			load_textdomain( 'wp-auto-republish', WP_LANG_DIR . '/wp-auto-republish/wp-auto-republish-' . $locale . '.mo' );
		}
		load_plugin_textdomain( 'wp-auto-republish', false, dirname( $this->plugin ) . '/languages/' ); 
	}
}