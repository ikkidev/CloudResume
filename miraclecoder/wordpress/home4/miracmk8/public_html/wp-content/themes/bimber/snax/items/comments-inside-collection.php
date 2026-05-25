<?php
/**
 * The Template Part for displaying Comments.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package snax
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( post_password_required() ) {
	return;
}
?>
<?php if ( get_comments_number() || comments_open() ) : ?>



<?php
if ( bimber_is_auto_load() ) :?>
	<div class="comment-respond">
		<a href="<?php echo esc_url( get_permalink( $post ) . '#comments' ); ?>"  class="g1-button g1-button-m g1-button-solid g1-auto-load-button">
			<?php esc_html_e( 'View comments', 'bimber' ); ?>
		</a>
	</div>
	<?php
	add_filter( 'snax_display_see_more_for_comment', '__return_false' );
	return;
endif;
if ( comments_open() ) {
	snax_item_comment_form( $post->ID );
} ?>

	<section class="comments-area" itemscope itemtype="http://schema.org/UserComments">

				<?php
				$per_page = apply_filters( 'snax_item_on_list_comments_per_page',3 );
				$args = array(
						'post_id' 	=> $post->ID,
						'status' => 'approve',
					);
				$comments = get_comments( $args );
				$top_level_comments_count = 0;
				foreach ( $comments as $comment ) {
					if ( ! $comment->comment_parent ) {
						$top_level_comments_count += 1;
					}
				}

				if ( $top_level_comments_count > $per_page ) {
					add_filter( 'snax_display_see_more_for_comment', '__return_true' );
				} else {
					add_filter( 'snax_display_see_more_for_comment', '__return_false' );
				}
				?>

			<ol class="comment-list" data-snax-top-level-comments="<?php echo esc_attr( $top_level_comments_count ); ?>">
				<?php
				wp_list_comments( array(
					'type'     			=> 'comment',
					'per_page' 			=> $per_page,
					'callback' => 'bimber_wp_list_comments_callback',
				), $comments );
				?>
			</ol>

	</section><!-- #comments -->
<?php endif;
