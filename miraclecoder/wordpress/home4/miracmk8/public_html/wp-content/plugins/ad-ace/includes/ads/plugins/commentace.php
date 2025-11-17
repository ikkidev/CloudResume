<?php
/**
 * CommentAce Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

add_action( 'after_setup_theme',                            'adace_add_cace_section', 20 );
add_filter( 'adace_options_slot_fields_filter',             'adace_after_cace_slot_option', 10, 2 );
add_action( 'adace_options_slots_field_renderer_action',    'adace_after_cace_slot_option_renderer', 10, 2 );
add_filter( 'adace_slots_options_save_validator_filter',    'adace_after_cace_slot_option_save_validator', 10, 2 );
add_action( 'cace_after_comment',                           'adace_after_cace_slot_inject', 10, 2 );

/**
 * Get After X Comments Slot Id
 *
 * @return string
 */
function adace_get_after_cace_comment_slot_id() {
    return 'adace-after-cace-comments';
}

/**
 * Add section
 */
function adace_add_cace_section() {
    adace_register_ad_section( 'cace', 'CommentAce' );
}

/**
 * Register slot
 */
adace_register_ad_slot(
    array(
        'id' => adace_get_after_cace_comment_slot_id(),
        'name' => esc_html__( 'After X Comments', 'adace' ),
        'section' => 'cace',
        'custom_options' => array(
            'after_x_cace' => 2,
            'after_x_cace_editable' => true,
            'repeat_after_x_cace' => 3,
            'repeat_after_x_cace_editable' => true,
        ),
    )
);

/**
 * Add slot options
 *
 * @param array $slot_fields        Slot fields.
 * @param array $adace_ad_slot      Slot ad.
 *
 * @return array
 */
function adace_after_cace_slot_option( $slot_fields, $adace_ad_slot ) {
    if ( adace_get_after_cace_comment_slot_id() !== $adace_ad_slot['id'] ) {
        return $slot_fields;
    }

    $slot_fields['after_x_cace'] = esc_html__( 'Start after X comments', 'adace' );
    $slot_fields['repeat_after_x_cace'] = esc_html__( 'Repeat after every X comments', 'adace' );

    return $slot_fields;
}

/**
 * Add renderer for slot
 *
 * @param array $args Slot args.
 * @param array $slot_options Slot options.
 */
function adace_after_cace_slot_option_renderer( $args, $slot_options ) {
    if ( adace_get_after_cace_comment_slot_id() !== $args['slot']['id'] ) {
        return;
    }

    $after_x_cace_editable = $args['slot']['custom_options']['after_x_cace_editable'];
    if ( $after_x_cace_editable ) {
        $after_x_cace_current = isset( $slot_options['after_x_cace'] ) ? $slot_options['after_x_cace'] : $args['slot']['custom_options']['after_x_cace'];
    } else {
        $after_x_cace_current = $args['slot']['custom_options']['after_x_cace'];
    }

    $repeat_after_x_cace_editable = $args['slot']['custom_options']['repeat_after_x_cace_editable'];
    if ( $repeat_after_x_cace_editable ) {
        $repeat_after_x_cace_current = isset( $slot_options['repeat_after_x_cace'] ) ? $slot_options['repeat_after_x_cace'] : $args['slot']['custom_options']['repeat_after_x_cace'];
    } else {
        $repeat_after_x_cace_current = $args['slot']['custom_options']['repeat_after_x_cace'];
    }

    if ( 'after_x_cace' === $args['field_for'] ) :
        ?>
        <input
                class="small-text"
                type="number"
                id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_cace]"
                name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_cace]"
                min="1"
                max="10000"
                step="1"
                value="<?php echo( esc_html( $after_x_cace_current ) ); ?>"
            <?php echo( $after_x_cace_editable ? '' : ' disabled' );  ?>
        />
        <label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[after_x_cace]"></label>
    <?php
    endif;

    if ( 'repeat_after_x_cace' === $args['field_for'] ) :
        ?>
        <input
                class="small-text"
                type="number"
                id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_cace]"
                name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_cace]"
                min="0"
                max="10000"
                step="1"
                value="<?php echo( esc_html( $repeat_after_x_cace_current ) ); ?>"
            <?php echo( $repeat_after_x_cace_editable ? '' : ' disabled' );  ?>
        />
        <label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[repeat_after_x_cace]"><?php esc_html_e( '0 to not repeat', 'adace' ); ?></label>
    <?php
    endif;
}

/**
 * Add option saving validator for slot
 *
 * @param array $input_sanitized Sanitized.
 * @param array $input Original.
 *
 * @return array
 */
function adace_after_cace_slot_option_save_validator( $input_sanitized, $input ) {
    if ( isset( $input['after_x_cace'] ) ) {
        $input_sanitized['after_x_cace'] = intval( filter_var( $input['after_x_cace'], FILTER_SANITIZE_NUMBER_INT ) );
    }

    if ( isset( $input['repeat_after_x_cace'] ) ) {
        $input_sanitized['repeat_after_x_cace'] = intval( filter_var( $input['repeat_after_x_cace'], FILTER_SANITIZE_NUMBER_INT ) );
    }

    return $input_sanitized;
}

/**
 * Add option saving validator for slot
 *
 * @param object $cace_item cace item post.
 * @param int    $cace_index counter in loop.
 */
function adace_after_cace_slot_inject( $cace_comment, $cace_index ) {
    if ( false === adace_is_ad_slot( adace_get_after_cace_comment_slot_id() ) ) { return; }

    $adace_ad_slots = adace_access_ad_slots();
    // get slot register data.
    $slot_register = $adace_ad_slots[ adace_get_after_cace_comment_slot_id() ];
    // Get slot options.
    $slot_options = get_option( 'adace_slot_' . adace_get_after_cace_comment_slot_id() . '_options' );

    $inject_after = $slot_register['custom_options']['after_x_cace_editable'] ? $slot_options['after_x_cace'] : $slot_register['custom_options']['after_x_cace'];
    $repeat_after = $slot_register['custom_options']['repeat_after_x_cace_editable'] && isset( $slot_options['repeat_after_x_cace'] ) ? $slot_options['repeat_after_x_cace'] : $slot_register['custom_options']['repeat_after_x_cace'];

    // Comments broken into page?
    $query_offset = (int) filter_input( INPUT_GET, 'cace-offset', FILTER_SANITIZE_NUMBER_INT );

    $current_post_number = $query_offset + $cace_index;

    $render_first_slot = $current_post_number === $inject_after;
    $render_repeated_slot =
        $repeat_after > 0 &&                                                    // repetition enabled
        $current_post_number >= $inject_after + $repeat_after &&                                 // the first ad rendered
        ( 0 === ( $current_post_number - $inject_after ) % $repeat_after );     // next ad slot, taking into account the first ad offset, has been reached

    $render_slot = $render_first_slot || $render_repeated_slot;

    if ( $render_slot ) {
        echo adace_get_ad_slot( adace_get_after_cace_comment_slot_id() );
    }
}
