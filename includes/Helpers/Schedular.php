<?php
/**
 * Action Schedular functions.
 *
 * @since      1.3.2
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Action Schedular Class
 */
trait Schedular
{
	
	/**
	 * Create the recurring action event.
	 *
	 * @param  integer $timestamp            Timestamp.
	 * @param  integer $interval_in_seconds  Interval in Seconds.
	 * @param  string  $hook                 Action Hook.
	 * @param  array   $args                 Parameters.
	 * @param  string  $group                Group Name.
	 * @return string
	 */
	protected function set_recurring_action( $timestamp, $interval_in_seconds, $hook, $args = [], $group = 'wp-auto-republish' ) {
		$action_id = \as_schedule_recurring_action( $timestamp, $interval_in_seconds, $hook, $args, $group );

		return $action_id;
	}

	/**
	 * Create the single action event.
	 *
	 * @param  integer $timestamp  Timestamp.
	 * @param  string  $hook       Hook.
	 * @param  array   $arg        Parameter.
	 * @param  string  $group      Group Name.
	 * @return string
	 */
	protected function set_single_action( $timestamp, $hook, $args = [], $group = 'wp-auto-republish' ) {
		$action_id = \as_schedule_single_action( $timestamp, $hook, $args, $group );

		return $action_id;
	}

	/**
	 * Unschedule all action events.
	 *
	 * @param  string  $hook       Hook.
	 * @param  array   $arg        Parameter.
	 * @param  string  $group      Group Name.
	 */
	protected function unschedule_all_actions( $hook, $args = [], $group = 'wp-auto-republish' ) {
		\as_unschedule_all_actions( $hook, $args, $group );
	}

	/**
	 * Unschedule last action event.
	 *
	 * @param  string  $hook       Hook.
	 * @param  array   $arg        Parameter.
	 * @param  string  $group      Group Name.
	 */
	protected function unschedule_last_action( $hook, $args = [], $group = 'wp-auto-republish' ) {
		\as_unschedule_action( $hook, $args, $group );
	}

	/**
	 * Net next scheduled action.
	 *
	 * @param  string  $hook   Action Hook.
	 * @param  array   $args   Parameters.
	 * @param  string  $group  Group Name.
	 * @return null|string
	 */
	protected function get_next_action( $hook, $args = [], $group = 'wp-auto-republish' ) {
		return \as_next_scheduled_action( $hook, $args, $group );
	}

	/**
	 * Check if next action is exists.
	 *
	 * @param  string  $hook   Action Hook.
	 * @param  array   $args   Parameters.
	 * @param  string  $group  Group Name.
	 * @return null|string
	 */
	protected function has_next_action( $hook, $args = [], $group = 'wp-auto-republish' ) {
		if ( ! function_exists( 'as_has_scheduled_action' ) ) {
			return \boolval( $this->get_next_action( $hook, $args, $group ) );
		}
		return \as_has_scheduled_action( $hook, $args, $group );
	}

	/**
	 * Get next scheduled actions
	 *
	 * @param  array   $args   Parameters.
	 * @return null|string
	 */
	protected function get_next_actions( $args ) {
		return \as_get_scheduled_actions( $args );
	}

	/**
	 * Get next scheduled actions by data
	 *
	 * @param  string  $hook   Action Hook.
	 * @param  array   $args   Parameters.
	 * @param  string  $group  Group Name.
	 * @return null|string
	 */
	protected function get_next_action_by_data( $hook, $timestamp, $args, $group = 'wp-auto-republish' ) {
		return $this->get_next_actions( [
			'hook'         => $hook,
			'args'         => $args,
			'date'         => gmdate( 'U', $timestamp ),
			'date_compare' => '=',
			'group'        => $group,
			'status'       => \ActionScheduler_Store::STATUS_PENDING,
			'per_page'     => 1,
			'orderby'      => 'date',
			'order'        => 'ASC',
		], 'ids' );
	}
}