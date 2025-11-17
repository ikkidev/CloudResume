<?php
/**
 * CloudflareFeaturesManager
 *
 * Tracks Cloudflare-related optimization toggles (Polish, Mirage, Fonts) and
 * manages a single .htaccess fragment that sets a sticky cookie indicating the
 * current optimization tuple. Uses the centralized HtaccessApi to ensure safe,
 * debounced writes and conflict-free composition with other fragments.
 *
 * @package NewfoldLabs\WP\Module\Performance\Cloudflare
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\Cloudflare;

use NewfoldLabs\WP\Module\Performance\Fonts\FontSettings;
use NewfoldLabs\WP\Module\Performance\Images\ImageSettings;
use NewfoldLabs\WP\Module\Htaccess\Api as HtaccessApi;
use NewfoldLabs\WP\Module\Performance\Cloudflare\Fragments\CloudflareOptimizationHeaderFragment;

/**
 * Handles detection and tracking of Cloudflare Polish, Mirage, and Font Optimization.
 *
 * Listens to option/transient changes and registers/unregisters a single
 * fragment that sets a cookie reflecting which CF features are enabled.
 *
 * @since 1.0.0
 */
class CloudflareFeaturesManager {

	/**
	 * Human-friendly marker printed in BEGIN/END comments in .htaccess.
	 *
	 * @var string
	 */
	private const MARKER = 'Newfold CF Optimization Header';

	/**
	 * Globally-unique identifier for the Cloudflare optimization header fragment.
	 *
	 * @var string
	 */
	private const FRAGMENT_ID = 'nfd.cloudflare.optimization.header';

	/**
	 * Constructor to register hooks for settings changes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'update_option_nfd_image_optimization', array( $this, 'on_image_optimization_change' ), 10, 2 );
		add_action( 'add_option_nfd_image_optimization', array( $this, 'on_image_optimization_change' ), 10, 2 );
		add_action( 'update_option_nfd_fonts_optimization', array( $this, 'on_fonts_optimization_change' ), 10, 2 );
		add_action( 'add_option_nfd_fonts_optimization', array( $this, 'on_fonts_optimization_change' ), 10, 2 );
		add_action( 'set_transient_nfd_site_capabilities', array( $this, 'on_site_capabilities_change' ), 10, 2 );
	}

	/**
	 * Handles image optimization setting changes.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $old_value Previous value.
	 * @param mixed $new_value New value.
	 * @return void
	 */
	public function on_image_optimization_change( $old_value, $new_value ): void {
		$this->update_htaccess_header( $new_value, get_option( 'nfd_fonts_optimization', array() ) );
	}

	/**
	 * Handles font optimization setting changes.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $old_value Previous value.
	 * @param mixed $new_value New value.
	 * @return void
	 */
	public function on_fonts_optimization_change( $old_value, $new_value ): void {
		$this->update_htaccess_header( get_option( 'nfd_image_optimization', array() ), $new_value );
	}

	/**
	 * Callback for when the `nfd_site_capabilities` transient is set.
	 *
	 * Triggers a refresh of image and font optimization settings based on updated site capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value      The value being set in the transient.
	 * @return void
	 */
	public function on_site_capabilities_change( $value ): void {
		if ( is_array( $value ) ) {
			ImageSettings::maybe_refresh_with_capabilities( $value );
			FontSettings::maybe_refresh_with_capabilities( $value );
			$this->update_htaccess_header( get_option( 'nfd_image_optimization', array() ), get_option( 'nfd_fonts_optimization', array() ) );
		}
	}

	/**
	 * Ensure the fragment reflects current optimization state.
	 *
	 * If no CF features are enabled, the fragment is unregistered. Otherwise,
	 * a deterministic cookie value is computed and a fragment is registered
	 * that sets the cookie when absent/mismatched.
	 *
	 * @since 1.0.0
	 *
	 * @param array $image_settings Array-like image optimization settings.
	 * @param array $fonts_settings Array-like font optimization settings.
	 * @return void
	 */
	private function update_htaccess_header( $image_settings, $fonts_settings ): void {
		$images_cloudflare = isset( $image_settings['cloudflare'] ) ? (array) $image_settings['cloudflare'] : array();
		$fonts_cloudflare  = isset( $fonts_settings['cloudflare'] ) ? (array) $fonts_settings['cloudflare'] : array();

		$mirage_enabled     = ! empty( $images_cloudflare['mirage']['value'] );
		$polish_enabled     = ! empty( $images_cloudflare['polish']['value'] );
		$fonts_enabled_flag = ! empty( $fonts_cloudflare['fonts']['value'] );

		// Build a short, stable header/cookie value for the enabled set.
		$mirage_hash  = $mirage_enabled ? substr( sha1( 'mirage' ), 0, 8 ) : '';
		$polish_hash  = $polish_enabled ? substr( sha1( 'polish' ), 0, 8 ) : '';
		$fonts_hash   = $fonts_enabled_flag ? substr( sha1( 'fonts' ), 0, 8 ) : '';
		$header_value = "{$mirage_hash}{$polish_hash}{$fonts_hash}";

		// Remove any existing fragment first to avoid duplicates.
		HtaccessApi::unregister( self::FRAGMENT_ID );

		// If no features enabled, return.
		if ( '' === $header_value ) {
			return;
		}

		// Register/replace the fragment with the current header value.
		HtaccessApi::register(
			new CloudflareOptimizationHeaderFragment(
				self::FRAGMENT_ID,
				self::MARKER,
				$header_value
			),
			true // queue apply to coalesce writes
		);
	}
}
