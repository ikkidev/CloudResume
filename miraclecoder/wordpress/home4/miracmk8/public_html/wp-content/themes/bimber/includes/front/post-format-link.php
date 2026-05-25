<?php
/**
 * Post format Link
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

add_filter( 'the_content',              'bimber_link_lading_page_content' );
add_filter( 'the_content',              'bimber_link_single_page' );
add_filter( 'get_the_excerpt',          'bimber_remove_link_from_excerpt' );
add_filter( 'bimber_show_excerpt_more', 'bimber_hide_link_excerpt_more_link' );
add_filter( 'bimber_the_permalink',     'bimber_change_permalink_to_content_url' );
add_filter( 'bimber_link_permalink',    'bimber_use_link_landing_page_permalink', 10, 2 );
add_filter( 'bimber_entry_title_data',  'bimber_link_entry_title_data' );
add_filter( 'bimber_entry_featured_media_link_data', 'bimber_link_entry_featured_media_link_data', 10, 2 );
add_filter( 'bimber_written_by_author_tpl',          'bimber_link_written_by_author_tpl' );

/**
 * Add the link redirection content
 *
 * @param string $content       Page content.
 *
 * @return string
 */
function bimber_link_lading_page_content( $content ) {
	if ( ! bimber_is_link_landing_page() ) {
		return $content;
	}

	$landing_page_content = bimber_get_link_landing_page_content();

	if ( empty( $landing_page_content ) ) {
		return $content;
	}

	$landing_page_content_tag = apply_filters( 'bimber_link_landing_page_content_tag', '<!--bimber-link-landing-page-content-->' );

	if ( false !== strpos( $content, $landing_page_content_tag ) ) {
		$content = str_replace( $landing_page_content_tag, $landing_page_content, $content );
	} else {
		// Append to the content.
		$content = $landing_page_content . $content;
	}

	return $content;
}

/**
 * Return count view query arg name
 *
 * @return string
 */
function bimber_get_count_view_query_arg() {
	return apply_filters( 'bimber_count_view_query_arg', 'bimber-count-view' );
}

/**
 * Highlight link url in page content
 *
 * @param string $content       Page content.
 *
 * @return string
 */
function bimber_link_single_page( $content ) {
	if ( is_single() && 'link' === get_post_format() ) {
		$url_arr = bimber_get_url_in_content( $content );

		$url   = $url_arr['url'];

		if ( $url ) {
			$url = apply_filters( 'bimber_link_permalink', $url, get_the_ID() );

			if ( bimber_link_is_opened_using_landing_page() ) {
				$query_arg = bimber_get_count_view_query_arg();

				$url = add_query_arg( $query_arg, 'no', $url );
			}

			global $bimber_link_data;
			$bimber_link_data = array(
				'href'    => $url,
				'target'  => bimber_link_is_opened_in_new_window() ? '_blank' : '_self',
				'classes' => array()
			);

			ob_start();
			get_template_part( 'template-parts/post/cta-visit-direct-link' );
			$out = ob_get_clean();

			$a_tag = $url_arr['a_tag'];

			// Replace <a> tag with our template.
			if ( $a_tag ) {
				$content = str_replace( $a_tag, $out, $content );
			} else {
				$content = str_replace( $url, $out, $content );
			}
		}
	}

	return $content;
}

/**
 * Remove a link from the post excerpt
 *
 * @param string $post_excerpt      Post excerpt.
 *
 * @return string
 */
function bimber_remove_link_from_excerpt( $post_excerpt ) {
	if ( 'link' === get_post_format() ) {
		$url_arr = bimber_get_url_in_content( get_the_content() );
		$has_url = $url_arr['url'];

		if ( $has_url ) {
			// Url is sticked to the text.
			$post_excerpt = str_replace( $has_url, '', $post_excerpt );

			// Url is separated from text with a space.
			$post_excerpt = str_replace( $has_url . ' ', '', $post_excerpt );
		}
	}

	return $post_excerpt;
}

/**
 * Hide the excerpt more link for the Link format
 *
 * @param bool $show    If show or not the excerpt more link.
 *
 * @return bool
 */
function bimber_hide_link_excerpt_more_link( $show ) {
	if ( 'link' === get_post_format() ) {
		$show = false;
	}

	return $show;
}

/**
 * Change post permalink to the first url/link tag in the post content
 *
 * @param string $permalink     Post permalink.
 *
 * @return string
 */
function bimber_change_permalink_to_content_url( $permalink ) {
	if ( 'link' === get_post_format() && ! bimber_link_has_single_page() ) {
		$content = get_the_content();
		$url_arr = bimber_get_url_in_content( $content );
		$has_url = $url_arr['url'];

		if ( $has_url ) {
			$permalink = apply_filters( 'bimber_link_permalink', $has_url, get_the_ID() );
		}
	}

	return $permalink;
}

/**
 * Change link url to the landing page
 *
 * @param string $url       Post url.
 * @param int    $post_id   Post id.
 *
 * @return string
 */
function bimber_use_link_landing_page_permalink( $url, $post_id ) {
	if ( ! bimber_link_is_opened_using_landing_page() ) {
		return $url;
	}

	$landing_page_id = bimber_get_link_landing_page_id();

	if ( $landing_page_id > 0 ) {
		$url = get_permalink( $landing_page_id );

		$encoded_args = base64_encode( json_encode( array( 'p' => $post_id ) ) );

		$query_arg_id = bimber_get_link_query_arg_id();

		$url = add_query_arg( array(
			$query_arg_id => $encoded_args,
		), $url );
	}

	return $url;
}

/**
 * Change title params for the Link post
 *
 * @param array $data       Title params.
 *
 * @return array
 */
function bimber_link_entry_title_data( $data ) {
	if ( 'link' !== get_post_format() ) {
		return $data;
	}

	$content = get_the_content();
	$url_arr = bimber_get_url_in_content( $content );
	$has_url = $url_arr['url'];

	// Proceed only if url exists.
	if ( ! $has_url ) {
		return $data;
	}

	if ( ! bimber_link_has_single_page() ) {
		$data['permalink']  = apply_filters( 'bimber_link_permalink', $has_url, get_the_ID() );
		$before             = $data['before'];

		// Open links method.
		if ( bimber_link_is_opened_in_new_window() ) {
			$before = str_replace( 'href="%1$s"', 'href="%1$s" target="_blank" rel="nofollow"', $before );
		} else {
			$before = str_replace( 'href="%1$s"', 'href="%1$s" rel="nofollow"', $before );
		}

		if ( in_array( bimber_get_link_open_method(), array( 'new_window', 'same_window' ) ) ) {
			if ( false !== strpos( $before, 'class="' ) ) {
				$before = str_replace( 'class="', 'class="bimber-count-view ', $before );
			} else {
				$before = str_replace( '<a', '<a class="bimber-count-view" ', $before );
			}
		}

		$data['before'] = $before;
	}

	if ( bimber_show_link_domain() ) {
		$data['after'] = sprintf( ' <small class="entry-domain g1-meta">(%s)</small>', parse_url( $has_url, PHP_URL_HOST ) ) . $data['after'];
	}

	return $data;
}

/**
 * Change featured media params for the Link post
 *
 * @param array $data
 *
 * @return array
 */
function bimber_link_entry_featured_media_link_data( $data, $post ) {
	if ( 'link' === get_post_format( $post ) && ! bimber_link_has_single_page() ) {
		if ( bimber_link_is_opened_in_new_window() ) {
			$data['target'] = '_blank';
		}

		if ( in_array( bimber_get_link_open_method(), array( 'new_window', 'same_window' ) ) ) {
			$data['classes'][] = 'bimber-count-view';
		}
	}

	return $data;
}

function bimber_link_written_by_author_tpl( $tpl ) {
	if ( 'link' === get_post_format() ) {
		$tpl = __( 'Posted by <a href="%s"><span itemprop="name" >%s</span></a>', 'bimber' );
	}

	return $tpl;
}

/**
 * Return id of query url arg
 *
 * @return string
 */
function bimber_get_link_query_arg_id() {
	return apply_filters( 'bimber_link_query_arg_id', 'd' );
}

/**
 * Return link landing page id (0 is not set)
 *
 * @return int
 */
function bimber_get_link_landing_page_id() {
	return (int) bimber_get_theme_option( 'post', 'link_landing_page' );
}

/**
 * Check whether the current page is the landing page for a link
 *
 * @return bool
 */
function bimber_is_link_landing_page() {
	$landing_page_id = bimber_get_link_landing_page_id();

	if ( $landing_page_id > 0 && is_page() ) {
		$page = get_post();

		return $page->ID === $landing_page_id;
	}

	return false;
}

/**
 * Return time (in seconds) after link redirection will start
 *
 * @return int
 */
function bimber_get_link_redirection_delay() {
	return (int) bimber_get_theme_option( 'post', 'link_redirection_delay' );
}

/**
 * Return link landing page content
 *
 * @return string
 */
function bimber_get_link_landing_page_content() {
	global $bimber_link_data;

	$query_arg_id = bimber_get_link_query_arg_id();

	$link_data = bimber_htmlspecialchars( filter_input( INPUT_GET, $query_arg_id ) );

	// Missing arg, skip.
	if ( empty( $link_data ) ) {
		return '';
	}

	$link_args = json_decode( base64_decode( $link_data ), true );

	// Invalid args, skip.
	if ( ! isset( $link_args['p'] ) ) {
		return '';
	}

	$delay      = bimber_get_link_redirection_delay();
	$target_url = bimber_get_link_target_url( $link_args['p'] );

	$count_view = true;

	$query_arg = bimber_get_count_view_query_arg();

	if ( 'no' === filter_input( INPUT_GET, $query_arg ) ) {
		$count_view = false;
	}

	$bimber_link_data = array(
		'delay'      => $delay,
		'target_url' => $target_url,
		'post_id'    => $link_args['p'],
		'count_view' => $count_view,
	);

	ob_start();
	get_template_part( 'template-parts/post/link-landing-page' );
	$html = ob_get_clean();

	return $html;
}

/**
 * Return link format target url
 *
 * @param int $post     Post id.
 *
 * @return string
 */
function bimber_get_link_target_url( $post = 0 ) {
	$post = get_post( $post );

	$url_arr = bimber_get_url_in_content( $post->post_content );

	$url = $url_arr['url'] ? $url_arr['url'] : get_permalink();

	return $url;
}

/**
 * Check whether the link single page is accessible for users
 *
 * @return string
 */
function bimber_link_has_single_page() {
	return 'standard' === bimber_get_theme_option( 'post_link', 'single_page' );
}

/**
 * Returns link open method name
 *
 * @return string
 */
function bimber_get_link_open_method() {
	return bimber_get_theme_option( 'post_link', 'open_method' );
}

/**
 * Check whether the link post format is opened in a new window
 *
 * @return bool
 */
function bimber_link_is_opened_in_new_window() {
	$is_new_window   = 'new_window' === bimber_get_link_open_method();
	$is_landing_page = bimber_link_is_opened_using_landing_page();

	return $is_new_window || $is_landing_page;
}

/**
 * Check whether the link post format is opened in the same window
 *
 * @return bool
 */
function bimber_link_is_opened_in_the_same_window() {
	return 'same_window' === bimber_get_link_open_method();
}

/**
 * Check whether the link post format is opened unsing landing page
 *
 * @return bool
 */
function bimber_link_is_opened_using_landing_page() {
	return 'landing_page' === bimber_get_link_open_method();
}

/**
 * Extract and return the first URL and wrapping <a> tag (if presents) from passed content.
 * According to the WordPress codex, the first URL might be:
 * - the first <a href=""> tag in the post content
 * - if the post consists only of an URL, then that will be the URL
 *
 * @param string $content       A string which might contain an URL.
 *
 * @return array                The found URL and related <a> tag if presents.
 */
function bimber_get_url_in_content( $content ) {
	$ret = array(
		'url'   => false,
		'a_tag' => false,
	);

	if ( empty( $content ) ) {
		return $ret;
	}

	// Find first <a> tag in the content.
	// Original PHP code by Chirp Internet: www.chirp.com.au.
	if ( preg_match( '/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU', $content, $matches ) ) {
		$ret['a_tag'] = $matches[0];
		$ret['url']   = esc_url_raw( $matches[2] );
	}

	// Check if post consists only of an URL.
	if ( ! $ret['url'] ) {
		$raw_content = strip_tags( $content );

		if ( 0 === strpos( $raw_content, 'http' ) ) {
			$parts = preg_split( '/\s/', $raw_content );

			if ( ! empty( $parts ) ) {
				$ret['url'] = $parts[0];
			}
		}
	}

	return $ret;
}

function bimber_show_link_domain() {
	return (bool) bimber_get_theme_option( 'post_link', 'show_domain' );
}
