<?php

/**
 * Dashboard actions.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Pages
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Pages;

use  Wpar\Api\SettingsApi ;
use  Wpar\Helpers\HelperFunctions ;
use  Wpar\Api\Callbacks\AdminCallbacks ;
use  Wpar\Api\Callbacks\ManagerCallbacks ;
defined( 'ABSPATH' ) || exit;
/**
 * Dashboard class.
 */
class Dashboard
{
    use  HelperFunctions ;
    /**
     * Settings.
     *
     * @var array
     */
    public  $settings ;
    /**
     * Callbacks.
     *
     * @var array
     */
    public  $callbacks ;
    /**
     * Callback Managers.
     *
     * @var array
     */
    public  $callbacks_manager ;
    /**
     * Settings pages.
     *
     * @var array
     */
    public  $pages = array() ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->callbacks_manager = new ManagerCallbacks();
        $this->setPages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addPages( $this->pages )->withSubPage( __( 'WP Auto Republish', 'wp-auto-republish' ) )->register();
    }
    
    /**
     * Register plugin pages.
     */
    public function setPages()
    {
        $this->pages = [ [
            'page_title' => __( 'WP Auto Republish', 'wp-auto-republish' ),
            'menu_title' => __( 'Auto Republish', 'wp-auto-republish' ),
            'capability' => 'manage_options',
            'menu_slug'  => 'wp-auto-republish',
            'callback'   => [ $this->callbacks, 'adminDashboard' ],
            'icon_url'   => 'dashicons-update',
            'position'   => 100,
        ] ];
    }
    
    /**
     * Register plugin settings.
     */
    public function setSettings()
    {
        $args = [ [
            'option_group' => 'wpar_plugin_settings_fields',
            'option_name'  => 'wpar_plugin_settings',
        ] ];
        $this->settings->setSettings( $args );
    }
    
    /**
     * Register plugin sections.
     */
    public function setSections()
    {
        $sections = [
            'default',
            'single_republish',
            'post_query',
            'post_type',
            'republish_info',
            'email_notify',
            'tools'
        ];
        $args = [];
        foreach ( $sections as $section ) {
            $args[] = [
                'id'       => 'wpar_plugin_' . $section . '_section',
                'title'    => '',
                'callback' => null,
                'page'     => 'wpar_plugin_' . $section . '_option',
            ];
        }
        $this->settings->setSections( $args );
    }
    
    /**
     * Register settings fields.
     */
    public function setFields()
    {
        $args = [];
        foreach ( $this->build_settings_fields() as $key => $value ) {
            foreach ( $value as $type => $settings ) {
                $args[] = [
                    'id'       => $type,
                    'title'    => $settings,
                    'callback' => [ $this->callbacks_manager, $type ],
                    'page'     => 'wpar_plugin_' . $key . '_option',
                    'section'  => 'wpar_plugin_' . $key . '_section',
                    'args'     => [
                    'label_for' => 'wpar_' . str_replace( '__premium_only', '', $type ),
                    'class'     => 'wpar_css_' . str_replace( '__premium_only', '', $type ),
                ],
                ];
            }
        }
        $this->settings->setFields( $args );
    }
    
    /**
     * Build settings fields.
     */
    private function build_settings_fields()
    {
        $managers = [
            'default'        => [
            'enable_plugin'              => __( 'Enable Auto Republishing?', 'wp-auto-republish' ),
            'minimun_republish_interval' => __( 'Run Republish Process in Every:', 'wp-auto-republish' ),
            'random_republish_interval'  => __( 'New Date Time Randomness:', 'wp-auto-republish' ),
            'republish_post_position'    => __( 'Republish Post to Position:', 'wp-auto-republish' ),
            'republish_time_start'       => __( 'Start Time for Republishing:', 'wp-auto-republish' ),
            'republish_time_end'         => __( 'End Time for Republishing:', 'wp-auto-republish' ),
            'republish_days'             => __( 'Select Weekdays to Republish:', 'wp-auto-republish' ),
        ],
            'republish_info' => [
            'republish_info'      => __( 'Show Original Publication Date:', 'wp-auto-republish' ),
            'republish_info_text' => __( 'Original Publication Message:', 'wp-auto-republish' ),
        ],
            'post_query'     => [
            'republish_post_age' => __( 'Post Republish Eligibility Age:', 'wp-auto-republish' ),
            'republish_order'    => __( 'Select Old Posts Order:', 'wp-auto-republish' ),
        ],
            'post_type'      => [
            'post_types_list'       => __( 'Select Post Types to Republish:', 'wp-auto-republish' ),
            'exclude_by_type'       => __( 'Auto Republish Old Posts by:', 'wp-auto-republish' ),
            'post_taxonomy'         => __( 'Select Post Categories/Tags:', 'wp-auto-republish' ),
            'override_category_tag' => __( 'Override Category or Post Tags Filtering for these Specific Posts:', 'wp-auto-republish' ),
        ],
            'tools'          => [
            'remove_plugin_data' => __( 'Remove Plugin Data on Uninstall?', 'wp-auto-republish' ),
        ],
        ];
        return $managers;
    }

}