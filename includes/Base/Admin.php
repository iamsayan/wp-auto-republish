<?php

/**
 * Admin customizations.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Base
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Base;

use  RevivePress\Helpers\Hooker ;
use  RevivePress\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Admin class.
 */
class Admin extends BaseController
{
    use  Hooker ;
    /**
     * Register functions.
     */
    public function register() {
        $this->action(
            "plugin_action_links_{$this->plugin}",
            'settings_link',
            10,
            1
        );
        $this->action( 'admin_menu', 'menu_items', 99 );
        $this->action( 'admin_footer', 'do_footer', 99 );
        $this->action(
            'plugin_row_meta',
            'meta_links',
            10,
            2
        );
        $this->action( 'admin_footer_text', 'admin_footer', 999 );
        $this->filter( 'action_scheduler_pastdue_actions_check_pre', 'as_exclude_pastdue_actions' );
    }
    
    /**
     * Register settings link.
     */
    public function settings_link( $links ) {
        $settings = [ '<a href="' . admin_url( 'admin.php?page=revivepress' ) . '">' . __( 'Settings', 'wp-auto-republish' ) . '</a>' ];
        return array_merge( $settings, $links );
    }
    
    /**
     * Add roadmap item to submenu
     */
    public function menu_items() {
        global  $submenu ;
        $manage_options_cap = apply_filters( 'wpar/manage_options_capability', 'manage_options' );
        $submenu['revivepress'][] = [ __( 'Scheduled Tasks', 'wp-auto-republish' ), $manage_options_cap, admin_url( 'tools.php?page=action-scheduler&status=pending&s=wpar' ) ];
        $submenu['revivepress'][] = [ __( 'Roadmap', 'wp-auto-republish' ), $manage_options_cap, 'https://api.wprevivepress.com/go/roadmap?utm_source=admin_menu&utm_medium=plugin' ];
    }
    
    /**
     * Open External links in new tab
     */
    public function do_footer() {
        ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( "ul#adminmenu a[href$='https://wpart.dev/wp-admin/tools.php?page=action-scheduler&status=pending&s=wpar']" ).attr( { target: '_blank' } );
				$( "ul#adminmenu a[href$='https://api.wprevivepress.com/go/roadmap?utm_source=admin_menu&utm_medium=plugin']" ).attr( { target: '_blank', rel: 'noopener noreferrer' } );
			} );
		</script>
		<?php 
    }
    
    /**
     * Register meta links.
     */
    public function meta_links( $links, $file ) {
        if ( $this->plugin === $file ) {
            // only for this plugin
            $links[] = '<a href="https://wprevivepress.com/docs/?utm_source=plugin_page&utm_medium=plugin" target="_blank">' . __( 'Documentation', 'wp-auto-republish' ) . '</a>';
        }
        return $links;
    }
    
    /**
     * Custom Admin footer text
     */
    public function admin_footer( $text ) {
        $current_screen = get_current_screen();
        
        if ( 'toplevel_page_revivepress' === $current_screen->id ) {
            $text = [];
            $text[] = __( 'Developed with', 'wp-auto-republish' ) . ' <span style="color:#e25555;">♥</span> by <a href="https://sayandatta.in?utm_source=plugin_page&utm_medium=revivepress" target="_blank" style="font-weight: 500;">Sayan Datta</a>';
            $text[] = '<a href="https://github.com/iamsayan/wp-auto-republish" target="_blank" style="font-weight: 500;">GitHub</a>';
            $text[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish" target="_blank" style="font-weight: 500;">' . __( 'Support Forum', 'wp-auto-republish' ) . '</a>';
            $text[] = '<a href="https://wordpress.org/support/plugin/wp-auto-republish/reviews/?filter=5#new-post" target="_blank" style="font-weight: 500;">' . __( 'Rate it', 'wp-auto-republish' ) . '</a> (<span style="color:#ffa000;">★★★★★</span>) on WordPress.org, if you like this plugin.</span>';
            $text = '<span class="wpar-footer">' . join( ' | ', $text ) . '</span>';
        }
        
        return $text;
    }
    
    /**
     * Action Scheduler: exclude our actions from the past-due checker.
     * Since this is a *_pre hook, it replaces the original checker.
     *
     * We first do the same check as what ActionScheduler_AdminView->check_pastdue_actions() does,
     * but then we also count how many of those past-due actions are ours.
     *
     * @param null $null Null value.
     */
    public function as_exclude_pastdue_actions( $null ) {
        $query_args = [
            'date'     => as_get_datetime_object( time() - DAY_IN_SECONDS ),
            'status'   => \ActionScheduler_Store::STATUS_PENDING,
            'per_page' => 1,
        ];
        $store = \ActionScheduler_Store::instance();
        $num_pastdue_actions = (int) $store->query_actions( $query_args, 'count' );
        
        if ( 0 !== $num_pastdue_actions ) {
            $query_args['group'] = 'wp-auto-republish';
            $num_pastdue_rm_actions = (int) $store->query_actions( $query_args, 'count' );
            $num_pastdue_actions -= $num_pastdue_rm_actions;
        }
        
        $threshold_seconds = (int) apply_filters( 'action_scheduler_pastdue_actions_seconds', DAY_IN_SECONDS );
        $threshhold_min = (int) apply_filters( 'action_scheduler_pastdue_actions_min', 1 );
        $check = $num_pastdue_actions >= $threshhold_min;
        return (bool) apply_filters(
            'action_scheduler_pastdue_actions_check',
            $check,
            $num_pastdue_actions,
            $threshold_seconds,
            $threshhold_min
        );
    }

}