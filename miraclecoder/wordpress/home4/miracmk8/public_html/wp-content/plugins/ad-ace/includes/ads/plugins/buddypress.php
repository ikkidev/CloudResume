<?php
/**
 * BuddyPress Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_disable_ads_per_post', 'adace_buddypress_disable_ads_per_page', 10, 2 );

/**
 * Per post ad disabling support for BP pages.
 *
 * @param bool $disable  Whether to disable.
 * @param int  $slot_id  Ad slot id.
 * @return bool
 */
function adace_buddypress_disable_ads_per_page( $disable, $slot_id ) {
	if ( ! is_buddypress() ) {
		return $disable;
	}

	// we cut content slots, as they usually create a horrible mess.
	$disable_slots = array( adace_get_after_content_slot_id(), adace_get_after_image_slot_id(), adace_get_after_more_slot_id(), adace_get_after_paragraph_2_slot_id(), adace_get_after_paragraph_3_slot_id(), adace_get_after_paragraph_slot_id(), adace_get_before_content_slot_id(), adace_get_before_last_paragraph_slot_id(),  adace_get_middle_content_slot_id() );
	if ( in_array( $slot_id, $disable_slots, true ) ) {
		return true;
	}

	// Get the id of current BP page by URL is the most reasonable way AFAIK.
	$bp_pages = get_option( 'bp-pages' );
	$current_url = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	foreach ( $bp_pages as $page => $id ) {
		$page_url = get_permalink( $id );
		if ( strpos( $current_url, $page_url ) > -1 ) {
			$page_id = $id;
		}
	}

	if ( isset( $page_id ) ) {
		$disable_array = get_post_meta( $page_id, 'adace_disable', true );
		if ( is_array( $disable_array ) ) {
			$disable_ad_all_slots	= $disable_array['adace_disable_all_slots'];
			$disable_ad_slots  		= $disable_array['adace_disable_slots'];
			$disable_ad_widgets 	= $disable_array['adace_disable_widgets'];
			$disable_ad_shortcodes 	= $disable_array['adace_disable_shortcodes'];

			if ( strpos( $slot_id, 'shortcode' ) !== false ) {
				$disable = $disable_ad_shortcodes;
			} elseif (  strpos( $slot_id, 'widget' ) !== false ) {
				$disable = $disable_ad_widgets;
			} else {
				if ( isset( $disable_ad_slots[ $slot_id ] ) ) {
					$disable = $disable_ad_slots[ $slot_id ];
				}
				if ( $disable_ad_all_slots ) {
					$disable = true;
				}
			}
		}
	}
	return $disable;
}
