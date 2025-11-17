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

$section_id = 'g1ui-settings-section-gdpr';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	'',        // Title to be displayed on the administration page.
	null,
	$this->get_page()                   // Page on which to add this section of options.
);

add_settings_field(
	'theme_gdpr',
	'',
	'bimber_render_theme_gdpr_section',
	$this->get_page(),
	$section_id
);

add_settings_field(
	'gdpr_enabled',
	esc_html__( 'Enable GDPR', 'bimber' ),
	array(
		$this,
		'render_checkbox',
	),
	$this->get_page(),
	$section_id,
	array(
		'field_name'    => 'gdpr_enabled',
		'default_value' => $bimber_theme_options_defaults['gdpr_enabled'],
	)
);

add_settings_field(
	'gdpr_wpsl_consent',
	esc_html__( 'WP Social Login consent text', 'bimber' ),
	array(
		$this,
		'render_input',
	),
	$this->get_page(),
	$section_id,
	array(
		'field_name'    => 'gdpr_wpsl_consent',
		'default_value' => $bimber_theme_options_defaults['gdpr_wpsl_consent'],
		'hint'			=> __( 'You can use: %privacy_policy% to create a link to the Privacy Policy page.', 'bimber' ),
		'size'			=> 120,
	)
);

/**
 * Render section
 */
function bimber_render_theme_gdpr_section() {
	/**
	 * Automatic plugin installation and activation library
	 *
	 * @var TGM_Plugin_Activation $tgmpa
	 */
	global $tgmpa;

	$action = '';
	$install_wp_gdpr_compliance_plugin = '';

	if ( ! $tgmpa->is_plugin_active( 'wp-gdpr-compliance' ) ) {
		if ( ! $tgmpa->is_plugin_installed( 'wp-gdpr-compliance' ) ) {
			$action = 'install';
		} else if ( $tgmpa->can_plugin_activate( 'wp-gdpr-compliance' ) ) {
			$action = 'activate';
		}

		$install_wp_gdpr_compliance_plugin =
		wp_nonce_url(
			add_query_arg(
				array(
					'plugin'           => urlencode( 'wp-gdpr-compliance' ),
					'tgmpa-' . $action => $action . '-plugin',
				),
				$tgmpa->get_tgmpa_url()
			),
			'tgmpa-' . $action,
			'tgmpa-nonce'
		);
	}
	?>
	</td></tr>
	<tr>
	<td colspan="2" style="padding-left: 0;">

	<div style="margin-top: -3em;"></div>

	<div>
		<h1><?php esc_html_e( 'GDPR', 'bimber' ); ?></h1>
		<br />
		<fieldset>
			<label>
				<?php $g1_theme_options   = get_option( bimber_get_theme_options_id() );
				$gdpr_enabled = isset( $g1_theme_options['gdpr_enabled'] ) ? $g1_theme_options['gdpr_enabled'] : false ;
				?>
				<input name="bimber-enable-gdpr" id="bimber-enable-gdpr" type="checkbox" <?php checked( $gdpr_enabled, 'on' );?>/>
				<?php _e( 'Enable GDPR integration', 'bimber' ); ?>
			</label>

			<br />

			<p class="gdpr-plugin-info">
				<?php esc_html_e( 'The Bimber theme uses the WP GDPR Compliance for GDPR integration.', 'bimber' ); ?>
			</p>
		<?php if ( $install_wp_gdpr_compliance_plugin ) : ?>
			<p class="gdpr-plugin-info">
				<span class="wp-gdpr-compliance-not-activated">
				<?php if ( 'install' === $action ) : ?>
					<?php printf( __( '<a href="%s" class="g1-install-wp-gdpr-compliance">Install the WP GDPR Compliance</a> plugin.', 'bimber' ),$install_wp_gdpr_compliance_plugin ); ?>
				<?php else : ?>
					<?php printf( __( '<a href="%s" class="g1-install-wp-gdpr-compliance">Activate the WP GDPR Compliance</a> plugin.', 'bimber' ),$install_wp_gdpr_compliance_plugin ); ?>
				<?php endif; ?>
				</span>
				<span class="wp-gdpr-compliance-installing" style="display: none;">
					<?php esc_html_e( 'Installing the WP GDPR Compliance plugin...', 'bimber' ); ?>
				</span>
				<span class="wp-gdpr-compliance-activated" style="display: none;">
					<?php printf( __( 'WP GDPR Compliance is ready to use, <a href="%s">go to the plugin\'s settings</a>.', 'bimber' ), esc_url( admin_url( '/tools.php?page=wp_gdpr_compliance' ) ) ) ?>
				</span>
				<span class="wp-gdpr-compliance-installation-failed" style="display: none;">
					<?php esc_html_e( 'The WP GDPR Compliance plugin installation failed.', 'bimber' ); ?>
					<?php printf( __( '<a href="%s" target="_blank">See details</a>.', 'bimber' ), $install_wp_gdpr_compliance_plugin ) ?>
				</span>
			</p>
		<?php else : ?>
		<p class="gdpr-plugin-info">
			<span>
			<?php printf( __( 'WP GDPR Compliance is ready to use, <a href="%s">go to the plugin\'s settings</a>.', 'bimber' ), esc_url( admin_url( '/tools.php?page=wp_gdpr_compliance' ) ) ) ?>
			</span>
		</p>
		<?php endif; ?>


		</fieldset>
	</div>
	<?php
}
