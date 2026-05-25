<?php

/**
 * @var string $type
 * @var string $name
 * @var string $value
 * @var string $class
 * @var string $attr
 */

use WPGDPRC\Utils\AdminForm;

if ( empty( $id ) ) {
	$id = sanitize_key( $name );
}
if ( empty( $type ) ) {
	$type = 'text';
}
?>

<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"
    <?php echo AdminForm::buildAttributes( $attr ); // PHPCS: XSS ok; ?> />

<?php if ( $type === 'color' ) : ?>
	<input type="text" id="<?php echo esc_attr( $id ) . '-text'; ?>" class="<?php echo esc_attr( $class ) . '_text'; ?>" name="<?php echo esc_attr( $name ) . '_text'; ?>" value="<?php echo esc_attr( $value ); ?>"
        <?php echo AdminForm::buildAttributes( $attr ); // PHPCS: XSS ok; ?> />
<?php endif; ?>
