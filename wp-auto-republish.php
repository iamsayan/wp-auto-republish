<?php
/**
 * Plugin Name: WP Auto Republish
 * Plugin URI: https://wordpress.org/plugins/wp-auto-republish/
 * Description: The WP Auto Republish plugin helps revive old posts by resetting the publish date to the current date. This will push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.
 * Version: 1.0.4
 * Author: Sayan Datta
 * Author URI: https://www.sayandatta.com
 * License: GPLv3
 * Text Domain: wp-auto-republish
 * Domain Path: /languages
 * 
 * WP Auto Republish is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WP Auto Republish is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Auto Republish. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Core
 * @package  WP Auto Republish
 * @author   Sayan Datta
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/wp-auto-republish/
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define ( 'WPAR_PLUGIN_VERSION', '1.0.4' );

// Internationalization
add_action( 'plugins_loaded', 'wpar_plugin_load_textdomain' );
/**
 * Load plugin textdomain.
 * 
 * @since 1.0.0
 */
function wpar_plugin_load_textdomain() {
    load_plugin_textdomain( 'wp-auto-republish', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

// register activation hook
register_activation_hook( __FILE__, 'wpar_plugin_activation' );
// register deactivation hook
register_deactivation_hook( __FILE__, 'wpar_plugin_deactivation' );

function wpar_plugin_activation() {
    
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    $default = array(
        'wpar_enable_plugin'                => 1,
        'wpar_minimun_republish_interval'   => 43200,
        'wpar_random_republish_interval'    => 14400,
        'wpar_republish_post_age'           => 120,
        'wpar_republish_post_position'      => 1,
        'wpar_republish_method'             => 'old_first',
        'wpar_republish_position'           => 'disable',
        'wpar_republish_position_text'      => 'Originally posted on ',
        'wpar_exclude_by_type'              => 'exclude',
        'wpar_exclude_by'                   => 'category',
        'wpar_exclude_category'             => array(),
        'wpar_exclude_tag'                  => array(),
        'wpar_override_category_tag'        => '',
        'wpar_days'                         => array('sun','mon','tue','wed','thu','fri','sat'),
        'wpar_start_time'                   => '05:00:00',
        'wpar_end_time'                     => '23:00:00',
    );
    update_option( 'wpar_plugin_settings', $default );
    set_transient( 'wpar-admin-notice-on-activation', true, 20 );
}

function wpar_plugin_deactivation() {

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    delete_option( 'wpar_last_update' );
    delete_option( 'wpar_plugin_dismiss_rating_notice' );
    delete_option( 'wpar_plugin_no_thanks_rating_notice' );
    delete_option( 'wpar_plugin_installed_time' );
}

function wpar_plugin_install_notice() { 

    if( get_transient( 'wpar-admin-notice-on-activation' ) ) { ?>
        <div class="notice notice-success">
            <p><strong><?php printf( __( 'Thanks for installing %1$s v%2$s plugin. Click <a href="%3$s">here</a> to configure plugin settings.', 'wp-auto-republish' ), 'WP Auto Republish', WPAR_PLUGIN_VERSION, admin_url( 'options-general.php?page=wp-auto-republish' ) ); ?></strong></p>
        </div> <?php
        delete_transient( 'wpar-admin-notice-on-activation' );
    }
    
    if( preg_match( '(%year%|%monthnum%|%day%|%hour%|%minute%|%second%)', get_option('permalink_structure') ) === 1 ) { ?>
        <div class="notice notice-warning">
            <p><strong><?php printf( __( 'WARNING: As it seems that your permalinks structure contain date, please disable the WP Auto Republish plugin immediately.', 'wp-auto-republish' ) ); ?></strong></p>
        </div> <?php
    }
}

add_action( 'admin_notices', 'wpar_plugin_install_notice' ); 

function wpar_load_admin_assets() {
    // get current screen
    $current_screen = get_current_screen();
    if ( strpos( $current_screen->base, 'wp-auto-republish') !== false ) {
        wp_enqueue_style( 'wpar-styles', plugins_url( 'admin/css/admin.min.css', __FILE__ ), array(), WPAR_PLUGIN_VERSION );
        wp_enqueue_style( 'wpar-selectize-css', plugins_url( 'admin/css/selectize.min.css', __FILE__ ), array(), '0.12.6' );
        wp_enqueue_script( 'wpar-admin-js', plugins_url( 'admin/js/admin.min.js', __FILE__ ), array(), WPAR_PLUGIN_VERSION );
        wp_enqueue_script( 'wpar-selectize-js', plugins_url( 'admin/js/selectize.min.js', __FILE__ ), array(), '0.12.6' );
    }
}

add_action( 'admin_enqueue_scripts', 'wpar_load_admin_assets' );

function wpar_ajax_save_admin_scripts() {
    if ( is_admin() ) { 
        // Embed the Script on our Plugin's Option Page Only
        if ( isset($_GET['page']) && $_GET['page'] == 'wp-auto-republish' ) {
            wp_enqueue_script('jquery');
            wp_enqueue_script( 'jquery-form' );
        }
    }
}

add_action( 'admin_init', 'wpar_ajax_save_admin_scripts' );

// register settings
add_action( 'admin_init', 'wpar_register_plugin_settings' );

function wpar_register_plugin_settings() {

    $wpar_settings = get_option('wpar_plugin_settings');

    add_settings_section('wpar_plugin_section', '', null, 'wpar_plugin_option');

        add_settings_field('wpar_enable_plugin', __( 'Enable Auto Republish?', 'wp-auto-republish' ), 'wpar_enable_plugin_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-enable' ));
        add_settings_field('wpar_minimun_republish_interval', __( 'Minimum Republish Interval:', 'wp-auto-republish' ), 'wpar_minimun_republish_interval_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-minimum' ));
        add_settings_field('wpar_random_republish_interval', __( 'Randomness Interval:', 'wp-auto-republish' ), 'wpar_random_republish_interval_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-random' ));
        add_settings_field('wpar_republish_post_age', __( 'Post Republish Eligibility Age:', 'wp-auto-republish' ), 'wpar_republish_post_age_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-age' ));
        add_settings_field('wpar_republish_method', __( 'Select Old Posts Query Method:', 'wp-auto-republish' ), 'wpar_republish_method_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-method' ));
        add_settings_field('wpar_republish_post_position', __( 'Republish Post to Position:', 'wp-auto-republish' ), 'wpar_republish_post_position_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-promotion' ));
        add_settings_field('wpar_republish_position', __( 'Show Original Publication Date:', 'wp-auto-republish' ), 'wpar_republish_position_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-position' ));
        add_settings_field('wpar_republish_position_text', __( 'Original Publication Message:', 'wp-auto-republish' ), 'wpar_republish_position_text_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-text', 'class' => 'wpar-text' ));
        add_settings_field('wpar_exclude_by_type', __( 'Auto Republish Old Posts by:', 'wp-auto-republish' ), 'wpar_exclude_by_type_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-exclude-type' ));
        add_settings_field('wpar_exclude_by', __( 'Select Taxonomy:', 'wp-auto-republish' ), 'wpar_exclude_by_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-taxonomy', 'class' => 'wpar-taxonomy' ));
        add_settings_field('wpar_exclude_category', __( 'Select Post Categories:', 'wp-auto-republish' ), 'wpar_exclude_category_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-cat', 'class' => 'wpar-cat' ));
        add_settings_field('wpar_exclude_tag', __( 'Select Post Tags:', 'wp-auto-republish' ), 'wpar_exclude_tag_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-tag', 'class' => 'wpar-tag' ));
        add_settings_field('wpar_override_category_tag', __( 'Override Category or Post Tags Filtering for Specific Posts:', 'wp-auto-republish' ), 'wpar_override_category_tag_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-override-cat-tag', 'class' => 'wpar-override-cat-tag' ));
        add_settings_field('wpar_days', __( 'Select Weekdays to Republish:', 'wp-auto-republish' ), 'wpar_days_display', 'wpar_plugin_option', 'wpar_plugin_section', array( 'label_for' => 'wpar-days' ));
        add_settings_field('wpar_time', __( 'Time Range for Republishing:', 'wp-auto-republish' ), 'wpar_time_display', 'wpar_plugin_option', 'wpar_plugin_section');
        
    //register settings
    register_setting( 'wpar_plugin_settings_fields', 'wpar_plugin_settings' );
}

require_once plugin_dir_path( __FILE__ ) . 'admin/settings-fields.php';

// register admin menu
add_action( 'admin_menu', 'wpar_admin_menu' );

function wpar_admin_menu() {
    //Add admin menu option
    add_submenu_page( 'options-general.php', __( 'WP Auto Republish', 'wp-auto-republish' ), __( 'WP Auto Republish', 'wp-auto-republish' ), 'manage_options', 'wp-auto-republish', 'wpar_plugin_settings_page' );
}

function wpar_plugin_settings_page() { 
    $wpar_settings = get_option( 'wpar_plugin_settings' ); 
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
}

$wpar_settings = get_option( 'wpar_plugin_settings' ); 
if ( isset( $wpar_settings['wpar_enable_plugin'] ) && $wpar_settings['wpar_enable_plugin'] == 1 ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/core.php';
}

require_once plugin_dir_path( __FILE__ ) . 'admin/notice.php';

// add action links
function wpar_add_action_links ( $links ) {
    $wparlinks = array(
        '<a href="' . admin_url( 'options-general.php?page=wp-auto-republish' ) . '">' . __( 'Settings', 'wp-auto-republish' ) . '</a>',
    );
    return array_merge( $wparlinks, $links );
}

function wpar_plugin_meta_links( $links, $file ) {
    $plugin = plugin_basename(__FILE__);
    if ( $file == $plugin ) // only for this plugin
        return array_merge( $links, 
            array( '<a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank">' . __( 'Support', 'wp-auto-republish' ) . '</a>' ),
            array( '<a href="http://bit.ly/2I0Gj60" target="_blank">' . __( 'Donate', 'wp-auto-republish' ) . '</a>' )
        );
    return $links;
}

// plugin action links
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpar_add_action_links', 10, 2 );

// plugin row elements
add_filter( 'plugin_row_meta', 'wpar_plugin_meta_links', 10, 2 );