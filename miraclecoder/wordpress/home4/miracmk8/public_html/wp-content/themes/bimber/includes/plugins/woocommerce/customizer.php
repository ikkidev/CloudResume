<?php
/**
 * WooCommerce Customizer integration
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

add_filter( 'bimber_customizer_defaults',       'bimber_wc_add_customizer_defaults' );
add_action( 'bimber_after_customize_register',  'bimber_wc_register_customizer_options', 10, 1 );


/**
 * Register plugin defaults
 *
 * @param array $defaults       Default values.
 *
 * @return array
 */
function bimber_wc_add_customizer_defaults( $defaults ) {
	$defaults['woocommerce_cart_visibility']        = 'always';
	$defaults['woocommerce_single_product_sidebar'] = 'hide';
	$defaults['woocommerce_product_link_target']    = '_self';
	$defaults['woocommerce_affiliate_link_target']  = '_blank';

	return $defaults;
}

/**
 * Add plugin panel
 *
 * @param WP_Customize_Manager $wp_customize        Customizer instance.
 */
function bimber_wc_register_customizer_options( $wp_customize ) {

	$defaults    = bimber_get_customizer_defaults();
	$option_name = bimber_get_theme_id();

	/**
	 * Sections
	 */

	$wp_customize->add_section( 'bimber_woocommerce_section', array(
		'title'    => __( 'Bimber Setup', 'bimber' ),
		'panel'    => 'woocommerce',
		'priority' => 1000,
	) );

	/**
	 * Controls
	 */

	// Hide cart.
	$wp_customize->add_setting( $option_name . '[woocommerce_cart_visibility]', array(
		'default'           => $defaults['woocommerce_cart_visibility'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_woocommerce_cart_visibility', array(
		'label'    => __( 'Show Cart in the Header', 'bimber' ),
		'section'  => 'bimber_woocommerce_section',
		'settings' => $option_name . '[woocommerce_cart_visibility]',
		'type'     => 'select',
		'choices'  => array(
			'always'			=> __( 'always', 'bimber' ),
			'on_woocommerce'	=> __( 'on WooCommerce pages', 'bimber' ),
			'none'				=> __( 'no', 'bimber' ),
		),
	) );

	// Single product sidebar.
	$wp_customize->add_setting( $option_name . '[woocommerce_single_product_sidebar]', array(
		'default'           => $defaults['woocommerce_single_product_sidebar'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_woocommerce_single_product_sidebar', array(
		'label'    => __( 'Show sidebar on single product', 'bimber' ),
		'section'  => 'bimber_woocommerce_section',
		'settings' => $option_name . '[woocommerce_single_product_sidebar]',
		'type'     => 'select',
		'choices'  => array(
			'show'			=> __( 'Show', 'bimber' ),
			'hide'			=> __( 'Hide', 'bimber' ),
		),
	) );

	// Open products in.
	$wp_customize->add_setting( $option_name . '[woocommerce_product_link_target]', array(
		'default'           => $defaults['woocommerce_product_link_target'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_woocommerce_product_link_target', array(
		'label'    => __( 'Open product links', 'bimber' ),
		'section'  => 'bimber_woocommerce_section',
		'settings' => $option_name . '[woocommerce_product_link_target]',
		'type'     => 'select',
		'choices'  => array(
			'_blank'		=> __( 'in a new window', 'bimber' ),
			'_self'			=> __( 'in the same window', 'bimber' ),
		),
	) );

	// Open affiliates in.
	$wp_customize->add_setting( $option_name . '[woocommerce_affiliate_link_target]', array(
		'default'           => $defaults['woocommerce_affiliate_link_target'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_woocommerce_affiliate_link_target', array(
		'label'    => __( 'Open affiliate links', 'bimber' ),
		'section'  => 'bimber_woocommerce_section',
		'settings' => $option_name . '[woocommerce_affiliate_link_target]',
		'type'     => 'select',
		'choices'  => array(
			'_blank'		=> __( 'in a new window', 'bimber' ),
			'_self'			=> __( 'in the same window', 'bimber' ),
		),
	) );
}
