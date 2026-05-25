<?php
/**
 * Vignette
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
function adace_get_vignette_slot_id() {
	return 'adace_vignette';
}

/**
* Register Slot
*/
adace_register_ad_slot(
	array(
		'id'             => adace_get_vignette_slot_id(),
		'name'           => esc_html__( 'Full Screen Vignette', 'adace' ),
        'custom_options' => array(
            'close_label'           => '',
            'close_label_editable'  => true,
            'delay'                 => 10,
            'delay_editable'        => true,
            'skips'                 => 5,
            'skips_editable'        => true,
        ),
		'section'        => 'global',
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_vignette_slot_option', 10, 2 );

/**
 * Slot Custom Option
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot.
 * @return array
 */
function adace_vignette_slot_option( $slot_fields, $adace_ad_slot ) {
    if ( adace_get_vignette_slot_id() !== $adace_ad_slot['id'] ) {
        return $slot_fields;
    }
    $slot_fields['close_label'] = esc_html__( 'Close label', 'adace' );
    $slot_fields['delay']       = esc_html__( 'Automatically close after X seconds', 'adace' );
    $slot_fields['skips']       = esc_html__( 'Display after every X pages', 'adace' );

    return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_vignette_slot_option_renderer', 10, 2 );

/**
 * Slot Custom Option Renderer
 *
 * @param array $args Slot registered args.
 * @param array $slot_options Slot options saved.
 */
function adace_vignette_slot_option_renderer( $args, $slot_options ) {
    if ( adace_get_vignette_slot_id() !== $args['slot']['id'] ) {
        return;
    }

    $close_label_editable = $args['slot']['custom_options']['close_label_editable'];
    if ( $close_label_editable ) {
        $close_label_current = isset( $slot_options['close_label'] ) ? $slot_options['close_label'] : $args['slot']['custom_options']['close_label'];
    } else {
        $delay_current = $args['slot']['custom_options']['close_label'];
    }

    if ( 'close_label' === $args['field_for'] ) :
        ?>
        <p class="field-inside-input">
            <input
                    type="text"
                    id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[close_label]"
                    name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[close_label]"
                    value="<?php echo( esc_html( $close_label_current ) ); ?>"
                    placeholder="<?php echo esc_attr_x( 'Close', 'Full Screen Vignette Close Button Label', 'adace' ); ?>"
                <?php echo( $close_label_editable ? '' : ' disabled' ); ?>
            />
            <?php echo esc_html_x( 'Leave empty to use the default value', 'Full Screen Vignette Close Label', 'adace' ); ?>
        </p>
    <?php
    endif;

    $delay_editable = $args['slot']['custom_options']['delay_editable'];
    if ( $delay_editable ) {
        $delay_current = isset( $slot_options['delay'] ) ? $slot_options['delay'] : $args['slot']['custom_options']['delay'];
    } else {
        $delay_current = $args['slot']['custom_options']['delay'];
    }

    if ( 'delay' === $args['field_for'] ) :
        ?>
        <p class="field-inside-input">
            <input
                class="small-text"
                type="number"
                id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[delay]"
                name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[delay]"
                min="0"
                step="1"
                value="<?php echo( esc_html( $delay_current ) ); ?>"
                <?php echo( $delay_editable ? '' : ' disabled' ); ?>
            />
            <?php echo esc_html_x( 'Set to 0 to disable counting', 'Full Screen Vignette Counter', 'adace' ); ?>
        </p>
    <?php
    endif;

    $skips_editable = $args['slot']['custom_options']['skips_editable'];
    if ( $skips_editable ) {
        $skips_current = isset( $slot_options['skips'] ) ? $slot_options['skips'] : $args['slot']['custom_options']['skips'];
    } else {
        $skips_current = $args['slot']['custom_options']['skips'];
    }

    if ( 'skips' === $args['field_for'] ) :
        ?>
        <p class="field-inside-input">
            <input
                class="small-text"
                type="number"
                id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[skips]"
                name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[skips]"
                min="1"
                step="1"
                value="<?php echo( esc_html( $skips_current ) ); ?>"
                <?php echo( $skips_editable ? '' : ' disabled' ); ?>
            />
        </p>
    <?php
    endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_vignette_slot_option_save_validator', 10, 2 );
/**
 * Slot Custom Option Saver
 *
 * @param array $input_sanitized already sanitized options in savings.
 * @param array $input saving options input.
 */
function adace_vignette_slot_option_save_validator( $input_sanitized, $input ) {
    if ( isset( $input['close_label'] ) ) {
        $input_sanitized['close_label'] = filter_var( $input['close_label'], FILTER_SANITIZE_STRING );
    }

    if ( isset( $input['delay'] ) ) {
        $input_sanitized['delay'] = intval( filter_var( $input['delay'], FILTER_SANITIZE_NUMBER_INT ) );
    }

    if ( isset( $input['skips'] ) ) {
        $input_sanitized['skips'] = intval( filter_var( $input['skips'], FILTER_SANITIZE_NUMBER_INT ) );
    }

    return $input_sanitized;
}

add_action( 'wp_footer', 'adace_render_vignette_slot' );
/**
 * Inject Before Content Slot into content
 */
function adace_render_vignette_slot() {
    // Do not render in the Customize view.
    if ( is_customize_preview() ) {
        return;
    }

	if ( false === adace_is_ad_slot( adace_get_vignette_slot_id() ) ) {
		return;
	}

	adace_get_template_part( 'vignette' );
}