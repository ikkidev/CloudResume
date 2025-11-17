<div class="list-wrap bp-card bp-card-l">
	<?php if ( bp_group_use_cover_image_header() ) : ?>
		<?php
		$bimber_image_url = bp_attachments_get_attachment('url', array(
			'object_dir'    => 'groups',
			'item_id'       => bp_get_group_id(),
		));

		// Default cover image fallback.
		if ( empty( $bimber_image_url ) ) {
			$bimber_settings  = bp_attachments_get_cover_image_settings();
			$bimber_image_url = $bimber_settings['default_cover'];
		}
		?>
		<a
			class="item-cover"
			href="<?php echo esc_url( bp_get_group_permalink() ); ?>"
			title="<?php echo esc_attr( sprintf( __( 'Profile page of %s', 'bimber'), apply_filters( 'bp_member_name', bp_get_group_name() ) ) ); ?>"
		>
			<?php echo apply_filters( 'bimber_buddypress_members_cover_image', '<img src="' . esc_url( $bimber_image_url ) . '" width="160" height="90" alt="" />' ); ?>
		</a>

	<?php endif; ?>

	<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
		<div class="item-avatar">
			<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a>
		</div>
	<?php endif; ?>

	<div class="item-block">
		<h2 class="list-title groups-title g1-gamma g1-gamma-1st entry-title"><?php bp_group_link(); ?></h2>

		<?php if ( bp_nouveau_group_has_meta() ) : ?>

			<p class="item-meta group-details g1-meta">
				<span class="bp-group-status bp-group-status-<?php echo sanitize_html_class( bp_get_group_status() ); ?>">
					<?php echo esc_html( bp_get_group_type() ); ?>
				</span>

				<?php bp_nouveau_the_group_meta( array( 'keys' => array( 'count' ) ) ); ?>
			</p>

		<?php endif; ?>

		<p class="last-activity item-meta">
			<?php
				printf(
					/* translators: %s: last activity timestamp (e.g. "Active 1 hour ago") */
					esc_html__( 'Active %s', 'buddypress' ),
					sprintf(
						'<span data-livestamp="%1$s">%2$s</span>',
						bp_core_get_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ),
						esc_html( bp_get_group_last_active() )
					)
				);
			?>
		</p>
	</div>

	<div class="group-desc"><p><?php bp_nouveau_group_description_excerpt(); ?></p></div>

	<?php bp_nouveau_groups_loop_item(); ?>

	<?php bp_nouveau_groups_loop_buttons( array( 'container' => 'ul' ) ); ?>
</div>
