<?php
/**
 * The file for Cron Health check.
 *
 * @since      1.2.2
 * @package    RevivePress
 * @subpackage RevivePress\Tools
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

// namespace RevivePress\Tools;

// use RevivePress\Helpers\Hooker;
// use RevivePress\Helpers\Schedular;
// use RevivePress\Helpers\HelperFunctions;

// defined( 'ABSPATH' ) || exit;

// /**
//  * Health check class.
//  */
// class HealthChecker
// {
// 	use HelperFunctions, Hooker, Schedular;

// 	/**
// 	 * Register functions.
// 	 */
// 	public function register() {
// 		$this->action( 'init', 'generate_task' );
// 		$this->action( 'wpar/process_health_check', 'do_health_check' );
// 	}

// 	/**
// 	 * Initialize health check tasks.
// 	 */
// 	public function generate_task() {
// 		if ( ! $this->has_next_action( 'wpar/process_health_check' ) ) {
// 			$this->set_recurring_action( time(), DAY_IN_SECONDS, 'wpar/process_health_check' );
// 		}
// 	}

//     /**
// 	 * Run the event once.
// 	 */
// 	public function do_health_check() {
// 		// global republish
// 		// $this->regenerate_task( $this->get_data( 'wpar_post_types', [ 'post' ] ) );
// 		// $this->remove_metas( $this->get_data( 'wpar_post_types', [ 'post' ] ) );

// 		// if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 		//     // single republish
// 		//     $this->regenerate_task( $this->get_data( 'post_types_list_single', [ 'post' ] ), true );
// 		//     $this->remove_metas( $this->get_data( 'post_types_list_single', [ 'post' ] ), true );
// 		// }
// 	}

// 	/**
// 	 * Re-Generate missed events.
// 	 * 
// 	 * @param array   $post_types  Available Post Types
// 	 * @param string  $type        Cron Type
// 	 * @param bool    $single      Single Cron
// 	 */
// 	private function regenerate_task( $post_types, $single = false ) {
// 		$type = $single ? 'single' : 'global';
// 		$key = '_wpar_global_republish_datetime';
		
// 		if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 	    	if ( $type == 'single' ) {
// 	    		$key = '_wpar_repost_schedule_datetime';
// 	    	}
// 	    }

// 		$args = [
// 			'post_type'   => array_keys( $this->get_post_types() ),
// 			'numberposts' => -1,
// 			'post_status' => 'publish',
// 			'fields'      => 'ids',
// 			'meta_query'  => [
// 				'relation' => 'AND',
// 				[
// 				    'key'     => 'wpar_' . $type . '_republish_status',
//     			    'compare' => 'EXISTS',
// 			    ],
// 				[
// 					'key'     => $key,
// 					'value'   => current_time( 'mysql' ),
// 					'compare' => '>',
// 					'type'    => 'DATETIME',
// 				],
// 			],
// 		];

// 		$args = $this->do_filter( $type . '_health_check_args', $args );

// 		$posts = get_posts( $args );
// 		if ( ! empty( $posts ) ) {
// 			foreach ( $posts as $post_id ) {
// 				if ( ! $single ) {
// 					// check if global cron event is not exists
// 					if ( ! $this->get_next_action( 'wpar/global_republish_single_post', [ $post_id ] ) ) {
//                         // get republish time from post meta
// 						$datetime = $this->get_meta( $post_id, '_wpar_global_republish_datetime' );

// 						// schedule single post republish event
// 						$this->set_single_action( get_gmt_from_date( $datetime, 'U' ), 'wpar/global_republish_single_post', [ $post_id ] );
// 					}
// 				}

// 				if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 			    	if ( $single ) {
// 			    		// check if single cron event is not exists
// 			    		if ( ! $this->get_next_action( 'wpar/run_single_republish', [ $post_id ] ) ) {
// 			    			// get republish time from post meta
// 			    			$datetime = $this->get_meta( $post_id, '_wpar_repost_schedule_datetime' );
    
// 			    			// schedule single post republish event
// 			    			$this->set_single_action( get_gmt_from_date( $datetime, 'U' ), 'wpar/run_single_republish', [ $post_id ] );
// 			    		}
// 			        }
// 			    }
// 			}
// 		}
// 	}

// 	/**
// 	 * Delete missed events post metas and publish them.
// 	 * 
// 	 * @param array   $post_types  Available Post Types
// 	 * @param string  $type        Action Type
// 	 * @param bool    $single      Single Event
// 	 */
// 	private function remove_metas( $post_types, $single = false ) {
// 		$type = $single ? 'single' : 'global';
// 		$key = '_wpar_global_republish_datetime';
// 		if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 	    	if ( $type == 'single' ) {
// 	    		$key = '_wpar_repost_schedule_datetime';
// 	    	}
// 	    }

// 		$args = [
// 			'post_type'   => $post_types,
// 			'numberposts' => -1,
// 			'post_status' => 'publish',
// 			'fields'      => 'ids',
// 			'meta_query'  => [
// 				'relation' => 'AND',
// 				[
// 				    'key'     => 'wpar_' . $type . '_republish_status',
//     			    'compare' => 'EXISTS',
// 			    ],
// 				[
// 					'key'     => $key,
// 					'value'   => current_time( 'mysql' ),
// 					'compare' => '<=',
// 					'type'    => 'DATETIME',
// 				],
// 			],
// 		];

// 		$args = $this->do_filter( $type . '_remove_metas_args', $args );

// 		//error_log( print_r( $args, true ) );
	
// 		$posts = get_posts( $args );
// 		if ( ! empty( $posts ) ) {
// 			foreach ( $posts as $post_id ) {
// 				if ( ! $single ) {
// 					// check if global cron event is not exists
// 					if ( ! $this->get_next_action( 'wpar/global_republish_single_post', [ $post_id ] ) ) {
// 						// delete old post meta
// 						$this->delete_meta( $post_id, 'wpar_global_republish_status' );
// 						$this->delete_meta( $post_id, '_wpar_global_republish_datetime' );
						
// 						if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 						    $this->delete_meta( $post_id, 'wpar_filter_republish_status' );
// 						    $this->delete_meta( $post_id, '_wpar_filter_republish_datetime' );
// 						}
//                     }
// 				}

// 				if ( wpar_load_fs_sdk()->can_use_premium_code__premium_only() ) {
// 				    if ( $single ) {
// 				    	// check if single cron event is not exists
// 				    	if ( ! $this->get_next_action( 'wpar/run_single_republish', [ $post_id ] ) ) {
// 				    		// delete old post meta
// 				    		$this->delete_meta( $post_id, '_wpar_repost_schedule_datetime' );
// 				    		$this->delete_meta( $post_id, '_wpar_filter_republish_datetime' );
    
// 				    		// immediate republish of post
// 				    		$this->do_action( 'republish_single_post', $post_id );
// 				    	}
// 			        }
// 				}
// 			}    
// 		}
// 	}
// }