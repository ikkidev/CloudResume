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
class Checkbox_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
	    $checked_value = isset( $this->data['value'] ) ? $this->data['value'] : 'standard';
        ?>
        <input type="checkbox"
               name="<?php echo esc_attr( $this->get_name() ); ?>"
               id="<?php echo esc_attr( $this->get_id() ); ?>"
               class="<?php echo esc_attr( $this->get_classes() ); ?>"
               value="<?php echo esc_attr( $checked_value ); ?>"
               <?php checked( $checked_value, $this->get_value() ) ?>
        />
        <?php
        $this->render_description();
	}

}
