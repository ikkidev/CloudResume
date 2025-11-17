<?php
/**
 * Secure password module.
 *
 * @package Newfold\WP\Module\Secure_Passwords
 */

namespace Newfold\WP\Module\Secure_Passwords;

use stdClass;
use WP_User;
use WP_Error;

if ( ! defined( 'NFD_SECURE_PASSWORD_MODULE_VERSION' ) ) {
	define( 'NFD_SECURE_PASSWORD_MODULE_VERSION', '1.0.3' );
}

// The interval to inform the user of an insecure password when remind me later is clicked.
if ( ! defined( 'NFD_REMIND_INTERVAL' ) ) {
	define( 'NFD_REMIND_INTERVAL', DAY_IN_SECONDS * 90 );
}

// The interval to search the HaveIBeenPwned API.
if ( ! defined( 'NFD_CHECK_INTERVAL' ) ) {
	define( 'NFD_CHECK_INTERVAL', DAY_IN_SECONDS * 90 );
}

require_once 'includes/functions.php';
require_once 'includes/class-have-i-been-pwned-api.php';

/**
 * Begin a secure password check when a user attempts to authenticate.
 *
 * This is the last action hook before a user's password is hashed when logging in.
 * This hashes and stores the entered login information for use later in the
 * request if the credentials are correct and authentication is successful.
 *
 * @since 1.0.0
 *
 * @param string $user_login    User login.
 * @param string $user_password User password.
 */
function wp_authenticate( $user_login, $user_password ) {
	/*
	 * The wp_authenticate hook is triggered by default on all login screen loads.
	 * Checks should only happen when credentials are present and a login is
	 * being attempted.
	 */
	if ( empty( $user_login ) || empty( $user_password ) ) {
		return;
	}

	$password_checker = Have_I_Been_Pwned_API::init();
	$password_checker->store_hash( $user_password );
	$password_checker->store_user_login( $user_login );
}
add_action( 'wp_authenticate', __NAMESPACE__ . '\wp_authenticate', 10, 2 );

/**
 * Checks a user account for a leaked password on login.
 *
 * @since 1.0.0
 *
 * @param string  $user_login Username.
 * @param WP_User $user       WP_User object of the logged-in user.
 */
function wp_login( $user_login, $user ) {
	// Display the insecure password screen for insecure passwords when enough time has passed.
	if ( ! is_user_password_secure( $user->ID ) && show_insecure_password_screen( $user->ID ) ) {
		display_insecure_password_screen();
	}

	// See if it's time to recheck the password.
	if ( ! should_check_password( $user->ID ) ) {
		return;
	}

	/*
	 * When checking passwords on the wp_login action, the password is not available, but
	 * has already been stored in the Have_I_Been_Pwned class on the `wp_authenticate` action.
	 */
	$is_secure = is_password_secure( '', $user->ID );

	if ( is_wp_error( $is_secure ) ) {
		return;
	}

	if ( $is_secure ) {
		mark_password_secure( $user->ID );
	} else {
		mark_password_insecure( $user->ID );
		display_insecure_password_screen( $user->ID );
	}
}
add_action( 'wp_login', __NAMESPACE__ . '\wp_login', 10, 2 );

/**
 * Handles the insecure password login interstitial.
 *
 * When the user has clicked Remind me later, this snoozes the notification and
 * redirects the user appropriately. Otherwise, the insecure password
 * interstitial is shown.
 *
 * @since 1.0.0
 */
function login_form_nfd_sp_insecure_password() {
	/*
	 * Note that `is_user_logged_in()` will return false immediately after logging in
	 * as the current user is not set, see wp-includes/pluggable.php.
	 * However this action runs on a redirect after logging in.
	 */
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url() );
		exit;
	}

	if ( ! empty( $_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
	} else {
		$redirect_to = admin_url();
	}

	if ( ! empty( $_GET['nfd_sp_remind_later'] ) ) {
		if ( ! wp_verify_nonce( $_GET['nfd_sp_remind_later'], 'nfd_sp_remind_later_nonce' ) ) {
			wp_safe_redirect( wp_login_url() );
			exit;
		}

		update_user_meta( get_current_user_id(), 'nfd_sp_next_notice', time() + NFD_REMIND_INTERVAL );

		$redirect_to = add_query_arg( 'nfd_sp_dismissed', 1, $redirect_to );
		wp_safe_redirect( $redirect_to );
		exit;
	}

	require_once 'includes/insecure-password-screen.php';
}
add_action( 'login_form_nfd_sp_insecure_password', __NAMESPACE__ . '\login_form_nfd_sp_insecure_password' );

/**
 * Displays an admin notice when the insecure password page is dismissed.
 *
 * @since 1.0.0
 */
function admin_notices() {
	if ( ! isset( $_GET['nfd_sp_dismissed'] ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php
			printf(
				/* translators: %s: Human-readable time interval. */
				esc_html__( 'You will not see an insecure password notice for %s.', 'newfold' ),
				esc_html( human_time_diff( time() + NFD_REMIND_INTERVAL ) )
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', __NAMESPACE__ . '\admin_notices' );

/**
 * Remove related query args after processing.
 *
 * @since 1.0.0
 *
 * @param string[] $removable_query_args An array of query variable names to remove from a URL.
 * @return string[] An adjusted array of query variable names to remove from the URL.
 */
function removable_query_args( $removable_query_args ) {
	$removable_args[] = 'nfd_sp_dismissed';

	return $removable_args;
}
add_filter( 'removable_query_args', __NAMESPACE__ . '\removable_query_args' );

/**
 * Confirms a password is secure before changing a user's password.
 *
 * @since 1.0.0
 *
 * @param WP_Error $errors WP_Error object (passed by reference).
 * @param bool     $update Whether this is a user update.
 * @param stdClass $user   User object (passed by reference).
 */
function user_profile_update_errors( $errors, $update, $user ) {
	if ( empty( $user->user_pass ) ) {
		return;
	}

	$is_secure = is_password_secure( $user->user_pass );

	if ( is_wp_error( $is_secure ) ) {
		$errors->merge_from( $is_secure );
	} elseif ( ! $is_secure ) {
		$errors->add(
			'nfd_sp_insecure_password',
			__( 'Please choose a different password. The one entered was found in a database of insecure passwords.', 'newfold' )
		);
	}
}
add_action( 'user_profile_update_errors', __NAMESPACE__ . '\user_profile_update_errors', 10, 3 );

/**
 * Enforces secure passwords when performing a password reset.
 *
 * @since 1.0.0
 *
 * @param WP_User $user     The user.
 * @param string  $new_pass New user password.
 */
function reset_password( $user, $new_pass ) {
	$is_secure = is_password_secure( $new_pass );

	// The password could not be confirmed as secure.
	if ( is_wp_error( $is_secure ) ) {
		return;
	}

	if ( ! $is_secure ) {
		wp_safe_redirect( add_query_arg( array( 'nfd_sp_insecure_password', 1 ) ) );
		exit;
	}
}
add_action( 'reset_password', __NAMESPACE__ . '\reset_password', 10, 2 );

/**
 * Resets the user's secure password information after a password reset.
 *
 * @param WP_User $user     The user.
 */
function after_password_reset( $user ) {
	clear_user_meta( $user->ID );
}
add_action( 'after_password_reset', __NAMESPACE__ . '\after_password_reset' );

/**
 * Performs a secure password check on Ajax request.
 *
 * @since 1.0.0
 */
function ajax_sp_is_password_secure() {
	if ( ! isset( $_GET['password'] ) || empty( $_GET['password'] ) ) {
		wp_send_json( new WP_Error() );
	}

	$is_secure = is_password_secure( wp_unslash( $_GET['password'] ) );

	if ( is_wp_error( $is_secure ) ) {
		wp_send_json_error( $is_secure );
	}

	wp_send_json_success( $is_secure );
}
add_action( 'wp_ajax_sp-is-password-secure', __NAMESPACE__ . '\ajax_sp_is_password_secure' );
add_action( 'wp_ajax_nopriv_sp-is-password-secure', __NAMESPACE__ . '\ajax_sp_is_password_secure' );

/**
 * Ensures generated passwords are secure.
 *
 * To prevent excessive requests and infinite loops, the maximum number of
 * attempts is 3.
 *
 * @since 1.0.0
 *
 * @param string $password            The generated password.
 * @param int    $length              The length of password to generate.
 * @param bool   $special_chars       Whether to include standard special characters.
 * @param bool   $extra_special_chars Whether to include other special characters.
 */
function random_password( $password, $length, $special_chars, $extra_special_chars ) {
	// Handle admin area context
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		// Limit checks to specific admin pages
		if ( ! $screen || ! in_array(
			$screen->id,
			array(
				'profile',
				'user-edit',
				'setup-config',
			),
			true
		) ) {
			return $password; // Return original password if checks fail
		}
	}

	// Handle admin-ajax.php context
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$action = $_REQUEST['action'] ?? '';

		// Allow checks for specific actions related to password generation
		if ( ! in_array( $action, array( 'generate-password' ), true ) ) {
			return $password;
		}
	}

	// Handle REST API or frontend requests
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		$request_uri = $_SERVER['REQUEST_URI'] ?? '';

		// Allow checks only during password reset or specific API endpoints
		if ( strpos( $request_uri, '/wp-json/wp/v2/users/password-reset' ) === false &&
			strpos( $request_uri, '/wp-login.php?action=lostpassword' ) === false ) {
			return $password; // Return original password if checks fail
		}
	}

	// Proceed with the original function logic
	static $count = 1;

	$is_secure = is_password_secure( $password );

	// The password could not be confirmed as secure
	if ( is_wp_error( $is_secure ) ) {
		return $password;
	}

	// If 3 attempts have been made or the password is secure, return it
	if ( $count > 3 || $is_secure ) {
		return $password;
	}

	// Increment the counter and regenerate the password
	++$count;
	return wp_generate_password( $length, $special_chars, $extra_special_chars );
}
add_filter( 'random_password', __NAMESPACE__ . '\random_password', 10, 4 );

/**
 * Resets module related user meta when a user's password is changed.
 *
 * An additional check is performed to ensure the new password is secure.
 *
 * @since 1.0.0
 *
 * @param int     $user_id User ID.
 * @param WP_User $old_user_data Object containing user's data prior to update.
 * @param array   $userdata      The raw array of data passed to wp_insert_user().
 */
function profile_update( $user_id, $old_user_data, $userdata ) {
	if ( empty( $userdata['user_pass'] ) ) {
		return;
	}

	clear_user_meta( $user_id );

	$is_secure = is_password_secure( $userdata['user_pass'] );

	// The password could not be confirmed as secure.
	if ( is_wp_error( $is_secure ) ) {
		return;
	}

	if ( $is_secure ) {
		mark_password_secure( $user_id );
	} else {
		mark_password_insecure( $user_id );
	}
}
add_action( 'profile_update', __NAMESPACE__ . '\profile_update', 10, 3 );

/**
 * Enqueues module related scripts and styles.
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix The current admin page.
 */
function admin_enqueue_scripts( $hook_suffix ) {
	$hooks = array(
		'profile.php',
		'user-new.php',
		'user-edit.php',
	);

	if ( ! in_array( $hook_suffix, $hooks, true ) ) {
		return;
	}

	wp_enqueue_script( 'nfd-secure-passwords', plugins_url( 'assets/js/secure-passwords.js', __FILE__ ), array( 'wp-util' ), NFD_SECURE_PASSWORD_MODULE_VERSION, true );

	wp_enqueue_style( 'nfd-secure-passwords-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), NFD_SECURE_PASSWORD_MODULE_VERSION );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );

/**
 * Enqueues scripts and styles for the login screens.
 *
 * @since 1.0.0
 */
function login_enqueue_scripts() {
	wp_enqueue_script( 'nfd-secure-passwords', plugins_url( 'assets/js/secure-passwords.js', __FILE__ ), array( 'wp-util' ), NFD_SECURE_PASSWORD_MODULE_VERSION, true );

	wp_enqueue_style( 'nfd-secure-passwords-login', plugins_url( 'assets/css/login.css', __FILE__ ), array(), NFD_SECURE_PASSWORD_MODULE_VERSION );
}
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\login_enqueue_scripts' );
