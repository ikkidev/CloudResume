<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\HelpCenter\Data\Brands;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * The class to initialize and load the module.
 */
class HelpCenter {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Identifier for script handle.
	 *
	 * @var string
	 */
	public static $handle = 'nfd-help-center';

	/**
	 * Text-domain
	 *
	 * @var string
	 */
	public static $text_domain = 'wp-module-help-center';

	/**
	 * Constructor.
	 *
	 * @param Container $container The container instance.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		add_action( 'init', array( $this, 'load_textdomains' ), 0 );
		add_action( 'rest_api_init', array( $this, 'initialize_rest' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_bar_menu', array( $this, 'newfold_help_center' ), 11 );
		add_filter(
			'load_script_translation_file',
			array( $this, 'load_script_translation_file' ),
			10,
			3
		);
	}

	/**
	 * Filters the file path for the JS translation JSON.
	 *
	 * If the script handle matches the module's handle, builds a custom path using
	 * the languages directory, current locale, text domain, and a hash of the script.
	 *
	 * @param string $file   Default translation file path.
	 * @param string $handle Script handle.
	 * @param string $domain Text domain.
	 * @return string Modified file path for the translation JSON.
	 */
	public function load_script_translation_file( $file, $handle, $domain ) {

		if ( $handle === self::$handle ) {
			$path   = NFD_HELPCENTER_DIR . '/languages/';
			$locale = determine_locale();

			$file_base = 'default' === $domain
				? $locale
				: $domain . '-' . $locale;
			$file      = $path . $file_base . '-' . md5( 'build/index.js' )
						. '.json';

		}

		return $file;
	}

	/**
	 * Loads the textdomain for the module. This applies only to PHP strings.
	 */
	public static function load_textdomains() {
		$langdir = dirname( container()->plugin()->basename ) . '/vendor/newfold-labs/wp-module-help-center/languages';
		\load_plugin_textdomain(
			self::$text_domain,
			false,
			$langdir
		);
	}

	/**
	 * Initializes REST API routes for the Help Center module.
	 *
	 * This method registers REST API routes by instantiating controller classes
	 * and calling their `register_routes()` methods.
	 */
	public function initialize_rest() {
		$controllers = array(
			'NewfoldLabs\\WP\\Module\\HelpCenter\\UserInteractionController',
			'NewfoldLabs\\WP\\Module\\HelpCenter\\CapabilityController',
			'NewfoldLabs\\WP\\Module\\HelpCenter\\MultiSearchController',
		);

		foreach ( $controllers as $controller ) {
			$instance = new $controller();
			$instance->register_routes();
		}
		$this->register_settings();
	}
	/**
	 * Register the helpcenter settings in the WordPress options API for last three searches.
	 *
	 * @return void
	 */
	public function register_settings() {
		$option = array(
			'type'         => 'string',
			'description'  => __( 'NFD helpcenter data', 'wp-module-help-center' ),
			'show_in_rest' => true,
			'default'      => '',
		);

		\register_setting( 'general', 'nfd_helpcenter_data', $option );
	}

	/**
	 * Adds the Help Center icon to the WordPress admin bar.
	 *
	 * @param \WP_Admin_Bar $admin_bar The WordPress Admin Bar instance.
	 */
	public function newfold_help_center( \WP_Admin_Bar $admin_bar ) {
		if ( current_user_can( 'manage_options' ) && is_admin() ) {
			$help_icon        =
			'<svg width="24" height="24" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg" style="margin-top: 5px;">
<path d="M20.25 8.51104C21.1341 8.79549 21.75 9.6392 21.75 10.6082V14.8938C21.75 16.0304 20.9026 16.9943 19.7697 17.0867C19.4308 17.1144 19.0909 17.1386 18.75 17.1592V20.25L15.75 17.25C14.3963 17.25 13.0556 17.1948 11.7302 17.0866C11.4319 17.0623 11.1534 16.9775 10.9049 16.8451M20.25 8.51104C20.0986 8.46232 19.9393 8.43 19.7739 8.41628C18.4472 8.30616 17.1051 8.25 15.75 8.25C14.3948 8.25 13.0528 8.30616 11.7261 8.41627C10.595 8.51015 9.75 9.47323 9.75 10.6082V14.8937C9.75 15.731 10.2099 16.4746 10.9049 16.8451M20.25 8.51104V6.63731C20.25 5.01589 19.0983 3.61065 17.4903 3.40191C15.4478 3.13676 13.365 3 11.2503 3C9.13533 3 7.05233 3.13678 5.00963 3.40199C3.40173 3.61074 2.25 5.01598 2.25 6.63738V12.8626C2.25 14.484 3.40173 15.8893 5.00964 16.098C5.58661 16.1729 6.16679 16.2376 6.75 16.2918V21L10.9049 16.8451" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
			$help_center_menu = array(
				'id'     => 'help-center',
				'parent' => 'top-secondary',
				'title'  => $help_icon,
				'href'   => '',
				'meta'   => array(
					'title'   => esc_attr__( 'Help', 'wp-module-help-center' ),
					'onclick' => 'newfoldEmbeddedHelp.toggleNFDLaunchedEmbeddedHelp()',
				),
			);
			$help_enabled     = $this->container->get( 'capabilities' )->get( 'canAccessHelpCenter' );
			if ( $help_enabled ) {
				$admin_bar->add_menu( $help_center_menu );
				$menu_name = $this->container->plugin()->id . '-help-center';
				$admin_bar->remove_menu( $menu_name );
			}
		}
	}

	/**
	 * Load WP dependencies into the page.
	 */
	public function assets() {
		$dir          = container()->plugin()->url . 'vendor/newfold-labs/wp-module-help-center/';
		$asset_file   = NFD_HELPCENTER_BUILD_DIR . 'index.asset.php';
		$help_enabled = $this->container->get( 'capabilities' )->get( 'canAccessHelpCenter' );
		if ( file_exists( $asset_file ) && $help_enabled && current_user_can( 'manage_options' ) ) {
			$asset = require_once $asset_file;

			\wp_register_script(
				self::$handle,
				$dir . '/build/index.js',
				array_merge( $asset['dependencies'], array( 'jquery', 'heartbeat' ) ),
				$asset['version'],
				true
			);

			\wp_set_script_translations(
				self::$handle,
				self::$text_domain,
				$dir . '/languages'
			);

			if ( $help_enabled ) {
				\wp_enqueue_script( self::$handle );

				\wp_enqueue_style(
					self::$handle,
					$dir . '/build/index.css',
					array(),
					$asset['version'],
					'screen'
				);

				\wp_add_inline_script(
					self::$handle,
					'var nfdHelpCenter =' . wp_json_encode(
						array(
							'restUrl'      => \get_home_url() . '/index.php?rest_route=',
							'resourceLink' => Brands::get_resource_link_for_brand( NFD_HELPCENTER_PLUGIN_BRAND ),
						)
					) . ';',
					'before'
				);

				/* Hide the helpcenter on onboarding flow */
				\wp_localize_script( self::$handle, 'newfoldHelpCenter', array( 'closeOnLoad' => ( isset( $_GET['page'] ) && 'nfd-onboarding' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ) );

				/* Remove values on log out */
				$logout_listener_js = <<<JS
				jQuery(document).ready(function ($) {
					$('a[href*="wp-login.php?action=logout"]').on('click', function () {
						localStorage.removeItem('helpResultContent');
						localStorage.removeItem('searchInput');
						localStorage.removeItem('helpVisible');
					});
				});
				JS;

				\wp_add_inline_script( self::$handle, $logout_listener_js );

				/* Remove values when the user is logged out */
				$session_expiration_js = <<<JS
				jQuery(document).on('heartbeat-tick', function (event, data) {
					if (data.hasOwnProperty('wp-auth-check') && data['wp-auth-check'] === false) {
						localStorage.removeItem('helpResultContent');
						localStorage.removeItem('searchInput');
						localStorage.removeItem('helpVisible');
					}
				});
				JS;

				\wp_add_inline_script( self::$handle, $session_expiration_js );
			}
		}
	}
}
