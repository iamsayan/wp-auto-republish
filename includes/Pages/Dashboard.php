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
    public function register() {
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
    public function setPages() {
        $manage_options_cap = apply_filters( 'wpar/manage_options_capability', 'manage_options' );
        $this->pages = [
			[
				'page_title' => 'RevivePress',
				'menu_title' => __( 'RevivePress', 'wp-auto-republish' ),
				'capability' => $manage_options_cap,
				'menu_slug'  => 'revivepress',
				'callback'   => [ $this->callbacks, 'adminDashboard' ],
				'icon_url'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDIwIDIwIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjsiPgogICAgPHBhdGggZD0iTTYuMDg1LDQuMjQ4TDkuMjU2LDcuNDExTDE1LjYzOCw3LjQ1OUMxNS44NDcsNy40ODkgMTYuMDY4LDcuNTM0IDE2LjMwNyw3LjYwMkMxNi41MjMsNy42NzMgMTYuNzA4LDcuNzQ3IDE2Ljg3MSw3LjgyNUMxNi45MDYsOC4xNzMgMTYuOTE3LDguNTI1IDE2LjkwMSw4Ljg4QzE2Ljg4Nyw5LjIwOCAxNi44NDgsOS41NDYgMTYuNzgxLDkuODk3QzE2LjY3NCwxMC40NSAxNi41MTMsMTAuOTggMTYuMzE2LDExLjQ5NUMxNi43NzgsMTEuMzU3IDE3LjE3LDExLjEzNiAxNy41MTEsMTAuODU0QzE4LjE2NSwxMC4zMTIgMTguNjY2LDkuNjY4IDE4Ljg2Nyw4LjgyNEMxOC45MjEsOC41NzQgMTguOTUzLDguMzMzIDE4Ljk3LDguMDk5TDE4Ljk3LDQuNTc3QzE4LjkyMiwzLjk5NyAxOC43NjMsMy40NTMgMTguNDk2LDIuOTQyQzE4LjI4NCwyLjU4NiAxOC4wMDMsMi4yNCAxNy42NTUsMS45MDdDMTYuOTQzLDEuMjk3IDE2LjA0NSwxLjAzMiAxNS4zNDksMC45ODlMOS4yMzcsMC45ODlMNi4wODUsNC4yNDhaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KC0xLDAsMCwtMSwxOS45NTc4LDIwLjAwMDcpIj4KICAgICAgICA8cGF0aCBkPSJNNi4wODUsNC4yNDhMOS4yNTYsNy40MTFMMTUuNjM4LDcuNDU5QzE1Ljg0Nyw3LjQ4OSAxNi4wNjgsNy41MzQgMTYuMzA3LDcuNjAyQzE2LjUyMyw3LjY3MyAxNi43MDgsNy43NDcgMTYuODcxLDcuODI1QzE2LjkwNiw4LjE3MyAxNi45MTcsOC41MjUgMTYuOTAxLDguODhDMTYuODg3LDkuMjA4IDE2Ljg0OCw5LjU0NiAxNi43ODEsOS44OTdDMTYuNjc0LDEwLjQ1IDE2LjUxMywxMC45OCAxNi4zMTYsMTEuNDk1QzE2Ljc3OCwxMS4zNTcgMTcuMTcsMTEuMTM2IDE3LjUxMSwxMC44NTRDMTguMTY1LDEwLjMxMiAxOC42NjYsOS42NjggMTguODY3LDguODI0QzE4LjkyMSw4LjU3NCAxOC45NTMsOC4zMzMgMTguOTcsOC4wOTlMMTguOTcsNC41NzdDMTguOTIyLDMuOTk3IDE4Ljc2MywzLjQ1MyAxOC40OTYsMi45NDJDMTguMjg0LDIuNTg2IDE4LjAwMywyLjI0IDE3LjY1NSwxLjkwN0MxNi45NDMsMS4yOTcgMTYuMDQ1LDEuMDMyIDE1LjM0OSwwLjk4OUw5LjIzNywwLjk4OUw2LjA4NSw0LjI0OFoiIHN0eWxlPSJmaWxsOndoaXRlOyIvPgogICAgPC9nPgo8L3N2Zz4K',
				'position'   => 100,
			],
		];
    }
    
    /**
     * Register plugin settings.
     */
    public function setSettings() {
        $args = [
			[
				'option_group' => 'wpar_plugin_settings_fields',
				'option_name'  => 'wpar_plugin_settings',
			],
		];
        $this->settings->setSettings( $args );
    }
    
    /**
     * Register plugin sections.
     */
    public function setSections() {
        $sections = [
            'general',
            'post_query',
            'post_type',
            'republish_info',
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
    public function setFields() {
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
    private function build_settings_fields() {
        $managers = [
            'general'        => [
				'enable_plugin'              => __( 'Enable Auto Republishing?', 'wp-auto-republish' ),
				'republish_interval_days'    => __( 'Schedule Auto Republish Process Every (in days)', 'wp-auto-republish' ),
				'minimun_republish_interval' => __( 'Republish Process Interval within a Day', 'wp-auto-republish' ),
				'random_republish_interval'  => __( 'Date Time Random Interval', 'wp-auto-republish' ),
				'republish_time_specific'    => __( 'Time Specific Republishing', 'wp-auto-republish' ),
				'republish_time_start'       => __( 'Start Time for Republishing', 'wp-auto-republish' ),
				'republish_time_end'         => __( 'End Time for Republishing', 'wp-auto-republish' ),
				'republish_days'             => __( 'Select Weekdays to Republish', 'wp-auto-republish' ),
				'republish_post_position'    => __( 'Republish Post to Position', 'wp-auto-republish' ),
				'remove_plugin_data'         => __( 'Delete Plugin Data on Uninstall?', 'wp-auto-republish' ),
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
        ];
        return $managers;
    }

}