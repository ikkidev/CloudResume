<?php
/**
 * Easy Google Fonts plugin functions
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
 * Disable "Default Typography" panel in WP Customizer
 *
 * @param array $tabs       Customizer panels.
 *
 * @return array
 */
function bimber_egf_disable_default_typography_tab( $tabs ) {
	if ( isset( $tabs['typography'] ) ) {
		unset( $tabs['typography'] );
	}

	return $tabs;
}

/**
 * Register theme's font selectors
 *
 * @param array $options            Font options.
 *
 * @return array
 */
function bimber_egf_register_theme_font_options( $options ) {
	$options[ 'bimber_body' ] = array(
		'name'        => 'bimber_body',
		'title'       => __( 'Body text', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'body, input, select, textarea',
			'font_size_min_range'   => 13,
			'font_size_max_range'   => 16,
		),
	);

	$options[ 'bimber_giga' ] = array(
		'name'        => 'bimber_giga',
		'title'       => __( 'Giga Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-giga',
		),
	);

	$options[ 'bimber_mega' ] = array(
		'name'        => 'bimber_mega',
		'title'       => __( 'Mega Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-mega',
		),
	);

	$options[ 'bimber_alpha' ] = array(
		'name'        => 'bimber_alpha',
		'title'       => __( 'H1 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h1, .g1-alpha',
		),
	);

	$options[ 'bimber_beta' ] = array(
		'name'        => 'bimber_beta',
		'title'       => __( 'H2 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h2, .g1-beta',
		),
	);

	$options[ 'bimber_gamma' ] = array(
		'name'        => 'bimber_gamma',
		'title'       => __( 'H3 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h3, .g1-gamma',
		),
	);

	$options[ 'bimber_delta' ] = array(
		'name'        => 'bimber_delta',
		'title'       => __( 'H4 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h4, .g1-delta',
		),
	);

	$options[ 'bimber_epsilon' ] = array(
		'name'        => 'bimber_epsilon',
		'title'       => __( 'H5 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h5, .g1-epsilon',
		),
	);

	$options[ 'bimber_zeta' ] = array(
		'name'        => 'bimber_zeta',
		'title'       => __( 'H6 Heading', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'h6, .g1-zeta',
		),
	);

	$options[ 'bimber_gamma_3rd' ] = array(
		'name'        => 'bimber_gamma_3rd',
		'title'       => __( 'Subheading H3', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-gamma-3rd',
		),
	);

	$options[ 'bimber_delta_3rd' ] = array(
		'name'        => 'bimber_delta_3rd',
		'title'       => __( 'Subheading H4', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-delta-3rd',
		),
	);



	$options[ 'bimber_meta' ] = array(
		'name'        => 'bimber_meta',
		'title'       => __( 'Metatext', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-meta',
		),
	);


	$options[ 'bimber_primary_nav' ] = array(
		'name'        => 'bimber_primary_nav',
		'title'       => __( 'Primary Navigation', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-primary-nav-menu > .menu-item > a',
		),
	);

	$options[ 'bimber_submenu' ] = array(
		'name'        => 'bimber_submenu',
		'title'       => __( 'Submenus', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.sub-menu .menu-item > a',
		),
	);



	$options[ 'bimber_button' ] = array(
		'name'        => 'bimber_button',
		'title'       => __( 'Buttons', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'input[type=submit], input[type=reset], input[type=button], button, .g1-button, .g1-arrow',
		),
	);

	$options[ 'bimber_tabs' ] = array(
		'name'        => 'bimber_tabs',
		'title'       => __( 'Tabs', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => '.g1-tab, .item-list-tabs a',
		),
	);

	$options[ 'bimber_blockquote' ] = array(
		'name'        => 'bimber_blockquote',
		'title'       => __( 'Blockquotes', 'bimber' ),
		'description' => '',
		'tab'         => 'theme-typography',
		'properties'  => array(
			'selector'     => 'blockquote',
		),
	);

	return $options;
}
