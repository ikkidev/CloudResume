<?php
/**
 * AdAce Customizer integration
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

add_filter( 'bimber_customizer_defaults',       'bimber_adace_add_customizer_defaults' );
add_action( 'bimber_after_customize_register',  'bimber_adace_register_customizer_options', 10, 1 );

/**
 * Register plugin defaults
 *
 * @param array $defaults       Default values.
 *
 * @return array
 */
function bimber_adace_add_customizer_defaults( $defaults ) {
	// Links Before Main Collection.
	$defaults['links_above_collection_title']       = esc_html__( 'Here are my links!', 'bimber' );
	$defaults['links_above_collection_category']    = '';
	$defaults['links_above_collection_simple']      = false;
	$defaults['links_above_collection_transparent'] = false;
	$defaults['links_above_collection_disclosure']  = false;

	// Links Before Footer.
	$defaults['links_above_footer_title']       = esc_html__( 'Here are my links!', 'bimber' );
	$defaults['links_above_footer_category']    = '';
	$defaults['links_above_footer_simple']      = false;
	$defaults['links_above_footer_transparent'] = false;
	$defaults['links_above_footer_disclosure']  = false;

	// Promoted Products.
	$defaults['promoted_products_title']            = esc_html__( 'Shop with me', 'bimber' );
	$defaults['promoted_products_disclosure']       = false;
	$defaults['promoted_products_description']      = '';
	$defaults['promoted_products_type']             = 'embed';
	$defaults['promoted_products_embed_code']       = '';
	$defaults['promoted_products_categories']       = '';
	$defaults['promoted_products_hide_price']       = true;
	$defaults['promoted_products_hide_add_to_cart'] = true;
	$defaults['promoted_products_link_label']       = esc_html__( 'More products', 'bimber' );

	// Promoted Single Product.
	$defaults['promoted_product_title']         = esc_html__( 'Shop with me', 'bimber' );
	$defaults['promoted_product_disclosure']    = false;
	$defaults['promoted_product_id']            = '';
	$defaults['promoted_product_link_label']    = esc_html__( 'More products', 'bimber' );

	return $defaults;
}

/**
 * Add plugin panel
 *
 * @param WP_Customize_Manager $wp_customize        Customizer instance.
 */
function bimber_adace_register_customizer_options( $wp_customize ) {

	$defaults    = bimber_get_customizer_defaults();
	$option_name = bimber_get_theme_id();

	/**
	 * PANEL: main
	 */

	$wp_customize->add_panel( 'bimber_adace_panel', array(
		'title'    => __( 'AdAce Plugin', 'bimber' ),
		'priority' => 500,
	) );

	/**
	 * SECTION: Links before main collection
	 */

	$wp_customize->add_section( 'bimber_adace_collection_section', array(
		'title'    => __( 'Links Before Main Collection', 'bimber' ),
		'priority' => 10,
		'panel'    => 'bimber_adace_panel',
	) );

	/**
	 * CONTROLS: Links before main collection
	 */

	// Title. links_above_collection.
	$wp_customize->add_setting( $option_name . '[links_above_collection_title]', array(
		'default'           => $defaults['links_above_collection_title'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_section_title_allowed_tags',
	) );
	$wp_customize->add_control( 'bimber_links_above_collection_title', array(
		'label'    => __( 'Title', 'bimber' ),
		'section'  => 'bimber_adace_collection_section',
		'settings' => $option_name . '[links_above_collection_title]',
		'type'     => 'text',
	) );

	// Disclosure. links_above_collection.
	$wp_customize->add_setting( $option_name . '[links_above_collection_disclosure]', array(
		'default'           => $defaults['links_above_collection_disclosure'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_collection_disclosure', array(
		'label'    => __( 'Show affiliate disclosure', 'bimber' ),
		'section'  => 'bimber_adace_collection_section',
		'settings' => $option_name . '[links_above_collection_disclosure]',
		'type'     => 'checkbox',
	) );

	// Category select. links_above_collection.
	$wp_customize->add_setting( $option_name . '[links_above_collection_category]', array(
		'default'           => $defaults['links_above_collection_category'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_multi_choice',
	) );
	$wp_customize->add_control( 'bimber_links_above_collection_category', array(
		'label'    => __( 'Links Category', 'bimber' ),
		'section'  => 'bimber_adace_collection_section',
		'settings' => $option_name . '[links_above_collection_category]',
		'choices'  => bimber_customizer_get_adace_links_categories_choices(),
		'type'     => 'select',
	) );

	// Show simple. links_above_collection.
	$wp_customize->add_setting( $option_name . '[links_above_collection_simple]', array(
		'default'           => $defaults['links_above_collection_simple'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_collection_simple', array(
		'label'    => __( 'Simple list.', 'bimber' ),
		'section'  => 'bimber_adace_collection_section',
		'settings' => $option_name . '[links_above_collection_simple]',
		'type'     => 'checkbox',
	) );

	// Transparent. links_above_collection.
	$wp_customize->add_setting( $option_name . '[links_above_collection_transparent]', array(
		'default'           => $defaults['links_above_collection_transparent'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_collection_transparent', array(
		'label'    => __( 'Semitransparent', 'bimber' ),
		'section'  => 'bimber_adace_collection_section',
		'settings' => $option_name . '[links_above_collection_transparent]',
		'type'     => 'checkbox',
	) );



	/**
	 * SECTION: Links before footer
	 */

	$wp_customize->add_section( 'bimber_adace_footer_section', array(
		'title'    => __( 'Links Before Footer', 'bimber' ),
		'priority' => 20,
		'panel'    => 'bimber_adace_panel',
	) );

	/**
	 * CONTROLS: Links before footer
	 */

	// Title. links_above_footer.
	$wp_customize->add_setting( $option_name . '[links_above_footer_title]', array(
		'default'           => $defaults['links_above_footer_title'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_section_title_allowed_tags',
	) );
	$wp_customize->add_control( 'bimber_links_above_footer_title', array(
		'label'    => __( 'Title', 'bimber' ),
		'section'  => 'bimber_adace_footer_section',
		'settings' => $option_name . '[links_above_footer_title]',
		'type'     => 'text',
	) );

	// Disclosure. links_above_footer.
	$wp_customize->add_setting( $option_name . '[links_above_footer_disclosure]', array(
		'default'           => $defaults['links_above_footer_disclosure'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_footer_disclosure', array(
		'label'    => __( 'Show affiliate disclosure', 'bimber' ),
		'section'  => 'bimber_adace_footer_section',
		'settings' => $option_name . '[links_above_footer_disclosure]',
		'type'     => 'checkbox',
	) );

	// Category select. links_above_footer.
	$wp_customize->add_setting( $option_name . '[links_above_footer_category]', array(
		'default'           => $defaults['links_above_footer_category'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_multi_choice',
	) );
	$wp_customize->add_control( 'bimber_links_above_footer_category', array(
		'label'       => __( 'Links Category', 'bimber' ),
		'description' => '',
		'section'     => 'bimber_adace_footer_section',
		'settings'    => $option_name . '[links_above_footer_category]',
		'choices'     => bimber_customizer_get_adace_links_categories_choices(),
		'type'        => 'select',
	) );

	// Show simple. links_above_footer.
	$wp_customize->add_setting( $option_name . '[links_above_footer_simple]', array(
		'default'           => $defaults['links_above_footer_simple'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_footer_simple', array(
		'label'    => __( 'Simple list.', 'bimber' ),
		'section'  => 'bimber_adace_footer_section',
		'settings' => $option_name . '[links_above_footer_simple]',
		'type'     => 'checkbox',
	) );

	// Transparent. links_above_footer.
	$wp_customize->add_setting( $option_name . '[links_above_footer_transparent]', array(
		'default'           => $defaults['links_above_footer_transparent'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'bimber_links_above_footer_transparent', array(
		'label'    => __( 'Semitransparent', 'bimber' ),
		'section'  => 'bimber_adace_footer_section',
		'settings' => $option_name . '[links_above_footer_transparent]',
		'type'     => 'checkbox',
	) );



	/**
	 * SECTION: Promoted Products
	 */

	$wp_customize->add_section( 'bimber_adace_promoted_products_section', array(
		'title'    => __( 'Promoted Products', 'bimber' ),
		'priority' => 30,
		'panel'    => 'bimber_adace_panel',
	) );

	/**
	 * CONTROLS: Promoted Products
	 */

	// Title.
	$wp_customize->add_setting( $option_name . '[promoted_products_title]', array(
		'default'           => $defaults['promoted_products_title'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_section_title_allowed_tags',
	) );
	$wp_customize->add_control( 'bimber_promoted_products_title', array(
		'label'    => __( 'Title', 'bimber' ),
		'section'  => 'bimber_adace_promoted_products_section',
		'settings' => $option_name . '[promoted_products_title]',
		'type'     => 'text',
	) );

	// Show disclosure.
	$wp_customize->add_setting( $option_name . '[promoted_products_disclosure]', array(
		'default'           => $defaults['promoted_products_disclosure'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'promoted_products_disclosure', array(
		'label'    => __( 'Show affiliate disclosure', 'bimber' ),
		'section'  => 'bimber_adace_promoted_products_section',
		'settings' => $option_name . '[promoted_products_disclosure]',
		'type'     => 'checkbox',
	) );

	// Description.
	$wp_customize->add_setting( $option_name . '[promoted_products_description]', array(
		'default'           => $defaults['promoted_products_description'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_post_kses',
	) );
	$wp_customize->add_control( 'bimber_promoted_products_description', array(
		'label'    => __( 'Description', 'bimber' ),
		'section'  => 'bimber_adace_promoted_products_section',
		'settings' => $option_name . '[promoted_products_description]',
		'type'     => 'textarea',
	) );

	// Type.
	$wp_customize->add_setting( $option_name . '[promoted_products_type]', array(
		'default'           => $defaults['promoted_products_type'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_post_kses',
	) );
	$wp_customize->add_control( 'bimber_promoted_products_type', array(
		'label'    => __( 'Type', 'bimber' ),
		'section'  => 'bimber_adace_promoted_products_section',
		'settings' => $option_name . '[promoted_products_type]',
		'type'     => 'radio',
		'choices'  => array(
			'embed'       => __( 'Embed code', 'bimber' ),
			'woocommerce' => 'WooCommerce',
		),
	) );

	// Category.
	$wp_customize->add_setting( $option_name . '[promoted_products_categories]', array(
		'default'           => $defaults['promoted_products_categories'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_multi_choice',
	) );
	$wp_customize->add_control( new Bimber_Customize_Multi_Select_Control( $wp_customize, 'bimber_promoted_products_categories', array(
		'label'           => __( 'Categories', 'bimber' ),
		'description'     => __( 'You can choose more than one.', 'bimber' ),
		'section'         => 'bimber_adace_promoted_products_section',
		'settings'        => $option_name . '[promoted_products_categories]',
		'choices'         => bimber_customizer_get_woocommerce_category_choices(),
		'size'            => 8,
		'active_callback' => 'bimber_customizer_is_woocoomerce_type_chosen',
	) ) );

	// Embed code.
	$wp_customize->add_setting( $option_name . '[promoted_products_embed_code]', array(
		'default'           => $defaults['promoted_products_embed_code'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_embed_code',
	) );
	$wp_customize->add_control( 'bimber_promoted_products_embed_code', array(
		'label'           => __( 'Embed Code', 'bimber' ),
		'description'     => __( 'E.g. from ShopStyle Collective or rewardStyle', 'bimber' ),
		'section'         => 'bimber_adace_promoted_products_section',
		'settings'        => $option_name . '[promoted_products_embed_code]',
		'type'            => 'textarea',
		'active_callback' => 'bimber_customizer_is_embed_type_chosen',
	) );

	// Hide price.
	$wp_customize->add_setting( $option_name . '[promoted_products_hide_price]', array(
		'default'           => $defaults['promoted_products_hide_price'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'promoted_products_hide_price', array(
		'label'           => __( 'Hide price', 'bimber' ),
		'section'         => 'bimber_adace_promoted_products_section',
		'settings'        => $option_name . '[promoted_products_hide_price]',
		'type'            => 'checkbox',
		'active_callback' => 'bimber_customizer_is_woocoomerce_type_chosen',
	) );

	// Hide add_to_cart.
	$wp_customize->add_setting( $option_name . '[promoted_products_hide_add_to_cart]', array(
		'default'           => $defaults['promoted_products_hide_add_to_cart'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'promoted_products_hide_add_to_cart', array(
		'label'           => __( 'Hide add to cart', 'bimber' ),
		'section'         => 'bimber_adace_promoted_products_section',
		'settings'        => $option_name . '[promoted_products_hide_add_to_cart]',
		'type'            => 'checkbox',
		'active_callback' => 'bimber_customizer_is_woocoomerce_type_chosen',
	) );

	// "More" link label.
	$wp_customize->add_setting( $option_name . '[promoted_products_link_label]', array(
		'default'           => $defaults['promoted_products_link_label'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_post_kses',
	) );
	$wp_customize->add_control( 'promoted_products_link_label', array(
		'label'           => __( '"More" Link Label', 'bimber' ),
		'section'         => 'bimber_adace_promoted_products_section',
		'settings'        => $option_name . '[promoted_products_link_label]',
		'type'            => 'text',
		'active_callback' => 'bimber_customizer_is_woocoomerce_type_chosen',
	) );

	/**
	 * SECTION: Promoted Product
	 */

	$wp_customize->add_section( 'bimber_adace_promoted_product_section', array(
		'title'    => __( 'Promoted Single Product', 'bimber' ),
		'priority' => 40,
		'panel'    => 'bimber_adace_panel',
	) );

	/**
	 * CONTROLS: Promoted Product
	 */

	// Title.
	$wp_customize->add_setting( $option_name . '[promoted_product_title]', array(
		'default'           => $defaults['promoted_product_title'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_section_title_allowed_tags',
	) );
	$wp_customize->add_control( 'bimber_promoted_product_title', array(
		'label'    => __( 'Title', 'bimber' ),
		'section'  => 'bimber_adace_promoted_product_section',
		'settings' => $option_name . '[promoted_product_title]',
		'type'     => 'text',
	) );

	// Show disclosure.
	$wp_customize->add_setting( $option_name . '[promoted_product_disclosure]', array(
		'default'           => $defaults['promoted_product_disclosure'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'promoted_product_disclosure', array(
		'label'    => __( 'Show affiliate disclosure', 'bimber' ),
		'section'  => 'bimber_adace_promoted_product_section',
		'settings' => $option_name . '[promoted_product_disclosure]',
		'type'     => 'checkbox',
	) );

	// Product Id.
	$wp_customize->add_setting( $option_name . '[promoted_product_id]', array(
		'default'           => $defaults['promoted_product_id'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_multi_choice',
	) );
	$wp_customize->add_control( 'promoted_product_id', array(
		'label'       => __( 'WooCommerce Product', 'bimber' ),
		'description' => '',
		'section'     => 'bimber_adace_promoted_product_section',
		'settings'    => $option_name . '[promoted_product_id]',
		'choices'     => bimber_customizer_get_woocommerce_ids_choices(),
		'type'        => 'select',
	) );

	// "More" link label.
	$wp_customize->add_setting( $option_name . '[promoted_product_link_label]', array(
		'default'           => $defaults['promoted_product_link_label'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_post_kses',
	) );
	$wp_customize->add_control( 'promoted_product_link_label', array(
		'label'    => __( '"More" Link Label', 'bimber' ),
		'section'  => 'bimber_adace_promoted_product_section',
		'settings' => $option_name . '[promoted_product_link_label]',
		'type'     => 'text',
	) );
}

/**
 * Return list of products categories
 *
 * @return array
 */
function bimber_customizer_get_woocommerce_category_choices() {
	if ( ! bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
		return array(
			'' => sprintf( __( 'Activate the %s plugin', 'bimber' ), 'WooCommerce' ),
		);
	}

	// Prep array for return.
	$choices = array();
	// Lets make small doggies cry and add this empty choice.
	$choices[''] = esc_html__( '- None -', 'bimber' );
	// Get terms and loop to add them to choices.
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );
	foreach ( $categories as $category_obj ) {
		$choices[ $category_obj->slug ] = $category_obj->name;
	}

	return $choices;
}

/**
 * Check whether the promoted producs type is "WooCommerce"
 *
 * @param WP_Customize_Control $control Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_woocoomerce_type_chosen( $control ) {
	$type = $control->manager->get_setting( bimber_get_theme_id() . '[promoted_products_type]' )->value();

	return 'woocommerce' === $type;
}

/**
 * Check whether the promoted producs type is "Embed code"
 *
 * @param WP_Customize_Control $control Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_embed_type_chosen( $control ) {
	$type = $control->manager->get_setting( bimber_get_theme_id() . '[promoted_products_type]' )->value();

	return 'embed' === $type;
}

/**
 * Return list of products categories
 *
 * @return array
 */
function bimber_customizer_get_woocommerce_ids_choices() {
	if ( ! bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
		return array(
			'' => sprintf( __( 'Activate the %s plugin', 'bimber' ), 'WooCommerce' ),
		);
	}

	// Prep array for return.
	$choices = array();
	// Lets make small doggies cry and add this empty choice.
	$choices[''] = esc_html__( '- None -', 'bimber' );
	// Args for products query.
	$bimber_products_query_args = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1,
	);
	$bimber_products_query      = new WP_Query( $bimber_products_query_args );
	// Check if any products are found.
	if ( $bimber_products_query->have_posts() ) {
		// Loop products.
		while ( $bimber_products_query->have_posts() ) {
			$bimber_products_query->the_post();
			$choices[ get_the_id() ] = get_the_title();
		}
	}
	wp_reset_postdata();

	return $choices;
}
