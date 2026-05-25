<?php
/**
 * Middle Content
 *
 * @package AdAce
 * @subpackage Default Slots
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get before_last X Paragraph Slot Id
 *
 * @return string
 */
function adace_get_middle_content_slot_id() {
	return 'adace-middle-content';
}

$default_singular_options = array_keys( adace_get_supported_post_types() );
unset( $default_singular_options['page'] );
/**
 * Register before_last X Paragraph Slot
 */
adace_register_ad_slot(
	array(
		'id' => adace_get_middle_content_slot_id(),
		'name' => esc_html__( 'In the middle of the content', 'adace' ),
		'section' => 'content',
		'custom_options' => array(
			'wrap_the_content' => false,
			'wrap_the_content_editable' => true,
		),
		'options' => array(
			'is_singular'          => array( $default_singular_options ),
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_middle_content_slot_option', 10, 2 );
/**
 * Add Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function adace_middle_content_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_middle_content_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['wrap_the_content'] = esc_html__( 'Wrap the text around the ad ', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_middle_content_slot_option_renderer', 10, 2 );
/**
 * Add Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function adace_middle_content_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_middle_content_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$wrap_the_content_editable = $args['slot']['custom_options']['wrap_the_content_editable'];
	if ( $wrap_the_content_editable ) {
		$wrap_the_content_current = isset( $slot_options['wrap_the_content'] ) ? $slot_options['wrap_the_content'] : $args['slot']['custom_options']['wrap_the_content'];
	} else {
		$wrap_the_content_current = $args['slot']['custom_options']['wrap_the_content'];
	}

	if ( 'wrap_the_content' === $args['field_for'] ) :
	?>
	<input
		type="checkbox"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[wrap_the_content]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[wrap_the_content]"
		value="1"
		<?php checked( $wrap_the_content_current ); ?>
		<?php echo( $wrap_the_content_editable ? '' : ' disabled' );  ?>
	/>
	<?php
	endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_middle_content_slot_option_save_validator', 10, 2 );
/**
 * Add Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function adace_middle_content_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['wrap_the_content'] ) ) {
		$input_sanitized['wrap_the_content'] = filter_var( $input['wrap_the_content'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['wrap_the_content'] = false;
	}
	return $input_sanitized;
}

add_filter( 'adace_pre_query_slot', 'adace_middle_content_add_wrap_to_query',10,2 );
/**
 * Add wrap to ad query
 *
 * @param array $result  Slot settings.
 * @param string $slot_id Slot id.
 * @return array
 */
function adace_middle_content_add_wrap_to_query( $result, $slot_id ) {
	if ( adace_get_middle_content_slot_id() !== $slot_id ) {
		return $result;
	}
	$adace_ad_slots = adace_access_ad_slots();
	$slot_register = $adace_ad_slots[ adace_get_middle_content_slot_id() ];
	$slot_options = get_option( 'adace_slot_' . adace_get_middle_content_slot_id() . '_options' );
	$result['wrap'] = $slot_register['custom_options']['wrap_the_content_editable'] && isset( $slot_options['wrap_the_content'] ) ? $slot_options['wrap_the_content'] : $slot_register['custom_options']['wrap_the_content'];
	return $result;
}

add_filter( 'the_content', 'adace_middle_content_slot_inject', 9999 );
/**
 * Middle Content Slot into content
 *
 * @param string $content Post content.
 * @return string
 */
function adace_middle_content_slot_inject( $content ) {
	if ( ! is_singular( ) ) {
		return $content;
	}
	if ( false === adace_is_ad_slot( adace_get_middle_content_slot_id() ) ) { return $content; }
	$unique = adace_preg_make_unique( '/<p.*p>/', $content );
	$content = $unique['string'];
	preg_match_all( "/<!--UNIQUEMATCH.*-->/U", $content, $paragraphs );
	$count = count( $paragraphs[0] );
	if ( $count > 1 ) {
		$middle_pos = floor( $count / 2 );
		$middle_p = $paragraphs[0][ $middle_pos ];
		$new_middle_p = $middle_p . adace_get_ad_slot( adace_get_middle_content_slot_id() );
		$middle_p = '/' . preg_quote( $middle_p, '/' ) . '/';
		$content = preg_replace( $middle_p, $new_middle_p, $content,1 );
	}
	$unique['string'] = $content;
	$content = adace_preg_make_unique_revert( $unique );
	return $content;
}
