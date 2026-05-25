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

add_filter( 'adace_options_tabs', 'adace_shoppable_images_add_options_tab' );
/**
 * Add Options Tab
 */
function adace_shoppable_images_add_options_tab( $tabs = array() ) {
	$tabs['adace_shoppable_images'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_shoppable_images',
		), '' ),
		'label'    => esc_html__( 'Shoppable Images', 'adace' ),
		'settings' => 'adace_shoppable_images_options',
	);
	return $tabs;
}

add_action( 'admin_menu', 'adace_add_shoppable_images_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_shoppable_images_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_shoppable_images', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_shoppable_images' // Page.
	);
	// Add setting field.
	add_settings_field(
		'adace_shoppable_images_animate_pins', // Field ID.
		esc_html( 'Animate Pins', 'adace' ), // Field title.
		'adace_options_shoppable_images_fields_renderer_callback', // Callback.
		'adace_shoppable_images', // Page.
		'adace_shoppable_images', // Section.
		array(
			'field_for' => 'adace_shoppable_images_animate_pins',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_shoppable_images', // Option group.
		'adace_shoppable_images_animate_pins', // Option name.
		'adace_shoppable_images_options_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_shoppable_images_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_shoppable_images_field_renderer_action', $args );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_shoppable_images_animate_pins':
			$option = get_option( $args['field_for'], adace_options_get_defaults( $args['field_for'] ) );
			?>
			<select id="<?php echo( esc_html( $args['field_for'] ) ); ?>" name="<?php echo( esc_html( $args['field_for'] ) ); ?>">
				<option value="1" <?php selected( $option, '1' ); ?>><?php esc_html_e( 'Enable', 'adace' ); ?></option>
				<option value="0" <?php selected( $option, '0' ); ?>><?php esc_html_e( 'Disable', 'adace' ); ?></option>
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
function adace_options_shoppable_images_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_shoppable_images_options_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_STRING )
	);
}
