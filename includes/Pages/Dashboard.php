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

use RevivePress\Api\SettingsApi;
use RevivePress\Helpers\HelperFunctions;
use RevivePress\Api\Callbacks\AdminCallbacks;
use RevivePress\Api\Callbacks\ManagerCallbacks;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard class.
 */
class Dashboard
{
	use HelperFunctions;

	/**
	 * Settings.
	 */
	public $settings;

	/**
	 * Callbacks.
	 */
	public $callbacks;

	/**
	 * Callback Managers.
	 */
	public $callbacks_manager;

	/**
	 * Settings pages.
	 *
	 * @var array
	 */
	public $pages = array();

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
		$this->pages = array(
			array(
				'page_title' => 'RevivePress', 
				'menu_title' => __( 'RevivePress', 'wp-auto-republish' ),
				'capability' => $manage_options_cap,
				'menu_slug'  => 'revivepress', 
				'callback'   => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDIwIDIwIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjsiPgogICAgPHBhdGggZD0iTTYuMDg1LDQuMjQ4TDkuMjU2LDcuNDExTDE1LjYzOCw3LjQ1OUMxNS44NDcsNy40ODkgMTYuMDY4LDcuNTM0IDE2LjMwNyw3LjYwMkMxNi41MjMsNy42NzMgMTYuNzA4LDcuNzQ3IDE2Ljg3MSw3LjgyNUMxNi45MDYsOC4xNzMgMTYuOTE3LDguNTI1IDE2LjkwMSw4Ljg4QzE2Ljg4Nyw5LjIwOCAxNi44NDgsOS41NDYgMTYuNzgxLDkuODk3QzE2LjY3NCwxMC40NSAxNi41MTMsMTAuOTggMTYuMzE2LDExLjQ5NUMxNi43NzgsMTEuMzU3IDE3LjE3LDExLjEzNiAxNy41MTEsMTAuODU0QzE4LjE2NSwxMC4zMTIgMTguNjY2LDkuNjY4IDE4Ljg2Nyw4LjgyNEMxOC45MjEsOC41NzQgMTguOTUzLDguMzMzIDE4Ljk3LDguMDk5TDE4Ljk3LDQuNTc3QzE4LjkyMiwzLjk5NyAxOC43NjMsMy40NTMgMTguNDk2LDIuOTQyQzE4LjI4NCwyLjU4NiAxOC4wMDMsMi4yNCAxNy42NTUsMS45MDdDMTYuOTQzLDEuMjk3IDE2LjA0NSwxLjAzMiAxNS4zNDksMC45ODlMOS4yMzcsMC45ODlMNi4wODUsNC4yNDhaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KC0xLDAsMCwtMSwxOS45NTc4LDIwLjAwMDcpIj4KICAgICAgICA8cGF0aCBkPSJNNi4wODUsNC4yNDhMOS4yNTYsNy40MTFMMTUuNjM4LDcuNDU5QzE1Ljg0Nyw3LjQ4OSAxNi4wNjgsNy41MzQgMTYuMzA3LDcuNjAyQzE2LjUyMyw3LjY3MyAxNi43MDgsNy43NDcgMTYuODcxLDcuODI1QzE2LjkwNiw4LjE3MyAxNi45MTcsOC41MjUgMTYuOTAxLDguODhDMTYuODg3LDkuMjA4IDE2Ljg0OCw5LjU0NiAxNi43ODEsOS44OTdDMTYuNjc0LDEwLjQ1IDE2LjUxMywxMC45OCAxNi4zMTYsMTEuNDk1QzE2Ljc3OCwxMS4zNTcgMTcuMTcsMTEuMTM2IDE3LjUxMSwxMC44NTRDMTguMTY1LDEwLjMxMiAxOC42NjYsOS42NjggMTguODY3LDguODI0QzE4LjkyMSw4LjU3NCAxOC45NTMsOC4zMzMgMTguOTcsOC4wOTlMMTguOTcsNC41NzdDMTguOTIyLDMuOTk3IDE4Ljc2MywzLjQ1MyAxOC40OTYsMi45NDJDMTguMjg0LDIuNTg2IDE4LjAwMywyLjI0IDE3LjY1NSwxLjkwN0MxNi45NDMsMS4yOTcgMTYuMDQ1LDEuMDMyIDE1LjM0OSwwLjk4OUw5LjIzNywwLjk4OUw2LjA4NSw0LjI0OFoiIHN0eWxlPSJmaWxsOndoaXRlOyIvPgogICAgPC9nPgo8L3N2Zz4K', 
				'position'   => 100,
			),
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function setSettings() {
		$args = array(
			array(
				'option_group' => 'wpar_plugin_settings_fields',
				'option_name'  => 'wpar_plugin_settings',
			),
		);

		$this->settings->setSettings( $args );
	}

	/**
	 * Register plugin sections.
	 */
	public function setSections() {
		$sections = array( 'general', 'post_query', 'post_type', 'republish_info' );
		//if ( revivepress_fs()->can_use_premium_code__premium_only() ) {
			$premium = array( 'metabox', 'individual_post', 'actions_republish', 'instant_indexing', 'email_notify', 'social_general', 'facebook', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'advanced' );
			$sections = array_merge( $sections, $premium );
		//}
		$args = array();
		foreach ( $sections as $section ) {
		    $args[] = array(
		    	'id'       => 'wpar_plugin_' . $section . '_section',
		    	'title'    => '',
		    	'callback' => null,
		    	'page'     => 'wpar_plugin_' . $section . '_option',
			);
		}

		$this->settings->setSections( $args );
	}

	/**
	 * Register settings fields.
	 */
	public function setFields() {
		$args = array();
		foreach ( $this->build_settings_fields() as $key => $value ) {
			foreach ( $value as $callback => $settings ) {
			    $args[] = array(
			    	'id'       => $callback,
			    	'title'    => $settings,
			    	'callback' => array( $this->callbacks_manager, $callback ),
			    	'page'     => 'wpar_plugin_' . $key . '_option',
			    	'section'  => 'wpar_plugin_' . $key . '_section',
			    	'args'     => array(
			    		'label_for' => 'wpar_' . str_replace( '__premium', '', $callback ),
			    		'class'     => $this->get_section_class( $callback ),
			    	),
				);
			}
		}

		$this->settings->setFields( $args );
	}

	/**
	 * Build settings fields.
	 */
	private function build_settings_fields() {
		$managers = array(
            'general'        => array(
				'enable_plugin'              => __( 'Enable Global Auto Republishing?', 'wp-auto-republish' ),
				'republish_interval_days'    => __( 'Schedule Auto Republish Process Every (in days)', 'wp-auto-republish' ),
				'minimun_republish_interval' => __( 'Republish Process Interval within a Day', 'wp-auto-republish' ),
			    'random_republish_interval'  => __( 'Date Time Random Interval', 'wp-auto-republish' ),
				'republish_time_specific'    => __( 'Time Specific Republishing', 'wp-auto-republish' ),
			    'republish_time_start'       => __( 'Start Time for Republishing', 'wp-auto-republish' ),
			    'republish_time_end'         => __( 'End Time for Republishing', 'wp-auto-republish' ),
			    'republish_days'             => __( 'Select Weekdays to Republish', 'wp-auto-republish' ),
				'republish_post_position'    => __( 'Republish Post to Position', 'wp-auto-republish' ),
				'remove_plugin_data'         => __( 'Delete Plugin Data on Uninstall?', 'wp-auto-republish' ),
			),
			'republish_info' => array(
			    'republish_info'      => __( 'Show Original Publication Date', 'wp-auto-republish' ),
				'republish_info_text' => __( 'Original Publication Message', 'wp-auto-republish' ),
			),
			'post_query'     => array(
			    'republish_post_age' => __( 'Post Republish Eligibility Age', 'wp-auto-republish' ),
			    'republish_order'    => __( 'Select Published Posts Order', 'wp-auto-republish' ),
				'republish_orderby'  => __( 'Select Published Posts Order by', 'wp-auto-republish' ),
			),
			'post_type'      => array(
			    'post_types_list'   => __( 'Select Post Type(s) to Republish', 'wp-auto-republish' ),
			    'taxonomies_filter' => __( 'Post Types Taxonomies Filter', 'wp-auto-republish' ),
			    'post_taxonomy'     => __( 'Select Post Type(s) Taxonomies', 'wp-auto-republish' ),
			    'force_include'     => __( 'Force Include Post IDs', 'wp-auto-republish' ),
			    'force_exclude'     => __( 'Force Exclude Post IDs', 'wp-auto-republish' ),
			),
		);

		//if ( revivepress_fs()->can_use_premium_code__premium_only() ) {
			if ( isset( $managers['general']['remove_plugin_data'] ) ) {
				unset( $managers['general']['remove_plugin_data'] );
			}

		    $managers['general'] = $this->insert_settings( $managers['general'], 3, array(
				'republish_custom_interval__premium' => __( 'Set Custom Interval', 'wp-auto-republish' ),
				'number_of_posts_day__premium'       => __( 'Maximum Number of Republish Allowed in a Day', 'wp-auto-republish' ),
			) );
			
			$managers['general'] = $this->insert_settings( $managers['general'], 10, array(
				'number_of_posts__premium'  => __( 'Number of Posts to be Republished at a time', 'wp-auto-republish' ),
				'republish_action__premium' => __( 'Post Auto Republish Action', 'wp-auto-republish' ),
			) );

			$managers['republish_info'] = $this->insert_settings( $managers['republish_info'], 2, array(
		        'date_time_format_display__premium' => __( 'Date Time Format to Display', 'wp-auto-republish' ),
		        'post_types_list_display__premium'  => __( 'Select Post Type(s) to Display', 'wp-auto-republish' ),
			) );

			$managers['post_query'] = $this->insert_settings( $managers['post_query'], 1, array(
				'republish_custom_age__premium'     => __( 'Enter Post Custom Age', 'wp-auto-republish' ),
				'republish_post_age_start__premium' => __( 'Exclude Posts Published Before', 'wp-auto-republish' ),
				'filter_thumbnail__premium'         => __( 'Thumbnail based Republishing', 'wp-auto-republish' ),
				'ignore_sticky_posts__premium'      => __( 'Ignore all Sticky Posts', 'wp-auto-republish' ),
			) );

			$managers['post_type'] = $this->insert_settings( $managers['post_type'], 1, array(
				'post_statuses__premium' => __( 'Select Post Status(es) to Republish', 'wp-auto-republish' ),
			) );

			$managers['post_type'] = $this->insert_settings( $managers['post_type'], 4, array(
				'authors_filter__premium'            => __( 'Post Types Author(s) Filter', 'wp-auto-republish' ),
			    'republish_allowed_authors__premium' => __( 'Select Authors to Include/Exclude', 'wp-auto-republish' ),
			) );

			$managers['metabox'] = array(
				'enable_single_metabox__premium' => __( 'Enable Single Post Metabox', 'wp-auto-republish' ),
				'single_roles__premium'          => __( 'Show Metabox for User Roles', 'wp-auto-republish' ),
			);

			$managers['individual_post'] = array(
				'enable_single_republishing__premium' => __( 'Enable Individual Republishing', 'wp-auto-republish' ),
				'single_republish_action__premium'    => __( 'Single Post Republish Action', 'wp-auto-republish' ),
				'post_types_list_single__premium'     => __( 'Enable Republish Options on', 'wp-auto-republish' ),
			);

			$managers['actions_republish'] = array(
				'enable_instant_republishing__premium' => __( 'Enable One Click Republishing', 'wp-auto-republish' ),
				'allowed_actions__premium'             => __( 'Enable Republish Actions', 'wp-auto-republish' ),
				'show_links_in__premium'               => __( 'Show Republish Links in', 'wp-auto-republish' ),
				'post_types_list_instant__premium'     => __( 'Enable Republish Links on', 'wp-auto-republish' ),
				'instant_roles__premium'               => __( 'Enable Republish Links for', 'wp-auto-republish' ),
			);
			
			$managers['email_notify'] = array(
				'enable_email_notify__premium'      => __( 'Enable Auto Email Notification?', 'wp-auto-republish' ),
				'enable_post_author_email__premium' => __( 'Send Notification to Post Author?', 'wp-auto-republish' ),
				'email_recipients__premium'         => __( 'Set List of Email Recipient(s)', 'wp-auto-republish' ),
				'email_post_types__premium'         => __( 'Enable Email for Post Types', 'wp-auto-republish' ),
				'email_subject__premium'            => __( 'Notification Email Subject', 'wp-auto-republish' ),
				'email_message__premium'            => __( 'Notification Email Message Body', 'wp-auto-republish' ),
			);

			$managers['social_general'] = array(
				'unique_posting__premium' => __( 'Generate Unique Share Link', 'wp-auto-republish' ),
				'link_shortner__premium'  => __( 'Post URL Shortner Provider', 'wp-auto-republish' ),
				'bitly_token__premium'    => __( 'Bit.ly Access Token', 'wp-auto-republish' ),
				'shortest_token__premium' => __( 'Shorte.st Access Token', 'wp-auto-republish' ),
				'url_patameters__premium' => __( 'Global Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['facebook'] = array(
				'fb_social_enable__premium'           => __( 'Enable Share on Facebook', 'wp-auto-republish' ),
				'fb_social_og_tag__premium'           => __( 'Add Facebook Meta to Header', 'wp-auto-republish' ),
				'fb_social_post_as__premium'          => __( 'Facebook Post Default Content', 'wp-auto-republish' ),
				'fb_social_content_source__premium'   => __( 'Content Source for Post', 'wp-auto-republish' ),
				'fb_social_template__premium'         => __( 'Facebook Share / Post Template (Character Limit: 9500)', 'wp-auto-republish' ),
				'fb_post_types_list_display__premium' => __( 'Enable Share for Post Type(s)', 'wp-auto-republish' ),
				'fb_social_taxonomy__premium'         => __( 'Post Taxonomies as Hashtags', 'wp-auto-republish' ),
				'fb_url_patameters__premium'          => __( 'Facebook Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['twitter'] = array(
				'tw_social_enable__premium'           => __( 'Enable Share on Twitter', 'wp-auto-republish' ),
				'tw_social_thumbnail__premium'        => __( 'Post Default Thumbnail Posting', 'wp-auto-republish' ),
				'tw_social_content_source__premium'   => __( 'Content Source for Tweet', 'wp-auto-republish' ),
				'tw_social_template__premium'         => __( 'Twitter Share / Tweet Template (Character Limit: 280)', 'wp-auto-republish' ),
				'tw_post_types_list_display__premium' => __( 'Enable Share for Post Type(s)', 'wp-auto-republish' ),
				'tw_social_taxonomy__premium'         => __( 'Tweet Taxonomies as Hashtags', 'wp-auto-republish' ),
				'tw_url_patameters__premium'          => __( 'Twitter Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['linkedin'] = array(
				'ld_social_enable__premium'           => __( 'Enable Share on Linkedin', 'wp-auto-republish' ),
				'ld_social_post_as__premium'          => __( 'Linkedin Post Default Content', 'wp-auto-republish' ),
				'ld_social_content_source__premium'   => __( 'Content Source for Post', 'wp-auto-republish' ),
				'ld_social_template__premium'         => __( 'Linkedin Share / Post Template (Character Limit: 1300)', 'wp-auto-republish' ),
				'ld_post_types_list_display__premium' => __( 'Enable Share for Post Type(s)', 'wp-auto-republish' ),
				'ld_social_taxonomy__premium'         => __( 'Post Taxonomies as Hashtags', 'wp-auto-republish' ),
				'ld_url_patameters__premium'          => __( 'Linkedin Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['pinterest'] = array(
				'pi_social_enable__premium'           => __( 'Enable Share on Pinterest', 'wp-auto-republish' ),
				'pi_social_content_source__premium'   => __( 'Content Source for Post', 'wp-auto-republish' ),
				'pi_social_template__premium'         => __( 'Pinterest Share / Pin Template (Character Limit: 500)', 'wp-auto-republish' ),
				'pi_post_types_list_display__premium' => __( 'Enable Share for Post Type(s)', 'wp-auto-republish' ),
				'pi_social_taxonomy__premium'         => __( 'Post Taxonomies as Hashtags', 'wp-auto-republish' ),
				'pi_url_patameters__premium'          => __( 'Pinterest Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['tumblr'] = array(
				'tb_social_enable__premium'           => __( 'Enable Share on Tumblr', 'wp-auto-republish' ),
				'tb_social_type__premium'             => __( 'Post Sharing Type', 'wp-auto-republish' ),
				'tb_social_content_source__premium'   => __( 'Content Source for Post', 'wp-auto-republish' ),
				'tb_social_template__premium'         => __( 'Tumblr Share / Post Template (Character Limit: 280)', 'wp-auto-republish' ),
				'tb_post_types_list_display__premium' => __( 'Enable Share for Post Type(s)', 'wp-auto-republish' ),
				'tb_social_taxonomy__premium'         => __( 'Post Taxonomies as Hashtags', 'wp-auto-republish' ),
				'tb_url_patameters__premium'          => __( 'Tumblr Share URL Parameters', 'wp-auto-republish' ),
			);

			$managers['advanced'] = array(
				'sort_order__premium'                 => __( 'Frontend Post Sorting Order', 'wp-auto-republish' ),
				'enable_silent_republishing__premium' => __( 'Enable Silent Publishing Event', 'wp-auto-republish' ),
				'disable_guid_reneration__premium'    => __( 'Disable Post GUID Regeneration', 'wp-auto-republish' ),
				'log_history_duration__premium'       => __( 'Keep Republish Log History For', 'wp-auto-republish' ),
				'remove_plugin_data'                  => __( 'Delete Plugin Data on Uninstall?', 'wp-auto-republish' ),
			);  

			if ( isset( $GLOBALS['sitepress'] ) || defined( 'POLYLANG_VERSION' ) ) {
				$managers['advanced'] = $this->insert_settings( $managers['advanced'], 1, array(
					'enable_republish_translated__premium' => __( 'Auto Republish Translated Posts', 'wp-auto-republish' ),
				) );
			}
		//}

		return $managers;
	}

	private function get_section_class( $item ) {
		$class = array( 'wpar_el_' . str_replace( '__premium', '', $item ) );
		
		if ( strpos( $item, 'premium' ) !== false && ! revivepress_fs()->can_use_premium_code__premium_only() ) {
			$class[] = 'premium wpar-upgrade1';
		}

		return join( ' ', $class );
	}
}