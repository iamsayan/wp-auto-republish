<?php

/**
 * Plugin Tools.
 *
 * @since      1.1.8
 * @package    RevivePress
 * @subpackage RevivePress\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Tools;

use  WP_REST_Server ;
use  RevivePress\Helpers\Ajax ;
use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\Scheduler ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Admin Notice class.
 */
class Database
{
    use  Ajax ;
    use  Hooker ;
    use  HelperFunctions ;
    use  Scheduler ;

    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'rest_api_init', 'register_routes' );
        $this->action( 'admin_init', 'export_settings' );
        $this->action( 'admin_init', 'import_settings' );
        $this->action( 'admin_notices', 'admin_notice' );
        $this->filter( 'wpar/tools/remove_data', 'remove_data' );
        $this->filter( 'wpar/tools/remove_meta', 'run_cleanup' );
        $this->filter( 'wpar/tools/deschedule_posts', 'deschedule_posts' );
        $this->filter( 'wpar/tools/regenerate_interval', 'regenerate_interval' );
        $this->filter( 'wpar/tools/regenerate_schedule', 'regenerate_schedule' );
        $this->filter( 'wpar/tools/recreate_tables', 'maybe_recreate_actionscheduler_tables' );
        $this->action( 'wpar/deschedule_posts_task', 'deschedule_posts_task' );
        $this->action( 'action_scheduler_canceled_action', 'action_cancelled' );
        $this->action( 'action_scheduler_deleted_action', 'action_removed' );
        // AJAX.
        $this->ajax( 'process_copy_data', 'copy_data' );
        $this->ajax( 'process_import_data', 'import_data' );
    }
    
    /**
     * Registers the routes for the objects of the controller.
     */
    public function register_routes()
    {
        register_rest_route( 'revivepress/v1', '/toolsAction', array(
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'tools_actions' ),
            'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        },
        ) );
    }
    
    /**
     * Tools actions.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function tools_actions( \WP_REST_Request $request )
    {
        $action = $request->get_param( 'action' );
        return apply_filters( 'wpar/tools/' . $action, 'Something went wrong.', $request );
    }
    
    /**
     * Process a settings export that generates a .json file
     */
    public function export_settings()
    {
        if ( empty($_POST['rvp_export_action']) || 'rvp_export_settings' != $_POST['rvp_export_action'] ) {
            return;
        }
        if ( ! isset( $_POST['rvp_export_nonce'] ) || ! wp_verify_nonce( $_POST['rvp_export_nonce'], 'rvp_export_nonce' ) ) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $settings = get_option( 'wpar_plugin_settings' );
        $url = get_home_url();
        $find = array( 'http://', 'https://' );
        $replace = '';
        $output = str_replace( $find, $replace, $url );
        ignore_user_abort( true );
        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . str_replace( '/', '-', $output ) . '-revivepress-export-' . date( 'm-d-Y', $this->current_timestamp() ) . '.json' );
        header( "Expires: 0" );
        echo  wp_json_encode( $settings ) ;
        exit;
    }
    
    /**
     * Process a settings import from a json file
     */
    public function import_settings()
    {
        if ( empty($_POST['rvp_import_action']) || 'rvp_import_settings' != $_POST['rvp_import_action'] ) {
            return;
        }
        if ( ! isset( $_POST['rvp_import_nonce'] ) || ! wp_verify_nonce( $_POST['rvp_import_nonce'], 'rvp_import_nonce' ) ) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if ( empty($_FILES['import_file']['name']) || empty($_FILES['import_file']['tmp_name']) ) {
            wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a file to import.', 'wp-auto-republish' ) ) );
        }
        $extension = explode( '.', sanitize_text_field( wp_unslash( $_FILES['import_file']['name'] ) ) );
        $file_extension = end( $extension );
        if ( 'json' !== $file_extension ) {
            wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a valid .json file to import settings in this website.', 'wp-auto-republish' ) ) );
        }
        $import_file = sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) );
        if ( empty($import_file) ) {
            wp_die( wp_kses_post( __( '<strong>Settings import failed:</strong> Please upload a file to import.', 'wp-auto-republish' ) ) );
        }
        // Retrieve the settings from the file and convert the json object to an array.
        $settings = (array) json_decode( file_get_contents( $import_file ) );
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        update_option( 'wpar_plugin_settings', $settings, false );
        // set temporary transient for admin notice
        set_transient( 'rvp_import_db_done', true );
        wp_safe_redirect( add_query_arg( 'page', 'revivepress', admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Process a settings export from ajax request
     */
    public function copy_data()
    {
        // security check
        $this->verify_nonce();
        if ( ! current_user_can( 'manage_options' ) ) {
            $this->error( __( 'Error: Missing permission!', 'wp-auto-republish' ) );
        }
        $option = get_option( 'wpar_plugin_settings' );
        $this->success( array(
            'settings_data' => wp_json_encode( $option ),
        ) );
    }
    
    /**
     * Process a settings import from ajax request
     */
    public function import_data()
    {
        // security check
        $this->verify_nonce();
        if ( ! current_user_can( 'manage_options' ) ) {
            $this->error( __( 'Error: Missing permission!', 'wp-auto-republish' ) );
        }
        if ( ! isset( $_REQUEST['settings_data'] ) ) {
            $this->error();
        }
        $data = wp_unslash( $_REQUEST['settings_data'] );
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $settings = (array) json_decode( $data );
        
        if ( ! empty($settings) && is_array( $settings ) ) {
            update_option( 'wpar_plugin_settings', $settings, false );
            // set temporary transient for admin notice
            set_transient( 'rvp_import_db_done', true );
        }
        
        $this->success();
    }
    
    /**
     * Process reset plugin settings
     */
    public function admin_notice()
    {
        
        if ( get_transient( 'rvp_import_db_done' ) !== false ) {
            ?>
			<div class="notice notice-success is-dismissible"><p><strong><?php 
            esc_html_e( 'Success! Plugin Settings has been imported successfully.', 'wp-auto-republish' );
            ?></strong></p></div><?php 
            delete_transient( 'rvp_import_db_done' );
        }
    }
    
    /**
     * Trigger when Action Scheduler action is cancelled.
     * 
     * @param int   $action_id  Action ID
     */
    public function action_cancelled( $action_id )
    {
        $run_remove_hook = true;
        
        if ( \ActionScheduler::is_initialized() ) {
            $action = \ActionScheduler::store()->fetch_action( $action_id );
            if ( $action || ! is_a( $action, 'ActionScheduler_NullAction' ) ) {
                $run_remove_hook = false;
            }
        }
        
        
        if ( $run_remove_hook ) {
            $this->action_removed( $action_id );
        } else {
            $hook = $action->get_hook();
            $args = $action->get_args();
            $group = $action->get_group();
            $action_list = array( 'wpar/global_republish_single_post', 'wpar/run_single_republish', 'wpar/run_republish_rule_event' );
            
            if ( in_array( $hook, $action_list, true ) ) {
                $post = get_post( $args[0] );
                
                if ( ! is_object( $post ) ) {
                    $this->action_removed( $action_id );
                } else {
                    $is_saving = $this->get_meta( $post->ID, 'wpar_post_is_saving' );
                    if ( ! $is_saving ) {
                        $this->perform_cleanup_regeneration( $post->ID );
                    }
                }            
}        
}
    }
    
    /**
     * Trigger when Action Scheduler action is deleted.
     * 
     * @param int   $action_id  Action ID
     */
    public function action_removed( $action_id )
    {
        $post_ids = $this->get_posts( array(
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_type'      => 'any',
            'fields'         => 'ids',
            'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'wpar_post_is_saving',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'wpar_republish_as_action_id',
					'value'   => $action_id,
					'compare' => '=',
				),
			),
        ) );
        if ( ! empty($post_ids) ) {
            foreach ( $post_ids as $post_id ) {
                $this->perform_cleanup_regeneration( $post_id );
            }
        }
    }
    
    /**
     * Trigger when Action Scheduler action is cancelled or deleted.
     * 
     * @param int   $post_id  Post ID
     */
    private function perform_cleanup_regeneration( $post_id )
    {
        $this->do_action( 'as_action_removed', $post_id );
        // post meta removal
        $this->delete_meta( $post_id, 'wpar_global_republish_status' );
        $this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
    }
    
    /**
     * Process reset plugin settings
     */
    public function remove_data()
    {
        delete_option( 'wpar_plugin_settings' );
        delete_option( 'wpar_republish_log_history' );
        delete_option( 'wpar_dashboard_widget_options' );
        delete_option( 'wpar_last_global_cron_run' );
        delete_option( 'wpar_global_republish_post_ids' );
        delete_option( 'wpar_social_credentials' );
        return __( 'Data removal completed.', 'wp-auto-republish' );
    }
    
    /**
     * Process regenerate global republish interval
     */
    public function regenerate_interval()
    {
        // remove last data
        \delete_option( 'wpar_next_scheduled_timestamp' );
        return __( 'Republish Interval regenerated.', 'wp-auto-republish' );
    }
    
    /**
     * Process regenerate republish schedules
     */
    public function regenerate_schedule()
    {
        return __( 'Republish Schedule re-generation started. It might take a couple of minutes.', 'wp-auto-republish' );
    }
    
    /**
     * Post meta cleanup.
     */
    public function run_cleanup()
    {
        global  $wpdb ;
        // Remove schedules
        $this->unschedule_all_actions( 'wpar/global_republish_single_post', array(), '' );
        $post_types = $this->get_post_types( true );
        $args = array(
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => array( 'publish', 'future', 'private' ),
            'fields'      => 'ids',
        );
        $post_ids = $this->get_posts( $args );
        
        if ( ! empty($post_ids) ) {
            $post_ids_placeholders = implode( ', ', array_fill( 0, count( $post_ids ), '%d' ) );
            // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
            $where = $wpdb->prepare( "WHERE post_id IN ( {$post_ids_placeholders} ) AND ( meta_key LIKE %s OR meta_key LIKE %s )", array_merge( $post_ids, array( '%' . $wpdb->esc_like( 'wpar_' ) . '%', '%' . $wpdb->esc_like( 'rvp_' ) . '%' ) ) );
            $wpdb->query( "DELETE FROM {$wpdb->postmeta} {$where}" );
            // phpcs:enable
        }
        
        return __( 'Cleanup task started. It might take a couple of minutes.', 'wp-auto-republish' );
    }
    
    /**
     * Remove actions.
     */
    public function deschedule_posts()
    {
        $post_types = $this->get_post_types( true );
        $args = $this->do_filter( 'deschedule_posts_args', array(
            'post_type'   => $post_types,
            'numberposts' => -1,
            'post_status' => array( 'publish', 'future', 'private' ),
            'fields'      => 'ids',
            'meta_query'  => array(
				array(
					'key'     => '_wpar_original_pub_date',
					'compare' => 'EXISTS',
				),
			),
        ) );
        $post_ids = $this->get_posts( $args );
        $this->schedule_batch_actions( $post_ids, 'wpar/deschedule_posts_task' );
        return __( 'Post de-scheduling started. It might take a couple of minutes.', 'wp-auto-republish' );
    }
    
    /**
     * Remove actions.
     */
    public function deschedule_posts_task( array $post_ids )
    {
        if ( ! empty($post_ids) ) {
            foreach ( $post_ids as $post_id ) {
                // get original published date
                $pub_date = $this->get_meta( $post_id, '_wpar_original_pub_date' );
                // update posts
                \wp_update_post( array(
                    'ID'            => $post_id,
                    'post_date'     => $pub_date,
                    'post_date_gmt' => \get_gmt_from_date( $pub_date ),
                ) );
                // delete old meta
                $this->delete_meta( $post_id, '_wpar_original_pub_date' );
            }
        }
    }
    
    /**
     * Recreate ActionScheduler tables if missing.
     */
    public function maybe_recreate_actionscheduler_tables()
    {
        global  $wpdb ;
        if ( $this->is_woocommerce_active() ) {
            return;
        }
        if ( ! class_exists( 'ActionScheduler_HybridStore' ) || ! class_exists( 'ActionScheduler_StoreSchema' ) || ! class_exists( 'ActionScheduler_LoggerSchema' ) ) {
            return;
        }
        $table_list = array(
            'actionscheduler_actions',
            'actionscheduler_logs',
            'actionscheduler_groups',
            'actionscheduler_claims',
        );
        $found_tables = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}actionscheduler%'" );
        foreach ( $table_list as $table_name ) {
            
            if ( ! in_array( $wpdb->prefix . $table_name, $found_tables, true ) ) {
                $this->recreate_actionscheduler_tables();
                break;
            }        
}
        return __( 'Table re-creation started. It might take a couple of minutes.', 'wp-auto-republish' );
    }
    
    /**
     * Force the data store schema updates.
     */
    private function recreate_actionscheduler_tables()
    {
        $store = new \ActionScheduler_HybridStore();
        // @phpstan-ignore-line
        add_action(
            'action_scheduler/created_table',
            array( $store, 'set_autoincrement' ),
            10,
            2
        );
        $store_schema = new \ActionScheduler_StoreSchema();
        // @phpstan-ignore-line
        $logger_schema = new \ActionScheduler_LoggerSchema();
        // @phpstan-ignore-line
        $store_schema->register_tables( true );
        // @phpstan-ignore-line
        $logger_schema->register_tables( true );
        // @phpstan-ignore-line
        remove_action( 'action_scheduler/created_table', array( $store, 'set_autoincrement' ), 10 );
    }
    
    /**
     * Is WooCommerce Installed
     *
     * @return bool
     */
    private function is_woocommerce_active()
    {
        // @codeCoverageIgnoreStart
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // @codeCoverageIgnoreEnd
        return is_plugin_active( 'woocommerce/woocommerce.php' ) && function_exists( 'is_woocommerce' );
    }
}