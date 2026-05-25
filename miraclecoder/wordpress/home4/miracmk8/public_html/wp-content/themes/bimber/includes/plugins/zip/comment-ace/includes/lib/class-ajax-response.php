<?php
/**
 * Ajax response class
 *
 * @package CommentAce
 */

namespace Commentace\Ajax;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Response {

    /**
     * Prints ajax success response, json encoded
     *
     * @param string $message       Text message describing response status code.
     * @param array  $args          Response extra arguments.
     *
     * @return void
     */
    public static function success( $message, $args = array() ) {
        self::raw( 'success', $message, $args );
    }

    /**
     * Prints ajax error response, json encoded
     *
     * @param string $message       Text message describing response status code.
     * @param array  $args          Response extra arguments.
     *
     * @return void
     */
    public static function error( $message, $args = array() ) {
        self::raw( 'error', $message, $args );
    }

    protected static function raw( $status, $message, $args ) {
        $res = array(
            'status'  => $status,
            'message' => $message,
            'args'    => $args,
        );

        echo wp_json_encode( $res );
    }
}
