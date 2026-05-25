<?php
/**
 * Jetpack.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Jetpack class.
 */
class Jetpack extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'disable_redirect' ), 5 ); // Priority 5 to run before Jetpack's own hook.
	}

	/**
	 * Disable plugin activation redirect.
	 *
	 * @return void
	 */
	public function disable_redirect() {
		if ( get_transient( 'activated_jetpack' ) ) {
			delete_transient( 'activated_jetpack' );
		}
	}
}
