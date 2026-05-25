<?php
/**
 * General module functions.
 *
 * @package Newfold\WP\Module\Secure_Passwords
 */

namespace Newfold\WP\Module\Secure_Passwords;

use Newfold\WP\Module\Secure_Passwords\Have_I_Been_Pwned_API;
use WP_Error;
use WP_User;

/**
 * Checks if a user's password is secure.
 *
 * @since 1.0.0
 *
 * @param int $user_id Optional. User ID to check.
 * @return bool true if password is secure, false if not.
 */
function is_user_password_secure( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	return ! (bool) get_user_meta( $user_id, 'nfd_sp_password_insecure', true );
}

/**
 * Checks if a password is secure.
 *
 * When a user ID is provided, the Have_I_Been_Pwned_API::user_login property will be checked
 * for a match. This is required when checking a password on login to ensure the pre-authenticated
 * user matches the actual authenticated one.
 *
 * @since 1.0.0
 *
 * @param string $password Optional. The password to check. Can be a SHA1 hash or plain text.
 * @param int    $user_id Optional. The ID of the user the password belongs to.
 * @return WP_Error|bool Whether the password is secure. WP_Error on failure.
 */
function is_password_secure( $password = '', $user_id = 0 ) {
	$password_checker = Have_I_Been_Pwned_API::init();

	if ( ! empty( $user_id ) ) {
		$user = new WP_User( $user_id );

		if ( $password_checker->user_login !== $user->user_login ) {
			return new WP_Error(
				'nfd_sp_user_mismatch',
				'The pre and post-authentication users do not match.'
			);
		}
	}

	$leaked = $password_checker->has_password_been_leaked( $password );

	if ( is_wp_error( $leaked ) ) {
		return $leaked;
	}

	return ! (bool) $leaked;
}

/**
 * Marks a user's password as secure.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 */
function mark_password_secure( $user_id ) {
	delete_user_meta( $user_id, 'nfd_sp_password_insecure' );
	record_password_check( $user_id );
}

/**
 * Marks a user's password as insecure.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 */
function mark_password_insecure( $user_id ) {
	update_user_meta( $user_id, 'nfd_sp_password_insecure', true );
	record_password_check( $user_id );
}

/**
 * Updates the last password check timestamp in user meta.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 */
function record_password_check( $user_id ) {
	update_user_meta( $user_id, 'nfd_sp_last_check', time() );
}

/**
 * Determine if a password should be checked.
 *
 * User passwords are only checked against the Have I Been Pwned API once enough time has passed.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 * @return bool Whether the password should be checked for security.
 */
function should_check_password( $user_id ) {

	if ( wp_get_environment_type() === 'local' ) {
		return false;
	}

	// Password is not currently marked as insecure. Check every 30 days.
	$last_check = (int) get_user_meta( $user_id, 'nfd_sp_last_check', true );

	// Only check for password compromises when enough time has passed.
	if ( $last_check < time() - NFD_CHECK_INTERVAL ) {
		return true;
	}

	return false;
}

/**
 * Checks if the insecure password screen should be shown.
 *
 * When a user has clicked "Remind me later" on the insecure password screen,
 * the screen will be hidden for a period of time.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 * @return bool Whether the insecure password screen should be shown.
 */
function show_insecure_password_screen( $user_id ) {
	$next_notice = (int) get_user_meta( $user_id, 'nfd_sp_next_notice', true );

	if ( time() > $next_notice ) {
		return true;
	}

	return false;
}

/**
 * Clears module related user meta when a user is updated.
 *
 * @since 1.0.0
 *
 * @param int $user_id User ID.
 */
function clear_user_meta( $user_id ) {
	delete_user_meta( $user_id, 'nfd_sp_password_insecure' );
	delete_user_meta( $user_id, 'nfd_sp_last_check' );
	delete_user_meta( $user_id, 'nfd_sp_next_notice' );
}

/**
 * Redirects a user to the insecure password interstitial.
 *
 * @since 1.0.0
 */
function display_insecure_password_screen() {
	if ( ! empty( $_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
	} else {
		$redirect_to = admin_url();
	}

	wp_safe_redirect(
		add_query_arg(
			'action',
			'nfd_sp_insecure_password',
			wp_login_url( $redirect_to )
		)
	);
	exit;
}
