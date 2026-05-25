<?php

namespace NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments;

use NewfoldLabs\WP\Module\Htaccess\Fragment;
use NewfoldLabs\WP\Module\Htaccess\Context;

/**
 * Fragment: File Cache Rewrite Rules
 *
 * Serves statically cached pages from wp-content/newfold-page-cache
 * under strict conditions (method, cookies, query string, headers).
 * Optional URI exclusion support to bypass cached responses.
 *
 * This fragment is exclusive and runs after the core WordPress block.
 *
 * @package NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments
 * @since 1.0.0
 */
final class FileCacheFragment implements Fragment {

	/**
	 * Globally-unique fragment identifier used by the Registry.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Human-friendly marker label printed in BEGIN/END comments.
	 *
	 * @var string
	 */
	private $marker_label;

	/**
	 * Site base path (parsed from home_url('/')).
	 *
	 * @var string
	 */
	private $base_path;

	/**
	 * Relative cache directory path from the docroot, beginning with "/".
	 * Example: "/wp-content/newfold-page-cache"
	 *
	 * @var string
	 */
	private $rel_cache_path;

	/**
	 * Optional pipe-separated set of URI path prefixes to exclude from caching.
	 * Example: "wp-admin|checkout|cart"
	 *
	 * @var string
	 */
	private $exclusion_pattern;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id                Unique fragment ID used by the registry.
	 * @param string $marker_label      Human-friendly label shown in BEGIN/END markers.
	 * @param string $base_path         Site base path from home_url('/'), e.g. "/".
	 * @param string $rel_cache_path    Relative cache path from docroot, e.g. "/wp-content/newfold-page-cache".
	 * @param string $exclusion_pattern Optional pipe-separated URI prefixes to exclude; empty string to disable.
	 */
	public function __construct( $id, $marker_label, $base_path, $rel_cache_path, $exclusion_pattern = '' ) {
		$this->id                = (string) $id;
		$this->marker_label      = (string) $marker_label;
		$this->base_path         = (string) $base_path;
		$this->rel_cache_path    = rtrim( (string) $rel_cache_path, '/' );
		$this->exclusion_pattern = (string) $exclusion_pattern;
	}

	/**
	 * Get the unique fragment ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string Fragment identifier.
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Get the execution priority relative to other fragments.
	 *
	 * Runs after the core WordPress rules.
	 *
	 * @since 1.0.0
	 *
	 * @return int Priority constant.
	 */
	public function priority() {
		return self::PRIORITY_POST_WP;
	}

	/**
	 * Whether this fragment is exclusive (only a single instance may render).
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if exclusive.
	 */
	public function exclusive() {
		return true;
	}

	/**
	 * Whether this fragment is enabled for the given context.
	 *
	 * Upper-layer logic (register/unregister) controls enablement; once
	 * instantiated, this always returns true.
	 *
	 * @since 1.0.0
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return bool True when enabled.
	 */
	public function is_enabled( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return true;
	}

	/**
	 * Render the .htaccess rules for serving file-cached pages.
	 *
	 * @since 1.0.0
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return string Rendered fragment including BEGIN/END comments.
	 */
	public function render( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$lines   = array();
		$lines[] = '# BEGIN ' . $this->marker_label;
		$lines[] = '<IfModule mod_rewrite.c>';
		$lines[] = 'RewriteEngine On';
		$lines[] = 'RewriteBase ' . $this->base_path;

		// Prevent loops: don’t recurse into the cache directory itself.
		$lines[] = 'RewriteRule ^' . ltrim( $this->rel_cache_path, '/' ) . '/ - [L]';

		// Optional URI exclusions (prefix match).
		if ( '' !== $this->exclusion_pattern ) {
			$lines[] = 'RewriteCond %{REQUEST_URI} !^/(' . $this->exclusion_pattern . ') [NC]';
		}

		// Respect request method, query string, cookies, and cache-control header.
		$lines[] = 'RewriteCond %{REQUEST_METHOD} !POST';
		$lines[] = 'RewriteCond %{QUERY_STRING} !.*=.*';
		$lines[] = 'RewriteCond %{HTTP_COOKIE} !(wordpress_test_cookie|comment_author|wp\-postpass|wordpress_logged_in|wptouch_switch_toggle|wp_woocommerce_session_) [NC]';
		$lines[] = 'RewriteCond %{HTTP:Cache-Control} ^((?!no-cache).)*$';

		// Serve the cached file when it exists.
		$lines[] = 'RewriteCond %{DOCUMENT_ROOT}' . $this->rel_cache_path . '/$1/_index.html -f';
		$lines[] = 'RewriteRule ^(.*)$ ' . $this->rel_cache_path . '/$1/_index.html [L]';
		$lines[] = '</IfModule>';
		$lines[] = '# END ' . $this->marker_label;

		return implode( "\n", $lines );
	}

	/**
	 * Optional regex patches (none for this fragment).
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return array
	 */
	public function patches( $context ) {
		return array();
	}
}
