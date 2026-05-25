<?php
/**
 * Base field class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
use ParagonIE\Sodium\Core\Curve25519\H;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

abstract class Base_Field {

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Base_Field constructor
     *
     * @param string $id     Field identifier.
     * @param array  $data   Field configuration.
     */
    public function __construct(  $id, $data  ) {
        $this->id   = $id;
        $this->data = $data;
    }

    /**
     * Return field ID
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    public function get_classes() {
        return ! empty( $this->data['classes'] ) ? implode( ' ', array_map( 'sanitize_html_class', $this->data['classes'] ) ) : '';
    }

    /**
     * Return field type
     *
     * @return string
     */
    public function get_type() {
        return $this->data['type'];
    }

    /**
     * Return field name
     *
     * @return string
     */
    public function get_name() {
        return $this->id;
    }

    /**
     * Return field value
     *
     * @return mixed
     */
    public function get_value() {
        if ( ! $this->value ) {
            $this->value = get_option( $this->id, $this->get_default() );
        }

        return $this->value;
    }

    /**
     * Return field default value
     *
     * @return mixed
     */
    public function get_default() {
        return ! empty( $this->data['default'] ) ? $this->data['default'] : false;
    }

    public function get_description() {
        return ! empty( $this->data['description'] ) ? $this->data['description'] : '';
    }

    public function get_inline_description() {
        return ! empty( $this->data['inline_description'] ) ? $this->data['inline_description'] : '';
    }

    /**
     * Render field
     *
     * @return void
     */
    abstract public function render();

    /**
     * Render field description
     */
    public function render_description() {
        $description = $this->get_description();

        if ( ! empty( $description ) ) {
            ?>
            <p class="description">
                <?php echo $description; ?>
            </p>
            <?php
        }

        $inline_description = $this->get_inline_description();

        if ( ! empty( $inline_description ) ) {
            ?>
            <span class="description">
                <?php echo $inline_description; ?>
            </span>
            <?php
        }
    }

}
