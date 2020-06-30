<?php

/**
 * The Main dashboard file.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Templates
 * @author     Sayan Datta <hello@sayandatta.in>
 */
?>

<div class="wrap">
    <div class="head-wrap">
        <h1 class="title"><?php 
echo  $this->name ;
?><span class="title-count"><?php 
echo  $this->version ;
?></span></h1>
        <div><?php 
_e( 'This plugin helps to revive old posts by resetting the publish date to the current date.', 'wp-auto-republish' );
?></div><hgfr>
        <div class="top-sharebar">
            <a class="share-btn rate-btn" href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" title="Please rate 5 stars if you like <?php 
echo  $this->name ;
?>"><span class="dashicons dashicons-star-filled"></span> Rate 5 stars</a>
            <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=Check%20out%20WP%20Auto%20Republish,%20a%20%23WordPress%20%23plugin%20that%20revive%20your%20old%20posts%20by%20resetting%20the%20published%20date%20to%20the%20current%20date%20https%3A//wordpress.org/plugins/wp-auto-republish/%20via%20%40im_sayaan" target="_blank"><span class="dashicons dashicons-twitter"></span> Tweet about WP Auto Republish</a>
        </div>
    </div>
    <div id="nav-container" class="nav-tab-wrapper" style="border-bottggom: none;">
        <a href="#general" class="nav-tab nav-tab-active" id="btn1"><span class="dashicons dashicons-admin-generic" style="padding-top: 2px;"></span> <?php 
_e( 'General', 'wp-auto-republish' );
?></a>
        <a href="#post" class="nav-tab" id="btn2"><span class="dashicons dashicons-admin-post" style="padding-top: 2px;"></span> <?php 
_e( 'Post Options', 'wp-auto-republish' );
?></a>
        <!--a href="#single" class="nav-tab" id="btn3"><span class="dashicons dashicons-share" style="padding-top: 2px;"></span> <?php 
//_e( 'Single Post', 'wp-auto-republish' );
?></a-->
        <?php 
?>
        <a href="#tools" class="nav-tab" id="btn5"><span class="dashicons dashicons-admin-tools" style="padding-top: 2px;"></span> <?php 
_e( 'Tools', 'wp-auto-republish' );
?></a>
        <!--a href="#help" class="nav-tab" id="btn6"><span class="dashicons dashicons-editor-help" style="padding-top: 2px;"></span> <?php 
//_e( 'Help', 'wp-auto-republish' );
?></a-->
        <?php 
?>
        <a href="<?php 
echo  wpar_load_fs_sdk()->get_upgrade_url() ;
?>" class="nav-tab" id="btn6" styfle="background-color: orange;color: red;"><span class="dashicons dashicons-arrow-up-alt" style="padding-top: 2px;"></span> <?php 
_e( 'Upgrade', 'wp-auto-republish' );
?></a>
        <?php 
?>
    </div>
    <script>
        var header = document.getElementById("nav-container");
        var btns = header.getElementsByClassName("nav-tab");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("nav-tab-active");
            current[0].className = current[0].className.replace(" nav-tab-active", "");
            this.className += " nav-tab-active";
            });
        }
    </script>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <form id="saveForm" method="post" action="options.php" stylce="padding-left: 8px;">
                <?php 
settings_fields( 'wpar_plugin_settings_fields' );
?>
                    <div id="wpar-general" class="postbox">
                        <h3 class="hndle" style="cursor:default;">
                            <span class="wpar-heading">
                                <?php 
_e( 'Configure Settings', 'wp-auto-republish' );
?>
                            </span>
                        </h3>
                        <div class="inside">
                            <?php 
do_settings_sections( 'wpar_plugin_default_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary wpar-save',
    '',
    false
);
?>
                        </div>
                    </div>
                    <div id="wpar-display" class="postbox">
                        <h3 class="hndle" style="cursor:default;">
                            <span class="wpar-heading">
                                <?php 
_e( 'Display Settings', 'wp-auto-republish' );
?>
                            </span>
                        </h3>
                        <div class="inside">
                            <?php 
do_settings_sections( 'wpar_plugin_republish_info_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary wpar-save',
    '',
    false
);
?>
                        </div>
                    </div>
                    <div id="wpar-query" class="postbox">
                        <h3 class="hndle" style="cursor:default;">
                            <span class="wpar-heading">
                                <?php 
_e( 'Old Posts Settings', 'wp-auto-republish' );
?>
                            </span>
                        </h3>
                        <div class="inside">
                            <?php 
do_settings_sections( 'wpar_plugin_post_query_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary wpar-save',
    '',
    false
);
?>
                        </div>
                    </div>
                    <div id="wpar-post-types" class="postbox">
                        <h3 class="hndle" style="cursor:default;">
                            <span class="wpar-heading">
                                <?php 
_e( 'Post Types Settings', 'wp-auto-republish' );
?>
                            </span>
                        </h3>
                        <div class="inside">
                            <?php 
do_settings_sections( 'wpar_plugin_post_type_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary wpar-save',
    '',
    false
);
?>
                        </div>
                    </div>
                    <?php 
?>
                    <div id="wpar-tools" class="postbox">
                        <h3 class="hndle" style="cursor:default;">
                            <span class="wpar-heading">
                                <?php 
_e( 'Plugin Tools', 'wp-auto-republish' );
?>
                            </span>
                        </h3>
                        <div class="inside">
                            <?php 
do_settings_sections( 'wpar_plugin_tools_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary wpar-save',
    '',
    false
);
?>
                            <?php 
?></p>
                        </div>
                    </div>
                </form>
                <?php 
?>
                <?php 
?>
                    <div class="coffee-box">
                        <div class="coffee-amt-wrap">
                            <p><select class="coffee-amt">
                                <option value="5usd">$5</option>
                                <option value="6usd">$6</option>
                                <option value="7usd">$7</option>
                                <option value="8usd">$8</option>
                                <option value="9usd">$9</option>
                                <option value="10usd" selected="selected">$10</option>
                                <option value="11usd">$11</option>
                                <option value="12usd">$12</option>
                                <option value="13usd">$13</option>
                                <option value="14usd">$14</option>
                                <option value="15usd">$15</option>
                                <option value=""><?php 
_e( 'Custom', 'wp-auto-republish' );
?></option>
                            </select></p>
                            <a class="button button-primary buy-coffee-btn" style="margin-left: 2px;" href="https://www.paypal.me/iamsayan/10usd" data-link="https://www.paypal.me/iamsayan/" target="_blank"><?php 
_e( 'Buy me a coffee!', 'wp-auto-republish' );
?></a>
                        </div>
                        <span class="coffee-heading">
                            <?php 
_e( 'Buy me a coffee!', 'wp-auto-republish' );
?>
                        </span>
                        <p style="text-align: justify;">
                            <?php 
printf( __( 'Thank you for using %s. If you found the plugin useful buy me a coffee! Your donation will motivate and make me happy for all the efforts. You can donate via PayPal.', 'wp-auto-republish' ), '<strong>' . $this->name . ' v' . $this->version . '</strong>' );
?></strong>
                        </p>
                        <p style="text-align: justify;font-size: 12px;font-style: italic;">
                            Developed with <span style="color:#e25555;">♥</span> by <a href="https://www.sayandatta.in" target="_blank" style="font-weight: 500;">Sayan Datta</a> | 
                            <a href="https://www.sayandatta.in/contact/" style="font-weight: 500;">Hire Me</a> | 
                            <a href="https://github.com/iamsayan/wp-auto-republish" target="_blank" style="font-weight: 500;">GitHub</a> | <a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank" style="font-weight: 500;">Support</a> | 
                            <a href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" style="font-weight: 500;">Rate it</a> (<span style="color:#ffa000;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>) on WordPress.org, if you like this plugin.
                        </p>
                    </div>
                <?php 
?>
            </div>
           
            <div id="postbox-container-1" class="postbox-container">
            <?php 
?> 
            <?php 
?>
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;text-align: center;"><?php 
_e( 'Upgrade to Premium Now!', 'wp-auto-republish' );
?></h3>
                    <div class="inside">
                        <div class="misc-pub-section" style="text-align:center;">
                            <i><?php 
_e( 'Upgrade to the premium version and get the following features', 'wp-auto-republish' );
?></i>:<br>
				            <ul>
				            	<li>• <?php 
_e( 'Unlimited Custom Post types Support', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Custom Taxonomies Support', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Individual Post Republishing', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Scheduled Post Republishing', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Date & Time Based Republishing', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Custom Post Republish Interval', 'wp-auto-republish' );
?></li>
				            	<li>• <?php 
_e( 'Custom Title for each Republish Event', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Automatic Single Cache Purge Support', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Date Range for Republishing', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Can use Dates in Post Permalinks', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Change Post Status after Republish', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'One Click Instant Republish', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Email Notification upon Republishing', 'wp-auto-republish' );
?></li>
                                <li>• <?php 
_e( 'Priority Email Support & many more..', 'wp-auto-republish' );
?></li>
				            </ul>
				            <?php 

if ( wpar_load_fs_sdk()->is_not_paying() && !wpar_load_fs_sdk()->is_trial() && !wpar_load_fs_sdk()->is_trial_utilized() ) {
    ?>
                                <a class="button button-primary" href="<?php 
    echo  wpar_load_fs_sdk()->get_trial_url() ;
    ?>"><?php 
    _e( 'Start Trial', 'wp-auto-republish' );
    ?></a>&nbsp;
                            <?php 
}

?>
                            <a class="button button-primary" href="<?php 
echo  wpar_load_fs_sdk()->get_upgrade_url() ;
?>"><?php 
_e( 'Upgrade Now', 'wp-auto-republish' );
?></a>
                        </div>
                    </div>
                </div>
            <?php 
?> 
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;text-align: center;"><?php 
_e( 'My Other Plugins!', 'wp-auto-republish' );
?></h3>
                    <div class="inside">
                        <div class="misc-pub-section">
                            <div style="text-align: center;">
                                <span class="dashicons dashicons-clock" style="font-size: 16px;vertical-align: middle;"></span>
                                <strong><a href="https://wordpress.org/plugins/wp-last-modified-info/" target="_blank">WP Last Modified Info</a></strong>
                            </div>
                            <div style="text-align: center;">
                                <?php 
_e( 'Display last update date and time on pages and posts very easily with \'dateModified\' Schema Markup.', 'wp-auto-republish' );
?>
                            </div>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <div style="text-align: center;">
                                <span class="dashicons dashicons-admin-comments" style="font-size: 16px;vertical-align: middle;"></span>
                                <strong><a href="https://wordpress.org/plugins/ultimate-facebook-comments/" target="_blank">Ultimate Social Comments</a></strong>
                            </div>
                            <div style="text-align: center;">
                                <?php 
_e( 'Ultimate Facebook Comments Solution with instant email notification for any WordPress Website. Everything is customizable.', 'wp-auto-republish' );
?>
                            </div>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <div style="text-align: center;">
                                <span class="dashicons dashicons-admin-links" style="font-size: 16px;vertical-align: middle;"></span>
                                <strong><a href="https://wordpress.org/plugins/change-wp-page-permalinks/" target="_blank">WP Page Permalink Extension</a></strong>
                            </div>
                            <div style="text-align: center;">
                                <?php 
_e( 'Add any page extension like .html, .php, .aspx, .htm, .asp, .shtml only to wordpress pages very easily (tested on Yoast SEO, All in One SEO Pack, Rank Math, SEOPresss and Others).', 'wp-auto-republish' );
?>
                            </div>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <div style="text-align: center;">
                                <span class="dashicons dashicons-megaphone" style="font-size: 16px;vertical-align: middle;"></span>
                                <strong><a href="https://wordpress.org/plugins/simple-posts-ticker/" target="_blank">Simple Posts Ticker</a></strong>
                            </div>
                            <div style="text-align: center;">
                                <?php 
_e( 'Simple Posts Ticker is a small tool that shows your most recent posts in a marquee style.', 'wp-auto-republish' );
?>
                            </div>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <div style="text-align: center;">
                                <span class="dashicons dashicons-admin-generic" style="font-size: 16px;vertical-align: middle;"></span>
                                <strong><a href="https://wordpress.org/plugins/remove-wp-meta-tags/" target="_blank">Easy Header Footer</a></strong>
                            </div>
                            <div style="text-align: center;">
                                <?php 
_e( 'Customize WP header, add custom code and enable, disable or remove the unwanted meta tags, links from the source code and many more.', 'wp-auto-republish' );
?>
                            </div>
                        </div>
                    </div>
                </div>
            </diV>
        </div>
    </div>
</div>