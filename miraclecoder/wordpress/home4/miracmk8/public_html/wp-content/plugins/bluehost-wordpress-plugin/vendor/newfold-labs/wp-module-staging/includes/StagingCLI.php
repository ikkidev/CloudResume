<?php
namespace NewfoldLabs\WP\Module\Staging;

/**
 * Class for Staging CLI commands
 */
class StagingCLI extends \WP_CLI_Command {
	/**
	 * Used internally to create staging environment.
	 *
	 * @param array $args       Command arguments
	 * @param array $assoc_args Associative command arguments.
	 *
	 * @throws \WP_CLI\ExitException On CLI failure.
	 */
	public function __invoke( $args, $assoc_args ) {

		if ( ! is_array( $args ) || ! isset( $args[0] ) ) {
			$this->error( __( 'No sub-command provided', 'wp-module-staging' ) );
		}

		switch ( $args[0] ) {

			case 'create':
				$this->render( Staging::getInstance()->createStaging() );
				break;

			case 'clone':
				$this->render( Staging::getInstance()->cloneProductionToStaging() );
				break;

			case 'destroy':
				$this->render( Staging::getInstance()->destroyStaging() );
				break;

			case 'sso_staging':
				$user_id = $this->get_admin_user_id();
				if ( ! $user_id ) {
					$this->error( __( 'Invalid user.', 'wp-module-staging' ) );
				}
				$this->render( Staging::getInstance()->switchTo( 'staging', $user_id ) );
				break;

			case 'sso_production':
				$user_id = $this->get_admin_user_id();
				if ( ! $user_id ) {
					$this->error( 'Invalid user.' );
				}
				$this->render( Staging::getInstance()->switchTo( 'production', $user_id ) );
				break;

			case 'deploy':
				$deploy_type = isset( $args[1] ) ? $args[1] : '';
				switch ( $deploy_type ) {
					case 'all':
					case 'both':
						$this->render( Staging::getInstance()->deployToProduction( 'all' ) );
						break;
					case 'db':
					case 'database':
						$this->render( Staging::getInstance()->deployToProduction( 'db' ) );
						break;
					case 'files':
						$this->render( Staging::getInstance()->deployToProduction( 'files' ) );
						break;
					default:
						$this->error( __( 'Invalid deploy type', 'wp-module-staging' ) );
				}
				break;

			case 'deploy_files':
				$this->render( Staging::getInstance()->deployToProduction( 'files' ) );
				break;

			case 'deploy_db':
				$this->render( Staging::getInstance()->deployToProduction( 'db' ) );
				break;

			case 'deploy_files_db':
				$this->render( Staging::getInstance()->deployToProduction( 'all' ) );
				break;

			default:
				$this->error( __( 'Invalid action', 'wp-module-staging' ) );
		}
	}

	/**
	 * Render a success or error message based on provided data.
	 *
	 * @param mixed $data The data from which to fetch the message.
	 */
	protected function render( $data ) {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Invalid JSON response', 'wp-module-staging' ),
		);
		switch ( gettype( $data ) ) {
			case 'string':
				$decoded = json_decode( $data );
				if ( $decoded && isset( $decoded['message'] ) ) {
					$response = $decoded;
				}
				break;
			case 'array':
				$response = $data;
				break;
			case 'object':
				if ( is_wp_error( $data ) ) {
					$response['message'] = $data->get_error_message();
				}
				break;
		}
		if ( 'success' === $response['status'] ) {
			$this->success( $response['message'] );
		} else {
			$this->error( $response['message'] );
		}
	}

	/**
	 * Get an admin user ID.
	 *
	 * @return int
	 */
	protected function get_admin_user_id() {

		$user_id = 0;

		$users = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
			)
		);

		if ( is_array( $users ) && is_a( $users[0], 'WP_User' ) ) {
			$user    = $users[0];
			$user_id = $user->ID;
		}

		return $user_id;
	}


	/**
	 * Helper to format data into tables.
	 *
	 * By default, the method creates simple $key => $value tables.
	 * Set $type to 'adv' and the table inherits keys from $data. DATA MUST BE UNIFORM & MATCH FIRST ROW.
	 *
	 * 1. Provide $data as an array or object
	 * 2. Provide $keys as two strings -y default 'DETAIL' and 'VALUE' are used.
	 * 3. Prints ASCII Table
	 *
	 * @param array  $data the data
	 * @param array  $keys key values
	 * @param string $type formating
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
	 * @param string $message the message
	 * @param string $emoji   an emoji to include
	 */
	protected function bold_heading( $message, $emoji = '' ) {
		$this->colorize_log( $message, '4', 'W', $emoji );
	}

	/**
	 * Formatted Success message.
	 *
	 * @param string $message the message
	 * @param bool   $silent  no feedback
	 */
	protected function success( $message, $silent = false ) {
		$pre_ = $silent ? '' : 'Success: ';
		$this->colorize_log( $pre_ . $message, '2', 'k', '✅' );
	}

	/**
	 * Formatted Info message.
	 *
	 * @param string $message the message
	 */
	protected function info( $message ) {
		$this->colorize_log( $message, '4', 'W', 'ℹ️' );
	}

	/**
	 * Formatted Warning message.
	 *
	 * @param string $message the message
	 */
	protected function warning( $message ) {
		$this->colorize_log( $message, '3', 'k', '⚠️' );
	}

	/**
	 * Formatted Error message. Halts by default.
	 *
	 * @param string $message the message
	 * @param bool   $silent  no feedback
	 * @param bool   $halt    should stop on error
	 * @param int    $code    error code to be supplied
	 *
	 * @throws \WP_CLI\ExitException Throws exit exception.
	 */
	protected function error( $message, $silent = false, $halt = true, $code = 400 ) {
		$pre_ = $silent ? '' : __( 'Error: ', 'wp-module-staging' );
		$this->colorize_log( $pre_ . $message, '1', 'W', '🛑️' );
		if ( $halt ) {
			\WP_CLI::halt( $code );
		}
	}

	/**
	 * Formatting helper for colorized messages.
	 *
	 * @param string $message      the message
	 * @param string $background   bg color
	 * @param string $text_color   text color
	 * @param string $emoji_prefix an emoji to prefix
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
	 * @param array|string $data Provide well-formed array or existing JSON string.
	 */
	protected function log_to_json( $data ) {
		if ( is_array( $data ) ) {
			\WP_CLI::log( wp_json_encode( $data ) );
		} elseif ( is_array( json_decode( $data, true ) ) ) {
			\WP_CLI::log( $data );
		} else {
			$this->error( __( 'Provided $data wasn\'t valid array or JSON string.', 'wp-module-staging' ) );
		}
	}

	/**
	 * Formatted Confirm Dialog. A 'n' response breaks the thread.
	 *
	 * @param string $question the question
	 * @param string $type     type of confirm dialog
	 *
	 * @throws \WP_CLI\ExitException Throws exit exception.
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
