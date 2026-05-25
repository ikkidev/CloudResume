<?php
/**
 * Migation from QUADS
 *
 * @package AdAce.
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'init', 'adace_handle_migration' );

/**
 * Check the flag and start migation if needed
 */
function adace_handle_migration() {
	if ( adace_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) ) {
		$migration = get_option( 'adace_quads_migration', false );
		if ( ! $migration ) {
			adace_migrate_from_quads();
			update_option( 'adace_quads_migration', true );
		}
	}
}

/**
 * Migrate from QUADS
 */
function adace_migrate_from_quads() {
	$quads_settings = get_option( 'quads_settings' );
	if ( ! is_array( $quads_settings ) || ! isset( $quads_settings['ads'] ) ) {
		return;
	}
	$quads_ads 		= $quads_settings['ads'];

	if ( isset( $quads_settings['maxads'] ) ) {
		$max_ads = $quads_settings['maxads'];
		if ( $max_ads < 21 ) {
			update_option( 'adace_general_ad_limit', $max_ads );
		}
	}

	$imported_ads = array();
	foreach ( $quads_ads as $key => $value ) {
		$type 			= $value['ad_type'];
		$label 			= ! empty( $value['label'] ) ? $value['label'] : $key;
		$code 			= $value['code'];
		$adsense_slot 	= $value['g_data_ad_slot'];
		$adsense_pub 	= $value['g_data_ad_client'];

		if ( 'plain_text' === $type && ! empty( $code ) ) {
			$post_id = wp_insert_post(array(
				'post_title' 	=> $label,
				'post_type'  	=> 'adace-ad',
				'post_status'	=> 'publish',
			));
			$general 	= adace_get_default_general_ad_settings();
			$adsense 	= adace_get_default_adsense_ad_settings();
			$custom 	= adace_get_default_custom_ad_settings();

			$general['adace_ad_type'] 	= 'custom';
			$custom['adace_ad_content'] = $code;
			update_post_meta( $post_id , 'adace_general', $general );
			update_post_meta( $post_id , 'adace_adsense', $adsense );
			update_post_meta( $post_id , 'adace_custom', $custom );
		}

		if ( 'adsense' === $type && ! empty( $adsense_slot ) && ! empty( $adsense_pub ) ) {
			$post_id = wp_insert_post(array(
				'post_title' 	=> $label,
				'post_type'  	=> 'adace-ad',
				'post_status'	=> 'publish',
			));
			$general 	= adace_get_default_general_ad_settings();
			$adsense 	= adace_get_default_adsense_ad_settings();
			$custom 	= adace_get_default_custom_ad_settings();

			$general['adace_ad_type'] 			= 'adsense';
			$adsense['adace_adsense_pub']		= $value['g_data_ad_client'];
			$adsense['adace_adsense_slot']		= $value['g_data_ad_slot'];
			$adsense['adace_adsense_type']		= 'normal' === $value['adsense_type'] ? 'fixed' : 'responsive';
			$adsense['adace_adsense_width']		= (int) $value['g_data_ad_width'];
			$adsense['adace_adsense_height']	= (int) $value['g_data_ad_width'];
			update_post_meta( $post_id , 'adace_general', $general );
			update_post_meta( $post_id , 'adace_adsense', $adsense );
			update_post_meta( $post_id , 'adace_custom', $custom );
		}

		if ( isset( $post_id ) ) {
			$imported_ads[ $key ] = $post_id;
		}
	}

	if ( ! is_array( $quads_settings ) || ! isset( $quads_settings['pos1'] ) || ! isset( $quads_settings['location_settings'] ) ) {
		return;
	}
	adace_import_quads_location( $quads_settings['pos1'], 'adace-before-content', $imported_ads );
	adace_import_quads_location( $quads_settings['pos2'], 'adace-middle-content', $imported_ads );
	adace_import_quads_location( $quads_settings['pos3'], 'adace-after-content', $imported_ads );
	adace_import_quads_location( $quads_settings['pos4'], 'adace-after-more', $imported_ads );
	adace_import_quads_location( $quads_settings['pos5'], 'adace-before-last-paragraph', $imported_ads );
	adace_import_quads_location( $quads_settings['pos6'], 'adace-after-paragraph', $imported_ads );
	adace_import_quads_location( $quads_settings['pos7'], 'adace-after-paragraph-2', $imported_ads );
	adace_import_quads_location( $quads_settings['pos8'], 'adace-after-paragraph-3', $imported_ads );
	adace_import_quads_location( $quads_settings['pos9'], 'adace-after-image', $imported_ads );

	$quads_slots 	= $quads_settings['location_settings'];
	foreach ( $quads_slots as $key => $value ) {
		$option_name = 'adace_slot_' . $key . '_options';
		$option = get_option( $option_name );
		$ad = false;
		if ( isset( $value['status'] ) ) {
			if ( ! $value['ad'] ) {
				$ad = -1;
			} else {
				$ad = 'ad' . $value['ad'];
				$ad = $imported_ads[ $ad ];
			}
		}
		if ( $ad ) { $option['ad_id'] = $ad; }
		update_option( $option_name, $option );
	}
}

/**
 * Import Quads default location
 *
 * @param array  $location        QUADS location.
 * @param string $adace_location  AdAce location slug.
 * @param array  $imported_ads    Imported Ads.
 * @return void
 */
function adace_import_quads_location( $location, $adace_location, $imported_ads ) {
	$ad = false;

	$ads = false;
	$rnd = false;
	$num = false;
	foreach ( $location as $key => $value ) {
		if ( strpos( $key, 'Ads') > -1  ) {
			$ads = $value;
		}
		if ( strpos( $key, 'Rnd') > -1 ) {
			$rnd = $value;
		}
		if ( strpos( $key, 'Nup') > -1 ) {
			$num = $value;
		}
	}

	if ( $ads ) {
		if ( 0 === (int) $rnd ) {
			$ad = -1;
		} else {
			$ad = 'ad' . $rnd;
			$ad = $imported_ads[ $ad ];
		}
	}

	$option_name = 'adace_slot_' . $adace_location . '_options';
	$option = get_option( $option_name );
	if ( $ad ) { $option['ad_id'] = $ad; }
	if ( $num ) { $option['after_x_paragraph'] = $num; }
	if ( $num ) { $option['after_x_image'] = $num; }
	update_option( $option_name, $option );
}
