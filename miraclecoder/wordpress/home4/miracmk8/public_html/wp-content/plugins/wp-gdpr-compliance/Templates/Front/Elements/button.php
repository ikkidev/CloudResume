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
	echo esc_attr($name) . '="' . esc_attr( $value ) . '" ';}
?>
><?php echo esc_html( $text ); ?></button>
