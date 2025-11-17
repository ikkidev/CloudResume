<?php
/**
 * Before Content
 *
 * @package AdAce
 * @subpackage Default Slots
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get Before Content Slot Id
 *
 * @return string
 */
function adace_get_slideup_slot_id() {
	return 'adace-slideup';
}

/**
* Register SldieUp Slot
*/
adace_register_ad_slot(
	array(
		'id'             => adace_get_slideup_slot_id(),
		'name'           => esc_html__( 'Slide Up', 'adace' ),
		'section'        => 'global',
		'custom_options' => array(
			'animate_slide_in'          => true,
			'animate_slide_in_editable' => true,
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_slideup_slot_option', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function adace_slideup_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_slideup_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['animate_slide_in'] = esc_html__( 'Enable Slide Animation', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_slideup_slot_option_renderer', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function adace_slideup_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_slideup_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$slide_in_editable = $args['slot']['custom_options']['animate_slide_in_editable'];
	if ( $slide_in_editable ) {
		$slide_in_current = isset( $slot_options['animate_slide_in'] ) ? $slot_options['animate_slide_in'] : $args['slot']['custom_options']['animate_slide_in'];
	} else {
		$slide_in_current = $args['slot']['custom_options']['animate_slide_in'];
	}

	if ( 'animate_slide_in' === $args['field_for'] ) :
	?>
	<input
		type="checkbox"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[animate_slide_in]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[animate_slide_in]"
		value="1"
		<?php checked( $slide_in_current ); ?>
		<?php echo( $slide_in_editable ? '' : ' disabled' ); ?>
	/>
	<?php
	endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_slideup_slot_option_save_validator', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function adace_slideup_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['animate_slide_in'] ) ) {
		$input_sanitized['animate_slide_in'] = filter_var( $input['animate_slide_in'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['animate_slide_in'] = false;
	}

	return $input_sanitized;
}

add_action( 'wp_footer', 'adace_slideup_slot_inject' );
/**
 * Inject Before Content Slot into content
 */
function adace_slideup_slot_inject() {
	if ( false === adace_is_ad_slot( adace_get_slideup_slot_id() ) ) { return ''; }
	$adace_ad_slots = adace_access_ad_slots();

	// Get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_slideup_slot_id() ];

	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_slideup_slot_id() . '_options' );

	$slide_in_animate_current = $slot_register['custom_options']['animate_slide_in_editable'] ? $slot_options['animate_slide_in'] : $slot_register['custom_options']['animate_slide_in'];

	$ad_slot = '<div class="adace-slideup-slot-wrap ' . ( $slide_in_animate_current ? 'animate-in' : '' ) . '">';
		$ad_slot .= '<div class="adace-slideup-slot">';
			$ad_slot .= adace_get_ad_slot( adace_get_slideup_slot_id() );
			if ( false !== $slot_options ) {
				$margin = $slot_options['margin'];
			} else {
				$margin = 0;
			}
			$ad_slot .= '<span class="adace-slideup-slot-closer"' . ( $margin > 0 ? 'style="margin-bottom:' . esc_attr( $margin ) . 'px;"' : '' ) . '><span class="closer-label">' . esc_html__( 'close', 'adace' ) . '</span></span>';
		$ad_slot .= '</div>';
	$ad_slot .= '</div>';

	echo( $ad_slot );
}
