<?php

namespace NewfoldLabs\WP\Module\Performance\HealthChecks;

/**
 * Health check for persistent object cache.
 */
class PersistentObjectCacheHealthCheck extends HealthCheck {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id           = 'persistent_object_cache'; // Same as the core ID so that we can override the core health check.
		$this->title        = esc_html__( 'Object Caching', 'wp-module-performance' );
		$this->passing_text = esc_html__( 'Object caching is enabled', 'wp-module-performance' );
		$this->failing_text = esc_html__( 'Object caching is disabled', 'wp-module-performance' );
		$this->description  = esc_html__( 'Object caching saves results from frequent database queries, reducing load times by avoiding repetitive query processing. Object caching is available in all tiers of Bluehost Cloud.', 'wp-module-performance' );
	}

	/**
	 * Test the object cache.
	 *
	 * @return bool
	 */
	public function test() {
		return wp_using_ext_object_cache();
	}
}
