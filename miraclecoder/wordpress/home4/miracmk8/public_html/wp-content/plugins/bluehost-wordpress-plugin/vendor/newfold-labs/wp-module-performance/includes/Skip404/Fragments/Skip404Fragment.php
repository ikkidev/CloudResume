<?php
/**
 * Skip404Fragment
 *
 * Renders .htaccess rules that stop rewrite processing (L) for requests that
 * look like static assets (wide extension allowlist) when the request does not
 * map to an existing file or directory. This avoids funneling such requests
 * into WordPress' 404 handling.
 *
 * @package NewfoldLabs\WP\Module\Performance\Skip404\Fragments
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\Skip404\Fragments;

use NewfoldLabs\WP\Module\Htaccess\Fragment;
use NewfoldLabs\WP\Module\Htaccess\Context;

/**
 * Fragment: Skip 404 Handling for Static Files
 *
 * Uses mod_rewrite conditions to short-circuit asset-like requests and
 * prevent WordPress 404 handling, improving performance for missing assets.
 *
 * @since 1.0.0
 */
final class Skip404Fragment implements Fragment {

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
	 * Render the .htaccess rules for skipping WordPress 404 handling on static-like requests.
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
		$lines[] = "\tRewriteCond %{REQUEST_URI} !(robots\\.txt|ads\\.txt|[a-z0-9_\\-]*sitemap[a-z0-9_\\.\\-]*\\.(xml|xsl|html)(\\.gz)?)";
		$lines[] = "\tRewriteCond %{REQUEST_URI} \\.(css|htc|less|js|js2|js3|js4|html|htm|rtf|rtx|txt|xsd|xsl|xml|asf|asx|wax|wmv|wmx|avi|avif|avifs|bmp|class|divx|doc|docx|eot|exe|gif|gz|gzip|ico|jpg|jpeg|jpe|webp|json|mdb|mid|midi|mov|qt|mp3|m4a|mp4|m4v|mpeg|mpg|mpe|webm|mpp|otf|_otf|odb|odc|odf|odg|odp|ods|odt|ogg|ogv|pdf|png|pot|pps|ppt|pptx|ra|ram|svg|svgz|swf|tar|tif|tiff|ttf|ttc|_ttf|wav|wma|wri|woff|woff2|xla|xls|xlsx|xlt|xlw|zip)$ [NC]";
		$lines[] = "\tRewriteRule .* - [L]";
		$lines[] = '</IfModule>';
		$lines[] = '# END ' . $this->marker_label;

		return implode( "\n", $lines );
	}

	/**
	 * Inject a no-rewrite for missing static assets into the core WP block.
	 *
	 * We insert just before: "RewriteRule . /index.php [L]".
	 * The existing WP conditions (!-f, !-d) continue to apply to our inserted rule.
	 *
	 * @param Context $context Context snapshot (unused).
	 * @return array
	 */
	public function patches( $context ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$id = $this->id;

		// Self-contained snippet: includes !-f and !-d so it never catches real files/dirs.
		$snippet  = "# NFD PATCH {$id} BEGIN\n";
		$snippet .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$snippet .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$snippet .= "RewriteCond %{REQUEST_URI} !(robots\\.txt|ads\\.txt|[a-z0-9_\\-]*sitemap[a-z0-9_\\.\\-]*\\.(xml|xsl|html)(\\.gz)?)\n";
		$snippet .= "RewriteCond %{REQUEST_URI} \\.(css|htc|less|js|js2|js3|js4|html|htm|rtf|rtx|txt|xsd|xsl|xml|asf|asx|wax|wmv|wmx|avi|avif|avifs|bmp|class|divx|doc|docx|eot|exe|gif|gz|gzip|ico|jpg|jpeg|jpe|webp|json|mdb|mid|midi|mov|qt|mp3|m4a|mp4|m4v|mpeg|mpg|mpe|webm|mpp|otf|_otf|odb|odc|odf|odg|odp|ods|odt|ogg|ogv|pdf|png|pot|pps|ppt|pptx|ra|ram|svg|svgz|swf|tar|tif|tiff|ttf|ttc|_ttf|wav|wma|wri|woff|woff2|xla|xls|xlsx|xlt|xlw|zip)$ [NC]\n";
		$snippet .= "RewriteRule .* - [L]\n";
		$snippet .= "# NFD PATCH {$id} END\n";

		return array(
			array(
				'scope'       => 'wp_block',
				// Find the two WP guards as a unit, and insert our snippet BEFORE them.
				'pattern'     => '~^(?=[ \t]*RewriteCond[^\n]*%{REQUEST_FILENAME}\s+!-f\s*\R[ \t]*RewriteCond[^\n]*%{REQUEST_FILENAME}\s+!-d\s*\R)~m',
				'replacement' => $snippet,
				'limit'       => 1,
			),
		);
	}
}
