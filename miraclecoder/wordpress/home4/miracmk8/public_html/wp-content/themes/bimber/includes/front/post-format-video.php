<?php
/**
 * Post format Video
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

add_filter( 'the_content', 'bimber_strip_first_video_from_content', 5 );    // 5 to hook before WP converts url to code.

/**
 * Strim url from a video post content
 *
 * @param string $content       Post content.
 *
 * @return string
 */
function bimber_strip_first_video_from_content( $content ) {
	if ( 'video' !== get_post_format() ) {
		return $content;
	}

	$hide_in_content = bimber_get_theme_option( 'post_video', 'hide_in_content' );

	if ( ! $hide_in_content ) {
		return $content;
	}

	$post_template = bimber_get_single_post_template();

	$is_background_tpl  = 0 === strpos( $post_template, 'background-' );
	$is_overlay_tpl     = 0 === strpos( $post_template, 'overlay-' );

	// These templates don't support featured video so we can't remove it from content.
	$skip_template =  $is_background_tpl || $is_overlay_tpl;

	if ( $skip_template ) {
		return $content;
	}

	$url = bimber_get_first_url_in_content();

	if ( ! $url ) {
		return $content;
	}

    /**
     * Find first Gutenberg embed block
     *
     * Regexp modifiers:
     * i - case insensitive
     * s - include new lines
     * U - ungreedy, stop on first match. Don't look to the end of string.
     */
    if (
        preg_match( '/<!--\s+wp:core-embed.*<!-- \/wp:core-embed[^\s]+ -->/isU', $content, $matches ) ||    // before WordPress 5.6
        preg_match( '/<!--\s+wp:embed.*<!-- \/wp:embed\s+-->/isU', $content, $matches )                     // since WordPRess 5.6
        ) {
        $wp_embed_block = $matches[0];

        // Remove entire block if contains url.
        if ( false !== strpos( $wp_embed_block, $url ) ) {
            $content = str_replace( $wp_embed_block, '', $content );

            return $content;
        }
    }

	/**
	 * Find first Gutenberg video block.
	 *
	 * Regexp modifiers:
	 * i - case insensitive
	 * s - include new lines
	 * U - ungreedy, stop on first match. Don't look to the end of string.
	 */
	if ( preg_match( '/<!--\s+wp:video.*<!-- \/wp:video\s+-->/isU', $content, $matches ) ) {
		$wp_video_block = $matches[0];

		// Remove entire block if contains url.
		if ( false !== strpos( $wp_video_block, $url ) ) {
			$content = str_replace( $wp_video_block, '', $content );

			return $content;
		}
	}

	// Remove raw url.
	$content = str_replace( $url, '', $content );

	// And all new lines after it.
	$content = preg_replace( '/^[\n\s]+/', '', $content );

	return $content;
}

/**
 * Return first url in post content
 *
 * @param int|WP_Post $post     Post id or WP_Post object.
 *
 * @return bool|string          False if not found.
 */
function bimber_get_first_url_in_content( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$content = $post->post_content;

	$content = bimber_strip_html_comments( $content );

	// @todo - can we extend support for iframes/video etc?
	// If so, we should use the get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
	// Example: twentyseventeen/template-parts/post/content-video.php at line 50.

	// Temporary workaround: Strip iframes. We don't want to get url from them.
	$content = preg_replace( '/<iframe[^>]+>.*<\/iframe>/isU', '', $content );

	// @todo - refactor end.

	if ( preg_match( '/https?:\/\/[^\n"\'<]+/i', $content, $matches ) ) {
		return trim( esc_url_raw( $matches[0] ) );
	}

	return false;
}

/**
 * Strip HTML comments from text
 *
 * @param string $input         Input text.
 *
 * @return string
 */
function bimber_strip_html_comments( $input = '' ) {
	return preg_replace( '/<!--(.|\s)*?-->/', '', $input );
}
