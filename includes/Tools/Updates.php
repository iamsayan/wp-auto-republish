<?php

/**
 * Functions and actions related to updates.
 *
 * @since      1.3.0
 * @package    RevivePress
 * @subpackage RevivePress\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Tools;

use  RevivePress\Helpers\Ajax ;
use  RevivePress\Helpers\Hooker ;
use  RevivePress\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Sction Migration class.
 */
class Updates extends BaseController
{
    use  Ajax ;
    use  Hooker ;

    /**
     * Updates that need to be run
     *
     * @var array
     */
    private static  $updates = array(
        '1.3.2' => 'updates/update-1.3.2.php',
        '1.3.5' => 'updates/update-1.3.5.php',
        '1.3.7' => 'updates/update-1.3.7.php',
    ) ;
    /**
     * Register hooks.
     */
    public function register()
    {
        $this->action( 'admin_init', 'do_updates' );
        $this->action( 'admin_enqueue_scripts', 'admin_pointer' );
        $this->ajax( 'process_hide_pointer', 'hide_pointer' );
    }
    
    /**
     * Check if any update is required.
     */
    public function do_updates()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $installed_version = get_option( 'revivepress_version', '1.0.0' );
        // Maybe it's the first install.
        if ( ! $installed_version ) {
            return;
        }
        if ( version_compare( $installed_version, $this->version, '<' ) ) {
            $this->perform_updates();
        }
    }
    
    /**
     * Perform all updates.
     */
    public function perform_updates()
    {
        $installed_version = get_option( 'revivepress_version', '1.0.0' );
        foreach ( self::$updates as $version => $path ) {
            
            if ( version_compare( $installed_version, $version, '<' ) ) {
                include_once $path;
                update_option( 'revivepress_version', $version, false );
            }        
}
        // Save install date.
        
        if ( false === boolval( get_option( 'revivepress_install_date' ) ) ) {
            update_option( 'revivepress_install_date', current_time( 'timestamp' ) );
            // phpcs:ignore
        }
        
        update_option( 'revivepress_version', $this->version, false );
    }
    
    /**
     * Initialise Admin Pointer
     *
     * Handles the bootstrapping of the admin pointer.
     * Mainly jQuery code that is self-initialising.
     *
     * @param string $hook_suffix The current admin page.
     * @since 1.3.2
     */
    public function admin_pointer( $hook_suffix )
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if ( ! in_array( $hook_suffix, array( 'index.php', 'plugins.php' ), true ) ) {
            return;
        }
        $db_version = get_option( 'revivepress_db_version', '1.0.0', false );
        $options = array(
            'heading'  => __( 'RevivePress', 'wp-auto-republish' ),
            'message'  => sprintf(
            /* translators: %s: settings page link */
            __( 'Go to %s to review & configure the plugin settings.', 'wp-auto-republish' ),
            '<a href="' . esc_url( add_query_arg( 'page', 'revivepress', admin_url( 'admin.php' ) ) ) . '">' . __( 'RevivePress > Dashbaord', 'wp-auto-republish' ) . '</a>'
        ),
            'version'  => '1.3.2',
            'security' => wp_create_nonce( 'rvp_admin_nonce' ),
        );
        
        if ( ! version_compare( $db_version, $options['version'], '<' ) ) {
            update_option( 'revivepress_db_version', $this->version, false );
            return;
        }
        
        
        if ( ! empty($options['message']) ) {
            wp_enqueue_script(
                'rvp-update-notice',
                $this->plugin_url . 'assets/js/update-notice.min.js',
                array( 'jquery', 'wp-pointer' ),
                $this->version,
                true
            );
            wp_localize_script( 'rvp-update-notice', 'rvpNoticeL10n', $options );
        }
    }
    
    /**
     * Hide pointer
     */
    public function hide_pointer()
    {
        // security check
        $this->verify_nonce();
        if ( ! current_user_can( 'manage_options' ) ) {
            $this->error();
        }
        update_option( 'revivepress_db_version', $this->version, false );
        $this->success();
    }
}