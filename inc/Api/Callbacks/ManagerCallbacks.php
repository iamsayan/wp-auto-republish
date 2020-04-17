<?php

/**
 * Settings callbacks.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Api\Callbacks
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Inc\Api\Callbacks;

use  Inc\Helpers\Hooker ;
use  Inc\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
class ManagerCallbacks
{
    use  Hooker, SettingsData ;
    public function enable_plugin( $args )
    {
        ?>  <label class="switch">
			<input type="checkbox" id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_enable_plugin]" value="1" <?php 
        checked( $this->get_data( 'wpar_enable_plugin' ), 1 );
        ?> /> 
			<span class="slider round"></span></label>&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Enable this if you want to auto republish old posts of your blog.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function minimun_republish_interval( $args )
    {
        $items = [
            '300'    => __( '5 Minutes', 'wp-auto-republish' ),
            '600'    => __( '10 Minutes', 'wp-auto-republish' ),
            '900'    => __( '15 Minutes', 'wp-auto-republish' ),
            '1200'   => __( '20 Minutes', 'wp-auto-republish' ),
            '1800'   => __( '30 Minutes', 'wp-auto-republish' ),
            '2700'   => __( '45 Minutes', 'wp-auto-republish' ),
            '3600'   => __( '1 hour', 'wp-auto-republish' ),
            '7200'   => __( '2 hours', 'wp-auto-republish' ),
            '14400'  => __( '4 hours', 'wp-auto-republish' ),
            '21600'  => __( '6 hours', 'wp-auto-republish' ),
            '28800'  => __( '8 hours', 'wp-auto-republish' ),
            '43200'  => __( '12 hours', 'wp-auto-republish' ),
            '86400'  => __( '24 hours (1 day)', 'wp-auto-republish' ),
            '172800' => __( '48 hours (2 days)', 'wp-auto-republish' ),
            '259200' => __( '72 hours (3 days)', 'wp-auto-republish' ),
            '432000' => __( '120 hours (5 days)', 'wp-auto-republish' ),
            '604800' => __( '168 hours (7 days)', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_minimun_republish_interval]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_minimun_republish_interval' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select minimum interval between post republishing.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function random_republish_interval( $args )
    {
        $items = [
            '3600'  => __( 'Upto 1 hour', 'wp-auto-republish' ),
            '7200'  => __( 'Upto 2 hours', 'wp-auto-republish' ),
            '14400' => __( 'Upto 4 hours', 'wp-auto-republish' ),
            '21600' => __( 'Upto 6 hours', 'wp-auto-republish' ),
            '28800' => __( 'Upto 8 hours', 'wp-auto-republish' ),
            '43200' => __( 'Upto 12 hours', 'wp-auto-republish' ),
            '86400' => __( 'Upto 24 hours', 'wp-auto-republish' ),
        ];
        $items = $this->do_filter( 'republish_interval', $items );
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_random_republish_interval]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_random_republish_interval' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select randomness interval from here which will be added to minimum republish interval.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function republish_post_age( $args )
    {
        $items = [
            '30'   => __( '30 Days (1 month)', 'wp-auto-republish' ),
            '45'   => __( '45 Days (1.5 months)', 'wp-auto-republish' ),
            '60'   => __( '60 Days (2 months)', 'wp-auto-republish' ),
            '90'   => __( '90 Days (3 months)', 'wp-auto-republish' ),
            '120'  => __( '120 Days (4 months)', 'wp-auto-republish' ),
            '180'  => __( '180 Days (6 months)', 'wp-auto-republish' ),
            '240'  => __( '240 Days (8 months)', 'wp-auto-republish' ),
            '365'  => __( '365 Days (1 year)', 'wp-auto-republish' ),
            '730'  => __( '730 Days (2 years)', 'wp-auto-republish' ),
            '1095' => __( '1095 Days (3 years)', 'wp-auto-republish' ),
        ];
        $items = $this->do_filter( 'republish_eligibility_age', $items );
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_republish_post_age]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_republish_post_age' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select the post age before eligible for republishing.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function republish_method( $args )
    {
        $items = [
            'old_first' => __( 'Select Old Post First (ASC)', 'wp-auto-republish' ),
            'new_first' => __( 'Select New Post First (DESC)', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_republish_method]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_republish_method' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select the method of getting old posts from database.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function republish_post_position( $args )
    {
        $items = [
            '1' => __( '1st Position', 'wp-auto-republish' ),
            '2' => __( '2nd Position', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_republish_post_position]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_republish_post_position' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in 1st place).', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function republish_info( $args )
    {
        $items = [
            'disable'        => __( 'Disable', 'wp-auto-republish' ),
            'before_content' => __( 'Before Content', 'wp-auto-republish' ),
            'after_content'  => __( 'After Content', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_republish_position]" style="width:40%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_republish_position' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select how you want to show original published date of the post on frontend.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function republish_info_text( $args )
    {
        ?>  <input id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_republish_position_text]" type="text" size="35" style="width:40%;" required value="<?php 
        echo  htmlspecialchars( wp_kses_post( $this->get_data( 'wpar_republish_position_text' ) ) ) ;
        ?>" />
			&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Message before original published date of the post on frontend.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function post_types_list( $args )
    {
        $data = ( !is_array( $this->get_data( 'wpar_post_types' ) ) ? [] : $this->get_data( 'wpar_post_types' ) );
        $post_types = get_post_types( [
            'public' => true,
        ], 'names' );
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_post_types][]" multiple="multiple" required style="width:90%;">' ;
        foreach ( $post_types as $post_type ) {
            $selected = ( in_array( $post_type, $data ) ? ' selected="selected"' : '' );
            echo  '<option value="' . $post_type . '"' . $selected . '>' . $post_type . '</option>' ;
        }
        echo  '</select>' ;
        ?>
		&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select post types where you want to show facebook comment box.', 'ultimate-facebook-comments' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
		<?php 
    }
    
    public function exclude_by_type( $args )
    {
        $items = [
            'none'    => __( 'Ignoring all Taxonomies', 'wp-auto-republish' ),
            'exclude' => __( 'Excluding Taxonomies (Categories/Tags)', 'wp-auto-republish' ),
            'include' => __( 'Including Taxonomies (Categories/Tags)', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_exclude_by_type]" style="width:55%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( $this->get_data( 'wpar_exclude_by_type' ) == $item ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select how you want to include or exclude a post category from republishing. If you choose excluding, selected categories/post tags will be ignored and vice-versa.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function post_taxonomy( $args )
    {
        $data = ( !is_array( $this->get_data( 'wpar_post_taxonomy' ) ) ? [] : $this->get_data( 'wpar_post_taxonomy' ) );
        $categories = get_terms( [
            'taxonomy' => 'category',
            'orderby'  => 'count',
        ] );
        $tags = get_terms( [
            'taxonomy' => 'post_tag',
            'orderby'  => 'count',
        ] );
        $items = array_merge( $categories, $tags );
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_post_taxonomy][]" multiple="multiple" style="width:90%;">' ;
        foreach ( $items as $item ) {
            $selected = ( in_array( $item->taxonomy . ':' . $item->term_id, $data ) ? ' selected="selected"' : '' );
            echo  '<option value="' . $item->taxonomy . ':' . $item->term_id . '"' . $selected . '>' . ucwords( str_replace( '_', ' ', $item->taxonomy ) ) . ': ' . $item->name . '</option>' ;
        }
        echo  '</select>' ;
        ?>
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select posts categories/tags which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function override_category_tag( $args )
    {
        $wpar_omit_override = preg_replace( [
            '/[^\\d,]/',
            '/(?<=,),+/',
            '/^,+/',
            '/,+$/'
        ], '', $this->get_data( 'wpar_override_category_tag' ) );
        ?> <input id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_override_category_tag]" type="text" size="90" style="width:90%;" value="<?php 
        echo  $wpar_omit_override ;
        ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Write the post IDs which you want to select forcefully (when you select excluding) or want to not select forcefully (when you select including).', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function republish_days( $args )
    {
        $data = ( !is_array( $this->get_data( 'wpar_days' ) ) ? [] : $this->get_data( 'wpar_days' ) );
        $items = [
            'sun' => __( 'Sunday', 'wp-auto-republish' ),
            'mon' => __( 'Monday', 'wp-auto-republish' ),
            'tue' => __( 'Tuesday', 'wp-auto-republish' ),
            'wed' => __( 'Wednesday', 'wp-auto-republish' ),
            'thu' => __( 'Thursday', 'wp-auto-republish' ),
            'fri' => __( 'Friday', 'wp-auto-republish' ),
            'sat' => __( 'Saturday', 'wp-auto-republish' ),
        ];
        echo  '<select id="' . $args['label_for'] . '" name="wpar_plugin_settings[wpar_days][]" multiple="multiple" required style="width:90%;">' ;
        foreach ( $items as $item => $label ) {
            $selected = ( in_array( $item, $data ) ? ' selected="selected"' : '' );
            echo  '<option value="' . $item . '"' . $selected . '>' . $label . '</option>' ;
        }
        echo  '</select>' ;
        ?>
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Select the weekdays when you want to republish old posts.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function republish_time_start( $args )
    {
        $wpar_starttime = ( !empty($this->get_data( 'wpar_start_time' )) ? $this->get_data( 'wpar_start_time' ) : '05:00' );
        ?>
        <input id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_start_time]" type="text" class="wpar-timepicker" size="40" style="width:40%;" placeholder="05:00" required readonly="readonly" value="<?php 
        echo  $wpar_starttime ;
        ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Set the time period for republish old posts from here.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function republish_time_end( $args )
    {
        $wpar_endtime = ( !empty($this->get_data( 'wpar_end_time' )) ? $this->get_data( 'wpar_end_time' ) : '23:00' );
        ?>
        <input id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_end_time]" type="text" class="wpar-timepicker" size="40" style="width:40%;" placeholder="05:00" required readonly="readonly" value="<?php 
        echo  $wpar_endtime ;
        ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Set the time period for republish old posts from here.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }
    
    public function remove_plugin_data( $args )
    {
        ?>  <label class="switch">
            <input type="checkbox" id="<?php 
        echo  $args['label_for'] ;
        ?>" name="wpar_plugin_settings[wpar_remove_plugin_data]" value="1" <?php 
        checked( $this->get_data( 'wpar_remove_plugin_data' ), 1 );
        ?> /> 
            <span class="slider round"></span></label>&nbsp;&nbsp;<span class="tooltip" title="<?php 
        _e( 'Enable this if you want to remove all the plugin data from your website.', 'wp-auto-republish' );
        ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php 
    }

}