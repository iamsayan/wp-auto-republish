<?php 
/**
 * Admin callbacks.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Api\Callbacks
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

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
		return require_once( "$this->plugin_path/templates/admin.php" );
	}
}