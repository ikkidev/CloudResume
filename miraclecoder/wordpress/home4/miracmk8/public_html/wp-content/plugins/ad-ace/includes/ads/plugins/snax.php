<?php
/**
 * Snax Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get After x Snax Items Slot Id
 *
 * @return string Id for bbpress needs.
 */
function adace_get_after_snax_slot_id() {
	return 'adace-after-snax-items';
}

add_filter( 'adace_get_supported_post_types', 'adace_add_snax_post_type_support' );
/**
 * Add snax item to supported post types
 *
 * @param array $supported_post_types Supported post types.
 */
function adace_add_snax_post_type_support( $supported_post_types ) {
	$supported_post_types['snax_item'] = esc_html__( 'Snax Item', 'adace' );
	$supported_post_types['snax_quiz'] = esc_html__( 'Snax Quiz', 'adace' );
	$supported_post_types['snax_poll'] = esc_html__( 'Snax Poll', 'adace' );
	return $supported_post_types;
}

add_action( 'after_setup_theme', 'adace_add_snax_section', 20 );
/**
 * Add snax section
 */
function adace_add_snax_section() {
	adace_register_ad_section( 'snax', __( 'Snax', 'adace' ) );
}


/**
* Register After X Snax Items Slot
*/
adace_register_ad_slot(
	array(
		'id' => adace_get_after_snax_slot_id(),
		'name' => esc_html__( 'After X Snax Item\'s', 'adace' ),
		'section' => 'snax',
		'custom_options' => array(
			'after_x_snax' => 2,
			'after_x_snax_editable' => true,
			'repeat_after_x_snax' => 0,
			'repeat_after_x_snax_editable' => true,
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_after_snax_slot_option', 10, 2 );
/**
 * Add option for After X Snax Items Slot
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot ad.
 */
function adace_after_snax_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_after_snax_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['after_x_snax'] = esc_html__( 'Start after X snax items', 'adace' );
	$slot_fields['repeat_after_x_snax'] = esc_html__( 'Repeat after every X items', 'adace' );

	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_after_snax_slot_option_renderer', 10, 2 );
/**
 * Add renderer for After X Snax Items Slot
 *
 * @param array $args Slot args.
 * @param array $slot_options Slot options.
 */
function adace_after_snax_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_after_snax_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$after_x_snax_editable = $args['slot']['custom_options']['after_x_snax_editable'];
	if ( $after_x_snax_editable ) {
		$after_x_snax_current = isset( $slot_options['after_x_snax'] ) ? $slot_options['after_x_snax'] : $args['slot']['custom_options']['after_x_snax'];
	} else {
		$after_x_snax_current = $args['slot']['custom_options']['after_x_snax'];
	}

	$repeat_after_x_snax_editable = $args['slot']['custom_options']['repeat_after_x_snax_editable'];
	if ( $repeat_after_x_snax_editable ) {
		$repeat_after_x_snax_current = isset( $slot_options['repeat_after_x_snax'] ) ? $slot_options['repeat_after_x_snax'] : $args['slot']['custom_options']['repeat_after_x_snax'];
	} else {
		$repeat_after_x_snax_current = $args['slot']['custom_options']['repeat_after_x_snax'];
	}

	if ( 'after_x_snax' === $args['field_for'] ) :
	?>
	<input
		class="small-text"
		type="number"
		id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_snax]"
		name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_snax]"
		min="1"
		max="10000"
		step="1"
		value="<?php echo( esc_html( $after_x_snax_current ) ); ?>"
		<?php echo( $after_x_snax_editable ? '' : ' disabled' );  ?>
	/>
	<label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_snax]"></label>
	<?php
	endif;

	if ( 'repeat_after_x_snax' === $args['field_for'] ) :
		?>
		<input
			class="small-text"
			type="number"
			id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_snax]"
			name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_snax]"
			min="0"
			max="10000"
			step="1"
			value="<?php echo( esc_html( $repeat_after_x_snax_current ) ); ?>"
			<?php echo( $repeat_after_x_snax_editable ? '' : ' disabled' );  ?>
			/>
		<label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_snax]"><?php esc_html_e( '0 to not repeat', 'adace' ); ?></label>
		<?php
	endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_after_snax_slot_option_save_validator', 10, 2 );
/**
 * Add option saving validator for After X Snax Items slot
 *
 * @param array $input_sanitized Sanitized.
 * @param array $input Original.
 */
function adace_after_snax_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['after_x_snax'] ) ) {
		$input_sanitized['after_x_snax'] = intval( filter_var( $input['after_x_snax'], FILTER_SANITIZE_NUMBER_INT ) );
	}

	if ( isset( $input['repeat_after_x_snax'] ) ) {
		$input_sanitized['repeat_after_x_snax'] = intval( filter_var( $input['repeat_after_x_snax'], FILTER_SANITIZE_NUMBER_INT ) );
	}

	return $input_sanitized;
}

add_action( 'snax_after_item', 'adace_afeter_snax_slot_inject', 10, 2 );
add_action( 'snax_after_poll_question', 'adace_afeter_snax_slot_inject', 10, 2 );
add_action( 'snax_after_quiz_question', 'adace_afeter_snax_slot_inject', 10, 2 );
/**
 * Add option saving validator for After X Snax Items slot
 *
 * @param object $snax_item snax item post.
 * @param int    $snax_index counter in loop.
 */
function adace_afeter_snax_slot_inject( $snax_item, $snax_index ) {
	if ( false === adace_is_ad_slot( adace_get_after_snax_slot_id() ) ) { return; }

	$adace_ad_slots = adace_access_ad_slots();
	// get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_after_snax_slot_id() ];
	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_after_snax_slot_id() . '_options' );

	$inject_after = $slot_register['custom_options']['after_x_snax_editable'] ? $slot_options['after_x_snax'] : $slot_register['custom_options']['after_x_snax'];
	$repeat_after = $slot_register['custom_options']['repeat_after_x_snax_editable'] && isset( $slot_options['repeat_after_x_snax'] ) ? $slot_options['repeat_after_x_snax'] : $slot_register['custom_options']['repeat_after_x_snax'];
	$query_offset = 0;

	if ( isset( snax()->items_query ) && is_array( snax()->items_query->query ) ) {
		$query_offset = (int) snax()->items_query->query['offset'];
	}

	$current_post_number = $query_offset + ($snax_index + 1); // +1, index starts from 0

    $render_first_slot = $current_post_number === $inject_after;
    $render_repeated_slot =
        $repeat_after > 0 &&                                                    // repetition enabled
        $current_post_number > $inject_after &&                                 // the first ad rendered
        ( 0 === ( $current_post_number - $inject_after ) % $repeat_after );     // next ad slot, taking into account the first ad offset, has been reached

    $render_slot = $render_first_slot || $render_repeated_slot;

	if ( $render_slot ) {
		echo adace_get_ad_slot( adace_get_after_snax_slot_id() );
	}
}

add_filter( 'adace_disable_ads_per_post', 'adace_snax_disable_ads_in_quiz_result', 10, 2 );

/**
 * Disable ads in quiz result
 *
 * @param  bool   $disable  Whether to disable ad.
 * @param  string $slot_id  Slot id.
 * @return bool
 * */
function adace_snax_disable_ads_in_quiz_result( $disable, $slot_id ) {
	if ( isset( $_REQUEST['action'] ) && 'snax_load_quiz_result' === $_REQUEST['action'] ) {
		return true;
	} else {
		return $disable;
	}
}

add_filter( 'adace_disable_ads_per_post', 'adace_snax_disable_ads_in_front_submission', 10, 2 );

/**
 * Disable ads in quiz result
 *
 * @param  bool   $disable  Whether to disable ad.
 * @param  string $slot_id  Slot id.
 * @return bool
 * */
function adace_snax_disable_ads_in_front_submission( $disable, $slot_id ) {
	$excluded_slots = array(
		adace_get_after_paragraph_slot_id(),
		adace_get_after_paragraph_2_slot_id(),
		adace_get_after_paragraph_3_slot_id(),
		adace_get_after_content_slot_id(),
		adace_get_middle_content_slot_id(),
		adace_get_before_content_slot_id(),
		adace_get_before_last_paragraph_slot_id()
	);

    $frontend_submission_page_id = snax_get_frontend_submission_page_id();

	if ( $frontend_submission_page_id > 0 && is_page( $frontend_submission_page_id ) ) {
		if ( in_array( $slot_id, $excluded_slots, true ) ) {
			return true;
		}
	}
	return $disable;
}
