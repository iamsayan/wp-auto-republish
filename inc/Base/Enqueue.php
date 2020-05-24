<?php

/**
 * Enqueue all css & js.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Base;

use  Wpar\Helpers\Hooker ;
use  Wpar\Base\BaseController ;
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
        $this->action( 'admin_enqueue_scripts', 'admin_assets' );
    }
    
    /**
     * Load admin assets.
     */
    public function admin_assets()
    {
        $version = $this->version;
        if ( $this->debug === true ) {
            $version = time();
        }
        wp_register_style(
            'wpar-jquery-ui-datepicker',
            $this->plugin_url . 'assets/css/jquery-ui.min.css',
            [],
            '1.12.1'
        );
        wp_register_style(
            'wpar-jquery-ui-datetimepicker',
            $this->plugin_url . 'assets/css/jquery-ui-timepicker-addon.min.css',
            [],
            '1.6.3'
        );
        wp_register_style(
            'wpar-selectize',
            $this->plugin_url . 'assets/css/selectize.min.css',
            [],
            '0.12.6'
        );
        wp_register_style(
            'wpar-confirm',
            $this->plugin_url . 'assets/css/jquery-confirm.min.css',
            [],
            '3.3.4'
        );
        wp_register_style(
            'wpar-styles',
            $this->plugin_url . 'assets/css/admin.min.css',
            [
            'wpar-jquery-ui-datepicker',
            'wpar-jquery-ui-datetimepicker',
            'wpar-selectize',
            'wpar-confirm'
        ],
            $version
        );
        wp_register_script(
            'wpar-datetimepicker-js',
            $this->plugin_url . 'assets/js/jquery-ui-timepicker-addon.min.js',
            [ 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ],
            '1.6.3',
            true
        );
        wp_register_script(
            'wpar-selectize-js',
            $this->plugin_url . 'assets/js/selectize.min.js',
            [ 'jquery' ],
            '0.12.6',
            true
        );
        wp_register_script(
            'wpar-confirm-js',
            $this->plugin_url . 'assets/js/jquery-confirm.min.js',
            [ 'jquery' ],
            '3.3.4',
            true
        );
        wp_register_script(
            'wpar-admin-js',
            $this->plugin_url . 'assets/js/admin.min.js',
            [
            'jquery',
            'wpar-datetimepicker-js',
            'wpar-selectize-js',
            'wpar-confirm-js'
        ],
            $version,
            true
        );
        // get current screen
        $current_screen = get_current_screen();
        
        if ( strpos( $current_screen->base, 'wp-auto-republish' ) !== false ) {
            wp_enqueue_style( 'wpar-selectize-css' );
            wp_enqueue_style( 'wpar-jquery-ui-datepicker' );
            wp_enqueue_style( 'wpar-jquery-ui-datetimepicker' );
            wp_enqueue_style( 'wpar-styles' );
            wp_enqueue_script( 'jquery-form' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'wpar-datetimepicker-js' );
            wp_enqueue_script( 'wpar-selectize-js' );
            wp_enqueue_script( 'wpar-confirm-js' );
            wp_enqueue_script( 'wpar-admin-js' );
            wp_localize_script( 'wpar-admin-js', 'wpar_admin_i10n', [
                'ajaxurl'           => admin_url( 'admin-ajax.php' ),
                'select_category'   => __( 'Select categories or tags', 'wp-auto-republish' ),
                'select_weekdays'   => __( 'Select weekdays (required)', 'wp-auto-republish' ),
                'select_post_types' => __( 'Select post types (required)', 'wp-auto-republish' ),
                'select_taxonomies' => __( 'Select custom taxonomies', 'wp-auto-republish' ),
                'post_ids'          => __( 'Enter post or page or custom post ids (comma separated)', 'wp-auto-republish' ),
                'saving'            => __( 'Saving...', 'wp-auto-republish' ),
                'saving_text'       => __( 'Please wait while we are saving your settings.', 'wp-auto-republish' ),
                'done'              => __( 'Done!', 'wp-auto-republish' ),
                'error'             => __( 'Error!', 'wp-auto-republish' ),
                'deleting'          => __( 'Deleting...', 'wp-auto-republish' ),
                'warning'           => __( 'Warning!', 'wp-auto-republish' ),
                'process_success'   => __( 'Please wait while we are saving your settings.', 'wp-auto-republish' ),
                'process_delete'    => __( 'Please wait while we are processing your request.', 'wp-auto-republish' ),
                'save_button'       => __( 'Save Settings', 'wp-auto-republish' ),
                'save_success'      => __( 'Settings saved successfully!', 'wp-auto-republish' ),
                'delete_success'    => __( 'All post data deleted successfully!', 'wp-auto-republish' ),
                'delete_confirm'    => __( 'It will delete all the data relating to single post republishing. Do you want to continue?', 'wp-auto-republish' ),
                'process_failed'    => __( 'We could not process your request.', 'wp-auto-republish' ),
                'ok_button'         => __( 'OK', 'wp-auto-republish' ),
                'confirm_button'    => __( 'Confirm', 'wp-auto-republish' ),
                'cancel_button'     => __( 'Cancel', 'wp-auto-republish' ),
                'security'          => wp_create_nonce( 'wpar_delete_post_meta' ),
            ] );
        }
    
    }

}