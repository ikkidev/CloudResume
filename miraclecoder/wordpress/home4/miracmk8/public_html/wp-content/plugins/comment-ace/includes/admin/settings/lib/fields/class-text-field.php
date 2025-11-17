<?php
/**
 * Text input field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Text_Field
 *
 * Input attrs:
 * - placeholder: string
 */
class Text_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
	    $placeholder_text = ! empty( $this->data['placeholder'] ) ? $this->data['placeholder'] : '';

        ?>
        <input type="text"
               name="<?php echo esc_attr( $this->get_name() ); ?>"
               id="<?php echo esc_attr( $this->get_id() ); ?>"
               class="<?php echo esc_attr( $this->get_classes() ); ?>"
               value="<?php echo esc_attr( $this->get_value() ); ?>"
               placeholder="<?php echo esc_attr( $placeholder_text ); ?>"
        />
        <?php
        $this->render_description();
	}

}
