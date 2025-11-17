<?php
/**
 * Theme options "Dashboard" section
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


$section_id = 'g1ui-settings-section-dashboard';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	'',        // Title to be displayed on the administration page.
	null,
	$this->get_page()                   // Page on which to add this section of options.
);

add_settings_field(
	'theme_dashboard_normal',
	'',
	'bimber_render_theme_dashborad_normal_section',
	$this->get_page(),
	$section_id
);

/**
 * Render dashborad section (after passing demo data step)
 */
function bimber_render_theme_dashborad_normal_section() {
	?>
	</td></tr>
	<tr>
	<td colspan="2" style="padding-left: 0;">

	<div style="margin-top: -3em;"></div>

	<div class="about-wrap">

		<h1><?php esc_html_e( 'Welcome to Bimber Theme', 'bimber' ); ?></h1>

		<h3><?php esc_html_e( 'Join Our Community', 'bimber' ); ?></h3>

		<div class="g1ui-cols">
			<div class="g1ui-col">
				<h4><?php esc_html_e( 'Never Miss a News', 'bimber' ); ?></h4>
				<p><?php printf( wp_kses_post( __( 'Stay up to date with all upcoming updates, important notes and announcements. Follow us on <a href="%s" target="_blank">Facebook</a> or <a href="%s" target="_blank">Twitter</a>.', 'bimber' ) ), esc_url( 'http://on.fb.me/1KmhAov' ), esc_url( 'http://bit.ly/1eiKcmX' ) ); ?></p>
			</div>

			<div class="g1ui-col">
				<h4><?php esc_html_e( 'Rate the Theme', 'bimber' ); ?></h4>
				<p><?php printf( wp_kses_post( __( 'If you are happy with our theme and support, please don\'t forget to rate the theme on <a href="%s" target="_blank">ThemeForest</a>. Thanks in advance.', 'bimber' ) ), esc_url( 'http://themeforest.net/downloads?filter_by=themeforest.net' ) ) ?></p>
			</div>
		</div>

		<h3><?php esc_html_e( 'Need Some Help?', 'bimber' ); ?></h3>

		<div class="g1ui-cols">
			<div class="g1ui-col">
				<h4><?php esc_html_e( 'Check Online Documentation', 'bimber' ); ?></h4>
				<p><?php printf( wp_kses_post( __( 'All information about theme installation, configuration and customization can be found in our <a href="%s" target="_blank">online documentation</a>.', 'bimber' ) ), esc_url( 'http://bit.ly/1RUE6dK' ) ); ?></p>
			</div>

			<div class="g1ui-col">
				<h4><?php esc_html_e( 'Use Support Centre', 'bimber' ); ?></h4>
				<p><?php printf( wp_kses_post( __( 'Support is conducted through our <a href="%s" target="_blank">Support Centre</a>, where you can submit your questions, bug-findings, etc.', 'bimber' ) ), esc_url( 'http://bit.ly/2mUIhPk' ) ); ?></p>
			</div>
		</div>

	</div>




	<?php
}


