<?php
/**
 * Snax binary Poll Teaser widget
 *
 * @package snax 1.11
 * @subpackage Theme
 */

$post_id = get_query_var( 'snax_widget_teaser_post_id' );
$id = get_query_var( 'snax_widget_teaser_id' );
$questions = snax_get_poll_questions( $post_id );
?>
<div class="snax-teaser-binary widget snax snax-teaser-<?php echo esc_attr( snax_get_poll_setting( 'answers_set' ) );?>">
	<?php if ( count( $questions ) ) : ?>
		<a href="<?php echo esc_url( get_the_permalink( $post_id ) );?>">
			<div class="snax-teaser-binary-images">
				<?php echo wp_get_attachment_image( $questions[0]['media']['id'], 'bimber-grid-standard' );?>
				<div class="snax-teaser-binary-slogan">
					<div class="g1-beta g1-beta-1st"><?php esc_html_e( 'Hot', 'snax' ); ?></div>
					<div class="g1-delta g1-delta-1st"><?php esc_html_e( 'or', 'snax' ); ?></div>
					<div class="g1-beta g1-beta-1st"><?php esc_html_e( 'Not?', 'snax' ); ?></div>
				</div>
			</div>
		</a>
	<?php endif; ?>

	<h3 class="snax-teaser-binary-post-title g1-delta g1-delta-1st entry-title">
		<a href="<?php echo esc_url( get_the_permalink( $post_id ) );?>">
			<?php echo esc_html( get_the_title( $post_id ) );?>
		</a>
	</h3>

	<a class="snax-teaser-binary-button g1-button g1-button-s g1-button-simple" href="<?php echo esc_url( get_the_permalink( $post_id ) );?>">
		<?php esc_html_e( 'Take the Poll', 'bimber' ); ?>
	</a>
</div>
