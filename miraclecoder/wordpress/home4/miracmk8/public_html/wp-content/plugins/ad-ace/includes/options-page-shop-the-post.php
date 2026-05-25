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

add_filter( 'adace_options_tabs', 'adace_shop_the_post_add_options_tab' );
/**
 * Add Options Tab
 */
function adace_shop_the_post_add_options_tab( $tabs = array() ) {
	$tabs['adace_shop_the_post'] = array(
		'path'     => add_query_arg( array(
			'page' => adace_options_page_slug(),
			'tab'  => 'adace_shop_the_post',
		), '' ),
		'label'    => esc_html__( 'Shop the Post', 'adace' ),
		'settings' => 'adace_shop_the_post_options',
	);
	return $tabs;
}

add_action( 'admin_menu', 'adace_add_shop_the_post_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_shop_the_post_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'adace_shop_the_post', // Section id.
		'', // Section title.
		'', // Section renderer callback with args pass.
		'adace_shop_the_post' // Page.
	);
	// Add setting field.
	add_settings_field(
		'adace_shop_the_post_excerpt', // Field ID.
		esc_html( 'Show excerpt', 'adace' ), // Field title.
		'adace_shop_the_post_fields_renderer_callback', // Callback.
		'adace_shop_the_post', // Page.
		'adace_shop_the_post', // Section.
		array(
			'field_for' => 'adace_shop_the_post_excerpt',
		) // Data for callback.
	);
	// Add setting field.
	add_settings_field(
		'adace_shop_the_post_excerpt_hide_on_single', // Field ID.
		esc_html( 'Hide excerpt on single post', 'adace' ), // Field title.
		'adace_shop_the_post_fields_renderer_callback', // Callback.
		'adace_shop_the_post', // Page.
		'adace_shop_the_post', // Section.
		array(
			'field_for' => 'adace_shop_the_post_excerpt_hide_on_single',
		) // Data for callback.
	);
	// Add setting field.
	add_settings_field(
		'adace_shop_the_post_disclosure', // Field ID.
		esc_html( 'Show disclosure', 'adace' ), // Field title.
		'adace_shop_the_post_fields_renderer_callback', // Callback.
		'adace_shop_the_post', // Page.
		'adace_shop_the_post', // Section.
		array(
			'field_for' => 'adace_shop_the_post_disclosure',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'adace_shop_the_post', // Option group.
		'adace_shop_the_post_excerpt', // Option name.
		'adace_options_shop_the_post_save_validator' // Options saving validator.
	);
	// Register setting.
	register_setting(
		'adace_shop_the_post', // Option group.
		'adace_shop_the_post_excerpt_hide_on_single', // Option name.
		'adace_options_shop_the_post_save_validator' // Options saving validator.
	);
	// Register setting.
	register_setting(
		'adace_shop_the_post', // Option group.
		'adace_shop_the_post_disclosure', // Option name.
		'adace_options_shop_the_post_save_validator' // Options saving validator.
	);
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_shop_the_post_fields_renderer_callback( $args ) {
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_shop_the_post_field_renderer_action', $args );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'adace_shop_the_post_excerpt':
		case 'adace_shop_the_post_disclosure':
		case 'adace_shop_the_post_excerpt_hide_on_single':
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
function adace_options_shop_the_post_save_validator( $input ) {
	// Return.
	return apply_filters(
		'adace_shop_the_post_save_validator_filter',
		filter_var( $input, FILTER_SANITIZE_STRING )
	);
}
