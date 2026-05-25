<?php

/**
 * @var String $sprite
 * @var String $icon
 */

use WPGDPRC\WordPress\Plugin;

$href = Plugin::getAssetsUrl( 'icons' ) . "/sprite-$sprite.svg#$icon"

?>

<span data-icon="<?php echo esc_attr( $icon ); ?>" class="icon--wrap">
	<svg class="icon">
		<use href=<?php echo esc_url( $href ); ?>></use>
	</svg>
</span>
