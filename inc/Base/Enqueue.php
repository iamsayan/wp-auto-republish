<?php

/**
 * Enqueue all css & js.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Inc\Base;

use  Inc\Helpers\Hooker ;
use  Inc\Base\BaseController ;
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
            'wpar-selectize',
            $this->plugin_url . 'assets/css/selectize.min.css',
            [],
            '0.12.6'
        );
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
            'wpar-styles',
            $this->plugin_url . 'assets/css/admin.min.css',
            [ 'wpar-selectize', 'wpar-jquery-ui-datepicker', 'wpar-jquery-ui-datetimepicker' ],
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
            [],
            '0.12.6',
            true
        );
        wp_register_script(
            'wpar-admin-js',
            $this->plugin_url . 'assets/js/admin.min.js',
            [ 'jquery', 'wpar-datetimepicker-js', 'wpar-selectize-js' ],
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
            wp_enqueue_script( 'wpar-admin-js' );
            wp_localize_script( 'wpar-admin-js', 'wpar_admin_i10n', [
                'select_category'   => __( '-- Select categories or tags --', 'wp-auto-republish' ),
                'select_weekdays'   => __( '-- Select weekdays (required) --', 'wp-auto-republish' ),
                'select_post_types' => __( '-- Select post types (required) --', 'wp-auto-republish' ),
                'select_taxonomies' => __( '-- Select custom taxonomies --', 'wp-auto-republish' ),
                'post_ids'          => __( '-- Enter post or page or custom post ids (comma separated) --', 'wp-auto-republish' ),
            ] );
        }
    
    }

}