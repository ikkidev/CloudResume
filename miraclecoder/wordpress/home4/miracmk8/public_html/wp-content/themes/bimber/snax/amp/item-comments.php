<?php
/**
 * Item Comments Part
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$post = get_post( $post );

if ( ! snax_show_item_comments_box( $post ) ) {
	return;
}
$final_class = array(
	'snax-item-comments',
);
$class = array(
	'snax-item-comments-more-link',
);

?>
<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $final_class ) ); ?>">
	<?php if ( get_comments_number() || comments_open() ) : ?>

		<?php if ( comments_open() ) { ?>
			<p>
				<a class="g1-button g1-button-l g1-button-wide g1-button-solid" href="<?php echo esc_attr( get_permalink( $post->ID ) . '#respond' ); ?>">
					<?php esc_html_e( 'Leave a Reply', 'bimber' ); ?>
				</a>
			</p>
		<?php } //@TODO
		endif;?>

		<section class="comments-area">

					<?php
					$args = array(
							'post_id' 	=> $post->ID,
							'status' => 'approve',
						);
					$comments = get_comments( $args );
					?>

				<ol class="comment-list">
					<?php
					wp_list_comments( array(
						'type'     			=> 'comment',
						'per_page' 			=> 1,
					), $comments );
					?>
				</ol>

		</section><!-- #comments -->

</div>
