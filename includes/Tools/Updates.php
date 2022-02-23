<?php
/**
 * Functions and actions related to updates.
 *
 * @since      1.3.0
 * @package    RevivePress
 * @subpackage Wpar\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace Wpar\Tools;

use Wpar\Helpers\Hooker;
use Wpar\Base\BaseController;

defined( 'ABSPATH' ) || exit;

/**
 * Sction Migration class.
 */
class Updates extends BaseController
{
	use Hooker;

	/**
	 * Updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = [
		'1.3.0' => 'updates/update-1.3.0.php',
	];

	/**
	 * Register hooks.
	 */
	public function register() {
		$this->action( 'admin_init', 'do_updates' );
	}

	/**
	 * Check if any update is required.
	 */
	public function do_updates() {
		$installed_version = get_option( 'revivepress_version', '1.0.0' );

		// Maybe it's the first install.
		if ( ! $installed_version ) {
			return;
		}

		if ( version_compare( $installed_version, $this->version, '<' ) ) {
			$this->perform_updates();
		}
	}

	/**
	 * Perform all updates.
	 */
	public function perform_updates() {
		$installed_version = get_option( 'revivepress_version', '1.0.0' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'revivepress_version', $version );
			}
		}

		// Save install date.
		if ( false === boolval( get_option( 'revivepress_install_date' ) ) ) {
			update_option( 'revivepress_install_date', current_time( 'timestamp' ) ); // phpcs:ignore
		}

		update_option( 'revivepress_version', $this->version );
	}
}