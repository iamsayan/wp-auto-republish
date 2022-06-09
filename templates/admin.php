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
?> <?php 
echo  esc_html( $this->version ) ;
?></span></h2>
    <a href="#general" class="wpar-tab is-active" id="wpar-tab-general"><?php 
esc_html_e( 'General', 'wp-auto-republish' );
?></a>
    <a href="#post" class="wpar-tab" id="wpar-tab-post"><?php 
esc_html_e( 'Posts', 'wp-auto-republish' );
?></a>
        <a href="#misc" class="wpar-tab" id="wpar-tab-misc"><?php 
esc_html_e( 'Misc.', 'wp-auto-republish' );
?></a>
    <a href="#tools" class="wpar-tab" id="wpar-tab-tools"><?php 
esc_html_e( 'Tools', 'wp-auto-republish' );
?></a>
    <a href="https://wprevivepress.com/docs/?utm_source=dashboard&utm_medium=plugin" target="_blank" class="wpar-tab type-link" id="wpar-tab-help"><?php 
esc_html_e( 'Help', 'wp-auto-republish' );
?></a>
            <a href="<?php 
echo  esc_url( revivepress_fs()->get_upgrade_url() ) ;
?>" target="_blank" class="wpar-tab type-link btn-upgrade wpar-upgrade" id="wpar-tab-upgrade">
            <span class="dashicons dashicons-admin-plugins"></span>
            <p><?php 
esc_html_e( 'Upgrade to Premium', 'wp-auto-republish' );
?></p>
        </a>
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
            <div id="post-body-content" class="wpar-metabox">
                                    <div class="wpar-upgrade-notice" id="wpar-upgrade-notice">
                        <p>
                            Republish & Share your Evergreen Content with more controls. Get <strong>RevivePress Premium</strong>!
                            <a class="wpar-upgrade" href="<?php 
echo  esc_url( revivepress_fs()->get_upgrade_url() ) ;
?>" target="_blank">Click here to see all the exciting features.</a>
                        </p>
                    </div>
                                <form id="wpar-settings-form" method="post" action="options.php">
                    <?php 
settings_fields( 'wpar_plugin_settings_fields' );
$this->doSettingsSection( [
    'id'          => 'wpar-configure',
    'class'       => 'wpar-general',
    'title'       => __( 'General Settings', 'wp-auto-republish' ),
    'description' => sprintf( __( 'Configure the Global Republish settings from here. Last run: %s', 'wp-auto-republish' ), date_i18n( $format, $last ) ),
    'name'        => 'wpar_plugin_default_option',
] );
$this->doSettingsSection( [
    'id'          => 'wpar-display',
    'class'       => 'wpar-general',
    'title'       => __( 'Display Settings', 'wp-auto-republish' ),
    'description' => __( 'You can control frontend republish info visiblity from here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_republish_info_option',
] );
$this->doSettingsSection( [
    'id'          => 'wpar-query',
    'class'       => 'wpar-post d-none',
    'title'       => __( 'Old Posts Settings', 'wp-auto-republish' ),
    'description' => __( 'Control the WP_Query of Post Republish Process for Global Republish.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_post_query_option',
] );
$this->doSettingsSection( [
    'id'          => 'wpar-post-types',
    'class'       => 'wpar-post d-none',
    'title'       => __( 'Post Types Settings', 'wp-auto-republish' ),
    'description' => __( 'Control Post Types, Taxonomies and Author Based Republish from here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_post_type_option',
] );
$this->doSettingsSection( [
    'id'          => 'wpar-misc',
    'class'       => 'wpar-misc',
    'title'       => __( 'Misc. Options', 'wp-auto-republish' ),
    'description' => __( 'Change some uncommon but essential settings here.', 'wp-auto-republish' ),
    'name'        => 'wpar_plugin_tools_option',
] );
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

$data = [
    [
		'heading' => __( 'Reset Settings', 'wp-auto-republish' ),
		'hint'    => __( 'Resetting will delete all custom options to the default settings of the plugin in your database.', 'wp-auto-republish' ),
		'notice'  => __( 'It will delete all the data relating to this plugin settings. You have to re-configure this plugin again. Do you want to still continue?', 'wp-auto-republish' ),
		'success' => __( 'Success! Plugin Settings reset successfully.', 'wp-auto-republish' ),
		'action'  => 'process_delete_plugin_data',
		'reload'  => true,
	],
    [
		'heading' => __( 'Remove Post Meta & Actions', 'wp-auto-republish' ),
		'hint'    => __( 'Resetting will delete all post metadatas and future action events associated with Post Republish.', 'wp-auto-republish' ),
		'notice'  => __( 'It will delete all the post meta data & action events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' ),
		'success' => __( 'Success! All post meta datas and republish events deleted successfully!', 'wp-auto-republish' ),
		'action'  => 'process_delete_post_metas',
		'button'  => __( 'Clear Post Metas & Events', 'wp-auto-republish' ),
	],
    [
		'heading' => __( 'De-Schedule Posts', 'wp-auto-republish' ),
		'hint'    => __( 'It will change the republish date to the original post published date on all posts.', 'wp-auto-republish' ),
		'notice'  => __( 'It will change the republish date to the original post published date on all posts. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' ),
		'success' => __( 'Success! All posts de-scheduled successfully!', 'wp-auto-republish' ),
		'action'  => 'process_deschedule_posts',
	],
    [
		'heading' => __( 'Re-Create Missing Database Tables', 'wp-auto-republish' ),
		'hint'    => __( 'Check if required tables exist and create them if not.', 'wp-auto-republish' ),
		'success' => __( 'Success! Table creation proceeded successfully!', 'wp-auto-republish' ),
		'action'  => 'process_fix_database_tables',
		'button'  => __( 'Re-Create Tables', 'wp-auto-republish' ),
		'type'    => 'blue',
	],
    [
		'heading' => __( 'Re-Generate Republish Interval', 'wp-auto-republish' ),
		'hint'    => __( 'It will regenerate Schedule Auto Republish Process Interval.', 'wp-auto-republish' ),
		'success' => __( 'Success! Schedule regenerated successfully!', 'wp-auto-republish' ),
		'action'  => 'process_regenerate_schedule',
		'button'  => __( 'Re-Generate Interval', 'wp-auto-republish' ),
		'type'    => 'blue',
	],
];
foreach ( $data as $args ) {
    $box = wp_parse_args( $args, [
        'reload' => false,
        'type'   => 'red',
        'button' => $args['heading'],
        'notice' => '',
    ] );
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
                                    <input type="button" id="<?php 
    echo  esc_attr( str_replace( 'process_', 'rvp_', $box['action'] ) ) ;
    ?>" class="button button-large button-secondary default wpar-reset" data-type="<?php 
    echo  esc_attr( $box['type'] ) ;
    ?>" data-action="wpar_<?php 
    echo  esc_attr( $box['action'] ) ;
    ?>" data-reload="<?php 
    echo  ( $box['reload'] ? 'yes' : 'no' ) ;
    ?>" data-notice="<?php 
    echo  esc_attr( $box['notice'] ) ;
    ?>" data-success="<?php 
    echo  esc_attr( $box['success'] ) ;
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
esc_html_e( 'WPML & Translation Compatibility', 'wp-auto-republish' );
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