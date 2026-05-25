<?php
/**
 * Wordpress Social Login plugin functions
 *
 * @package snax
 * @subpackage Plugins
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

remove_action( 'bp_before_account_details_fields', 'wsl_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_register_page', 'bimber_wsl_buddypress_registration_form', 10 );

function bimber_wsl_buddypress_registration_form() {
	if ( 'completed-confirmation' == bp_get_current_signup_step() ) {
		return;
	}

	if ( 'standard' === bimber_get_theme_option( 'bp', 'enable_sidebar' ) ) {
		$heading = _x( 'Register with your social network:', 'BuddyPress register form', 'bimber' );
	} else {
		$heading = _x( 'With social network:', 'BuddyPress register form', 'bimber' );
	}
	?>
	<div class="bp-register-wpsl">
		<h3 class="g1-beta"><?php echo esc_html( $heading ); ?></h3>
		<?php wsl_render_auth_widget_in_wp_login_form(); ?>
	</div>

	<h3 class="g1-beta"><?php echo esc_html_x( 'Or register with your email:', 'BuddyPress register form', 'bimber' ); ?></h3>
	<?php
}
