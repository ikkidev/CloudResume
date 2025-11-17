<?php
/**
 * Adace Standard template
 *
 * @package adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $adace_standard_template_args;
$slot = adace_slot_get_query();
$ad = adace_ad_get_query();

$slot_styles_array = array();
$alignement_class = '';
if ( isset( $adace_standard_template_args['align'] ) && 'none' !== $adace_standard_template_args['align'] ) {
	$slot_styles_array['alignment'] = 'text-align:' . $adace_standard_template_args['align'] . ';';
	$alignement_class = 'adace-align-' . $adace_standard_template_args['align'];
}

if ( ! empty( $slot_styles_array ) ) {
	$slot_styles = 'style="' . esc_attr( join( '', $slot_styles_array ) ) . '"';
} else {
	$slot_styles = '';
}

?>
<div class="adace-slot-wrapper <?php echo( sanitize_html_class( $slot['slot_id'] ) ); ?> <?php echo( sanitize_html_class( $alignement_class ) ); ?>" <?php echo( $slot_styles ); ?>>
	<div class="adace-disclaimer">
	<?php
	$disclaimer = get_option( 'adace_general_disclaimer', '' );
	echo $disclaimer;
	?>
	</div>
	<div class="adace-slot"><?php do_action( 'adace_generic_slot_start' );
		if ( 'custom' === $ad['type'] ) {
			adace_render_custom_ad();
		}
		if ( 'adsense' === $ad['type'] ) {
			adace_render_adsense_ad();
		}
		do_action( 'adace_generic_slot_end' ); ?>
	</div>
</div>
<?php
