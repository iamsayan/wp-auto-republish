<?php
/**
 * The Updates routine for version 1.3.7
 *
 * @since      1.3.7
 * @package    RevivePress
 * @subpackage RevivePress\Tools\updates
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * v1.3.7 migration
 */
function revivepress_1_3_7_migration() {
	as_unschedule_all_actions( 'wpar/global_schedule_next_date' ); // @phpstan-ignore-line
	wp_clear_scheduled_hook( 'wpar/schedular_health_check' );
	delete_option( 'wpar_next_scheduled' );
}
revivepress_1_3_7_migration();