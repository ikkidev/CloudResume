<?php
/**
 * Links Shortcode
 *
 * @package AdAce.
 * @subpackage Links.
 */

add_shortcode( 'adace_links', 'adace_links_shortcode' );
/**
 * Snapcode shortcode
 *
 * @param array $atts Snapcode shortcode atts.
 * @return string HTML
 */
function adace_links_shortcode( $atts ) {
	// Fill shortcode atts.
	$atts_filled = shortcode_atts(
		array(
			'title'       => '',
			'category'    => '',
			'simple'      => false,
			'highlighted' => 2,
			'transparent' => false,
		),
		$atts,
		'adace_links'
	);
	// Sanitize atts.
	$category    = filter_var( $atts_filled['category'], FILTER_SANITIZE_STRING );
	$simple      = filter_var( $atts_filled['simple'], FILTER_VALIDATE_BOOLEAN );
	$highlighted = filter_var( $atts_filled['highlighted'], FILTER_SANITIZE_NUMBER_INT );
	$transparent = filter_var( $atts_filled['transparent'], FILTER_VALIDATE_BOOLEAN );
	// Get output.
	$output      = '';
	$links_list = adace_get_links_list( $category, $simple, $highlighted, $transparent );
	if ( ! empty( $links_list ) ) {
		$output = '<div class="csstodo-links-wrapper adace-links-shortcode">';
		if ( ! empty( $atts_filled['title'] ) ) {
			$output .= '<h3 class="g1-gamma g1-gamma-1st">' . wp_kses_post( $atts_filled['title'] ) . '</h3>';
		}
		$output .= adace_get_links_list( $category, $simple, $highlighted );
		$output .= '</div>';
	}
	// Return.
	return $output;
}
