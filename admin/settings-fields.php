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
        '300'     => '5 Minutes',
        '600'     => '10 Minutes',
        '900'     => '15 Minutes',
        '1200'    => '20 Minutes',
        '1800'    => '30 Minutes',
        '3600'    => '1 hour',
        '7200'    => '2 hours',
        '14400'   => '4 hours',
        '21600'   => '6 hours',
        '28800'   => '8 hours',
        '43200'   => '12 hours',
        '86400'   => '24 hours (1 day)',
        '172800'  => '48 hours (2 days)',
        '259200'  => '72 hours (3 days)',
        '432000'  => '120 hours (5 days)',
        '604800'  => '168 hours (7 days)'
    );
    echo '<select id="wpar-minimum" name="wpar_plugin_settings[wpar_minimun_republish_interval]" style="width:35%;">';
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
        //'0'       => 'Disable',
        '3600'    => 'Upto 1 hour',
        '7200'    => 'Upto 2 hours',
        '14400'   => 'Upto 4 hours',
        '21600'   => 'Upto 6 hours',
        '28800'   => 'Upto 8 hours',
        '43200'   => 'Upto 12 hours',
        '86400'   => 'Upto 24 hours'
    );
    echo '<select id="wpar-random" name="wpar_plugin_settings[wpar_random_republish_interval]" style="width:35%;">';
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
        '30'   => '30 Days (1 month)',
        '45'   => '45 Days (1.5 months)',
        '60'   => '60 Days (2 months)',
        '90'   => '90 Days (3 months)',
        '120'  => '120 Days (4 months)',
        '180'  => '180 Days (6 months)',
        '240'  => '240 Days (8 months)',
        '365'  => '365 Days (1 year)',
        '730'  => '730 Days (2 years)',
        '1095' => '1095 Days (3 years)'
    );
    echo '<select id="wpar-age" name="wpar_plugin_settings[wpar_republish_post_age]" style="width:35%;">';
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
        'old_first'   => 'Select Old Post First (ASC)',
        'random'      => 'Random Selection',
    );
    echo '<select id="wpar-method" name="wpar_plugin_settings[wpar_republish_method]" style="width:35%;">';
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
        '1'   => '1st Position',
        '2'   => '2nd Position'
    );
    echo '<select id="wpar-promotion" name="wpar_plugin_settings[wpar_republish_post_position]" style="width:35%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_republish_post_position'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in place).', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_republish_position_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_republish_position']) ) {
        $wpar_settings['wpar_republish_position'] = 'disable';
    }
    $items = array(
        'disable'         => 'Disable',
        'before_content'  => 'Before Content',
        'after_content'   => 'After Content'
    );
    echo '<select id="wpar-position" name="wpar_plugin_settings[wpar_republish_position]" style="width:35%;">';
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
        $wpar_settings['wpar_republish_position_text'] = 'Originally posted on ';
    }
    ?>  <input id="wpar-text" name="wpar_plugin_settings[wpar_republish_position_text]" type="text" size="35" style="width:35%;" required value="<?php if (isset($wpar_settings['wpar_republish_position_text'])) { echo $wpar_settings['wpar_republish_position_text']; } ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Message before original published date of the post on frontend.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_by_type_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( !isset($wpar_settings['wpar_exclude_by_type']) ) {
        $wpar_settings['wpar_exclude_by_type'] = 'exclude';
    }
    $items = array(
        'none'     => 'None',
        'exclude'  => 'Excluding',
        'include'  => 'Including'
    );
    echo '<select id="wpar-exclude-type" name="wpar_plugin_settings[wpar_exclude_by_type]" style="width:20%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_exclude_by_type'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?><span class="wparexclude" style="display: none;">&nbsp;&nbsp;&nbsp;<label for="wpar-exclude" style="font-size:14px;"><strong><?php _e( 'Select Taxonomy: ', 'wp-auto-republish' ); ?></strong></label>&nbsp;&nbsp;<?php
    if( !isset($wpar_settings['wpar_exclude_by']) ) {
        $wpar_settings['wpar_exclude_by'] = 'category';
    }
    $items = array(
        'category'  => 'Categories',
        'post_tag'  => 'Post Tags'
    );
    echo '<select id="wpar-exclude" name="wpar_plugin_settings[wpar_exclude_by]" style="width:22%;">';
    foreach( $items as $item => $label ) {
        $selected = ( $wpar_settings['wpar_exclude_by'] == $item ) ? ' selected="selected"' : '';
        echo '<option value="' . $item . '"' . $selected . '>' . $label . '</option>';
    }
    echo '</select>';
    ?></span>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select how you want to include or exclude a post category from republishing. If you choose excluding, selected categories/post tags will be ignored and vice-versa.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_exclude_category_tag_display() {
    $wpar_settings = get_option('wpar_plugin_settings');
    
    if( $wpar_settings['wpar_exclude_by'] != 'none' ) {
        $type = $wpar_settings['wpar_exclude_by'];
    } else {
        $type = 'category';
    }
    
    if( !isset($wpar_settings['wpar_exclude_category_tag']) ) {
        $wpar_settings['wpar_exclude_category_tag'][] = '';
    }

    $categories = get_terms( array(
        'taxonomy'     => $type,
        'orderby'      => 'count',
        'hide_empty'   => false,
    ) );

    echo '<select id="wpar-cat-tag" name="wpar_plugin_settings[wpar_exclude_category_tag][]" multiple="multiple" required style="width:90%;">';
    foreach( $categories as $item ) {
        $selected = in_array( $item->term_id, $wpar_settings['wpar_exclude_category_tag'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $item->term_id . '"' . $selected . '>' . $item->name . '</option>';
    }
    echo '</select>';
    ?>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Select categories which you want to include to republishing or exclude from republishing.', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function wpar_override_category_tag_display() {
    $wpar_settings = get_option('wpar_plugin_settings');

    if( empty($wpar_settings['wpar_override_category_tag']) ) {
        $wpar_settings['wpar_override_category_tag'] = '';
    }
    ?>
    <textarea id="wpar-override-cat-tag" name="wpar_plugin_settings[wpar_override_category_tag]" rows="3" cols="90" placeholder="ex: 53,109,257" style="width:90%"><?php if (isset($wpar_settings['wpar_override_category_tag'])) { echo $wpar_settings['wpar_override_category_tag']; } ?></textarea>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Write the post IDs which you want to select forcefully (when you select excluding) or want to not select forcefully (when you select including).', 'wp-auto-republish' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

?>