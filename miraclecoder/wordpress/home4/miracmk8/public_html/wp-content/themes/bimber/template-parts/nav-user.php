<?php
/**
 * The template part for displaying user navigation
 *
 * @package Bimber_Theme 5.4
 */

?>
<?php get_template_part( 'template-parts/nav-snax' ); ?>


<!-- BEGIN .g1-user-nav -->
<?php if ( has_nav_menu( 'bimber_user_nav' ) ) : ?>
	<nav class="g1-drop g1-drop-before g1-drop-the-user">

		<?php
		$bimber_current_user = wp_get_current_user();
		?>

		<?php if ( is_user_logged_in() ) : ?>
			<a class="g1-drop-toggle" href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>">
				<i class="g1-drop-toggle-icon">
					<?php
					$bimber_user_email = apply_filters( 'bimber_user_nav_email', $bimber_current_user->user_email );

					if ( 'bunchy' === bimber_get_current_stack() ) {
						echo get_avatar( $bimber_user_email, 40 );
					} else {
						echo get_avatar( $bimber_user_email, 30 );
					} ?>
				</i>
				<?php echo esc_html( $bimber_current_user->display_name ); ?>

				<?php if ( bimber_can_use_plugin( 'buddypress/bp-loader.php' ) && bp_is_active( 'notifications' ) ) : ?>
					<?php
					$bimber_count = intval( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) );
					?>
					<?php if ( $bimber_count ) : ?>
						<span class="g1-drop-toggle-badge"><?php echo intval( $bimber_count ); ?></span>
					<?php endif; ?>
				<?php endif; ?>

				<span class="g1-drop-toggle-arrow"></span>
			</a>
		<?php else : ?>
			<a class="g1-drop-toggle snax-login-required" href="#">
				<i class="g1-drop-toggle-icon"></i>
				<span class="g1-drop-toggle-arrow"></span>
			</a>
		<?php endif; ?>

		<?php wp_nav_menu( array(
			'theme_location'    => 'bimber_user_nav',
			'container'         => 'div',
			'container_class'   => 'g1-drop-content',
			'menu_class'        => 'sub-menu',
			'menu_id'           => '',
			'depth'             => 0,
		) );
		?>
	</nav>
<?php endif; ?>
<!-- END .g1-user-nav -->

