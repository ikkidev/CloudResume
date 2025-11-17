<?php
/**
 * AMP Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'template_redirect', 	'adace_amp_initialize' );

/**
 * Set AMP hooks
 */
function adace_amp_initialize() {
	if ( is_amp_endpoint() ) {
		add_filter( 'amp_post_template_data', 	'adace_amp_postprocess' );
		add_filter( 'adace_custom_output', 'adace_amp_sanitize_custom_ad' );
		add_filter( 'adace_custom_css', 'adace_amp_remove_custom_css' );

		remove_filter( 'adace_custom_output', 'adace_wrap_in_loader', 10 );
		remove_filter( 'adace_adsense_output', 'adace_wrap_in_loader', 10 );
	}
}

/**
 * Postprocess final output.
 *
 * @param array $data  AMP template data.
 * @return array
 */
function adace_amp_postprocess( $data ) {
	if ( isset( $data['post_amp_content'] ) ) {

		$content = $data['post_amp_content'];

		// add scripts.
		if ( 0 !== substr_count( $content, '<amp-ad' ) ) {
			$data['amp_component_scripts']['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';
		}

		$data['post_amp_content'] = $content;
	}
	return $data;
}

/**
 * Render AMP AdSense for queried ad
 */
function adace_amp_render_adsense() {
	$ad = adace_ad_get_query();
	if ( ! $ad ) {
		return;
	}

	$pub 	= $ad['adsense']['adace_adsense_pub'];
	$unit 	= $ad['adsense']['adace_adsense_slot'];
	$amp_height = apply_filters( 'adace_amp_adsense_height',75 );

	$html = sprintf('<amp-ad
	layout="responsive"
	width=300
	height=' . $amp_height . '
	type="adsense"
	data-ad-client="%s"
	data-ad-slot="%s">
	</amp-ad>', $pub, $unit);

	echo apply_filters( 'adace_amp_render_adsense', $html );
}

/**
 * Sanitize custom ads for AMP
 *
 * @param string $html  Content.
 * @return string
 */
function adace_amp_sanitize_custom_ad( $html ) {
	list( $sanitized_html, $featured_scripts, $featured_styles ) = AMP_Content_Sanitizer::sanitize(
		$html,
		array( 'AMP_Img_Sanitizer' => array() ),
		array()
	);

	return $sanitized_html;
}

/**
 * Remove custom css is amp
 *
 * @param string $css CSS code.
 * @return string
 */
function adace_amp_remove_custom_css( $css ) {
	return '';
}
