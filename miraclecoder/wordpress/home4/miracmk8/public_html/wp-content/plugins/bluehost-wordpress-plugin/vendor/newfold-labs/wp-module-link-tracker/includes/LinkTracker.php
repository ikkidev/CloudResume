<?php

namespace NewfoldLabs\WP\Module\LinkTracker;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Manages all the functionalities for the module.
 */
class LinkTracker {
	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor for the LinkTracker class.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( Container $container ) {

		$this->container = $container;
	}

	/**
	 * Adds hooks for the module.
	 *
	 * This method registers the necessary hooks for the link tracker module,
	 * specifically for enqueuing scripts in the admin area.
	 */
	public function add_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
		add_filter( 'nfd_build_url', array( $this, 'build_url' ), 10, 2 );
	}

	/**
	 * Enqueues the JavaScript file for the link tracker.
	 *
	 * This method registers and enqueues the JavaScript file that will handle
	 * the link tracking functionality on the front end.
	 */
	public function enqueue_scripts() {

		$asset_file = NFD_LINK_TRACKER_BUILD_DIR . '/index.asset.php';
		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;
		} else {
			return;
		}

		wp_register_script(
			'wp-module-link-tracker',
			NFD_LINK_TRACKER_BUILD_URL . '/index.js',
			array_merge( $asset['dependencies'], array( 'nfd-runtime' ) ),
			$asset['version'],
			true
		);

		wp_enqueue_script( 'wp-module-link-tracker' );
	}

	/**
	 * Builds a URL with tracking parameters.
	 *
	 * This method constructs a URL with default and provided query parameters,
	 * ensuring that the URL is properly formatted and includes necessary tracking
	 * information.
	 *
	 * @param string $url    The base URL to which parameters will be added.
	 * @param array  $params Optional. Additional parameters to include in the URL.
	 *                       Default is an empty array.
	 *
	 * @return string The constructed URL with query parameters.
	 */
	public function build_url( string $url, $params = array() ) {

		$container = $this->container;

		$source = false;

		if ( ! empty( $params['source'] ) ) {
			$source = $params['source'];
			unset( $params['source'] );
		}

		$parts = wp_parse_url( $url );

		$query_params = array();
		if ( isset( $parts['query'] ) ) {
			parse_str( $parts['query'], $query_params );
		}

		$default_params = array(
			'channelid'  => strpos( $url, 'wp-admin' ) !== false ? 'P99C100S1N0B3003A151D115E0000V111' : 'P99C100S1N0B3003A151D115E0000V112',
			'utm_medium' => $container ? $container->plugin()->get( 'id', 'bluehost' ) . '_plugin' : 'bluehost_plugin',
			'utm_source' => ( $_SERVER['PHP_SELF'] ?? '' ) . ( $source ? '?' . $source : false ),
		);

		foreach ( $default_params as $key => $value ) {
			if ( ! array_key_exists( $key, $query_params ) ) {
				$query_params[ $key ] = $value;
			}
		}
		// Merge the default parameters with the provided parameters and clean the empty parameters.
		$query_params = array_filter(
			! empty( $params ) ? array_merge( $query_params, $params ) : $query_params,
			function ( $value ) {
				return null !== $value && '' !== $value && false !== $value;
			}
		);
		// Build the final URL with the query parameters.
		$base = ( isset( $parts['scheme'] ) ? $parts['scheme'] . '://' : '' ) .
			( isset( $parts['host'] ) ? $parts['host'] : '' ) .
			( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) .
			( isset( $parts['path'] ) ? $parts['path'] : '' );

		// If the original URL has a fragment, append it to the final URL.
		$fragment = ( isset( $parts['fragment'] ) ? '#' . $parts['fragment'] : '' );

		return $base . '?' . http_build_query( $query_params, '', '&' ) . $fragment;
	}
}
