<p class="snax-quiz-actions">
	<?php if ( snax_show_play_again_button() ) : ?>
		<form action="<?php echo esc_url( get_permalink() ); ?>" method="get">
			<button class="snax-quiz-button snax-quiz-button-restart-quiz g1-arrow g1-arrow-right g1-arrow-xl g1-arrow-solid" name="play" value="again"><?php esc_html_e( 'Play again', 'snax' ); ?></button>
		</form>
	<?php endif; ?>
</p>

