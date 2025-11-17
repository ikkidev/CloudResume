<?php
/**
 * Number input field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Number_Field
 *
 * Input attrs:
 * - placeholder: string
 */
class Number_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
	    $min  = isset( $this->data['min'] ) ? $this->data['min'] : false;
	    $max  = isset( $this->data['max'] ) ? $this->data['max'] : false;
	    $step = isset( $this->data['step'] ) ? $this->data['step'] : 1;
        $placeholder_text = ! empty( $this->data['placeholder'] ) ? $this->data['placeholder'] : '';

        ?>
        <input type="number"
               name="<?php echo esc_attr( $this->get_name() ); ?>"
               id="<?php echo esc_attr( $this->get_id() ); ?>"
               class="<?php echo esc_attr( $this->get_classes() ); ?>"
               value="<?php echo esc_attr( $this->get_value() ); ?>"
               placeholder="<?php echo esc_attr( $placeholder_text ); ?>"
               <?php if ( $min ) printf( ' min="%d"', absint( $min ));  ?>
               <?php if ( $max ) printf( ' max="%d"', absint( $max ));  ?>
               <?php if ( $step ) printf( ' step="%d"', absint( $step ));  ?>
        />
        <?php
        $this->render_description();
	}

}
