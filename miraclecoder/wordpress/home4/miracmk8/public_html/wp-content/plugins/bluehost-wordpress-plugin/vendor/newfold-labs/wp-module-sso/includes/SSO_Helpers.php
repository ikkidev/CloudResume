<?php

namespace NewFoldLabs\WP\Module\SSO;

class SSO_Helpers {

	/**
	 * SSO AJAX action.
	 */
	const ACTION = 'newfold_sso_login';

	/**
	 * SSO token meta key.
	 */
	const META_KEY = 'newfold_sso_token';

	/**
	 * Generate an SSO token for a user.
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	public static function generateToken( $user_id ) {
		return base64_encode(
			implode(
				':',
				[
					$user_id,
					time(),
					wp_generate_password( 64, true, true )
				]
			)
		);
	}

	/**
	 * Save an SSO token for a specific user.
	 *
	 * @param string $token
	 */
	public static function saveToken( $token ) {
		update_user_meta( self::getUserIdFromToken( $token ), self::META_KEY, $token );
	}

	/**
	 * Validate an SSO token.
	 *
	 * @param $token
	 *
	 * @return bool
	 */
	public static function validateToken( $token ) {

		// Decode token
		$parts = explode( ':', base64_decode( $token ), 3 );

		// Validate user
		$user_id = absint( array_shift( $parts ) );
		if ( ! $user_id ) {
			return false;
		}

		$user = get_user_by( 'id', $user_id );
		if ( ! $user || ! is_a( $user, \WP_User::class ) ) {
			return false;
		}

		// Validate timeframe
		$time = array_shift( $parts );
		if ( ! $time || ( $time + 600 ) < time() ) {
			return false;
		}

		// Validate token
		$user_token = get_user_meta( $user->ID, self::META_KEY, true );
		if ( $token !== $user_token ) {
			return false;
		}

		// Token is valid
		return true;
	}

	/**
	 * Get the WordPress user ID from a token.
	 *
	 * @param string $token
	 *
	 * @return int
	 */
	public static function getUserIdFromToken( $token ) {
		$parts = explode( ':', base64_decode( $token ), 3 );

		return absint( array_shift( $parts ) );
	}

	/**
	 * Get the WordPress user object from a token.
	 *
	 * @param string $token
	 *
	 * @return \WP_User|false
	 */
	public static function getUserFromToken( $token ) {
		return get_user_by( 'id', self::getUserIdFromToken( $token ) );
	}

	/**
	 * Log a failed SSO attempt.
	 */
	public static function logFailure() {
		$key   = 'newfold_sso_failure_count';
		$count = absint( get_transient( $key ) );
		set_transient( $key, $count + 1, MINUTE_IN_SECONDS * 5 );
	}

	/**
	 * Check if we should throttle attempts.
	 *
	 * @return bool
	 */
	public static function shouldThrottle() {
		return absint( get_transient( 'newfold_sso_failure_count' ) ) > 4;
	}

	/**
	 * Trigger an SSO failure.
	 */
	public static function triggerFailure() {

		self::logFailure();

		// Enable legacy action when necessary
		if ( has_action( 'eig_sso_fail' ) ) {
			do_action( 'eig_sso_fail' );
		}

		do_action( 'newfold_sso_fail' );

		wp_safe_redirect( wp_login_url() );
		exit;

	}

	/**
	 * Trigger an SSO success
	 *
	 * @param \WP_User $user
	 */
	public static function triggerSuccess( \WP_User $user ) {

		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login, $user );

		$redirect = self::getSuccessUrl();

		// Enable legacy action when necessary
		if ( has_action( 'eig_sso_success' ) ) {
			do_action( 'eig_sso_success', $user, $redirect );
		}

		do_action( 'newfold_sso_success', $user, $redirect );

		// Ensure the same token can't be used twice
		delete_user_meta( $user->ID, self::META_KEY );

		wp_safe_redirect( $redirect );
		exit;

	}

	/**
	 * Get the SSO success URL.
	 *
	 * @return string
	 */
	public static function getSuccessUrl() {
		$url = '';

		$params = array( 'bounce', 'redirect' );

		foreach ( $params as $param ) {
			$relative_path = esc_url_raw( filter_input( INPUT_GET, $param ) );
			if ( $relative_path ) {
				$url = admin_url( $relative_path );
				break;
			}
		}

		if ( $url ) {
			$params = $_GET;

			unset( $params['action'] );
			unset( $params['bounce'] );
			unset( $params['nonce'] );
			unset( $params['redirect'] );
			unset( $params['salt'] );
			unset( $params['token'] );
			unset( $params['user'] );

			// Persist all query params not used for SSO
			if ( ! empty( $params ) ) {
				foreach ( $params as $key => $value ) {
					$url = add_query_arg( $key, $value, $url );
				}
			}

		}

		if ( ! $url ) {
			$url = apply_filters( 'newfold_sso_success_url_default', admin_url() );
		}

		// Enable legacy filter when necessary
		if ( has_filter( 'eig_sso_redirect' ) ) {
			$url = (string) apply_filters( 'eig_sso_redirect', $url );
		}

		return (string) apply_filters( 'newfold_sso_success_url', $url );
	}

	/**
	 * Handle SSO login.
	 *
	 * @param string $token
	 */
	public static function handleLogin( $token ) {

		// No token provided
		if ( ! $token ) {
			wp_safe_redirect( wp_login_url() );
			exit;
		}

		// Too many failed attempts
		if ( self::shouldThrottle() ) {
			self::triggerFailure();
			exit;
		}

		$isValid = self::validateToken( $token );

		// Invalid token
		if ( ! $isValid ) {
			self::triggerFailure();
			exit;
		}

		self::triggerSuccess( self::getUserFromToken( $token ) );

	}

}
