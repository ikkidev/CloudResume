<?php
/**
 * Restrict Content Pro plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.2
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$rcp_settings = get_option( 'rcp_settings' );
?>
<div class="g1-rcp-message paid-restriction">
	<h2 class="g1-rcp-message-title g1-alpha g1-alpha-1st"><?php esc_html_e( 'This content is restricted to paid subscribers', 'bimber' ); ?></h2>
	<div class="g1-rcp-actions">
		<?php if ( isset( $rcp_settings['registration_page'] ) ) :  ?>
			<div class="g1-rcp-action g1-rcp-action-register">
				<div>
					<i class="g1-rcp-action-icon"></i>
				</div>
				<p class="g1-gamma g1-gamma-1st"><?php esc_html_e( 'New here?', 'bimber' ); ?></p>
				<p class="g1-rcp-action-buttons"><a href="<?php echo( esc_url( get_permalink( $rcp_settings['registration_page'] ) ) ); ?>" target="_blank" class="g1-button g1-button-solid g1-button-m g1-button-wide"><?php esc_html_e( 'Register', 'bimber' ); ?></a></p>
			</div>
		<?php endif; ?>
		<?php if ( isset( $rcp_settings['login_redirect'] ) ) :  ?>
			<div class="g1-rcp-action g1-rcp-action-login">
				<div>
					<i class="g1-rcp-action-icon"></i>
				</div>
				<p class="g1-gamma g1-gamma-1st"><?php esc_html_e( 'Already have an account?', 'bimber' ); ?></p>
				<p class="g1-rcp-action-buttons"><a href="<?php echo( esc_url( get_permalink( $rcp_settings['login_redirect'] ) ) ); ?>" target="_blank" class="g1-button g1-button-solid g1-button-m g1-button-wide"><?php esc_html_e( 'Login', 'bimber' ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
</div><!-- .g1-restricted -->