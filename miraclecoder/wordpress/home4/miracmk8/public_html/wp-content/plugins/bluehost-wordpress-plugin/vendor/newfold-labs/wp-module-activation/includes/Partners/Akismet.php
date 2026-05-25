<?php
/**
 * Akismet.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Akismet class.
 */
class Akismet extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'disable_redirect' ), 5 ); // Priority 5 to run before Akismet's own hook.
		add_action( 'admin_init', array( $this, 'dismiss_admin_notice' ) );
	}

	/**
	 * Disable plugin activation redirect.
	 *
	 * @return void
	 */
	public function disable_redirect() {
		if ( get_option( 'Activated_Akismet' ) ) {
			delete_option( 'Activated_Akismet' );
		}
	}

	/**
	 * Dismiss admin notice.
	 *
	 * @return void
	 */
	public function dismiss_admin_notice() {
		add_filter(
			'akismet_view_arguments',
			function ( $args, $name ) {
				if ( 'notice' !== $name || ! isset( $args['type'] ) ) {
					return $args;
				}
				if ( 'plugin' === $args['type'] ) {
					$args['type'] = '';
				}

				return $args;
			},
			10,
			2
		);
	}
}
