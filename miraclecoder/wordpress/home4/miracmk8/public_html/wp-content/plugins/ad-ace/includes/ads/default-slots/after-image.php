<?php
/**
 * After Image X
 *
 * @package AdAce
 * @subpackage Default Slots
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get After X images Slot Id
 *
 * @return string
 */
function adace_get_after_image_slot_id() {
	return 'adace-after-image';
}

$default_singular_options = array_keys( adace_get_supported_post_types() );
unset( $default_singular_options['page'] );
/**
 * Register After X images Slot
 */
adace_register_ad_slot(
	array(
		'id' => adace_get_after_image_slot_id(),
		'name' => esc_html__( 'After X Images', 'adace' ),
		'section' => 'content',
		'custom_options' => array(
			'after_x_image' => 2,
			'after_x_image_editable' => true,
			'after_div' => false,
			'after_div_editable' => true,
			'wrap_the_content' => false,
			'wrap_the_content_editable' => true,
		),
		'options' => array(
			'is_singular'          => array( $default_singular_options ),
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_after_image_slot_option', 10, 2 );
/**
 * Add After X images Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function adace_after_image_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_after_image_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['wrap_the_content'] = esc_html__( 'Wrap the text around the ad ', 'adace' );
	$slot_fields['after_x_image'] = esc_html__( 'Show after X images', 'adace' );
	$slot_fields['after_div'] = esc_html__( 'show after images wp-caption wrapper. ', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_after_image_slot_option_renderer', 10, 2 );
/**
 * Add After X images Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function adace_after_image_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_after_image_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$after_x_image_editable = $args['slot']['custom_options']['after_x_image_editable'];
	if ( $after_x_image_editable ) {
		$after_x_image_current = isset( $slot_options['after_x_image'] ) ? $slot_options['after_x_image'] : $args['slot']['custom_options']['after_x_image'];
	} else {
		$after_x_image_current = $args['slot']['custom_options']['after_x_image'];
	}

	if ( 'after_x_image' === $args['field_for'] ) :
	?>
	<input
		class="small-text"
		type="number"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_x_image]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_x_image]"
		min="1"
		max="10000"
		step="1"
		value="<?php echo( esc_html( $after_x_image_current ) ); ?>"
		<?php echo( $after_x_image_editable ? '' : ' disabled' );  ?>
	/>
	<label for="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_x_image]"><?php esc_html_e( 'Type here after how many images display slot.', 'adace' ); ?></label>
	<?php
	endif;

	$after_div_editable = $args['slot']['custom_options']['after_div_editable'];
	if ( $after_div_editable ) {
		$after_div_current = isset( $slot_options['after_div'] ) ? $slot_options['after_div'] : $args['slot']['custom_options']['after_div'];
	} else {
		$after_div_current = $args['slot']['custom_options']['after_div'];
	}

	if ( 'after_div' === $args['field_for'] ) :
	?>
	<input
		type="checkbox"
		id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_div]"
		name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[after_div]"
		value="1"
		<?php checked( $after_div_current ); ?>
		<?php echo( $after_div_editable ? '' : ' disabled' );  ?>
	/>
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

add_filter( 'adace_slots_options_save_validator_filter', 'adace_after_image_slot_option_save_validator', 10, 2 );
/**
 * Add After X images Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function adace_after_image_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['after_x_image'] ) ) {
		$input_sanitized['after_x_image'] = intval( filter_var( $input['after_x_image'], FILTER_SANITIZE_NUMBER_INT ) );
	}
	if ( isset( $input['after_div'] ) ) {
		$input_sanitized['after_div'] = filter_var( $input['after_div'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['after_div'] = false;
	}
	if ( isset( $input['wrap_the_content'] ) ) {
		$input_sanitized['wrap_the_content'] = filter_var( $input['wrap_the_content'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['wrap_the_content'] = false;
	}

	return $input_sanitized;
}

add_filter( 'adace_pre_query_slot', 'adace_after_image_add_wrap_to_query',10,2 );
/**
 * Add wrap to ad query
 *
 * @param array $result  Slot settings.
 * @param string $slot_id Slot id.
 * @return array
 */
function adace_after_image_add_wrap_to_query( $result, $slot_id ) {
	if ( adace_get_after_image_slot_id() !== $slot_id ) {
		return $result;
	}
	$adace_ad_slots = adace_access_ad_slots();
	$slot_register = $adace_ad_slots[ adace_get_after_image_slot_id() ];
	$slot_options = get_option( 'adace_slot_' . adace_get_after_image_slot_id() . '_options' );
	$result['wrap'] = $slot_register['custom_options']['wrap_the_content_editable'] && isset( $slot_options['wrap_the_content'] ) ? $slot_options['wrap_the_content'] : $slot_register['custom_options']['wrap_the_content'];
	return $result;
}

// High priority, so all other ads are injected later.
add_filter( 'the_content', 'adace_after_image_slot_inject', 1, 9999 );
/**
 * After X images Slot into content
 *
 * @param string $content Post content.
 * @return string
 */
function adace_after_image_slot_inject( $content ) {
	if ( ! is_singular( ) ) {
		return $content;
	}
	if ( false === adace_is_ad_slot( adace_get_after_image_slot_id() ) ) { return $content; }

	$adace_ad_slots = adace_access_ad_slots();

	// Get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_after_image_slot_id() ];

	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_after_image_slot_id() . '_options' );

	$inject_after = $slot_register['custom_options']['after_x_image_editable'] ? $slot_options['after_x_image'] : $slot_register['custom_options']['after_x_image'];
	$after_div = $slot_register['custom_options']['after_div_editable'] ? $slot_options['after_div'] : $slot_register['custom_options']['after_div'];

	$unique = adace_preg_make_unique( '/<img.*>/U', $content );
	$content = $unique['string'];
	preg_match_all( '/<!--UNIQUEMATCH.*-->/U', $content, $images );

	if ( count( $images[0] ) >= $inject_after) {
		$image = $images[0][ $inject_after - 1 ];
		$search = $image;

		if ( $after_div ) {
			preg_match_all( '/\[caption.*\[\/caption\]/U', $content, $captions );
			foreach ( $captions[0] as $caption ) {
				if ( strpos( $caption, $image ) ) {
					$search = $caption;
				}
			}
		}

		$new_image = $search . adace_get_ad_slot( adace_get_after_image_slot_id() );
		$content = adace_str_replace_first( $search, $new_image, $content );
	}

	$unique['string'] = $content;
	$content = adace_preg_make_unique_revert( $unique );
	return $content;
}
