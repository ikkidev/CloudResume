<?php
/**
 * Displays the insecure password warning screen when a user logs in
 * if their password has been flagged as leaked.
 *
 * @package Newfold\WP\Module\Secure_Passwords
 */

$errors = new WP_Error();

if ( ! empty( $_REQUEST['redirect_to'] ) ) {
	$redirect_to = $_REQUEST['redirect_to'];
} else {
	$redirect_to = admin_url();
}

login_header( esc_html__( 'Insecure password detected', 'newfold' ), '', $errors );

?>
	<form class="sp-insecure-password-form" name="sp-insecure-password-form" action="<?php echo esc_url( get_edit_user_link() ); ?>" method="post">
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />

		<h1 class="admin-email__heading">
			<?php esc_html_e( 'Insecure password detected', 'newfold' ); ?>
		</h1>

		<p class="admin-email__details">
			<strong><?php esc_html_e( 'This does not mean your user account or WordPress site has been compromised.', 'newfold' ); ?></strong>
		</p>

		<p class="admin-email__details">
			<?php esc_html_e( 'The password you are using was found in a database of insecure passwords. This likely means that that it was part of a previously reported data breach, making it much more likely to be used in attempts by bad actors to compromise sites.', 'newfold' ); ?>
		</p>

		<p class="admin-email__details">
			<strong><?php esc_html_e( 'It is strongly recommended that you change your password.', 'newfold' ); ?></strong>
		</p>

		<div class="admin-email__actions">
			<div class="admin-email__actions-primary">
				<input type="submit" name="update-password" id="update-password" class="button button-primary button-large" value="<?php esc_attr_e( 'Change password', 'newfold' ); ?>" />
			</div>

			<div class="admin-email__actions-secondary">
				<?php

				$remind_me_link = wp_login_url( $redirect_to );
				$remind_me_link = add_query_arg(
					array(
						'action'              => 'nfd_sp_insecure_password',
						'nfd_sp_remind_later' => wp_create_nonce( 'nfd_sp_remind_later_nonce' ),
					),
					$remind_me_link
				);

				?>
				<a href="<?php echo esc_url( $remind_me_link ); ?>"><?php esc_html_e( 'Remind me later', 'newfold' ); ?></a>
			</div>
		</div>
	</form>

<?php

login_footer();

exit;
