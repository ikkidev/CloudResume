<?php
/**
 * After Paragraph X
 *
 * @package AdAce
 * @subpackage Default Slots
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get After X Paragraph Slot Id
 *
 * @return string
 */
function adace_get_after_paragraph_slot_id() {
	return 'adace-after-paragraph';
}

$default_singular_options = array_keys( adace_get_supported_post_types() );
unset( $default_singular_options['page'] );
/**
 * Register After X Paragraph Slot
 */
adace_register_ad_slot(
	array(
		'id'             => adace_get_after_paragraph_slot_id(),
		'name'           => esc_html__( 'After X Paragraphs', 'adace' ),
		'custom_options' => array(
			'after_x_paragraph'          => 2,
			'after_x_paragraph_editable' => true,
			'to_end_of_post'             => false,
			'to_end_of_post_editable'    => true,
			'wrap_the_content'           => false,
			'wrap_the_content_editable'  => true,
		),
		'section' => 'content',
		'options' => array(
			'is_singular'          => array( $default_singular_options ),
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_after_paragraph_slot_option', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function adace_after_paragraph_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_after_paragraph_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['wrap_the_content'] = esc_html__( 'Wrap the text around the ad ', 'adace' );
	$slot_fields['after_x_paragraph'] = esc_html__( 'Show after X paragraphs', 'adace' );

	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_after_paragraph_slot_option_renderer', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function adace_after_paragraph_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_after_paragraph_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$after_x_paragraph_editable = $args['slot']['custom_options']['after_x_paragraph_editable'];
	if ( $after_x_paragraph_editable ) {
		$after_x_paragraph_current = isset( $slot_options['after_x_paragraph'] ) ? $slot_options['after_x_paragraph'] : $args['slot']['custom_options']['after_x_paragraph'];
	} else {
		$after_x_paragraph_current = $args['slot']['custom_options']['after_x_paragraph'];
	}

	$to_end_of_post_editable = $args['slot']['custom_options']['to_end_of_post_editable'];
	if ( $to_end_of_post_editable ) {
		$to_end_of_post_current = isset( $slot_options['to_end_of_post'] ) ? $slot_options['to_end_of_post'] : $args['slot']['custom_options']['to_end_of_post'];
	} else {
		$to_end_of_post_current = $args['slot']['custom_options']['to_end_of_post'];
	}

	if ( 'after_x_paragraph' === $args['field_for'] ) :
	?>
	<p class="field-inside-input">
	<input
		class="small-text"
		type="number"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_x_paragraph]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_x_paragraph]"
		min="1"
		max="10000"
		step="1"
		value="<?php echo( esc_html( $after_x_paragraph_current ) ); ?>"
		<?php echo( $after_x_paragraph_editable ? '' : ' disabled' ); ?>
	/>
	</p>
	<p class="field-inside-input">
	<input
		type="checkbox"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[to_end_of_post]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[to_end_of_post]"
		value="1"
		<?php checked( $to_end_of_post_current ); ?>
		<?php echo( $to_end_of_post_editable ? '' : ' disabled' ); ?>
	/>
	<label for="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[to_end_of_post]"><?php esc_html_e( 'to End of Post if fewer paragraphs are found. ', 'adace' ); ?></label>
	</p>
	<?php
	endif;

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

add_filter( 'adace_slots_options_save_validator_filter', 'adace_after_paragraph_slot_option_save_validator', 10, 2 );
/**
 * Add After X Paragraph Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function adace_after_paragraph_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['after_x_paragraph'] ) ) {
		$input_sanitized['after_x_paragraph'] = intval( filter_var( $input['after_x_paragraph'], FILTER_SANITIZE_NUMBER_INT ) );
	}
	if ( isset( $input['to_end_of_post'] ) ) {
		$input_sanitized['to_end_of_post'] = filter_var( $input['to_end_of_post'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['to_end_of_post'] = false;
	}
	if ( isset( $input['wrap_the_content'] ) ) {
		$input_sanitized['wrap_the_content'] = filter_var( $input['wrap_the_content'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['wrap_the_content'] = false;
	}
	return $input_sanitized;
}

add_filter( 'adace_pre_query_slot', 'adace_after_paragraph_add_wrap_to_query',10,2 );
/**
 * Add wrap to ad query
 *
 * @param array $result  Slot settings.
 * @param string $slot_id Slot id.
 * @return array
 */
function adace_after_paragraph_add_wrap_to_query( $result, $slot_id ) {
	if ( adace_get_after_paragraph_slot_id() !== $slot_id ) {
		return $result;
	}
	$adace_ad_slots = adace_access_ad_slots();
	$slot_register = $adace_ad_slots[ adace_get_after_paragraph_slot_id() ];
	$slot_options = get_option( 'adace_slot_' . adace_get_after_paragraph_slot_id() . '_options' );
	$result['wrap'] = $slot_register['custom_options']['wrap_the_content_editable'] && isset( $slot_options['wrap_the_content'] ) ? $slot_options['wrap_the_content'] : $slot_register['custom_options']['wrap_the_content'];
	return $result;
}

add_filter( 'the_content', 'adace_after_paragraph_slot_inject', 9999 );
/**
 * After X Paragraph Slot into content
 *
 * @param string $content Post content.
 * @return string
 */
function adace_after_paragraph_slot_inject( $content ) {
	if ( ! is_singular( ) ) {
		return $content;
	}
	if ( false === adace_is_ad_slot( adace_get_after_paragraph_slot_id() ) ) { return $content; }

	$adace_ad_slots = adace_access_ad_slots();

	// Get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_after_paragraph_slot_id() ];

	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_after_paragraph_slot_id() . '_options' );

	$inject_after = $slot_register['custom_options']['after_x_paragraph_editable'] ? $slot_options['after_x_paragraph'] : $slot_register['custom_options']['after_x_paragraph'];
	$to_end_of_post = $slot_register['custom_options']['to_end_of_post_editable'] ? $slot_options['to_end_of_post'] : $slot_register['custom_options']['to_end_of_post'];

	$paragraphs = explode( '</p>', $content );

	if ( count( $paragraphs ) >= $inject_after ) {
		foreach ( $paragraphs as $paragraph_index => $paragraph_content ) {
			if ( trim( $paragraph_content ) ) {
				$paragraphs[ $paragraph_index ] .= '</p>';
			}
			if ( $inject_after === $paragraph_index + 1 ) {
				$paragraphs[ $paragraph_index ] .= adace_get_ad_slot( adace_get_after_paragraph_slot_id() );
			}
		}
		return implode( '', $paragraphs );
	} else {
		if ( $to_end_of_post ) {
			$content .= adace_get_ad_slot( adace_get_after_paragraph_slot_id() );
		}
	}

	return $content;
}
