<?php
/**
 * Theme activation functions
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

define( 'BIMBER_MODE_WELCOME', 'welcome' );
define( 'BIMBER_MODE_IN_PROGRESS', 'in_progress' );
define( 'BIMBER_MODE_NORMAL', 'normal' );

/**
 * Redirect user to info page after activation
 */
function bimber_redirect_after_activation() {
	// Redirect to welcome page.
	$count       = 0;
	$attachments = wp_count_attachments();

	foreach ( $attachments as $type_counter ) {
		$count += $type_counter;
	}

	// Expire after a week.
	set_transient( 'bimber_initial_attachment_count', $count, 60 * 60 * 24 * 7 );

	//$nonce = wp_create_nonce( 'tgmpa-dismiss-' . get_current_user_id() );

	wp_safe_redirect( admin_url( 'themes.php?page=theme-options&group=registration' ) );
	return;
}

/**
 * Check whether theme is in normal mode
 *
 * @return bool
 */
function bimber_is_normal_mode_enabled() {
	return apply_filters( 'bimber_is_normal_mode_enabled', true );
}

/**
 * Check whether theme is in progress mode
 *
 * @return bool
 */
function bimber_is_in_progress_mode_enabled() {
	return apply_filters( 'bimber_is_in_progress_mode_enabled', false );
}

/**
 * Return current theme mode
 *
 * @return mixed|void
 */
function bimber_get_mode() {
	// If no value set in database (theme activate for the first time)
	// or if user didn't finish installation steps,
	// dashboard should be in "welcome" mode.
	return get_option( bimber_get_theme_id() . '_mode', BIMBER_MODE_WELCOME );
}

/**
 * Set theme mode
 *
 * @param string $mode      Allowed mode (normal, welocome, in_progress).
 */
function bimber_set_mode( $mode ) {
	update_option( bimber_get_theme_id() . '_mode', $mode );
}

