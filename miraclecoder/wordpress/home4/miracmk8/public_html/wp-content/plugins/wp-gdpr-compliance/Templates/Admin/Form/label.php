<?php

/**
 * @var string $id
 * @var string $text
 * @var bool $sr_only
 * @var string $info
 */

$classes = [ 'wpgdprc-form__label' ];
if ( ! empty( $sr_only ) ) {
	$classes[] = 'screen-reader-text';
}
$class = implode( ' ', $classes );

?>

<label for="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
	<?php echo esc_html( $text ); ?>
	<?php if ( ! empty( $info ) ) : ?>
		<span title="<?php echo esc_attr( $info ); ?>" class="wpgdprc-label__info">i</span>
	<?php endif; ?>
</label>
