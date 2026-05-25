<?php
/**
 * CloudflareOptimizationHeaderFragment
 *
 * Emits .htaccess rules that set a durable cookie encoding the tuple of
 * enabled Cloudflare optimizations (e.g., Mirage/Polish/Fonts). The cookie
 * is set only when absent or mismatched, and is skipped for admin/API routes.
 *
 * @package NewfoldLabs\WP\Module\Performance\Cloudflare\Fragments
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\Cloudflare\Fragments;

use NewfoldLabs\WP\Module\Htaccess\Fragment;
use NewfoldLabs\WP\Module\Htaccess\Context;

/**
 * Fragment: Cloudflare Optimization Header
 *
 * Sets a cookie `nfd-enable-cf-opt=<hash>` (Max-Age=86400, HttpOnly) to signal
 * which Cloudflare optimizations are currently active on the site, enabling
 * downstream consumers (e.g., theme/plugin logic, CDN edge logic) to key on it.
 *
 * @since 1.0.0
 */
final class CloudflareOptimizationHeaderFragment implements Fragment {

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
	 * Deterministic cookie value encoding which CF features are enabled.
	 *
	 * @var string
	 */
	private $header_value;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id           Unique fragment ID.
	 * @param string $marker_label Marker label for readability in the file.
	 * @param string $header_value Deterministic cookie value reflecting CF features.
	 */
	public function __construct( $id, $marker_label, $header_value ) {
		$this->id           = (string) $id;
		$this->marker_label = (string) $marker_label;
		$this->header_value = (string) $header_value;
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
	 * Execute after core WordPress rules to avoid conflicts.
	 *
	 * @since 1.0.0
	 *
	 * @return int Priority constant.
	 */
	public function priority() {
		return self::PRIORITY_POST_WP;
	}

	/**
	 * Only one instance of this fragment should render.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if exclusive.
	 */
	public function exclusive() {
		return true;
	}

	/**
	 * Upper-layer logic controls registration; once instantiated, always enabled.
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
	 * Render the .htaccess rules that set the Cloudflare optimization cookie.
	 *
	 * Skips admin/login/API endpoints; sets an env var when the cookie is absent
	 * or different; then sends `Set-Cookie` via mod_headers conditional on env.
	 *
	 * @since 1.0.0
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return string Rendered fragment including BEGIN/END comments.
	 */
	public function render( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$value  = $this->header_value;
		$cookie = 'nfd-enable-cf-opt=' . $value;

		$lines   = array();
		$lines[] = '# BEGIN ' . $this->marker_label;

		// Gate with mod_rewrite to set an env var only when the cookie is missing/mismatched.
		$lines[] = '<IfModule mod_rewrite.c>';
		$lines[] = "\tRewriteEngine On";
		$lines[] = "\t# Skip setting for admin/API routes";
		$lines[] = "\tRewriteCond %{REQUEST_URI} !/wp-admin/       [NC]";
		$lines[] = "\tRewriteCond %{REQUEST_URI} !/wp-login\\.php   [NC]";
		$lines[] = "\tRewriteCond %{REQUEST_URI} !/wp-json/        [NC]";
		$lines[] = "\tRewriteCond %{REQUEST_URI} !/xmlrpc\\.php     [NC]";
		$lines[] = "\tRewriteCond %{REQUEST_URI} !/admin-ajax\\.php [NC]";
		$lines[] = "\t# Skip if the exact cookie and value are already present";
		$lines[] = "\tRewriteCond %{HTTP_COOKIE} !(^|;\\s*)" . preg_quote( $cookie, '/' ) . ' [NC]';
		$lines[] = "\t# Set env var if we passed all conditions";
		$lines[] = "\tRewriteRule .* - [E=CF_OPT:1]";
		$lines[] = '</IfModule>';

		// Set the cookie only when env var was set above.
		$lines[] = '<IfModule mod_headers.c>';
		$lines[] = "\t# Set cookie only if env var is present (i.e., exact cookie not found)";
		$lines[] = "\tHeader set Set-Cookie \"" . $cookie . '; path=/; Max-Age=86400; HttpOnly" env=CF_OPT';
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
