<?php
/**
 * BurstSafetyMode\Skip404
 *
 * Reuses the existing Skip404 fragment to register/unregister the .htaccess
 * rules that short-circuit WordPress 404 handling for static-like requests.
 * Uses the centralized HtaccessApi to ensure safe, debounced writes.
 *
 * @package NewfoldLabs\WP\Module\Performance\BurstSafetyMode
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\BurstSafetyMode;

use NewfoldLabs\WP\Module\Htaccess\Api as HtaccessApi;
use NewfoldLabs\WP\Module\Performance\Skip404\Fragments\Skip404Fragment;

/**
 * Skip 404 cache type (Burst Safety Mode).
 *
 * Thin wrapper that reuses the shared Skip404Fragment from the Performance
 * module to manage the same static-file skip rules under Burst Safety Mode.
 *
 * @since 1.0.0
 */
class Skip404 {

	/**
	 * Human-friendly marker shown in BEGIN/END comments inside .htaccess.
	 *
	 * @var string
	 */
	const MARKER = 'Newfold Skip 404 Handling for Static Files';

	/**
	 * Globally-unique fragment identifier used by the registry.
	 *
	 * Reusing the same ID as the primary Skip404 ensures there is a single
	 * source of truth for this block in .htaccess.
	 *
	 * @var string
	 */
	const FRAGMENT_ID = 'nfd.skip404.static';

	/**
	 * Constructor.
	 *
	 * Registers the fragment immediately.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		self::addRules();
	}

	/**
	 * Register (or replace) the shared Skip404 fragment.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function addRules(): void {
		HtaccessApi::register(
			new Skip404Fragment(
				self::FRAGMENT_ID,
				self::MARKER
			),
			true // queue apply to coalesce writes
		);
	}

	/**
	 * Unregister the shared Skip404 fragment.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function removeRules(): void {
		HtaccessApi::unregister( self::FRAGMENT_ID );
	}
}
