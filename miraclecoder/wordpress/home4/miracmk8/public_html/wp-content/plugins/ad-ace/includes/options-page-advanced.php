<?php
/**
 * Options Page for Sponsors
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_options_tabs',   'adace_add_advanced_tab' );
add_action( 'admin_menu',           'adace_add_advanced_sections_and_fields' );

/**
 * Add Options Tab
 */
function adace_add_advanced_tab( $tabs = array() ) {
	$tabs['adace_advanced'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_advanced',
		), '' ),
		'label'    => esc_html__( 'Advanced', 'adace' ),
		'settings' => 'adace_advanced',
	);

	return $tabs;
}


/**
 * Add options page sections, fields and options.
 */
function adace_add_advanced_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_advanced', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_advanced' // Page.
	);

	// Add setting field.
	add_settings_field(
		'adace_adblocker_detection',
		esc_html( 'AdBlocker', 'adace' ), // Field title.
		'adace_render_adblocker_detection_field', // Callback.
		'adace_advanced', // Page.
		'adace_advanced', // Section.
		array() // Data for callback.
	);

	// Add setting field.
	add_settings_field(
		'adace_rewrite_plugin_name',
		esc_html( 'Rewrite plugin name', 'adace' ), // Field title.
		'adace_render_rewrite_plugin_name_field', // Callback.
		'adace_advanced', // Page.
		'adace_advanced', // Section.
		array() // Data for callback.
	);

	// Register setting.
	register_setting(
		'adace_advanced',               // Option group.
		'adace_rewrite_plugin_name',    // Option name.
		'adace_advanced_save_validator' // Options saving validator.
	);
}

/**
 * Field renderer.
 */
function adace_render_adblocker_detection_field() {
	wp_enqueue_script( 'adace-adblocker-trap', adace_get_plugin_url( true ) . 'assets/js/adblocker-trap.js', array(), adace_get_plugin_version(), true );

	?>
	<p id="adace-adblocker-detected">
		<span style="color: #ff0000;"><?php echo esc_html_x( 'detected', 'AdBlocker detection status', 'adace' ); ?></span>
		<br />
		<br />
		<?php esc_html_e( 'To use the AdAce plugin, the below option "Rewrite plugin name" has to be enabled', 'adace' ); ?>
	</p>
	<p id="adace-adblocker-not-detected" style="display: none">
		<?php echo esc_html_x( 'not detected', 'AdBlocker detection status', 'adace' ); ?>
	</p>
	<?php
}

/**
 * Fields renderer.
 */
function adace_render_rewrite_plugin_name_field() {
	$rewrite = adace_rewrite_plugin_name();
	?>
	<input type="checkbox" name="adace_rewrite_plugin_name" value="standard"<?php checked( true, $rewrite ) ?> />
	<?php

	if ( is_multisite() && adace_rewrite_plugin_name() ) {
		$slashed_home      = trailingslashit( get_option( 'home' ) );
		$base              = parse_url( $slashed_home, PHP_URL_PATH );
		$document_root_fix = str_replace( '\\', '/', realpath( $_SERVER['DOCUMENT_ROOT'] ) );
		$abspath_fix       = str_replace( '\\', '/', ABSPATH );
		$home_path         = 0 === strpos( $abspath_fix, $document_root_fix ) ? $document_root_fix . $base : get_home_path();
		?>
		<br />
		<p>
			<?php
			printf(
			/* translators: 1: a filename like .htaccess. 2: a file path. */
				_x( 'You are using multisite so you have to manually add the following to your %1$s file in %2$s, at the beginning:', 'Advanced settings tab', 'adace' ),
				'<code>.htaccess</code>',
				'<code>' . $home_path . '</code>'
			);
			?>
		</p>
		<br />
		<textarea rows="10" cols="80" disabled><?php echo esc_textarea( adace_get_plugin_name_rewrite_rules() ); ?></textarea>
		<?php
	}

	if ( adace_rewrite_plugin_name() ) {
		$is_nginx = ( strpos( $_SERVER['SERVER_SOFTWARE'], 'nginx' ) !== false );

		if ( $is_nginx ): ?>
		<br />
		<h3><?php _ex( 'You use Nginx server. You have to apply the steps below', 'Advanced settings tab', 'adace' ); ?></h3>
		<p>
			<?php _ex( 'With Nginx there is no directory-level configuration file like Apache’s .htaccess or IIS’s web.config files. All configuration has to be done at the server level by an administrator, and WordPress cannot modify the configuration, like it can with Apache or IIS.', 'Advanced settings tab', 'adace' ); ?>
		</p>
		<br />
		<p>
			<?php _ex( 'To rewrite the plugin name, you have to ask your administrator to add manually the below rewrite rules to your Nginx configuration file, in any server {} block.', 'Advanced settings tab', 'adace' ); ?>
		</p>
		<br />
		<textarea rows="10" cols="80" disabled><?php echo esc_textarea( adace_get_plugin_name_rewrite_rules_nginx() ); ?></textarea>
		<?php endif;
	}
}

/**
 * Options validator.
 *
 * @param array $input Saved options.
 * @return array Sanitised options for save.
 */
function adace_advanced_save_validator( $input ) {
	return $input;
}
