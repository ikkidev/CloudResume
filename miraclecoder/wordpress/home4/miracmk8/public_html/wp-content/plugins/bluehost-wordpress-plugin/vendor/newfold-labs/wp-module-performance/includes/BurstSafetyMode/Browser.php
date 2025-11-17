<?php
/**
 * BurstSafetyMode\Browser
 *
 * Reuses the shared BrowserCacheFragment to emit browser caching rules during
 * Burst Safety Mode. Registers a fragment with a fixed cache level defined by
 * BURST_SAFETY_CACHE_LEVEL, and keeps the same human-readable marker for parity.
 *
 * Uses the centralized HtaccessApi to ensure safe, debounced writes.
 *
 * @package NewfoldLabs\WP\Module\Performance\BurstSafetyMode
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\BurstSafetyMode;

use NewfoldLabs\WP\Module\Htaccess\Api as HtaccessApi;
use NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments\BrowserCacheFragment;

/**
 * Browser cache (Burst Safety Mode).
 *
 * Thin wrapper that registers the standard BrowserCacheFragment with a
 * burst-mode cache level, and sets an identifying response header.
 *
 * @since 1.0.0
 */
class Browser {

	/**
	 * Human-friendly marker label used in BEGIN/END comments.
	 *
	 * @var string
	 */
	const MARKER = 'Newfold Browser Cache';

	/**
	 * Globally-unique fragment identifier (same as the primary Browser cache).
	 *
	 * Reusing the same ID ensures only a single Browser cache block exists in
	 * .htaccess. Last registration wins and replaces the prior one.
	 *
	 * @var string
	 */
	const FRAGMENT_ID = 'nfd.cache.browser';

	/**
	 * Constructor.
	 *
	 * Sets an identifying response header and registers the fragment using the
	 * burst safety cache level. Exclusions are not applied in burst mode.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		self::addRules();
	}

	/**
	 * Register (or replace) the Browser cache fragment with burst cache level.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function addRules(): void {
		HtaccessApi::register(
			new BrowserCacheFragment(
				self::FRAGMENT_ID,
				self::MARKER,
				(int) BURST_SAFETY_CACHE_LEVEL, // cache level (1–3)
				''                               // no exclusion pattern in burst mode
			),
			true // queue apply to coalesce writes
		);
	}

	/**
	 * Unregister the Browser cache fragment.
	 *
	 * Provided for symmetry; call when exiting burst mode.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function removeRules(): void {
		HtaccessApi::unregister( self::FRAGMENT_ID );
	}
}
