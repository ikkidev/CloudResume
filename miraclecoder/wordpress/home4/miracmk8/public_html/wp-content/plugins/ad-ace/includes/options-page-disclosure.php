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

add_filter( 'adace_options_tabs', 'adace_disclosure_add_options_tab' );
/**
 * Add Options Tab
 */
function adace_disclosure_add_options_tab( $tabs = array() ) {
	$tabs['adace_disclosure'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_disclosure',
		), '' ),
		'label'    => esc_html__( 'Disclosure', 'adace' ),
		'settings' => 'adace_disclosure_options',
	);
	return $tabs;
}

add_action( 'admin_menu', 'adace_add_disclosure_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_disclosure_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_disclosure', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_disclosure' // Page.
	);
	// Add setting field.
	add_settings_field(
		'adace_disclosure_text', // Field ID.
		esc_html( 'Affiliate Disclosure', 'adace' ), // Field title.
		'adace_disclosure_fields_renderer_callback', // Callback.
		'adace_disclosure', // Page.
		'adace_disclosure', // Section.
		array(
			'field_for' => 'adace_disclosure_text',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_disclosure', // Option group.
		'adace_disclosure_text', // Option name.
		'adace_disclosure_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_disclosure_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_disclosure_field_renderer_action', $args );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_disclosure_text':
			$option = get_option( $args['field_for'], adace_options_get_defaults( $args['field_for'] ) );
		?>
		<textarea
			class="large-text"
			rows="4"
			id="<?php echo( esc_html( $args['field_for'] ) ); ?>"
			name="<?php echo( esc_html( $args['field_for'] ) ); ?>"
		><?php echo( html_entity_decode( $option ) ); ?></textarea>
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
function adace_disclosure_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_disclosure_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_FULL_SPECIAL_CHARS )
	);
}
