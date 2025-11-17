<?php

/**
 * @var string $text
 */

if ( empty( $text ) ) {
	return;
}

?>

<p class="wpgdprc-form__description"><?php echo wp_kses( $text, \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() ); ?></p>
