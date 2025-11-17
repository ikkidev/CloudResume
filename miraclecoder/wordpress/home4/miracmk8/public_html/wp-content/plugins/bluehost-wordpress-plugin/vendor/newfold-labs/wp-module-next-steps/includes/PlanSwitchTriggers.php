<?php
/**
 * Plan Switch Triggers for Next Steps Module.
 *
 * @package WPPluginBluehost
 */

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;

/**
 * PlanSwitchTriggers
 *
 * Handles plan switching hooks and triggers for the Next Steps module.
 * Responsible for detecting changes that should trigger plan switches.
 */
class PlanSwitchTriggers {


	/**
	 * Constructor
	 *
	 * @param Container $container the container
	 */
	public function __construct( $container ) {
		// Hook into solution option changes for dynamic plan switching
		\add_action( 'update_option_' . PlanFactory::ONBOARDING_SITE_INFO_OPTION, array( __CLASS__, 'on_sitetype_change' ), 10, 2 );

		// Hook into WooCommerce activation to potentially switch to store plan
		\add_action( 'activated_plugin', array( __CLASS__, 'on_woocommerce_activation' ), 10, 2 );

		// Hook into language changes to resync next steps data
		\add_action( 'update_option_WPLANG', array( __CLASS__, 'on_language_change' ), 10, 2 );
		\add_action( 'switch_locale', array( __CLASS__, 'on_locale_switch' ), 10, 1 );
	}

	/**
	 * Handle site type changes
	 *
	 * @param array $old_value The old site type
	 * @param array $new_value The new site type
	 * @return void
	 */
	public static function on_sitetype_change( $old_value, $new_value ) {
		// Check if values are each an array
		if (
			! is_array( $old_value ) ||
			! is_array( $new_value )
		) {
			return;
		}

		$old_site_type = array_key_exists( 'site_type', $old_value ) ? $old_value['site_type'] : '';
		$new_site_type = array_key_exists( 'site_type', $new_value ) ? $new_value['site_type'] : '';

		if ( $old_site_type === $new_site_type ) {
			return;
		}

		// Check if the new site type is valid
		if ( ! array_key_exists( $new_site_type, PlanFactory::PLAN_TYPES ) ) {
			return; // Don't load any plan for invalid site types
		}

		// Convert onboarding site type to internal plan type
		$new_plan_type = PlanFactory::PLAN_TYPES[ $new_site_type ];

		// Switch to the new plan
		PlanRepository::switch_plan( $new_plan_type );
	}

	/**
	 * Handle WooCommerce activation
	 *
	 * @param string $plugin The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_woocommerce_activation( $plugin, $network_wide ) {
		if ( 'woocommerce/woocommerce.php' !== $plugin ) {
			return;
		}

		// Switch to ecommerce plan when WooCommerce is activated
		PlanRepository::switch_plan( 'ecommerce' );
	}

	/**
	 * Handle language changes
	 *
	 * @param string $old_value The old language code
	 * @param string $new_value The new language code
	 * @return void
	 */
	public static function on_language_change( $old_value, $new_value ): void {
		if ( $old_value === $new_value ) {
			return;
		}
		self::resync_next_steps_data( $new_value, 'site' );
	}

	/**
	 * Handle locale switch
	 *
	 * @param string $locale The new locale code
	 * @return void
	 */
	public static function on_locale_switch( $locale ) {
		self::resync_next_steps_data( $locale, 'locale_switch' );
	}

	/**
	 * Resync next steps data when language changes
	 *
	 * @param string $new_locale The new locale/language code
	 * @param string $change_type The type of change ('site', 'locale_switch')
	 * @return void
	 */
	private static function resync_next_steps_data( $new_locale, $change_type ) {
		// Get the saved plan data (preserves user progress)
		$plan_repository = \get_option( PlanRepository::OPTION, array() );
		$plan_repository = is_array( $plan_repository ) ? $plan_repository : array();
		$saved_data      = new Plan( $plan_repository );
		$saved_plan_type = $saved_data->type;

		// Load fresh plan data with new language context
		// We'll create the plan directly based on the saved plan ID
		if ( 'custom' === $saved_plan_type ) {
			// For custom plans, create a new plan with the same structure but updated language
			$new_plan = PlanFactory::create_plan( $saved_plan_type, $saved_data->to_array() );
		} else {
			$new_plan = PlanFactory::create_plan( $saved_plan_type );
		}

		if ( $new_plan ) {
			// Use Plan DTO's merge_with method to combine saved data with new translations
			// This preserves user progress while updating language content
			$merged_plan = $new_plan->merge_with( $saved_data );

			// Save the merged plan data
			$saved = PlanRepository::save_plan( $merged_plan );

			if ( $saved ) {
				// Clear any relevant caches
				\wp_cache_delete( 'nfd_next_steps', 'options' );
				// Trigger action for other components that might need to know about the sync
				\do_action( 'nfd_next_steps_language_synced', $new_locale, $change_type, $merged_plan );
			}
		}
	}
}
