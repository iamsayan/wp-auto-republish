<?php

/**
 * Plugin Name: RevivePress
 * Plugin URI: https://wpautorepublish.com
 * Description: RevivePress (formerly WP Auto Republish), the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.
 * Version: 1.3.0
 * Author: Sayan Datta
 * Author URI: https://sayandatta.in
 * License: GPLv3
 * Text Domain: wp-auto-republish
 * Domain Path: /languages
 * 
 * RevivePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * RevivePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with RevivePress. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Core
 * @package  RevivePress
 * @author   Sayan Datta <iamsayan@protonmail.com>
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/wp-auto-republish/
 * 
 * 
 */
// If this file is called firectly, abort!!!
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wpar_load_fs_sdk' ) ) {
    wpar_load_fs_sdk()->set_basename( false, __FILE__ );
    return;
}

// include freemius sdk

if ( !function_exists( 'wpar_load_fs_sdk' ) ) {
    // Create a helper function for easy SDK access.
    function wpar_load_fs_sdk()
    {
        global  $wpar_load_fs_sdk ;
        
        if ( !isset( $wpar_load_fs_sdk ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $wpar_load_fs_sdk = fs_dynamic_init( [
                'id'             => '5789',
                'slug'           => 'wp-auto-republish',
                'type'           => 'plugin',
                'public_key'     => 'pk_94e7891c5190ae1f9af5110b0f6eb',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'slug'    => 'revivepress',
                'support' => false,
            ],
                'is_live'        => true,
            ] );
        }
        
        return $wpar_load_fs_sdk;
    }
    
    // Init Freemius.
    wpar_load_fs_sdk();
    // Signal that SDK was initiated.
    do_action( 'wpar_load_fs_sdk_loaded' );
}

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}
/**
 * The code that runs during plugin activation
 */

if ( !function_exists( 'wpar_plugin_activation' ) ) {
    function wpar_plugin_activation()
    {
        Wpar\Base\Activate::activate();
    }
    
    register_activation_hook( __FILE__, 'wpar_plugin_activation' );
}

/**
 * The code that runs during plugin deactivation
 */

if ( !function_exists( 'wpar_plugin_deactivation' ) ) {
    function wpar_plugin_deactivation()
    {
        Wpar\Base\Deactivate::deactivate();
    }
    
    register_deactivation_hook( __FILE__, 'wpar_plugin_deactivation' );
}

/**
 * The code that runs during plugin uninstalltion
 */

if ( !function_exists( 'wpar_plugin_uninstallation' ) ) {
    function wpar_plugin_uninstallation()
    {
        Wpar\Base\Uninstall::uninstall();
    }
    
    wpar_load_fs_sdk()->add_action( 'after_uninstall', 'wpar_plugin_uninstallation' );
}

/**
 * Initialize all the core classes of the plugin
 */

if ( !function_exists( 'wpar_plugin_init' ) ) {
    function wpar_plugin_init()
    {
        if ( class_exists( 'Wpar\\WPARLoader' ) ) {
            Wpar\WPARLoader::register_services();
        }
    }
    
    wpar_plugin_init();
}
