<?php

namespace NewfoldLabs\WP\Module\Performance\Images;

/**
 * Manages the registration and sanitization of image optimization settings.
 */
class ImageSettings {
	/**
	 * The setting key for image optimization.
	 */
	private const SETTING_KEY = 'nfd_image_optimization';

	/**
	 * Stores default image optimization settings.
	 *
	 * @var array
	 */
	private $default_settings = array();

	/**
	 * Constructor to initialize the settings and the listener.
	 *
	 * @param \NewfoldLabs\WP\Container\Container $container Dependency injection container.
	 */
	private static function get_default_settings( $container = null ) {
		$default = array(
			'enabled'                            => true,
			'bulk_optimization'                  => true,
			'prefer_optimized_image_when_exists' => true,
			'auto_optimized_uploaded_images'     => array(
				'enabled'                    => true,
				'auto_delete_original_image' => false,
			),
			'lazy_loading'                       => array(
				'enabled' => true,
			),
			'banned_status'                      => false,
			'monthly_usage'                      => array(
				'monthlyRequestCount' => 0,
				'maxRequestsPerMonth' => 100000,
			),
			'cloudflare'                         => array(
				'polish' => array(
					'value'    => false,
					'user_set' => false,
				),
				'mirage' => array(
					'value'    => false,
					'user_set' => false,
				),
			),
		);

		// Override with capability-aware defaults
		if (
		null !== $container &&
		$container->has( 'capabilities' )
		) {
			$capabilities = $container->get( 'capabilities' );

			$default['cloudflare']['polish']['value'] = (bool) $capabilities->get( 'hasCloudflarePolish' );
			$default['cloudflare']['mirage']['value'] = (bool) $capabilities->get( 'hasCloudflareMirage' );
		}

		return $default;
	}

	/**
	 * Constructor to initialize the settings and the listener.
	 *
	 * @param \NewfoldLabs\WP\Container\Container $container Dependency injection container.
	 */
	public function __construct( $container ) {
		$this->default_settings = self::get_default_settings( $container );
		$this->register_settings( $container );
		$this->initialize_settings();
	}

	/**
	 * Registers the `nfd_image_optimization` setting in WordPress.
	 */
	private function register_settings() {
		register_setting(
			'general',
			self::SETTING_KEY,
			array(
				'type'              => 'object',
				'description'       => __( 'Settings for NFD Image Optimization.', 'wp-module-performance' ),
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->default_settings,
				'show_in_rest'      => array(
					'schema' => array(
						'type'                 => 'object',
						'properties'           => array(
							'enabled'           => array(
								'type'        => 'boolean',
								'description' => __( 'Enable image optimization.', 'wp-module-performance' ),
								'default'     => $this->default_settings['enabled'],
							),
							'prefer_optimized_image_when_exists' => array(
								'type'        => 'boolean',
								'description' => __( 'Prefer WebP format when it exists.', 'wp-module-performance' ),
								'default'     => $this->default_settings['prefer_optimized_image_when_exists'],
							),
							'auto_optimized_uploaded_images' => array(
								'type'        => 'object',
								'description' => __( 'Auto-optimized uploaded images settings.', 'wp-module-performance' ),
								'properties'  => array(
									'enabled' => array(
										'type'        => 'boolean',
										'description' => __( 'Automatically optimize uploaded images.', 'wp-module-performance' ),
										'default'     => $this->default_settings['auto_optimized_uploaded_images']['enabled'],
									),
									'auto_delete_original_image' => array(
										'type'        => 'boolean',
										'description' => __( 'Delete the original uploaded image after optimization.', 'wp-module-performance' ),
										'default'     => $this->default_settings['auto_optimized_uploaded_images']['auto_delete_original_image'],
									),
								),
							),
							'lazy_loading'      => array(
								'type'        => 'object',
								'description' => __( 'Settings for lazy loading.', 'wp-module-performance' ),
								'properties'  => array(
									'enabled' => array(
										'type'        => 'boolean',
										'description' => __( 'Enable lazy loading.', 'wp-module-performance' ),
										'default'     => $this->default_settings['lazy_loading']['enabled'],
									),
								),
							),
							'bulk_optimization' => array(
								'type'        => 'boolean',
								'description' => __( 'Enable bulk optimization of images.', 'wp-module-performance' ),
								'default'     => $this->default_settings['bulk_optimization'],
							),
							'banned_status'     => array(
								'type'        => 'boolean',
								'description' => __( 'Indicates if the site is banned from image optimization.', 'wp-module-performance' ),
								'default'     => $this->default_settings['banned_status'],
							),
							'monthly_usage'     => array(
								'type'        => 'object',
								'description' => __( 'Monthly usage statistics for image optimization.', 'wp-module-performance' ),
								'properties'  => array(
									'monthlyRequestCount' => array(
										'type'        => 'integer',
										'description' => __( 'Number of requests made this month.', 'wp-module-performance' ),
										'default'     => $this->default_settings['monthly_usage']['monthlyRequestCount'],
									),
									'maxRequestsPerMonth' => array(
										'type'        => 'integer',
										'description' => __( 'Maximum allowed requests per month.', 'wp-module-performance' ),
										'default'     => $this->default_settings['monthly_usage']['maxRequestsPerMonth'],
									),
								),
							),
							'cloudflare'        => array(
								'type'        => 'object',
								'description' => __( 'Cloudflare-related image optimization options.', 'wp-module-performance' ),
								'properties'  => array(
									'polish' => array(
										'type'        => 'object',
										'description' => __( 'Enable Cloudflare Polish optimization.', 'wp-module-performance' ),
										'properties'  => array(
											'value'    => array(
												'type'    => 'boolean',
												'default' => $this->default_settings['cloudflare']['polish']['value'],
											),
											'user_set' => array(
												'type'    => 'boolean',
												'default' => false,
												'description' => 'Whether the value was explicitly set by the user.',
											),
										),
									),
									'mirage' => array(
										'type'        => 'object',
										'description' => __( 'Enable Cloudflare Mirage optimization.', 'wp-module-performance' ),
										'properties'  => array(
											'value'    => array(
												'type'    => 'boolean',
												'default' => $this->default_settings['cloudflare']['mirage']['value'],
											),
											'user_set' => array(
												'type'    => 'boolean',
												'default' => false,
												'description' => 'Whether the value was explicitly set by the user.',
											),
										),
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
	 * Initializes the setting if it does not exist.
	 */
	private function initialize_settings() {
		$current_settings = get_option( self::SETTING_KEY, false );

		if ( false === $current_settings || ! is_array( $current_settings ) ) {
			add_option( self::SETTING_KEY, $this->default_settings );
		}
	}

	/**
	 * Sanitizes the `nfd_image_optimization` settings.
	 *
	 * @param array $settings The input settings.
	 * @return array The sanitized settings.
	 */
	public function sanitize_settings( $settings ) {
		$existing_settings = get_option( self::SETTING_KEY, array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return array(
			'enabled'                            => isset( $settings['enabled'] ) ? ! empty( $settings['enabled'] ) : ( ! empty( $existing_settings['enabled'] ) ),
			'prefer_optimized_image_when_exists' => isset( $settings['prefer_optimized_image_when_exists'] ) ? ! empty( $settings['prefer_optimized_image_when_exists'] ) : ( ! empty( $existing_settings['prefer_optimized_image_when_exists'] ) ),
			'auto_optimized_uploaded_images'     => array(
				'enabled'                    => isset( $settings['auto_optimized_uploaded_images']['enabled'] ) ? ! empty( $settings['auto_optimized_uploaded_images']['enabled'] ) : ( ! empty( $existing_settings['auto_optimized_uploaded_images']['enabled'] ) ),
				'auto_delete_original_image' => isset( $settings['auto_optimized_uploaded_images']['auto_delete_original_image'] ) ? ! empty( $settings['auto_optimized_uploaded_images']['auto_delete_original_image'] ) : ( ! empty( $existing_settings['auto_optimized_uploaded_images']['auto_delete_original_image'] ) ),
			),
			'lazy_loading'                       => array(
				'enabled' => isset( $settings['lazy_loading']['enabled'] ) ? ! empty( $settings['lazy_loading']['enabled'] ) : ( ! empty( $existing_settings['lazy_loading']['enabled'] ) ),
			),
			'bulk_optimization'                  => isset( $settings['bulk_optimization'] ) ? ! empty( $settings['bulk_optimization'] ) : ( ! empty( $existing_settings['bulk_optimization'] ) ),
			'banned_status'                      => isset( $settings['banned_status'] ) ? ! empty( $settings['banned_status'] ) : ( ! empty( $existing_settings['banned_status'] ) ),
			'monthly_usage'                      => array(
				'monthlyRequestCount' => isset( $settings['monthly_usage']['monthlyRequestCount'] ) ? (int) $settings['monthly_usage']['monthlyRequestCount'] : ( isset( $existing_settings['monthly_usage']['monthlyRequestCount'] ) ? (int) $existing_settings['monthly_usage']['monthlyRequestCount'] : 0 ),
				'maxRequestsPerMonth' => isset( $settings['monthly_usage']['maxRequestsPerMonth'] ) ? (int) $settings['monthly_usage']['maxRequestsPerMonth'] : ( isset( $existing_settings['monthly_usage']['maxRequestsPerMonth'] ) ? (int) $existing_settings['monthly_usage']['maxRequestsPerMonth'] : 100000 ),
			),
			'cloudflare'                         => array(
				'polish' => array(
					'value'    => isset( $settings['cloudflare']['polish']['value'] )
						? (bool) $settings['cloudflare']['polish']['value']
						: (bool) ( $existing_settings['cloudflare']['polish']['value'] ?? false ),
					'user_set' => isset( $settings['cloudflare']['mirage']['user_set'] )
					? (bool) $settings['cloudflare']['mirage']['user_set']
					: (bool) ( $existing_settings['cloudflare']['mirage']['user_set'] ?? false ),
				),
				'mirage' => array(
					'value'    => isset( $settings['cloudflare']['mirage']['value'] )
					? (bool) $settings['cloudflare']['mirage']['value']
					: (bool) ( $existing_settings['cloudflare']['mirage']['value'] ?? false ),
					'user_set' => isset( $settings['cloudflare']['mirage']['user_set'] )
					? (bool) $settings['cloudflare']['mirage']['user_set']
					: (bool) ( $existing_settings['cloudflare']['mirage']['user_set'] ?? false ),
				),
			),
		);
	}

	/**
	 * Refreshes legacy settings with Cloudflare feature flags based on capabilities.
	 *
	 * @param object|null $capabilities Capabilities object (optional).
	 */
	public static function maybe_refresh_with_capabilities( $capabilities ) {
		$settings = get_option( self::SETTING_KEY, array() );

		// If the option doesn't exist or is empty, use default settings
		if ( empty( $settings ) || ! is_array( $settings ) ) {
			$settings = self::get_default_settings();
		}

		if ( ! isset( $settings['cloudflare']['polish'] ) || ! is_array( $settings['cloudflare']['polish'] ) ) {
			$settings['cloudflare']['polish'] = array(
				'value'    => false,
				'user_set' => false,
			);
		}
		if ( ! isset( $settings['cloudflare']['mirage'] ) || ! is_array( $settings['cloudflare']['mirage'] ) ) {
			$settings['cloudflare']['mirage'] = array(
				'value'    => false,
				'user_set' => false,
			);
		}

		if ( is_array( $capabilities ) ) {
			$has_polish = isset( $capabilities['hasCloudflarePolish'] ) ? (bool) $capabilities['hasCloudflarePolish'] : false;
			$has_mirage = isset( $capabilities['hasCloudflareMirage'] ) ? (bool) $capabilities['hasCloudflareMirage'] : false;

			// Polish
			if ( $settings['cloudflare']['polish']['user_set'] ) {
				if ( $settings['cloudflare']['polish']['value'] && ! $has_polish ) {
					// User enabled but capability is gone — disable it
					$settings['cloudflare']['polish']['value'] = false;
				}
			} elseif ( ! $settings['cloudflare']['polish']['value'] && $has_polish ) {
					// Not user set — follow capability
				$settings['cloudflare']['polish']['value'] = true;

			}

			// Mirage
			if ( $settings['cloudflare']['mirage']['user_set'] ) {
				if ( $settings['cloudflare']['mirage']['value'] && ! $has_mirage ) {
					$settings['cloudflare']['mirage']['value'] = false;
				}
			} elseif ( ! $settings['cloudflare']['mirage']['value'] && $has_mirage ) {
					$settings['cloudflare']['mirage']['value'] = true;
			}
		}

		update_option( self::SETTING_KEY, $settings );
	}


	/**
	 * Checks if image optimization is enabled.
	 *
	 * @return bool True if optimization is enabled, false otherwise.
	 */
	public static function is_optimization_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['enabled'] );
	}

	/**
	 * Checks if auto-optimization for uploaded images is enabled.
	 *
	 * @return bool True if auto-optimization is enabled, false otherwise.
	 */
	public static function is_auto_optimization_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['auto_optimized_uploaded_images']['enabled'] );
	}

	/**
	 * Checks if auto-deletion of the original image is enabled.
	 *
	 * @return bool True if auto-deletion is enabled, false otherwise.
	 */
	public static function is_auto_delete_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['auto_optimized_uploaded_images']['auto_delete_original_image'] );
	}

	/**
	 * Checks if lazy loading is enabled.
	 *
	 * @return bool True if lazy loading is enabled, false otherwise.
	 */
	public static function is_lazy_loading_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['lazy_loading']['enabled'] );
	}

	/**
	 * Checks if bulk optimization is enabled.
	 *
	 * @return bool True if bulk optimization is enabled, false otherwise.
	 */
	public static function is_bulk_optimization_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['bulk_optimization'] );
	}

	/**
	 * Checks if WebP preference is enabled.
	 *
	 * @return bool True if WebP preference is enabled, false otherwise.
	 */
	public static function is_webp_preference_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['prefer_optimized_image_when_exists'] );
	}

	/**
	 * Checks if the site is banned from image optimization.
	 *
	 * @return bool True if the site is banned, false otherwise.
	 */
	public static function is_banned() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );
		return ! empty( $settings['banned_status'] );
	}

	/**
	 * Retrieves the monthly usage statistics for image optimization.
	 *
	 * @return array An array containing `monthlyRequestCount` and `maxRequestsPerMonth`.
	 */
	public static function get_monthly_usage() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );

		// Ensure monthly_usage exists and return default values if not set
		return isset( $settings['monthly_usage'] ) && is_array( $settings['monthly_usage'] )
		? $settings['monthly_usage']
		: array(
			'monthlyRequestCount' => 0,
			'maxRequestsPerMonth' => 100000,
		);
	}

	/**
	 * Retrieves the image optimization settings.
	 *
	 * @param \NewfoldLabs\WP\Container\Container|null $container   Dependency injection container (optional).
	 * @param bool                                     $call_worker Whether to fetch the latest monthly usage from the worker. Default is true.
	 *
	 * @return array The current image optimization settings, including monthly usage and banned status.
	 */
	public static function get( $container, $call_worker = true ) {
		$settings = get_option( self::SETTING_KEY, array() );

		if ( ! is_array( $settings ) ) {
			$settings = self::get_default_settings();
		}

		if ( ! isset( $settings['banned_status'] ) ) {
			$settings['banned_status'] = self::is_banned();
		}

		if ( $call_worker && ( empty( $settings['monthly_usage'] ) || ! is_array( $settings['monthly_usage'] ) ) ) {
			$usage_data = ( new ImageService( $container ) )->get_monthly_usage_limit( true );
			if ( ! is_wp_error( $usage_data ) ) {
				$settings['monthly_usage'] = $usage_data;
				update_option( self::SETTING_KEY, $settings );
			} else {
				$settings['monthly_usage'] = isset( $settings['monthly_usage'] ) ? $settings['monthly_usage'] : array(
					'monthlyRequestCount' => 0,
					'maxRequestsPerMonth' => 100000,
				);
			}
		}

		return $settings;
	}

	/**
	 * Updates the image optimization settings.
	 *
	 * @param array                               $settings  The new settings array.
	 * @param \NewfoldLabs\WP\Container\Container $container Dependency injection container.
	 *
	 * @return bool true if the settings were updated successfully, false otherwise.
	 */
	public static function update( $settings, $container ) {
		$instance           = new self( $container );
		$sanitized_settings = $instance->sanitize_settings( $settings );
		return update_option( self::SETTING_KEY, $sanitized_settings );
	}

	/**
	 * Checks if Cloudflare Polish is enabled.
	 *
	 * @return bool True if Polish is enabled, false otherwise.
	 */
	public static function is_cloudflare_polish_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );

		return ! empty( $settings['cloudflare']['polish'] );
	}

	/**
	 * Checks if Cloudflare Mirage is enabled.
	 *
	 * @return bool True if Mirage is enabled, false otherwise.
	 */
	public static function is_cloudflare_mirage_enabled() {
		$settings = get_option( self::SETTING_KEY, self::get_default_settings() );

		return ! empty( $settings['cloudflare']['mirage'] );
	}
}
