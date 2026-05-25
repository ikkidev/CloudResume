<?php
/**
 * Template for displaying closed comments info.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$commentace_classes = array(
    'cace-notice',
    'cace-comments-closed',
);

?>

<div class="<?php echo implode( ' ', array_filter( array_map( 'sanitize_html_class', $commentace_classes ) ) ); ?>">
    <?php
    esc_html_e( 'Comments are closed.', 'cace' );

    if ( plugin()->in_debug_mode() ) {
        echo esc_html_x( 'To enable, click "Edit Post" page on top WP admin bar, find "Discussion" metabox and check "Allow comments" in it.', 'Debug mode','cace' );
    }
    ?>
</div>


