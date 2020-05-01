<?php

/**
 * Base controller class.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Core
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Inc\Base;

/**
 * Base Controller class.
 */
class BaseController
{
    /**
     * Plugin path.
     *
     * @var string
     */
    public  $plugin_path ;
    /**
     * Plugin URL.
     *
     * @var string
     */
    public  $plugin_url ;
    /**
     * Plugin basename.
     *
     * @var string
     */
    public  $plugin ;
    /**
     * Plugin version.
     *
     * @var string
     */
    public  $version ;
    /**
     * Plugin name.
     *
     * @var string
     */
    public  $name ;
    /**
     * Enable debug.
     *
     * @var bool
     */
    public  $debug ;
    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/wp-auto-republish.php';
        $this->version = '1.1.4';
        $this->debug = false;
        $this->name = 'WP Auto Republish';
    }

}