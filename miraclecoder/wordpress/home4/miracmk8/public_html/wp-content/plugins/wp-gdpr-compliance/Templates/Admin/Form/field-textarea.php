<?php

/**
 * @var string $name
 * @var string $value
 * @var string $class
 * @var string $attr
 */

use WPGDPRC\Utils\AdminForm;

if ( empty( $id ) ) {
	$id = sanitize_key( $name );
}

$classes = [ 'wpgdprc-form__input', 'wpgdprc-form__input--textarea', ! empty( $class ) ? $class : 'regular-text' ];
$class   = implode( ' ', $classes );

?>
<textarea id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $name ); ?>"
    <?php echo AdminForm::buildAttributes( $attr ); // PHPCS: XSS ok; ?> />
    <?php echo esc_html( $value ); ?>
</textarea>
