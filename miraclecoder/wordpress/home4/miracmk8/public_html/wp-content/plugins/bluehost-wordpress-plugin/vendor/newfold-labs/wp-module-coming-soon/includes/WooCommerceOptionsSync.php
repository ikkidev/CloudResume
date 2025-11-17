<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

/**
 * Sync coming soon options between the brand plugin and WooCommerce.
 */
class WooCommerceOptionsSync {
	/**
	 * Service provider.
	 *
	 * @var Service
	 */
	private static $service;

	/**
	 * Flag to prevent infinite loops during synchronization.
	 *
	 * @var bool
	 */
	private static $syncing = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'update_option_nfd_coming_soon', array( __CLASS__, 'sync_options' ), 10, 3 );
		add_action( 'update_option_woocommerce_coming_soon', array( __CLASS__, 'sync_options' ), 10, 3 );
		add_action( 'update_option_woocommerce_store_pages_only', array( __CLASS__, 'sync_options' ), 10, 3 );
		add_action( 'add_option_woocommerce_coming_soon', array( __CLASS__, 'sync_when_woocommerce_option_is_added' ) );
		add_action( 'woocommerce_init', array( __CLASS__, 'sync_when_woocommerce_option_is_missing' ) );
	}

	/**
	 * Sync coming soon options between the brand plugin and WooCommerce.
	 *
	 * @param mixed  $old_value   The old option value.
	 * @param mixed  $new_value   The new option value.
	 * @param string $option_name The option name.
	 */
	public static function sync_options( $old_value, $new_value, $option_name ): void {
		// Prevent infinite loops.
		if ( self::$syncing ) {
			return;
		}

		self::$syncing = true;

		if ( 'nfd_coming_soon' === $option_name ) {
			// Update WooCommerce options to match nfd_coming_soon.
			self::sync_woocommerce_coming_soon_option( $new_value );
			self::sync_nfd_woocommerce_pages_only_option();
		}
	
		if ( 'woocommerce_coming_soon' === $option_name ) {
			// Update brand plugin's option to match woocommerce_coming_soon.
			self::sync_nfd_coming_soon_option( $new_value, $option_name );
		}

		if ( 'woocommerce_store_pages_only' === $option_name ) {
			// Update brand plugin's option to match woocommerce_store_pages_only.
			self::sync_nfd_coming_soon_option( $new_value, $option_name );
		}

		self::$syncing = false;
	}

	/**
	 * Get the service provider.
	 */
	private static function get_service(): Service {
		if ( ! self::$service ) {
			self::$service = new Service();
		}

		return self::$service;
	}

	/**
	 * Sync the woocommerce_coming_soon option with the nfd_coming_soon option.
	 *
	 * @param bool $new_value The new value of the option.
	 */
	private static function sync_woocommerce_coming_soon_option( $new_value ): void {
		if ( optionExists( 'woocommerce_coming_soon' ) ) {
			$value = wp_validate_boolean( $new_value );
			$value = $value ? 'yes' : 'no';

			update_option( 'woocommerce_coming_soon', $value );
		}
	}

	/**
	 * Sync the woocommerce_store_pages_only option with the nfd_coming_soon option.
	 */
	private static function sync_nfd_woocommerce_pages_only_option(): void {
		if ( optionExists( 'woocommerce_store_pages_only' ) ) {
			update_option( 'woocommerce_store_pages_only', 'no' );
		}
	}

	/**
	 * Sync the nfd_coming_soon option with the woocommerce_coming_soon || woocommerce_store_pages_only options.
	 *
	 * @param mixed  $new_value   The new value of the option.
	 * @param string $option_name The option name.
	 */
	private static function sync_nfd_coming_soon_option( $new_value, $option_name ): void {
		$nfd_coming_soon_service = self::get_service();

		$value = 'yes' === $new_value ? true : false;

		// Sync the coming soon options when the woocommerce_coming_soon option is updated.
		if ( 'woocommerce_store_pages_only' === $option_name ) {
			if ( $value ) {
				$nfd_coming_soon_service->disable();
			} else {
				$nfd_coming_soon_service->enable();
			}
		}

		// Sync the coming soon options when the woocommerce_coming_soon option is updated.
		if ( 'woocommerce_coming_soon' === $option_name ) {
			if ( $value ) {
				$nfd_coming_soon_service->enable();
			} else {
				$nfd_coming_soon_service->disable();
			}
		}
	}

	/**
	 * Sync the coming soon options when the woocommerce_coming_soon option is newly added.
	 */
	public static function sync_when_woocommerce_option_is_added(): void {
		$nfd_coming_soon_service = self::get_service();
		$new_value               = $nfd_coming_soon_service->is_enabled();

		self::sync_woocommerce_coming_soon_option( $new_value );
	}

	/**
	 * Sync options when WooCommerce is initialized but the 'woocommerce_coming_soon' option is not set.
	 */
	public static function sync_when_woocommerce_option_is_missing(): void {
		if ( optionExists( 'woocommerce_coming_soon' ) ) {
			return;
		}

		self::sync_when_woocommerce_option_is_added();
	}
}
