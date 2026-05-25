<?php
/**
 * AMP plugin functions
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

// Filter post template data.
add_filter( 'amp_post_template_data', 'bimber_amp_post_template_data', 10, 2 );

add_action( 'wp', 'bimber_amp_hooks' );
add_action( 'wp', 'bimber_amp_post_set_elements_order' );


/**
 * AMP specific hooks.
 */
function bimber_amp_hooks() {
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		// Adjust avatars HTML.
		add_filter( 'get_avatar', 'bimber_amp_get_avatar', 999, 5 );

		add_filter( 'post_thumbnail_html', 'bimber_amp_sanitize_img_html', 10, 5 );

		add_filter( 'amp_post_template_data', 	'bimber_amp_postprocess' );

		add_filter( 'bimber_display_g1_socials_in_author_box', '__return_false' );
		remove_action( 'bimber_author_after_name', 'bimber_mycred_add_badges_to_author_box' );
		remove_action( 'bimber_author_box_after_avatar', 'bimber_mycred_render_rank_in_author_info_box', 10, 1 );

		// Don't strip videos.
		remove_filter( 'the_content', 'bimber_strip_first_video_from_content', 5 );
	}
}



/**
 * Set up extra post data.
 *
 * @param $data
 * @param $post
 *
 * @return mixed
 */
function bimber_amp_post_template_data( $data, $post ) {
	if ( $data['featured_image' ] ) {
		$post_id = $post->ID;

		$featured_html = get_the_post_thumbnail( $post_id, 'bimber-grid-2of3' );

		list( $sanitized_html, $featured_scripts, $featured_styles ) = AMP_Content_Sanitizer::sanitize(
			$featured_html,
			array( 'AMP_Img_Sanitizer' => array() ),
			array(
				'content_max_width' => 758,
			)
		);

		$data['featured_image']['amp_html'] = $sanitized_html;
	}

	// Google Fonts.
	$font_families = bimber_get_google_font_families();

	if ( ! is_array( $font_families ) ) {
		$font_families = array();
	}

	foreach( $font_families as $key => $value ) {
		$font_families[$key] = 'https://fonts.googleapis.com/css?family='. $value;
	}

	$data['font_urls'] = $font_families;

	// Make GIFs 100% width.
	$data['post_amp_content'] = preg_replace('/(<amp-anim.+?)sizes=".+?"(>)/i', "$1$2", $data['post_amp_content']);

	// Remove the layout="intrinsic" attribute from the <amp-anim>.
	$data['post_amp_content'] = preg_replace('/(<amp-anim.+?)layout="intrinsic"(.+?>)/i', '$1 $2', $data['post_amp_content']);

	// Add the layout="responsive" attribute to the <amp-anim>.
	$data['post_amp_content'] = preg_replace('/(<amp-anim.+?src=".+?gif".+?)(>)/i', '$1 layout="responsive" $2', $data['post_amp_content']);

	return $data;
}


/**
 * Remove empty style attribute.
 *
 * @param string $out HTML markup.
 *
 * @return string
 */
function bimber_amp_remove_empty_style_attribute( $out ) {
	$out = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $out);

	return $out;
}


/**
 * Set final elements order on a single post page
 */
function bimber_amp_post_set_elements_order() {
	//add_action( 'bimber_amp_after_single_content', 'bimber_render_pagination_single',       bimber_get_theme_option( 'post', 'pagination_single_order' ) );

	add_action( 'bimber_amp_after_single_content', 'bimber_render_bottom_share_buttons',        bimber_get_theme_option( 'post', 'bottom_share_buttons_order' ) );
	add_action( 'bimber_amp_after_single_content', 'bimber_render_entry_tags', 	                bimber_get_theme_option( 'post', 'tags_order' ) );

	//add_action( 'bimber_amp_after_single_content', 'bimber_render_newsletter', 	            bimber_get_theme_option( 'post', 'newsletter_order' ) );

	add_action( 'bimber_amp_after_single_content', 'bimber_amp_render_nav_single',                  bimber_get_theme_option( 'post', 'nav_single_order' ) );
	add_action( 'bimber_amp_after_single_content', 'bimber_render_author_info',                 bimber_get_theme_option( 'post', 'author_info_order' ) );

	add_action( 'bimber_amp_after_single_content', 'bimber_amp_render_related_entries', 	    bimber_get_theme_option( 'post', 'related_entries_order' ) );
	add_action( 'bimber_amp_after_single_content', 'bimber_amp_render_more_from', 		        bimber_get_theme_option( 'post', 'more_from_order' ) );

	add_action( 'bimber_amp_after_single_content', 'bimber_amp_render_comments', 		        bimber_get_theme_option( 'post', 'comments_order' ) );

	add_action( 'bimber_amp_after_single_content', 'bimber_amp_render_dont_miss', 		        bimber_get_theme_option( 'post', 'dont_miss_order' ) );
	add_action( 'bimber_amp_after_single_content', 'bimber_render_missing_metadata',        9999 );

	add_action( 'amp_post_template_metadata', 'bimber_amp_post_template_metadata' );
}

function bimber_amp_render_related_entries( $args ) {
	get_template_part( 'template-parts/ads/ad-before-related-entries' );

	if ( isset( $args['elements']['related_entries'] ) && $args['elements']['related_entries'] ) {
		get_template_part( 'amp/collection-related', $args['layout'] );
	}
}

function bimber_amp_render_more_from( $args ) {
	get_template_part( 'template-parts/ads/ad-before-more-from' );

	if ( isset( $args['elements']['more_from'] ) && $args['elements']['more_from'] ) {
		get_template_part( 'amp/collection-more-from' );
	}
}

function bimber_amp_render_comments( $args ) {
	if ( isset( $args['elements']['comments'] ) && $args['elements']['comments'] ) {

	}
	get_template_part( 'amp/comments' );
}

function bimber_amp_render_dont_miss( $args ) {
	// Ad slot.
	get_template_part( 'template-parts/ads/ad-before-dont-miss' );

	if ( isset( $args['elements']['dont_miss'] ) && $args['elements']['dont_miss'] ) {
		get_template_part( 'amp/collection-dont-miss', $args['layout'] );
	}
}

function bimber_amp_sanitize_img_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	list( $sanitized_html, $featured_scripts, $featured_styles ) = AMP_Content_Sanitizer::sanitize(
		$html,
		array( 'AMP_Img_Sanitizer' => array() ),
		array(
			'content_max_width' => 758,
		)
	);

	return $sanitized_html;
}


function bimber_amp_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	$avatar = str_replace(
		array(
			'<img ',
			'/>',
		),
		array(
			'<amp-img layout="intrinsic" ',
			'></amp-img>',
		),
		$avatar
	);

	return $avatar;
}

function bimber_amp_render_nav_single( $args ) {
	if ( isset( $args['elements']['navigation'] ) && $args['elements']['navigation'] ) {
		get_template_part( 'amp/nav-single' );
	}
}

/**
 * Force AMP permalinks.
 *
 * @param str     $url   The url.
 * @param WP_Post $post  The post.
 * @return str
 */
function bimber_amp_force_amp_permalinks( $url, $post ) {
	$amp_url = amp_get_permalink( $post->ID );
	if ( isset( $amp_url ) ) {
		return $amp_url;
	} else {
		return $url;
	}
}

/**
 * Postprocess final output.
 *
 * @param array $data  AMP template data.
 * @return array
 */
function bimber_amp_postprocess( $data ) {
	if ( isset( $data['post_amp_content'] ) ) {
		// add scripts.
		if ( 0 !== substr_count( $data['post_amp_content'], '<amp-video' ) ) {
			$data['amp_component_scripts']['amp-video'] = 'https://cdn.ampproject.org/v0/amp-video-0.1.js';
		}
		// We force the usage of amp-anim because there is no filter to catch gifs in after-post collections.
		$data['amp_component_scripts']['amp-anim'] = 'https://cdn.ampproject.org/v0/amp-anim-0.1.js';
		// The same with adsense.
		$data['amp_component_scripts']['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';
	}
	return $data;
}

/**
 * Set schema type to Article
 *
 * @param array $metadata
 * @return array
 */
function bimber_amp_post_template_metadata( $metadata ) {
	$metadata['@type'] = 'Article';
	return $metadata;
}

/**
 * Sanitize share buttons for AMP compliancy.
 *
 * @param string $html  HTML of share buttons.
 * @return string
 */
function bimber_amp_sanitize_shares_html( $html ) {
	$html = preg_replace( '/<!-- MailChimp for WordPress.*<!-- \/ MailChimp for WordPress Plugin -->/sm', '', $html );
	$html = preg_replace( '/<script.*<\/script>/sm', '', $html );
	return $html;
}
