<?php

namespace NewfoldLabs\WP\Module\Performance\Cache\Types;

use NewfoldLabs\WP\Module\Performance\OptionListener;
use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Performance\Cache\CacheExclusion;
use NewfoldLabs\WP\Module\Performance\Cache\CacheManager;
use NewfoldLabs\WP\Module\Htaccess\Api as HtaccessApi;
use NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments\BrowserCacheFragment;

use function NewfoldLabs\WP\Module\Performance\get_cache_level;

/**
 * Browser cache type.
 *
 * Migrated to new Htaccess Fragment approach:
 *   - Writes are performed by registering/unregistering a fragment.
 *   - Content is rendered by the BrowserCacheFragment class.
 *
 * @package NewfoldLabs\WP\Module\Performance\Cache\Types
 * @since 1.0.0
 */
class Browser extends CacheBase {

	/**
	 * Human-friendly marker label used in BEGIN/END comments rendered
	 * by the fragment. Preserved for readability and parity.
	 *
	 * @var string
	 */
	const MARKER = 'Newfold Browser Cache';

	/**
	 * Registry identifier for this fragment.
	 * Must be globally unique across fragments.
	 *
	 * @var string
	 */
	const FRAGMENT_ID = 'nfd.cache.browser';

	/**
	 * Whether or not the code for this cache type should be loaded.
	 *
	 * @param Container $container Dependency injection container.
	 * @return bool
	 */
	public static function should_enable( Container $container ) {
		return (bool) $container->has( 'isApache' ) && $container->get( 'isApache' );
	}

	/**
	 * Constructor.
	 *
	 * Registers option listeners and filters that keep the fragment in sync
	 * with cache level and exclusion changes.
	 */
	public function __construct() {
		new OptionListener( CacheManager::OPTION_CACHE_LEVEL, array( __CLASS__, 'maybeAddRules' ) );
		new OptionListener( CacheExclusion::OPTION_CACHE_EXCLUSION, array( __CLASS__, 'exclusionChange' ) );

		add_filter( 'newfold_update_htaccess', array( $this, 'on_rewrite' ) );
	}

	/**
	 * When updating .htaccess, also update our rules as appropriate.
	 *
	 * @return void
	 */
	public function on_rewrite() {
		self::maybeAddRules( get_cache_level() );
	}

	/**
	 * Handle exclusion option change: refresh the fragment.
	 *
	 * @return void
	 */
	public static function exclusionChange() {
		self::maybeAddRules( get_cache_level() );
	}

	/**
	 * Determine whether to add or remove rules based on caching level.
	 *
	 * @param int|null $cache_level The caching level.
	 * @return void
	 */
	public static function maybeAddRules( $cache_level ) {
		absint( $cache_level ) > 0 ? self::addRules( $cache_level ) : self::removeRules();
	}

	/**
	 * Remove our rules by unregistering the fragment.
	 *
	 * @return void
	 */
	public static function removeRules() {
		HtaccessApi::unregister( self::FRAGMENT_ID );
	}

	/**
	 * Add (or replace) our rules by registering a fragment.
	 *
	 * @param int $cache_level The caching level (1–3).
	 * @return void
	 */
	public static function addRules( $cache_level ) {

		// Build exclusion pattern (same logic as before).
		$exclusion_pattern = '';
		$cache_exclusion   = get_option( CacheExclusion::OPTION_CACHE_EXCLUSION, '' );

		if ( is_string( $cache_exclusion ) && '' !== $cache_exclusion ) {
			$parts             = array_map( 'trim', explode( ',', sanitize_text_field( $cache_exclusion ) ) );
			$exclusion_pattern = implode( '|', array_filter( $parts ) );
		}

		// Register (or replace) a fragment with the current settings.
		HtaccessApi::register(
			new BrowserCacheFragment(
				self::FRAGMENT_ID,
				self::MARKER,
				absint( $cache_level ),
				$exclusion_pattern
			),
			true // queue apply
		);
	}

	/**
	 * Get the filetype expirations based on the current caching level.
	 *
	 * @param int $cache_level The caching level.
	 * @return array<string,string> Map of mime-type => TTL (human string).
	 */
	public static function getFileTypeExpirations( int $cache_level ) {

		switch ( $cache_level ) {
			case 3:
				return array(
					'default'         => '1 week',
					'text/html'       => '8 hours',
					'image/jpg'       => '1 week',
					'image/jpeg'      => '1 week',
					'image/gif'       => '1 week',
					'image/png'       => '1 week',
					'image/webp'      => '1 week',
					'text/css'        => '1 week',
					'text/javascript' => '1 week',
					'application/pdf' => '1 month',
					'image/x-icon'    => '1 year',
				);

			case 2:
				return array(
					'default'         => '24 hours',
					'text/html'       => '2 hours',
					'image/jpg'       => '24 hours',
					'image/jpeg'      => '24 hours',
					'image/gif'       => '24 hours',
					'image/png'       => '24 hours',
					'image/webp'      => '24 hours',
					'text/css'        => '24 hours',
					'text/javascript' => '24 hours',
					'application/pdf' => '1 week',
					'image/x-icon'    => '1 year',
				);

			case 1:
				return array(
					'default'         => '5 minutes',
					'text/html'       => '0 seconds',
					'image/jpg'       => '1 hour',
					'image/jpeg'      => '1 hour',
					'image/gif'       => '1 hour',
					'image/png'       => '1 hour',
					'image/webp'      => '1 hour',
					'text/css'        => '1 hour',
					'text/javascript' => '1 hour',
					'application/pdf' => '6 hours',
					'image/x-icon'    => '1 year',
				);

			default:
				return array();
		}
	}

	/**
	 * Handle activation logic.
	 *
	 * @return void
	 */
	public static function on_activation() {
		self::maybeAddRules( get_cache_level() );
	}

	/**
	 * Handle deactivation logic.
	 *
	 * @return void
	 */
	public static function on_deactivation() {
		self::removeRules();
	}
}
