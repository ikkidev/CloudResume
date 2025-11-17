<?php
/**
 * The Template Part for displaying promoted products.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Make sure that we have AdAce on board. If not leave!
if ( ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
	return;
}

$promoted_products_type = bimber_get_theme_option( 'promoted_products', 'type' );

get_template_part( 'template-parts/promoted-products/base-standard', $promoted_products_type );
