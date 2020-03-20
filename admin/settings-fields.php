<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Auto Republish
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

function wpar_enable_plugin_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    ?>  <label class="switch">
        <input type="checkbox" id="wpar-enable" name="wpar_plugin_settings[wpar_enable_plugin]" value="1" <?php checked(isset($wpar_settings['wpar_enable_plugin']), 1); ?> /> 
        <span class="slider round"></span></label>&nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Enable this if you want to auto republish old posts of your blog.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
   <?php
}

function wpar_minimun_republish_interval_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_minimun_republish_interval']) ) {
        $wpar_settings['wpar_minimun_republish_interval'] = '43200';
    }
    $items = array(
        '300'     => __( '5 Minutes', 'wp-auto-republish' ),
        '600'     => __( '10 Minutes', 'wp-auto-republish' ),
        '900'     => __( '15 Minutes', 'wp-auto-republish' ),
        '1200'    => __( '20 Minutes', 'wp-auto-republish' ),
        '1800'    => __( '30 Minutes', 'wp-auto-republish' ),
        '3600'    => __( '1 hour', 'wp-auto-republish' ),
        '7200'    => __( '2 hours', 'wp-auto-republish' ),
        '14400'   => __( '4 hours', 'wp-auto-republish' ),
        '21600'   => __( '6 hours', 'wp-auto-republish' ),
        '28800'   => __( '8 hours', 'wp-auto-republish' ),
        '43200'   => __( '12 hours', 'wp-auto-republish' ),
        '86400'   => __( '24 hours (1 day)', 'wp-auto-republish' ),
        '172800'  => __( '48 hours (2 days)', 'wp-auto-republish' ),
        '259200'  => __( '72 hours (3 days)', 'wp-auto-republish' ),
        '432000'  => __( '120 hours (5 days)', 'wp-auto-republish' ),
        '604800'  => __( '168 hours (7 days)', 'wp-auto-republish' )
    );
    echo '<select id="wpar-minimum" name="wpar_plugin_settings[wpar_minimun_republish_interval]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_minimun_republish_interval'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select minimum interval between post republishing.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_random_republish_interval_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_random_republish_interval']) ) {
        $wpar_settings['wpar_random_republish_interval'] = '14400';
    }
    $items = array(
        '3600'    => __( 'Upto 1 hour', 'wp-auto-republish' ),
        '7200'    => __( 'Upto 2 hours', 'wp-auto-republish' ),
        '14400'   => __( 'Upto 4 hours', 'wp-auto-republish' ),
        '21600'   => __( 'Upto 6 hours', 'wp-auto-republish' ),
        '28800'   => __( 'Upto 8 hours', 'wp-auto-republish' ),
        '43200'   => __( 'Upto 12 hours', 'wp-auto-republish' ),
        '86400'   => __( 'Upto 24 hours', 'wp-auto-republish' )
    );
    echo '<select id="wpar-random" name="wpar_plugin_settings[wpar_random_republish_interval]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_random_republish_interval'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select randomness interval from here which will be added to minimum republish interval.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_post_age_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_republish_post_age']) ) {
        $wpar_settings['wpar_republish_post_age'] = '120';
    }
    $items = array(
        '30'   => __( '30 Days (1 month)', 'wp-auto-republish' ),
        '45'   => __( '45 Days (1.5 months)', 'wp-auto-republish' ),
        '60'   => __( '60 Days (2 months)', 'wp-auto-republish' ),
        '90'   => __( '90 Days (3 months)', 'wp-auto-republish' ),
        '120'  => __( '120 Days (4 months)', 'wp-auto-republish' ),
        '180'  => __( '180 Days (6 months)', 'wp-auto-republish' ),
        '240'  => __( '240 Days (8 months)', 'wp-auto-republish' ),
        '365'  => __( '365 Days (1 year)', 'wp-auto-republish' ),
        '730'  => __( '730 Days (2 years)', 'wp-auto-republish' ),
        '1095' => __( '1095 Days (3 years)', 'wp-auto-republish' )
    );
    $items = apply_filters( 'wpar_republish_eligibility_age', $items );
    echo '<select id="wpar-age" name="wpar_plugin_settings[wpar_republish_post_age]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_republish_post_age'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select the post age before eligible for republishing.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_method_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_republish_method']) ) {
        $wpar_settings['wpar_republish_method'] = 'old_first';
    }
    $items = array(
        'old_first'   => __( 'Select Old Post First (ASC)', 'wp-auto-republish' ),
        'new_first'   => __( 'Select New Post First (DESC)', 'wp-auto-republish' ),
        'random'      => __( 'Random Selection', 'wp-auto-republish' )
    );
    echo '<select id="wpar-method" name="wpar_plugin_settings[wpar_republish_method]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_republish_method'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select the method of getting old posts from database.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_post_position_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_republish_post_position']) ) {
        $wpar_settings['wpar_republish_post_position'] = '1';
    }
    $items = array(
        '1'   => __( '1st Position', 'wp-auto-republish' ),
        '2'   => __( '2nd Position', 'wp-auto-republish' )
    );
    echo '<select id="wpar-promotion" name="wpar_plugin_settings[wpar_republish_post_position]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_republish_post_position'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in 1st place).', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_position_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_republish_position']) ) {
        $wpar_settings['wpar_republish_position'] = 'disable';
    }
    $items = array(
        'disable'         => __( 'Disable', 'wp-auto-republish' ),
        'before_content'  => __( 'Before Content', 'wp-auto-republish' ),
        'after_content'   => __( 'After Content', 'wp-auto-republish' )
    );
    echo '<select id="wpar-position" name="wpar_plugin_settings[wpar_republish_position]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_republish_position'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select how you want to show original published date of the post on frontend.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_position_text_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    if( empty($wpar_settings['wpar_republish_position_text']) ) {
        $wpar_settings['wpar_republish_position_text'] = __( 'Originally posted on ', 'wp-auto-republish' );
    }
    ?>  <input id="wpar-text" name="wpar_plugin_settings[wpar_republish_position_text]" type="text" size="35" style="width:40%;" required value="<?php if (isset($wpar_settings['wpar_republish_position_text'])) { echo $wpar_settings['wpar_republish_position_text']; } ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Message before original published date of the post on frontend.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_by_type_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_exclude_by_type']) ) {
        $wpar_settings['wpar_exclude_by_type'] = 'exclude';
    }
    $items = array(
        'none'     => __( 'None', 'wp-auto-republish' ),
        'exclude'  => __( 'Excluding Categories/Tags', 'wp-auto-republish' ),
        'include'  => __( 'Including Categories/Tags', 'wp-auto-republish' )
    );
    echo '<select id="wpar-exclude-type" name="wpar_plugin_settings[wpar_exclude_by_type]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_exclude_by_type'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select how you want to include or exclude a post category from republishing. If you choose excluding, selected categories/post tags will be ignored and vice-versa.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_by_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_exclude_by']) ) {
        $wpar_settings['wpar_exclude_by'] = 'category';
    }
    $items = array(
        'category'  => __( 'Categories', 'wp-auto-republish' ),
        'post_tag'  => __( 'Post Tags', 'wp-auto-republish' )
    );
    echo '<select id="wpar-taxonomy" name="wpar_plugin_settings[wpar_exclude_by]" style="width:40%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_exclude_by'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select how you want to include or exclude a post category from republishing. If you choose excluding, selected categories/post tags will be ignored and vice-versa.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_category_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_exclude_category']) ) {
        $wpar_settings['wpar_exclude_category'][] = '';
    }

    $categories = get_terms( array(
        'taxonomy'     => 'category',
        'orderby'      => 'count',
        //'hide_empty'   => false,
    ) );

    echo '<select id="wpar-cat" name="wpar_plugin_settings[wpar_exclude_category][]" multiple="multiple" style="width:90%;">';
    foreach( $categories as $item ) {
        $selected = in_array( $item->term_id, $wpar_settings['wpar_exclude_category'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $item->term_id . '"' . $selected . '>' . $item->name . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select categories which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_tag_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_exclude_tag']) ) {
        $wpar_settings['wpar_exclude_tag'][] = '';
    }

    $tags = get_terms( array(
        'taxonomy'     => 'post_tag',
        'orderby'      => 'count',
        //'hide_empty'   => false,
    ) );

    echo '<select id="wpar-tag" name="wpar_plugin_settings[wpar_exclude_tag][]" multiple="multiple" style="width:90%;">';
    foreach( $tags as $item ) {
        $selected = in_array( $item->term_id, $wpar_settings['wpar_exclude_tag'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $item->term_id . '"' . $selected . '>' . $item->name . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select tags which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_override_category_tag_display() {
    $wpar_settings = get_option('wpar_plugin_settings');

    if( empty($wpar_settings['wpar_override_category_tag']) ) {
        $wpar_settings['wpar_override_category_tag'] = '';
    }
    $wpar_omit_override = preg_replace( array( '/[^\d,]/', '/(?<=,),+/', '/^,+/', '/,+$/' ), '', $wpar_settings['wpar_override_category_tag'] ); ?>
    <textarea id="wpar-override-cat-tag" name="wpar_plugin_settings[wpar_override_category_tag]" rows="3" cols="90" placeholder="ex: 53,109,257" style="width:90%"><?php if (isset($wpar_omit_override)) { echo $wpar_omit_override; } ?></textarea>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Write the post IDs which you want to select forcefully (when you select excluding) or want to not select forcefully (when you select including).', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_post_types_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_post_types']) ) {
        $wpar_settings['wpar_post_types'][] = 'post';
    }

    $post_types = get_post_types(array(
        'public'   => true,
    ), 'names'); 

    echo '<select id="wpar-pt" name="wpar_plugin_settings[wpar_post_types][]" multiple="multiple" style="width:90%;">';
    foreach( $post_types as $item ) {
        $selected = in_array( $item, $wpar_settings['wpar_post_types'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $item . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select post types where you want to show facebook comment box.', 'ultimate-facebook-comments' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_days_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_days']) ) {
        $wpar_settings['wpar_days'][] = '';
    }

    $items = array(
        'sun'  => __( 'Sunday', 'wp-auto-republish' ),
        'mon'  => __( 'Monday', 'wp-auto-republish' ),
        'tue'  => __( 'Tuesday', 'wp-auto-republish' ),
        'wed'  => __( 'Wednesday', 'wp-auto-republish' ),
        'thu'  => __( 'Thursday', 'wp-auto-republish' ),
        'fri'  => __( 'Friday', 'wp-auto-republish' ),
        'sat'  => __( 'Saturday', 'wp-auto-republish' )
    );
    echo '<select id="wpar-days" name="wpar_plugin_settings[wpar_days][]" multiple="multiple" required style="width:90%;">';
    foreach( $items as $item => $label ) {
        $selected = in_array( $item, $wpar_settings['wpar_days'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select the weekdays when you want to republish old posts.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_time_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    if( empty($wpar_settings['wpar_start_time']) ) {
        $wpar_settings['wpar_start_time'] = '05:00:00';
    }
    if( empty($wpar_settings['wpar_end_time']) ) {
        $wpar_settings['wpar_end_time'] = '23:00:00';
    }
    $wpar_starttime = preg_replace( array( '/[^\d:]/', '/(?<=:):+/', '/^:+/', '/:+$/' ), '', $wpar_settings['wpar_start_time'] );
    $wpar_endtime = preg_replace( array( '/[^\d:]/', '/(?<=:):+/', '/^:+/', '/:+$/' ), '', $wpar_settings['wpar_end_time'] ); ?>
    <label for="wpar-start-time"><span style="font-weight: 600;font:size: 13px;"><?php _e( 'Start Time:', 'wp-auto-republish' ); ?></span>&nbsp;
        <input id="wpar-start-time" name="wpar_plugin_settings[wpar_start_time]" type="text" size="30" style="width:30%;" placeholder="05:00:00" minlength="8" maxlength="8" required value="<?php if (isset($wpar_starttime)) { echo $wpar_starttime; } ?>" />
    </label>&nbsp;&nbsp;&nbsp;
    <label for="wpar-end-time"><span style="font-weight: 600;font:size: 13px;"><?php _e( 'End Time:', 'wp-auto-republish' ); ?></span>&nbsp;
        <input id="wpar-end-time" name="wpar_plugin_settings[wpar_end_time]" type="text" size="30" style="width:30%;" placeholder="23:00:00" minlength="8" maxlength="8" required value="<?php if (isset($wpar_endtime)) { echo $wpar_endtime; } ?>" />
    </label>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Set the time period for republish old posts from here.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

?>