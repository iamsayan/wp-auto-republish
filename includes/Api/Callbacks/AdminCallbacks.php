<?php

/**
 * Admin callbacks.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Api\Callbacks
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Api\Callbacks;

use  RevivePress\Base\BaseController ;
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
        $class_name = ( revivepress_fs()->can_use_premium_code__premium_only() ? ' premium' : '' );
        return require_once $this->plugin_path . 'templates/admin.php';
    }
    
    public function subMenu( $items, $class )
    {
        $allowed_html = array(
            'i' => array(
				'class' => array(),
			),
        );
        $sub_items = array();
        foreach ( $items as $item => $title ) {
            $sub_items[] = '<a href="#" class="sub-link sub-link-' . esc_attr( $item ) . '" data-type="' . esc_attr( $item ) . '">' . wp_kses( $title, $allowed_html ) . '</a>';
        }
        
        if ( ! empty($sub_items) ) {
            echo  '<div class="postbox sub-links wpar-' . esc_attr( $class ) . ' d-none">' ;
            echo  join( '<span>&#124;</span>', $sub_items ) ;
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo  '</div>' ;
        }
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
        $social_accounts = false;
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
    
    public function systemStatus()
    {
        $info = array();
        $info['memory_limit'] = array(
            'label'       => __( 'PHP memory limit' ),
            'value'       => ini_get( 'memory_limit' ),
            'minimum'     => '256M',
            'recommended' => '512M',
        );
        $info['max_execution_time'] = array(
            'label'       => __( 'PHP time limit' ),
            'value'       => ini_get( 'max_execution_time' ),
            'minimum'     => 300,
            'recommended' => 600,
        );
        $info['max_input_time'] = array(
            'label'       => __( 'Max input time' ),
            'value'       => ini_get( 'max_input_time' ),
            'minimum'     => 120,
            'recommended' => 300,
        );
        ?>

		<div class="table-php-requirements-container">
			<table class="table-php-requirements" style="text-align: left;">
				<thead>
					<tr>
						<th><?php 
        esc_html_e( 'Name', 'wp-auto-republish' );
        ?></th>
						<th><?php 
        esc_html_e( 'Directive', 'wp-auto-republish' );
        ?></th>
						<th><?php 
        esc_html_e( 'Least Suggested', 'wp-auto-republish' );
        ?></th>
						<th><?php 
        esc_html_e( 'Recommended', 'wp-auto-republish' );
        ?></th>
						<th><?php 
        esc_html_e( 'Current Value', 'wp-auto-republish' );
        ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
        foreach ( $info as $key => $data ) {
            ?>
						<tr>
							<td><?php 
            echo  esc_html( $data['label'] ) ;
            ?></td>
							<td><?php 
            echo  esc_html( $key ) ;
            ?></td>
							<td class="bold"><?php 
            echo  esc_html( $data['minimum'] ) ;
            ?></td>
							<td class="bold"><?php 
            echo  esc_html( $data['recommended'] ) ;
            ?></td>
							<td class="bold"><?php 
            echo  esc_html( $data['value'] ) ;
            ?></td>
						</tr>
					<?php 
        }
        ?>
				</tbody>
			</table>
			<p>
				<?php 
        printf(
            /* translators: 1: <a> tag start, 2: </a> tag end. */
            esc_html__( 'To change PHP directives you need to modify php.ini file, more information about this you can %1$ssearch here%2$s or contact your hosting provider. See Site Health for more.', 'wp-auto-republish' ),
            '<a href="http://goo.gl/I9f74U" target="_blank" rel="noopener">',
            '</a>'
        );
        ?>
				<?php 
        if ( defined( 'DISABLE_WP_CRON' ) && true === DISABLE_WP_CRON ) {
            esc_html_e( 'WordPress Cron is currently disabled. Please enable it if you are facing asny issue.', 'wp-auto-republish' );
        }
        ?>
			</p>
		</div>
		<?php 
    }
}