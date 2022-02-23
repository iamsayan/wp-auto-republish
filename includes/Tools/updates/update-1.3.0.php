<?php
/**
 * The Updates routine for version 1.3.0
 *
 * @since      1.3.0
 * @package    RevivePress
 * @subpackage Wpar\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove duplicated post fetch tasks.
 *
 * @return void
 */
function revivepress_1_3_0_remove_duplicate_tasks() {
	// remove health check tasks
	as_unschedule_all_actions( 'wpar/process_health_check' );

	$task_name = 'wpar/global_republish_fetch_posts';
	$actions   = as_get_scheduled_actions(
		[
			'hook'    => $task_name,
			'status'  => 'pending',
			'orderby' => 'date',
			'order'   => 'DESC',
		],
		ARRAY_A
	);

	// Run cleaner only when two or more actions are scheduled.
	if ( count( $actions ) <= 1 ) {
		return;
	}

	$timestamp = as_next_scheduled_action( $task_name ); // Get first action timestamp.
	as_unschedule_all_actions( 'wpar/global_republish_fetch_posts' );
	
	if ( false !== $timestamp ) {
		$interval = apply_filters( 'wpar/global_cron_interval', 3 );
		as_schedule_recurring_action( time() + ( MINUTE_IN_SECONDS * $interval ), MINUTE_IN_SECONDS * $interval, $task_name, [], 'wp-auto-republish' );
	}
}
revivepress_1_3_0_remove_duplicate_tasks();