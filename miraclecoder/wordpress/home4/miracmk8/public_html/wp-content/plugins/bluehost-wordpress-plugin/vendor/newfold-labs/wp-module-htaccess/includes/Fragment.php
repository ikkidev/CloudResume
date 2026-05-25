<?php
/**
 * Fragment interface for the Htaccess module.
 *
 * Each fragment represents a self-contained block of .htaccess rules
 * that other modules can register. The Manager/Composer will determine
 * ordering (by priority), inclusion (via is_enabled), and spacing.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Interface Fragment
 *
 * Implement this in other modules to contribute .htaccess rules.
 * Fragments should be inexpensive to evaluate and pure (no side effects).
 *
 * @since 1.0.0
 */
interface Fragment {

	/**
	 * Render priority constants.
	 *
	 * Lower numbers render earlier in the final file.
	 * Treat these as guidance for common placement buckets:
	 *
	 * - PRE_WP:     Before native WordPress rules (e.g., early rewrites).
	 * - WP:         For the canonical WordPress block itself.
	 * - POST_WP:    Immediately after WP rules (e.g., overrides/extensions).
	 * - SECURITY:   Security-focused rules that should appear late.
	 * - LAST:       Catch-alls or lowest precedence content.
	 *
	 * @since 1.0.0
	 */
	const PRIORITY_PRE_WP   = 100; // before core WP rules
	const PRIORITY_WP       = 200; // the core WordPress block
	const PRIORITY_POST_WP  = 300; // immediately after WP rules
	const PRIORITY_SECURITY = 400; // security rules that should be late
	const PRIORITY_LAST     = 900; // render last (catch-all)

	/**
	 * Get a globally unique identifier for this fragment.
	 *
	 * Used as a stable key in the registry and persisted state.
	 * Must be globally unique across the codebase and stable over time.
	 * Example: "epc.skip-static-404".
	 *
	 * @since 1.0.0
	 *
	 * @return string Unique fragment ID.
	 */
	public function id();

	/**
	 * Render order (ascending). Lower numbers render first.
	 *
	 * The Composer will sort all enabled fragments by this value,
	 * then by ID for stability when priorities tie.
	 *
	 * @since 1.0.0
	 *
	 * @return int Priority value (see PRIORITY_* constants).
	 */
	public function priority();

	/**
	 * Whether only a single instance of this fragment may appear.
	 *
	 * If true, the Manager will de-duplicate multiple registrations
	 * of fragments with the same ID (first registration wins).
	 * This is appropriate for canonical/unique blocks (e.g., WordPress).
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if only one instance should exist.
	 */
	public function exclusive();

	/**
	 * Whether this fragment should be included for the given context.
	 *
	 * Typical checks: plugin active, module setting enabled, multisite mode,
	 * environment compatibility, etc. This method should be fast and have
	 * no side effects (pure predicate).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $context Optional context object; commonly an instance of {@see Context}.
	 * @return bool True if the fragment should be rendered.
	 */
	public function is_enabled( $context );

	/**
	 * Return the exact .htaccess text for this fragment (no trailing newline).
	 *
	 * Requirements & recommendations:
	 * - Do not include a trailing newline; the Composer handles spacing.
	 * - Prefer LF line endings ("\n") if you normalize internally.
	 * - Include BEGIN/END comment markers inside your block for clarity, e.g.:
	 *     # BEGIN My Fragment
	 *     <IfModule mod_rewrite.c>
	 *     ...
	 *     </IfModule>
	 *     # END My Fragment
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $context Optional context object; commonly an instance of {@see Context}.
	 * @return string Rendered .htaccess fragment without a trailing newline.
	 */
	public function render( $context );

	/**
	 * Return regex patches to modify existing text.
	 *
	 * Each patch is an associative array:
	 * - 'scope'       string One of: 'full', 'wp_block', 'managed_block'
	 * - 'pattern'     string PCRE with delimiters, e.g. '~^RewriteRule\s+\.\s+/index\.php\s+\[L\]\s*$~m'
	 * - 'replacement' string Replacement (backrefs allowed)
	 * - 'limit'       int    Optional; default -1 (replace all)
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $context Optional context.
	 * @return array<int,array{
	 *   scope:string,
	 *   pattern:string,
	 *   replacement:string,
	 *   limit?:int
	 * }>
	 */
	public function patches( $context );
}
