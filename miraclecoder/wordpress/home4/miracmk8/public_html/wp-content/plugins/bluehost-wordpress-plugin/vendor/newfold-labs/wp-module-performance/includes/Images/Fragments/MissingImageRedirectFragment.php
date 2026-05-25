<?php
/**
 * MissingImageRedirectFragment
 *
 * Renders .htaccess rules that redirect requests for non-existent original
 * raster/vector images to a corresponding `.webp` variant when present.
 * Example: If `/images/hero.jpg` does not exist but `/images/hero.webp` does,
 * rewrite the request to serve `/images/hero.webp`.
 *
 * Uses the centralized Htaccess fragment system to ensure single, debounced writes.
 *
 * @package NewfoldLabs\WP\Module\Performance\Images\Fragments
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\Images\Fragments;

use NewfoldLabs\WP\Module\Htaccess\Fragment;
use NewfoldLabs\WP\Module\Htaccess\Context;

/**
 * Fragment: WebP Missing Image Redirect
 *
 * If the requested original image does not exist but a `.webp` variant exists,
 * this fragment rewrites to the `.webp` file.
 *
 * @since 1.0.0
 */
final class MissingImageRedirectFragment implements Fragment {

	/**
	 * Globally-unique fragment identifier used by the registry.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Human-friendly marker text printed in BEGIN/END comments.
	 *
	 * @var string
	 */
	private $marker_label;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id           Unique fragment ID used by the registry.
	 * @param string $marker_label Marker label shown in BEGIN/END comments.
	 */
	public function __construct( $id, $marker_label ) {
		$this->id           = (string) $id;
		$this->marker_label = (string) $marker_label;
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
	 * Render the .htaccess rules for missing-image → .webp redirects.
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
		$lines[] = "\tRewriteEngine On";
		$lines[] = "\tRewriteCond %{REQUEST_FILENAME} !-f";
		$lines[] = "\tRewriteCond %{REQUEST_FILENAME} !-d";
		$lines[] = "\tRewriteCond %{REQUEST_URI} (.+)\\.(gif|bmp|jpg|jpeg|png|tiff|svg|webp)$ [NC]";
		$lines[] = "\tRewriteCond %{DOCUMENT_ROOT}%1.webp -f";
		$lines[] = "\tRewriteRule ^(.+)\\.(gif|bmp|jpg|jpeg|png|tiff|svg|webp)$ $1.webp [T=image/webp,E=WEBP_REDIRECT:1,L]";
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
