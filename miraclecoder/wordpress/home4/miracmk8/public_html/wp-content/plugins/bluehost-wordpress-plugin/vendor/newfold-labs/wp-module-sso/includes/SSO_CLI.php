<?php
namespace NewFoldLabs\WP\Module\SSO;

/**
 * Class SSO_CLI
 */
class SSO_CLI extends \WP_CLI_Command {

	/**
	 * @var string - Stored transient key used for SSO.
	 */
	public static $transient_slug = 'sso_token';

	/**
	 * @var string - Nonce validation key.
	 */
	public static $nonce_slug = 'newfold-sso';

	/**
	 * @var string - Nonce action key.
	 */
	public static $nonce_action = 'sso-check';

	/**
	 * @var int Time for nonce token to be valid.
	 */
	public $expiry_min = 3;

	/**
	 * @var string - Cryptographic salt.
	 */
	protected $salt;

	/**
	 * @var string - Validation nonce.
	 */
	protected $nonce;

	/**
	 * @var string - Cryptographic hash.
	 */
	protected $hash;

	/**
	 * Single Sign On via WP-CLI.
	 *
	 * @param  null  $args Unused.
	 * @param  array $assoc_args Additional args to define which user or role to login as.
	 */
	public function __invoke( $args, $assoc_args ) {

		$this->create_salt_nonce_and_hash();

		$params = $this->build_request_params(
			$assoc_args,
			array(
				'action' => static::$nonce_action,
				'salt'   => $this->salt,
				'nonce'  => $this->nonce,
			)
		);

		set_transient(
			static::$transient_slug,
			$this->hash,
			MINUTE_IN_SECONDS * $this->expiry_min
		);

		$link = add_query_arg( $params, admin_url( 'admin-ajax.php' ) );

		if ( isset( $assoc_args['url-only'] ) ) {
			\WP_CLI::log( $link );
		} else {
			/* Translators: %d number */
			$this->success( sprintf( __( 'Single-use login link valid for %d minutes', 'wp-module-sso' ), $this->expiry_min ) );
			$this->colorize_log( $link, 'underline' );
		}
	}

	/**
	 * Build request parameters for SSO URL.
	 *
	 * @param array $assoc_args
	 * @param array $params
	 *
	 * @return array
	 */
	protected function build_request_params( $assoc_args, $params ) {
		if ( ! empty( $assoc_args ) ) {
			if ( isset( $assoc_args['role'] ) ) {
				$user = get_users(
					array(
						'role'   => 'administrator',
						'number' => 1,
					)
				);
				if ( is_array( $user ) && is_a( $user[0], 'WP_User' ) ) {
					$params['user'] = $user[0]->ID;
				}
			}

			if ( isset( $assoc_args['email'] ) ) {
				$user = get_user_by( 'email', $assoc_args['email'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['username'] ) ) {
				$user = get_user_by( 'login', $assoc_args['username'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['id'] ) ) {
				$user = get_user_by( 'ID', $assoc_args['id'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['min'] ) ) {
				$this->expiry_min = (int) $assoc_args['min'];
			}
		}

		return $params;
	}

	/**
	 * Setup cryptographic strings for SSO link.
	 */
	protected function create_salt_nonce_and_hash() {
		$this->salt  = wp_generate_password( 32, false );
		$this->nonce = wp_create_nonce( static::$nonce_slug );
		$this->hash  = substr(
			base64_encode( hash( 'sha256', $this->nonce . $this->salt, false ) ),
			0,
			64
		);
	}


	/**
	 * Helper to format data into tables.
	 *
	 * By default, the method creates simple $key => $value tables.
	 * Set $type to 'adv' and the table inherits keys from $data. DATA MUST BE UNIFORM & MATCH FIRST ROW.
	 *
	 * 1. Provide $data as an array or object
	 * 2. Provide $keys as two strings -- by default 'DETAIL' and 'VALUE' are used.
	 * 3. Prints ASCII Table
	 *
	 * @param array  $data
	 * @param array  $keys
	 * @param string $type
	 */
	protected function table( $data, $keys = array( 'DETAIL', 'VALUE' ), $type = 'simple' ) {
		if ( empty( $data ) ) {
			return;
		}

		if ( 'adv' === $type ) {
			$items = $data;
			$keys  = array_keys( array_shift( $data ) );
		} else {
			$items = array();
			foreach ( $data as $detail => $value ) {
				$items[] = array(
					$keys[0] => $detail,
					$keys[1] => $value,
				);
			}
		}

		Utils\format_items( 'table', $items, $keys );
	}

	/**
	 * Creates Heading with Blue background and Grey text.
	 *
	 * @param string $message
	 * @param string $emoji
	 */
	protected function bold_heading( $message, $emoji = '' ) {
		$this->colorize_log( $message, '4', 'W', $emoji );
	}

	/**
	 * Formatted Success message.
	 *
	 * @param string $message
	 */
	protected function success( $message, $silent = false ) {
		$pre_ = $silent ? '' : __( 'Success: ', 'wp-module-sso' );
		$this->colorize_log( $pre_ . $message, '2', 'k', '✅' );
	}

	/**
	 * Formatted Info message.
	 *
	 * @param string $message
	 */
	protected function info( $message ) {
		$this->colorize_log( $message, '4', 'W', 'ℹ️' );
	}

	/**
	 * Formatted Warning message.
	 *
	 * @param string $message
	 */
	protected function warning( $message ) {
		$this->colorize_log( $message, '3', 'k', '⚠️' );
	}

	/**
	 * Formatted Error message. Halts by default.
	 *
	 * @param string $message
	 * @param bool   $silent
	 * @param bool   $halt
	 * @param int    $code
	 *
	 * @throws \WP_CLI\ExitException
	 */
	protected function error( $message, $silent = false, $halt = true, $code = 400 ) {
		$pre_ = $silent ? '' : __( 'Error: ', 'wp-module-sso' );
		$this->colorize_log( $pre_ . $message, '1', 'W', '🛑️' );
		if ( $halt ) {
			WP_CLI::halt( $code );
		}
	}

	/**
	 * Formatting helper for colorized messages.
	 *
	 * @param string $message
	 * @param string $background
	 * @param string $text_color
	 * @param string $emoji_prefix
	 */
	protected function colorize_log( $message = '', $background = '', $text_color = '%_', $emoji_prefix = '' ) {
		if ( ! empty( $background ) ) {
			$background = '%' . $background;
		}

		if ( ! empty( $text_color ) && false === strpos( $text_color, '%' ) ) {
			$text_color = '%' . $text_color;
		}

		if ( ! empty( $emoji_prefix ) ) {
			$message = $emoji_prefix . '  ' . $message;
		}

		\WP_CLI::log( \WP_CLI::colorize( $background . $text_color . $message . '%n' ) );
	}

	/**
	 * Empty linebreak
	 */
	protected function new_line() {
		\WP_CLI::log( __return_empty_string() );
	}

	/**
	 * Helper function for returning clean JSON response.
	 *
	 * @param array|string $data - Provide well-formed array or existing JSON string.
	 */
	protected function log_to_json( $data ) {
		if ( is_array( $data ) ) {
			\WP_CLI::log( json_encode( $data ) );
		} elseif ( is_array( json_decode( $data, true ) ) ) {
			\WP_CLI::log( $data );
		} else {
			$this->error( __( 'Provided $data wasn\'t valid array or JSON string.', 'wp-module-sso' ) );
		}
	}

	/**
	 * Formatted Confirm Dialog. A 'n' response breaks the thread.
	 *
	 * @param string $question
	 * @param string $type
	 *
	 * @throws \WP_CLI\ExitException
	 */
	protected function confirm( $question, $type = 'normal' ) {
		switch ( $type ) {
			case 'omg':
				\WP_CLI::confirm( $this->warning( '☢ 🙊 🙈 🙊 ☢️  ' . $question ) );
				break;
			case 'red':
				\WP_CLI::confirm( $this->error( $question, true ) );
				break;
			case 'yellow':
				\WP_CLI::confirm( $this->warning( $question ) );
				break;
			case 'green':
				\WP_CLI::confirm( $this->success( $question ) );
				break;
			case 'underline':
				\WP_CLI::confirm( $this->colorize_log( $question, '', 'U' ) );
				break;
			case 'normal':
			default:
				\WP_CLI::confirm( $question );
				break;
		}
	}
}
