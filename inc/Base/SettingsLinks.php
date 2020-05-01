<?php
/**
 * Action links.
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
 * Action links class.
 */
class SettingsLinks extends BaseController
{
	use Hooker;

	/**
	 * Register functions.
	 */
	public function register() 
	{
		$this->action( "plugin_action_links_$this->plugin", 'settings_link', 10, 1 );
		$this->action( 'plugin_row_meta', 'meta_links', 10, 2 );
	}

	/**
	 * Register settings link.
	 */
	public function settings_link( $links ) 
	{
		$wparlinks = [
			'<a href="' . admin_url( 'admin.php?page=wp-auto-republish' ) . '">' . __( 'Settings', 'wp-auto-republish' ) . '</a>',
		];
		return array_merge( $wparlinks, $links );
	}

	/**
	 * Register meta links.
	 */
	public function meta_links( $links, $file ) {
		if ( $file === $this->plugin ) { // only for this plugin
			if ( wpar_load_fs_sdk()->is_not_paying() && ! wpar_load_fs_sdk()->is_trial() ) {
				if ( ! wpar_load_fs_sdk()->is_trial_utilized() ) {
				    $links[] = '<a href="' . wpar_load_fs_sdk()->get_trial_url() . '" target="_blank" style="font-weight: 700;">' . __( 'Try Premium', 'wp-auto-republish' ) . '</a>';
				}
				$links[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank">' . __( 'Support', 'wp-auto-republish' ) . '</a>';
				$links[] = '<a href="https://www.paypal.me/iamsayan/" target="_blank">' . __( 'Donate', 'wp-auto-republish' ) . '</a>';
			}
		}
		return $links;
	}
}