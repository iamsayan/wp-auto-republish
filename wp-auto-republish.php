<?php

/**
 * Plugin Name: RevivePress
 * Plugin URI: https://wprevivepress.com?utm_source=landing&utm_medium=plugin
 * Description: RevivePress, the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.
 * Version: 1.5.7
 * Author: Sayan Datta
 * Author URI: https://www.sayandatta.co.in
 * License: GPLv3
 * Text Domain: wp-auto-republish
 * Domain Path: /languages
 * 
 * RevivePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * RevivePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with RevivePress. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Core
 * @package  RevivePress
 * @author   Sayan Datta <iamsayan@protonmail.com>
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/wp-auto-republish/
 * 
 * 
 */

// If this file is called directly, abort!!!
defined( 'ABSPATH' ) || exit;

// Freemius SDK: Auto deactivate the free version when activating the paid one.
if ( function_exists( 'revivepress_fs' ) ) {
    revivepress_fs()->set_basename( false, __FILE__ );
    return;
}

/**
 * Check if RevivePress class is already exists.
 *
 * @class Main class of the plugin.
 */
if ( ! class_exists( 'RevivePress' ) ) {

	/**
	 * RevivePress class.
	 *
	 * @class Main class of the plugin.
	 */
	final class RevivePress {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.5.7';

		/**
		 * Minimum version of WordPress required to run RevivePress.
		 *
		 * @var string
		 */
		private $wordpress_version = '5.2';

		/**
		 * Minimum version of PHP required to run RevivePress.
		 *
		 * @var string
		 */
		private $php_version = '7.3';

		/**
		 * Hold install error messages.
		 *
		 * @var bool
		 */
		private $messages = array();

		/**
		 * The single instance of the class.
		 *
		 * @var RevivePress
		 */
		protected static $instance = null;

		/**
		 * Retrieve main RevivePress instance.
		 *
		 * Ensure only one instance is loaded or can be loaded.
		 *
		 * @see revivepress()
		 * @return RevivePress
		 */
		public static function get() {
			if ( is_null( self::$instance ) && ! ( self::$instance instanceof RevivePress ) ) {
				self::$instance = new RevivePress();
				self::$instance->setup();
			}

			return self::$instance;
		}

		/**
		 * Instantiate the plugin.
		 */
		private function setup() {
			// Define plugin constants.
			$this->define_constants();

			if ( ! $this->is_requirements_meet() ) {
				return;
			}

			// Load Freemius.
			$this->freemius();

			// Include required files.
			$this->includes();

			// Instantiate services.
			$this->instantiate();

			// Loaded action.
			do_action( 'revivepress/loaded' );
		}

		/**
		 * Check that the WordPress and PHP setup meets the plugin requirements.
		 *
		 * @return bool
		 */
		private function is_requirements_meet() {

			// Check WordPress version.
			if ( version_compare( get_bloginfo( 'version' ), $this->wordpress_version, '<' ) ) {
				/* translators: WordPress Version */
				$this->messages[] = sprintf( esc_html__( 'You are using the outdated WordPress, please update it to version %s or higher.', 'wp-auto-republish' ), $this->wordpress_version );
			}

			// Check PHP version.
			if ( version_compare( phpversion(), $this->php_version, '<' ) ) {
				/* translators: PHP Version */
				$this->messages[] = sprintf( esc_html__( 'RevivePresss requires PHP version %s or above. Please update PHP to run this plugin.', 'wp-auto-republish' ), $this->php_version );
			}

			if ( empty( $this->messages ) ) {
				return true;
			}

			// Auto-deactivate plugin.
			add_action( 'admin_init', array( $this, 'auto_deactivate' ) );
			add_action( 'admin_notices', array( $this, 'activation_error' ) );

			return false;
		}

		/**
		 * Auto-deactivate plugin if requirements are not met, and display a notice.
		 */
		public function auto_deactivate() {
			deactivate_plugins( REVIVEPRESS_BASENAME );
			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
				unset( $_GET['activate'] ); // phpcs:ignore
			}
		}

		/**
		 * Error notice on plugin activation.
		 */
		public function activation_error() {
			?>
			<div class="notice revivepress-notice notice-error">
				<p>
					<?php echo join( '<br>', $this->messages ); // phpcs:ignore ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Define the plugin constants.
		 */
		private function define_constants() {
			define( 'REVIVEPRESS_VERSION', $this->version );
			define( 'REVIVEPRESS_FILE', __FILE__ );
			define( 'REVIVEPRESS_PATH', dirname( REVIVEPRESS_FILE ) . '/' );
			define( 'REVIVEPRESS_URL', plugins_url( '', REVIVEPRESS_FILE ) . '/' );
			define( 'REVIVEPRESS_BASENAME', plugin_basename( REVIVEPRESS_FILE ) );
		}

		/**
		 * Include the Freemius SDK.
		 */
		private function freemius() {
			include __DIR__ . '/freemius.php';

			// Init Freemius.
			revivepress_fs();

			// Load the TablePress plugin icon for the Freemius opt-in/activation screen.
			revivepress_fs()->add_filter(
				'plugin_icon',
				function() {
					return __DIR__ . '/assets/images/logo.png';
				}
			);

			// Hide the Powered by Freemius tab from generated pages, like "Upgrade" or "Pricing".
			revivepress_fs()->add_filter( 'hide_freemius_powered_by', '__return_true' );

			// Hide the Affiliate program notice.
			revivepress_fs()->add_filter( 'show_affiliate_program_notice', '__return_false' );

			// Hide the Subscription cancellation form.
			revivepress_fs()->add_filter( 'show_deactivation_subscription_cancellation', '__return_false' );

			// Use different arrow icons in the admin menu.
			revivepress_fs()->override_i18n( array(
				'symbol_arrow-left'  => '&larr;',
				'symbol_arrow-right' => '&rarr;',
			) );

			// Signal that SDK was initiated.
			do_action( 'revivepress_fs_loaded' );
		}

		/**
		 * Include the required files.
		 */
		private function includes() {
			include __DIR__ . '/vendor/autoload.php';
		}

		/**
		 * Instantiate services.
		 */
		private function instantiate() {
			// Activation hook.
			register_activation_hook( REVIVEPRESS_FILE, 
				function () {
					RevivePress\Base\Activate::activate();
				} 
			);

			// Deactivation hook.
			register_deactivation_hook( REVIVEPRESS_FILE, 
				function () {
					RevivePress\Base\Deactivate::deactivate();
				} 
			);

			// Uninstall hook.
			revivepress_fs()->add_action( 
				'after_uninstall', 
				function () {
					RevivePress\Base\Uninstall::uninstall();
				} 
			);

			// Init RevivePress Classes.
			RevivePress\Loader::register_services();
		}
	}
}

/**
 * Returns the main instance of RevivePress to prevent the need to use globals.
 *
 * @return RevivePress
 */
if ( ! function_exists( 'revivepress' ) ) {
	function revivepress() {
		return RevivePress::get();
	}
}

// Start it.
revivepress();