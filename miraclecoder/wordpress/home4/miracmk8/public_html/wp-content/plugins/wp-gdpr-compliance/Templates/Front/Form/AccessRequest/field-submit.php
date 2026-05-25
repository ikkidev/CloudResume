<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $label
 */

?>

<p class="wpgdprc-form__submit">
	<input type="submit" class="wpgdprc-form__input wpgdprc-form__input--submit" name="<?php echo esc_attr( Plugin::PREFIX ); ?>_submit" value="<?php echo esc_attr( $label ); ?>" />
</p>
