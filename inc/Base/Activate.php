<?php

/**
 * Activation.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Inc\Base;

/**
 * Activation class.
 */
class Activate
{
    /**
     * Run plugin activation process.
     */
    public static function activate()
    {
        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }
        flush_rewrite_rules();
        $options = [
            'wpar_enable_plugin'              => '1',
            'wpar_minimun_republish_interval' => '43200',
            'wpar_random_republish_interval'  => '14400',
            'wpar_republish_post_age'         => '120',
            'wpar_republish_post_position'    => '1',
            'wpar_republish_method'           => 'old_first',
            'wpar_republish_position'         => 'disable',
            'wpar_republish_position_text'    => __( 'Originally posted on ', 'wp-auto-republish' ),
            'wpar_exclude_by_type'            => 'none',
            'wpar_post_taxonomy'              => [],
            'wpar_exclude_tag'                => [],
            'wpar_override_category_tag'      => '',
            'wpar_days'                       => [
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat'
        ],
            'wpar_start_time'                 => '05:00',
            'wpar_end_time'                   => '23:00',
            'wpar_post_types'                 => [ 'post' ],
        ];
        if ( get_option( 'wpar_plugin_settings' ) === false ) {
            update_option( 'wpar_plugin_settings', $options );
        }
    }

}