<?php
/**
 * BuddyPress - Members Loop
 *
 * @since 3.0.0
 * @version 6.0.0
 */
?>

<div class="list-wrap csstodo-bp-item">
	<div class="item-avatar">
		<a href="<?php bp_member_permalink(); ?>">
			<?php
			bp_member_avatar( array(
				'type'      => 'full',
				'width'     => 80,
				'height'    => 80,
			) );
			do_action( 'bimber_buddypress_memebers_after_avatar', bp_get_member_user_id() );
			?>
		</a>
	</div>

	<div class="item">

		<div class="item-header">
			<h2 class="list-title member-name g1-gamma g1-gamma-1st entry-title">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
			</h2>

			<?php if ( bp_nouveau_member_has_meta() ) : ?>
				<p class="item-meta last-activity g1-meta">
					<?php bp_nouveau_member_meta(); ?>
				</p><!-- .item-meta -->
			<?php endif; ?>
		</div>

		<?php if ( bp_nouveau_member_has_extra_content() ) : ?>
			<div class="item-extra-content">
				<?php bp_nouveau_member_extra_content() ; ?>
			</div><!-- .item-extra-content -->
		<?php endif ; ?>

		<div class="item-action">
			<?php
			bp_nouveau_members_loop_buttons(
				array(
					'container'      => 'ul',
					'button_element' => 'button',
				)
			);
			?>
		</div>

		<?php if ( bp_get_member_latest_update() && ! bp_nouveau_loop_is_grid() ) : ?>
			<div class="user-update">
				<p class="update"> <?php bp_member_latest_update(); ?></p>
			</div>
		<?php endif; ?>

	</div><!-- // .item -->
</div>
