<?php 
/**
 * Rating notice.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Wpar\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace Wpar\Base;

use Wpar\Helpers\Hooker;
use Wpar\Base\BaseController;

defined( 'ABSPATH' ) || exit;

/**
 * Rating notice class.
 */
class RatingNotice
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
	    	|| '1' === get_option( 'wpar_plugin_dismiss_rating_notice' )
            || ! current_user_can( 'manage_options' )
			|| apply_filters( 'wpar/hide_sticky_rating_notice', false ) ) {
            return;
        }
    
        $dismiss = wp_nonce_url( add_query_arg( 'wpar_rating_notice_action', 'dismiss_rating_true' ), 'dismiss_rating_true' ); 
        $no_thanks = wp_nonce_url( add_query_arg( 'wpar_rating_notice_action', 'no_thanks_rating_true' ), 'no_thanks_rating_true' ); ?>
        
        <div class="notice notice-success">
            <p><?php echo wp_kses_post( 'Hey, I noticed you\'ve been using RevivePress (formerly WP Auto Republish) for more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a <strong>5-star</strong> rating on WordPress? Just to help us spread the word and boost my motivation.', 'wp-auto-republish' ); ?></p>
            <p><a href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/" target="_blank" class="button button-secondary"><?php esc_html_e( 'Ok, you deserve it', 'wp-auto-republish' ); ?></a>&nbsp;
            <a href="<?php echo esc_url( $dismiss ); ?>" class="already-did"><strong><?php esc_html_e( 'I already did', 'wp-auto-republish' ); ?></strong></a>&nbsp;<strong>|</strong>
            <a href="<?php echo esc_url( $no_thanks ); ?>" class="later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'wp-auto-republish' ); ?></strong></a>&nbsp;<strong>|</strong>
            <a href="<?php echo esc_url( $dismiss ); ?>" class="hide"><strong><?php esc_html_e( 'I don\'t want to rate', 'wp-auto-republish' ); ?></strong></a></p>
        </div>
	<?php
	}
	
	/**
	 * Dismiss admin notices.
	 */
	public function dismiss_notice() {
		if ( get_option( 'wpar_plugin_no_thanks_rating_notice' ) === '1' ) {
			if ( get_option( 'wpar_plugin_dismissed_time' ) > strtotime( '-168 hours' ) ) {
				return;
			}
			delete_option( 'wpar_plugin_dismiss_rating_notice' );
			delete_option( 'wpar_plugin_no_thanks_rating_notice' );
		}
	
		if ( ! isset( $_REQUEST['wpar_rating_notice_action'] ) ) {
			return;
		}
	
		if ( 'dismiss_rating_true' === $_REQUEST['wpar_rating_notice_action'] ) {
			check_admin_referer( 'dismiss_rating_true' );
			update_option( 'wpar_plugin_dismiss_rating_notice', '1' );
		}
	
		if ( 'no_thanks_rating_true' === $_REQUEST['wpar_rating_notice_action'] ) {
			check_admin_referer( 'no_thanks_rating_true' );
			update_option( 'wpar_plugin_no_thanks_rating_notice', '1' );
			update_option( 'wpar_plugin_dismiss_rating_notice', '1' );
			update_option( 'wpar_plugin_dismissed_time', time() );
		}
	
		wp_redirect( remove_query_arg( 'wpar_rating_notice_action' ) );
		exit;
	}
	
	/**
	 * Calculate install time.
	 */
	private function calculate_time() {
		$installed_time = get_option( 'wpar_plugin_installed_time' );
		
        if ( ! $installed_time ) {
            $installed_time = time();
            update_option( 'wpar_plugin_installed_time', $installed_time );
        }

        return $installed_time;
	}
}