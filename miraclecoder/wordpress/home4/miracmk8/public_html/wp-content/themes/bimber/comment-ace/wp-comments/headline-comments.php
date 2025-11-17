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
<?php if ( apply_filters( 'bimber_cace_comments_headline_is_small', false ) ) : ?>
	<h4 class="cace-comments-headline g1-epsilon g1-epsilon-1st"><?php echo esc_html( sprintf( _n( 'One Comment', '%1$s Comments', $args['comment_count'], 'cace' ), number_format_i18n( $args['comment_count'] ) ) ); ?></h4>
<?php else : ?>
	<h2 class="cace-comments-headline"><?php echo esc_html( sprintf( _n( 'One Comment', '%1$s Comments', $args['comment_count'], 'cace' ), number_format_i18n( $args['comment_count'] ) ) ); ?></h2>
<?php endif; ?>