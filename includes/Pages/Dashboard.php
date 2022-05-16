<?php

/**
 * Dashboard actions.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Pages
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Pages;

use  RevivePress\Api\SettingsApi ;
use  RevivePress\Helpers\HelperFunctions ;
use  RevivePress\Api\Callbacks\AdminCallbacks ;
use  RevivePress\Api\Callbacks\ManagerCallbacks ;
defined( 'ABSPATH' ) || exit;
/**
 * Dashboard class.
 */
class Dashboard
{
    use  HelperFunctions ;
    /**
     * Settings.
     */
    public  $settings ;
    /**
     * Callbacks.
     */
    public  $callbacks ;
    /**
     * Callback Managers.
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
        $this->settings->addPages( $this->pages )->withSubPage( __( 'Dashboard', 'wp-auto-republish' ) )->register();
    }
    
    /**
     * Register plugin pages.
     */
    public function setPages()
    {
        $manage_options_cap = apply_filters( 'wpar/manage_options_capability', 'manage_options' );
        $this->pages = [ [
            'page_title' => 'RevivePress',
            'menu_title' => __( 'RevivePress', 'wp-auto-republish' ),
            'capability' => $manage_options_cap,
            'menu_slug'  => 'revivepress',
            'callback'   => [ $this->callbacks, 'adminDashboard' ],
            'icon_url'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDIwIDIwIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjsiPgogICAgPHVzZSB4bGluazpocmVmPSIjX0ltYWdlMSIgeD0iMC45OTUiIHk9IjkiIHdpZHRoPSIxMi45MTRweCIgaGVpZ2h0PSIxMC40NTdweCIgdHJhbnNmb3JtPSJtYXRyaXgoMC45OTM0MTEsMCwwLDAuOTUwNTkzLDAsMCkiLz4KICAgIDx1c2UgeGxpbms6aHJlZj0iI19JbWFnZTIiIHg9IjYuMTQiIHk9IjEuMDM1IiB3aWR0aD0iMTIuODg0cHgiIGhlaWdodD0iMTAuNTA2cHgiIHRyYW5zZm9ybT0ibWF0cml4KDAuOTkxMDk2LDAsMCwwLjk1NTEyNywwLDApIi8+CiAgICA8ZGVmcz4KICAgICAgICA8aW1hZ2UgaWQ9Il9JbWFnZTEiIHdpZHRoPSIxM3B4IiBoZWlnaHQ9IjExcHgiIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQTBBQUFBTENBWUFBQUNrc2dkaEFBQUFDWEJJV1hNQUFBN0VBQUFPeEFHVkt3NGJBQUFBaGtsRVFWUW9rWldQTVE0QkFSQkYzNnlUaUMwMVd5akVOUnhEbzlBN2tndHM5SzdnREJLSmFDejdGS3dvVnN5K2FpYjVMMzhHQUhXcXJraFNxSFBnQUR5eUV1clJGNHQwRTFDKzUzRldDdFd2dlFHdVBia1dXRWJFdmpzdlM2UE8rcHIrY1FPcVVPL0FhSUI0TG9CNmdBQ3dRWjJvbCtSZjY0K3FsdXBPUGYwSXQrcTJ5ejhCVzQrdHA1M2dmMVFBQUFBQVNVVk9SSzVDWUlJPSIvPgogICAgICAgIDxpbWFnZSBpZD0iX0ltYWdlMiIgd2lkdGg9IjEzcHgiIGhlaWdodD0iMTFweCIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9wbmc7YmFzZTY0LGlWQk9SdzBLR2dvQUFBQU5TVWhFVWdBQUFBMEFBQUFMQ0FZQUFBQ2tzZ2RoQUFBQUNYQklXWE1BQUE3RUFBQU94QUdWS3c0YkFBQUFpa2xFUVZRb2taV1FMUTdDUUJRR1oxdUN3WENHQm8rcUkrRWNKTFdjZ0F0d0lBeVNlNkJKdUFBSURJR1V3WFNiUWlEWkhmUE1tKy85UUllNlZWLys1cUx1MVJrRFlmT24rWnViV3FHdUU0WElJYWhYWUVvNmJRRXNnRWVHVk1hYmF2V1p1bDhZUEdNSjdQcWtUeWJBT0dPYlBuUVZKeFVaM3JtcnB4eHBEdHlCWnBRaGxVQWRRamkrQVZoTjBFSDJXd2pIQUFBQUFFbEZUa1N1UW1DQyIvPgogICAgPC9kZWZzPgo8L3N2Zz4K',
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
            'post_query',
            'post_type',
            'republish_info',
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
                    'class'     => 'wpar_el_' . str_replace( '__premium_only', '', $type ),
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
            'republish_interval_days'    => __( 'Schedule Auto Republish Process Every (in days)', 'wp-auto-republish' ),
            'minimun_republish_interval' => __( 'Republish Process Interval within a Day', 'wp-auto-republish' ),
            'random_republish_interval'  => __( 'Date Time Random Interval', 'wp-auto-republish' ),
            'republish_time_specific'    => __( 'Time Specific Republishing', 'wp-auto-republish' ),
            'republish_time_start'       => __( 'Start Time for Republishing', 'wp-auto-republish' ),
            'republish_time_end'         => __( 'End Time for Republishing', 'wp-auto-republish' ),
            'republish_days'             => __( 'Select Weekdays to Republish', 'wp-auto-republish' ),
            'republish_post_position'    => __( 'Republish Post to Position', 'wp-auto-republish' ),
        ],
            'republish_info' => [
            'republish_info'      => __( 'Show Original Publication Date', 'wp-auto-republish' ),
            'republish_info_text' => __( 'Original Publication Message', 'wp-auto-republish' ),
        ],
            'post_query'     => [
            'republish_post_age' => __( 'Post Republish Eligibility Age', 'wp-auto-republish' ),
            'republish_order'    => __( 'Select Published Posts Order', 'wp-auto-republish' ),
            'republish_orderby'  => __( 'Select Published Posts Order by', 'wp-auto-republish' ),
        ],
            'post_type'      => [
            'post_types_list'   => __( 'Select Post Type(s) to Republish', 'wp-auto-republish' ),
            'taxonomies_filter' => __( 'Post Types Taxonomies Filter', 'wp-auto-republish' ),
            'post_taxonomy'     => __( 'Select Post Type(s) Taxonomies', 'wp-auto-republish' ),
            'force_include'     => __( 'Force Include Post IDs', 'wp-auto-republish' ),
            'force_exclude'     => __( 'Force Exclude Post IDs', 'wp-auto-republish' ),
        ],
            'tools'          => [
            'remove_plugin_data' => __( 'Delete Plugin Data on Uninstall?', 'wp-auto-republish' ),
        ],
        ];
        return $managers;
    }

}