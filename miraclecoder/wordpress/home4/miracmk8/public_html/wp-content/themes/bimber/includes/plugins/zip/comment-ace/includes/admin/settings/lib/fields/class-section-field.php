<?php
/**
 * Section field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Section_Field
 *
 * Input attrs:
 * - placeholder: string
 */
class Section_Field extends Base_Field {

    /**
     * Render field
     */
	public function render() {
	    if ( empty( $this->data['content'] ) ) {
	        return;
        }

	    ?>
        </td>
        </tr>
        <tr>
            <td colspan="2"><?php echo wp_kses_post( $this->data['content'] ); ?>
        <?php
    }

}
