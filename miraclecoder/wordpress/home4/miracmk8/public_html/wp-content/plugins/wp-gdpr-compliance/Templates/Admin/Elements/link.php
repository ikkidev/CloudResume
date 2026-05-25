<?php

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Template;

if ( empty( $text ) ) {
	return;
}
if ( empty( $url ) ) {
	return;
}
if ( ! isset( $icon ) ) {
	return;
}
if ( empty( $attr ) ) {
	$attr = [];
}

// is done like this to stop the addition of extra whitespace into the link.
$externalIcon = '';
if ( $icon && isset( $attr['target'] ) && ! empty( $target = $attr['target'] ) && $target === '_blank' ) {
	 $externalIcon = Template::getIcon( 'external-link', 'fontawesome-pro-regular' );
}

?>

<a href="<?php echo esc_url( $url ); ?>"
					<?php
                        foreach ( $attr as $name => $value ) {
                            echo  esc_html($name) . '="' . esc_attr( $value ) . '" ';
                        }
					?>
>

    <?php echo wp_kses( $text, AdminHelper::getAllAllowedSvgTags() ), wp_kses( $externalIcon, AdminHelper::getAllowedSvgTags() ); ?>
</a>
