<?php
/**
 * Youzer plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'wp_enqueue_scripts', 'bimber_youzer_enqueue_assets', 20 );
add_filter( 'bimber_author_info_box_bio', 'bimber_youzer_use_bio_in_author_info_box', 10, 2 );

//
// Snax integration.
//

// Override defaults.
add_filter( 'yz_default_options', 'bimber_snax_youzer_default_options' );

// Disable Login Popup.
add_filter( 'snax_enable_login_popup', '__return_false', 99 );
add_action( 'snax_after_admin_setting_enable_login_popup', 'bimber_youzer_snax_login_popup_unavailability_info' );

// Disable Social Login.
add_filter( 'snax_slog_enabled', '__return_false', 99 );
add_action( 'snax_after_snax_admin_setting_slog_enabled', 'bimber_youzer_snax_social_login_unavailability_info' );

// Disable reCaptcha.
add_filter( 'snax_recatpcha_enabled_for_login_form', '__return_false', 99 );
add_action( 'snax_after_snax_admin_setting_callback_login_recaptcha', 'bimber_youzer_snax_login_recaptcha_unavailability_info' );

/**
 * Use Biographical Info instead of WP Admin > Users > User > Bio in author info box.
 *
 * @param string $bio           Bio.
 * @param int    $user_id       User id.
 * @return string
 */
function bimber_youzer_use_bio_in_author_info_box( $bio, $user_id ) {
	if ( function_exists( 'xprofile_get_field_data' ) ) {
		$bio_field_name = apply_filters( 'bimber_youzer_bio_field_name', __( 'Biographical Info', 'youzer' ) );

		// Get ID by name. If user deleted that field, it won't be used.
		$long_id = xprofile_get_field_id_from_name( $bio_field_name );

		if ( $long_id ) {
			$long_field = xprofile_get_field( $long_id );

			if ( ! $long_field->id || (int) $long_id !== (int) $long_field->id ) {
				return $bio;
			}

			$description = xprofile_get_field_data( $long_id, $user_id );

			if ( ! empty( $description ) ) {
				$bio = $description;
			}
		}
	}

	return $bio;
}

/**
 * Override default options
 *
 * @param array $default_options        Options.
 *
 * @return array
 */
function bimber_snax_youzer_default_options( $default_options ) {

	//
	// Login Popup.
	//

	// Enable Youzer Login Popup if Snax Popup was enabled.Read value directly from db to omit "snax_enable_login_popup" filter.
	$snax_login_popup_enabled = 'standard' === get_option( 'snax_enable_login_popup', 'standard' );

	if ( $snax_login_popup_enabled ) {
		$default_options['yz_enable_login_popup'] = 'on';
	}

	return $default_options;
}

/**
 * Enqueue Youzer Plugin integration assets.
 */
function bimber_youzer_enqueue_assets() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	// Global styles.
	wp_enqueue_style( 'bimber-youzer', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/youzer-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-youzer', 'rtl', 'replace' );

	// Global scripts.
	wp_enqueue_script( 'bimber-youzer', $uri . 'js/youzer.js', array( 'youzer' ), $version, true );
	wp_localize_script( 'bimber-youzer', 'bimber_youzer', array(
	    // Youzer bug: change 'off' to 'on' at line 90 in includes/logy/class.logy.php
		'login_popup_active' => function_exists( 'yz_option' ) && ( yz_option( 'yz_enable_login_popup', 'on' ) == 'on' ),
	) );
}

/**
 * Shows info that Snax Login Popup is disabled.
 */
function bimber_youzer_snax_login_popup_unavailability_info() {
	$settings_url = admin_url( 'admin.php?page=yz-membership-settings&tab=login' );

	echo '<span class="notice notice-warning" style="padding: 4px 10px;">';
	printf( esc_html_x( 'Youzer plugin is active and overrides this option. Manage the login popup using Youzer %s', 'Youzer Integration', 'bimber' ), '<a href="'. esc_url( $settings_url ) .'" target="_blank">'.  esc_html__( 'login settings', 'youzer' ) .'</a>' );
	echo '</span>';
}

/**
 * Shows info that Snax Social Login is disabled.
 */
function bimber_youzer_snax_social_login_unavailability_info() {
	$settings_url = admin_url( 'admin.php?page=yz-membership-settings&tab=social_login' );

	echo '<span class="notice notice-warning" style="padding: 4px 10px;">';
	printf( esc_html_x( 'Youzer plugin is active and overrides this option. Manage the social login using Youzer %s', 'Youzer Integration', 'bimber' ), '<a href="'. esc_url( $settings_url ) .'" target="_blank">'.  esc_html__( 'social login settings', 'youzer' ) .'</a>' );
	echo '</span>';
}

/**
 * Shows info that Snax reCaptcha is disabled.
 */
function bimber_youzer_snax_login_recaptcha_unavailability_info() {
	$settings_url = admin_url( 'admin.php?page=yz-membership-settings&tab=captcha' );

	echo '<span class="notice notice-warning" style="padding: 4px 10px;">';
	printf( esc_html_x( 'Youzer plugin is active and overrides this option. Manage the captcha protection using Youzer %s', 'Youzer Integration', 'bimber' ), '<a href="'. esc_url( $settings_url ) .'" target="_blank">'.  esc_html__( 'captcha settings', 'youzer' ) .'</a>' );
	echo '</span>';
}
