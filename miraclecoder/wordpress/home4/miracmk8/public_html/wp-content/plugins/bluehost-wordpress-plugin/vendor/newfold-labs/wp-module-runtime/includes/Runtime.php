<?php

namespace NewfoldLabs\WP\Module\Runtime;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Class Runtime
 *
 * @package NewfoldLabs\WP\Module\Runtime
 */
class Runtime {
	/**
	 * Main Runtime file
	 *
	 * Entry point via bootstrap.php
	 */

	/**
	 * Container loaded from the brand plugin.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Runtime constructor.
	 *
	 * @param Container $container Container loaded from the brand plugin.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	public function loadIntoPage( $page ) {
		add_action( $page, array( $this, 'register_runtime' ) );
	}

	public function prepareRuntime() {
		global $wp_version;
		$sdk = apply_filters( 'newfold-runtime', array( 'wpversion' => $wp_version ) ); // kept for backward compatability, will be removed later

		return apply_filters(
			'newfold_runtime',
			array(
				'site'                 => array(
					'url'   => \get_site_url(), // TODO: To be removed once no longer in use
					'title' => htmlspecialchars_decode( \get_bloginfo( 'name' ) ), // TODO: To be removed once no longer in use
				),
				'admin_url'            => \admin_url(), // TODO: To be removed once no longer in use
				'adminUrl'             => \admin_url(),
				'base_url'             => \get_home_url() . '/index.php', // TODO: To be removed once no longer in use
				'homeUrl'              => \get_home_url(),
				'capabilities'         => $this->container->get( 'capabilities' )->all(),
				'sdk'                  => $sdk, // TODO: To be removed once no longer in use
				'siteUrl'              => \get_site_url(),
				'siteTitle'            => htmlspecialchars_decode( \get_bloginfo( 'name' ) ),
				'restUrl'              => \esc_url_raw( \rest_url() ),
				'restNonce'            => wp_create_nonce( 'wp_rest' ),
				'isWoocommerceActive'  => is_plugin_active( 'woocommerce/woocommerce.php' ),
				'isYithBookingActive'  => is_plugin_active( 'yith-woocommerce-booking-extended/init.php' ) || is_plugin_active( 'yith-woocommerce-booking-premium/init.php' ) || is_plugin_active( 'yith-woocommerce-booking/init.php' ),
				'isJetpackBoostActive' => is_plugin_active( 'jetpack-boost/jetpack-boost.php' ),
				'wpVersion'            => $wp_version,
				'currentTheme'         => wp_get_theme()->get( 'TextDomain' ),
			)
		);
	}

	/**
	 * Load Runtime into the page.
	 */
	public function register_runtime() {
		global $wp_version;
		\wp_register_script(
			'nfd-runtime',
			false,
			null,
			$wp_version
		);
		\wp_add_inline_script(
			'nfd-runtime',
			'window.NewfoldRuntime =' . wp_json_encode( $this->prepareRuntime( $this->container ) ) . ';',
			'before'
		);
	}

}
