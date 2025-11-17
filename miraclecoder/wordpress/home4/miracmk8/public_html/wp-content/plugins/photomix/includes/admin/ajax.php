<?php
/**
 * Ajax Functions
 *
 * @package photomix
 * @subpackage Ajax
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Prints ajax response, json encoded
 *
 * @param string $status    Status of the response (success|error).
 * @param string $message   Text message describing response status code.
 * @param array  $args      Response extra arguments.
 *
 * @return void
 */
function photomix_ajax_response( $status, $message, $args ) {
	$res = array(
		'status'  => $status,
		'message' => $message,
		'args'    => $args,
	);

	echo wp_json_encode( $res );
}

/**
 * Prints ajax success response, json encoded
 *
 * @param string $message       Text message describing response status code.
 * @param array  $args          Response extra arguments.
 *
 * @return void
 */
function photomix_ajax_response_success( $message, $args = array() ) {
	photomix_ajax_response( 'success', $message, $args );
}

/**
 * Prints ajax error response, json encoded
 *
 * @param string $message       Text message describing response status code.
 * @param array  $args          Response extra arguments.
 *
 * @return void
 */
function photomix_ajax_response_error( $message, $args = array() ) {
	photomix_ajax_response( 'error', $message, $args );
}