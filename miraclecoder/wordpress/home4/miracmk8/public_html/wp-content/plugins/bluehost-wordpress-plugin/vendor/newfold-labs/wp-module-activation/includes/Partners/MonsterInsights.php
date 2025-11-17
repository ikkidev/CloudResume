<?php
/**
 * Monster Insights.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Monster Insights class.
 */
class MonsterInsights extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'disable_redirect' ) );
		add_action( 'admin_init', array( $this, 'dismiss_admin_notice' ) );
	}

	/**
	 * Disable plugin activation redirect.
	 *
	 * @return void
	 */
	public function disable_redirect() {
		add_filter(
			'monsterinsights_enable_onboarding_wizard',
			function () {
				return false;
			}
		);
	}

	/**
	 * Dismiss default admin notice.
	 *
	 * The default admin notice is indismisible and doesn't have a database option to dismiss it.
	 * Instead, Monster Insights decides to show it based on a series of logic checks.
	 * So, we have to unregister the admin notice action.
	 *
	 * @return void
	 */
	public function dismiss_admin_notice() {
		remove_action( 'admin_notices', 'monsterinsights_admin_setup_notices' );
	}
}
