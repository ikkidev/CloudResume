<?php
/**
 * WP QUADS plugin functions
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

add_action( 'after_setup_theme', 	'bimber_adace_register_ad_ids' );
add_action( 'admin_head',           'bimber_adace_hide_places' );
add_action( 'widgets_init',         'bimber_adace_deregister_places_widget', 11,0 );

add_filter( 'adace_patreon_widget_options', 'bimber_adace_patreon_widget_options' );
add_filter( 'adace_shop_the_post_shortcode_columns', 'bimber_adace_shop_the_post_shortcode_columns', 10, 1 );

add_filter( 'bimber_ad_settings_url', 'bimber_adace_ad_settings_url', 10, 2 );

/**
 * Register custom ad ids
 */
function bimber_adace_register_ad_ids() {

	adace_register_ad_section( 'bimber', __( 'Bimber Custom Slots', 'bimber' ) );

	adace_register_ad_slot( array(
		'id'   => 'bimber_before_header_theme_area',
		'name' => esc_html__( 'Before header theme area', 'bimber' ),
		'section' => 'bimber',
		'custom_options' => array(
			'background_color' => false,
			'background_color_editable' => true,
		),
	) );

	adace_register_ad_slot( array(
		'id'   => 'bimber_inside_header',
		'name' => esc_html__( 'Inside header', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot( array(
		'id'   => 'bimber_before_content_theme_area',
		'name' => esc_html__( 'Before content theme area', 'bimber' ),
		'section' => 'bimber',
		'custom_options' => array(
			'background_color' => false,
			'background_color_editable' => true,
		),
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_after_featured_content',
		'name' => esc_html__( 'After featured entries', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_before_related_entries',
		'name' => esc_html__( 'Before "You May Also Like" section', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot(	array(
		'id'    => 'bimber_before_more_from',
		'name' => esc_html__( 'Before "More From" section', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_before_comments',
		'name' => esc_html__( 'Before "Comments" section', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_before_dont_miss',
		'name' => esc_html__( 'Before "Don\'t Miss" section', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_inside_grid',
		'name' => esc_html__( 'Inside grid collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_inside_grid_s',
		'name' => esc_html__( 'Inside small grid collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot(	array(
		'id'    => 'bimber_inside_list',
		'name' => esc_html__( 'Inside list collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_inside_classic',
		'name' => esc_html__( 'Inside classic collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_inside_stream',
		'name' => esc_html__( 'Inside stream collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot(	array(
		'id'    => 'bimber_inside_zigzag',
		'name' => esc_html__( 'Inside zigzag collection', 'bimber' ),
		'section' => 'bimber',
		'is_repeater' => true,
		'options' => array(
			'is_singular_editable'       => false,
		),
	) );

	adace_register_ad_slot( array(
		'id'    => 'bimber_left_stream',
		'name' => esc_html__( 'On the left side of stream collection', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot(	array(
		'id'    => 'bimber_right_stream',
		'name' => esc_html__( 'On the right side of stream collection', 'bimber' ),
		'section' => 'bimber',
	) );

	adace_register_ad_slot(	array(
		'id'    => 'bimber_link_exit',
		'name' => esc_html__( 'After link exit counter', 'bimber' ),
		'section' => 'bimber',
	) );

	// Before post pagination
	adace_register_ad_slot(	array(
		'id'    => 'bimber_before_pagination',
		'name' => esc_html__( 'Before pagination', 'bimber' ),
		'section' => 'bimber',
	) );

	// After post pagination
	adace_register_ad_slot(	array(
		'id'    => 'bimber_after_pagination',
		'name' => esc_html__( 'After pagination', 'bimber' ),
		'section' => 'bimber',
	) );

}

/**
 * Hide Places menu
 */
function bimber_adace_hide_places() {
	remove_menu_page( 'edit.php?post_type=adace_place' );
}

/**
 * Ads widget register function
 */
function bimber_adace_deregister_places_widget() {
	unregister_widget( 'Adace_Places_Widget' );
}


function bimber_adace_patreon_widget_options( $options ) {
	$options['classname'] .= ' g1-box';

	return $options;
}

function bimber_adace_shop_the_post_shortcode_columns( $cols ) {
	return 3;
}

add_filter( 'adace_options_slot_fields_filter', 'bimber_adace_before_header_slot_option', 10, 2 );
/**
 * Add Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function bimber_adace_before_header_slot_option( $slot_fields, $adace_ad_slot ) {
	$r = array(
		'bimber_before_header_theme_area',
		'bimber_before_content_theme_area'
	);

	if ( ! in_array( $adace_ad_slot['id'], $r, true ) ) {
		return $slot_fields;
	}
	$slot_fields['background_color'] = esc_html__( 'Background color', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'bimber_adace_before_header_slot_option_renderer', 10, 2 );
/**
 * Add Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function bimber_adace_before_header_slot_option_renderer( $args, $slot_options ) {
	$r = array(
		'bimber_before_header_theme_area',
		'bimber_before_content_theme_area'
	);

	if ( ! in_array( $args['slot']['id'], $r, true ) ) {
		return;
	}

	$background_color_editable = $args['slot']['custom_options']['background_color_editable'];
	if ( $background_color_editable ) {
		$background_color_current = isset( $slot_options['background_color'] ) ? $slot_options['background_color'] : $args['slot']['custom_options']['background_color'];
	} else {
		$background_color_current = $args['slot']['custom_options']['background_color'];
	}

	if ( 'background_color' === $args['field_for'] ) :
	?>
	<input
		type="text"
		class="bimber-color-picker"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[background_color]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[background_color]"
		value="<?php echo esc_attr( $background_color_current ); ?>"
		<?php echo( $background_color_editable ? '' : ' disabled' );  ?>
	/>
	<?php
	endif;
}
add_filter( 'adace_slots_options_save_validator_filter', 'bimber_adace_before_header_slot_option_save_validator', 10, 2 );
/**
 * Add Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function bimber_adace_before_header_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['background_color'] ) ) {
		$input_sanitized['background_color'] = bimber_htmlspecialchars( filter_var( $input['background_color'] ) );
	} else {
		$input_sanitized['background_color'] = false;
	}
	return $input_sanitized;
}

/**
 * Return URL to the plugin ad settings
 *
 * @param string $url       Ad settings URL.
 * @param string $slot      Ad slot id.
 *
 * @return string
 */
function bimber_adace_ad_settings_url( $url, $slot ) {
    $url = admin_url( 'admin.php?page=adace_options&tab=adace_slots&open_slot=' . $slot );

    return $url;
}