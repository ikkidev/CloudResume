<?php
/**
 * Latest Reactions widget
 *
 * @package whats-your-reaction
 * @subpackage Widgets
 */

?>
<div id="<?php echo esc_attr( $wyr_latest_reactions_id ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $wyr_latest_reactions_classes ) ); ?>">
	<?php if ( ! empty( $wyr_latest_reactions ) ) : ?>
		<ul class="wyr-links">
			<?php foreach ( $wyr_latest_reactions as $wyr_reaction ) : 
				$post_id = $wyr_reaction->post_id;
				?>
				<li class="wyr-link">
					<?php $wyr_reaction_term = wyr_get_reaction( $wyr_reaction->vote );
					?>

					<?php wyr_render_reaction_icon( $wyr_reaction_term->term_id ); ?>

					<div class="wyr-link-header">
						<?php if ( 'global' === $wyr_latest_reactions_type ) : ?>
							<span class="g1-meta wyr-meta">
								<?php
									if ( $wyr_reaction->author_id > 0 ) {
										$author_link = '<a class="wyr-reaction-author" href="' . esc_url( get_author_posts_url( $wyr_reaction->author_id ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $wyr_reaction->author_id ) ) . '</a>';
									} else {
										$author_link = esc_html__( 'Anonymous', 'wyr' );
									}

									echo sprintf( esc_html_x( '%1$s to', 'someone reacted to post', 'wyr' ), $author_link );
								?>
							</span>
						<?php endif; ?>

						<p class="wyr-link-title entry-title g1-epsilon g1-epsilon-1st"><a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>"><?php echo get_the_title( $post_id ); ?></a></p>

						<p class="wyr-link-after-title wyr-meta g1-meta">
							<time datetime="<?php echo esc_attr( mysql2date( 'Y-m-d', $wyr_reaction->date_gmt ) . 'T' . mysql2date( 'H:i:s', $wyr_reaction->date_gmt ) ); ?>">
								<?php echo mysql2date( get_option( 'date_format' ), $wyr_reaction->date_gmt ); ?>, <?php echo mysql2date( get_option( 'time_format' ), $wyr_reaction->date_gmt ); ?>
							</time>
						</p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( $wyr_latest_reactions_view_all_url ) : ?>
			<p class="wyr-more-results">
				<a class="g1-link g1-link-s g1-link-right" href="<?php echo esc_url( $wyr_latest_reactions_view_all_url ); ?>"><?php esc_html_e( 'View all reactions', 'wyr' ); ?></a>
			</p>
		<?php endif; ?>

	<?php else : ?>

		<?php wyr_get_template_part( 'empty', 'latest-reactions' ); ?>

	<?php endif; ?>
</div>
