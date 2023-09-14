<?php
/**
 * Helper functions.
 *
 * @since      1.1.8
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class.
 */
trait Ajax {

	/**
	 * Hooks a function on to a specific ajax action
	 *
	 * @param string   $tag             The name of the action to which the $function_to_add is hooked.
	 * @param callable $function_to_add The name of the function you wish to be called.
	 * @param int      $priority        Optional. Used to specify the order in which the functions
	 *                                  associated with a particular action are executed. Default 10.
	 *                                  Lower numbers correspond with earlier execution,
	 *                                  and functions with the same priority are executed
	 *                                  in the order in which they were added to the action.
	 */
	protected function ajax( $tag, $function_to_add, $priority = 10 ) {
		\add_action( 'wp_ajax_wpar_' . $tag, array( $this, $function_to_add ), $priority );
	}

	/**
	 * Verify request nonce
	 *
	 * @param string $action The nonce action name.
	 */
	protected function verify_nonce( $action = 'rvp_admin_nonce' ) {
		if ( ! isset( $_REQUEST['security'] ) || ! \wp_verify_nonce( $_REQUEST['security'], $action ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$this->error( __( 'Error: Nonce verification failed!', 'wp-auto-republish' ) );
		}
	}

	/**
	 * Wrapper function for sending success response
	 *
	 * @param mixed $data Data to send to response.
	 */
	protected function success( $data = null ) {
		$this->send( $data );
	}

	/**
	 * Wrapper function for sending error
	 *
	 * @param mixed $data Data to send to response.
	 */
	protected function error( $data = null ) {
		if ( is_null( $data ) ) {
			$data = __( 'Error: Requested actions failed!', 'wp-auto-republish' );
		}
		$this->send( $data, false );
	}

	/**
	 * Send AJAX response.
	 *
	 * @param array|string   $data    Data to send using ajax.
	 * @param boolean $success Optional. If this is an error. Defaults: true.
	 */
	private function send( $data = null, $success = true, $status_code = null ) {
		if ( ! is_array( $data ) ) {
			$data = $success ? array( 'message' => $data ) : array( 'error' => $data );
		}

		if ( $success ) {
			\wp_send_json_success( $data, $status_code );
		} else {
			\wp_send_json_error( $data, $status_code );
		}
	}
}