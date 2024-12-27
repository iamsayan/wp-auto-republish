<?php
/**
 * Action Scheduler functions.
 *
 * @since      1.3.2
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Action Scheduler Class
 */
trait Scheduler
{
	
	/**
	 * Create the async action event.
	 *
	 * @param string  $hook      Action Hook.
	 * @param array   $args      Parameters.
	 * @param integer $priority  Load Order Priority.
	 * @param boolean $unique    Whether the action should be unique.
	 * @param string  $group     Group Name.
	 *
	 * @return int
	 */
	protected function schedule_async_action( string $hook, array $args = array(), int $priority = 5, bool $unique = false, string $group = 'wp-auto-republish' ): int {
		return \as_enqueue_async_action( $hook, $args, $group, $unique, $priority ); // @phpstan-ignore-line
	}

	/**
	 * Create the recurring action event.
	 *
	 * @param integer $timestamp            Timestamp.
	 * @param integer $interval_in_seconds  Interval in Seconds.
	 * @param string  $hook                 Action Hook.
	 * @param array   $args                 Parameters.
	 * @param integer $priority             Load Order Priority.
	 * @param boolean $unique               Whether the action should be unique.
	 * @param string  $group                Group Name.
	 *
	 * @return int
	 */
	protected function schedule_recurring_action( int $timestamp, int $interval_in_seconds, string $hook, array $args = array(), int $priority = 5, bool $unique = false, string $group = 'wp-auto-republish' ): int {
		return \as_schedule_recurring_action( $timestamp, $interval_in_seconds, $hook, $args, $group, $unique, $priority ); // @phpstan-ignore-line
	}

	/**
	 * Create the single action event.
	 *
	 * @param  integer $timestamp  Timestamp.
	 * @param  string  $hook       Hook.
	 * @param  array   $args       Parameters.
	 * @param  integer $priority   Load Order Priority.
	 * @param  boolean $unique     Whether the action should be unique.
	 * @param  string  $group      Group Name.
	 * @return string
	 */
	protected function schedule_single_action( $timestamp, $hook, $args = array(), $priority = 5, $unique = false, $group = 'wp-auto-republish' ) {
		$action_id = \as_schedule_single_action( $timestamp, $hook, $args, $group, $unique, $priority ); // @phpstan-ignore-line

		return $action_id;
	}

	/**
	 * Unscheduled all action events.
	 *
	 * @param string $hook  Hook.
	 * @param array  $args  Parameters.
	 * @param string $group Group Name.
	 */
	protected function unschedule_all_actions( string $hook, array $args = array(), string $group = 'wp-auto-republish' ) {
		\as_unschedule_all_actions( $hook, $args, $group ); // @phpstan-ignore-line
	}

	/**
	 * Unschedule last action event.
	 *
	 * @param string $hook       Hook.
	 * @param array  $args       Parameters.
	 * @param string $group      Group Name.
	 */
	protected function unschedule_last_action( string $hook, array $args = array(), string $group = 'wp-auto-republish' ) {
		\as_unschedule_action( $hook, $args, $group ); // @phpstan-ignore-line
	}

	/**
	 * Net next scheduled action.
	 *
	 * @param string $hook   Action Hook.
	 * @param array  $args   Parameters.
	 * @param string $group  Group Name.
	 *
	 * @return bool|int
	 */
	protected function get_next_action( string $hook, array $args = array(), string $group = 'wp-auto-republish' ) {
		return \as_next_scheduled_action( $hook, $args, $group ); // @phpstan-ignore-line
	}

	/**
	 * Check if next action is existing.
	 *
	 * @param string $hook   Action Hook.
	 * @param array  $args   Parameters.
	 * @param string $group  Group Name.
	 *
	 * @return bool
	 */
	protected function has_next_action( string $hook, array $args = array(), string $group = 'wp-auto-republish' ): bool {
		if ( ! function_exists( 'as_has_scheduled_action' ) ) {
			return \boolval( $this->get_next_action( $hook, $args, $group ) );  // @phpstan-ignore-line
		}
		return \as_has_scheduled_action( $hook, $args, $group ); // @phpstan-ignore-line
	}

	/**
	 * Create batch tasks with a specific interval.
	 *
	 * @since 1.3.2
	 *
	 * @param array    $post_ids       Post IDs.
	 * @param string   $name           Action Name.
	 * @param integer  $chunk_size     Size of the Chunk.
	 * @param integer  $interval       Interval between two batch tasks.
	 * @param array    $args           Args to merge.
	 *
	 * @return void
	 */
	protected function schedule_batch_actions( array $post_ids, string $name, int $chunk_size = 50, int $interval = 10, array $args = array() ) {
		$interval = $interval * 2;
		$counter  = 0;

		if ( ! empty( $post_ids ) ) {
			$chunks = \array_chunk( $post_ids, $chunk_size );

			foreach ( $chunks as $chunk ) {
				++$counter;
				$this->schedule_single_action( time() + ( $interval * ( $counter / 2 ) ), $name, array_merge( array( $chunk ), $args ) );
			}
		}
	}
}