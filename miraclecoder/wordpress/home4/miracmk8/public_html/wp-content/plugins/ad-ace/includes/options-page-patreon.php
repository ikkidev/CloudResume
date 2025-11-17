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

add_filter( 'adace_options_tabs', 'adace_patreon_add_options_tab' );
/**
 * Add Options Tab
 */
function adace_patreon_add_options_tab( $tabs = array() ) {
	$tabs['adace_patreon'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_patreon',
		), '' ),
		'label'    => esc_html__( 'Patreon', 'adace' ),
		'settings' => 'adace_patreon_options',
	);
	return $tabs;
}

add_action( 'admin_menu', 'adace_add_patreon_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_patreon_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_patreon', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_patreon' // Page.
	);
	// Add setting field.
	add_settings_field(
		'adace_patreon_label', // Field ID.
		esc_html( 'Label', 'adace' ), // Field title.
		'adace_options_patreon_fields_renderer_callback', // Callback.
		'adace_patreon', // Page.
		'adace_patreon', // Section.
		array(
			'field_for' => 'adace_patreon_label',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_patreon', // Option group.
		'adace_patreon_label', // Option name.
		'adace_patreon_options_save_validator' // Options saving validator.
	);
	// Add setting field.
	add_settings_field(
		'adace_patreon_title', // Field ID.
		esc_html( 'Title', 'adace' ), // Field title.
		'adace_options_patreon_fields_renderer_callback', // Callback.
		'adace_patreon', // Page.
		'adace_patreon', // Section.
		array(
			'field_for' => 'adace_patreon_title',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_patreon', // Option group.
		'adace_patreon_title', // Option name.
		'adace_patreon_options_save_validator' // Options saving validator.
	);
	// Register setting.
	register_setting(
		'adace_patreon', // Option group.
		'adace_patreon_label', // Option name.
		'adace_patreon_options_save_validator' // Options saving validator.
	);
	// Add setting field.
	add_settings_field(
		'adace_patreon_link', // Field ID.
		esc_html( 'Link', 'adace' ), // Field title.
		'adace_options_patreon_fields_renderer_callback', // Callback.
		'adace_patreon', // Page.
		'adace_patreon', // Section.
		array(
			'field_for' => 'adace_patreon_link',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_patreon', // Option group.
		'adace_patreon_link', // Option name.
		'adace_patreon_options_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_patreon_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_shoppable_images_field_renderer_action', $args );
	$option = get_option( $args['field_for'], adace_options_get_defaults( $args['field_for'] ) );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_patreon_label':
		case 'adace_patreon_title':
		?>
		<textarea
			class="regular-text"
			id="<?php echo( esc_html( $args['field_for'] ) ); ?>"
			name="<?php echo( esc_html( $args['field_for'] ) ); ?>"
		><?php echo( html_entity_decode( $option ) ); ?></textarea>
		<?php
		break;
		case 'adace_patreon_link':
		?>
		<input class="regular-text" type="text" value="<?php echo esc_url( $option );?>" name="<?php echo esc_attr( $args['field_for'] );?>">
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
function adace_options_patreon_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_patreon_options_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_STRING )
	);
}
