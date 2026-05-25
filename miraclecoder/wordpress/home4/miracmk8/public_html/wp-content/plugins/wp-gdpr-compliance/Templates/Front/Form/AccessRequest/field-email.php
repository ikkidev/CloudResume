<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $label
 * @var string $placeholder
 */

if ( empty( $placeholder ) ) {
	$placeholder = esc_attr( $label );
}

?>

<li class="wpgdprc-form__field">
	<label for="wpgdprc-form__email" class="screen-reader-text"><?php echo esc_html( $label ); ?></label>
	<input type="email" class="wpgdprc-form__input wpgdprc-form__input--email" name="<?php echo esc_attr( Plugin::PREFIX ); ?>_email" id="wpgdprc-form__email" placeholder="<?php echo esc_attr( $placeholder ); ?>" required />
</li>
