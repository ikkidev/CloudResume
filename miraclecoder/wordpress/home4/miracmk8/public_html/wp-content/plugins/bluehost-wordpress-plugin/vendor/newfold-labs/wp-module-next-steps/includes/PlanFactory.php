<?php
/**
 * Plan Factory for Next Steps Data.
 *
 * @package WPPluginBluehost
 */

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;
use NewfoldLabs\WP\Module\NextSteps\Data\Plans\StorePlan;
use NewfoldLabs\WP\Module\NextSteps\Data\Plans\BlogPlan;
use NewfoldLabs\WP\Module\NextSteps\Data\Plans\CorporatePlan;
use function NewfoldLabs\WP\ModuleLoader\container;
use function NewfoldLabs\WP\Context\getContext;

/**
 * PlanFactory
 *
 * Handles plan creation, site type detection, and WordPress hooks for the Next Steps module.
 * Responsible for creating and instantiating plans based on site context.
 */
class PlanFactory {

	/**
	 * Transient name for Newfold solutions data
	 */
	const SOLUTIONS_TRANSIENT = 'newfold_solutions';

	/**
	 * Option name for onboarding site info
	 */
	const ONBOARDING_SITE_INFO_OPTION = 'nfd_module_onboarding_site_info';

	/**
	 * Available plan types, this maps the site_type from onboarding module to internal plan types
	 *
	 * Maps nfd_module_onboarding_site_info['site_type'] values to internal plan types:
	 * - 'personal' (onboarding) -> 'blog' (internal plan)
	 * - 'business' (onboarding) -> 'corporate' (internal plan)
	 * - 'ecommerce' (onboarding) -> 'ecommerce' (internal plan)
	 */
	const PLAN_TYPES = array(
		'personal'  => 'blog',
		'business'  => 'corporate',
		'ecommerce' => 'ecommerce',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize default steps on init
		\add_action( 'init', array( __CLASS__, 'load_default_steps' ), 1 );
	}

	/**
	 * Load default steps on init
	 *
	 * @return void
	 */
	public static function load_default_steps() {
		// Only load if we're in admin or doing AJAX
		if ( ! \is_admin() && ! \wp_doing_ajax() ) {
			return;
		}

		// Check if we already have a plan loaded
		$current_plan = PlanRepository::get_current_plan();
		if ( $current_plan ) {
			return;
		}

		// Load default plan based on site type
		$site_type    = self::determine_site_type();
		$default_plan = self::create_plan( $site_type );
		if ( $default_plan ) {
			PlanRepository::save_plan( $default_plan );
		}
	}


	/**
	 * Detect site type based on various factors
	 *
	 * @return string The detected site type
	 */
	public static function detect_site_type() {
		// Check if WooCommerce is active
		if ( self::is_ecommerce_site() ) {
			return 'ecommerce';
		}

		// Check if it's a corporate/business site
		if ( self::is_corporate_site() ) {
			return 'corporate';
		}

		// Default to blog/personal
		return 'blog';
	}

	/**
	 * Check if site is ecommerce
	 *
	 * @return bool Whether the site is ecommerce
	 */
	private static function is_ecommerce_site() {
		return \class_exists( 'WooCommerce' ) || \is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Check if site is corporate
	 *
	 * @return bool Whether the site is corporate
	 */
	private static function is_corporate_site() {
		// Check for business-related plugins or themes
		$business_plugins = array(
			'elementor-pro/elementor-pro.php',
			'wpforms/wpforms.php',
			'contact-form-7/wp-contact-form-7.php',
		);

		foreach ( $business_plugins as $plugin ) {
			if ( \is_plugin_active( $plugin ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine site type from onboarding data or detection
	 *
	 * @return string The determined site type
	 */
	public static function determine_site_type(): string {
		// First, try to get from onboarding data
		$onboarding_data = \get_option( self::ONBOARDING_SITE_INFO_OPTION, array() );
		$onboarding_data = is_array( $onboarding_data ) ? $onboarding_data : array();
		$site_type       = $onboarding_data['site_type'] ?? '';

		if ( ! empty( $site_type ) && \array_key_exists( $site_type, self::PLAN_TYPES ) ) {
			return self::PLAN_TYPES[ $site_type ];
		}

		// Check transient for solutions data (for testing compatibility)
		$solutions_data = \get_transient( self::SOLUTIONS_TRANSIENT );
		if ( $solutions_data && isset( $solutions_data['solution'] ) ) {
			$solution = $solutions_data['solution'];
			switch ( $solution ) {
				case 'WP_SOLUTION_COMMERCE':
					return 'ecommerce';
				case 'WP_SOLUTION_SERVICE':
					return 'corporate';
				case 'WP_SOLUTION_CREATOR':
					return 'blog';
			}
		}

		// Fall back to detection
		return self::detect_site_type();
	}

	/**
	 * Create a plan by type
	 *
	 * @param string $plan_type Plan type to create
	 * @param array  $custom_plan_data Custom plan data
	 * @return Plan The created plan
	 */
	public static function create_plan( string $plan_type, array $custom_plan_data = array() ): Plan {

		// ecommerce plan
		if ( 'ecommerce' === $plan_type ) {
			return StorePlan::get_plan();
		}

		// corporate plan
		if ( 'corporate' === $plan_type ) {
			return CorporatePlan::get_plan();
		}

		// custom plan
		if ( 'custom' === $plan_type && ! empty( $custom_plan_data ) ) {
			return new Plan( $custom_plan_data );
		}

		// if blog type or anything else (blog is default)
		return BlogPlan::get_plan();
	}

	/**
	 * Load default plan based on site type
	 *
	 * @return Plan The loaded plan
	 */
	public static function load_default_plan(): Plan {
		$plan_type = self::determine_site_type();
		return self::create_plan( $plan_type );
	}

	/**
	 * Get current plan type
	 *
	 * @return string The current plan type
	 */
	public static function get_current_plan_type(): string {
		$current_plan = PlanRepository::get_current_plan();
		if ( $current_plan ) {
			return $current_plan->type;
		}
		return self::determine_site_type();
	}
}
