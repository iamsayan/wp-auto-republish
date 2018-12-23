<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Auto Republish
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */
?>

<div class="wrap">
    <h1><?php _e( 'WP Auto Republish', 'wp-auto-republish' ); ?> <span style="font-size:12px;"><?php _e( 'Ver', 'wp-auto-republish' ); ?> <?php echo WPAR_PLUGIN_VERSION ?></span></h1>
    <div><?php _e( 'This plugin helps to revive old posts by resetting the publish date to the current date.', 'wp-auto-republish' ); ?></div><hr>
    <div id="poststuff" style="padding-top: 0;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">
                        <span class="wpar-heading">
                            <?php _e( 'Configure Settings', 'wp-auto-republish' ); ?>
                        </span>
                    </h3>
                    <div class="inside">
                        <form id="saveForm" method="post" action="options.php" style="padding-left: 8px;">
                            <?php settings_fields( 'wpar_plugin_settings_fields' ); ?>
                            <?php do_settings_sections( 'wpar_plugin_option' ); ?>
                            <p><?php submit_button( __( 'Save Changes', 'wp-auto-republish' ), 'primary save-settings', '', false ); ?></p>
                        </form>
                    </div>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">My Other Plugins!</h3>
                    <div class="inside">
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-clock"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/wp-last-modified-info/" target="_blank">WP Last Modified Info</a>: </strong>
                                Display last update date and time on pages and posts very easily with 'dateModified' Schema Markup.
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-comments"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/ultimate-facebook-comments/" target="_blank">Ultimate Facebook Comments</a>: </strong>
                                Ultimate Facebook Comment Solution with instant email notification for any WordPress Website. Everything is customizable.
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-links"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/change-wp-page-permalinks/" target="_blank">WP Page Permalink Extension</a>: </strong>
                                Add any page extension like .html, .php, .aspx, .htm, .asp, .shtml only to wordpress pages very easily (tested on Yoast SEO
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/remove-wp-meta-tags/" target="_blank">Easy Header Footer</a>: </strong>
                                Customize WP header, add custom code and enable, disable or remove the unwanted meta tags, links from the source code and many more.
                            </label>
                        </div>
                    </div>
                </div>
            </diV>
        </div>
    </div>
</div>