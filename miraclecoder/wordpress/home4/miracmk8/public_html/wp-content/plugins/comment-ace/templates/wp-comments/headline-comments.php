<?php
/**
 * WordPress comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<h2 class="cace-comments-headline"><?php echo esc_html( sprintf( _n( 'One Comment', '%1$s Comments', $args['comment_count'], 'cace' ), number_format_i18n( $args['comment_count'] ) ) ); ?></h2>