<?php
/**
 * Coming soon service provider.
 *
 * @package NewfoldLabs\WP\Module\ComingSoon
 */

namespace NewfoldLabs\WP\Module\ComingSoon;

/**
 * A service provider class to interact with the coming soon module from the container.
 **/
class Service {
	/**
	 * Enable the coming soon page.
	 *
	 * @return void
	 */
	public function enable( $timestamp = true ) {
		update_option( 'nfd_coming_soon', true );

		if ( $timestamp ) {
			$this->last_changed_timestamp();
		}
	}

	/**
	 * Disable the coming soon page.
	 *
	 * @return void
	 */
	public function disable( $timestamp = true ) {
		update_option( 'nfd_coming_soon', false );

		if ( $timestamp ) {
			$this->last_changed_timestamp();
		}
	}

	/**
	 * Check if the coming soon page is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return true === wp_validate_boolean( get_option( 'nfd_coming_soon', false ) );
	}

	/**
	 * Create/update the last changed timestamp.
	 *
	 * @return void
	 */
	private function last_changed_timestamp() {
		update_option( 'nfd_coming_soon_last_changed', time() );
	}

	/**
	 * Get the last changed timestamp.
	 *
	 * @return int
	 */
	public function get_last_changed_timestamp( $as_date = false ) {
		$timestamp = get_option( 'nfd_coming_soon_last_changed' );

		if ( ! $timestamp ) {
			return false;
		}

		// If requested as date convert and return.
		if ( $as_date ) {
			return date( 'Y-m-d H:i:s', $timestamp );
		}

		return $timestamp;
	}
}