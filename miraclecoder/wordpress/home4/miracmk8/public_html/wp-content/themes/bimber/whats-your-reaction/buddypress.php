<?php
/**
 * Latest Reactions BuddyPress
 *
 * @package whats-your-reaction
 * @subpackage Widgets
 */
if ( isset( $_GET['wyr_page'] ) ) {
	$page = $_GET['wyr_page'];
} else {
	$page = 1;
}
$per_page = apply_filters( 'wyr_bp_page_reactions_per_page', 10 );
$offset = ( $page - 1 ) * $per_page;
$wyr_latest_reactions = wyr_get_user_latest_votes( bp_displayed_user_id(), $per_page, $offset );
?>
<div id="wyr-memeber-reactions" class="wyr-memeber-reactions">
	<?php if ( ! empty( $wyr_latest_reactions ) ) : ?>

		<ul class="wyr-links">
			<?php foreach ( $wyr_latest_reactions as $wyr_reaction ) : 
				$post_id = $wyr_reaction->post_id;
				?>
				<li class="wyr-link">
					<?php $wyr_reaction_term = wyr_get_reaction( $wyr_reaction->vote ); ?>
					<?php wyr_render_reaction_icon( $wyr_reaction_term->term_id ); ?>

					<div class="wyr-link-header">
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

		<?php wyr_get_template_part( 'buddypress/pagination' ); ?>

	<?php else : ?>
		<?php wyr_get_template_part( 'empty-buddypress' ); ?>
	<?php endif; ?>
</div>
