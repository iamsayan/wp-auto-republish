<?php

/**
 * Uninstallation hook.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Base;

/**
 * Uninstall class.
 */
class Uninstall
{
    /**
     * Run plugin uninstallation process.
     */
    public static function uninstall()
    {
        $options = get_option( 'wpar_plugin_settings' );
        if ( ! isset( $options['wpar_remove_plugin_data'] ) || ! ($options['wpar_remove_plugin_data'] == 1) ) {
            return;
        }
        
        if ( ! is_multisite() ) {
            self::remove_options();
            return;
        }
        
        global  $wpdb ;
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE archived = '0' AND spam = '0' AND deleted = '0'" );
        if ( ! empty($blog_ids) ) {
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                self::remove_options();
                restore_current_blog();
            }
        }
    }
    
    /**
     * Run plugin uninstallation process.
     */
    public static function remove_options()
    {
        global  $wpdb ;
        delete_option( 'wpar_plugin_settings' );
        delete_option( 'wpar_republish_log_history' );
        delete_option( 'wpar_dashboard_widget_options' );
        delete_option( 'wpar_last_global_cron_run' );
        delete_option( 'wpar_global_republish_post_ids' );
        delete_option( 'wpar_social_credentials' );
        // Delete post meta.
        $where = $wpdb->prepare( 'WHERE meta_key LIKE %s OR meta_key LIKE %s', '%' . $wpdb->esc_like( 'wpar_' ) . '%', '%' . $wpdb->esc_like( 'rvp_' ) . '%' );
        $wpdb->query( "DELETE FROM {$wpdb->postmeta} {$where}" );
        // Clear any cached data that has been removed.
        wp_cache_flush();
    }
}