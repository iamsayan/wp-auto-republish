<?php 
/**
 * Plugin Tools.
 *
 * @since      1.1.8
 * @package    RevivePress
 * @subpackage Wpar\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace Wpar\Base;

use Wpar\Helpers\Ajax;
use Wpar\Helpers\Hooker;
use Wpar\Helpers\HelperFunctions;

defined( 'ABSPATH' ) || exit;

/**
 * Admin Notice class.
 */
class PluginTools
{
	use Ajax, Hooker, HelperFunctions;
	
	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'admin_init', 'export_settings' );
		$this->action( 'admin_init', 'import_settings' );
		$this->action( 'admin_notices', 'admin_notice' );
		$this->ajax( 'process_copy_data', 'copy_data' );
		$this->ajax( 'process_import_data', 'import_data' );
		$this->ajax( 'process_delete_plugin_data', 'remove_settings' );
		$this->ajax( 'process_delete_post_metas', 'remove_metas' );
		$this->ajax( 'process_deschedule_posts', 'deschedule_posts' );
	}
	
	/**
     * Process a settings export that generates a .json file
     */
	public function export_settings() {
		if ( empty( $_POST['wpar_export_action'] ) || 'wpar_export_settings' != $_POST['wpar_export_action'] )
			return;
		if ( ! wp_verify_nonce( $_POST['wpar_export_nonce'], 'wpar_export_nonce' ) )
			return;
		if ( ! current_user_can( 'manage_options' ) )
			return;
		$settings = get_option( 'wpar_plugin_settings' );
		$url = get_home_url();
		$find = [ 'http://', 'https://' ];
		$replace = '';
		$output = str_replace( $find, $replace, $url );
		ignore_user_abort( true );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . str_replace( '/', '-', $output ) . '-wpar-export-' . gmdate( 'm-d-Y', $this->current_timestamp() ) . '.json' );
		header( "Expires: 0" );
		echo json_encode( $settings );
		exit;
	}

	/**
     * Process a settings import from a json file
     */
	public function import_settings() {
    	if ( empty( $_POST['wpar_import_action'] ) || 'wpar_import_settings' != $_POST['wpar_import_action'] )
    		return;
    	if ( ! wp_verify_nonce( $_POST['wpar_import_nonce'], 'wpar_import_nonce' ) )
    		return;
    	if ( ! current_user_can( 'manage_options' ) )
    		return;
        $extension = explode( '.', sanitize_text_field( $_FILES['import_file']['name'] ) );
        $file_extension = end( $extension );
    	if ( $file_extension != 'json' ) {
    		wp_die( __( '<strong>Settings import failed:</strong> Please upload a valid .json file to import settings in this website.', 'wp-auto-republish' ) );
    	}
    	$import_file = sanitize_text_field( $_FILES['import_file']['tmp_name'] );
    	if ( empty( $import_file ) ) {
    		wp_die( __( '<strong>Settings import failed:</strong> Please upload a file to import.', 'wp-auto-republish' ) );
    	}
    	// Retrieve the settings from the file and convert the json object to an array.
    	$settings = (array) json_decode( file_get_contents( $import_file ) );
		
		update_option( 'wpar_plugin_settings', $settings );

		// set temporary transient for admin notice
		set_transient( 'wpar_import_db_done', true );
        
		wp_safe_redirect( add_query_arg( 'page', 'wp-auto-republish', admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
     * Process a settings export from ajax request
     */
	public function copy_data() {
		// security check
		$this->verify_nonce();

		$option = get_option( 'wpar_plugin_settings' );

		//error_log( json_encode( $option ) );
	
		$this->success( [
			'settings_data' => json_encode( $option ),
		] );
	}

	/**
     * Process a settings import from ajax request
     */
	public function import_data() {
		// security check
		$this->verify_nonce();

		if ( ! isset( $_REQUEST['settings_data'] ) ) {
			$this->error();
		}

		$data = wp_unslash( $_REQUEST['settings_data'] );
		$settings = (array) json_decode( $data );

		if ( ! empty( $settings ) && is_array( $settings ) ) {
			update_option( 'wpar_plugin_settings', $settings );
			
			// set temporary transient for admin notice
		    set_transient( 'wpar_import_db_done', true );
		}

		$this->success();
	}

	/**
     * Process reset plugin settings
     */
	public function remove_settings() {
    	// security check
		$this->verify_nonce();
		
		delete_option( 'wpar_plugin_settings' );
		delete_option( 'wpar_republish_log_history' );
		delete_option( 'wpar_dashboard_widget_options' );
		delete_option( 'wpar_last_global_cron_run' );
		delete_option( 'wpar_global_republish_post_ids' );
		delete_option( 'wpar_social_credentials' );

		$this->success();
	}

	/**
     * Process reset plugin settings
     */
	public function remove_metas() {
    	// security check
		$this->verify_nonce();
		
		$this->set_single_action( time() + 10, 'wpar/remove_post_metadata' );

		$this->success();
	}

	/**
     * Process reset plugin settings
     */
	public function deschedule_posts() {
    	// security check
		$this->verify_nonce();
		
		$this->set_single_action( time() + 10, 'wpar/deschedule_posts' );

		$this->success();
	}

    /**
     * Process reset plugin settings
     */
	public function admin_notice() {
    	if ( get_transient( 'wpar_import_db_done' ) !== false ) { ?>
			<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e( 'Success! Plugin Settings has been imported successfully.', 'wp-auto-republish' ); ?></strong></p></div><?php 
		    delete_transient( 'wpar_import_db_done' );
	    }
	}
}