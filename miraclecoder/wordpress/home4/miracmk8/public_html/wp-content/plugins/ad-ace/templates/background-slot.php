<?php
/**
 * Adace Background Slot
 *
 * @package adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$slot = adace_slot_get_query();
$ad = adace_ad_get_query();

// Output style.
$slot_styles_array = array();
$background_id = $ad['custom']['adace_ad_image'];
$background_attachment_src = wp_get_attachment_image_src( $background_id, 'full' );
$ad_link = adace_get_ad_link( $ad['custom'] );

if ( $background_attachment_src ) {
	$slot_styles_array['background-image'] = 'background-image:url(\'' . esc_url( $background_attachment_src[0] ) . '\')';
}
if ( ! empty( $slot_styles_array ) ) {
	$slot_styles = 'style="' . esc_attr( join( '', $slot_styles_array ) ) . '"';
} else {
	$slot_styles = '';
}
?>
<?php do_action( 'adace_background_slot_start' ); ?>
	<a class="adace-slot-wrapper <?php echo( sanitize_html_class( $slot['slot_id'] ) ); ?> <?php echo( sanitize_html_class( $ad_link['css_class'] ) ); ?>" <?php echo( sanitize_html_class( $slot_styles ) ); ?> href="<?php echo( esc_url( $ad_link['url'] ) ); ?>">
		<div class="adace-slot">
		</div>
	</a>
<?php do_action( 'adace_background_slot_end' ); ?>
<?php
