<?php
/**
 * Header Builder common functions
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

/**
 * Convert row index to letter for CSS class
 *
 * @param int $index  Index.
 * @return string
 */
function bimber_hb_index_to_letter( $index ) {
	$letters = array_combine( range( 1,26 ), range( 'a', 'z' ) );
	return $letters[ $index ];
}


/**
 * Echo/return the classes for the element based on settings from customizer (eg. icon size)
 *
 * @param string $element  Element slug
 * @param boolean $echo    Echo?
 */
function bimber_hb_get_element_class_from_settings( $element, $echo = true) {
	$classes = '';
	if ( apply_filters( 'bimber_hb_apply_size_to_element', true ) ) {
		$size = bimber_get_theme_option( 'header_builder', 'element_size_' . $element );
		if ( 'standard' !== $size ) {
			$classes .= $size . ' ';
		}
	}
	if ( apply_filters( 'bimber_hb_apply_type_to_element', true ) ) {
		$type = bimber_get_theme_option( 'header_builder', 'element_type_' . $element );
		if ( 'standard' !== $type ) {
			$classes .= $type . ' ';
		}
	}

	if ( $echo ) {
		echo wp_kses_post( $classes );
	} else {
		return $classes;
	}
}

function bimber_hb_get_elements() {
	return apply_filters( 'bimber_hb_elements', array(
		'logo' => array(
			'label'		=> __( 'Logo', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section'	=> 'bimber_header_builder_section_elements_logo',
		),
		'mobile_logo' => array(
			'label'		=> __( 'Mobile Logo', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section'	=> 'bimber_header_builder_section_elements_mobile_logo',
		),
		'primary_menu' => array(
			'label'		=> __( 'Primary Nav', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'section' 	=> 'bimber_header_builder_section_elements_primary_nav',
		),
		'secondary_menu' => array(
			'label'		=> __( 'Secondary Nav', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'panel' 	=> 'nav_menus',
			'section' 	=> 'menu_locations',
			'highlight'	=> '#customize-control-nav_menu_locations-bimber_secondary_nav',
		),
		'mobile_menu' => array(
			'label'		=> __( 'Hamburger', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_hamburger',
		),
		'quick_nav' => array(
			'label'		=> __( 'Quick Nav', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'section' 	=> 'bimber_header_builder_section_elements_quick_nav',
		),
		'top' => array(
			'label'		=> __( 'Top X link', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_posts_global_section',
			'highlight'	=> '#customize-control-bimber_posts_top_in_menu',
		),
		'quick_nav_small' => array(
			'label'		=> __( 'Quick Nav (small)', 'bimber' ),
			'tabs'		=> array( 'normal' ),
			'section'	=> 'bimber_header_builder_section_elements_quick_nav',
		),
		'social_icons_full' => array(
			'label'		=> __( 'Social icons list', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'plugin'	=> 'g1-socials/g1-socials.php',
			'section' 	=> 'bimber_header_builder_section_elements_social_icons',
		),
		'social_icons_dropdown' => array(
			'label'		=> __( 'Social icons dropdown', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'plugin'	=> 'g1-socials/g1-socials.php',
			'section' 	=> 'bimber_header_builder_section_elements_social_icons',
		),
		'search_dropdown' => array(
			'label'		=> __( 'Search Dropdown', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_search',
		),
		'search' => array(
			'label'		=> __( 'Search Form', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'section' 	=> 'bimber_header_builder_section_elements_search',
		),
		'skin_dropdown' => array(
			'label'		=> __( 'Skin Switcher', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_skin',
		),
		'nsfw_dropdown' => array(
			'label'		=> __( 'NSFW Switcher', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_nsfw',
		),
		'user_menu' => array(
			'label'		=> __( 'User Dropdown', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_user',
		),
		'create_button' => array(
			'label'		=> __( 'Create Button', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'section' 	=> 'bimber_header_builder_section_elements_button',
			'plugin'	=> 'snax/snax.php',
		),
		'language_selector' => array(
			'label'		=> __( 'Language Selector', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile', 'canvas' ),
			'plugin'	=> 'sitepress-multilingual-cms/sitepress.php',
		),
		'cart' => array(
			'label'		=> __( 'Cart Dropdown', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'section' 	=> 'bimber_header_builder_section_elements_cart',
			'plugin'	=> 'woocommerce/woocommerce.php',
		),
		'ad_slot' => array(
			'label'		=> __( 'Ad Slot', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'plugin'	=> 'ad-ace/ad-ace.php',
		),
		'newsletter' => array(
			'label'		=> __( 'Newsletter Dropdown', 'bimber' ),
			'tabs'		=> array( 'normal', 'mobile' ),
			'plugin'	=> 'mailchimp-for-wp/mailchimp-for-wp.php',
			'section'	=> 'bimber_header_builder_section_elements_newsletter',
		),
	));
}

function bimber_hb_get_layouts() {
	$layouts = array();
	$layouts ['original'] = '{"normal":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["logo"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["quick_nav"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"on","cols":{"1":{"elements":["primary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"on","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['gag'] = '{"normal":{"1":{"letter":"a","style":"full","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"full","sticky":"on","cols":{"1":{"elements":["logo","primary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"full","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['05'] = '{"normal":{"1":{"letter":"a","style":"full","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"full","sticky":"on","cols":{"1":{"elements":["logo"],"align":"left","grow":"off"},"2":{"elements":["primary_menu"],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"full","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['smiley'] = '{"normal":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"on","cols":{"1":{"elements":["logo","primary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['07'] = '{"normal":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"on","cols":{"1":{"elements":["logo"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["primary_menu","social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['hardcore'] = '{"normal":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"on","cols":{"1":{"elements":["primary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["logo"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['06'] = '{"normal":{"1":{"letter":"a","style":"boxed","sticky":"off","cols":{"1":{"elements":["secondary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"on","cols":{"1":{"elements":["mobile_menu","top"],"align":"left","grow":"off"},"2":{"elements":["logo"],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":["quick_nav"],"align":"center","grow":"on"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';
	$layouts ['bunchy'] = '{"normal":{"1":{"letter":"a","style":"full","sticky":"off","cols":{"1":{"elements":["secondary_menu","quick_nav_small"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"full","sticky":"on","cols":{"1":{"elements":["logo","primary_menu"],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":["social_icons_dropdown","search_dropdown","user_menu","create_button","cart"],"align":"right","grow":"off"}},"icons":"standard","shadow":"on"},"3":{"letter":"c","style":"full","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"mobile":{"1":{"letter":"a","style":"boxed","sticky":"on","cols":{"1":{"0":"mobile_logo","elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"2":{"letter":"b","style":"boxed","sticky":"off","cols":{"1":{"elements":["mobile_menu"],"align":"left","grow":"off"},"2":{"elements":["mobile_logo"],"align":"center","grow":"on"},"3":{"elements":["user_menu"],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"},"3":{"letter":"c","style":"boxed","sticky":"off","cols":{"1":{"elements":[],"align":"left","grow":"off"},"2":{"elements":[],"align":"center","grow":"off"},"3":{"elements":[],"align":"right","grow":"off"}},"icons":"standard","shadow":"off"}},"canvas":{"1":{"cols":{"1":{"elements":["primary_menu","secondary_menu","quick_nav","social_icons_full","search","create_button","language_selector"],"grow":"off"}},"sticky":"off","shadow":"off"}}}';

	return apply_filters( 'bimber_hb_layouts', $layouts );
}


function bimber_hb_get_settings() {
	$settings = array();
	$settings['original'] = array(
		'header_builder_element_size_social_icons_full'        => 'standard',
	);
	$settings['bunchy'] = array(
		'header_quicknav_margin_bottom'        => '2',
		'header_quicknav_margin_top'        	  => '2',
	);
	return apply_filters( 'bimber_hb_settings', $settings );
}

/**
 * Returns the letter of the row with logo, false if logo not found.
 *
 * @return string
 */
function bimber_hb_get_row_with_logo() {
	$layouts = bimber_get_theme_option( 'header_builder', '' );
	foreach ( $layouts['normal'] as $row_index => $row ) :
		foreach ( $row['cols'] as $col_index => $col ) :
			if ( in_array( 'mobile_logo' ,$col['elements'], true ) ) {
				return $row['letter'];
			}
		endforeach;
	endforeach;
	return false;
}

/**
 * Returns the letter of the row with mobile logo, false if logo not found.
 *
 * @return string
 */
function bimber_hb_get_row_with_mobile_logo() {
	$layouts = bimber_get_theme_option( 'header_builder', '' );
	foreach ( $layouts['normal'] as $row_index => $row ) :
		foreach ( $row['cols'] as $col_index => $col ) :
			if ( in_array( 'mobile_logo' ,$col['elements'], true ) ) {
				return $row['letter'];
			}
		endforeach;
	endforeach;
	return false;
}
