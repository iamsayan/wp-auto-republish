<?php

/**
 * Admin callbacks.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Api\Callbacks
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Api\Callbacks;

use  Wpar\Helpers\Logger ;
use  Wpar\Base\BaseController ;
defined( 'ABSPATH' ) || exit;
/**
 * Admin callbacks class.
 */
class AdminCallbacks extends BaseController
{
    use  Logger ;
    /**
     * Call dashboard template.
     */
    public function adminDashboard()
    {
        return require_once "{$this->plugin_path}/templates/admin.php";
    }

}