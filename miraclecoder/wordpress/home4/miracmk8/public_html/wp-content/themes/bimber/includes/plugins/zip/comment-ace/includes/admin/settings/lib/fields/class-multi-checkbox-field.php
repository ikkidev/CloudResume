<?php
/**
 * Checkbox field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Checkbox_Field
 */
class Multi_Checkbox_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
        $choices = ! empty( $this->data['choices'] ) ? $this->data['choices'] : array();

        if ( is_callable( $choices ) ) {
            $choices = call_user_func( $choices );
        }

        $value = $this->get_value();

        foreach ( $choices as $choice ) {
            $field_name = sprintf( '%s[]', $this->get_name() );
            $field_id = sprintf( '%s_%s', $this->get_name(), $choice );
            $checked = ! empty( $value ) && in_array( $choice, $value );
            ?>
            <div>
                <label>
                <input type="checkbox"
                       name="<?php echo esc_attr( $field_name ); ?>"
                       id="<?php echo esc_attr( $field_id ); ?>"
                       class="<?php echo esc_attr( $this->get_classes() ); ?>"
                       value="<?php echo esc_attr( $choice ); ?>"
                    <?php checked( $checked  ) ?>
                />
                <?php echo esc_html( $choice ); ?>
                </label>
            </div>
            <?php
        }



        $this->render_description();
	}

}
