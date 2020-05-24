<?php

/**
 * Admin notices.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Base;

use  Wpar\Helpers\Hooker ;
defined( 'ABSPATH' ) || exit;
/**
 * Admin Notice class.
 */
class AdminNotice
{
    use  Hooker ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->action( 'admin_notices', 'install_notice' );
    }
    
    /**
     * Show internal admin notices.
     */
    public function install_notice()
    {
        global  $pagenow ;
        
        if ( preg_match( '(%year%|%monthnum%|%day%|%hour%|%minute%|%second%)', get_option( 'permalink_structure' ) ) === 1 ) {
            ?>
			    <div class="notice notice-warning">
			    	<p><strong><?php 
            printf( __( 'WARNING: As it seems that your permalinks structure contain date, please disable the WP Auto Republish plugin immediately.', 'wp-auto-republish' ) );
            ?></strong></p>
			    </div> <?php 
        }
        
        // Show a warning to sites running PHP < 5.6
        
        if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
            ?>
			<div class="error"><p><strong><?php 
            _e( 'Your version of PHP is below the minimum version of PHP required by WP Auto Republish plugin. Please contact your host and request that your version be upgraded to 5.6 or later.', 'wp-auto-republish' );
            ?></strong></p></div>
		<?php 
        }
    
    }

}