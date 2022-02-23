<?php 
/**
 * Donation notice.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Wpar\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace Wpar\Base;

use Wpar\Helpers\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Donation Notice class.
 */
class DonateNotice
{
	use Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'admin_notices', 'show_notice' );
		$this->action( 'admin_init', 'dismiss_notice' );
	}
	
	/**
	 * Show admin notices.
	 */
	public function show_notice() {
		// Show notice after 240 hours (10 days) from installed time.
		if ( $this->calculate_time() > strtotime( '-360 hours' )
			|| '1' === get_option( 'wpar_plugin_dismiss_donate_notice' )
			|| ! current_user_can( 'manage_options' )
			|| apply_filters( 'wpar/hide_sticky_donate_notice', false ) 
			|| wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
			return;
		}
	
		$dismiss = wp_nonce_url( add_query_arg( 'wpar_donate_notice_action', 'dismiss_donate_true' ), 'wpar_dismiss_donate_true' ); 
		$no_thanks = wp_nonce_url( add_query_arg( 'wpar_donate_notice_action', 'no_thanks_donate_true' ), 'wpar_no_thanks_donate_true' ); ?>
		
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Hey, I noticed you\'ve been using RevivePress (formerly WP Auto Republish) for more than 2 week – that’s awesome! If you like this plugin and you are satisfied with the plugin, isn’t that worth a coffee or two? Please consider donating. Donations help me to continue support and development of this free plugin! Thank you very much!', 'wp-auto-republish' ); ?></p>
			<p><a href="https://www.paypal.me/iamsayan" target="_blank" class="button button-secondary"><?php esc_html_e( 'Donate Now', 'wp-auto-republish' ); ?></a>&nbsp;
			<a href="<?php echo esc_url( $dismiss ); ?>" class="already-did"><strong><?php esc_html_e( 'I already donated', 'wp-auto-republish' ); ?></strong></a>&nbsp;<strong>|</strong>
			<a href="<?php echo esc_url( $no_thanks ); ?>" class="later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'wp-auto-republish' ); ?></strong></a>&nbsp;<strong>|</strong>
			<a href="<?php echo esc_url( $dismiss ); ?>" class="hide"><strong><?php esc_html_e( 'I don\'t want to donate', 'wp-auto-republish' ); ?></strong></a></p>
		</div>
	<?php
	}
	
	/**
	 * Dismiss admin notices.
	 */
	public function dismiss_notice() {
		if ( get_option( 'wpar_plugin_no_thanks_donate_notice' ) === '1' ) {
			if ( get_option( 'wpar_plugin_dismissed_time_donate' ) > strtotime( '-360 hours' ) ) {
				return;
			}
			delete_option( 'wpar_plugin_dismiss_donate_notice' );
			delete_option( 'wpar_plugin_no_thanks_donate_notice' );
		}
	
		if ( ! isset( $_REQUEST['wpar_donate_notice_action'] ) ) {
			return;
		}
	
		if ( 'dismiss_donate_true' === $_REQUEST['wpar_donate_notice_action'] ) {
			check_admin_referer( 'wpar_dismiss_donate_true' );
			update_option( 'wpar_plugin_dismiss_donate_notice', '1' );
		}
	
		if ( 'no_thanks_donate_true' === $_REQUEST['wpar_donate_notice_action'] ) {
			check_admin_referer( 'wpar_no_thanks_donate_true' );
			update_option( 'wpar_plugin_no_thanks_donate_notice', '1' );
			update_option( 'wpar_plugin_dismiss_donate_notice', '1' );
			update_option( 'wpar_plugin_dismissed_time_donate', time() );
		}
	
		wp_redirect( remove_query_arg( 'wpar_donate_notice_action' ) );
		exit;
	}
	
	/**
	 * Calculate install time.
	 */
	private function calculate_time() {
		$installed_time = get_option( 'wpar_plugin_installed_time_donate' );
		
		if ( ! $installed_time ) {
			$installed_time = time();
			update_option( 'wpar_plugin_installed_time_donate', $installed_time );
		}

		return $installed_time;
	}
}