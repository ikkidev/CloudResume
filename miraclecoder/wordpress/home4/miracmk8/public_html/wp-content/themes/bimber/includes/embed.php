<?php
/**
 * Embed functions
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

add_filter( 'embed_defaults', 'bimber_embed_defaults', 10, 2 );
add_filter( 'embed_oembed_html', 'bimber_fluid_wrapper_embed_oembed_html', 10, 999 );
add_filter( 'bimber_apply_fluid_wrapper_for_oembed', 'bimber_apply_fluid_wrapper_for_services', 10, 2 );

// Gfycat.
add_action( 'after_setup_theme', 'bimber_add_oembed_gfycat' );
add_filter( 'embed_defaults', 'bimber_embed_defaults_gfycat', 10, 2 );

// Facebook and Instagram support after October 24th.
add_filter( 'oembed_providers', 'bimber_oembed_add_facebook_support', 10, 1 );
add_filter( 'oembed_fetch_url', 'bimber_oembed_append_facebook_token', 10, 1);

/**
 * Adjust embed defaul values.
 *
 * @param array  $dims Dimensions.
 * @param string $url URL.
 *
 * @return mixed
 */
function bimber_embed_defaults( $dims, $url ) {
	// 16:9 aspect ratio.
	$video_16_9_domains = apply_filters( 'bimber_oembed_video_16_9_domains', array(
		'youtube.com',
		'youtu.be',
		'vimeo.com',
		'dailymotion.com',
		'facebook.com/plugins/video.php',
	) );

	$is_video_16_9 = false;

	foreach ( $video_16_9_domains as $video_16_9_domain ) {
		if ( strpos( $url, $video_16_9_domain ) !== false ) {
			$is_video_16_9 = true;
			break;
		}
	}

	if ( $is_video_16_9 ) {
		$dims['height'] = absint( round( 9 * $dims['width'] / 16 ) );
	}

	// 1:1 aspect ratio.
	$video_1_1_domains = apply_filters( 'bimber_oembed_video_1_1_domains', array(
		'vine.co',
	) );

	$is_video_1_1 = false;

	foreach ( $video_1_1_domains as $video_1_1_domain ) {
		if ( strpos( $url, $video_1_1_domain ) !== false ) {
			$is_video_1_1 = true;
			break;
		}
	}

	if ( $is_video_1_1 ) {
		$dims['height'] = $dims['width'];
	}

	return $dims;
}

function bimber_add_oembed_gfycat() {
	// @todo
	// The language code shouldn't be in the embed URL though.
	wp_oembed_add_provider( '#http(s)?://(www\.)?gfycat\.com/([a-z]{2}/)?.*#i', 'https://api.gfycat.com/v1/oembed', true );
}

function bimber_embed_defaults_gfycat( $dims, $url ) {
	if ( preg_match( '#http(s)?://(www\.)?gfycat\.com/([a-z]{2}/)?.*#i', $url ) ) {
		$dims['width']  = 0;
		$dims['height'] = 0;
	}

	return $dims;
}

/**
 * Add support for Facebook and Instragram oEmbed (after October 24th)
 *
 * @param array $providers      Providers.
 *
 * @return array
 */
function bimber_oembed_add_facebook_support( $providers ) {
    $extra_providers = array(
        // Facebook.
        '#https?://www\.facebook\.com/.*/posts/.*#i'        => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/.*/activity/.*#i'     => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/.*/photos/.*#i'       => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/photo(s/|\.php).*#i'  => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/permalink\.php.*#i'   => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/media/.*#i'           => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/questions/.*#i'       => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/notes/.*#i'           => array( 'https://graph.facebook.com/v8.0/oembed_post', true ),
        '#https?://www\.facebook\.com/.*/videos/.*#i'       => array( 'https://graph.facebook.com/v8.0/oembed_video', true ),
        '#https?://www\.facebook\.com/video\.php.*#i'       => array( 'https://graph.facebook.com/v8.0/oembed_video', true ),
        '#https?://www\.facebook\.com/watch/?\?v=\d+#i'     => array( 'https://graph.facebook.com/v8.0/oembed_video', true ),

        // Instagram.
        '#https?://(www\.)?instagr(\.am|am\.com)/(p|tv)/.*#i' => array( 'https://graph.facebook.com/v8.0/instagram_oembed', true ),
        '#https?://(www\.)?instagr(\.am|am\.com)/p/.*#i'      => array( 'https://graph.facebook.com/v8.0/instagram_oembed', true ),
    );

    $providers = array_merge( $providers, $extra_providers );

    return $providers;
}

/**
 * Convert Facebook URLs to include a token
 *
 * @param string $provider_url          Provider URL.
 *
 * @return string
 */
function bimber_oembed_append_facebook_token( $provider_url ) {
    if ( 0 !== strpos( $provider_url, 'https://graph.facebook.com/v8.0/' ) ) {
        return $provider_url;
    }

    $app_id     = bimber_get_facebook_app_id();
    $app_secret = bimber_get_facebook_app_secret();

    if ( empty( $app_id ) || empty( $app_secret ) ) {
        return $provider_url;
    }

    $access_token = $app_id . '|' . $app_secret;

    return sprintf( '%s&access_token=%s', $provider_url, urlencode( $access_token ) );
}

/**
 * Wrap embeds in fluid wrapper
 *
 * @param string $html oembed HTML markup.
 * @param string $url Embed URL.
 * @param array  $attr Attributes.
 *
 * @return string
 */
function bimber_fluid_wrapper_embed_oembed_html( $html, $url, $attr ) {
	$apply = apply_filters( 'bimber_apply_fluid_wrapper_for_oembed', false, $url );

	preg_match_all( '/<blockquote class=\"embedly-card\".*<\/blockquote>/', $html, $matches );

	if ( ! $apply || count( $matches[0] ) > 0 ) {
		return $html;
	}

	return bimber_fluid_wrapper( array(
		'width'  => esc_attr( $attr['width'] ),
		'height' => esc_attr( $attr['height'] ),
	), $html );
}

/**
 * Keep element ratio while scaling.
 *
 * @param array  $atts Attributes.
 * @param string $content Content.
 *
 * @return string
 */
function bimber_fluid_wrapper( $atts, $content ) {
	/* We need a static counter to trace a shortcode without the id attribute */
	static $counter = 0;
	$counter ++;

	$vars = shortcode_atts( array(
		'id'     => '',
		'class'  => '',
		'width'  => '',
		'height' => '',
	), $atts, 'bimber_fluid_wrapper' );

	$id     = $vars['id'];
	$class  = $vars['class'];
	$width  = $vars['width'];
	$height = $vars['height'];

	$content = preg_replace( '#^<\/p>|<p>$#', '', $content );

	// Compose final HTML id attribute.
	$final_id = strlen( $id ) ? $id : 'g1-fluid-wrapper-counter-' . $counter;

	// Compose final HTML class attribute.
	$final_class = array(
		'g1-fluid-wrapper',
	);

	$final_class = array_merge( $final_class, explode( ' ', $class ) );

	// Get width and height values.
	$width  = absint( $width );
	$height = absint( $height );

	if ( ! $width ) {
		$re    = '/width=[\'"]?(\d+)[\'"]?/';
		$width = preg_match( $re, $content, $match );
		$width = $width ? absint( $match[1] ) : 0;
	}

	if ( ! $height ) {
		$re     = '/height=[\'"]?(\d+)[\'"]?/';
		$height = preg_match( $re, $content, $match );
		$height = $height ? absint( $match[1] ) : 0;
	}

	$height = ( 9999 === $height ) ? round( $width * 9 / 16 ) : $height;

	// Compose output.
	$out = '<div id="%id%" class="%class%" %outer_style% data-g1-fluid-width="%fluid_width%" data-g1-fluid-height="%fluid_height%">
	       <div class="g1-fluid-wrapper-inner" %inner_style%>
	       %content%
	       </div>
	       </div>';
	$out = str_replace(
		array(
			'%id%',
			'%class%',
			'%outer_style%',
			'%fluid_width%',
			'%fluid_height%',
			'%inner_style%',
			'%content%',
		),
		array(
			esc_attr( $final_id ),
			implode( ' ', array_map( 'sanitize_html_class', $final_class ) ),
			( $width && $height ? 'style="width:' . absint( $width ) . 'px;"' : '' ),
			$width,
			$height,
			( $width && $height ? 'style="padding-bottom:' . ( absint( $height ) / absint( $width ) ) * 100 . '%;"' : '' ),
			do_shortcode( shortcode_unautop( $content ) ),
		),
		$out
	);

	return $out;
}

/**
 * Apply fluid wrapper for embedded services
 *
 * @param bool   $apply     Current state.
 * @param string $url       Service url.
 *
 * @return bool
 */
function bimber_apply_fluid_wrapper_for_services( $apply, $url ) {
	$services = apply_filters( 'bimber_fluid_wrapper_services', array(
		'youtube.com',
		'youtu.be',
		'vimeo.com',
		'dailymotion.com',
		'vine.co',
		'facebook.com/plugins/video.php',
	) );

	foreach ( $services as $service ) {
		if ( strpos( $url, $service ) !== false ) {
			$apply = true;
			break;
		}
	}

	return $apply;
}
