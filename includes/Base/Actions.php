<?php

/**
 * Action links.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Wpar\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace Wpar\Base;

use  Wpar\Helpers\Hooker ;
use  Wpar\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Action links class.
 */
class Actions extends BaseController
{
    use  Hooker ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action(
            "plugin_action_links_{$this->plugin}",
            'settings_link',
            10,
            1
        );
        $this->action( 'admin_menu', 'roadmap_link', 999 );
        $this->action( 'admin_footer', 'do_footer', 99 );
        $this->action(
            'plugin_row_meta',
            'meta_links',
            10,
            2
        );
        $this->action( 'admin_footer_text', 'admin_footer', 9999 );
        $this->action(
            'upgrader_process_complete',
            'run_upgrade_action',
            10,
            2
        );
    }
    
    /**
     * Register settings link.
     */
    public function settings_link( $links )
    {
        $wparlinks = [ '<a href="' . admin_url( 'admin.php?page=revivepress' ) . '">' . __( 'Settings', 'wp-auto-republish' ) . '</a>' ];
        return array_merge( $wparlinks, $links );
    }
    
    /**
     * Add roadmap item to submenu
     */
    public function roadmap_link()
    {
        global  $submenu ;
        $submenu['revivepress'][] = [ __( 'Roadmap', 'wp-auto-republish' ), 'manage_options', 'https://api.wpautorepublish.com/go/roadmap' ];
    }
    
    /**
     * Open External links in new tab
     */
    public function do_footer()
    {
        ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( "ul#adminmenu a[href$='https://api.wpautorepublish.com/go/roadmap']" ).attr( { target: '_blank', rel: 'noopener noreferrer' } );
			} );
		</script>
		<?php 
    }
    
    /**
     * Register meta links.
     */
    public function meta_links( $links, $file )
    {
        
        if ( $file === $this->plugin ) {
            // only for this plugin
            
            if ( wpar_load_fs_sdk()->is_not_paying() && !wpar_load_fs_sdk()->is_trial() ) {
                if ( !wpar_load_fs_sdk()->is_trial_utilized() ) {
                    $links[] = '<a href="' . esc_url( wpar_load_fs_sdk()->get_trial_url() ) . '" target="_blank" style="font-weight: 700;">' . __( 'Premium Trial', 'wp-auto-republish' ) . '</a>';
                }
                $links[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank">' . __( 'Support Forum', 'wp-auto-republish' ) . '</a>';
                $links[] = '<a href="https://www.paypal.me/iamsayan/" target="_blank">' . __( 'Donate', 'wp-auto-republish' ) . '</a>';
            }
            
            $links[] = '<a href="https://wpautorepublish.com/docs/" target="_blank">' . __( 'Documentation', 'wp-auto-republish' ) . '</a>';
        }
        
        return $links;
    }
    
    /**
     * Custom Admin footer text
     */
    public function admin_footer( $text )
    {
        $current_screen = get_current_screen();
        
        if ( 'toplevel_page_revivepress' === $current_screen->id ) {
            $text = [];
            $text[] = __( 'Developed with', 'wp-auto-republish' ) . ' <span style="color:#e25555;">♥</span> by <a href="https://sayandatta.in" target="_blank" style="font-weight: 500;">Sayan Datta</a>';
            $text[] = '<a href="https://sayandatta.in/contact/" target="_blank" style="font-weight: 500;">' . __( 'Hire Me', 'wp-auto-republish' ) . '</a>';
            $text[] = '<a href="https://github.com/iamsayan/wp-auto-republish" target="_blank" style="font-weight: 500;">GitHub</a>';
            $text[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank" style="font-weight: 500;">' . __( 'Support Forum', 'wp-auto-republish' ) . '</a>';
            $text[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" style="font-weight: 500;">' . __( 'Rate it', 'wp-auto-republish' ) . '</a> (<span style="color:#ffa000;">★★★★★</span>) on WordPress.org, if you like this plugin.</span>';
            $text = '<span class="wpar-footer">' . join( ' | ', $text ) . '</span>';
        }
        
        return $text;
    }
    
    /**
     * Run process after plugin update.
     */
    public function run_upgrade_action( $upgrader_object, $options )
    {
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there
            foreach ( $options['plugins'] as $plugin ) {
                if ( $plugin === $this->plugin ) {
                    $this->do_action(
                        'plugin_updated',
                        $options,
                        $this->version,
                        $upgrader_object
                    );
                }
            }
        }
    }

}