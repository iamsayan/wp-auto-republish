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

use RevivePress\Helpers\Fields;
use RevivePress\Helpers\Hooker;

defined( 'ABSPATH' ) || exit;

class ManagerCallbacks
{
	use Fields;
    use Hooker;

	public function enable_plugin( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'wpar_enable_plugin',
			'checked'     => 1 == $this->get_data( 'wpar_enable_plugin' ),
			'description' => __( 'Enabling this will allow to auto republish old posts of your blog. It is the switch to enable or disable the Global Republish related functionalities of this plugin.', 'wp-auto-republish' ),
		) );
	}

	public function republish_interval_days( $args ) {
		$this->do_field( array(
			'type'        => 'number',
			'id'          => $args['label_for'],
			'name'        => 'republish_interval_days',
			'value'       => $this->get_data( 'republish_interval_days', '1' ),
			'description' => __( 'Set custom interval in Days. Default 1 day means plugin will republish on everyday if all weekdays are selected.', 'wp-auto-republish' ),
			'attributes'  => array(
				'min' => 1,
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function minimun_republish_interval( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_minimun_republish_interval',
			'value'       => $this->get_data( 'wpar_minimun_republish_interval', 300 ),
			'description' => __( 'Select post republish interval between two post republish events. It will be added to the Last Run time (see top right) and will re-run at a point of time which resolves Last Run plus this settings. If Last Run is Today at 10:05 AM and this settings is set to 30 Minutes, then next process will run at 10:35 AM. Although, running of this process doesn\'t mean that it will republish a post every time. It will also check if the below conditions are met, if not, republish will not work at that particular point of time.', 'wp-auto-republish' ),
			'options'     => $this->do_filter( 'minimum_republish_interval', array(
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
			) ),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_custom_interval__premium( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'republish_custom_interval',
			'value'       => $this->get_data( 'republish_custom_interval', '1d' ),
			'description' => __( 'You can set custom interval in minutes between two republish events from here. It will overwrite the pre-defined settings in the plugin codebase. Only use this, if your desired interval is already not specified in the list. You can use postfixes of Year, Month, Week, Day, Hour, Minutes like 1y, 2m, 3w, 4d, 5h, 6i respectively. Without postfix, plugin will assume it as minute(s). Don\'t use less than 5 minutes, otherwise republish may not work properly and overload your server.', 'wp-auto-republish' ),
			'condition'   => array( 'minimun_republish_interval', '=', 'custom' ),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function random_republish_interval( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_random_republish_interval',
			'value'       => $this->get_data( 'wpar_random_republish_interval', 3600 ),
			'description' => __( 'Select randomness interval from here which will be added to post republish time. If republish process runs at 11.25 AM and this option is set to Upto 1 Hour, post will be republished at anytime between 11.25 AM and 12.25 PM. It can make the republishing seem more natural to Readers and SERPs.', 'wp-auto-republish' ),
			'options'     => $this->do_filter( 'random_republish_interval', array(
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
			) ),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_post_position( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_post_position',
			'value'       => $this->get_data( 'wpar_republish_post_position', 'one' ),
			'description' => __( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in 1st place). Let\'s say, your last post/republished post time is 02:45 PM, First Position will push the current republished post to before the last post and Second Position will attach current republished post just after last post with a interval of 5 minutes (can be modified by filter).', 'wp-auto-republish' ),
			'options'     => array(
				'one' => __( 'First Position', 'wp-auto-republish' ),
				'two' => __( 'Second Position', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_action__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_action',
			'value'       => $this->get_data( 'wpar_republish_action', 'repost' ),
			'description' => __( 'Select the post republish action from here. Republish Post will actually republish your old posts and post will not be duplicated. Duplicate Post will make a copy of your existing old post and publish that. It wouldn\'t touch your old exisiting post. Default is Republish Post.', 'wp-auto-republish' ),
			'options'     => array(
				'repost' => __( 'Republish Post', 'wp-auto-republish' ),
				'clone'  => __( 'Duplicate Post', 'wp-auto-republish' ),
				//'update'  => __( 'Update Post', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_time_specific( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'republish_time_specific',
			'value'       => $this->get_data( 'republish_time_specific', 'no' ),
			'description' => sprintf( '%s <div class="wpar-time-limit-msg red">%s <a href="">%s</a></div>', __( 'Enable or Disable Time Specifc Republish from here. If you Enable this, plugin will only republish between the Start Time and End Time. If Start Time is grater than End Time, plugin will assume the end time in on the next available day (if the next day is eligible for republish). No Time Limit will republish at any time.', 'wp-auto-republish' ), __( 'Note: If you are using Time Limits then you have to set the "Republish Process Interval within a Day" option as less than the interval between Start Time and End Time so that process interval fits within the interval. Otherwise, it will not work.', 'wp-auto-republish' ), '' ),
			'options'     => array(
				'no'  => __( 'No Time Limit', 'wp-auto-republish' ),
				'yes' => __( 'Set Time Limit', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_time_start( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'wpar_start_time',
			'value'       => $this->get_data( 'wpar_start_time', '05:00:00' ),
			'description' => __( 'Set the starting time period for republish old posts from here. Republish will start from this time.', 'wp-auto-republish' ),
			'class'       => 'wpar-timepicker',
			'attributes'  => array(
				'placeholder' => '05:00:00',
			),
			'required'    => true,
			'readonly'    => true,
			'condition'   => array( 'republish_time_specific', '=', 'yes' ),
			'show_if'     => 'wpar_enable_plugin',
		) );
    }
    
    public function republish_time_end( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'wpar_end_time',
			'value'       => $this->get_data( 'wpar_end_time', '23:59:59' ),
			'description' => __( 'Set the ending time period for republish old posts from here. Republish will not occur after this time.', 'wp-auto-republish' ),
			'class'       => 'wpar-timepicker',
			'attributes'  => array(
				'placeholder' => '05:00:00',
			),
			'required'    => true,
			'readonly'    => true,
			'condition'   => array( 'republish_time_specific', '=', 'yes' ),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function number_of_posts__premium( $args ) {
		$this->do_field( array(
			'type'        => 'number',
			'id'          => $args['label_for'],
			'name'        => 'number_of_posts',
			'value'       => $this->get_data( 'number_of_posts', 1 ),
			'description' => __( 'You can set here the number of Posts to be Republished at a time. Let\'s say this is set as 5, plugin will only republish 5 available posts when a republish process runs. Use -1 for no limit.', 'wp-auto-republish' ),
			'attributes'  => array(
				'min' => -1,
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function number_of_posts_day__premium( $args ) {
		$this->do_field( array(
			'type'        => 'number',
			'id'          => $args['label_for'],
			'name'        => 'number_of_posts_day',
			'value'       => $this->get_data( 'number_of_posts_day' ),
			'description' => sprintf( '%s <div>%s</div>', __( 'You can set here the number of Posts to be Republished on a prticular day. Let\'s say this is set as 5, plugin will only republish 5 posts within a day. If Start Time and End Time is specified, 5 posts will be republished within the time range and you need to adjust the republish interval accordingly so that 5 posts will fit in the specified interval. Leave blank for no limit.', 'wp-auto-republish' ), 
				sprintf( 
					/* translators: Number of Posts republished today. */
					__( 'Number of Posts republished today: %s', 'wp-auto-republish' ), $this->get_daily_completed() 
				) 
			),
			'attributes'  => array(
				'min' => 1,
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_days( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'wpar_days',
			'value'       => $this->get_data( 'wpar_days', array( 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ) ),
			'description' => __( 'Select the weekdays when you want to republish old posts. If you want to disable republish on any weekday, you can easily do it from here. Just remove that day and save your settings.', 'wp-auto-republish' ),
			'options'     => array(
				'sun' => __( 'Sunday', 'wp-auto-republish' ),
				'mon' => __( 'Monday', 'wp-auto-republish' ),
				'tue' => __( 'Tuesday', 'wp-auto-republish' ),
				'wed' => __( 'Wednesday', 'wp-auto-republish' ),
				'thu' => __( 'Thursday', 'wp-auto-republish' ),
				'fri' => __( 'Friday', 'wp-auto-republish' ),
				'sat' => __( 'Saturday', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_enable_plugin',
		) );
	}

	public function republish_info( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_position',
			'value'       => $this->get_data( 'wpar_republish_position', 'disable' ),
			'description' => __( 'This will show original published date of the post on frontend only for Republished Posts. Before Content option will push Republish info to top and After Content will push post content to top. You can keep it disable if you don\'t want to use this.', 'wp-auto-republish' ),
			'options'     => array(
				'disable'        => __( 'Disable', 'wp-auto-republish' ),
				'before_content' => __( 'Before Content', 'wp-auto-republish' ),
				'after_content'  => __( 'After Content', 'wp-auto-republish' ),
			),
		) );
	}
	
	public function republish_info_text( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_position_text',
			'class'       => 'expand',
			'value'       => $this->get_data( 'wpar_republish_position_text', 'Originally posted on ' ),
			'description' => __( 'Message before original published date of the post on frontend. It will work like prefix. Post Republish info will be added after this prefix if actually exists.', 'wp-auto-republish' ),
			'required'    => true,
			'condition'   => array( 'wpar_republish_info', '!=', 'disable' ),
		) );
	}

	public function post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'wpar_post_types_display',
			'value'       => $this->get_data( 'wpar_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types on which you want to display original published date. Republish info will be visible only on selected post types.', 'wp-auto-republish' ),
			'class'       => 'wpar-post-types',
			'options'     => $this->get_post_types(),
			'condition'   => array( 'wpar_republish_info', '!=', 'disable' ),
		) );
	}

	public function date_time_format_display__premium( $args ) {
		$get_df = get_option( 'date_format' );
    	$get_tf = get_option( 'time_format' );

		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'date_time_format_display',
			'value'       => $this->get_data( 'date_time_format_display', $get_df . ' @ ' . $get_tf ),
			'description' => __( 'Set date time format here.', 'wp-auto-republish' ) . ' <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank" rel="nopender">' . __( 'Learn more', 'wp-auto-republish' ) . '</a>',
			'condition'   => array( 'wpar_republish_info', '!=', 'disable' ),
		) );
	}

	public function republish_post_age( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_post_age',
			'value'       => $this->get_data( 'wpar_republish_post_age', 30 ),
			'description' => __( 'Select the post age for republishing. Post originally published before this, will be available for republish. Note: If a post is already republished, then plugin will consider the new republished date, not the actual published date.', 'wp-auto-republish' ),
			'options'     => $this->do_filter( 'republish_eligibility_age', array(
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
			) ),
		) );
	}

	public function republish_custom_age__premium( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'republish_post_custom_age',
			'value'       => $this->get_data( 'republish_post_custom_age', '1m' ),
			'description' => __( 'You can set post republishing age from here. It will overwrite the pre-defined settings in the plugin codebase. Only use this, if your desired interval is already not specified in the list. You can use suffixes of Year, Month, Week, Day, Hour, Minutes like 1y, 2m, 3w, 4d, 5h, 6i respectively. Without suffix, plugin will assume it as minutes.', 'wp-auto-republish' ),
			'condition'   => array( 'wpar_republish_post_age', '=', 'custom' ),
		) );
	}

	public function republish_post_age_start__premium( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'republish_post_age_start',
			'value'       => $this->get_data( 'republish_post_age_start' ),
			'class'       => 'wpar-datepicker',
			'readonly'    => true,
			'description' => __( 'Posts having published date after this date are eligible for republish. Republish process will ignore all posts published before this date. Leave it blank for no limit.', 'wp-auto-republish' ),
		) );
	}

	public function filter_thumbnail__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'filter_thumbnail',
			'checked'     => 1 == $this->get_data( 'filter_thumbnail' ),
			'description' => __( 'Enabling this will only republish which post has a valid thumbnail. Posts with no thubnails will be ignored.', 'wp-auto-republish' ),
		) );
	}

	public function ignore_sticky_posts__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'ignore_sticky_posts',
			'checked'     => 1 == $this->get_data( 'ignore_sticky_posts' ),
			'description' => __( 'Enabling this will not republish yout sticky posts. Only non-sticky posts will be queried for republish.', 'wp-auto-republish' ),
		) );
	}

	public function republish_order( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_method',
			'value'       => $this->get_data( 'wpar_republish_method', 'old_first' ),
			'description' => __( 'Select the method of getting old posts from database.', 'wp-auto-republish' ),
			'options'     => array(
				'old_first' => __( 'Select Old Post First (ASC)', 'wp-auto-republish' ),
				'new_first' => __( 'Select New Post First (DESC)', 'wp-auto-republish' ),
			),
		) );
	}

	public function republish_orderby( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_republish_orderby',
			'value'       => $this->get_data( 'wpar_republish_orderby', 'date' ),
			'description' => __( 'Select the method of getting old posts order by parameter. Default: Post Date', 'wp-auto-republish' ),
			'options'     => $this->do_filter( 'republish_orderby_items', array(
				'date'      => __( 'Post Date', 'wp-auto-republish' ),
				'premium_1' => __( 'Post ID (Premium)', 'wp-auto-republish' ),
				'premium_2' => __( 'Post Author (Premium)', 'wp-auto-republish' ),
				'premium_3' => __( 'Post Title (Premium)', 'wp-auto-republish' ),
				'premium_4' => __( 'Post Name (Premium)', 'wp-auto-republish' ),
				'premium_5' => __( 'Random Selection (Premium)', 'wp-auto-republish' ),
				'premium_6' => __( 'Comment Count (Premium)', 'wp-auto-republish' ),
				'premium_7' => __( 'Relevance (Premium)', 'wp-auto-republish' ),
				'premium_8' => __( 'Menu Order (Premium)', 'wp-auto-republish' ),
			) ),
		) );
	}

	public function post_types_list( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'wpar_post_types',
			'class'       => 'wpar-post-types',
			'value'       => $this->get_data( 'wpar_post_types', array( 'post' ) ),
			'description' => __( 'Select the post types of which you want to republish using global method.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
		) );
	}

	public function post_statuses__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'post_statuses',
			'class'       => 'wpar-post-statuses',
			'value'       => $this->get_data( 'post_statuses', array( 'publish' ) ),
			'description' => __( 'Select which post status(es) may be eligible for republish.', 'wp-auto-republish' ),
			'options'     => $this->get_post_statuses(),
		) );
    }

	public function taxonomies_filter( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_exclude_by_type',
			'value'       => $this->get_data( 'wpar_exclude_by_type', 'none' ),
			'description' => __( 'Select how you want to include or exclude a post category from republishing. If you choose Exclude Taxonomies, selected taxonomies will be ignored and Include will add them only.', 'wp-auto-republish' ),
			'options'     => array(
				'none'    => __( 'Disable', 'wp-auto-republish' ),
				'include' => __( 'Include Taxonomies', 'wp-auto-republish' ),
				'exclude' => __( 'Exclude Taxonomies', 'wp-auto-republish' ),
			),
		) );
    }
    
	public function post_taxonomy( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'wpar_post_taxonomy',
			'value'       => $this->get_data( 'wpar_post_taxonomy', array() ),
			'description' => __( 'Select taxonimies which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'condition'   => array( 'wpar_taxonomies_filter', '!=', 'none' ),
		) );
	}

	public function force_include( $args ) {
		$value = $this->get_data( 'force_include', array() );
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'force_include',
			'value'       => is_array( $value ) ? $value : explode( ',', $value ),
			'description' => __( 'Write the post IDs which you want to include forcefully in the republish process. But it doesn\'t mean that it will republish every time, rather it will added to the republish eligible post list. These posts will be republished only if the orther conditions are met.', 'wp-auto-republish' ),
		) );
	}

	public function force_exclude( $args ) {
		$value = $this->get_data( 'wpar_override_category_tag', array() );
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'wpar_override_category_tag',
			'value'       => is_array( $value ) ? $value : explode( ',', $value ),
			'description' => __( 'Write the post IDs which you want to exclude forcefully from the republish process.', 'wp-auto-republish' ),
		) );
	}

	public function authors_filter__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'authors_filter',
			'value'       => $this->get_data( 'authors_filter', 'none' ),
			'description' => __( 'Select how you want to include or exclude a post which has a specific author from republishing. If you choose Exclude Authors, posts with selected author will be ignored and vice-versa.', 'wp-auto-republish' ),
			'options'     => array(
				'none'    => __( 'Disable', 'wp-auto-republish' ),
				'include' => __( 'Include Authors', 'wp-auto-republish' ),
				'exclude' => __( 'Exclude Authors', 'wp-auto-republish' ),
			),
		) );
    }

	public function republish_allowed_authors__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'allowed_authors',
			'value'       => $this->get_data( 'allowed_authors', array() ),
			'description' => __( 'Select post types of which you want to republish.', 'wp-auto-republish' ),
			'attributes'  => array(
				'data-placeholder' => __( 'Select user roles', 'wp-auto-republish' ),
			),
			'condition'   => array( 'wpar_authors_filter', '!=', 'none' ),
		) );
	}
    
	public function enable_single_metabox__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'enable_single_metabox',
			'checked'     => 1 == $this->get_data( 'enable_single_metabox' ),
			'description' => __( 'Enabling this will allow to edit republish parameters per post basis. It adds a metabox on post edit screen from where you can set custom republish events.', 'wp-auto-republish' ),
		) );
	}

	public function enable_single_republishing__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'wpar_enable_single_republishing',
			'checked'     => 1 == $this->get_data( 'wpar_enable_single_republishing' ),
			'description' => __( 'Enabling this will allow to enable automatic republish of single posts of your blog. It adds a metabox on post edit screen from where you can set custom republish events and schedules.', 'wp-auto-republish' ),
		) );
	}

	public function single_republish_action__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'wpar_single_republish_action',
			'value'       => $this->get_data( 'wpar_single_republish_action', 'repost' ),
			'description' => __( 'Select the single post republish action from here. Default is Republish Post.', 'wp-auto-republish' ),
			'options'     => array(
				'repost' => __( 'Republish Post', 'wp-auto-republish' ),
				'clone'  => __( 'Duplicate Post', 'wp-auto-republish' ),
				//'update'  => __( 'Update Post', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_enable_single_republishing',
		) );
	}
	
	public function post_types_list_single__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'post_types_list_single',
			'class'       => 'wpar-post-types',
			'value'       => $this->get_data( 'post_types_list_single', array( 'post' ) ),
			'description' => __( 'Select post types on which you want to enable single republish. It will add a metabox to the all posts from which you can configure the single republishing for a particular post.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'show_if'     => 'wpar_enable_single_republishing',
		) );
	}

	public function single_roles__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'wpar_single_roles',
			'value'       => $this->get_data( 'wpar_single_roles', array( 'administrator' ) ),
			'description' => __( 'Set user roles who can access the metaboxes.', 'wp-auto-republish' ),
			'options'     => $this->get_roles(),
			'show_if'     => 'wpar_enable_single_metabox',
		) );
	}

	public function enable_instant_republishing__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'wpar_enable_instant_republishing',
			'checked'     => 1 == $this->get_data( 'wpar_enable_instant_republishing' ),
			'description' => __( 'Enabling this will allow to enable republish links on post edit rows and post edit screen.', 'wp-auto-republish' ),
		) );
	}

	public function allowed_actions__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'class'       => 'do-select2',
			'name'        => 'allowed_actions',
			'value'       => $this->get_data( 'allowed_actions', array( 'republish', 'clone' ) ),
			'description' => __( 'Select the Action Links which you want to show on Post List Page and Post edit screen. If all the sharing are disabled, then share option will be hidden from Post List Page and Post edit screen.', 'wp-auto-republish' ),
			'options'     => array(
				'republish' => __( 'Republish', 'wp-auto-republish' ),
				'clone'     => __( 'Clone', 'wp-auto-republish' ),
				'rewrite'   => __( 'Rewrite & Republish', 'wp-auto-republish' ),
				'share'     => __( 'Share', 'wp-auto-republish' ),
			),
			'attributes'  => array(
				'data-placeholder' => __( 'Select actions links', 'wp-auto-republish' ),
			),
			'required'    => true,
			'show_if'     => 'wpar_enable_instant_republishing',
		) );
	}

	public function show_links_in__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'class'       => 'do-select2',
			'name'        => 'show_links_in',
			'value'       => $this->get_data( 'show_links_in', array( 'post_list' ) ),
			'description' => __( 'Select the places where you want to show Action Links.', 'wp-auto-republish' ),
			'options'     => array(
				'post_list'   => __( 'Post List', 'wp-auto-republish' ),
				'edit_screen' => __( 'Post Edit Screen', 'wp-auto-republish' ),
			),
			'attributes'  => array(
				'data-placeholder' => __( 'Select links positions', 'wp-auto-republish' ),
			),
			'required'    => true,
			'show_if'     => 'wpar_enable_instant_republishing',
		) );
	}

	public function post_types_list_instant__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'post_types_list_instant',
			'class'       => 'wpar-post-types',
			'value'       => $this->get_data( 'post_types_list_instant', array( 'post' ) ),
			'description' => __( 'Select post types on which you want to enable single republish. It will add a metabox to the all posts from which you can configure the single republishing for a particular post.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'show_if'     => 'wpar_enable_instant_republishing',
		) );
	}

	public function instant_roles__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'instant_roles',
			'value'       => $this->get_data( 'instant_roles', array( 'administrator' ) ),
			'description' => __( 'Set user roles who can access the metabox and post row links.', 'wp-auto-republish' ),
			'options'     => $this->get_roles(),
			'show_if'     => 'wpar_enable_instant_republishing',
		) );
	}

	public function unique_posting__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'unique_posting',
			'checked'     => 1 == $this->get_data( 'unique_posting' ),
			'description' => __( 'Enabling this will allow to add an unique random string after post URLs at the time of republish.', 'wp-auto-republish' ),
		) );
	}

	public function link_shortner__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'link_shortner',
			'value'       => $this->get_data( 'link_shortner', 'disable' ),
			'description' => __( 'Select Link Shortner type from here.', 'wp-auto-republish' ),
			'options'     => array(
				'disable'   => __( 'Disable', 'wp-auto-republish' ),
	         	'wordpress' => __( 'WordPress', 'wp-auto-republish' ),
				'tinyurl'   => __( 'TinyURL', 'wp-auto-republish' ),
				'is_gd'     => __( 'is.gd', 'wp-auto-republish' ),
				'bitly'     => __( 'Bit.ly', 'wp-auto-republish' ),
				'shortest'  => __( 'Shorte.st', 'wp-auto-republish' ),
			),
		) );
	}

	public function bitly_token__premium( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'bitly_token',
			'value'       => $this->get_data( 'bitly_token' ),
			'description' => __( 'Enter Bit.ly Access Token here.', 'wp-auto-republish' ),
			'condition'   => array( 'link_shortner', '=', 'bitly' ),
		) );
	}

	public function shortest_token__premium( $args ) {
		$this->do_field( array(
			'id'          => $args['label_for'],
			'name'        => 'shortest_token',
			'value'       => $this->get_data( 'shortest_token' ),
			'description' => __( 'Enter Shorte.st Access Token here.', 'wp-auto-republish' ),
			'condition'   => array( 'link_shortner', '=', 'shortest' ),
		) );
	}

	public function url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'url_patameters',
			'value'       => $this->get_data( 'url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
		) );
	}

	public function fb_social_enable__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'facebook_enable',
			'checked'     => 1 == $this->get_data( 'facebook_enable' ),
			'description' => __( 'Enabling this will allow to enable auto post publish to Facebook upon post republishing.', 'wp-auto-republish' ),
		) );
	}

	public function fb_social_og_tag__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'facebook_og_tag',
			'checked'     => 1 == $this->get_data( 'facebook_og_tag' ),
			'description' => __( 'Enabling this will allow to add Open Graph metadata to your site head section and other social networks use this data when your pages are shared. If you are using any SEO plugin, you can leave this option as disable.', 'wp-auto-republish' ),
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}
	
	public function fb_social_post_as__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'facebook_post_as',
			'value'       => $this->get_data( 'facebook_post_as', 'link_status' ),
			'description' => __( 'Select Facebook post template type from here.', 'wp-auto-republish' ),
			'options'     => array(
				'link'        => __( 'Post as Link', 'wp-auto-republish' ),
				'status'      => __( 'Post as Status', 'wp-auto-republish' ),
				'link_status' => __( 'Post as Status & Link', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function fb_social_content_source__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'facebook_content_source',
			'value'       => $this->get_data( 'facebook_content_source', 'post_content' ),
			'description' => __( 'Select Facebook post template content source from here. %post_content% will be replaced by this in the below field.', 'wp-auto-republish' ),
			'options'     => array(
				'post_content' => __( 'Post Content', 'wp-auto-republish' ),
				'post_excerpt' => __( 'Post Excerpt', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function fb_social_template__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'facebook_template',
			'value'       => $this->get_data( 'facebook_template', '%post_title% %post_content% %post_url% %hashtags%' ),
			'description' => $this->do_filter( 'social_template_tags', 'facebook' ),
			'attributes'  => array(
				'rows' => 5,
				'cols' => 100,
			),
			'required'    => true,
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function fb_post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'facebook_post_types_display',
			'value'       => $this->get_data( 'facebook_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types of which you want to share on Facebook as post.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function fb_social_taxonomy__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'facebook_social_taxonomy',
			'value'       => $this->get_data( 'facebook_social_taxonomy', array() ),
			'description' => __( 'Select taxonomies of which you want to post them on Facebook as hashtags. If a post is actually attached with these taxonomies, then these will be included in the post as hashtags.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function fb_url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'facebook_url_patameters',
			'value'       => $this->get_data( 'facebook_url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
			'show_if'     => 'wpar_fb_social_enable',
		) );
	}

	public function tw_social_enable__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'twitter_enable',
			'checked'     => 1 == $this->get_data( 'twitter_enable' ),
			'description' => __( 'Enabling this will allow to enable auto tweet publish to Twitter upon post republishing.', 'wp-auto-republish' ),
		) );
	}

	public function tw_social_thumbnail__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'twitter_thumbnail',
			'value'       => $this->get_data( 'twitter_thumbnail', 'yes' ),
			'description' => __( 'Enable or Disable Tweet Post Thumbnail form here.', 'wp-auto-republish' ),
			'options'     => array(
				'yes' => __( 'Show Thumbnail', 'wp-auto-republish' ),
				'no'  => __( 'Don\'t Show Thumbnail', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}

	public function tw_social_content_source__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'twitter_content_source',
			'value'       => $this->get_data( 'twitter_content_source', 'post_content' ),
			'description' => __( 'Select Twitter tweet template content source from here. %post_content% tag will be replaced by this in the below field.', 'wp-auto-republish' ),
			'options'     => array(
				'post_content' => __( 'Post Content', 'wp-auto-republish' ),
				'post_excerpt' => __( 'Post Excerpt', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}

	public function tw_social_template__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'twitter_template',
			'value'       => $this->get_data( 'twitter_template', '%post_title% %post_content% %post_url% %hashtags%' ),
			'description' => $this->do_filter( 'social_template_tags', 'twitter' ),
			'attributes'  => array(
				'rows' => 5,
				'cols' => 100,
			),
			'required'    => true,
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}
	
	public function tw_post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'twitter_post_types_display',
			'value'       => $this->get_data( 'twitter_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types of which you want to share on Twitter as tweet.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}

	public function tw_social_taxonomy__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'twitter_social_taxonomy',
			'value'       => $this->get_data( 'twitter_social_taxonomy', array() ),
			'description' => __( 'Select taxonomies of which you want to post them on Twitter as hashtags. If a post is actually attached with these taxonomies, then these will be included in the post as hashtags.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}

	public function tw_url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'twitter_url_patameters',
			'value'       => $this->get_data( 'twitter_url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
			'show_if'     => 'wpar_tw_social_enable',
		) );
	}

	public function ld_social_enable__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_enable',
			'checked'     => 1 == $this->get_data( 'linkedin_enable' ),
			'description' => __( 'Enabling this will allow to enable auto post publish to Linkedin upon post republishing.', 'wp-auto-republish' ),
		) );
	}

	public function ld_social_post_as__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_post_as',
			'value'       => $this->get_data( 'linkedin_post_as', 'link_status' ),
			'description' => __( 'Select Linkedin post template type from here.', 'wp-auto-republish' ),
			'options'     => array(
				'link'        => __( 'Post as Link', 'wp-auto-republish' ),
				'status'      => __( 'Post as Status', 'wp-auto-republish' ),
				'link_status' => __( 'Post as Media', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function ld_social_content_source__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_content_source',
			'value'       => $this->get_data( 'linkedin_content_source', 'post_content' ),
			'description' => __( 'Select Linkedin post template content source from here. %post_content% will be replaced by this in the below field.', 'wp-auto-republish' ),
			'options'     => array(
				'post_content' => __( 'Post Content', 'wp-auto-republish' ),
				'post_excerpt' => __( 'Post Excerpt', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function ld_social_template__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_template',
			'value'       => $this->get_data( 'linkedin_template', '%post_title% %post_content% %post_url% %hashtags%' ),
			'description' => $this->do_filter( 'social_template_tags', 'linkedin' ),
			'attributes'  => array(
				'rows' => 5,
				'cols' => 100,
			),
			'required'    => true,
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function ld_post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_post_types_display',
			'value'       => $this->get_data( 'linkedin_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types of which you want to share on Linkedin as post.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function ld_social_taxonomy__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_social_taxonomy',
			'value'       => $this->get_data( 'linkedin_social_taxonomy', array() ),
			'description' => __( 'Select taxonomies of which you want to post them on Linkedin as hashtags. If a post is actually attached with these taxonomies, then these will be included in the post as hashtags.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function ld_url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'linkedin_url_patameters',
			'value'       => $this->get_data( 'linkedin_url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
			'show_if'     => 'wpar_ld_social_enable',
		) );
	}

	public function pi_social_enable__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_enable',
			'checked'     => 1 == $this->get_data( 'pinterest_enable' ),
			'description' => __( 'Enabling this will allow to enable auto post to Pinterest upon post republishing.', 'wp-auto-republish' ),
		) );
	}

	public function pi_social_content_source__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_content_source',
			'value'       => $this->get_data( 'pinterest_content_source', 'post_content' ),
			'description' => __( 'Select Pinterest Pin template content source from here. %post_content% tag will be replaced by this in the below field.', 'wp-auto-republish' ),
			'options'     => array(
				'post_content' => __( 'Post Content', 'wp-auto-republish' ),
				'post_excerpt' => __( 'Post Excerpt', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_pi_social_enable',
		) );
	}

	public function pi_social_template__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_template',
			'value'       => $this->get_data( 'pinterest_template', '%post_title% %post_content% %post_url% %hashtags%' ),
			'description' => $this->do_filter( 'social_template_tags', 'pinterest' ),
			'attributes'  => array(
				'rows' => 5,
				'cols' => 100,
			),
			'required'    => true,
			'show_if'     => 'wpar_pi_social_enable',
		) );
	}
	
	public function pi_post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_post_types_display',
			'value'       => $this->get_data( 'pinterest_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types of which you want to share on Pinterest as Pin.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_pi_social_enable',
		) );
	}

	public function pi_social_taxonomy__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_social_taxonomy',
			'value'       => $this->get_data( 'pinterest_social_taxonomy', array() ),
			'description' => __( 'Select taxonomies of which you want to post them on Pinterest as hashtags. If a post is actually attached with these taxonomies, then these will be included in the post as hashtags.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'show_if'     => 'wpar_pi_social_enable',
		) );
	}

	public function pi_url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'pinterest_url_patameters',
			'value'       => $this->get_data( 'pinterest_url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
			'show_if'     => 'wpar_pi_social_enable',
		) );
	}

	public function tb_social_enable__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_enable',
			'checked'     => 1 == $this->get_data( 'tumblr_enable' ),
			'description' => __( 'Enabling this will allow to enable auto publish to Tumblr upon post republishing.', 'wp-auto-republish' ),
		) );
	}

	public function tb_social_type__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_posting_type',
			'value'       => $this->get_data( 'tumblr_posting_type', 'link' ),
			'description' => __( 'Select Tumblr Post sharing type here.', 'wp-auto-republish' ),
			'options'     => array(
				'link' => __( 'Link Posting', 'wp-auto-republish' ),
				'text' => __( 'Text Posting', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}

	public function tb_social_thumbnail__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_thumbnail',
			'value'       => $this->get_data( 'tumblr_thumbnail', 'yes' ),
			'description' => __( 'Enable or Disable Tweet Post Thumbnail form here.', 'wp-auto-republish' ),
			'options'     => array(
				'yes' => __( 'Show Thumbnail', 'wp-auto-republish' ),
				'no'  => __( 'Don\'t Show Thumbnail', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}
	
	public function tb_social_content_source__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_content_source',
			'value'       => $this->get_data( 'tumblr_content_source', 'post_content' ),
			'description' => __( 'Select Tumblr post template content source from here. %post_content% tag will be replaced by this in the below field.', 'wp-auto-republish' ),
			'options'     => array(
				'post_content' => __( 'Post Content', 'wp-auto-republish' ),
				'post_excerpt' => __( 'Post Excerpt', 'wp-auto-republish' ),
			),
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}
	
	public function tb_social_template__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_template',
			'value'       => $this->get_data( 'tumblr_template', '%post_content%' ),
			'description' => $this->do_filter( 'social_template_tags', 'tumblr' ),
			'attributes'  => array(
				'rows' => 5,
				'cols' => 100,
			),
			'required'    => true,
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}
	
	public function tb_post_types_list_display__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_post_types_display',
			'value'       => $this->get_data( 'tumblr_post_types_display', array( 'post' ) ),
			'description' => __( 'Select post types of which you want to share on Tumblr as link or text.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}
	
	public function tb_social_taxonomy__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple_tax',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_social_taxonomy',
			'value'       => $this->get_data( 'tumblr_social_taxonomy', array() ),
			'description' => __( 'Select taxonomies of which you want to post them on Tumblr as hashtags. If a post is actually attached with these taxonomies, then these will be included in the post as hashtags.', 'wp-auto-republish' ),
			'class'       => 'wpar-taxonomies',
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}
	
	public function tb_url_patameters__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'tumblr_url_patameters',
			'value'       => $this->get_data( 'tumblr_url_patameters' ),
			'description' => __( 'Adds the specified string as extra URL parameters to your Post Share URL so that they can be tracked as an event by your analytics system.', 'wp-auto-republish' ),
			'attributes'  => array(
				'rows'        => 3,
				'cols'        => 100,
				'placeholder' => 'utm_medium=ppc&utm_source=adwords&utm_campaign=snow-boots&utm_content=durable-snow-boots',
			),
			'show_if'     => 'wpar_tb_social_enable',
		) );
	}

	public function enable_email_notify__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'enable_email_notify',
			'checked'     => 1 == $this->get_data( 'enable_email_notify' ),
			'description' => __( 'Enabling this will allow to get republish info as an email notification.', 'wp-auto-republish' ),
		) );
	}

	public function enable_post_author_email__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'enable_post_author_email',
			'checked'     => 1 == $this->get_data( 'enable_post_author_email' ),
			'description' => __( 'Enabling this will allow to auto send email to the original post author.', 'wp-auto-republish' ),
			'show_if'     => 'wpar_enable_email_notify',
		) );
	}

	public function email_recipients__premium( $args ) {
		$value = $this->get_data( 'email_recipients', array( get_bloginfo( 'admin_email' ) ) );
		$this->do_field( array(
			'type'       => 'multiple',
			'id'         => $args['label_for'],
			'name'       => 'email_recipients',
			'value'      => is_array( $value ) ? $value : explode( ',', $value ),
			'attributes' => array(
				'placeholder' => get_bloginfo( 'admin_email' ),
			),
			'required'   => true,
			'show_if'    => 'wpar_enable_email_notify',
		) );
	}

	public function email_post_types__premium( $args ) {
		$this->do_field( array(
			'type'        => 'multiple',
			'id'          => $args['label_for'],
			'name'        => 'email_post_types',
			'value'       => $this->get_data( 'email_post_types', array( 'post' ) ),
			'description' => __( 'Select post types for which you want to enable email notification.', 'wp-auto-republish' ),
			'options'     => $this->get_post_types(),
			'class'       => 'wpar-post-types',
			'show_if'     => 'wpar_enable_email_notify',
		) );
	}
	
	public function email_subject__premium( $args ) {
		$this->do_field( array(
			'type'        => 'textarea',
			'id'          => $args['label_for'],
			'name'        => 'email_subject',
			'value'       => $this->get_data( 'email_subject', '[%site_name%] A %post_type% - %post_title% has been republished on your blog.' ),
			'description' => $this->do_filter( 'email_template_tags', 'subject' ),
			'attributes'  => array(
				'rows' => 2,
				'cols' => 100,
			),
			'show_if'     => 'wpar_enable_email_notify',
		) );
	}
	
	public function email_message__premium( $args ) {
		$this->do_field( array(
			'type'        => 'wp_editor',
			'id'          => 'wpar_email_message',
			'name'        => 'email_message',
			'value'       => $this->get_data( 'email_message', 'A %post_type% is republished of your blog by %author_name%' . "\n\n" . '<p><strong>Post: %post_title%</strong></p><p><strong>Post: %post_title%</strong></p><p><strong>Republished Time: %republish_time%</strong></p><p><strong>Original Time: %post_time%</strong></p>' ),
			'description' => $this->do_filter( 'email_template_tags', 'message' ),
			'show_if'     => 'wpar_enable_email_notify',
		) );
	}

	public function log_history_duration__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'log_history_duration',
			'value'       => $this->get_data( 'log_history_duration', 'unlimited' ),
			'description' => __( 'Republish Logs will be deleted automatically based on this settings. This will be checked everyday.', 'wp-auto-republish' ),
			'options'     => array(
				'3'         => __( '3 Days', 'wp-auto-republish' ),
				'7'         => __( '7 Days', 'wp-auto-republish' ),
				'15'        => __( '15 Days', 'wp-auto-republish' ),
				'30'        => __( '30 Days', 'wp-auto-republish' ),
				'45'        => __( '45 Days', 'wp-auto-republish' ),
				'60'        => __( '60 Days', 'wp-auto-republish' ),
				'unlimited' => __( 'Unlimited Days', 'wp-auto-republish' ),
			),
		) );
	}

	public function sort_order__premium( $args ) {
		$this->do_field( array(
			'type'        => 'select',
			'id'          => $args['label_for'],
			'name'        => 'post_sorting_order',
			'value'       => $this->get_data( 'post_sorting_order', 'default' ),
			'description' => __( 'Select the post sorting order on Frontend. Only works if post is actually republished by this plugin.', 'wp-auto-republish' ),
			'options'     => array(
				'default' => __( 'Default', 'wp-auto-republish' ),
				'asc'     => __( 'Original Publish Date (ASC)', 'wp-auto-republish' ),
				'desc'    => __( 'Original Publish Date (DESC)', 'wp-auto-republish' ),
			),
		) );
	}

	public function enable_republish_translated__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'enable_republish_translated',
			'checked'     => 1 == $this->get_data( 'enable_republish_translated' ),
			'description' => __( 'Enabling this will allow to automatically republish all the WPML or Polylang translated posts relating to a particular post which is currently being queried for republish. Also, it will ignore all other conditions for all the translated posts.', 'wp-auto-republish' ),
		) );
	}

	public function enable_silent_republishing__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'wpar_enable_silent_republishing',
			'checked'     => 1 == $this->get_data( 'wpar_enable_silent_republishing' ),
			'description' => __( 'Enable this if you do not want to trigger actual WordPress post publish event. It may stop any social media share or other actions which occur every time when a new post is actually published. Enabling this, will also disable OneSignal support.', 'wp-auto-republish' ),
		) );
	}

	public function disable_guid_reneration__premium( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'disable_guid_reneration',
			'checked'     => 1 == $this->get_data( 'disable_guid_reneration' ),
			'description' => __( 'Disabling this, will disable keep the post GUID same as before republish.', 'wp-auto-republish' ),
		) );
	}

	public function remove_plugin_data( $args ) {
		$this->do_field( array(
			'type'        => 'checkbox',
			'id'          => $args['label_for'],
			'name'        => 'wpar_remove_plugin_data',
			'checked'     => 1 == $this->get_data( 'wpar_remove_plugin_data' ),
			'description' => __( 'Enabling this will allow to remove all the plugin data from your website.', 'wp-auto-republish' ),
		) );
	}
}