<?php
/**
 * Autoptimize plugin integration
 *
 * @package Commentace
 */

namespace Commentace\Autoptimize;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

add_filter( 'autoptimize_filter_noptimize', 'Commentace\Autoptimize\noptimize_load_more', 11 );

function noptimize_load_more( $ao_noptimize ) {
    $cace_offset = filter_input( INPUT_GET, 'cace-offset', FILTER_SANITIZE_NUMBER_INT );

    if ( ! is_null( $cace_offset ) ) {
        $ao_noptimize = true;
    }

    return $ao_noptimize;
}
