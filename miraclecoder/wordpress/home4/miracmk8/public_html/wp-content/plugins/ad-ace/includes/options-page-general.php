<?php
/**
 * Options Page for general
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'admin_menu', 'adace_add_general_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_general_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_general', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_general' // Page.
	);

	// Add setting field.
	add_settings_field(
		'adace_general_ad_limit', // Field ID.
		esc_html( 'Maximum ads per page', 'adace' ), // Field title.
		'adace_options_general_fields_renderer_callback', // Callback.
		'adace_general', // Page.
		'adace_general', // Section.
		array(
			'field_for' => 'adace_general_ad_limit',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_general', // Option group.
		'adace_general_ad_limit', // Option name.
		'adace_general_options_save_validator' // Options saving validator.
	);

	// Add setting field.
	add_settings_field(
		'adace_general_desktop_breakpoint', // Field ID.
		esc_html( 'Desktop minimum width', 'adace' ), // Field title.
		'adace_options_general_fields_renderer_callback', // Callback.
		'adace_general', // Page.
		'adace_general', // Section.
		array(
			'field_for' => 'adace_general_desktop_breakpoint',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_general', // Option group.
		'adace_general_desktop_breakpoint', // Option name.
		'adace_general_options_save_validator' // Options saving validator.
	);

	// Add setting field.
	add_settings_field(
		'adace_general_landscape_breakpoint', // Field ID.
		esc_html( 'Tablet landscape minimum width', 'adace' ), // Field title.
		'adace_options_general_fields_renderer_callback', // Callback.
		'adace_general', // Page.
		'adace_general', // Section.
		array(
			'field_for' => 'adace_general_landscape_breakpoint',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_general', // Option group.
		'adace_general_landscape_breakpoint', // Option name.
		'adace_general_options_save_validator' // Options saving validator.
	);

	// Add setting field.
	add_settings_field(
		'adace_general_portrait_breakpoint', // Field ID.
		esc_html( 'Tablet portrait minimum width', 'adace' ), // Field title.
		'adace_options_general_fields_renderer_callback', // Callback.
		'adace_general', // Page.
		'adace_general', // Section.
		array(
			'field_for' => 'adace_general_portrait_breakpoint',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_general', // Option group.
		'adace_general_portrait_breakpoint', // Option name.
		'adace_general_options_save_validator' // Options saving validator.
	);

	// Add setting field.
	add_settings_field(
		'adace_general_disclaimer', // Field ID.
		esc_html( 'Ad disclaimer', 'adace' ), // Field title.
		'adace_options_general_fields_renderer_callback', // Callback.
		'adace_general', // Page.
		'adace_general', // Section.
		array(
			'field_for' => 'adace_general_disclaimer',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_general', // Option group.
		'adace_general_disclaimer', // Option name.
		'adace_general_options_disclaimer_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_general_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_general_field_renderer_action', $args );
	$default_breakpoints = adace_general_get_default_breakepoints();
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_general_desktop_breakpoint':
		case 'adace_general_landscape_breakpoint':
		case 'adace_general_portrait_breakpoint':
			$general_option = get_option( $args['field_for'], $default_breakpoints[ $args['field_for'] ] );
		?>
		<input step="1" min="1" max="9999" class="small-text" type="number" value="<?php echo intval( $general_option );?>" name="<?php echo esc_attr( $args['field_for'] );?>"> px
		<?php
		break;
		case 'adace_general_ad_limit':
			$general_option = intval( get_option( 'adace_general_ad_limit', 0 ) );
			?>
		<input step="1" min="0" max="999" class="small-text" type="number" value="<?php echo intval( $general_option );?>" name="<?php echo esc_attr( $args['field_for'] );?>">
		<p class="description">0 for unlimited</p>
		<?php
		break;
		case 'adace_general_disclaimer':
			$general_option =  get_option( 'adace_general_disclaimer', '' );
			?>
		<textarea class="large-text" rows="4" name="<?php echo esc_attr( $args['field_for'] );?>"><?php echo( $general_option );?></textarea>
		<p class="description">Put before all slots, widgets and shortcodes. Accepts &lt;div&gt;,&lt;span&gt;,&lt;a&gt;,&lt;strong&gt;,&lt;img&gt;,&lt;br&gt;,&lt;p&gt;</p>
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
function adace_general_options_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_general_options_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_NUMBER_INT )
	);
}

/**
 * Options validator.
 *
 * @param array $input Saved options.
 * @return array Sanitised options for save.
 */
function adace_general_options_disclaimer_save_validator( $input ) {
	// Return.
	$value = strip_tags ( $input, '<div><span><a><strong><img><br><p>' );
	return apply_filters(
		'adace_general_options_disclaimer_save_validator_filter',
		$value
	);
}

/**
 * Return default breakpoints
 *
 * @return array
 */
function adace_general_get_default_breakepoints() {
	$breakepoints = array(
		'adace_general_desktop_breakpoint'   => 961,
		'adace_general_landscape_breakpoint' => 801,
		'adace_general_portrait_breakpoint'  => 601,
	);
	return apply_filters( 'adace_default_breakpoints', $breakepoints );
}
