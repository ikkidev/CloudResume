<?php

namespace NewfoldLabs\WP\Module\Performance\Fonts;

/**
 * Manages the registration and sanitization of font optimization settings.
 */
class FontSettings {

	/**
	 * The setting key for font optimization.
	 */
	private const SETTING_KEY = 'nfd_fonts_optimization';

	/**
	 * The default setting.
	 *
	 * @var array
	 */
	private $default_settings = array(
		'cloudflare' => array(
			'fonts'        => array(
				'value'    => false,
				'user_set' => false,
			),
			'last_updated' => 0,
		),
	);

	/**
	 * Constructor to initialize defaults and register setting.
	 *
	 * @param \NewfoldLabs\WP\Container\Container|null $container Optional DI container.
	 */
	public function __construct( $container = null ) {
		if ( $container && $container->has( 'capabilities' ) ) {
			$capabilities = $container->get( 'capabilities' );
			$this->default_settings['cloudflare']['fonts']['value'] = (bool) $capabilities->get( 'hasCloudflareFonts' );
		}

		$this->default_settings['cloudflare']['last_updated'] = time();

		$this->register_settings();
		$this->initialize_settings();
	}

	/**
	 * Registers the `nfd_fonts_optimization` setting.
	 */
	private function register_settings() {
		register_setting(
			'general',
			self::SETTING_KEY,
			array(
				'type'              => 'object',
				'description'       => __( 'Settings for NFD Font Optimization.', 'wp-module-performance' ),
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->default_settings,
				'show_in_rest'      => array(
					'schema' => array(
						'type'                 => 'object',
						'properties'           => array(
							'cloudflare' => array(
								'type'        => 'object',
								'description' => __( 'Cloudflare-related font optimization settings.', 'wp-module-performance' ),
								'properties'  => array(
									'fonts'        => array(
										'type'        => 'object',
										'description' => __( 'Enable Cloudflare Font Optimization.', 'wp-module-performance' ),
										'properties'  => array(
											'value'    => array(
												'type'    => 'boolean',
												'default' => $this->default_settings['cloudflare']['fonts']['value'],
											),
											'user_set' => array(
												'type'    => 'boolean',
												'default' => $this->default_settings['cloudflare']['fonts']['user_set'],
											),
										),
									),
									'last_updated' => array(
										'type'        => 'integer',
										'description' => __( 'Timestamp of last update.', 'wp-module-performance' ),
										'default'     => $this->default_settings['cloudflare']['last_updated'],
									),
								),
							),
						),
						'additionalProperties' => false,
					),
				),
			)
		);
	}

	/**
	 * Initializes the setting if it doesn't exist.
	 */
	private function initialize_settings() {
		if ( get_option( self::SETTING_KEY, false ) === false ) {
			add_option( self::SETTING_KEY, $this->default_settings );
		}
	}

	/**
	 * Sanitizes the font optimization settings before saving.
	 *
	 * @param array $settings The input settings from the request.
	 * @return array Sanitized settings array with a timestamp.
	 */
	public function sanitize_settings( $settings ) {
		$existing = get_option( self::SETTING_KEY, array() );

		$fonts_value    = isset( $settings['cloudflare']['fonts']['value'] )
		? ! empty( $settings['cloudflare']['fonts']['value'] )
		: ( ! empty( $existing['cloudflare']['fonts']['value'] ) );
		$fonts_user_set = isset( $settings['cloudflare']['fonts']['user_set'] )
		? ! empty( $settings['cloudflare']['fonts']['user_set'] )
		: ( ! empty( $existing['cloudflare']['fonts']['user_set'] ) );

		return array(
			'cloudflare' => array(
				'fonts'        => array(
					'value'    => $fonts_value,
					'user_set' => $fonts_user_set,
				),
				'last_updated' => time(),
			),
		);
	}

	/**
	 * Refreshes legacy font settings based on Cloudflare capabilities.
	 *
	 * @param object|null $capabilities Optional capabilities object.
	 */
	public static function maybe_refresh_with_capabilities( $capabilities ) {
		$settings = get_option( self::SETTING_KEY, array() );

		if ( ! isset( $settings['cloudflare']['fonts'] ) ) {
			$settings['cloudflare']['fonts'] = array(
				'value'    => false,
				'user_set' => false,
			);
		}

		if ( is_array( $capabilities ) ) {
			$has_fonts = isset( $capabilities['hasCloudflareFonts'] ) ? (bool) $capabilities['hasCloudflareFonts'] : false;

			if ( $settings['cloudflare']['fonts']['user_set'] ) {
				if ( $settings['cloudflare']['fonts']['value'] && ! $has_fonts ) {
					$settings['cloudflare']['fonts']['value'] = false;
				}
			} elseif ( ! $settings['cloudflare']['fonts']['value'] && $has_fonts ) {
				$settings['cloudflare']['fonts']['value'] = true;
			}
		}

		$settings['cloudflare']['last_updated'] = time();
		update_option( self::SETTING_KEY, $settings );
	}

	/**
	 * Checks if Cloudflare font optimization is enabled.
	 *
	 * @return bool
	 */
	public static function is_cloudflare_fonts_enabled() {
		$settings = get_option( self::SETTING_KEY, array( 'cloudflare' => array( 'fonts' => array( 'value' => false ) ) ) );
		return ! empty( $settings['cloudflare']['fonts']['value'] );
	}
}
