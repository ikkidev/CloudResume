<?php
/**
 * WP Customizer panel section to handle general design options
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

$bimber_option_name = bimber_get_theme_id();

$wp_customize->add_section( 'bimber_design_typo_section', array(
	'title'    => __( 'Typography', 'bimber' ),
	'priority' => 30,
	'panel'    => 'bimber_design_panel',
) );

// Google Font Subset.
$wp_customize->add_setting( $bimber_option_name . '[global_google_font_subset]', array(
	'default'           => $bimber_customizer_defaults['global_google_font_subset'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_global_google_font_subset', array(
	'label'    => __( 'Google Font Subset', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[global_google_font_subset]',
	'choices'  => array(
		'latin'        => _x( 'Latin',              'font subset', 'bimber' ),
		'latin-ext'    => _x( 'Latin Extended',     'font subset', 'bimber' ),
		'cyrillic'     => _x( 'Cyrillic',           'font subset', 'bimber' ),
		'cyrillic-ext' => _x( 'Cyrillic Extended',  'font subset', 'bimber' ),
		'greek'        => _x( 'Greek',              'font subset', 'bimber' ),
		'greek-ext'    => _x( 'Greek Extended',     'font subset', 'bimber' ),
		'vietnamese'   => _x( 'Vietnamese',         'font subset', 'bimber' ),
	),
) ) );


// Selectors.
$wp_customize->add_setting( $bimber_option_name . '[typo_selectors]', array(
	'default'           => $bimber_customizer_defaults['typo_selectors'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Selector_Control( $wp_customize, 'bimber_typo_selectors', array(
	'label'    => __( 'Add new', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_selectors]',
) ) );

// Body.
$wp_customize->add_setting( $bimber_option_name . '[typo_body]', array(
	'default'           => $bimber_customizer_defaults['typo_body'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_body', array(
	'label'    => __( 'Body Text', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_body]',
	'input_attrs' => array(
		'selector' => 'html,body,input,select,textarea',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'attribute_args'    => array(
			'font-size' => array(
				'min' => 10,
				'max' => 24,
			),
			'font-size-tablet' => array(
				'min' => 10,
				'max' => 24,
			),
			'font-size-mobile' => array(
				'min' => 10,
				'max' => 24,
			),
		),
		'setting'	=> 'typo_body',
	),
) ) );


// g1-meta.
$wp_customize->add_setting( $bimber_option_name . '[typo_meta]', array(
	'default'           => $bimber_customizer_defaults['typo_meta'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_meta', array(
	'label'    => __( 'Metatext', 'bimber' ),
	'description' => __( 'Short meta information like post date, author etc.', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_meta]',
	'input_attrs' => array(
		'selector' => '.g1-meta',
		'attributes' => array( 'font-family', 'font-style', 'font-size','letter-spacing','text-transform', 'font-size-tablet', 'font-size-mobile' ),
		'setting'	=> 'typo_meta',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 15,
			),
		),
	),
) ) );

// g1-link.
$wp_customize->add_setting( $bimber_option_name . '[typo_link]', array(
	'default'           => $bimber_customizer_defaults['typo_link'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_link', array(
	'label'    => __( 'Metatext link', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_link]',
	'input_attrs' => array(
		'selector' => '.g1-meta a',
		'attributes' => array( 'font-family', 'font-style', 'font-size','letter-spacing','text-transform', 'font-size-tablet', 'font-size-mobile' ),
		'setting'	=> 'typo_link',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 15,
			),
		),
	),
) ) );

// Button medium.
$wp_customize->add_setting( $bimber_option_name . '[typo_button]', array(
	'default'           => $bimber_customizer_defaults['typo_button'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_button', array(
	'label'    => __( 'Button', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_button]',
	'input_attrs' => array(
		'selector' => '[type=submit], [type=reset], [type=button], button, .g1-button, .g1-hb-row .snax-button-create.g1-button-m',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_button',
	),
) ) );

// Categories.
$wp_customize->add_setting( $bimber_option_name . '[typo_categories]', array(
	'default'           => $bimber_customizer_defaults['typo_categories'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_categories', array(
	'label'    => __( 'Category label', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_categories]',
	'input_attrs' => array(
		'selector' => '.entry-category, .entry-categories-l .entry-category',
		'attributes' => array( 'font-family', 'font-style', 'font-size','letter-spacing','text-transform', 'font-size-tablet', 'font-size-mobile' ),
		'setting'	=> 'typo_categories',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 15,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 15,
			),
		),
	),
) ) );

// Tags.
$wp_customize->add_setting( $bimber_option_name . '[typo_tags]', array(
	'default'           => $bimber_customizer_defaults['typo_tags'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_tags', array(
	'label'    => __( 'Tag label', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_tags]',
	'input_attrs' => array(
		'selector' => '.entry-tag',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_tags',
	),
) ) );


// Tab.
$wp_customize->add_setting( $bimber_option_name . '[typo_tabs]', array(
	'default'           => $bimber_customizer_defaults['typo_tabs'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_tabs', array(
	'label'    => __( 'Tab', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_tabs]',
	'input_attrs' => array(
		'selector' => '.g1-tab',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_tabs',
	),
) ) );

// Primary nav.
$wp_customize->add_setting( $bimber_option_name . '[typo_primary_nav]', array(
	'default'           => $bimber_customizer_defaults['typo_primary_nav'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_primary_nav', array(
	'label'    => __( 'Primary Navigation', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_primary_nav]',
	'input_attrs' => array(
		'selector' => '.g1-body-inner .g1-primary-nav-menu > .menu-item > a, .g1-primary-nav-menu>.menu-item>a',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_primary_nav',
	),
) ) );

// Secondary nav.
$wp_customize->add_setting( $bimber_option_name . '[typo_secondary_nav]', array(
	'default'           => $bimber_customizer_defaults['typo_secondary_nav'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_secondary_nav', array(
	'label'    => __( 'Secondary Navigation', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_secondary_nav]',
	'input_attrs' => array(
		'selector' => '.g1-body-inner .g1-secondary-nav-menu > .menu-item > a',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_secondary_nav',
	),
) ) );

// Quick Nav.
$wp_customize->add_setting( $bimber_option_name . '[typo_quick_nav]', array(
	'default'           => $bimber_customizer_defaults['typo_quick_nav'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_quick_nav', array(
	'label'    => __( 'Quick Nav', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_quick_nav]',
	'input_attrs' => array(
		'selector' => '.g1-quick-nav .g1-quick-nav-menu > .menu-item > a',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_quick_nav',
	),
) ) );

// Submenus.
$wp_customize->add_setting( $bimber_option_name . '[typo_submenus]', array(
	'default'           => $bimber_customizer_defaults['typo_submenus'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_submenus', array(
	'label'    => __( 'Submenu link', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_submenus]',
	'input_attrs' => array(
		'selector' => '.sub-menu > .menu-item > a',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_submenus',
	),
) ) );


// Drop toggle.
$wp_customize->add_setting( $bimber_option_name . '[typo_drop_toggle]', array(
	'default'           => $bimber_customizer_defaults['typo_drop_toggle'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_drop_toggle', array(
	'label'    => __( 'Dropdown toggle', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_drop_toggle]',
	'input_attrs' => array(
		'selector' => '.g1-drop-toggle-text',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_drop_toggle',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 9,
				'max' => 30,
			),
			'font-size-tablet' => array(
				'min' => 9,
				'max' => 30,
			),
			'font-size-mobile' => array(
				'min' => 9,
				'max' => 30,
			),
		),
	),
) ) );

// entry-content.
$wp_customize->add_setting( $bimber_option_name . '[typo_xl]', array(
	'default'           => $bimber_customizer_defaults['typo_xl'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_xl', array(
	'label'    => __( 'Post Content', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_xl]',
	'input_attrs' => array(
		'selector' => '.g1-typography-xl',
		'attributes'        => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_xl',
	),
) ) );


// Giga.
$wp_customize->add_setting( $bimber_option_name . '[typo_giga]', array(
	'default'           => $bimber_customizer_defaults['typo_giga'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_giga', array(
	'label'    => __( 'Giga Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_giga]',
	'input_attrs' => array(
		'selector' => '.g1-giga',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_giga',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// mega.
$wp_customize->add_setting( $bimber_option_name . '[typo_mega]', array(
	'default'           => $bimber_customizer_defaults['typo_mega'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_mega', array(
	'label'    => __( 'Mega Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_mega]',
	'input_attrs' => array(
		'selector' => '.g1-mega',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_mega',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// mega-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_mega_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_mega_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_mega_2nd', array(
	'label'    => __( 'Mega Heading as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_mega_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-mega-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_mega_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// alpha.
$wp_customize->add_setting( $bimber_option_name . '[typo_alpha]', array(
	'default'           => $bimber_customizer_defaults['typo_alpha'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_alpha', array(
	'label'    => __( 'H1 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_alpha]',
	'input_attrs' => array(
		'selector' => '.g1-alpha, .entry-content h1, h1',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_alpha',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// alpha-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_alpha_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_alpha_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_alpha_2nd', array(
	'label'    => __( 'H1 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_alpha_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-alpha-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_alpha_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// beta.
$wp_customize->add_setting( $bimber_option_name . '[typo_beta]', array(
	'default'           => $bimber_customizer_defaults['typo_beta'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_beta', array(
	'label'    => __( 'H2 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_beta]',
	'input_attrs' => array(
		'selector' => '.g1-beta, .entry-content h2, h2',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_beta',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// beta-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_beta_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_beta_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_beta_2nd', array(
	'label'    => __( 'H2 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_beta_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-beta-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_beta_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// gamma.
$wp_customize->add_setting( $bimber_option_name . '[typo_gamma]', array(
	'default'           => $bimber_customizer_defaults['typo_gamma'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_gamma', array(
	'label'    => __( 'H3 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_gamma]',
	'input_attrs' => array(
		'selector' => '.g1-gamma, .entry-content h3, h3',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_gamma',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// gamma-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_gamma_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_gamma_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_gamma_2nd', array(
	'label'    => __( 'H3 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_gamma_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-gamma-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_gamma_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// gamma-3rd.
$wp_customize->add_setting( $bimber_option_name . '[typo_gamma_3rd]', array(
	'default'           => $bimber_customizer_defaults['typo_gamma_3rd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_gamma_3rd', array(
	'label'    => __( 'H3 as Subtitle', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_gamma_3rd]',
	'input_attrs' => array(
		'selector' => '.g1-gamma-3rd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_gamma_3rd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// delta.
$wp_customize->add_setting( $bimber_option_name . '[typo_delta]', array(
	'default'           => $bimber_customizer_defaults['typo_delta'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_delta', array(
	'label'    => __( 'H4 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_delta]',
	'input_attrs' => array(
		'selector' => '.g1-delta, .entry-content h4, h4',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_delta',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// delta-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_delta_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_delta_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_delta_2nd', array(
	'label'    => __( 'H4 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_delta_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-delta-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_delta_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// delta-3rd.
$wp_customize->add_setting( $bimber_option_name . '[typo_delta_3rd]', array(
	'default'           => $bimber_customizer_defaults['typo_delta_3rd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_delta_3rd', array(
	'label'    => __( 'H4 as Subtitle', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_delta_3rd]',
	'input_attrs' => array(
		'selector' => '.g1-delta-3rd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_delta_3rd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// epsilon.
$wp_customize->add_setting( $bimber_option_name . '[typo_epsilon]', array(
	'default'           => $bimber_customizer_defaults['typo_epsilon'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_epsilon', array(
	'label'    => __( 'H5 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_epsilon]',
	'input_attrs' => array(
		'selector' => '.g1-epsilon, .entry-content h5, h5',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_epsilon',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// epsilon-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_epsilon_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_epsilon_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_epsilon_2nd', array(
	'label'    => __( 'H5 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_epsilon_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-epsilon-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_epsilon_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// epsilon-3rd.
$wp_customize->add_setting( $bimber_option_name . '[typo_epsilon_3rd]', array(
	'default'           => $bimber_customizer_defaults['typo_epsilon_3rd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_epsilon_3rd', array(
	'label'    => __( 'H5 as Subtitle', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_epsilon_3rd]',
	'input_attrs' => array(
		'selector' => '.g1-epsilon-3rd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_epsilon_3rd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),

) ) );

// zeta.
$wp_customize->add_setting( $bimber_option_name . '[typo_zeta]', array(
	'default'           => $bimber_customizer_defaults['typo_zeta'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_zeta', array(
	'label'    => __( 'H6 Heading', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_zeta]',
	'input_attrs' => array(
		'selector' => '.g1-zeta, .entry-content h6, h6',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_zeta',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// zeta-2nd.
$wp_customize->add_setting( $bimber_option_name . '[typo_zeta_2nd]', array(
	'default'           => $bimber_customizer_defaults['typo_zeta_2nd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_zeta_2nd', array(
	'label'    => __( 'H6 as Section Title', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_zeta_2nd]',
	'input_attrs' => array(
		'selector' => '.g1-zeta-2nd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_zeta_2nd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),
) ) );

// zeta-3rd.
$wp_customize->add_setting( $bimber_option_name . '[typo_zeta_3rd]', array(
	'default'           => $bimber_customizer_defaults['typo_zeta_3rd'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_zeta_3rd', array(
	'label'    => __( 'H6 as Subtitle', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_zeta_3rd]',
	'input_attrs' => array(
		'selector' => '.g1-zeta-3rd',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_zeta_3rd',
		'attribute_args'	=> array(
			'font-size' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-tablet' => array(
				'min' => 11,
				'max' => 60,
			),
			'font-size-mobile' => array(
				'min' => 11,
				'max' => 60,
			),
		),
	),

) ) );

// Blockquote.
$wp_customize->add_setting( $bimber_option_name . '[typo_quote]', array(
	'default'           => $bimber_customizer_defaults['typo_quote'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Typography_Control( $wp_customize, 'bimber_typo_quote', array(
	'label'    => __( 'Blockquote', 'bimber' ),
	'section'  => 'bimber_design_typo_section',
	'settings' => $bimber_option_name . '[typo_quote]',
	'input_attrs' => array(
		'selector' => 'blockquote',
		'attributes' => array( 'font-family', 'font-style', 'font-size','line-height','letter-spacing','text-transform', 'font-size-tablet','line-height-tablet', 'font-size-mobile','line-height-mobile' ),
		'setting'	=> 'typo_quote',
	),
) ) );




