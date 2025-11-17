<?php
/**
 * BuddyPress - Members Loop
 *
 * @since 3.0.0
 * @version 6.0.0
 */
?>

<div class="list-wrap bp-card bp-card-s">
	<?php if ( bp_displayed_user_use_cover_image_header() ) : ?>
		<?php
		$bimber_image_url = bp_attachments_get_attachment('url', array(
			'object_dir'    => 'members',
			'item_id'       => bp_get_member_user_id(),
		));

		// Default cover image fallback.
		if ( empty( $bimber_image_url ) ) {
			$bimber_settings  = bp_attachments_get_cover_image_settings();
			$bimber_image_url = $bimber_settings['default_cover'];
		}
		?>
		<a
			class="item-cover"
			href="<?php echo esc_url( bp_get_member_permalink() ); ?>"
			title="<?php echo esc_attr( sprintf( __( 'Profile page of %s', 'bimber'), apply_filters( 'bp_member_name', bp_get_member_name() ) ) ); ?>"
		>
			<?php echo apply_filters( 'bimber_buddypress_members_cover_image', '<img src="' . esc_url( $bimber_image_url ) . '" width="160" height="90" alt="" />' ); ?>
		</a>
	<?php endif; ?>

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
		<div class="item-block">
			<h2 class="list-title member-name g1-epsilon g1-epsilon-1st entry-title">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
			</h2>

			<?php
				$bimber_desc = '';
				if ( function_exists( 'xprofile_get_field_data' ) ) {
					$bimber_desc = xprofile_get_field_data( bimber_bp_get_short_description_field_id(), bp_get_member_user_id() );
					$bimber_desc = strip_tags( $bimber_desc );
				}
				?>
				<?php if ( strlen( $bimber_desc ) ) : ?>
					<p class="item-desc"><?php echo esc_html( $bimber_desc ); ?></p>
			<?php endif; ?>

			<?php if ( bp_nouveau_member_has_meta() ) : ?>
				<p class="item-meta last-activity g1-meta">
					<?php bp_nouveau_member_meta(); ?>
				</p><!-- .item-meta -->
			<?php endif; ?>

			<?php if ( bp_nouveau_member_has_extra_content() ) : ?>
				<div class="item-extra-content">
					<?php bp_nouveau_member_extra_content() ; ?>
				</div><!-- .item-extra-content -->
			<?php endif ; ?>
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
