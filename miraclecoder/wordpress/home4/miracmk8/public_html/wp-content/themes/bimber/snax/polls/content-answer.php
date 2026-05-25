<?php
$snax_answer_class = array(
	'snax-poll-answer',
	'snax-poll-answer-' . get_the_ID(),
);
// gets the 'grandparent' of the answer.
$poll_type = snax_get_poll_type( wp_get_post_parent_id( wp_get_post_parent_id( get_the_ID() ) ) );
if ( 'classic' === $poll_type ) {
	$img_size = 'poll-answer-grid-1of2';
} else {
	$img_size = 'quizzard-answer-grid-1of2';
}
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_answer_class ) ); ?>" data-quizzard-answer-id="<?php echo absint( get_the_ID() ); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="snax-poll-answer-media <?php if ( 'versus' === $poll_type ) {echo sanitize_html_class( 'snax-poll-answer-media-versus' ); }?>">
			<?php the_post_thumbnail( $img_size ); ?>
			<?php if ( 'versus' === $poll_type ) :?>
				<div class="snax-poll-anticipation"></div>
			<?php endif;?>
		</figure>
	<?php endif; ?>
	<div class="snax-poll-answer-label">
		<input type="radio" name="snax_question_1" />

		<div class="g1-epsilon g1-epsilon-1st snax-poll-answer-label-text">
			<?php the_title(); ?>
		</div>

		<?php $bimber_caption = wp_get_attachment_caption( get_post_thumbnail_id() ); ?>
		<?php if ( $bimber_caption ) : ?>
			<div class="snax-poll-answer-caption g1-meta">
				<?php echo esc_html( $bimber_caption ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
