<?php
/**
 * Header Builder template
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<nav class="g1-drop g1-drop-with-anim g1-drop-before g1-drop-the-user  <?php bimber_hb_get_element_class_from_settings( 'user_menu' );?>">

<?php
$bimber_current_user = wp_get_current_user();
if ( is_user_logged_in() ) {
	$bimber_current_user_email = apply_filters( 'bimber_user_nav_email', $bimber_current_user->user_email );
	$bimber_current_user_url = get_author_posts_url( $bimber_current_user->ID );
}
?>

<?php if ( is_user_logged_in() ) : ?>
	<a class="g1-drop-toggle" href="<?php echo esc_url( $bimber_current_user_url ); ?>">
		<span class="g1-drop-toggle-icon">
			<?php
			if ( 'bunchy' === bimber_get_current_stack() ) {
				echo get_avatar( $bimber_current_user_email, 40 );
			} else {
				echo get_avatar( $bimber_current_user_email, 30 );
			} ?>
		</span>
		<span class="g1-drop-toggle-text"><?php echo esc_html( $bimber_current_user->display_name ); ?></span>

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
	<a class="g1-drop-toggle snax-login-required" href="<?php echo esc_url( wp_login_url() ); ?>">
		<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Login', 'bimber' ); ?></span>
		<span class="g1-drop-toggle-arrow"></span>
	</a>
<?php endif; ?>

	<?php if ( is_user_logged_in() ) : ?>
		<div class="g1-drop-content">
			<ul class="sub-menu csstodo-sub-menu">
				<li class="menu-item">
					<a href="<?php echo esc_url( $bimber_current_user_url ); ?>">
						<?php echo get_avatar( $bimber_current_user_email, 48 ); ?>
						<strong><?php echo esc_html( $bimber_current_user->display_name ); ?></strong>
					</a>
				</li>

				<?php if ( bimber_can_use_plugin( 'mycred/mycred.php' ) && bimber_can_use_plugin( 'buddypress/bp-loader.php' ) ) : ?>
					<?php $mycred = mycred(); ?>
					<?php if ( ! empty( $mycred->core['buddypress']['history_location'] ) ) : ?>
						<?php
						$mycred_amount = mycred_display_users_balance( $bimber_current_user->ID );
						// Use %cred% instead of %cred_f% and the $mycred_amount is already formatted.
						$mycred_string = $mycred_amount == 1 ? '<strong>%cred%</strong> %_singular%' : '<strong>%cred%</strong> %_plural%';
						$mycred_url = bp_core_get_user_domain( $bimber_current_user->ID );

						if ( isset( $mycred->buddypress['history_url'] ) && ! empty( $mycred->buddypress['history_url'] ) ) {
							$mycred_url .= $mycred->buddypress['history_url'];
						}
						?>
						<li class="menu-item">
							<a href="<?php echo esc_url( $mycred_url ); ?>"><?php echo $mycred->template_tags_amount( $mycred_string, $mycred_amount ); ?></a>
						</li>
					<?php endif; ?>

				<?php endif; ?>

				<li class="menu-item">
					<a href="<?php echo esc_url( wp_logout_url( bimber_get_current_url() ) ); ?>"><?php esc_html_e( 'Log Out', 'bimber' ); ?></a>
				</li>
			</ul>
	<?php endif; ?>

	<?php
	if ( has_nav_menu( 'bimber_user_nav' ) ) {
		$bimber_user_nav = array(
			'theme_location'    => 'bimber_user_nav',
			'container'         => 'div',
			'container_class'   => 'g1-drop-content',
			'menu_class'        => 'sub-menu',
			'menu_id'           => '',
			'depth'             => 0,
		);
		if ( is_user_logged_in() ) {
			$bimber_user_nav['container'] = '';
			$bimber_user_nav['container_class'] = '';
		}

		wp_nav_menu( $bimber_user_nav );
	}
	?>

	<?php if ( is_user_logged_in() ) : ?>
		</div><!-- .g1-drop-content -->
	<?php endif; ?>
</nav>
