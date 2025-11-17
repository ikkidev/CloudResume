<?php
/**
 * Download Monitor plugin functions
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

add_filter( 'dlm_download_get_the_download_link',   'bimber_dm_change_download_link', 10 ,3 );
add_filter( 'the_content',                          'bimber_dm_download_page_content' );
add_action( 'after_setup_theme',                    'bimber_dm_register_ad_section', 20 );
add_action( 'after_setup_theme', 	                'bimber_dm_register_ad_slot' );

/**
 * Change download url to the landing page
 *
 * @param string   $url             Download url.
 * @param stdClass $dm_object       DM object.
 * @param string   $version         DM version.
 *
 * @return string
 */
function bimber_dm_change_download_link( $url, $dm_object ) {
	if ( 'download_page' !== bimber_dm_get_download_method() ) {
		return $url;
	}

	$download_page_id = bimber_dm_get_download_page_id();

	if ( $download_page_id > 0 ) {
		$url = get_permalink( $download_page_id );

		$encoded_args = base64_encode( json_encode( array( 'download_id' => $dm_object->get_id() ) ) );

		$query_arg_id = bimber_dm_get_query_arg_id();

		$url = add_query_arg( array(
			$query_arg_id => $encoded_args,
		), $url );
	}

	return $url;
}

/**
 * Add the download page content
 *
 * @param string $content       Page content.
 *
 * @return string
 */
function bimber_dm_download_page_content( $content ) {
	if ( ! bimber_dm_is_download_page() ) {
		return $content;
	}

	$download_page_content = bimber_dm_get_download_page_content();

	if ( empty( $download_page_content ) ) {
		return $content;
	}

	$download_page_content_tag = apply_filters( 'bimber_download_page_content_tag', '<!--bimber-download-page-content-->' );

	if ( false !== strpos( $content, $download_page_content_tag ) ) {
		$content = str_replace( $download_page_content_tag, $download_page_content, $content );
	} else {
		// Append to the content.
		$content .= $download_page_content;
	}

	return $content;
}

/**
 * Return download page content
 *
 * @return string
 */
function bimber_dm_get_download_page_content() {
	global $bimber_download_data;

	$query_arg_id = bimber_dm_get_query_arg_id();

	$download_data = bimber_htmlspecialchars( filter_input( INPUT_GET, $query_arg_id ) );

	// Missing arg, skip.
	if ( empty( $download_data ) ) {
		return '';
	}

	$download_args = json_decode( base64_decode( $download_data ), true );

	// Invalid args, skip.
	if ( ! isset( $download_args['download_id'] ) ) {
		return '';
	}

	$delay      = bimber_dm_get_download_delay();
	$target_url = bimber_dm_get_download_target_url( $download_args['download_id'] );

	$bimber_download_data = array(
		'delay'         => $delay,
		'target_url'    => $target_url,
		'download_id'   => $download_args['download_id'],
	);

	ob_start();
	get_template_part( 'template-parts/download-monitor/download-page' );
	$html = ob_get_clean();

	return $html;
}

/**
 * Return download target url
 *
 * @param int $post     Post id.
 *
 * @return string
 */
function bimber_dm_get_download_target_url( $download_id ) {
	$url = '';

	/** @var DLM_Download $download */
	$download = null;

	if ( $download_id > 0 ) {
		try {
			$download = download_monitor()->service( 'download_repository' )->retrieve_single( $download_id );
		}catch (Exception $e) {
			// download not found
		}
	}

	if ( null != $download ) {
		remove_filter( 'dlm_download_get_the_download_link',   'bimber_dm_change_download_link', 10 );

		$url = $download->get_the_download_link();

		add_filter( 'dlm_download_get_the_download_link',   'bimber_dm_change_download_link', 10 ,3 );
	}

	return $url;
}

/**
 * Register custom ad section
 */
function bimber_dm_register_ad_section() {
	if ( ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
		return;
	}

	adace_register_ad_section( 'dm', __( 'Download Monitor', 'mace' ) );
}

/**
 * Register custom ad slot
 */
function bimber_dm_register_ad_slot() {
	if ( ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
		return;
	}

	adace_register_ad_slot(
		array(
			'id'    => 'bimber_dm_download_page',
			'name' => esc_html__( 'On download page', 'bimber' ),
			'section' => 'dm',
		) );
}

/**
 * Check whether the current page is the download page
 *
 * @return bool
 */
function bimber_dm_is_download_page() {
	$download_page_id = bimber_dm_get_download_page_id();

	if ( $download_page_id > 0 && is_page() ) {
		$page = get_post();

		return $page->ID === $download_page_id;
	}

	return false;
}

/**
 * Return download method
 *
 * @return string
 */
function bimber_dm_get_download_method() {
	return bimber_get_theme_option( 'dm', 'download_method' );
}

/**
 * Return download page id (0 is not set)
 *
 * @return int
 */
function bimber_dm_get_download_page_id() {
	return (int) bimber_get_theme_option( 'dm', 'download_page' );
}

/**
 * Return time (in seconds) after download will start
 *
 * @return int
 */
function bimber_dm_get_download_delay() {
	return (int) bimber_get_theme_option( 'dm', 'download_delay' );
}

/**
 * Return id of query url arg
 *
 * @return string
 */
function bimber_dm_get_query_arg_id() {
	return apply_filters( 'bimber_dm_query_arg_id', 'd' );
}
