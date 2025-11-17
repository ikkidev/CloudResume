<?php
/**
 * Have I Been Pwned API.
 *
 * @package Newfold\WP\Module\Secure_Passwords
 */

namespace Newfold\WP\Module\Secure_Passwords;

use WP_Error;

/**
 * A class that interacts with the Have I Been Pwned API.
 *
 * The Have I Been Pwned API is a free resource for anyone to quickly assess
 * if they may have been put at risk due to an online account of theirs having
 * been compromised or "pwned" in a data breach.
 *
 * The API contains a list of more than half a billion passwords which have
 * previously been exposed in data breaches. These passwords are stored as a
 * SHA-1 hash of a UTF-8 encoded password.
 *
 * In order to protect the value of the source password being checked, the
 * k-Anonymity model is implemented to allow a password to be searched for by
 * partial hash. This allows the first 5 characters of a SHA-1 hash to be passed
 * to the API.
 *
 * @link https://haveibeenpwned.com/API/v3#PwnedPasswords
 * @link https://en.wikipedia.org/wiki/K-anonymity
 *
 * @since 1.0.0
 */
class Have_I_Been_Pwned_API {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var Have_I_Been_Pwned_API
	 */
	protected static $instance = null;

	/**
	 * The Have I Been Pwned API URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $api_url = 'https://api.pwnedpasswords.com/';

	/**
	 * Whether to use padding in search by range API requests.
	 *
	 * @link https://haveibeenpwned.com/API/v3#PwnedPasswordsPadding
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	private $use_padding = false;

	/**
	 * The user login for the password being checked.
	 *
	 * This is only used for temporary storage when attempting to login.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $user_login;

	/**
	 * The password being checked as a SHA1 hash.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $password_hash;

	/**
	 * Ensures only one instance of the class is created.
	 *
	 * This ensures the user_login and password_hash properties carry over
	 * during an authentication attempt so that the user can be properly verified.
	 *
	 * @since 1.0.0
	 *
	 * @return Have_I_Been_Pwned_API
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Stores a password as a SHA1 hash for later use.
	 *
	 * @since 1.0.0
	 *
	 * @param string $password Password to check. Can be a SHA1 hash or plain text.
	 */
	public function store_hash( $password ) {
		/*
		 * This temporarily limits the class to checking one password per request.
		 *
		 * The random_password filter is somehow causing the hashed password that is stored
		 * to be changed between the wp_authenticate and wp_login actions.
		 */
		if ( ! empty( $this->password_hash ) ) {
			return;
		}

		$password = trim( $password );

		if ( empty( $password ) ) {
			return;
		}

		// Don't re-hash strings that are already SHA1 hashes.
		if ( ! preg_match( '/^[0-9a-f]{40}$/i', $password ) ) {
			$password = sha1( $password );
		}

		$this->password_hash = strtoupper( $password );
	}

	/**
	 * Stores the user login for the password being checked.
	 *
	 * @since 1.0.0
	 *
	 * @param string $user_login User login.
	 */
	public function store_user_login( $user_login ) {
		$this->user_login = $user_login;
	}

	/**
	 * Checks a password for leaks.
	 *
	 * @since 1.0.0
	 *
	 * @param string $password Optional. The password to check. Can be a SHA1 hash or plain text.
	 * @return WP_Error|bool WP_Error on failure, true when a password has been leaked, false when
	 *                       the password has not been leaked and can be considered secure.
	 */
	public function has_password_been_leaked( $password = '' ) {
		$this->store_hash( $password );

		if ( empty( $this->password_hash ) ) {
			return new WP_Error(
				'nfd_sp_empty_password',
				'A password was not passed to check.'
			);
		}

		$potential_matches = $this->search_range();

		if ( is_wp_error( $potential_matches ) ) {
			return $potential_matches;
		}

		return $this->scan_for_match( $potential_matches );
	}

	/**
	 * Performs a search by range API request.
	 *
	 * @link https://haveibeenpwned.com/API/v3#SearchingPwnedPasswordsByRange
	 *
	 * @since 1.0.0
	 *
	 * @return array|WP_Error An array of potential matching SHA1 hashes on success. A WP_Error on failure.
	 */
	public function search_range() {
		$args        = array();
		$request_url = $this->api_url . 'range/' . substr( $this->password_hash, 0, 5 );

		if ( $this->use_padding ) {
			$args['Add-Padding'] = true;
		}

		$result = self::make_request( $request_url, $args );

		if ( is_wp_error( $result ) ) {
			return $result;
		} elseif ( empty( $result ) ) {
			return array();
		}

		$potential_matches = array_map( 'trim', explode( "\n", $result ) );

		// Filter out padded entries.
		if ( $this->use_padding ) {
			$potential_matches = array_filter( $potential_matches, array( $this, 'filter_padded_entries' ) );
		}

		// Remove the appearance counts.
		$potential_matches = array_map( array( $this, 'map_remove_counts' ), $potential_matches );

		// Reindex array before returning.
		return array_values( $potential_matches );
	}

	/**
	 * Scans a list of partial hashes for a full hash match.
	 *
	 * @since 1.0.0
	 *
	 * @param array $potential_matches A list of hash suffixes of potential matches.
	 * @return bool true if a full hash match is found, false if not.
	 */
	public function scan_for_match( $potential_matches ) {
		if ( empty( $potential_matches ) ) {
			return false;
		}

		$first_five = substr( $this->password_hash, 0, 5 );

		foreach ( $potential_matches as $potential_match ) {
			if ( $this->password_hash === $first_five . $potential_match ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Performs a GET request to the Have I Been Pwned API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The API URL for the request.
	 * @param array  $args Optional. Request arguments. Default empty array.
	 * @return string|WP_Error Response body on success, WP_Error on failure.
	 */
	public function make_request( $url, $args = array() ) {
		$response = wp_remote_get( $url, $args );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error(
				'nfd_sp_api_error',
				'There was a problem checking this password for security.'
			);
		}

		$body = wp_remote_retrieve_body( $response );

		if ( false !== strpos( $body, 'hash prefix was not in a valid format' ) ) {
			return new WP_Error(
				'nfd_sp_invalid_hash',
				'An invalid hash was submitted to the API.'
			);
		}

		return $body;
	}

	/**
	 * Callback for array_filter() that removes padded entries.
	 *
	 * @see https://haveibeenpwned.com/API/v3#PwnedPasswordsPadding
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry Entry to check.
	 * @return bool Returns false when the entry is for padding purposes, true when a potential match.
	 */
	public function filter_padded_entries( $entry ) {
		if ( version_compare( phpversion(), '8.0', '>=' ) ) {
			return ! str_contains( $entry, ':0' );
		}

		return false === strpos( $entry, ':0' );
	}

	/**
	 * Remove the count off a partial hash.
	 *
	 * When the API returns a hash suffix as a potential match, it includes a
	 * count of how many times it appears in the data set. For our purposes, the
	 * count does not matter. The presence of the hash is enough to consider the
	 * password insecure.
	 *
	 * @since 1.0.0
	 *
	 * @param string $entry The partial hash followed by :X, where X is the count.
	 * @return string The partial hash with the count removed.
	 */
	public function map_remove_counts( $entry ) {
		return substr( $entry, 0, 35 );
	}
}
