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
     * Settings fields.
     *
     * @var array
     */
    public  $managers = array() ;
    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/wp-auto-republish.php';
        $this->version = '1.1.0';
        $this->debug = true;
        $this->name = 'WP Auto Republish';
        $this->managers = [
            'enable_plugin'              => __( 'Enable Auto Republishing?', 'wp-auto-republish' ),
            'minimun_republish_interval' => __( 'Minimum Republish Interval:', 'wp-auto-republish' ),
            'random_republish_interval'  => __( 'Random Republish Interval:', 'wp-auto-republish' ),
            'republish_post_age'         => __( 'Post Republish Eligibility Age:', 'wp-auto-republish' ),
            'republish_method'           => __( 'Select Old Posts Query Method:', 'wp-auto-republish' ),
            'republish_post_position'    => __( 'Republish Post to Position:', 'wp-auto-republish' ),
            'republish_info'             => __( 'Show Original Publication Date:', 'wp-auto-republish' ),
            'republish_info_text'        => __( 'Original Publication Message:', 'wp-auto-republish' ),
            'post_types_list'            => __( 'Select Post Types to Republish:', 'wp-auto-republish' ),
            'exclude_by_type'            => __( 'Auto Republish Old Posts by:', 'wp-auto-republish' ),
            'post_taxonomy'              => __( 'Select Post Categories/Tags:', 'wp-auto-republish' ),
            'override_category_tag'      => __( 'Override Category or Post Tags Filtering for these Specific Posts:', 'wp-auto-republish' ),
            'republish_days'             => __( 'Select Weekdays to Republish:', 'wp-auto-republish' ),
            'republish_time_start'       => __( 'Start Time for Republishing:', 'wp-auto-republish' ),
            'republish_time_end'         => __( 'End Time for Republishing:', 'wp-auto-republish' ),
            'remove_plugin_data'         => __( 'Remove Plugin Data on Uninstall?', 'wp-auto-republish' ),
        ];
    }

}