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
?></div><hr>
        <div class="top-sharebar">
            <a class="share-btn rate-btn" href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" title="Please rate 5 stars if you like <?php 
echo  $this->name ;
?>"><span class="dashicons dashicons-star-filled"></span> Rate 5 stars</a>
            <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=Check%20out%20WP%20Auto%20Republish,%20a%20%23WordPress%20%23plugin%20that%20revive%20your%20old%20posts%20by%20resetting%20the%20published%20date%20to%20the%20current%20date%20https%3A//wordpress.org/plugins/wp-auto-republish/%20via%20%40im_sayaan" target="_blank"><span class="dashicons dashicons-twitter"></span> Tweet about WP Auto Republish</a>
        </div>
    </div>
    <div id="poststuff" style="padding-top: 0;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">
                        <span class="wpar-heading">
                            <?php 
_e( 'Configure Settings', 'wp-auto-republish' );
?>
                        </span>
                    </h3>
                    <div class="inside">
                        <form id="saveForm" method="post" action="options.php" style="padding-left: 8px;">
                            <?php 
settings_fields( 'wpar_plugin_settings_fields' );
?>
                            <?php 
do_settings_sections( 'wpar_plugin_option' );
?>
                            <p><?php 
submit_button(
    __( 'Save Settings', 'wp-auto-republish' ),
    'primary save-settings',
    '',
    false
);
?></p>
                        </form>
                        <div id="progressMessage" class="progressModal" style="display:none;">
                            <?php 
_e( 'Please wait...', 'wp-auto-republish' );
?>
                        </div>
                        <div id="saveMessage" class="successModal" style="display:none;">
                            <p class="spt-success-msg">
                                <?php 
_e( 'Settings Saved Successfully!', 'wp-auto-republish' );
?>
                            </p>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function($) {
                                $('#saveForm').submit(function() {
                                    $('#progressMessage').show();
                                    $(".save-settings").addClass("disabled");
                                    $(".save-settings").val("<?php 
_e( 'Saving...', 'wp-auto-republish' );
?>");
                                    $(this).ajaxSubmit({
                                        success: function() {
                                            $('#progressMessage').fadeOut();
                                            $('#saveMessage').show().delay(4000).fadeOut();
                                            $(".save-settings").removeClass("disabled");
                                            $(".save-settings").val("<?php 
_e( 'Save Settings', 'wp-auto-republish' );
?>");
                                        }
                                    });
                                    return false;
                                });
                            });
                        </script>
                    </div>
                </div>
                <!-- todo: email -->
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
                            <a href="mailto:iamsayan@pm.me" style="font-weight: 500;">Hire Me</a> | 
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
_e( 'Priority Email Support & many more..', 'wp-auto-republish' );
?></li>
				            </ul>
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