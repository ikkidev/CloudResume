<?php
/**
 * This module detects wp-cloud and adjusts features accordingly.
 *
 * @package NewfoldLabs\WP\Module\Atomic
 */

use function NewfoldLabs\WP\Context\getContext;

if ( function_exists( 'add_action' ) ) {

	/**
	 * Early Hooks
	 *
	 * These hooks need to be added before:
	 * - features module (plugins_loaded:3)
	 * - module loader (after_theme_setup:100)
	 */
	add_action(
		'plugins_loaded',
		function () {
			if ( 'atomic' === getContext( 'platform' ) ) {

				// Disable performance
				add_filter( 'newfold/features/filter/canToggle:performance', '__return_false' );
				add_filter( 'newfold/features/filter/isEnabled:performance', '__return_false' );

				// Disable staging
				add_filter( 'newfold/features/filter/canToggle:staging', '__return_false' );
				add_filter( 'newfold/features/filter/isEnabled:staging', '__return_false' );

				// Disable Help Center by default
				add_filter( 'newfold/features/filter/defaultValue:helpCenter', '__return_false' );

				// Disable WonderBlocks by default
				add_filter( 'newfold/features/filter/defaultValue:patterns', '__return_false' );
			}
		},
		// context is set on plugins_loaded priority 1
		2
	);

	/**
	 * Late Hooks
	 *
	 * These hook removals need to be removed after they are added
	 */
	add_action(
		'after_setup_theme',
		function () {
			if ( 'atomic' === getContext( 'platform' ) ) {

				// Disable plugin login redirects
				remove_action( 'login_redirect', array( 'Bluehost\LoginRedirect', 'on_login_redirect' ), 10, 3 );
				remove_action( 'login_init', array( 'Bluehost\LoginRedirect', 'on_login_init' ), 10, 3 );
				remove_action( 'admin_init', array( 'Bluehost\LoginRedirect', 'disable_yoast_onboarding_redirect' ), 2 );
				remove_filter( 'login_form_defaults', array( 'Bluehost\LoginRedirect', 'filter_login_form_defaults' ) );
				remove_filter( 'newfold_sso_success_url_default', array( 'Bluehost\LoginRedirect', 'get_default_redirect_url' ) );

				// Disable onboarding login redirects
				remove_filter( 'login_redirect', array( 'NewfoldLabs\WP\Module\Onboarding\LoginRedirect', 'wplogin' ), 10, 3 );
				remove_filter( 'newfold_sso_success_url', array( 'NewfoldLabs\WP\Module\Onboarding\LoginRedirect', 'sso' ), 10 );
				remove_filter(
					'nfd_module_onboarding_should_redirect_disable',
					array( 'NewfoldLabs\WP\Module\Onboarding\LoginRedirect', 'remove_handle_redirect_action' )
				);

				// Set option to not perform onboarding redirect too
				update_option( 'nfd_module_onboarding_should_redirect', '0' );
			}
		},
		101
	);

	/**
	 * Coming soon default value
	 *
	 * Set the default coming soon value for fresh installs to false on atomic
	 */
	add_filter(
		'newfold/coming-soon/filter/default/fresh',
		function ( $value ) {
			// Set default coming soon value to false on atomic platform
			// For some reason setContext is not reliable at this point
			if ( defined( 'IS_ATOMIC' ) && IS_ATOMIC ) {
				return false;
			}
			return $value;
		}
	);
}
