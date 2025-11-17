<?php
/**
 * WP Forms.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * WP Forms class.
 */
class WpForms extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		$this->disable_redirect();
	}

	/**
	 * Disable plugin activation redirect.
	 *
	 * @return void
	 */
	private function disable_redirect() {
		if ( get_option( 'wpforms_activation_redirect' ) !== true ) {
			update_option( 'wpforms_activation_redirect', true );
		}
	}
}
