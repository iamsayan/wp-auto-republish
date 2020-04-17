<?php 
/**
 * Dashboard actions.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Pages
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard class.
 */
class Dashboard extends BaseController
{
	/**
	 * Settings.
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Callbacks.
	 *
	 * @var array
	 */
	public $callbacks;

	/**
	 * Callback Managers.
	 *
	 * @var array
	 */
	public $callbacks_manager;

	/**
	 * Settings pages.
	 *
	 * @var array
	 */
	public $pages = [];

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
		$this->pages = [
			[
				'page_title' => __( 'WP Auto Republish', 'wp-auto-republish' ), 
				'menu_title' => __( 'Auto Republish', 'wp-auto-republish' ), 
				'capability' => 'manage_options', 
				'menu_slug' => 'wp-auto-republish', 
				'callback' => [ $this->callbacks, 'adminDashboard' ], 
				'icon_url' => 'dashicons-update', 
				'position' => 100
			]
		];
	}

	/**
	 * Register plugin settings.
	 */
	public function setSettings()
	{
		$args = [
			[
				'option_group' => 'wpar_plugin_settings_fields',
				'option_name' => 'wpar_plugin_settings',
			]
		];

		$this->settings->setSettings( $args );
	}

	/**
	 * Register plugin sections.
	 */
	public function setSections()
	{
		$args = [
			[
				'id' => 'wpar_plugin_section',
				'title' => '',
				'callback' => null,
				'page' => 'wpar_plugin_option'
			]
		];

		$this->settings->setSections( $args );
	}

	/**
	 * Register settings fields.
	 */
	public function setFields()
	{
		$args = [];
		foreach ( $this->managers as $key => $value ) {
			$args[] = [
				'id' => $key,
				'title' => $value,
				'callback' => [ $this->callbacks_manager, $key ],
				'page' => 'wpar_plugin_option',
				'section' => 'wpar_plugin_section',
				'args' => [
					'label_for' => 'wpar_'.$key,
					'class' => 'wpar_css_'.$key
				]
			];
		}

		$this->settings->setFields( $args );
	}
}