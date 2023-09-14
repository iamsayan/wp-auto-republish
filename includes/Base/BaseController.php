<?php

/**
 * Base controller class.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Base;

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
     * Plugin tag.
     *
     * @var string
     */
    public  $tag ;
    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->plugin_path = REVIVEPRESS_PATH;
        $this->plugin_url = REVIVEPRESS_URL;
        $this->plugin = REVIVEPRESS_BASENAME;
        $this->version = REVIVEPRESS_VERSION;
        $this->name = 'RevivePress';
        $this->tag = '';
    }
}