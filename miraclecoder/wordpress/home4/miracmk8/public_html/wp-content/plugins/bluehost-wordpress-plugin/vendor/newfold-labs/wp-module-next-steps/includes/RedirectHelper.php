<?php

namespace NewfoldLabs\WP\Module\NextSteps;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Redirect Helper
 *
 * Handles dynamic redirects for next steps tasks including both partner plugins
 * and WordPress block theme templates. Provides real-time URL generation to
 * ensure links remain valid even when themes or plugin configurations change.
 *
 * Features:
 * - Plugin redirects with whitelist-based validation for security
 * - Template redirects for block theme site editor URLs
 * - Real-time URL generation to handle theme/plugin changes
 * - Default URLs with optional parameter overrides
 * - Plugin-specific configuration checks (e.g., Jetpack connection)
 * - Block theme detection and fallback handling
 * - Graceful error handling with fallback redirects
 *
 * Usage Examples:
 * Plugin Redirects:
 * - Jetpack with defaults: admin.php?page=redirect-check&type=plugin&p=jetpack
 * - WooCommerce with custom URL: admin.php?page=redirect-check&type=plugin&p=woocommerce&r=admin.php?page=wc-orders
 *
 * Template Redirects:
 * - Header template part: admin.php?page=redirect-check&type=template&area=header&template=index
 * - Home template: admin.php?page=redirect-check&type=template&template=home
 *
 * @package NewfoldLabs\WP\Module\NextSteps
 * @since 1.0.0
 * @author Newfold Labs
 */
class RedirectHelper {

	/**
	 * Initialize the plugin redirect functionality
	 */
	public static function init() {
		// dummy page to manage dynamic redirects
		add_action( 'admin_menu', array( __CLASS__, 'add_redirect_page' ) );
	}

	/**
	 * Partner plugin whitelist with default redirect and fallback URLs
	 *
	 * @return array Partner plugins configuration
	 */
	private static function get_partner_plugins() {
		$plugin_id = container()->plugin()->id;
		// key value pair of plugin slug and plugin configuration
		return array(
			'jetpack'          => array(
				'file'         => 'jetpack/jetpack.php',
				'redirect_url' => 'admin.php?page=my-jetpack#add-boost',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=all&s=jetpack#/commerce',
			),
			'woocommerce'      => array(
				'file'         => 'woocommerce/woocommerce.php',
				'redirect_url' => 'admin.php?page=wc-settings&tab=advanced',
				'fallback_url' => 'plugin-install.php?s=woocommerce&tab=search&type=term',
			),
			'yoast-seo'        => array(
				'file'         => 'wordpress-seo/wp-seo.php',
				'redirect_url' => 'admin.php?page=wpseo_dashboard',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=all&s=yoast#/commerce',
			),
			'advanced-reviews' => array(
				'file'         => 'wp-plugin-advanced-reviews/wp-plugin-advanced-reviews.php',
				'redirect_url' => 'admin.php?page=advanced-reviews',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=all&s=advanced+reviews#/commerce',
			),
			'affiliates'       => array(
				'file'         => 'yith-woocommerce-affiliates/init.php',
				'redirect_url' => 'admin.php?page=yith_wcaf_panel',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=premium&s=affiliate#/commerce',
			),
			'gift-cards'       => array(
				'file'         => 'yith-woocommerce-gift-cards-premium/init.php',
				'redirect_url' => 'admin.php?page=gift-cards',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=all&s=gift+cards#/commerce',
			),
			'email-templates'  => array(
				'file'         => 'wp-plugin-email-templates/wp-plugin-email-templates.php',
				'redirect_url' => 'edit.php?post_type=bh-email-template',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '&category=all&s=email+templates#/commerce',
			),
			'akismet'          => array(
				'file'         => 'akismet/akismet.php',
				'redirect_url' => 'admin.php?page=akismet-key-config',
				'fallback_url' => 'plugin-install.php?s=akismet&tab=search&type=term',
			),
			'wondercart'       => array(
				'file'         => 'wonder-cart/init.php',
				'redirect_url' => 'admin.php?page=' . $plugin_id . '#/store/sales_discounts',
				'fallback_url' => 'admin.php?page=' . $plugin_id . '#/store/sales_discounts', // same used for fallback since ctb to install is on the page
			),
		);
	}

	/**
	 * Registers a hidden submenu page for checking partner plugin status and redirecting.
	 */
	public static function add_redirect_page() {
		$hook = \add_submenu_page(
			'', // No parent, so it won't appear in any menu
			'Checking Partner Plugin Before Redirect',
			'',
			'manage_options',
			'redirect-check',
			'__return_null',
		);

		add_action( "load-$hook", array( __CLASS__, 'check_redirect' ) );
	}

	/**
	 * Handles dynamic redirects for both plugins and templates.
	 *
	 * This method processes redirect requests and routes them to the appropriate
	 * handler based on the 'type' parameter. Supports both plugin redirects
	 * (with activation checks) and template redirects (with real-time URL generation).
	 *
	 * URL Parameters:
	 * Common:
	 * - type: 'plugin' or 'template' (required)
	 *
	 * Plugin redirects:
	 * - p: Plugin slug to check (required for plugin type)
	 * - r: URL to redirect to if plugin is active (optional - uses default from whitelist)
	 * - f: URL to redirect to if plugin is not active (optional - uses default from whitelist)
	 *
	 * Template redirects:
	 * - area: Template part area (e.g., 'header', 'footer') - for template parts
	 * - template: Template slug (e.g., 'index', 'home') - required for template type
	 * - fallback: Fallback URL if template editing is not available
	 *
	 * @return void
	 */
	public static function check_redirect() {
		// Only process redirect requests
		if (
			! is_admin() ||
			! isset( $_GET['page'] ) ||
			'redirect-check' !== $_GET['page']
		) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// Nonce not necessary - redirects are whitelisted and nonces expire after 24 hours
		// which causes issues with cached plan data that users may access weeks/months later

		// Sanitize and validate redirect type
		$redirect_type = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'plugin';

		// Route to appropriate handler based on type
		switch ( $redirect_type ) {
			case 'template':
				self::handle_template_redirect();
				break;
			case 'plugin':
			default:
				self::handle_plugin_redirect();
				break;
		}
	}

	/**
	 * Handle plugin-based redirects (legacy functionality)
	 *
	 * @return void
	 */
	private static function handle_plugin_redirect() {
		// Sanitize and validate parameters
		$plugin_slug  = isset( $_GET['p'] ) ? sanitize_text_field( wp_unslash( $_GET['p'] ) ) : '';
		$redirect_url = isset( $_GET['r'] ) ? esc_url_raw( wp_unslash( $_GET['r'] ) ) : '';
		$fallback_url = isset( $_GET['f'] ) ? esc_url_raw( wp_unslash( $_GET['f'] ) ) : '';

		// Validate required plugin parameter
		if ( empty( $plugin_slug ) ) {
			// Redirect to admin dashboard if no plugin specified
			wp_safe_redirect( admin_url() );
			exit;
		}

		// Get partner plugin defaults
		$partner_plugins = self::get_partner_plugins();
		if ( ! array_key_exists( $plugin_slug, $partner_plugins ) ) {
			// Redirect to admin dashboard if invalid plugin specified
			wp_safe_redirect( admin_url() );
			exit;
		}

		// Use provided URLs or fall back to defaults
		$final_redirect_url = ! empty( $redirect_url ) ? $redirect_url : admin_url( $partner_plugins[ $plugin_slug ]['redirect_url'] );
		$final_fallback_url = ! empty( $fallback_url ) ? $fallback_url : admin_url( $partner_plugins[ $plugin_slug ]['fallback_url'] );

		// Check plugin status
		$plugin_active = self::check_plugin( $plugin_slug );

		// Determine redirect URL based on plugin status
		$final_redirect_url = $plugin_active ? $final_redirect_url : $final_fallback_url;

		// Perform redirect
		wp_safe_redirect( $final_redirect_url );
		exit;
	}

	/**
	 * Handle template-based redirects with real-time URL generation
	 *
	 * @return void
	 */
	private static function handle_template_redirect() {
		// Sanitize and validate parameters
		$area_param     = isset( $_GET['area'] ) ? sanitize_text_field( wp_unslash( $_GET['area'] ) ) : '';
		$template_param = isset( $_GET['template'] ) ? sanitize_text_field( wp_unslash( $_GET['template'] ) ) : 'index';
		$fallback_url   = isset( $_GET['fallback'] ) ? esc_url_raw( wp_unslash( $_GET['fallback'] ) ) : '';

		// Set appropriate fallback URL based on theme type if none provided
		if ( empty( $fallback_url ) ) {
			$fallback_url = self::is_block_theme()
				? admin_url( 'site-editor.php' ) // Block themes: use site editor
				: admin_url( 'customize.php' );  // Classic themes: use customizer
		}

		// Generate the appropriate template URL based on parameters
		$template_url = null;

		if ( ! empty( $area_param ) ) {
			// Template part redirect (e.g., header, footer)
			$template_url = self::get_template_part_editor_url( $area_param, $template_param );
		} else {
			// Template redirect (e.g., home, index)
			$template_url = self::get_template_editor_url( $template_param );
		}

		// Use template URL if available, otherwise use fallback
		if ( $template_url ) {
			// Replace {siteUrl} placeholder with actual site URL
			$template_url = str_replace( '{siteUrl}', untrailingslashit( get_site_url() ), $template_url );
			wp_safe_redirect( $template_url );
		} else {
			// Use fallback URL (defaults to site editor)
			wp_safe_redirect( $fallback_url );
		}
		exit;
	}


	/**
	 * Check if a plugin is properly configured (plugin-specific logic)
	 *
	 * @param string $plugin_slug The plugin slug
	 * @return bool Whether the plugin is active and/or configured
	 */
	private static function check_plugin( $plugin_slug ) {
		// Ensure WordPress plugin functions are available
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$partner_plugins = self::get_partner_plugins();

		// Check if plugin slug is in our whitelist
		if ( ! array_key_exists( $plugin_slug, $partner_plugins ) ) {
			return false;
		}

		// Plugin-specific configuration checks
		switch ( $plugin_slug ) {
			case 'jetpack':
				// Check if Jetpack is both active AND connected
				if (
					is_plugin_active( $partner_plugins[ $plugin_slug ]['file'] ) &&
					class_exists( '\Automattic\Jetpack\Connection\Manager' )
				) {
					$manager = new \Automattic\Jetpack\Connection\Manager();
					return $manager->is_connected();
				}
				return false;

			default:
				// For other plugins, just check if they're active
				return is_plugin_active( $partner_plugins[ $plugin_slug ]['file'] );
		}
	}

	/**
	 * Generate URL for editing a template part in the site editor
	 *
	 * @param string $area_name     The template part area (e.g., 'header', 'footer')
	 * @param string $template_slug The template slug to search in (default: 'index')
	 * @return string|null The site editor URL or null if not available
	 */
	public static function get_template_part_editor_url( string $area_name, string $template_slug = 'index' ): ?string {
		// Check if this is a block theme
		if ( ! self::is_block_theme() ) {
			return null;
		}

		// Return null if WordPress functions aren't available (e.g., during testing)
		if ( ! function_exists( 'wp_get_theme' ) || ! function_exists( 'get_block_template' ) ) {
			return null;
		}

		// Get the current theme name for template part lookup
		$theme = wp_get_theme()->get_stylesheet();

		// First try to find the template part slug used in the specified template
		$slug = self::get_active_template_part_slug( $area_name, $template_slug );

		// If not found, try common template part naming conventions
		if ( ! $slug ) {
			// Try common naming patterns for template parts
			$common_names = array(
				$area_name,                    // e.g., "header"
				"{$area_name}-default",        // e.g., "header-default"
				"template-part-{$area_name}",  // e.g., "template-part-header"
			);

			foreach ( $common_names as $potential_slug ) {
				$template_part = get_block_template( "{$theme}//{$potential_slug}", 'wp_template_part' );
				if ( $template_part ) {
					$slug = $potential_slug;
					break;
				}
			}
		}

		if ( ! $slug ) {
			return null;
		}

		// Build the site editor URL with the necessary parameters
		return add_query_arg(
			array(
				'postType' => 'wp_template_part',
				'postId'   => "{$theme}//{$slug}",
				'canvas'   => 'edit',
			),
			'{siteUrl}/wp-admin/site-editor.php'
		);
	}

	/**
	 * Generate URL for editing a template in the site editor
	 *
	 * @param string $template_slug The template slug (e.g., 'home', 'index')
	 * @return string|null The site editor URL or null if not available
	 */
	public static function get_template_editor_url( string $template_slug = 'home' ): ?string {
		// Check if this is a block theme
		if ( ! self::is_block_theme() ) {
			return null;
		}

		// Return null if WordPress functions aren't available (e.g., during testing)
		if ( ! function_exists( 'wp_get_theme' ) || ! function_exists( 'get_block_template' ) ) {
			return null;
		}

		// Get the current theme name for template lookup
		$theme = wp_get_theme()->get_stylesheet();

		// Try to find the specified template first
		$template = get_block_template( "{$theme}//{$template_slug}" );

		// Fall back to 'index' template if specified template doesn't exist
		if ( ! $template && 'index' !== $template_slug ) {
			$template_slug = 'index';
		}

		// Build the site editor URL for the template
		return add_query_arg(
			array(
				'postType' => 'wp_template',
				'postId'   => "{$theme}//{$template_slug}",
				'canvas'   => 'edit',
			),
			'{siteUrl}/wp-admin/site-editor.php'
		);
	}

	/**
	 * Check if the current theme supports block templates
	 *
	 * @return bool True if the theme supports block templates, false otherwise.
	 */
	public static function is_block_theme(): bool {
		// Return false if WordPress functions aren't available (e.g., during testing)
		if ( ! function_exists( 'current_theme_supports' ) || ! function_exists( 'wp_get_theme' ) ) {
			return false;
		}

		// Check if theme explicitly supports block templates
		$theme_supports_block_templates = current_theme_supports( 'block-templates' );

		// Check if this is a full block theme using WordPress's detection
		$is_block_theme = function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();

		return $theme_supports_block_templates || $is_block_theme;
	}

	/**
	 * Get the slug of the template part used in a given template
	 *
	 * @param string $tag_name      The HTML tag name to look for (e.g., 'header', 'footer')
	 * @param string $template_slug The slug of the template (e.g., 'index', 'front-page', 'single')
	 * @return string|null The slug of the template part, or null if not found
	 */
	private static function get_active_template_part_slug( string $tag_name, string $template_slug = 'index' ): ?string {
		// Return null if WordPress functions aren't available (e.g., during testing)
		if ( ! function_exists( 'wp_get_theme' ) || ! function_exists( 'get_block_template' ) ) {
			return null;
		}

		// Get the current theme's stylesheet name for template lookup
		$theme    = wp_get_theme()->get_stylesheet();
		$template = get_block_template( "{$theme}//{$template_slug}" );

		if ( ! $template ) {
			return null;
		}

		// Parse the template content into blocks for analysis
		$blocks = parse_blocks( $template->content );
		$stack  = $blocks; // Use a stack to traverse nested blocks

		// Traverse all blocks in the template (including nested ones)
		while ( $stack ) {
			$block = array_shift( $stack );

			// Handle pattern blocks by resolving their content
			if ( 'core/pattern' === $block['blockName'] ) {
				$pattern_slug = $block['attrs']['slug'] ?? null;
				if ( $pattern_slug && function_exists( 'get_block_pattern' ) ) {
					$pattern = get_block_pattern( $pattern_slug );
					if ( $pattern && isset( $pattern['content'] ) ) {
						$pattern_blocks = parse_blocks( $pattern['content'] );
						// Add pattern blocks to the stack for processing
						$stack = array_merge( $stack, $pattern_blocks );
					}
				}
				continue;
			}

			// Look for template-part blocks specifically
			if ( 'core/template-part' === $block['blockName'] ) {
				// Extract the template part slug and theme from block attributes
				$slug       = $block['attrs']['slug'] ?? null;
				$part_theme = $block['attrs']['theme'] ?? $theme; // Default to current theme

				if ( $slug ) {
					// Get the actual template part object to examine its properties
					$part = get_block_template( "{$part_theme}//{$slug}", 'wp_template_part' );

					// Method 1: Check the 'area' property directly on the template part
					$area_prop = isset( $part->area ) ? $part->area : null;

					// Method 2: Check the 'wp_template_part_area' taxonomy terms
					$area_tax = null;
					if ( isset( $part->wp_id ) && $part->wp_id ) {
						$terms = wp_get_post_terms(
							$part->wp_id,
							'wp_template_part_area',
							array( 'fields' => 'slugs' )
						);
						if ( ! is_wp_error( $terms ) && $terms ) {
							$area_tax = $terms[0] ?? null;
						}
					}

					// Method 3: Check the block's tagName attribute
					$is_tag = ( $block['attrs']['tagName'] ?? '' ) === $tag_name;

					// Match if any of the three methods identify this as the target area
					if ( $area_prop === $tag_name || $area_tax === $tag_name || $is_tag ) {
						return $slug;
					}
				}
			}

			// Add any nested blocks to the stack for continued traversal
			if ( ! empty( $block['innerBlocks'] ) ) {
				$stack = array_merge( $stack, $block['innerBlocks'] );
			}
		}

		return null;
	}
}
