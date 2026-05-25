<?php
/**
 * WordPress core customizations.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * WordPress class.
 */
class WordPress extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'disable_welcome_panel' ) );
	}

	/**
	 * Disable WordPress welcome panel.
	 *
	 * @return void
	 */
	public function disable_welcome_panel() {
		// Remove the welcome panel.
		remove_action( 'welcome_panel', 'wp_welcome_panel' );

		// Set user meta to hide welcome panel.
		if ( get_current_user_id() > 0 ) {
			update_user_meta( get_current_user_id(), 'show_welcome_panel', 0 );
		}
	}
}
