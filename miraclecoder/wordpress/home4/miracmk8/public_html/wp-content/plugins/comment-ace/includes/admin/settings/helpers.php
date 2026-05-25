<?php
/**
 * Settings configuration
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Sanitize array text values (1 level deep only)
 *
 * @param array $input_array        Input.
 *
 * @return array                    Output.
 */
function sanitize_text_array( $input_array ) {
    if ( ! is_array( $input_array ) ) {
        return array();
    }

    foreach ( $input_array as $key => $value ) {
        if ( is_array( $value ) ) {
            $input_array[ $key ] = array_map( 'sanitize_text_field', $input_array );
        } else {
            $input_array[ $key ] = sanitize_text_field( $value );
        }
    }

    return $input_array;
}

/**
 * Render info about the sorting activity
 *
 * @param string $sorting       Sortign type.
 *
 * @return string               Empty is type is active, message is not.
 */
function get_sorting_activity_info( $sorting ) {
    if ( ! is_voting_enabled() && in_array( $sorting, array( 'top', 'most_voted' ) ) ) {
        return ' ('. _x( 'inactive, enable Voting to activate', 'Sorting activity info', 'cace' ) . ')';
    }

    return '';
}

/**
 *
 *
 * @param string $field_id          Field ID.
 * @param array  $field             Field data.
 */
function sort_types_setting_renderer( $field_id, $field ) {
    $value         = get_option( $field_id );
    $all_types     = get_all_sort_types();
    $active_types  = get_active_sort_types();
    ?>
    <ul>
        <?php foreach ( $all_types as $type_id => $type_default_label ): ?>
        <?php
            $label = '';
            $placeholder = $all_types[ $type_id ]['label'];

            // Value stored in db.
            if ( ! empty( $value ) ) {
                $checked = isset( $value[ $type_id ]['enabled'] ) && 'standard' === $value[ $type_id ]['enabled'];

                if ( ! empty( $value[ $type_id ]['label'] ) ) {
                    $label = $value[ $type_id ]['label'];
                }
            } else {
                $checked = 'standard' === $all_types[ $type_id ]['enabled'];
            }

            $disabled = ! isset( $active_types[ $type_id ] );

            if ( $disabled ) {
                $checked = false;
            }
        ?>
        <li>
            <input type="checkbox"
                   name="<?php echo esc_attr( sprintf( '%s[%s][%s]', $field_id, $type_id, 'enabled' ) ); ?>"
                   id="<?php echo esc_attr( sprintf( '%s_%s_%s', $field_id, $type_id, 'enabled' ) ); ?>"
                   value="standard"
                   <?php checked( $checked ) ?>
                   <?php disabled( $disabled ) ?>
            />
            <input type="text"
                   name="<?php echo esc_attr( sprintf( '%s[%s][%s]', $field_id, $type_id, 'label' ) ); ?>"
                   id="<?php echo esc_attr( sprintf( '%s_%s_%s', $field_id, $type_id, 'label' ) ); ?>"
                   value="<?php echo esc_attr( $label ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                    <?php disabled( $disabled ) ?>
            />
            <?php echo esc_html( get_sorting_activity_info( $type_id ) ); ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
}

function get_allowed_post_types() {
    $hidden_post_types = array(
        'page',
        'attachment',
        'product',
        'snax_collection',
        'snax_meme_template',
        'forum',
        'topic',
        'reply',
        'elementor_library',
    );

    $post_types = get_post_types( array( 'public' => true ) );

    $post_types = array_diff( $post_types, $hidden_post_types );

    return apply_filters( 'cace_allowed_post_types', $post_types );
}

