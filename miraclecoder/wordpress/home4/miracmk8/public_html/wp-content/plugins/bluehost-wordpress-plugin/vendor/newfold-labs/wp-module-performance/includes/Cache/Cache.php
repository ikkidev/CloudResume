<?php

namespace NewfoldLabs\WP\Module\Performance\Cache;

use NewfoldLabs\WP\ModuleLoader\Container;

use function NewfoldLabs\WP\Module\Performance\get_cache_exclusion;
use function NewfoldLabs\WP\Module\Performance\get_cache_level;

/**
 * Cache manager.
 */
class Cache {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container the container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		$cacheManager = new CacheManager( $container );
		$cachePurger  = new CachePurgingService( $cacheManager->get_instances() );

		$container->set( 'cachePurger', $cachePurger );

		new CacheExclusion( $container );

		$container->set( 'hasMustUsePlugin', file_exists( WPMU_PLUGIN_DIR . '/endurance-page-cache.php' ) );

		$this->hooks();

		add_filter( 'newfold-runtime', array( $this, 'add_to_runtime' ), 100 );
	}

	/**
	 * Add hooks.
	 */
	public function hooks() {
		add_action( 'after_mod_rewrite_rules', array( $this, 'on_rewrite' ) );
	}

	/**
	 * When updating mod rewrite rules, also update our rewrites as appropriate.
	 */
	public function on_rewrite() {
		$this->on_cache_level_change();
	}

	/**
	 * On cache level change, update the response headers.
	 */
	public function on_cache_level_change() {
		// Remove the old option from EPC, if it exists.
		if ( $this->container->get( 'hasMustUsePlugin' ) && absint( get_option( 'endurance_cache_level', 0 ) ) ) {
			update_option( 'endurance_cache_level', 0 );
			delete_option( 'endurance_cache_level' );
		}
	}

	/**
	 * Add to Newfold SDK runtime.
	 *
	 * @param array $sdk SDK data.
	 * @return array SDK data.
	 */
	public function add_to_runtime( $sdk ) {

		$values = array(
			'level'     => get_cache_level(),
			'exclusion' => get_cache_exclusion(),
		);

		return array_merge( $sdk, array( 'cache' => $values ) );
	}
}
