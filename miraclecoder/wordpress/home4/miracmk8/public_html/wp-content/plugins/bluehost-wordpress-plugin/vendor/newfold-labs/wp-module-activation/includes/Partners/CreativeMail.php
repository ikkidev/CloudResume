<?php
/**
 * Creative Mail.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Creative Mail class.
 */
class CreativeMail extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		$this->disable_redirect();
		$this->dismiss_admin_notice();
	}

	/**
	 * Disable plugin activation redirect.
	 *
	 * @return void
	 */
	private function disable_redirect() {
		if ( get_option( 'ce4wp_activation_redirect' ) !== false ) {
			update_option( 'ce4wp_activation_redirect', false );
		}
	}

	/**
	 * Dismiss default admin notice.
	 *
	 * @return void
	 */
	private function dismiss_admin_notice() {
		if ( ! get_option( 'ce4wp_hide_banner:get_started', false ) ) {
			update_option( 'ce4wp_hide_banner:get_started', true );
		}
	}
}
