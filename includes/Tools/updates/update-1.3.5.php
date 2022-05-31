<?php
/**
 * The Updates routine for version 1.3.5
 *
 * @since      1.3.5
 * @package    RevivePress
 * @subpackage RevivePress\Tools\updates
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * v1.3.5 migration
 */
function revivepress_1_3_5_migration() {
	as_unschedule_all_actions( 'wpar/global_schedule_next_date' ); // @phpstan-ignore-line
}
revivepress_1_3_5_migration();