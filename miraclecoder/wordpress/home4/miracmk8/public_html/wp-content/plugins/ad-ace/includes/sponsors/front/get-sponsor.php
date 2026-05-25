<?php
/**
 * Front Sponsor Functions
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

 /**
  * Get compact sponsor box.
  */
function adace_get_sponsor_box_compact() {
	ob_start();
		adace_get_template_part( 'sponsor', 'compact' );
	$output = ob_get_clean();
	return $output;
}

 /**
  * Get full sponsor box.
  */
function adace_get_sponsor_box_full() {
	ob_start();
		adace_get_template_part( 'sponsor', 'full' );
	$output = ob_get_clean();
	return $output;
}

add_filter( 'the_content', 'adace_sponsor_before_post_inject' );
/**
 * Inject Before Content Sponsor into content
 *
 * @param string $content Post content.
 */
function adace_sponsor_before_post_inject( $content ) {
	$template = get_option( 'adace_sponsor_before_post', adace_options_get_defaults( 'adace_sponsor_before_post' ) );
	if ( 'compact' === $template ) {
		$content = adace_get_sponsor_box_compact() . $content;
	}
	if ( 'full' === $template ) {
		$content = adace_get_sponsor_box_full() . $content;
	}
	return $content;
}

add_filter( 'the_content', 'adace_sponsor_after_post_inject' );
/**
 * Inject Before Content Sponsor into content
 *
 * @param string $content Post content.
 */
function adace_sponsor_after_post_inject( $content ) {
	$template = get_option( 'adace_sponsor_after_post', adace_options_get_defaults( 'adace_sponsor_after_post' ) );
	if ( 'compact' === $template ) {
		$content .= adace_get_sponsor_box_compact();
	}
	if ( 'full' === $template ) {
		$content .= adace_get_sponsor_box_full();
	}
	return $content;
}
