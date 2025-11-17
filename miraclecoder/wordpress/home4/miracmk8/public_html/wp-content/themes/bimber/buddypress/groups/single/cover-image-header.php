<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @since 3.0.0
 * @version 3.2.0
 */
?>

<div id="cover-image-container">
	<div id="header-cover-image">
		<?php
		if ( bimber_bp_show_group_cover_image_change_link() ) {
			bimber_bp_render_group_cover_image_change_link();
		}
		?>
	</div>

	<div id="item-header-cover-image" class="g1-row g1-dark">
		<div class="g1-row-background">
		</div>

		<div class="g1-row-inner">
			<div class="g1-column">
				<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
					<div id="item-header-avatar">
						<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>" title="<?php echo esc_attr( bp_get_group_name() ); ?>">
							<?php
							bp_group_avatar( array(
								'width'     => 160,
								'height'    => 160,
								'type'      => 'full',
							) );
							?>
						</a>
						<?php if ( bimber_bp_show_group_photo_change_link()  ) : ?>
							<?php bimber_bp_render_group_photo_change_link(); ?>
						<?php endif; ?>
					</div><!-- #item-header-avatar -->
				<?php endif; ?>

		<?php	if ( ! bp_nouveau_groups_front_page_description() ) : ?>
				<div id="item-header-content">
					<div id="item-header-content-main">
						<h1 class="g1-alpha g1-alpha-1st entry-title"><?php bp_group_name(); ?>
							<sup class="group-status"><?php echo esc_html( bp_nouveau_the_group_meta( array('keys' => 'status' ) ) ); ?></sup>
						</h1>

						<?php if ( ! bp_nouveau_groups_front_page_description() && bp_nouveau_group_has_meta( 'description' ) ) : ?>
							<div class="desc-wrap">
								<div class="group-description">
									<?php bp_group_description(); ?>
								</div><!-- //.group_description -->
							</div>
						<?php endif; ?>
					</div>

					<div id="item-header-content-side">
						<?php echo bp_nouveau_the_group_meta( array( 'keys' => 'group_type_list' ) ); ?>
						<?php bp_nouveau_group_hook( 'before', 'header_meta' ); ?>
					</div>

				</div><!-- #item-header-content -->
		<?php endif; ?>

				<?php bp_get_template_part( 'groups/single/parts/header-item-actions' ); ?>

			</div>
		</div>
	</div><!-- #item-header-cover-image -->

</div><!-- #cover-image-container -->

<div id="csstodo-bp-meta" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
	<div class="g1-row-background">
	</div>
	<div class="g1-row-inner">
		<div class="g1-column">
			<div class="item-meta">
				<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
					<?php
					/* translators: %s: last activity timestamp (e.g. "active 1 hour ago") */
					printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() );
					?>
				</span>

				<?php if ( bp_nouveau_group_has_meta_extra() ) : ?>
					<?php echo bp_nouveau_the_group_meta( array( 'keys' => 'extra' ) ); ?>
				<?php endif; ?>

			</div><!-- .item-meta -->
			<?php bp_nouveau_group_header_buttons(); ?>
		</div>
	</div>
</div>
