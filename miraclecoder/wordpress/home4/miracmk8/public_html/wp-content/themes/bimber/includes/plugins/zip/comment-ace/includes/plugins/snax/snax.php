<?php
/**
 * Snax plugin integration
 *
 * @package Commentace
 */

namespace Commentace\Snax;

// Prevent direct script access.
use function Commentace\is_post_type_enabled;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'snax_display_see_more_for_comment', 'Commentace\Snax\disable_see_more_for_comment', 11 );
add_filter( 'snax_item_comments_js_enabled', 'Commentace\Snax\disable_item_comments_js', 11 );
add_filter( 'cace_post_type_enabled', 'Commentace\Snax\check_if_snax_item_is_supported', 10, 3 );

/**
 * If Snax Item comments are taken over by CommentAce, we want to disable the View More Comments link
 *
 * @param bool $display   True to display.
 * @return bool
 */
function disable_see_more_for_comment( $display ) {
    if ( is_post_type_enabled( 'snax_item' ) ) {
        $display = false;
    }

    return $display;
}

/**
 * Disable item comments JS scripts if CommentAce overtakes them
 *
 * @param bool $enabled         Enabled flag.
 *
 * @return bool
 */
function disable_item_comments_js( $enabled ) {
    if ( is_post_type_enabled( 'snax_item' ) ) {
        $enabled = false;
    }

    return $enabled;
}

/**
 * Check whether the post type can be used with CommentAce
 *
 * @param bool         $enabled                 Is post type enabled?
 * @param array|string $enabled_post_types      All enabled post types.
 * @param string       $post_type               Current post type
 *
 * @return bool
 */
function check_if_snax_item_is_supported( $enabled, $enabled_post_types, $post_type ) {
    if ( $enabled && 'snax_item' === $post_type ) {
        $cace_comments = \Commentace\plugin()->comments();

        // WP type has to be active.
        if ( $cace_comments && ! $cace_comments->get_type( CACE_COMMENT_TYPE_WORDPRESS ) ) {
            $enabled = false;
        }
    }

    return $enabled;
}
