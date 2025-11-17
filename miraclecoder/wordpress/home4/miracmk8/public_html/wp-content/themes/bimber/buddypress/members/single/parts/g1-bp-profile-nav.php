<?php
/**
 * BuddyPress - Single Profile Nav
 */
?>


	<?php
	$bimber_prev_id = bimber_bp_get_prev_user_id();
	$bimber_next_id = bimber_bp_get_next_user_id();
	?>
	<?php if ( $bimber_prev_id || $bimber_next_id ) : ?>
		<nav class="g1-bp-profile-nav">
			<?php if ( $bimber_prev_id ) : ?>
				<a class="g1-bp-profile-arrow g1-bp-profile-arrow-prev"
				   title="<?php esc_attr_e( 'Previous Member', 'bimber' ); ?>"
				   href="<?php echo esc_url( bp_core_get_user_domain( $bimber_prev_id ) ); ?>"
				   data-g1-member-id="<?php echo (int) $bimber_prev_id; ?>"
				>
					<?php
					echo bp_core_fetch_avatar( array(
						'item_id'   => $bimber_prev_id,
						'object'    => 'user',
						'width'     => 30,
						'height'    => 30,
						'class'     => 'avatar',
					) );
					?>
					<span class="g1-bp-profile-arrow-title"><?php echo bp_core_get_user_displayname( $bimber_prev_id ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $bimber_next_id ) : ?>
				<a class="g1-bp-profile-arrow g1-bp-profile-arrow-next"
				   title="<?php esc_attr_e( 'Next Member', 'bimber' ); ?>"
				   href="<?php echo esc_url( bp_core_get_user_domain( $bimber_next_id ) ); ?>"
				   data-g1-member-id="<?php echo (int) $bimber_next_id; ?>"
				>
					<?php
					echo bp_core_fetch_avatar( array(
						'item_id'   => $bimber_next_id,
						'object'    => 'user',
						'width'     => 30,
						'height'    => 30,
						'class'     => 'avatar',
					) );
					?>
					<span class="g1-bp-profile-arrow-title"><?php echo bp_core_get_user_displayname( $bimber_next_id ); ?></span>
				</a>
			<?php endif; ?>

		</nav><!-- .g1-bp-profile-nav -->
	<?php endif; ?>