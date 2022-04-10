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
    <?php 
?>
    <a href="#misc" class="wpar-tab" id="wpar-tab-misc"><?php 
esc_html_e( 'Misc.', 'wp-auto-republish' );
?></a>
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
            <div id="post-body-content" class="wpar-metabox">
                <?php 
?>
                    <div class="wpar-upgrade-notice" id="wpar-upgrade-notice">
                        <p>
                            Republish & Share your Evergreen Content with more controls. Get <strong>RevivePress Premium</strong>!
                            <a class="wpar-upgrade" href="<?php 
echo  esc_url( revivepress_fs()->get_upgrade_url() ) ;
?>" target="_blank">Click here to see all the exciting features.</a>
                        </p>
                    </div>
                <?php 
?>
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

                <?php 
?>

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
        'secondary',
        'wpar-export',
        false
    );
    ?>
                                        <input type="button" class="button wpar-copy" value="<?php 
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
        'secondary',
        'wpar-import',
        false
    );
    ?>
                                        <input type="button" class="button wpar-paste" value="<?php 
    esc_attr_e( 'Paste', 'wp-auto-republish' );
    ?>">
                                    </p>
                                </form>
                            </div>
                        <?php 
}

?>
                        <div class="wpar-tools-box">
                            <span><?php 
esc_html_e( 'Reset Settings', 'wp-auto-republish' );
?></span>
		    	        	<p style="color: #ff0000;"><strong><?php 
esc_html_e( 'WARNING:', 'wp-auto-republish' );
?> </strong><?php 
esc_html_e( 'Resetting will delete all custom options to the default settings of the plugin in your database.', 'wp-auto-republish' );
?></p>
		    	        	<p><input type="button" class="button button-primary wpar-reset" data-action="wpar_process_delete_plugin_data" data-reload="true" data-notice="<?php 
esc_attr_e( 'It will delete all the data relating to this plugin settings. You have to re-configure this plugin again. Do you want to still continue?', 'wp-auto-republish' );
?>" data-success="<?php 
esc_attr_e( 'Success! Plugin Settings reset successfully.', 'wp-auto-republish' );
?>" value="<?php 
esc_attr_e( 'Reset Settings', 'wp-auto-republish' );
?>"></p>
                        </div>
                        <div class="wpar-tools-box">
                            <span><?php 
esc_html_e( 'Remove Post Meta & Actions', 'wp-auto-republish' );
?></span>
		    	        	<p style="color: #ff0000;"><strong><?php 
esc_html_e( 'WARNING:', 'wp-auto-republish' );
?> </strong><?php 
esc_html_e( 'Resetting will delete all post metadatas and future action events associated with Post Republish.', 'wp-auto-republish' );
?></p>
		    	        	<p><input type="button" class="button button-primary wpar-reset" data-action="wpar_process_delete_post_metas" data-reload="false" data-notice="<?php 
esc_attr_e( 'It will delete all the post meta data & action events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' );
?>" data-success="<?php 
esc_attr_e( 'Success! All post meta datas and republish events deleted successfully!', 'wp-auto-republish' );
?>" value="<?php 
esc_attr_e( 'Clear Post Metas & Events', 'wp-auto-republish' );
?>"></p>
                        </div>
                        <div class="wpar-tools-box">
                            <span><?php 
esc_html_e( 'De-Schedule Posts', 'wp-auto-republish' );
?></span>
		    	        	<p style="color: #ff0000;"><strong><?php 
esc_html_e( 'WARNING:', 'wp-auto-republish' );
?> </strong><?php 
esc_html_e( 'It will change the republish date to the original post published date on all posts.', 'wp-auto-republish' );
?></p>
		    	        	<p><input type="button" class="button button-primary wpar-reset" data-action="wpar_process_deschedule_posts" data-reload="false" data-notice="<?php 
esc_attr_e( 'It will change the republish date to the original post published date on all posts. Leave if you are not sure what you are doing. Do you want to still continue?', 'wp-auto-republish' );
?>" data-success="<?php 
esc_attr_e( 'Success! All posts de-scheduled successfully!', 'wp-auto-republish' );
?>" value="<?php 
esc_attr_e( 'De-Schedule Posts', 'wp-auto-republish' );
?>"></p>
                        </div>
                    </div>
                </div>

                <div id="wpar-help" class="postbox wpar-help d-none">
                    <?php 
$this->sectionHeader( 'Plugin Help', sprintf( __( 'Do you need help any other help with this plugin?  Checkout %s for more.', 'wp-auto-republish' ), sprintf( '<a href="https://wprevivepress.com/docs/" target="_blank">%s</a>', __( 'Documentation', 'wp-auto-republish' ) ) ) );
?>
                    <div class="inside">
                        <?php 

if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON !== false ) {
    ?>
                            <div class="notice inline notice-error notice-alt">
                                <p class="cron-warning"><?php 
    esc_html_e( 'WP Cron is currently disabled. Use Server Level Cron or use external cron services to get it working. If you already had done this, ignore this warning.', 'wp-auto-republish' );
    ?></p>
                            </div>
                        <?php 
}

?>
                        <ol class="help-faq">
                            <li>
                                <?php 
printf( esc_html__( 'How this %s plugin works?', 'wp-auto-republish' ), esc_html( $this->name ) );
?>
                                <p>
                                    <?php 
esc_html_e( 'This plugin is mainly based on WordPress Cron system to republish your old evergreen posts. It will generate republish events when plugin is instructed to republish a post. It is designed in a way to easily work with any server enviroment. If still it not works, please contact your hosting provider to increase server resources.', 'wp-auto-republish' );
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'Republish is not working. How to resolve this?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
esc_html_e( 'Please follow the settings help present in the below of every settings and try to properly configure the plugin. If still not working, please contact support.', 'wp-auto-republish' );
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'WordPress Cron is disabled on my website. What can I do?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
printf(
    esc_html__( 'This plugin is heavily based on WP Cron. If it is disabled on your website which is required by %1$s plugin, please enable native WP Cron or follow this %2$s tutorial %3$s to enable server level PHP Cron instead with an interval of less than Republish Interval option.', 'wp-auto-republish' ),
    esc_html( $this->name ),
    '<a href="https://wprevivepress.com/docs/how-to-replace-wp-cron-with-a-real-cron-job/" target="_blank">',
    '</a>'
);
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'Doesn’t changing the timestamp affect permalinks that include dates using this plugin?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
printf( esc_html__( 'If your permalinks structure contains date, please use %1$s instead of %2$s respectively if you are using premium version. If you are using free version then please disable this plugin or upgrade to Premium version to avoid SEO issues.', 'wp-auto-republish' ), '<code>%rvp_year%</code>, <code>%rvp_monthnum%</code>, <code>%rvp_day%</code>, <code>%rvp_hour%</code>, <code>%rvp_minute%</code>, <code>%rvp_second%</code>', '<code>%year%</code>, <code>%monthnum%</code>, <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>' );
?>
                                </p>
                            </li>
                            <?php 
?>
                            <li>
                                <?php 
esc_html_e( 'I have some custom taxonomies associated with posts or pages or any custom post types but they are not showing on settings dropdown. OR, Somehow custom post types republishing are now stopped suddenly.', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
esc_html_e( 'Free version of this plugin has some limitation. You can republish a particular post of a custom post type upto 3 times. After that plugin doesn\'t republish those posts anymore. You have to use Premium version of this plugin to use it more than 3 times for custom post types. Also, Post and Page do not have such limitations in the free version. Taxonomies, other than Category and Post Tags, are available only on premium version.', 'wp-auto-republish' );
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'I am using GoDaddy managed hosting and plugin is not working properly. OR, My Hosting Company does not support Server Level Cron Jobs. What to do next?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
printf( esc_html__( 'As if your Hosting does not allow to use server level cron, you have to use WordPress Native Cron method, to get it properly woking. Just follow the FAQ no. 2. Otherwise you can use other external cron services like %1$s with 1 minute interval and use this URL: %2$s to solve this issue.', 'wp-auto-republish' ), '<a href="https://cron-job.org" target="_blank">https://cron-job.org</a>', '<code>' . esc_url( home_url( 'wp-cron.php?doing_wp_cron' ) ) . '</code>' );
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'I have just installed this plugin and followed all previous guides but still it is not working properly. What to do?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
printf( esc_html__( 'At first, properly configure plugin settings. You can know more details about every settings hovering the mouse over the question mark icon next to the settings option. After that, Please wait some time to allow plugin to run republish process with an interval configured by you from plugin settings. If still not working, go to Tools > Plugins Tools > Import Settings > Copy and then open Pastebin.com or GitHub Gist and create a paste or gist with the copied data and send me the link using Contact page or open a support on WordPress.org forums (only for free version users). Here are some common %1$s cron problems %2$s related to WordPress.', 'wp-auto-republish' ), '<a href="https://github.com/johnbillion/wp-crontrol/wiki/Cron-events-that-have-missed-their-schedule" target="_blank">', '</a>' );
?>
                                </p>
                            </li>
                            <li>
                                <?php 
esc_html_e( 'Plugin is showing a warning notice to disable the plugin after activation. What is the reason?', 'wp-auto-republish' );
?>
                                <p>
                                    <?php 
printf( esc_html__( 'Currently you are using original post published information in your post permalinks (Settings > Permalinks). But this plugin reassign a current date to republish a post. So, the permalink will be changed after republish. It may cause SEO issues. It will be safe not to use free version of this plugin in such situation. But in the Premium version you can use %1$s instead of %2$s to solve this issue.', 'wp-auto-republish' ), '<code>%rvp_year%</code>, <code>%rvp_monthnum%</code>, <code>%rvp_day%</code>, <code>%rvp_hour%</code>, <code>%rvp_minute%</code>, <code>%rvp_second%</code>', '<code>%year%</code>, <code>%monthnum%</code>, <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>' );
?>
                                </p>
                            </li>
                        </ol>
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