<?php

if ( empty( $text ) ) {
	return;
}
if ( empty( $attr ) ) {
	$attr = [];
}

?>

<button
<?php
foreach ( $attr as $name => $value ) {
	echo esc_html($name) . '="' . esc_attr( $value ) . '" ';}
?>
>
	<?php echo wp_kses( $text, \WPGDPRC\Utils\AdminHelper::getAllAllowedSvgTags()); ?>
</button>
