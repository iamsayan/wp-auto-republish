<?php

/**
 * Plugin Name: RevivePress
 * Plugin URI: https://wprevivepress.com?utm_source=landing&utm_medium=plugin
 * Description: RevivePress, the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.
 * Version: 1.4.4
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
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'revivepress_fs' ) ) {
    revivepress_fs()->set_basename( false, __FILE__ );
    return;
} else {
    // Create a helper function for easy SDK access.
    function revivepress_fs() {
        global  $revivepress_fs ;
        
        if ( ! isset( $revivepress_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $revivepress_fs = fs_dynamic_init( [
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
					'slug'        => 'revivepress',
					'support'     => false,
					'affiliation' => false,
				],
                'is_live'        => true,
            ] );
        }
        
        return $revivepress_fs;
    }
    
    // Init Freemius.
    revivepress_fs();
    // Signal that SDK was initiated.
    do_action( 'revivepress_fs_loaded' );
}

// Define constants
if ( ! defined( 'REVIVEPRESS_VERSION' ) ) {
    define( 'REVIVEPRESS_VERSION', '1.4.4' );
}
// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}
/**
 * The code that runs during plugin activation
 */
if ( ! function_exists( 'revivepress_activation' ) ) {
    function revivepress_activation() {
        RevivePress\Base\Activate::activate();
    }
}
register_activation_hook( __FILE__, 'revivepress_activation' );
/**
 * The code that runs during plugin deactivation
 */
if ( ! function_exists( 'revivepress_deactivation' ) ) {
    function revivepress_deactivation() {
        RevivePress\Base\Deactivate::deactivate();
    }
}
register_deactivation_hook( __FILE__, 'revivepress_deactivation' );
/**
 * The code that runs during plugin uninstalltion
 */
if ( ! function_exists( 'revivepress_uninstallation' ) ) {
    function revivepress_uninstallation() {
        RevivePress\Base\Uninstall::uninstall();
    }
}
revivepress_fs()->add_action( 'after_uninstall', 'revivepress_uninstallation' );
/**
 * Initialize all the core classes of the plugin
 */
if ( ! function_exists( 'revivepress_init' ) ) {
    function revivepress_init() {
        if ( class_exists( 'RevivePress\\Loader' ) ) {
            RevivePress\Loader::register_services();
        }
    }
}
revivepress_init();
/**
 * Add RevivePress icon to freemius
 */
if ( ! function_exists( 'revivepress_freemius_logo' ) ) {
    function revivepress_freemius_logo() {
        return dirname( __FILE__ ) . '/assets/images/logo.png';
    }
}
revivepress_fs()->add_filter( 'plugin_icon', 'revivepress_freemius_logo' );
/**
 * Flag Freemius options
 */
revivepress_fs()->add_filter( 'hide_freemius_powered_by', '__return_true' );
revivepress_fs()->add_filter( 'show_affiliate_program_notice', '__return_false' );
revivepress_fs()->add_filter( 'show_deactivation_subscription_cancellation', '__return_false' );