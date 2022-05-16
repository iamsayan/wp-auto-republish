<?php 
/**
 * Admin notices.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Base;

use WP_Dismiss_Notice;
use RevivePress\Helpers\Hooker;
use RevivePress\Base\BaseController;

defined( 'ABSPATH' ) || exit;

/**
 * Admin Notice class.
 */
class AdminNotice extends BaseController
{
	use Hooker;
	
	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'admin_notices', 'load_notice' );
		$this->action( 'admin_init', 'fix_action' );
		//$this->action( 'wpar/after_plugin_activate', 'fix_permalink' );
	}
	
	/**
	 * Show internal admin notices.
	 */
	public function load_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show a warning to sites running PHP < 7.2
		if ( version_compare( PHP_VERSION, '7.2', '<' ) ) {
			deactivate_plugins( $this->plugin );
			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
				unset( $_GET['activate'] ); // phpcs:ignore
			}
			echo '<div class="error"><p>' . sprintf( esc_html__( 'Your version of PHP is below the minimum version of PHP required by %s plugin. Please contact your host and request that your version be upgraded to 7.2 or later.', 'wp-auto-republish' ), esc_html( $this->name ) ) . '</p></div>';
			return;
		}

		$this->permalink_notice();
	}

	/**
	 * Show permalink notices.
	 */
	public function permalink_notice() {
		$show_notice = get_option( 'revivepress_hide_permalink_notice' );
		if ( $show_notice ) {
			return;
		}

		$permalink_structure = get_option( 'permalink_structure' );
		$fix_url = wp_nonce_url( add_query_arg( 'rvp_sync_permalink', 'yes' ), 'rvp_sync_permalink' );
		$hide = wp_nonce_url( add_query_arg( 'rvp_sync_permalink', 'hide' ), 'rvp_sync_permalink' );
		
		if ( preg_match( '(%year%|%monthnum%|%day%|%hour%|%minute%|%second%)', $permalink_structure ) === 1 ) { ?>
			<div class="notice notice-warning">
				<p style="line-height: 1.8;">
					<strong><?php echo esc_html( $this->name ); ?></strong>: <em><?php printf( esc_html__( 'As it seems that your permalinks structure contains date, please use %1$s instead of %2$s respectively. Otherwise, it may create SEO issues. But, if you want to use different permalink structure everytime after republish, you can safely dismiss this warning.', 'wp-auto-republish' ), '<code>%rvp_year%</code>, <code>%rvp_monthnum%</code>, <code>%rvp_day%</code>, <code>%rvp_hour%</code>, <code>%rvp_minute%</code>, <code>%rvp_second%</code>', '<code>%year%</code>, <code>%monthnum%</code>, <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>' ); ?></em>
				</p>
				<p style="margin-bottom: 10px;">
					<a href="<?php echo esc_url( $fix_url ); ?>" class="button button-secondary"><strong><?php esc_html_e( 'Fix Permalink Structure', 'wp-auto-republish' ); ?></strong></a>&nbsp;&nbsp;
					<a href="<?php echo esc_url( $hide ); ?>"><strong><?php esc_html_e( 'Dismiss Notice', 'wp-auto-republish' ); ?></strong></a>
				</p>
			</div> <?php
		}
	}

	/**
	 * Fix permalink action.
	 */
	public function fix_action() {
		if ( ! isset( $_REQUEST['rvp_sync_permalink'] ) ) {
			return;
		}

		check_admin_referer( 'rvp_sync_permalink' );
			
		if ( 'hide' === $_REQUEST['rvp_sync_permalink'] ) {
			update_option( 'revivepress_hide_permalink_notice', true );
		}

		if ( 'yes' === $_REQUEST['rvp_sync_permalink'] ) {
			$this->fix_permalink();
			flush_rewrite_rules();
		}
	
		wp_safe_redirect( admin_url( 'options-permalink.php' ) );
		exit;
	}

	/**
	 * Fix permalinks.
	 */
	public function fix_permalink() {
		$permalink_structure = get_option( 'permalink_structure' );

		$search = [ '%year%', '%monthnum%', '%day%', '%hour%', '%minute%', '%second%' ];
		$replace = [ '%rvp_year%', '%rvp_monthnum%', '%rvp_day%', '%rvp_hour%', '%rvp_minute%', '%rvp_second%' ];
		$permalink_structure = str_replace( $search, $replace, $permalink_structure );

		update_option( 'permalink_structure', $permalink_structure );
	}
}