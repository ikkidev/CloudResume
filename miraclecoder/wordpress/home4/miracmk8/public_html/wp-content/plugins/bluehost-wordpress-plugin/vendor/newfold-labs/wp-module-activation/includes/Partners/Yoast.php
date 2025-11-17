<?php
/**
 * Yoast.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Yoast class.
 */
class Yoast extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'disable_notice' ) );
		add_action( 'admin_init', array( $this, 'disable_onboarding_redirect' ) );

		// Disable premium upgrade block.
		add_filter( 'wpseo_premium_upgrade_admin_block', '__return_false' );

		// Additional dashboard-specific blocks.
		add_filter( 'wpseo_remove_premium_upsell_admin_block', '__return_true' );
		add_filter( 'wpseo_hide_premium_upsell_admin_block', '__return_true' );
	}

	/**
	 * Dismiss promotional admin notices for Yoast.
	 *
	 * @return void
	 */
	public function disable_notice() {
		if ( class_exists( 'WPSEO_Options' ) ) {
			// Dismiss admin notice.
			\WPSEO_Options::set( 'dismiss_configuration_workout_notice', true );

			// Dismiss only promotional notifications.
			\WPSEO_Options::set( 'dismiss_premium_deactivated', true );
			\WPSEO_Options::set( 'dismiss_premium_notices', true );
			\WPSEO_Options::set( 'dismiss_upsell_notice', true );

			// Keep the admin bar menu as it's functional.
			\WPSEO_Options::set( 'enable_admin_bar_menu', true );
		}
	}

	/**
	 * Disable redirect to Yoast onboarding.
	 *
	 * @return void
	 */
	public function disable_onboarding_redirect() {
		if ( class_exists( 'WPSEO_Options' ) ) {
			// Disable redirect to Yoast onboarding.
			\WPSEO_Options::set( 'should_redirect_after_install_free', false );
		}
	}
}
