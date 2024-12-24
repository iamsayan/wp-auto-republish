<?php 
/**
 * Rating notice.
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
		if ( $this->calculate_time() > strtotime( '-7 days' )
	    	|| '1' === get_option( 'wpar_plugin_dismiss_rating_notice' )
            || ! current_user_can( 'manage_options' )
			|| apply_filters( 'wpar/hide_sticky_rating_notice', false ) ) {
            return;
        }
    
        $dismiss = wp_nonce_url( add_query_arg( 'rvp_rating_notice', 'dismiss' ), 'rvp_rating_nonce' ); 
        $later = wp_nonce_url( add_query_arg( 'rvp_rating_notice', 'later' ), 'rvp_rating_nonce' ); ?>

        <div class="notice notice-success">
            <p>
				<?php echo wp_kses_post( 'Hey, I noticed you\'ve been using RevivePress for more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a <strong>5-star</strong> rating on WordPress? Just to help us spread the word and boost my motivation.', 'wp-auto-republish' ); ?>
			</p>
            <p>
				<a href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" class="button button-secondary"><?php esc_html_e( 'Ok, you deserve it', 'wp-auto-republish' ); ?></a>&nbsp;
            	<a href="<?php echo esc_url( $dismiss ); ?>" class="rvp-already-did"><strong><?php esc_html_e( 'I already did', 'wp-auto-republish' ); ?></strong></a>&nbsp;<strong>|</strong>
            	<a href="<?php echo esc_url( $later ); ?>" class="rvp-later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'wp-auto-republish' ); ?></strong></a>
			</p>
        </div>
	<?php
	}
	
	/**
	 * Dismiss admin notices.
	 */
	public function dismiss_notice() {
		if ( get_option( 'wpar_plugin_no_thanks_rating_notice' ) === '1' ) {
			if ( get_option( 'wpar_plugin_dismissed_time' ) > strtotime( '-10 days' ) ) {
				return;
			}
			delete_option( 'wpar_plugin_dismiss_rating_notice' );
			delete_option( 'wpar_plugin_no_thanks_rating_notice' );
		}
	
		if ( ! isset( $_REQUEST['rvp_rating_notice'] ) ) {
			return;
		}

		check_admin_referer( 'rvp_rating_nonce' );

		if ( 'dismiss' === $_REQUEST['rvp_rating_notice'] ) {
			update_option( 'wpar_plugin_dismiss_rating_notice', '1', false );
		}
	
		if ( 'later' === $_REQUEST['rvp_rating_notice'] ) {
			update_option( 'wpar_plugin_no_thanks_rating_notice', '1', false );
			update_option( 'wpar_plugin_dismiss_rating_notice', '1', false );
			update_option( 'wpar_plugin_dismissed_time', time(), false );
		}
	
		wp_safe_redirect( remove_query_arg( array( 'rvp_rating_notice', '_wpnonce' ) ) );
		exit;
	}
	
	/**
	 * Calculate install time.
	 */
	private function calculate_time() {
		$installed_time = get_option( 'wpar_plugin_installed_time' );
		
        if ( ! $installed_time ) {
            $installed_time = time();
            update_option( 'wpar_plugin_installed_time', $installed_time, false );
        }

        return $installed_time;
	}
}