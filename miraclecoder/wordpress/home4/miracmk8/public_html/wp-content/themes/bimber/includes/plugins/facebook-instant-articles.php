<?php
/**
 * Facebook Instant Articles plugin functions
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

add_action( 'instant_articles_before_transform_post', 'bimber_fbia_before_transform_post' );
add_action( 'instant_articles_after_transform_post', 'bimber_fbia_after_transform_post' );

/**
 * Run actions before content is processed.
 */
function bimber_fbia_before_transform_post() {
	remove_filter( 'embed_oembed_html', 'bimber_fluid_wrapper_embed_oembed_html', 10 );
	add_filter( 'the_content', 'bimber_fia_add_subtitle_before_content' );

	if ( function_exists( 'quads_get_load_priority' ) ) {
		remove_filter( 'the_content', 'quads_post_settings_to_quicktags', 5 );
		remove_filter( 'the_content', 'quads_process_content', quads_get_load_priority() );
	}
	if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
		add_filter( 'adace_disable_ads_per_post', '__return_true', 99 );
	}

	if ( bimber_can_use_plugin( 'media-ace/media-ace.php' ) ) {
		add_filter( 'mace_lazy_load_embed',     '__return_false', 99 );
		add_filter( 'mace_lazy_load_image',     '__return_false', 99 );
	}

	if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
		remove_filter( 'the_content', 'snax_post_content' , 9 );
	}
}

/**
 * Run actions after content is processed.
 */
function bimber_fbia_after_transform_post() {
	add_filter( 'embed_oembed_html', 'bimber_fluid_wrapper_embed_oembed_html', 10, 999 );
	remove_filter( 'the_content', 'bimber_fia_add_subtitle_before_content' );

	if ( function_exists( 'quads_get_load_priority' ) ) {
		add_filter( 'the_content', 'quads_post_settings_to_quicktags', 5 );
		add_filter( 'the_content', 'quads_process_content', quads_get_load_priority() );
	}
	if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
		remove_filter( 'adace_disable_ads_per_post', '__return_true', 99 );
	}

	if ( bimber_can_use_plugin( 'media-ace/media-ace.php' ) ) {
		remove_filter( 'mace_lazy_load_embed',     '__return_false', 99 );
		remove_filter( 'mace_lazy_load_image',     '__return_false', 99 );
	}

	if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
		add_filter( 'the_content', 'snax_post_content' , 9 );
	}
}

/**
 * Add the subtitle to the Instant Article's content
 *
 * @param str $content  The Content.
 * @return str
 */
function bimber_fia_add_subtitle_before_content( $content ) {
	ob_start();
	if ( bimber_can_use_plugin( 'wp-subtitle/wp-subtitle.php' ) ) :
		the_subtitle( '<h2>', "</h2>\n" );
	endif;
	$subtitle = ob_get_clean();
	$content = $subtitle . $content;
	return $content;
}

