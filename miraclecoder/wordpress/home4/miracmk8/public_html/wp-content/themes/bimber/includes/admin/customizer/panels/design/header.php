<?php
/**
 * WP Customizer panel section to handle header design options
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

$wp_customize->add_section( 'bimber_header_layout_section', array(
	'title'    => __( 'Builder', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );


$wp_customize->add_section( 'bimber_header_colors_section', array(
	'title'    => __( 'Colors', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );


// Composition.
$wp_customize->add_setting( $bimber_option_name . '[header_composition]', array(
	'default'           => $bimber_customizer_defaults['header_composition'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_header_composition', array(
	'label'    => __( 'Composition', 'bimber' ),
	'section'  => 'bimber_header_layout_section',
	'settings' => $bimber_option_name . '[header_composition]',
	'type'     => 'select',
	'choices'  => array(
		'original'  => array(
			'label'	=> 'Logo on left, menu below',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-01.png',
		),
		'gag'       => array(
			'label'	=> 'Logo + header, same line (full width)',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-02.png',
		),
		'05'  => array(
			'label'	=> '05',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-05.png',
		),
		'smiley'    => array(
			'label'	=> 'Logo + header, same line',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-03.png',
		),
		'07'  => array(
			'label'	=> '07',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-07.png',
		),
		'hardcore'  => array(
			'label'	=> 'Menu on left, logo below',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-04.png',
		),
		'06'  => array(
			'label'	=> '06',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-06.png',
		),
		'bunchy'       => array(
			'label'	=> 'Bunchy',
			'path'	=> BIMBER_ADMIN_DIR_URI . 'customizer/images/header-composition-bunchy.png',
		),
	),
) ) );

require_once $customizer_path . 'panels/header/colors.php';

require_once $customizer_path . 'panels/header/featured-entries.php';

require_once $customizer_path . 'panels/header/elements-hamburger.php';
require_once $customizer_path . 'panels/header/elements-user.php';
require_once $customizer_path . 'panels/header/elements-search.php';
require_once $customizer_path . 'panels/header/elements-cart.php';
require_once $customizer_path . 'panels/header/elements-newsletter.php';
require_once $customizer_path . 'panels/header/elements-skin.php';
require_once $customizer_path . 'panels/header/elements-nsfw.php';
require_once $customizer_path . 'panels/header/elements-logo.php';
require_once $customizer_path . 'panels/header/elements-mobile-logo.php';
require_once $customizer_path . 'panels/header/elements-primary-nav.php';
require_once $customizer_path . 'panels/header/elements-quick-nav.php';
require_once $customizer_path . 'panels/header/elements-button.php';
require_once $customizer_path . 'panels/header/elements-social-icons.php';
