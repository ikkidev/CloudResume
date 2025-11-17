<?php
/**
 * The Template for displaying comments.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php if ( comments_open() || get_comments_number() ) : ?>

<?php get_template_part( 'template-parts/ads/ad-before-comments' ); ?>

<?php if ( bimber_is_auto_load() ) :?>
	<div class="comment-list">
		<a href="<?php echo esc_url( get_permalink( $post ) . '#comments' ); ?>" class="g1-button g1-button-l g1-button-wide g1-button-solid g1-auto-load-comments g1-auto-load-button">
			<?php esc_html_e( 'View comments', 'bimber' ); ?>
		</a>
	</div>
	<?php return;
endif; ?>

<?php
	$bimber_comment_types = bimber_get_comment_types();

	$bimber_class = array(
		'g1-comments',
		count( $bimber_comment_types ) ? 'g1-comments-with-tabs' : 'g1-comments-without-tabs',
	);
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>" id="comments">

	<?php do_action( 'bimber_before_comments' ); ?>

	<?php if ( 1 < count( $bimber_comment_types ) ) : ?>
		<?php bimber_render_section_title( __( 'Comments', 'bimber' ), false, array( 'comments-title' ) );?>

		<div class="g1-comments-tabs g1-tabs">
			<ul class="g1-tab-items">
				<?php foreach ( $bimber_comment_types as $bimber_type => $bimber_label ) : ?>

					<?php
					$bimber_classes = array(
						'g1-tab-item',
						'g1-tab-item-' . $bimber_type,
					);

					if ( $bimber_type === apply_filters( 'bimber_comments_default_type', 'wp' ) ) {
						$bimber_classes[] = 'g1-tab-item-current';
					}
					?>

					<li class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_classes ) ); ?>" data-bimber-type="<?php echo esc_attr( $bimber_type ); ?>">
						<a class="g1-tab"><?php echo wp_kses_post( $bimber_label ); ?></a>
					</li>

				<?php endforeach; ?>

			</ul>
		</div>
	<?php endif; ?>

	<?php comments_template(); ?>

	<?php do_action( 'bimber_after_comments' ); ?>

</div>
<?php endif; ?>
