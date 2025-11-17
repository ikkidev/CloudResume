<?php
/**
 * Theme options "Welcome" section (demo data installation step)
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

$section_id = 'g1ui-settings-section-registration';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	'',        // Title to be displayed on the administration page.
	null,
	$this->get_page()                   // Page on which to add this section of options.
);

add_settings_field(
	'theme_registration',
	'',
	'bimber_render_theme_registration_section',
	$this->get_page(),
	$section_id
);

/**
 * Render section
 */
function bimber_render_theme_registration_section() {
	$purchase_code          = bimber_get_registered_purchase_code();
	$theme_is_registered    = bimber_is_theme_registered();
	?>
	</td></tr></tbody></table>


	<div style="margin-top: -3em;"></div>

	<div class="wrap" id="bimber-theme-registration">
		<?php if ( ! $theme_is_registered ) : ?>
			<h1><?php esc_html_e( 'Register theme', 'bimber' ); ?></h1>
			<p style="max-width: 500px;">
				<?php esc_html_e( 'You\'re almost done. Just one more step. In order to gain full access to all demos, premium plugins and support, please register your theme\'s purchase code.',  'bimber' ); ?>
			</p>
		<?php else: ?>
			<h1><?php esc_html_e( 'Registration Completed &#127881;', 'bimber' ); ?></h1>
			<p><?php esc_html_e( 'Your theme is registered and ready to use.', 'bimber' ); ?></p>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Your Envato Purchase Code', 'bimber' ); ?></h2>

		<div id="bimber-registration-error">
		</div>
		<p>
			<input class="regular-text code" type="text" name="bimber_purchase_code" id="bimber-purchase-code" value="<?php echo esc_attr( $purchase_code ); ?>" />
            <?php if ( ! $theme_is_registered ) : ?>
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><?php esc_html_e( 'Where to find the code?', 'bimber' ); ?></a>
            <?php else : ?>
                <a href="#" data-bimber-nonce="<?php echo wp_create_nonce( 'bimber_deregister_theme' ); ?>" id="bimber-deregister-theme"><?php esc_html_e( 'Deregister the purchase code', 'bimber' ); ?></a>
            <?php endif; ?>
		</p>

		<?php if ( ! $purchase_code ) : ?>
		<p>
			<label>
				<input type="checkbox" id="bimber-accept-license-terms" />
				<?php esc_html_e( 'I confirm that, according to the Envato License Terms, I am licensed to use the purchase code for a single project. Using it on multiple installations is a copyright violation.', 'bimber' ); ?>
			</label>
			<a href="https://themeforest.net/licenses/terms/regular" target="_blank"><?php esc_html_e( 'License details.', 'bimber' ); ?></a>
		</p>
		<?php endif; ?>


		<?php if ( ! $purchase_code ) : ?>
		<p class="bimber-actions">
			<button class="button button-primary button-hero" id="bimber-register-theme" disabled="disabled"><?php echo esc_html_x( 'Register Theme', 'Theme activation' , 'bimber' ); ?></button>
			<span class="spinner"></span>
		</p>
		<?php endif; ?>

		<div id="bimber-alt-registration" style="display: none;">
			<h2><?php esc_html_e( 'Token Registration', 'bimber' ); ?></h2>
			<div id="bimber-token-registration-error"></div>
			<p>
				<?php $token_url = sprintf( 'https://api.bringthepixel.com/?action=register&theme=bimber&purchase_code=PURCHASE_CODE&site_url=%s', urlencode( home_url() ) ); ?>
				<?php esc_html_e( 'When automatic registration fails, please unlock the theme using this token method. Click the following link to get your individual token and enter it below.', 'bimber' ); ?> <a id="bimber-token-generator-url" href="<?php echo esc_url( $token_url ); ?>" target="_blank"><?php esc_html_e( 'Generate token', 'bimber' ); ?></a>
			</p>
			<p>
				<input class="regular-text code" type="text" name="bimber_token" id="bimber-token" value="" />
			</p>

			<button class="button button-primary button-hero" id="bimber-register-with-token" disabled="disabled"><?php echo esc_html_x( 'Register Theme with Token', 'Theme activation' , 'bimber' ); ?></button>
			<span class="spinner"></span>
		</div>
	</div>

	<table><tbody><tr><td>
	<?php
}
