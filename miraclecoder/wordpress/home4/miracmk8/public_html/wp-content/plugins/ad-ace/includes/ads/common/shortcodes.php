<?php
/**
 * Shortcodes
 *
 * @package AdAce.
 * @subpackage Functions
 */

$shortcode_slug = apply_filters( 'adace_shortcode_slug', 'adace-ad' );
add_shortcode( $shortcode_slug, 'adace_ad_shortcode' );

/**
 * Callback function for ad shortcode
 *
 * @param array $atts Shortcode atts.
 * @return string Shortcode output.
 */
function adace_ad_shortcode( $atts ) {
	if ( ! apply_filters( 'adace_display_shortcode',  true, $atts ) ) {
		return;
	}

	$atts_filled = shortcode_atts( array(
		'id'    => '',
		'align' => 'center',
	), $atts );

	if ( empty( $atts_filled['id'] ) ) {
		return;
	}
	$slot_id = 'adace-shortcode-' . $atts_filled['id'];
	if ( adace_disable_ads_per_post( $slot_id ) ) {
			return '';
	}
	$html = adace_capture_ad_standard_template( $atts_filled['id'], $slot_id, $atts_filled );

	return apply_filters( 'adace_ad_shortcode_output', $html, $atts );
}

/**
 * Get shortcode string for an ad ID
 *
 * @param int $ad_id  Ad id.
 * @return string
 */
function adace_get_shortcode_for_ad( $ad_id ) {
	$shortcode_slug = apply_filters( 'adace_shortcode_slug', 'adace-ad' );
	return '[' . $shortcode_slug . ' id="' . $ad_id . '"]';
}

/**
 * Get PHP shortcode string for an ad ID
 *
 * @param int $ad_id  Ad id.
 * @return string
 */
function adace_get_php_shortcode_for_ad( $ad_id ) {
	return "<?php echo do_shortcode('" . adace_get_shortcode_for_ad( $ad_id ) . "'); ?>";
}
