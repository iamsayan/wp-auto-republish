<?php

/**
 * The Main dashboard file.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Templates
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
?>

<div id="wpar-nav-container" class="wpar-admin-toolbar">
	<h2>RevivePress<span class="title-count<?php 
echo  esc_attr( $class_name ) ;
?>"><?php 
echo  esc_html( $this->tag ) ;
echo  esc_html( $this->version ) ;
?></span></h2>
    <a href="#general" class="wpar-tab is-active" id="wpar-tab-general"><?php 
esc_html_e( 'General', 'wp-auto-republish' );
?></a>
    <?php 
//if ( revivepress_fs()->can_use_premium_code__premium_only() ) {
?>
        <a href="#single" class="wpar-tab" id="wpar-tab-single"><?php 
esc_html_e( 'Single', 'wp-auto-republish' );
?></a>
        <a href="#social" class="wpar-tab" id="wpar-tab-social"><?php 
esc_html_e( 'Social', 'wp-auto-republish' );
?></a>
        <a href="#email" class="wpar-tab" id="wpar-tab-email"><?php 
esc_html_e( 'Notification', 'wp-auto-republish' );
?></a>
        <a href="#advanced" class="wpar-tab" id="wpar-tab-advanced"><?php 
esc_html_e( 'Advanced', 'wp-auto-republish' );
?></a>
    <?php 
//}
?>
    <a href="#tools" class="wpar-tab" id="wpar-tab-tools"><?php 
esc_html_e( 'Tools', 'wp-auto-republish' );
?></a>
    <a href="https://wprevivepress.com/docs/?utm_source=dashboard&utm_medium=plugin" target="_blank" class="wpar-tab type-link" id="wpar-tab-help"><?php 
esc_html_e( 'Help', 'wp-auto-republish' );
?></a>
    <?php 
?>
        <a href="<?php 
echo  esc_url( revivepress_fs()->get_upgrade_url() ) ;
?>" target="_blank" class="wpar-tab type-link btn-upgrade wpar-upgrade" id="wpar-tab-upgrade">
            <span class="dashicons dashicons-admin-plugins"></span>
            <p><?php 
esc_html_e( 'Upgrade to Premium', 'wp-auto-republish' );
?></p>
        </a>
    <?php 
?>
    <div class="top-sharebar">
        <a class="share-btn rate-btn no-popup" href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" title="<?php 
esc_html_e( 'Please rate 5 stars if you like RevivePress', 'wp-auto-republish' );
?>"><span class="dashicons dashicons-star-filled"></span> <?php 
esc_html_e( 'Rate 5 stars', 'wp-auto-republish' );
?></a>
        <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=Check%20out%20RevivePress,%20a%20%23WordPress%20%23Plugin%20that%20revive%20your%20old%20evergreen%20content%20by%20republishing%20them%20and%20sharing%20them%20on%20Social%20Media%20https%3A//wordpress.org/plugins/wp-auto-republish/%20via%20%40im_sayaan%20" target="_blank"><span class="dashicons dashicons-twitter"></span> <?php 
esc_html_e( 'Tweet', 'wp-auto-republish' );
?></a>
        <a class="share-btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=https://wordpress.org/plugins/wp-auto-republish/&quote=Check%20out%20RevivePress,%20a%20%23WordPress%20%23Plugin%20that%20revive%20your%20old%20evergreen%20content%20by%20republishing%20them%20and%20sharing%20them%20on%20Social%20Media%20https%3A//wordpress.org/plugins/wp-auto-republish/" target="_blank"><span class="dashicons dashicons-facebook"></span> <?php 
esc_html_e( 'Share', 'wp-auto-republish' );
?></a>
    </div>
</div>
<div class="wrap wpar-wrap" data-reload="no">
    <h2 style="display: none;"></h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div class="rvp-loader"><div></div><div></div><div></div><div></div></div>
            <div id="post-body-content" class="wpar-metabox">
                <form id="wpar-settings-form" method="post" action="options.php">
                    <?php 
settings_fields( 'wpar_plugin_settings_fields' );
$this->subMenu( array(
    'configure' => '<i class="fas fa-cog"></i>' . esc_html__( 'Configure', 'wp-auto-republish' ),
    'query'     => '<i class="fas fa-clipboard-check"></i>' . esc_html__( 'Conditions', 'wp-auto-republish' ),
    'filter'    => '<i class="fas fa-filter"></i>' . esc_html__( 'Filter Options', 'wp-auto-republish' ),
    'display'   => '<i class="fas fa-eye"></i>' . esc_html__( 'Visibility', 'wp-auto-republish' ),
), 'general' );
// $text = encrypt_my( 'sayan', 5 );
// $text .= decrypt_my( 'sayan', 5 );
$this->doSettingsSection( array(
    'id'          => 'wpar-configure',
    'class'       => 'wpar-general d-none',
    'title'       => __( 'General Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    '%1$s <span class="wpar-last-run-timestamp">%2$s</span>',
    /* translators: Last Global Republish run time. */
    __( 'Configure the Global Republish settings from here.', 'wp-auto-republish' ),
    /* translators: Last Global Republish run time. */
    sprintf( __( 'Last run: %s', 'wp-auto-republish' ), date_i18n( $format, $last ) )
),
    'name'        => 'wpar_plugin_general_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-query',
    'class'       => 'wpar-general d-none',
    'title'       => __( 'Republish Conditions', 'wp-auto-republish' ),
    'description' => __( 'Control the WP_Query of Post Republish Process for Global Republish.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_post_query_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-filter',
    'class'       => 'wpar-general d-none',
    'title'       => __( 'Filter Options', 'wp-auto-republish' ),
    'description' => __( 'Control Post Types, Taxonomies and Author Based Filtering here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_post_type_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-display',
    'class'       => 'wpar-general d-none',
    'title'       => __( 'Frontend Visibility', 'wp-auto-republish' ),
    'description' => __( 'You can control frontend republish info visiblity here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_republish_info_option',
) );
//if ( revivepress_fs()->can_use_premium_code__premium_only() ) {
$this->subMenu( array(
    'metabox'    => '<i class="fas fa-clipboard"></i>' . esc_html__( 'Post Metabox', 'wp-auto-republish' ),
    'individual' => '<i class="fas fa-history"></i>' . esc_html__( 'Individual Republish', 'wp-auto-republish' ),
    'actions'    => '<i class="far fa-clock"></i>' . esc_html__( 'Republish Actions', 'wp-auto-republish' ),
), 'single' );
$this->doSettingsSection( array(
    'id'          => 'wpar-metabox',
    'class'       => 'wpar-single d-none',
    'title'       => __( 'Single Posts Settings', 'wp-auto-republish' ),
    'description' => __( 'Configure the Per Post based Metabox settings here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_metabox_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-individual',
    'class'       => 'wpar-single d-none',
    'title'       => __( 'Individual Republish', 'wp-auto-republish' ),
    'description' => __( 'Configure the Per Post based Metabox settings here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_individual_post_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-actions',
    'class'       => 'wpar-single d-none',
    'title'       => __( 'Republish Actions', 'wp-auto-republish' ),
    'description' => __( 'Configure the Per Post based Instant Republish settings here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_actions_republish_option',
) );
$this->subMenu( array(
    'social-general' => '<i class="fas fa-cog"></i>' . esc_html__( 'General', 'wp-auto-republish' ),
    'facebook'       => '<i class="fab fa-facebook"></i>' . esc_html__( 'Facebook', 'wp-auto-republish' ),
    'twitter'        => '<i class="fab fa-square-x-twitter"></i>' . esc_html__( 'X (Twitter)', 'wp-auto-republish' ),
    'linkedin'       => '<i class="fab fa-linkedin-in"></i>' . esc_html__( 'Linkedin', 'wp-auto-republish' ),
    'pinterest'      => '<i class="fab fa-pinterest"></i>' . esc_html__( 'Pinterest', 'wp-auto-republish' ),
    'tumblr'         => '<i class="fab fa-tumblr"></i>' . esc_html__( 'Tumblr', 'wp-auto-republish' ),
), 'social' );
$this->doSettingsSection( array(
    'id'          => 'wpar-social-general',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'General Settings', 'wp-auto-republish' ),
    'description' => __( 'General Settings for Social Sharing.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_social_general_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-facebook',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'Facebook Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    /* translators: Documentation Link. */
    __( 'Setup Facebook Page and Group Sharing from here. %s', 'wp-auto-republish' ),
    '<a href="https://wprevivepress.com/docs-topics/social/" target="_blank">' . __( 'Learn More', 'wp-auto-republish' ) . '</a>'
),
    'name'        => 'wpar_plugin_facebook_option',
    'type'        => 'facebook',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-twitter',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'X (Twitter) Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    /* translators: Documentation Link. */
    __( 'Setup X (Twitter) Profile Sharing from here. %s', 'wp-auto-republish' ),
    '<a href="https://wprevivepress.com/docs-topics/social/" target="_blank">' . __( 'Learn More', 'wp-auto-republish' ) . '</a>'
),
    'name'        => 'wpar_plugin_twitter_option',
    'type'        => 'twitter',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-linkedin',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'Linkedin Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    /* translators: Documentation Link. */
    __( 'Setup Linkedin Profile Sharing from here. %s', 'wp-auto-republish' ),
    '<a href="https://wprevivepress.com/docs-topics/social/" target="_blank">' . __( 'Learn More', 'wp-auto-republish' ) . '</a>'
),
    'name'        => 'wpar_plugin_linkedin_option',
    'type'        => 'linkedin',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-pinterest',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'Pinterest Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    /* translators: Documentation Link. */
    __( 'Setup Pinterest Profile Sharing from here. %s', 'wp-auto-republish' ),
    '<a href="https://wprevivepress.com/docs-topics/social/" target="_blank">' . __( 'Learn More', 'wp-auto-republish' ) . '</a>'
),
    'name'        => 'wpar_plugin_pinterest_option',
    'type'        => 'pinterest',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-tumblr',
    'class'       => 'wpar-social d-none',
    'title'       => __( 'Tumblr Settings', 'wp-auto-republish' ),
    'description' => sprintf(
    /* translators: Documentation Link. */
    __( 'Setup Tumblr Profile Sharing from here. %s', 'wp-auto-republish' ),
    '<a href="https://wprevivepress.com/docs-topics/social/" target="_blank">' . __( 'Learn More', 'wp-auto-republish' ) . '</a>'
),
    'name'        => 'wpar_plugin_tumblr_option',
    'type'        => 'tumblr',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-email',
    'class'       => 'wpar-email',
    'title'       => __( 'Email Settings', 'wp-auto-republish' ),
    'description' => __( 'Setup Email Notification settings from here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_email_notify_option',
) );
$this->doSettingsSection( array(
    'id'          => 'wpar-advanced',
    'class'       => 'wpar-advanced',
    'title'       => __( 'Advanced Options', 'wp-auto-republish' ),
    'description' => __( 'Change some uncommon but essential settings here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_advanced_option',
) );
//}
?>
                </form>
                <div id="wpar-tools" class="postbox wpar-tools d-none">
                    <?php 
$this->sectionHeader( 'Plugin Tools', __( 'Perform database related actions from here.', 'wp-auto-republish' ) );
?>
				    <div class="inside wpar-inside" style="padding: 10px 20px;">
                        <?php 

if ( current_user_can( 'manage_options' ) ) {
    ?>
                            <div class="wpar-tools-box">
                                <span><?php 
    esc_html_e( 'Export Settings', 'wp-auto-republish' );
    ?></span>
                                <p><?php 
    esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'wp-auto-republish' );
    ?></p>
                                <form method="post">
                                    <p><input type="hidden" name="rvp_export_action" value="rvp_export_settings" /></p>
                                    <p>
                                        <?php 
    wp_nonce_field( 'rvp_export_nonce', 'rvp_export_nonce' );
    ?>
                                        <?php 
    submit_button(
        __( 'Export Settings', 'wp-auto-republish' ),
        'button-large button-secondary default',
        'wpar-export',
        false
    );
    ?>
                                        <input type="button" class="button button-large button-secondary default wpar-copy" value="<?php 
    esc_attr_e( 'Copy', 'wp-auto-republish' );
    ?>" style="margin-left: -1px;">
                                        <span class="wpar-copied" style="padding-left: 6px;display: none;color: #068611;"><?php 
    esc_html_e( 'Copied!', 'wp-auto-republish' );
    ?></span>
                                    </p>
                                </form>
                            </div>
                            <div class="wpar-tools-box">
                                <span><?php 
    esc_html_e( 'Import Settings', 'wp-auto-republish' );
    ?></span>
                                <p><?php 
    esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp-auto-republish' );
    ?></p>
                                <form method="post" enctype="multipart/form-data">
                                    <p><input type="file" name="import_file" accept=".json"/></p>
                                    <p>
                                        <input type="hidden" name="rvp_import_action" value="rvp_import_settings" />
                                        <?php 
    wp_nonce_field( 'rvp_import_nonce', 'rvp_import_nonce' );
    ?>
                                        <?php 
    submit_button(
        __( 'Import Settings', 'wp-auto-republish' ),
        'button-large button-secondary default',
        'wpar-import',
        false
    );
    ?>
                                        <input type="button" class="button button-large button-secondary default wpar-paste" value="<?php 
    esc_attr_e( 'Paste', 'wp-auto-republish' );
    ?>">
                                    </p>
                                </form>
                            </div>
                        <?php 
}

$data = array(
    array(
		'heading' => __( 'Reset Settings', 'wp-auto-republish' ),
		'hint'    => __( 'Resetting will delete all custom options to the default settings of the plugin in your database.', 'wp-auto-republish' ),
		'notice'  => __( 'It will delete all the data relating to this plugin settings. You have to re-configure this plugin again. Do you want to still continue?', 'wp-auto-republish' ),
		'action'  => 'remove_data',
		'reload'  => true,
	),
    array(
		'heading' => __( 'De-Schedule Posts', 'wp-auto-republish' ),
		'hint'    => __( 'It will change the republish date to the original post published date on all posts.', 'wp-auto-republish' ),
		'notice'  => __( 'It will change the republish date to the original post published date on all posts. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' ),
		'action'  => 'deschedule_posts',
	),
    array(
		'heading' => __( 'Re-Create Missing Database Tables', 'wp-auto-republish' ),
		'hint'    => __( 'Check if required tables exist and create them if not.', 'wp-auto-republish' ),
		'action'  => 'recreate_tables',
		'button'  => __( 'Re-Create Tables', 'wp-auto-republish' ),
		'type'    => 'blue',
	),
    array(
		'heading' => __( 'Re-Generate Republish Interval', 'wp-auto-republish' ),
		'hint'    => __( 'It will regenerate Schedule Auto Republish Process Interval.', 'wp-auto-republish' ),
		'action'  => 'regenerate_interval',
		'button'  => __( 'Re-Generate Interval', 'wp-auto-republish' ),
		'type'    => 'blue',
		'show'    => ! empty($options['wpar_enable_plugin']),
	),
    array(
		'heading' => __( 'Re-Generate Republish Schedule', 'wp-auto-republish' ),
		'hint'    => __( 'It will regenerate Schedule Auto Republish Schedules of Single Posts and Custom Rules.', 'wp-auto-republish' ),
		'notice'  => __( 'It will remove and re-create all the scheduled or missed republish events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' ),
		'action'  => 'regenerate_schedule',
		'button'  => __( 'Re-Generate Schedule', 'wp-auto-republish' ),
		'show'    => revivepress_fs()->can_use_premium_code__premium_only(),
	),
    array(
		'heading' => __( 'Remove Post Meta & Actions', 'wp-auto-republish' ),
		'hint'    => __( 'Resetting will delete all post metadatas and future action events associated with Post Republish.', 'wp-auto-republish' ),
		'notice'  => __( 'It will delete all the post meta data & action events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' ),
		'action'  => 'remove_meta',
		'button'  => __( 'Clear Post Metas & Events', 'wp-auto-republish' ),
	),
);
foreach ( $data as $args ) {
    $box = wp_parse_args( $args, array(
        'show'   => true,
        'reload' => false,
        'type'   => 'red',
        'button' => $args['heading'],
        'notice' => '',
    ) );
    if ( ! $box['show'] ) {
        continue;
    }
    ?>
                            <div class="wpar-tools-box">
                                <span><?php 
    echo  esc_html( $box['heading'] ) ;
    ?></span>
                                <p>
                                    <?php 
    echo  esc_html( $box['hint'] ) ;
    ?>
                                </p>
                                <p>
                                    <input type="button" class="button button-large button-secondary default wpar-reset" data-type="<?php 
    echo  esc_attr( $box['type'] ) ;
    ?>" data-action="<?php 
    echo  esc_attr( $box['action'] ) ;
    ?>" data-reload="<?php 
    echo  ( $box['reload'] ? 'yes' : 'no' ) ;
    ?>" data-notice="<?php 
    echo  esc_attr( $box['notice'] ) ;
    ?>" value="<?php 
    echo  esc_attr( $box['button'] ) ;
    ?>">
                                </p>
                            </div>
                            <?php 
}
?>
                    </div>
                </div>

                <div id="wpar-status" class="postbox wpar-tools d-none">
				    <div class="inside wpar-inside" style="padding: 15px 20px;">
                        <div class="wpar-tools-box">
                            <span><?php 
esc_html_e( 'System Status', 'wp-auto-republish' );
?></span>
                            <p><?php 
esc_html_e( 'In order to use this plugin, please ensure your server meets the following PHP configurations. Your hosting provider will help you modify server configurations, if required.', 'wp-auto-republish' );
?></p>
                            <?php 
$this->systemStatus();
?>
                        </div>
                    </div>
                </div>

                <div class="wpar-premium-popup" style="display: none !important;">
                    <div class="wpar-feature-title"><?php 
esc_html_e( 'Upgrade to the premium version and get the following features:', 'wp-auto-republish' );
?></div>
                    <div class="wpar-premium-features">
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Custom Post types & Taxonomies', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Individual & Scheduled Republishing', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Date Time Range Based Republishing', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Custom Post Republish Interval & Title', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Automatic Social Media Share', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Automatic Cache Plugin Purge Support', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Change Post Status after Republish', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'One Click Instant Republish & Clone', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Custom Post Republish Rulesets', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'WordPress Sticky Posts Support', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'OneSignal Notification Support', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'WPML & Polylang Compatibility', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Indexing API Plugin Compatibility', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Email Notification upon Republishing', 'wp-auto-republish' );
?></p>
                        <p><span class="dashicons dashicons-yes"></span><?php 
esc_html_e( 'Priority Email Support & many more..', 'wp-auto-republish' );
?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>