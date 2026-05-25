<?php
/**
 * Context for Htaccess composition.
 *
 * Provides environment details that fragments and services can
 * consult when deciding whether to render and how to render.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Context
 *
 * Immutable snapshot of relevant site/server state.
 *
 * @since 1.0.0
 */
class Context {

	/**
	 * Home URL (string, no trailing slash).
	 *
	 * @var string
	 */
	protected $home_url = '';

	/**
	 * Site URL (string, no trailing slash).
	 *
	 * @var string
	 */
	protected $site_url = '';

	/**
	 * Host name (example.com).
	 *
	 * @var string
	 */
	protected $host = '';

	/**
	 * Absolute path to webroot that holds .htaccess.
	 *
	 * @var string
	 */
	protected $home_path = '';

	/**
	 * Whether this is a multisite network.
	 *
	 * @var bool
	 */
	protected $is_multisite = false;

	/**
	 * Whether this request is CLI.
	 *
	 * @var bool
	 */
	protected $is_cli = false;

	/**
	 * Whether this environment looks like Apache-compatible (mod_rewrite available).
	 *
	 * @var bool
	 */
	protected $is_apache_like = true;

	/**
	 * Active plugin basenames for quick checks.
	 *
	 * @var string[]
	 */
	protected $active_plugins = array();

	/**
	 * Arbitrary module settings map (optional).
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Build a Context snapshot from the current WordPress environment.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Optional associative array of module settings to embed.
	 * @return static
	 */
	public static function from_wp( $settings = array() ) {
		$ctx = new static();

		// Ensure get_home_path() is available
		if ( ! function_exists( 'get_home_path' ) && defined( 'ABSPATH' ) ) {
			$maybe = ABSPATH . 'wp-admin/includes/file.php';
			if ( is_readable( $maybe ) ) {
				require_once $maybe;
			}
		}

		// Home/Site URLs.
		if ( function_exists( 'home_url' ) ) {
			$ctx->home_url = untrailingslashit( home_url() );
		}
		if ( function_exists( 'site_url' ) ) {
			$ctx->site_url = untrailingslashit( site_url() );
		}

		// Host.
		if ( '' !== $ctx->home_url ) {
			$hp        = wp_parse_url( $ctx->home_url, PHP_URL_HOST );
			$ctx->host = is_string( $hp ) ? $hp : '';
		}
		if ( '' === $ctx->host && '' !== $ctx->site_url ) {
			$hp        = wp_parse_url( $ctx->site_url, PHP_URL_HOST );
			$ctx->host = is_string( $hp ) ? $hp : '';
		}

		// Home path (with ABSPATH fallback)
		if ( function_exists( 'get_home_path' ) ) {
			$hp = get_home_path();
			if ( is_string( $hp ) && '' !== $hp ) {
				$ctx->home_path = rtrim( $hp, "/\\ \t\n\r\0\x0B" );
			}
		}
		if ( '' === $ctx->home_path && defined( 'ABSPATH' ) ) {
			$ctx->home_path = rtrim( ABSPATH, "/\\ \t\n\r\0\x0B" );
		}

		// Multisite flag.
		$ctx->is_multisite = function_exists( 'is_multisite' ) ? (bool) is_multisite() : false;

		// CLI flag.
		$ctx->is_cli = ( defined( 'WP_CLI' ) && WP_CLI );

		// Rewrite/server capability
		$ctx->is_apache_like = $ctx->detect_rewrite_capability();

		// Active plugins (network-aware).
		$ctx->active_plugins = $ctx->detect_active_plugins();

		// Settings.
		if ( is_array( $settings ) ) {
			$ctx->settings = $settings;
		}

		return $ctx;
	}

	/**
	 * Detect active plugins including network-activated ones.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Array of plugin basenames.
	 */
	protected function detect_active_plugins() {
		$list = array();

		$active = array();
		if ( function_exists( 'get_option' ) ) {
			$active = get_option( 'active_plugins', array() );
			if ( ! is_array( $active ) ) {
				$active = array();
			}
		}

		$network_active = array();
		if ( $this->is_multisite && function_exists( 'get_site_option' ) ) {
			$network_active = get_site_option( 'active_sitewide_plugins', array() );
			if ( is_array( $network_active ) ) {
				$network_active = array_keys( $network_active );
			} else {
				$network_active = array();
			}
		}

		$merged = array_unique( array_merge( $active, $network_active ) );
		foreach ( $merged as $basename ) {
			if ( is_string( $basename ) && '' !== $basename ) {
				$list[] = $basename;
			}
		}

		return $list;
	}

	/**
	 * Whether a given plugin is active (basename match).
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_basename Plugin basename, e.g. 'endurance-page-cache/endurance-page-cache.php'.
	 * @return bool
	 */
	public function is_plugin_active( $plugin_basename ) {
		return in_array( (string) $plugin_basename, $this->active_plugins, true );
	}

	/**
	 * Get a setting value by key with optional fallback.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $fallback Default if not found.
	 * @return mixed
	 */
	public function setting( $key, $fallback = null ) {
		$key = (string) $key;
		if ( is_array( $this->settings ) && array_key_exists( $key, $this->settings ) ) {
			return $this->settings[ $key ];
		}
		return $fallback;
	}

	/**
	 * Get the site home URL (without trailing slash).
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 * This usually corresponds to {@see home_url()} but normalized and cached.
	 *
	 * @since 1.0.0
	 *
	 * @return string Home URL string (no trailing slash).
	 */
	public function home_url() {
		return $this->home_url;
	}

	/**
	 * Get the site URL (without trailing slash).
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 * This usually corresponds to {@see site_url()} but normalized and cached.
	 *
	 * @since 1.0.0
	 *
	 * @return string Site URL string (no trailing slash).
	 */
	public function site_url() {
		return $this->site_url;
	}

	/**
	 * Get the host name (example.com).
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return string Host name or empty string.
	 */
	public function host() {
		return $this->host;
	}

	/**
	 * Get the absolute filesystem path to the webroot that holds .htaccess.
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return string Absolute path or empty string.
	 */
	public function home_path() {
		return $this->home_path;
	}

	/**
	 * Whether this is a multisite network.
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_multisite() {
		return $this->is_multisite;
	}

	/**
	 * Whether this request is running in WP-CLI.
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_cli() {
		return $this->is_cli;
	}

	/**
	 * Whether this environment appears to support Apache/mod_rewrite-like capabilities.
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_apache_like() {
		return $this->is_apache_like;
	}

	/**
	 * Get the list of active plugin basenames.
	 *
	 * Snapshot value captured when the Context was created via from_wp().
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Array of plugin basenames.
	 */
	public function active_plugins() {
		return $this->active_plugins;
	}

	/**
	 * Detect whether the server environment supports mod_rewrite-like capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function detect_rewrite_capability() {
		if ( function_exists( 'got_mod_rewrite' ) ) {
			return (bool) got_mod_rewrite();
		}
		$ss = isset( $_SERVER['SERVER_SOFTWARE'] ) ? strtolower( (string) $_SERVER['SERVER_SOFTWARE'] ) : '';
		if ( false !== strpos( $ss, 'nginx' ) ) {
			return false;
		}
		if ( false !== strpos( $ss, 'apache' ) ) {
			return true;
		}
		if ( false !== strpos( $ss, 'litespeed' ) || false !== strpos( $ss, 'openlitespeed' ) ) {
			return true;
		}
		if ( false !== strpos( $ss, 'iis' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get the full filesystem path to the .htaccess file.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path including filename, or empty string if home_path is not set.
	 */
	public function htaccess_path() {
		if ( '' === $this->home_path ) {
			return '';
		}
		return $this->home_path . DIRECTORY_SEPARATOR . '.htaccess';
	}

	/**
	 * Export context as an associative array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'home_url'       => $this->home_url,
			'site_url'       => $this->site_url,
			'host'           => $this->host,
			'home_path'      => $this->home_path,
			'is_multisite'   => $this->is_multisite,
			'is_cli'         => $this->is_cli,
			'is_apache_like' => $this->is_apache_like,
			'active_plugins' => $this->active_plugins,
			'settings'       => $this->settings,
		);
	}
}
