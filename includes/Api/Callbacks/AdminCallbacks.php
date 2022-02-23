<?php

/**
 * Admin callbacks.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Wpar\Api\Callbacks
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace Wpar\Api\Callbacks;

use  Wpar\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Admin callbacks class.
 */
class AdminCallbacks extends BaseController
{
    /**
     * Call dashboard template.
     */
    public function adminDashboard()
    {
        $options = get_option( 'wpar_plugin_settings' );
        $last = get_option( 'wpar_last_global_cron_run' );
        $format = get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' );
        $class_name = ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ? ' premium' : '' );
        return require_once "{$this->plugin_path}/templates/admin.php";
    }
    
    public function sectionHeader( $title, $description )
    {
        ?>
		<div class="wpar-metabox-holder">
			<div class="wpar-metabox-td">
				<h3 class="wpar-metabox-title"><?php 
        echo  esc_html( $title ) ;
        ?></h3>
				<p class="wpar-metabox-description"><?php 
        echo  wp_kses_post( $description ) ;
        ?></p>
			</div>
		</div>
		<?php 
    }
    
    public function doSettingsSection( $attr )
    {
        ?>
		<div id="<?php 
        echo  esc_attr( $attr['id'] ) ;
        ?>" class="postbox <?php 
        echo  esc_attr( $attr['class'] ) ;
        ?>">
			<?php 
        $this->sectionHeader( $attr['title'], $attr['description'] );
        ?>
			<div class="inside">
				<?php 
        do_settings_sections( $attr['name'] );
        ?>
			</div>
			<p class="wpar-control-area">
				<?php 
        submit_button(
            __( 'Save Settings', 'wp-auto-republish' ),
            'primary wpar-save',
            '',
            false
        );
        ?>
				<?php 
        ?>
			</p>
		</div>
		<?php 
    }

}