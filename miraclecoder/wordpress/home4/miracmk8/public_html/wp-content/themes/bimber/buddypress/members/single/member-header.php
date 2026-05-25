<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>

<div id="cover-image-container">
	<div id="header-cover-image">
		<?php
		if ( bimber_bp_show_cover_image_change_link() ) {
			bimber_bp_render_cover_image_change_link();
		}
		?>
		<?php
		if ( apply_filters( 'bimber_bp_show_profile_nav', true ) ) {
			get_template_part( 'buddypress/members/single/parts/g1-bp-profile-nav' );
		}
		?>
	</div>

	<?php
	$bimber_class = array(
		'g1-row',
		'g1-row-layout-page',
		'g1-dark',
	);

	if ( bimber_can_use_plugin( 'mycred/mycred.php' ) && bimber_mycred_is_addon_enabled( 'ranks' ) ) {
		$bimber_class[] = 'csstodo-with-rank';
	}
	?>
	<div id="item-header-cover-image" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<div class="g1-column">
				<div id="item-header-avatar">
					<a href="<?php bp_displayed_user_link(); ?>">
						<?php
						bp_displayed_user_avatar( array(
						'width'     => 160,
						'height'    => 160,
						'type'      => 'full',
						) );
						do_action( 'bimber_buddypress_memebers_after_avatar', bp_displayed_user_id() );
						?>
					</a>
					<?php
						if ( bimber_bp_show_profile_photo_change_link()  ) {
							bimber_bp_render_profile_photo_change_link();
						}
					?>
				</div><!-- #item-header-avatar -->

				<div id="item-header-content">
					<div id="item-header-content-main">
						<h1 class="g1-alpha g1-alpha-1st entry-title"><?php bp_displayed_user_fullname(); ?>
							<sup><?php do_action( 'bimber_buddypress_memebers_after_user_name', bp_displayed_user_id() );?></sup>
						</h1>

						<?php if ( function_exists( 'xprofile_get_field_data' ) ) : ?>
							<?php
							$description = xprofile_get_field_data( bimber_bp_get_short_description_field_id(), bp_displayed_user_id() );
							?>
							<p class="item-header-user-desc">
								<?php echo esc_html( strip_tags( $description ) ); ?>
							</p>
						<?php endif; ?>
					</div>
					<div id="item-header-content-side">
						<?php
						add_filter( 'mycred_bp_profile_header', function( $output ) {
							$output = preg_replace(
								'/<div class="mycred-balance mycred-mycred_default">[\sa-z:]+([\d\.,]+)/i',
								'<div class="csstodo-ranking"><div class="g1-gamma g1-gamma-1st">\1</div> <div>' . __( 'Points', 'bimber' ) . '</div>',
								$output
							);

							return $output;
						} );
						?>
						<?php
						if ( bimber_can_use_plugin( 'mycred/mycred.php' ) ) {
							$bimber_module = mycred_get_module( 'badges' );
							if ( $bimber_module ) {
								if ( $bimber_module->badges['buddypress'] == 'header' || $bimber_module->badges['buddypress'] == 'both' ) {
									$bimber_module->insert_into_buddypress();
								}
							}
						}
						?>

						<?php if ( bimber_can_use_plugin( 'mycred/mycred.php' ) ) : ?>
						<div class="csstodo-ranking">
							<div class="g1-gamma g1-gamma-1st"><?php echo do_shortcode( '[mycred_leaderboard_position user_id="' . bp_displayed_user_id() . '"]' ); ?></div>
							<div><?php esc_html_e( 'Ranking', 'bimber' ); ?></div>
						</div>
						<?php endif; ?>

						<?php
						if ( bimber_can_use_plugin( 'mycred/mycred.php' ) ) {
							$bimber_module = mycred_get_module( 'buddypress', MYCRED_DEFAULT_TYPE_KEY );
							if ( $bimber_module ) {
								if ( $bimber_module->buddypress['balance_location'] == 'top' || $bimber_module->buddypress['balance_location'] == 'both' ) {
									$bimber_module->show_balance();
								}
							}
						}
						?>
					</div>
				</div>


			</div>
		</div>

	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->
<?php
$bimber_class = array(
	'g1-row',
	'g1-row-layout-page',
);

if ( bimber_can_use_plugin( 'mycred/mycred.php' ) && bimber_mycred_is_addon_enabled( 'ranks' ) ) {
	$bimber_class[] = 'csstodo-with-rank';
}
?>
<div id="csstodo-bp-meta" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
	<div class="g1-row-background">
	</div>
	<div class="g1-row-inner">
		<div class="g1-column">
			<?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

			<?php if ( bp_nouveau_member_has_meta() ) : ?>
				<div class="item-meta">

					<?php bp_nouveau_member_meta(); ?>

				</div><!-- #item-meta -->
			<?php endif; ?>

			<div id="item-buttons" class="g1-dropable">
				<div class="g1-drop g1-drop-m g1-drop-icon g1-drop-before">
					<button class="g1-button-none g1-drop-toggle"> <span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text">More</span> <span class="g1-drop-toggle-arrow"></span> </button>
					<div class="g1-drop-content">
						<?php
						bp_nouveau_member_header_buttons(
							array(
								'container'         => 'ul',
								'parent_element'    => 'li',
								'parent_attr'       => array( 'class' => 'menu-item' ),
								'button_element'    => 'button',
								'container_classes' => array( 'member-header-actions', 'sub-menu' ),
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
