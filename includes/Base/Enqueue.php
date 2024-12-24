<?php

/**
 * Enqueue all css & js.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Base;

use  RevivePress\Helpers\Hooker ;
use  RevivePress\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Script class.
 */
class Enqueue extends BaseController
{
    use  Hooker ;

    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'admin_enqueue_scripts', 'load_assets' );
    }
    
    /**
     * Load admin assets.
     */
    public function load_assets( $hook )
    {
        // Don't load assets on the Freemius opt-in/activation screen.
        if ( revivepress_fs()->is_activation_mode() && revivepress_fs()->is_activation_page() ) {
            return;
        }
        $this->load(
            'css',
            'jquery-ui',
            'jquery-ui.min.css',
            '1.13.1'
        );
        $this->load(
            'css',
            'jquery-ui-timepicker',
            'jquery-ui-timepicker-addon.min.css',
            '1.6.3'
        );
        $this->load(
            'css',
            'select2',
            'select2.min.css',
            '4.0.13'
        );
        $this->load(
            'css',
            'confirm',
            'jquery-confirm.min.css',
            '3.3.4'
        );
        $this->load(
            'css',
            'styles',
            'admin.min.css',
            $this->version,
            array(
				'revivepress-jquery-ui',
				'revivepress-jquery-ui-timepicker',
				'revivepress-select2',
				'revivepress-confirm',
			)
        );
        $this->load(
            'js',
            'datetimepicker',
            'jquery-ui-timepicker-addon.min.js',
            '1.6.3',
            array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' )
        );
        $this->load(
            'js',
            'select2',
            'select2.min.js',
            '4.0.13',
            array( 'jquery' )
        );
        $this->load(
            'js',
            'confirm',
            'jquery-confirm.min.js',
            '3.3.4',
            array( 'jquery' )
        );
        $this->load(
            'js',
            'admin',
            'admin.min.js',
            $this->version,
            array(
				'jquery',
				'jquery-form',
				'revivepress-datetimepicker',
				'revivepress-select2',
				'revivepress-confirm',
			)
        );
        wp_register_style(
            'revivepress-fa',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            array(),
            '6.5.1'
        );
        
        if ( 'toplevel_page_revivepress' === $hook ) {
            wp_enqueue_style( 'revivepress-select2' );
            wp_enqueue_style( 'revivepress-jquery-ui' );
            wp_enqueue_style( 'revivepress-jquery-ui-timepicker' );
            wp_enqueue_style( 'revivepress-fa' );
            wp_enqueue_style( 'revivepress-styles' );
            wp_enqueue_script( 'jquery-form' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'revivepress-datetimepicker' );
            wp_enqueue_script( 'revivepress-select2' );
            wp_enqueue_script( 'revivepress-confirm' );
            wp_enqueue_script( 'revivepress-admin' );
            wp_localize_script( 'revivepress-admin', 'rvpAdminL10n', array(
                'ajaxurl'              => admin_url( 'admin-ajax.php' ),
                'select_weekdays'      => __( 'Select weekdays (required)', 'wp-auto-republish' ),
                'select_post_types'    => __( 'Select post types (required)', 'wp-auto-republish' ),
                'select_post_statuses' => __( 'Select post statuses (required)', 'wp-auto-republish' ),
                'select_user_roles'    => __( 'Select user roles (required)', 'wp-auto-republish' ),
                'select_taxonomies'    => __( 'Select taxonomies', 'wp-auto-republish' ),
                'post_ids'             => __( 'Enter post or page or custom post ids (comma separated)', 'wp-auto-republish' ),
                'saving'               => __( 'Saving...', 'wp-auto-republish' ),
                'saving_text'          => __( 'Please wait while we are saving your settings...', 'wp-auto-republish' ),
                'done'                 => __( 'Done!', 'wp-auto-republish' ),
                'error'                => __( 'Error!', 'wp-auto-republish' ),
                'deleting'             => __( 'Deleting...', 'wp-auto-republish' ),
                'processing'           => __( 'Processing...', 'wp-auto-republish' ),
                'warning'              => __( 'Warning!', 'wp-auto-republish' ),
                'under_process'        => __( 'Please wait while we are processing your request...', 'wp-auto-republish' ),
                'save_button'          => __( 'Save Settings', 'wp-auto-republish' ),
                'save_success'         => __( 'Settings Saved Successfully!', 'wp-auto-republish' ),
                'are_you_sure'         => __( 'Are you sure that you want to delete this item?', 'wp-auto-republish' ),
                'process_failed'       => __( 'Invalid Nonce! We could not process your request.', 'wp-auto-republish' ),
                'ok_button'            => __( 'OK', 'wp-auto-republish' ),
                'confirm_button'       => __( 'Confirm', 'wp-auto-republish' ),
                'cancel_button'        => __( 'Cancel', 'wp-auto-republish' ),
                'close_btn'            => __( 'Close', 'wp-auto-republish' ),
                'paste_data'           => __( 'Paste Here', 'wp-auto-republish' ),
                'import_btn'           => __( 'Import', 'wp-auto-republish' ),
                'importing'            => __( 'Importing...', 'wp-auto-republish' ),
                'please_wait'          => __( 'Please wait...', 'wp-auto-republish' ),
                'no_logs_found'        => __( 'No logs found.', 'wp-auto-republish' ),
                'filter_btn'           => __( 'Filter', 'wp-auto-republish' ),
                'activating'           => __( 'Activating...', 'wp-auto-republish' ),
                'deactivating'         => __( 'Deactivating...', 'wp-auto-republish' ),
                'activate'             => __( 'Activate', 'wp-auto-republish' ),
                'deactivate'           => __( 'Deactivate', 'wp-auto-republish' ),
                'enabled'              => __( 'Enabled', 'wp-auto-republish' ),
                'disabled'             => __( 'Disabled', 'wp-auto-republish' ),
                'verify'               => __( 'Verify', 'wp-auto-republish' ),
                'new_account'          => __( 'New Account', 'wp-auto-republish' ),
                'is_empty'             => __( 'Please enter the required data first!', 'wp-auto-republish' ),
                'edit_template'        => __( 'Edit Template', 'wp-auto-republish' ),
                'save_template'        => __( 'Save Template', 'wp-auto-republish' ),
                'is_premium'           => revivepress_fs()->can_use_premium_code__premium_only(),
                'security'             => wp_create_nonce( 'rvp_admin_nonce' ),
                'api'                  => array(
					'root'  => esc_url_raw( get_rest_url() ),
					'nonce' => ( wp_installing() && ! is_multisite() ? '' : wp_create_nonce( 'wp_rest' ) ),
				),
            ) );
        }
    }
    
    /**
     * Register CSS & JS wrapper function.
     */
    private function load(
        $type,
        $handle,
        $name,
        $version,
        $dep = array(),
        $end = true
    )
    {
        
        if ( $type == 'css' ) {
            wp_register_style(
                'revivepress-' . $handle,
                $this->plugin_url . 'assets/css/' . $name,
                $dep,
                $version
            );
        } elseif ( $type == 'js' ) {
            wp_register_script(
                'revivepress-' . $handle,
                $this->plugin_url . 'assets/js/' . $name,
                $dep,
                $version,
                $end
            );
        }
    }
}