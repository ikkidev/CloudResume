<?php
/**
 * Front attach sections functions
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

// ---------------------
// Above main collection
// ---------------------

/**
 * Fire action sections can use to register
 */
function bimber_above_collection_attach_sections() {
	do_action( 'bimber_above_collection_sections' );
}

/**
 * Set final elements order above the Main collection
 */
function bimber_above_collection_set_elements_order() {
	if ( is_paged() && apply_filters( 'bimber_hide_sections_above_collection_on_paged', true ) ) {
		return;
	}

	//add_action( 'bimber_above_collection_sections', 'bimber_render_podcast_above_collection',           bimber_get_theme_option( 'above_collection', 'podcast_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_newsletter_before_collection',        bimber_get_theme_option( 'above_collection', 'newsletter_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_patreon_above_collection',           bimber_get_theme_option( 'above_collection', 'patreon_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_instagram_above_collection',         bimber_get_theme_option( 'above_collection', 'instagram_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_links_above_collection',             bimber_get_theme_option( 'above_collection', 'links_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_promoted_products_above_collection', bimber_get_theme_option( 'above_collection', 'promoted_products_order' ), 1 );
	add_action( 'bimber_above_collection_sections', 'bimber_render_promoted_product_above_collection',  bimber_get_theme_option( 'above_collection', 'promoted_product_order' ), 1 );
}

function bimber_render_podcast_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'podcast', $position ) ) {
		global $bimber_podcast_section_positon;
		$bimber_podcast_section_positon = $position;

		get_template_part( 'template-parts/podcast/podcast', 'before-main-collection' );
	}
}

function bimber_render_newsletter_before_collection() {
	$position = 'before_collection';

	if ( bimber_get_theme_option( 'newsletter', $position ) ) {
		global $bimber_newsletter_section_positon;
		$bimber_newsletter_section_positon = $position;

		get_template_part( 'template-parts/newsletter/newsletter', 'before-main-collection' );
	}
}

function bimber_render_patreon_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'patreon', $position ) ) {
		if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
			global $bimber_patreon_section_positon;
			$bimber_patreon_section_positon = $position;

			get_template_part( 'template-parts/patreon/patreon', 'before-main-collection' );
		}
	}
}

function bimber_render_instagram_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'instagram', $position ) ) {
		global $bimber_instagram_section_type;
		global $bimber_instagram_section_positon;
		global $bimber_instagram_single_row;
		$bimber_instagram_section_positon = $position;
		$bimber_instagram_section_type    = 'expanded';
		$bimber_instagram_single_row = false;

		get_template_part( 'template-parts/instagram/instagram', 'before-main-collection' );
	}
}

function bimber_render_links_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'links', $position ) ) {
		global $bimber_links_section_positon;
		$bimber_links_section_positon = $position;

		get_template_part( 'template-parts/links/section', 'boxed' );
	}
}

function bimber_render_promoted_products_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'promoted_products', $position ) ) {
		global $bimber_promoted_products_section_positon;
		$bimber_promoted_products_section_positon = $position;

		get_template_part( 'template-parts/promoted-products/section', 'boxed' );
	}
}

function bimber_render_promoted_product_above_collection() {
	$position = 'above_collection';

	if ( bimber_get_theme_option( 'promoted_product', $position ) ) {
		// Hide on woo pages.
		if ( function_exists( 'is_woocommerce' ) && ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) ) {
			global $bimber_promoted_product_section_positon;
			$bimber_promoted_product_section_positon = $position;

			get_template_part( 'template-parts/promoted-product/section', 'boxed' );
		}
	}
}


// ------------
// Above footer
// ------------

/**
 * Fire action sections can use to register
 */
function bimber_above_footer_attach_sections() {
	do_action( 'bimber_above_footer_sections', 'above_footer' );
}

/**
 * Set final elements order above the footer
 */
function bimber_above_footer_set_elements_order() {
	add_action( 'bimber_above_footer_sections', 'bimber_render_newsletter_before_footer',        bimber_get_theme_option( 'footer', 'newsletter_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_links_above_footer',             bimber_get_theme_option( 'footer', 'links_order' ), 1 );
	//add_action( 'bimber_above_footer_sections', 'bimber_render_podcast_above_footer',           bimber_get_theme_option( 'footer', 'podcast_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_instagram_above_footer',         bimber_get_theme_option( 'footer', 'instagram_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_social_above_footer',            bimber_get_theme_option( 'footer', 'social_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_patreon_above_footer',           bimber_get_theme_option( 'footer', 'patreon_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_promoted_product_above_footer',  bimber_get_theme_option( 'footer', 'promoted_product_order' ), 1 );
	add_action( 'bimber_above_footer_sections', 'bimber_render_promoted_products_above_footer', bimber_get_theme_option( 'footer', 'promoted_products_order' ), 1 );
}

function bimber_render_newsletter_before_footer() {
	$position = 'before_footer';

	if ( bimber_get_theme_option( 'newsletter', $position ) ) {
		get_template_part( 'template-parts/newsletter/newsletter', 'before-footer' );
	}
}

function bimber_render_links_above_footer() {
	$position   = 'above_footer';

	if ( bimber_get_theme_option( 'links', $position ) ) {
		global $bimber_links_section_positon;
		$bimber_links_section_positon = $position;

		get_template_part( 'template-parts/links/section', 'full' );
	}
}

function bimber_render_podcast_above_footer() {
	$position   = 'above_footer';

	if ( bimber_get_theme_option( 'podcast', $position ) ) {
		global $bimber_podcast_section_positon;
		$bimber_podcast_section_positon = $position;

		get_template_part( 'template-parts/podcast/podcast', 'before-footer' );
	}
}

function bimber_render_instagram_above_footer() {
	$position   = 'above_footer';

	if ( bimber_get_theme_option( 'instagram', $position ) ) {
		global $bimber_instagram_section_type;
		global $bimber_instagram_section_positon;
		global $bimber_instagram_single_row;

		$bimber_instagram_section_positon = $position;
		$bimber_instagram_section_type    = 'expanded';
		$bimber_instagram_single_row = false;

		get_template_part( 'template-parts/instagram/instagram', 'before-footer' );
	}
}

function bimber_render_social_above_footer() {
	$position   = 'above_footer';

	if ( bimber_get_theme_option( 'social', $position ) ) {
		global $bimber_social_section_positon;
		$bimber_social_section_positon = $position;

		get_template_part( 'template-parts/social-icons/section', 'full' );
	}
}

function bimber_render_patreon_above_footer() {
	$position = 'above_footer';

	if ( bimber_get_theme_option( 'patreon', $position ) ) {
		if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
			global $bimber_patreon_section_positon;
			$bimber_patreon_section_positon = $position;

			get_template_part( 'template-parts/patreon/patreon', 'before-footer' );
		}
	}
}

function bimber_render_promoted_product_above_footer() {
	$position = 'above_footer';

	if ( bimber_get_theme_option( 'promoted_product', $position ) ) {
		// Hide on woo pages.
		if ( function_exists( 'is_woocommerce' ) && ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) ) {
			global $bimber_promoted_product_section_positon;
			$bimber_promoted_product_section_positon = $position;

			get_template_part( 'template-parts/promoted-product/section', 'boxed' );
		}
	}
}

function bimber_render_promoted_products_above_footer() {
	$position = 'above_footer';

	if ( bimber_get_theme_option( 'promoted_products', $position ) ) {
		// Hide on woo pages.
		if ( function_exists( 'is_woocommerce' ) && ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) ) {
			global $bimber_promoted_products_section_positon;
			$bimber_promoted_products_section_positon = $position;

			get_template_part( 'template-parts/promoted-products/section', 'boxed' );
		}
	}
}
