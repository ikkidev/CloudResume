<?php

namespace NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments;

use NewfoldLabs\WP\Module\Htaccess\Fragment;
use NewfoldLabs\WP\Module\Htaccess\Context;
use NewfoldLabs\WP\Module\Performance\Cache\Types\Browser;

/**
 * Fragment: Browser Cache Rules
 *
 * Renders cache-control and expires headers into .htaccess
 * based on the configured cache level and optional URI exclusions.
 *
 * This fragment is exclusive and runs after the core WordPress block.
 *
 * @package NewfoldLabs\WP\Module\Performance\Cache\Types\Fragments
 * @since 1.0.0
 */
final class BrowserCacheFragment implements Fragment {
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
	 * Current cache level (1–3). Level 0 is handled by unregistering the fragment.
	 *
	 * @var int
	 */
	private $cache_level;

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
	 * @param string $id                Unique fragment ID.
	 * @param string $marker_label      Marker label for readability in the file.
	 * @param int    $cache_level       Cache level (1–3). Higher = longer TTLs.
	 * @param string $exclusion_pattern Pipe-separated pattern to exclude, or empty string.
	 */
	public function __construct( $id, $marker_label, $cache_level, $exclusion_pattern = '' ) {
		$this->id                = (string) $id;
		$this->marker_label      = (string) $marker_label;
		$this->cache_level       = (int) $cache_level;
		$this->exclusion_pattern = (string) $exclusion_pattern;
	}

	/**
	 * Unique ID for this fragment.
	 *
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Execution priority relative to other fragments.
	 * Runs after the WordPress core rules.
	 *
	 * @return int
	 */
	public function priority() {
		return self::PRIORITY_POST_WP;
	}

	/**
	 * Whether this fragment is exclusive (single instance in output).
	 *
	 * @return bool
	 */
	public function exclusive() {
		return true;
	}

	/**
	 * Whether this fragment is enabled for the given context.
	 * Upper-layer logic (Browser::maybeAddRules) registers/unregisters this,
	 * so this always returns true once instantiated.
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return bool
	 */
	public function is_enabled( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return true;
	}

	/**
	 * Render the htaccess block.
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return string Rendered fragment text including BEGIN/END comments.
	 */
	public function render( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$expirations = Browser::getFileTypeExpirations( $this->cache_level );

		$lines   = array();
		$lines[] = '# BEGIN ' . $this->marker_label;
		$lines[] = '<IfModule mod_expires.c>';
		$lines[] = "\tExpiresActive On";

		foreach ( $expirations as $type => $ttl ) {
			if ( 'default' === $type ) {
				$lines[] = "\tExpiresDefault \"access plus {$ttl}\"";
			} else {
				$lines[] = "\tExpiresByType {$type} \"access plus {$ttl}\"";
			}
		}

		$lines[] = '</IfModule>';

		// Optional cache-exclusion rules.
		if ( '' !== $this->exclusion_pattern ) {
			$lines[] = '<IfModule mod_rewrite.c>';
			$lines[] = 'RewriteEngine On';
			$lines[] = "RewriteCond %{REQUEST_URI} ^/({$this->exclusion_pattern}) [NC]";
			$lines[] = '<IfModule mod_headers.c>';
			$lines[] = 'Header set Cache-Control "no-cache, no-store, must-revalidate"';
			$lines[] = 'Header set Pragma "no-cache"';
			$lines[] = 'Header set Expires 0';
			$lines[] = '</IfModule>';
			$lines[] = '</IfModule>';
		}

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
