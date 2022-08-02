<?php

/**
 * Settings callbacks.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Api\Callbacks
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Api\Callbacks;

use  RevivePress\Helpers\Fields ;
use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
class ManagerCallbacks
{
    use  Fields, HelperFunctions, Hooker ;
    public function enable_plugin( $args ) {
        $this->do_field( [
            'type'        => 'checkbox',
            'id'          => $args['label_for'],
            'name'        => 'wpar_enable_plugin',
            'checked'     => 1 == $this->get_data( 'wpar_enable_plugin' ),
            'description' => __( 'Enable this if you want to auto republish old posts of your blog. It is the switch to enable or disable the Global Republish related functionalities of this plugin.', 'wp-auto-republish' ),
        ] );
    }
    
    public function republish_interval_days( $args ) {
        $this->do_field( [
            'type'        => 'number',
            'id'          => $args['label_for'],
            'name'        => 'republish_interval_days',
            'value'       => $this->get_data( 'republish_interval_days', '1' ),
            'description' => __( 'Set custom interval in Days. Default 1 day means plugin will republish on everyday if all weekdays are selected.', 'wp-auto-republish' ),
            'attributes'  => [
				'min' => 1,
			],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function minimun_republish_interval( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_minimun_republish_interval',
            'value'       => $this->get_data( 'wpar_minimun_republish_interval', 300 ),
            'description' => __( 'Select post republish interval between two post republish events. It will be added to the Last Run time (see top right) and will re-run at a point of time which resolves Last Run plus this settings. If Last Run is Today at 10:05 AM and this settings is set to 30 Minutes, then next process will run at 10:35 AM. Although, running of this process doesn\'t mean that it will republish a post every time. It will also check if the below conditions are met, if not, republish will not work at that particular point of time.', 'wp-auto-republish' ),
            'options'     => $this->do_filter( 'minimum_republish_interval', [
				'300'     => __( '5 Minutes', 'wp-auto-republish' ),
				'600'     => __( '10 Minutes', 'wp-auto-republish' ),
				'900'     => __( '15 Minutes', 'wp-auto-republish' ),
				'1200'    => __( '20 Minutes', 'wp-auto-republish' ),
				'1800'    => __( '30 Minutes', 'wp-auto-republish' ),
				'2700'    => __( '45 Minutes', 'wp-auto-republish' ),
				'3600'    => __( '1 hour', 'wp-auto-republish' ),
				'7200'    => __( '2 hours', 'wp-auto-republish' ),
				'14400'   => __( '4 hours', 'wp-auto-republish' ),
				'21600'   => __( '6 hours', 'wp-auto-republish' ),
				'28800'   => __( '8 hours', 'wp-auto-republish' ),
				'43200'   => __( '12 hours', 'wp-auto-republish' ),
				'premium' => __( 'Custom Interval (Premium)', 'wp-auto-republish' ),
			] ),
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function random_republish_interval( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_random_republish_interval',
            'value'       => $this->get_data( 'wpar_random_republish_interval', 3600 ),
            'description' => __( 'Select randomness interval from here which will be added to post republish time. If republish process runs at 11.25 AM and this option is set to Upto 1 Hour, post will be republished at anytime between 11.25 AM and 12.25 PM. It can make the republishing seem more natural to Readers and SERPs.', 'wp-auto-republish' ),
            'options'     => $this->do_filter( 'random_republish_interval', [
				'premium_1'  => __( 'No Randomness (Premium)', 'wp-auto-republish' ),
				'premium_2'  => __( 'Upto 5 Minutes (Premium)', 'wp-auto-republish' ),
				'premium_3'  => __( 'Upto 10 Minutes (Premium)', 'wp-auto-republish' ),
				'premium_4'  => __( 'Upto 15 Minutes (Premium)', 'wp-auto-republish' ),
				'premium_5'  => __( 'Upto 20 Minutes (Premium)', 'wp-auto-republish' ),
				'premium_6'  => __( 'Upto 30 Minutes (Premium)', 'wp-auto-republish' ),
				'premium_7'  => __( 'Upto 45 Minutes (Premium)', 'wp-auto-republish' ),
				'3600'       => __( 'Upto 1 hour', 'wp-auto-republish' ),
				'7200'       => __( 'Upto 2 hours', 'wp-auto-republish' ),
				'14400'      => __( 'Upto 4 hours', 'wp-auto-republish' ),
				'21600'      => __( 'Upto 6 hours', 'wp-auto-republish' ),
				'premium_8'  => __( 'Upto 8 hours (Premium)', 'wp-auto-republish' ),
				'premium_9'  => __( 'Upto 12 hours (Premium)', 'wp-auto-republish' ),
				'premium_10' => __( 'Upto 24 hours (Premium)', 'wp-auto-republish' ),
			] ),
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_post_position( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_post_position',
            'value'       => $this->get_data( 'wpar_republish_post_position', 'one' ),
            'description' => __( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in 1st place). Let\'s say, your last post/republished post time is 02:45 PM, First Position will push the current republished post to before the last post and Second Position will attach current republished post just after last post with a interval of 5 minutes (can be modified by filter).', 'wp-auto-republish' ),
            'options'     => [
				'one' => __( 'First Position', 'wp-auto-republish' ),
				'two' => __( 'Second Position', 'wp-auto-republish' ),
			],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_time_specific( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'republish_time_specific',
            'value'       => $this->get_data( 'republish_time_specific', 'no' ),
            'description' => sprintf(
            '%s <div class="wpar-time-limit-msg red">%s <a href="">%s</a></div>',
            __( 'Enable or Disable Time Specifc Republish from here. If you Enable this, plugin will only republish between the Start Time and End Time. If Start Time is grater than End Time, plugin will assume the end time in on the next available day (if the next day is eligible for republish). No Time Limit will republish at any time.', 'wp-auto-republish' ),
            __( 'Note: If you are using Time Limits then you have to set the "Republish Process Interval within a Day" option as less than the interval between Start Time and End Time so that process interval fits within the interval. Otherwise, it will not work.', 'wp-auto-republish' ),
            ''
        ),
            'options'     => [
				'no'  => __( 'No Time Limit', 'wp-auto-republish' ),
				'yes' => __( 'Set Time Limit', 'wp-auto-republish' ),
			],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_time_start( $args ) {
        $this->do_field( [
            'id'          => $args['label_for'],
            'name'        => 'wpar_start_time',
            'value'       => $this->get_data( 'wpar_start_time', '05:00:00' ),
            'description' => __( 'Set the starting time period for republish old posts from here. Republish will start from this time.', 'wp-auto-republish' ),
            'class'       => 'wpar-timepicker',
            'attributes'  => [
				'placeholder' => '05:00:00',
			],
            'required'    => true,
            'readonly'    => true,
            'condition'   => [ 'republish_time_specific', '=', 'yes' ],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_time_end( $args ) {
        $this->do_field( [
            'id'          => $args['label_for'],
            'name'        => 'wpar_end_time',
            'value'       => $this->get_data( 'wpar_end_time', '23:59:59' ),
            'description' => __( 'Set the ending time period for republish old posts from here. Republish will not occur after this time.', 'wp-auto-republish' ),
            'class'       => 'wpar-timepicker',
            'attributes'  => [
				'placeholder' => '05:00:00',
			],
            'required'    => true,
            'readonly'    => true,
            'condition'   => [ 'republish_time_specific', '=', 'yes' ],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_days( $args ) {
        $this->do_field( [
            'type'        => 'multiple',
            'id'          => $args['label_for'],
            'name'        => 'wpar_days',
            'value'       => $this->get_data( 'wpar_days', [
				'sun',
				'mon',
				'tue',
				'wed',
				'thu',
				'fri',
				'sat',
			] ),
            'description' => __( 'Select the weekdays when you want to republish old posts. If you want to disable republish on any weekday, you can easily do it from here. Just remove that day and save your settings.', 'wp-auto-republish' ),
            'options'     => [
				'sun' => __( 'Sunday', 'wp-auto-republish' ),
				'mon' => __( 'Monday', 'wp-auto-republish' ),
				'tue' => __( 'Tuesday', 'wp-auto-republish' ),
				'wed' => __( 'Wednesday', 'wp-auto-republish' ),
				'thu' => __( 'Thursday', 'wp-auto-republish' ),
				'fri' => __( 'Friday', 'wp-auto-republish' ),
				'sat' => __( 'Saturday', 'wp-auto-republish' ),
			],
            'show_if'     => 'wpar_enable_plugin',
        ] );
    }
    
    public function republish_info( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_position',
            'value'       => $this->get_data( 'wpar_republish_position', 'disable' ),
            'description' => __( 'Select how you want to show original published date of the post on frontend. Before Content option will push Republish info to top and After Content will pust post content to top. You can keep it disable if you don\'t want to use this.', 'wp-auto-republish' ),
            'options'     => [
				'disable'        => __( 'Disable', 'wp-auto-republish' ),
				'before_content' => __( 'Before Content', 'wp-auto-republish' ),
				'after_content'  => __( 'After Content', 'wp-auto-republish' ),
			],
        ] );
    }
    
    public function republish_info_text( $args ) {
        $this->do_field( [
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_position_text',
            'class'       => 'expand',
            'value'       => $this->get_data( 'wpar_republish_position_text', 'Originally posted on ' ),
            'description' => __( 'Message before original published date of the post on frontend. It will work like prefix. Post Republish info will be added after this prefix if actually exists.', 'wp-auto-republish' ),
            'required'    => true,
            'condition'   => [ 'wpar_republish_info', '!=', 'disable' ],
        ] );
    }
    
    public function republish_post_age( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_post_age',
            'value'       => $this->get_data( 'wpar_republish_post_age', 30 ),
            'description' => __( 'Select the post age for republishing. Post originally published before this, will be available for republish. Note: If a post is already republished, then plugin will consider the new republished date, not the actual published date.', 'wp-auto-republish' ),
            'options'     => $this->do_filter( 'republish_eligibility_age', [
				'premium_1' => __( 'No Age Limit (Premium)', 'wp-auto-republish' ),
				'30'        => __( '30 Days (1 month)', 'wp-auto-republish' ),
				'45'        => __( '45 Days (1.5 months)', 'wp-auto-republish' ),
				'60'        => __( '60 Days (2 months)', 'wp-auto-republish' ),
				'90'        => __( '90 Days (3 months)', 'wp-auto-republish' ),
				'120'       => __( '120 Days (4 months)', 'wp-auto-republish' ),
				'180'       => __( '180 Days (6 months)', 'wp-auto-republish' ),
				'240'       => __( '240 Days (8 months)', 'wp-auto-republish' ),
				'365'       => __( '365 Days (1 year)', 'wp-auto-republish' ),
				'730'       => __( '730 Days (2 years)', 'wp-auto-republish' ),
				'1095'      => __( '1095 Days (3 years)', 'wp-auto-republish' ),
				'premium_2' => __( 'Custom Age Limit (Premium)', 'wp-auto-republish' ),
			] ),
        ] );
    }
    
    public function republish_order( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_method',
            'value'       => $this->get_data( 'wpar_republish_method', 'old_first' ),
            'description' => __( 'Select the method of getting old posts from database.', 'wp-auto-republish' ),
            'options'     => [
				'old_first' => __( 'Select Old Post First (ASC)', 'wp-auto-republish' ),
				'new_first' => __( 'Select New Post First (DESC)', 'wp-auto-republish' ),
			],
        ] );
    }
    
    public function republish_orderby( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_republish_orderby',
            'value'       => $this->get_data( 'wpar_republish_orderby', 'date' ),
            'description' => __( 'Select the method of getting old posts order by parameter. Default: Post Date', 'wp-auto-republish' ),
            'options'     => $this->do_filter( 'republish_orderby_items', [
				'date'      => __( 'Post Date', 'wp-auto-republish' ),
				'premium_1' => __( 'Post ID (Premium)', 'wp-auto-republish' ),
				'premium_2' => __( 'Post Author (Premium)', 'wp-auto-republish' ),
				'premium_3' => __( 'Post Title (Premium)', 'wp-auto-republish' ),
				'premium_4' => __( 'Post Name (Premium)', 'wp-auto-republish' ),
				'premium_5' => __( 'Random Selection (Premium)', 'wp-auto-republish' ),
				'premium_6' => __( 'Comment Count (Premium)', 'wp-auto-republish' ),
				'premium_7' => __( 'Relevance (Premium)', 'wp-auto-republish' ),
				'premium_8' => __( 'Menu Order (Premium)', 'wp-auto-republish' ),
			] ),
        ] );
    }
    
    public function post_types_list( $args ) {
        $this->do_field( [
            'type'        => 'multiple',
            'id'          => $args['label_for'],
            'name'        => 'wpar_post_types',
            'class'       => 'wpar-post-types',
            'value'       => $this->get_data( 'wpar_post_types', [ 'post' ] ),
            'description' => __( 'Select the post types of which you want to republish using global method.', 'wp-auto-republish' ),
            'options'     => $this->get_post_types(),
        ] );
    }
    
    public function taxonomies_filter( $args ) {
        $this->do_field( [
            'type'        => 'select',
            'id'          => $args['label_for'],
            'name'        => 'wpar_exclude_by_type',
            'value'       => $this->get_data( 'wpar_exclude_by_type', 'none' ),
            'description' => __( 'Select how you want to include or exclude a post category from republishing. If you choose Exclude Taxonomies, selected taxonomies will be ignored and Include will add them only.', 'wp-auto-republish' ),
            'options'     => [
				'none'    => __( 'Disable', 'wp-auto-republish' ),
				'include' => __( 'Include Taxonomies', 'wp-auto-republish' ),
				'exclude' => __( 'Exclude Taxonomies', 'wp-auto-republish' ),
			],
        ] );
    }
    
    public function post_taxonomy( $args ) {
        $this->do_field( [
            'type'        => 'multiple_tax',
            'id'          => $args['label_for'],
            'name'        => 'wpar_post_taxonomy',
            'value'       => $this->get_data( 'wpar_post_taxonomy', [] ),
            'description' => __( 'Select taxonimies which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' ),
            'class'       => 'wpar-taxonomies',
            'condition'   => [ 'wpar_taxonomies_filter', '!=', 'none' ],
        ] );
    }
    
    public function force_include( $args ) {
        $value = $this->get_data( 'force_include', [] );
        $this->do_field( [
            'type'        => 'multiple',
            'id'          => $args['label_for'],
            'name'        => 'force_include',
            'value'       => ( is_array( $value ) ? $value : explode( ',', $value ) ),
            'description' => __( 'Write the post IDs which you want to include forcefully in the republish process. But it doesn\'t mean that it will republish every time, rather it will added to the republish eligible post list. These posts will be republished only if the orther conditions are met.', 'wp-auto-republish' ),
        ] );
    }
    
    public function force_exclude( $args ) {
        $value = $this->get_data( 'wpar_override_category_tag', [] );
        $this->do_field( [
            'type'        => 'multiple',
            'id'          => $args['label_for'],
            'name'        => 'wpar_override_category_tag',
            'value'       => ( is_array( $value ) ? $value : explode( ',', $value ) ),
            'description' => __( 'Write the post IDs which you want to exclude forcefully from the republish process.', 'wp-auto-republish' ),
        ] );
    }
    
    public function remove_plugin_data( $args ) {
        $this->do_field( [
            'type'        => 'checkbox',
            'id'          => $args['label_for'],
            'name'        => 'wpar_remove_plugin_data',
            'checked'     => 1 == $this->get_data( 'wpar_remove_plugin_data' ),
            'description' => __( 'Enable this if you want to remove all the plugin data from your website.', 'wp-auto-republish' ),
        ] );
    }

}