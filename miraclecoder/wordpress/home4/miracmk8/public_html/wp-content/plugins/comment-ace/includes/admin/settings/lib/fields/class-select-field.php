<?php
/**
 * Select field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Select_Field
 *
 * Input attrs:
 * - choices:       array or callable
 * - empty_choice:  text
 */
class Select_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
	    $choices = ! empty( $this->data['choices'] ) ? $this->data['choices'] : array();
	    $empty_choice = ! empty( $this->data['empty_choice'] ) ? $this->data['empty_choice'] : '';

	    if ( is_callable( $choices ) ) {
	        $choices = call_user_func( $choices );
        }
        ?>
        <select name="<?php echo esc_attr( $this->get_name() ); ?>" id="<?php echo esc_attr( $this->get_id() ); ?>" class="<?php echo esc_attr( $this->get_classes() ); ?>">
            <?php if ( $empty_choice ): ?>

                <option value=""><?php echo esc_html( $empty_choice ); ?></option>

            <?php endif; ?>
            <?php foreach ( $choices as $value => $label ): ?>

                <option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, $this->get_value() ); ?>><?php echo esc_html( $label ); ?></option>

            <?php endforeach; ?>
        </select>
        <?php
        $this->render_description();
	}

}
