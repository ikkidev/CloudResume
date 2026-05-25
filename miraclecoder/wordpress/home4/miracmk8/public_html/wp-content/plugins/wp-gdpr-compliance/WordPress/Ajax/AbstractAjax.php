<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\Helper;
use WPGDPRC\WordPress\Plugin;

/**
 * Class AbstractAjax
 * @package WPGDPRC\WordPress\Ajax
 */
abstract class AbstractAjax {

	/**
	 * AbstractAjax constructor
	 */
	public static function init() {
		add_action( 'wp_ajax_' . static::getAction(), [ static::class, 'execute' ] );

		if ( static::isPublic() ) {
			add_action( 'wp_ajax_nopriv_' . static::getAction(), [ static::class, 'execute' ] );
		}
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * Determines if AJAX call is sending a data attribute
	 * @return bool
	 */
	public static function hasData() {
		return true;
	}

	/**
	 * Lists the required data keys
	 * @return array
	 */
	public static function requiredData() {
		return [];
	}

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
	}

	/**
	 * Executes the AJAX request
	 */
	public static function execute() {
        check_ajax_referer( Plugin::AJAX_NONCE, Plugin::AJAX_ARG );

        if ( !static::isPublic() && !current_user_can('manage_options') ) {
            static::returnError(__('You do not have sufficient permissions to access this page.', 'vo-gdpr-compliance'));
        }

		if ( ! static::hasData() ) {
			static::buildResponse([]);
			return;
		}

		$data = static::validateData();
		$data = static::sanitizeData($data);
        static::buildResponse($data);
	}

	/**
	 * Sanitizes
	 * @param array $data
	 * @return array
	 */
	public static function sanitizeData($data) {
        if (is_array($data)) {
            return Helper::sanitizeStringArray( $data );
        }

        return sanitize_text_field($data);
	}

	/**
	 * Validates the data attribute
	 * @return array|void
	 */
	public static function validateData() {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Is done later in the function.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : false;
		if ( is_string( $data ) ) {
            // Is json.
			$data = (array) json_decode( $data );
            $data = Helper::sanitizeStringArray( $data );
		} else {
            $data = santize_text_field($data);
        }

		$error = __( 'Missing data.', 'wp-gdpr-compliance' );
		if ( empty( $data ) ) {
			static::returnError( $error );
		}

		$required = static::requiredData();
		if ( empty( $required ) ) {
			return $data;
		}

		foreach ( $required as $key ) {
			if ( ! isset( $data[ $key ] ) ) {
				static::returnError( $error );
			}
		}

		return $data;
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is already done at this point)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
	}

	/**
	 * Returns a JSON response with an error message
	 * @param string $message
	 */
	public static function returnError( $message = '' ) {
		$response = [
			'success' => false,
			'error'   => $message,
		];
		static::returnResponse( $response );
	}

	/**
	 * Returns a JSON response with a success message
	 * @param string $message
	 */
	public static function returnSuccess( $message = '' ) {
		$response = [
			'success' => true,
			'message' => $message,
		];
		static::returnResponse( $response );
	}

	/**
	 * Returns a JSON response and exits php execution
	 * @param array $response
	 */
	public static function returnResponse( $response = [] ) {

		header( 'Content-type: application/json' );
		echo wp_json_encode( $response );
		wp_die();
	}

}
