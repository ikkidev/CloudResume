<?php
/**
 * Snax Latest Votes widget
 *
 * @package snax 1.11
 * @subpackage Theme
 */

?>
<div id="<?php echo esc_attr( $snax_latest_votes_id ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_latest_votes_classes ) ); ?>">
	<?php if ( ! empty( $snax_latest_votes ) ) : ?>

		<ul class="snax-links">
			<?php foreach ( $snax_latest_votes as $snax_vote ) : ?>
				<li>
					<div class="snax-voting">
						<?php if ( (int) $snax_vote->vote === snax_get_upvote_value() ) : ?>
							<span class="snax-voting-upvote snax-vote snax-vote-up snax-user-voted">
								<span class="snax-vote-icon snax-vote-icon-<?php echo sanitize_html_class( snax_get_post_voting_icon() ); ?>"></span>
							</span>
						<?php endif; ?>
						<?php if ( (int) $snax_vote->vote === snax_get_downvote_value() ) : ?>
							<span class="snax-voting-downvote snax-vote snax-vote-down snax-user-voted">
								<span class="snax-vote-icon snax-vote-icon-<?php echo sanitize_html_class( snax_get_post_voting_icon() ); ?>"></span>
							</span>
						<?php endif; ?>
					</div>

					<div class="snax-link-header">

						<?php if ( 'global' === $snax_latest_votes_type ) : ?>
							<span class="g1-meta snax-meta">
								<?php
								if ( $snax_vote->author_id > 0 ) {
									$author_link = '<a href="' . esc_url( get_author_posts_url( $snax_vote->author_id ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $snax_vote->author_id ) ) . '</a>';
								} else {
									$author_link = esc_html__( 'Anonymous', 'snax' );
								}

								echo sprintf( esc_html__( '%1$s on', 'snax' ), $author_link );
								?>
							</span>
						<?php endif; ?>

						<p class="snax-link-title entry-title g1-epsilon g1-epsilon-1st"><a href="<?php echo esc_url( get_the_permalink( $snax_vote->post_id ) ); ?>"><?php echo get_the_title( $snax_vote->post_id ); ?></a></p>
					</div>
				</li>

			<?php endforeach; ?>
		</ul>

		<?php if ( $snax_latest_votes_view_all_url ) : ?>
			<p class="snax-more-results">
				<a class="g1-link g1-link-s g1-link-right" href="<?php echo esc_url( $snax_latest_votes_view_all_url ); ?>"><?php esc_html_e( 'View all votes', 'snax' ); ?></a>
			</p>
		<?php endif; ?>


	<?php else : ?>

		<?php snax_get_template_part( 'empty', 'latest-votes' ); ?>

	<?php endif; ?>
</div>
