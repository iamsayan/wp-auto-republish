<?php
/**
 * The Metadata and Options.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage Wpar\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace Wpar\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Meta & Option class.
 */
trait SettingsData
{
	/**
	 * Get meta by post id.
	 *
	 * @param int    $post_id     Post id for destination where to save.
	 * @param string $key         The meta key to retrieve. If no key is provided, fetches all metadata.
	 * @param bool   $single      Whether to return a single value.
	 *
	 * @return mixed
	 */
    protected function get_meta( $post_id, $key, $single = true ) {
		return \get_post_meta( $post_id, $key, $single );
	}

	/**
	 * Add new meta by post id.
	 *
	 * @param int    $post_id     Post id for destination where to save.
	 * @param string $key         Metadata key.
	 * @param mixed  $value       Metadata value.
	 *
	 * @return mixed
	 */
	protected function add_meta( $post_id, $key, $value ) {
		return \add_post_meta( $post_id, $key, $value );
	}

	/**
	 * Update meta by post id.
	 *
	 * @param int    $post_id     Post id for destination where to save.
	 * @param string $key         Metadata key.
	 * @param mixed  $value       Metadata value.
	 *
	 * @return mixed
	 */
	protected function update_meta( $post_id, $key, $value ) {
		return \update_post_meta( $post_id, $key, $value );
	}

	/**
	 * delete meta by post id.
	 *
	 * @param int    $post_id     Post id for destination where to save.
	 * @param string $key         Metadata key.
	 * @param mixed  $value       Metadata value.
	 *
	 * @return mixed
	 */
	protected function delete_meta( $post_id, $key, $value = '' ) {
		return \delete_post_meta( $post_id, $key, $value );
	}

	/**
	 * retreive plugin data.
	 *
	 * @param string $key         Option key.
	 * @param mixed  $value       Default value
	 *
	 * @return mixed
	 */
	protected function get_data( $key, $default = false ) {
		$settings = get_option( 'wpar_plugin_settings' );

		return ( isset( $settings[ $key ] ) && ! empty( $settings[ $key ] ) ) ? $settings[ $key ] : $default;
	}

	/**
	 * retreive plugin data.
	 *
	 * @param string $key         Option key.
	 * @param mixed  $value       Default value
	 *
	 * @return mixed
	 */
	protected function build_meta( $post_id, $meta_key, $default = false, $maybe_unserialize = false, $single = true ) {
		$meta = $this->get_meta( $post_id, $meta_key, $single );
		if ( $maybe_unserialize ) {
			$meta = maybe_unserialize( $meta );
		}

		return ( ! empty( $meta ) ) ? $meta : $default;
	}
}