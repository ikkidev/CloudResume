<?php
/**
 * Title shortcode
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_shortcode( 'bimber_title', 					'bimber_title_shortcode' );

/**
 * Title shortcode
 *
 * @param array $atts			Shortcode attributes.
 *
 * @return string				Shortcode output.
 */
function bimber_title_shortcode( $atts, $content ) {
	$default_atts = array(
		'size' 	=> 'h4', 	// Options: h1-h6, giga, mega.
		'align'	=> '', 		// Options: center or empty.
		'id' 	=> '',
		'class'	=> '',
	);

	$atts = shortcode_atts( $default_atts, $atts, 'bimber_title' );

	// Map sizes to CSS classes.
	$mapping = array(
		'giga'  => array( 'g1-giga',    'g1-giga-2nd' ),
		'mega'  => array( 'g1-mega',    'g1-mega-2nd' ),
		'h1'    => array( 'g1-alpha',   'g1-alpha-2nd' ),
		'h2'    => array( 'g1-beta',    'g1-beta-2nd' ),
		'h3'    => array( 'g1-gamma',   'g1-gamma-2nd' ),
		'h4'    => array( 'g1-delta',   'g1-delta-2nd' ),
		'h5'    => array( 'g1-epsilon', 'g1-epsilon-2nd' ),
		'h6'    => array( 'g1-zeta',    'g1-zeta-2nd' ),
	);

	// Compose final HTML class attribute
	$final_class = array(
		'g1-title',
		'g1-title-align-' . $atts['align'],
	);

	if ( isset( $mapping[ $atts['size'] ] ) ) {
		$final_class = array_merge( $final_class, $mapping[ $atts['size'] ] );
	}

	$final_class = array_merge( $final_class, explode( ' ', $atts['class'] ) );

	$out = '';

	if ( strlen( $content ) ) {
		$out .= '<h2 id="' . esc_attr( $atts['id'] ) . '" class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '"><span>';
		$out .= wp_kses_post( $content );
		$out .= '</span></h2>';

		// HTML validation.
		$out = str_replace('id=""', '', $out);
	}

	return $out;
}
