<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\WordPress\Plugin;

/**
 * @var string $name
 * @var string $value
 * @var string $class
 * @var array $args
 */

if ( empty( $id ) ) {
	$id = sanitize_key( $name );
}
if ( empty( $value ) ) {
	$value = '';
}
wp_dropdown_pages(
	[
		'id'               => esc_attr($id),
		'name'             => esc_attr($name),
		'selected'         => esc_attr($value),
		'class'            => esc_attr($class),
		'post_status'      => $args['post_status'] ?? 'publish', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- is an array. And does not get echoed.
		'show_option_none' => esc_textarea( $args['show_option_none'] ?? _x( 'Select a page', 'admin', 'wp-gdpr-compliance' ) ),
	]
);

?>

<span class="
<?php
if ( empty( $value ) ) {
	echo 'hidden';}
?>
">
	<?php
	Elements::editLink(
		(int) $value,
		_x( 'Edit page', 'admin', 'wp-gdpr-compliance' ),
		[
			'target' => '_blank',
			'class'  => 'wpgdprc-link wpgdprc-link--edit',
		]
	);
	?>
</span>
