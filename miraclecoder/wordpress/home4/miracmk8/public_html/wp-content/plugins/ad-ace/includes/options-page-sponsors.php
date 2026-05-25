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

add_filter( 'adace_options_tabs', 'adace_sponsors_add_options_tab' );
/**
 * Add Options Tab
 */
function adace_sponsors_add_options_tab( $tabs = array() ) {
	$tabs['adace_sponsors'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_sponsors',
		), '' ),
		'label'    => esc_html__( 'Sponsors', 'adace' ),
		'settings' => 'adace_sponsors_options',
	);
	return $tabs;
}

add_action( 'admin_menu', 'adace_add_sponsors_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_sponsors_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_sponsors', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_sponsors' // Page.
	);
	// Add setting field.
	add_settings_field(
		'adace_sponsor_before_post', // Field ID.
		esc_html( '"Sponsored by" before post', 'adace' ), // Field title.
		'adace_options_sponsor_fields_renderer_callback', // Callback.
		'adace_sponsors', // Page.
		'adace_sponsors', // Section.
		array(
			'field_for' => 'adace_sponsor_before_post',
		) // Data for callback.
	);
	// Add setting field.
	add_settings_field(
		'adace_sponsor_after_post', // Field ID.
		esc_html( '"Sponsored by" after post', 'adace' ), // Field title.
		'adace_options_sponsor_fields_renderer_callback', // Callback.
		'adace_sponsors', // Page.
		'adace_sponsors', // Section.
		array(
			'field_for' => 'adace_sponsor_after_post',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_sponsors', // Option group.
		'adace_sponsor_before_post', // Option name.
		'adace_sponsor_options_save_validator' // Options saving validator.
	);
	// Register setting.
	register_setting(
		'adace_sponsors', // Option group.
		'adace_sponsor_after_post', // Option name.
		'adace_sponsor_options_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_sponsor_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_sponsor_field_renderer_action', $args );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_sponsor_before_post':
		case 'adace_sponsor_after_post':
			$option = get_option( $args['field_for'], adace_options_get_defaults( $args['field_for'] ) );
			?>
			<select id="<?php echo( esc_html( $args['field_for'] ) ); ?>" name="<?php echo( esc_html( $args['field_for'] ) ); ?>">
				<option value="" <?php selected( $option, '' ); ?>><?php esc_html_e( '- Disabled -', 'adace' ); ?></option>
				<option value="compact" <?php selected( $option, 'compact' ); ?>><?php esc_html_e( 'Compact', 'adace' ); ?></option>
				<option value="full" <?php selected( $option, 'full' ); ?>><?php esc_html_e( 'Full', 'adace' ); ?></option>
			</select>
			<?php
			break;
	}
}

/**
 * Options validator.
 *
 * @param array $input Saved options.
 * @return array Sanitised options for save.
 */
function adace_sponsor_options_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_sponsor_options_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_STRING )
	);
}
