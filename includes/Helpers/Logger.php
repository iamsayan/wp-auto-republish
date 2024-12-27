<?php

/**
 * The Logger.
 *
 * @since      1.5.8
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Helpers;

defined( 'ABSPATH' ) || exit;
/**
 * Logger class.
 */
trait Logger
{
    /**
     * Log an event
     *
     * @param int   $post_id Post ID
     * @param array $args    Log arguments
     */
    protected function log( $post_id, $args ) {
    }
}