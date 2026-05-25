<?php

namespace NewFoldLabs\WP\Module\Marketplace;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class PluginsMarketplace
 * Adds a Premium tab with Marketplace data in plugin-install.php
 */
class PluginsMarketplace {

	/**
	 * Initialize.
	 */
	public static function init() {
		// Filters whether the premuim plugins tab is enabled or disabled. Default is enabled.
		$enabled = apply_filters( 'nfd_enable_plugins_marketplace', true );

		if ( $enabled ) {
			add_action( 'admin_menu', array( __CLASS__, 'add_premuim_plugins_menu_link' ) );
			add_filter( 'install_plugins_tabs', array( __CLASS__, 'add_premuim_plugins_tab' ) );
			add_action( 'admin_head-plugin-install.php', array( __CLASS__, 'premuim_plugins_tab_enqueue_assets' ) );

			\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 100 );
		}
	}

	/**
	 * Add "Premium" sub-link to admin menu.
	 */
	public static function add_premuim_plugins_menu_link() {
		add_submenu_page(
			'plugins.php',
			'Premium Plugins',
			'Premium',
			'manage_options',
			'plugin-install.php?tab=premium-marketplace'
		);

		// Add crown icon to menu and filter links
		add_action(
			'admin_footer',
			function () { ?>
				<style>
					#nfd-premium-plugins-crown {
						max-width: .85rem;
						max-height: .85rem;
						margin-left: 5px;
						margin-bottom: -1.5px;
					}

					.plugin-install-premium-marketplace #nfd-premium-plugins-crown {
						max-width: 1rem;
						max-height: 1rem;
						fill: #f19805;
					}
				</style>
				<script>
					window.addEventListener('DOMContentLoaded', () => {
						const crownSVG = `<svg id="nfd-premium-plugins-crown" width="24" height="24" viewBox="0 0 124 109" fill="#FBAA10" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M22.13 84.86H102.38L114.92 50.13C115.57 50.34 116.27 50.45 116.99 50.45C120.79 50.45 123.88 47.37 123.88 43.56C123.88 39.76 120.8 36.67 116.99 36.67C113.19 36.67 110.1 39.75 110.1 43.56C110.1 45.06 110.58 46.44 111.39 47.57L104.27 53.43C94.3 61.63 88.05 57.83 90 45.09L91.1 37.92C91.48 37.99 91.88 38.02 92.28 38.02C96.08 38.02 99.17 34.94 99.17 31.13C99.17 27.33 96.09 24.24 92.28 24.24C88.48 24.24 85.39 27.32 85.39 31.13C85.39 33.3 86.4 35.24 87.97 36.5L86.26 39.2C77.88 51.78 71.7 46.96 69.23 34.53L64.82 14.22C67.29 13.17 69.03 10.73 69.03 7.86998C69.03 4.06998 65.95 0.97998 62.14 0.97998C58.34 0.97998 55.25 4.05998 55.25 7.86998C55.25 11.05 57.4 13.72 60.32 14.52L57.46 26.1C54.98 36.71 52.01 57.85 38.58 39.83L36.39 36.85C38.12 35.6 39.25 33.56 39.25 31.26C39.25 27.46 36.17 24.37 32.36 24.37C28.56 24.37 25.47 27.45 25.47 31.26C25.47 35.06 28.55 38.15 32.36 38.15C32.89 38.15 33.41 38.09 33.91 37.97L34.37 42.65C35.27 49.04 36.42 57.69 29.08 57.28C25.44 57.08 24.07 55.84 21.29 53.86L13.35 48.23C14.24 47.07 14.77 45.62 14.77 44.04C14.77 40.24 11.69 37.15 7.87999 37.15C4.07999 37.15 0.98999 40.23 0.98999 44.04C0.98999 47.85 4.06999 50.93 7.87999 50.93C8.77999 50.93 9.62999 50.76 10.42 50.45L22.13 84.86ZM22.07 94.47H102.58V108.76H22.07V94.47Z" />
						</svg>`;

						const menuPremiumLink = document.querySelector('#menu-plugins a[href="plugin-install.php?tab=premium-marketplace"]');
						menuPremiumLink.innerHTML += crownSVG;

						const filterPremiumLink = document.querySelector('.plugin-install-premium-marketplace > a');
						if (filterPremiumLink) {
							filterPremiumLink.innerHTML += crownSVG;
						}
					});
				</script>
				<?php
			},
			0
		);
	}

	/**
	 * Add "Premium" tab to plugins install tabs.
	 *
	 * @param array $tabs Collection of tabs.
	 *
	 * @return array
	 */
	public static function add_premuim_plugins_tab( array $tabs ) {
		$tabs['premium-marketplace'] = __( 'Premium', 'wp-module-marketplace' );

		return $tabs;
	}

	/**
	 * Enqueue assets and set locals.
	 */
	public static function premuim_plugins_tab_enqueue_assets() {
		if ( false === ( isset( $_GET['tab'] ) && 'premium-marketplace' === $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$assetsDir = container()->plugin()->url . 'vendor/newfold-labs/wp-module-marketplace/includes/assets/';

		wp_enqueue_style( 'nfd_plugins_marketplace_css', $assetsDir . 'css/NFDPluginsMarketplace.css', array(), container()->plugin()->version );
		wp_enqueue_script( 'nfd_plugins_marketplace_js', $assetsDir . 'js/NFDPluginsMarketplace.js', array(), container()->plugin()->version, true );

		\wp_set_script_translations(
			'nfd_plugins_marketplace_js',
			'wp-module-marketplace',
			NFD_MARKETPLACE_DIR . '/languages'
		);

		wp_localize_script(
			'nfd_plugins_marketplace_js',
			'nfdPremiumPluginsMarketplace',
			array(
				'restApiRoot'            => \get_home_url() . '/index.php?rest_route=',
				'restApiNonce'           => wp_create_nonce( 'wp_rest' ),
				'marketplaceDescription' => sprintf(
					/* translators: The brand from the plugin is inserted into the string */
					esc_html__(
						'Unlock the full potential of your WordPress website with premium plugins from %s.',
						'wp-module-marketplace'
					),
					esc_html( ucwords( container()->plugin()->id ) )
				),
			)
		);
	}


	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-module-marketplace',
			false,
			NFD_MARKETPLACE_DIR . '/languages'
		);

		\load_script_textdomain(
			'nfd_plugins_marketplace_js',
			'wp-module-marketplace',
			NFD_MARKETPLACE_DIR . '/languages'
		);
	}
}
