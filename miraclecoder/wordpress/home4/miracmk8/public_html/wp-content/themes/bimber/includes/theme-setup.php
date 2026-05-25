<?php
/**
 * Theme setup functions
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
 * Set up the theme
 */
function bimber_setup_theme() {
    // @since 9.1.1
    remove_theme_support( 'widgets-block-editor' );

	// Make theme available for translation.
	load_theme_textdomain( 'bimber', BIMBER_THEME_DIR . 'languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Define grid size.
	$grid_size = apply_filters( 'bimber_get_grid_size', array(
		'width'         => 1182,
		'gutter_width'  => 30,
	) );

	$grid_w = $grid_size['width'];
	$grid_gutter_w = $grid_size['gutter_width'];

	// Calculate grid related sizes.
	$grid_1of4_w = ($grid_w - 4*$grid_gutter_w)/4;
	$grid_1of2_w = ($grid_w - 2*$grid_gutter_w)/2;
	$grid_1of3_w = ($grid_w - 3*$grid_gutter_w)/3;
	$grid_2of3_w = 2*$grid_1of3_w + $grid_gutter_w;
	$grid_1of1_w = $grid_w - $grid_gutter_w;

	$image_sizes = array(
	//  Name                                        Width,  Height                          Crop    Has2x
		'bimber-grid-xs'                => array(   192,    192 * 1/2,                      true,   true ),
		'bimber-grid-xs-ratio-16-9'     => array(   192,    192 * 9/16,                     true,   true ),
		'bimber-grid-xs-ratio-4-3'      => array(   192,    192 * 3/4,                      true,   true ),
		'bimber-grid-xs-ratio-1-1'      => array(   192,    192 * 1/1,                      true,   true ),
		'bimber-list-xxs'               => array(   90,     90 * 3/4,                       true,   true ),
		'bimber-list-xs'                => array(   110,    110,                            true,   true ),
		'bimber-grid-standard'          => array(   $grid_1of3_w,   $grid_1of3_w * 9/16,    true,   true ),
		// Small list (1of4)
		'bimber-list-s'                 => array(   $grid_1of4_w,   $grid_1of4_w * 1/1.43,  true,   true ),
		// Small grid (1of4)
		'bimber-grid-s'                 => array(   $grid_1of4_w,   $grid_1of4_w * 1/1.43,  true,   true ),
		// Large Grid (1of2)
		'bimber-grid-l'                 => array(   $grid_1of2_w,   $grid_1of2_w * 9/16,    true,   true ),
		'bimber-grid-l-ratio-3-4'       => array(   $grid_1of2_w,   $grid_1of2_w * 3/4,     true,   true ), // Refactor?
		// Zigzag (1of2)
		'bimber-zigzag'                 => array(   $grid_1of2_w,   9999,                   false,  true ),
		'bimber-zigzag-s'               => array(   $grid_1of4_w,   9999,                   false,  true ),
		// Masonry
		'bimber-grid-masonry'           => array(   $grid_1of3_w,   9999,                   false,  true ),
		'bimber-list-standard'          => array(   $grid_1of3_w,   $grid_1of3_w * 9/16,    true,   true ),
		'bimber-grid-fancy'             => array(   $grid_1of3_w,   $grid_1of3_w * 9/21,    true,   true ),
		'bimber-list-fancy'             => array(   $grid_1of3_w,   $grid_1of3_w * 9/21,    true,   true ),
		'bimber-stream'                 => array(   608,            9999,                   false,  false ),
		'bimber-grid-2of3'              => array(   $grid_2of3_w,   9999,                   false,  false ),
		'bimber-classic-1of1'           => array(   $grid_1of1_w,   9999,                   false,  false ),
		'bimber-tile'                   => array(   $grid_2of3_w,   $grid_2of3_w * 9/16,    true,   false ),
		'bimber-tile-carmania'          => array(   $grid_2of3_w,   $grid_2of3_w * 1/2,     true,   false ),
		'bimber-tile-xxl'               => array(   $grid_1of1_w,   $grid_1of1_w * 9/16,    true,   false ),
	);

	$image_sizes = apply_filters( 'bimber_get_image_sizes', $image_sizes );

	foreach( $image_sizes as $name => $args ) {
		list( $width, $height, $crop, $has_2x ) = $args;

		add_image_size(
			$name,
			round( $width, 0, PHP_ROUND_HALF_DOWN ),
			round( $height, 0, PHP_ROUND_HALF_DOWN ),
			$crop
		);

		// Add retina '-2x' size.
		if ( $has_2x ) {
			$height_multiplier = ( 9999 === $height ) ? 1 : 2;

			add_image_size(
				$name . '-2x',
				round( 2 * $width, 0, PHP_ROUND_HALF_DOWN ),
				round( $height_multiplier * $height, 0, PHP_ROUND_HALF_DOWN ),
				$crop
			);
		}
	}


//	if ( 'miami' === bimber_get_current_stack()) {
//		add_image_size( 'bimber-grid-standard',     364,        round( 364 * 3 / 4 ), true );
//		add_image_size( 'bimber-grid-standard-2x',  364 * 2,    round( 364 * 2 * 3 / 4 ), true );
//	} else {
//		add_image_size( 'bimber-grid-standard',     364,        round( 364 * 9 / 16 ), true );
//		add_image_size( 'bimber-grid-standard-2x',  364 * 2,    round( 364 * 2 * 9 / 16 ), true );
//	}


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Enable support for Post Formats.
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		)
	);

	// Gutenberg related features.
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	// @todo
	add_action( 'wp_enqueue_scripts', function() {
		wp_dequeue_style( 'wp-block-library' );
	} );


	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus( array(
		'bimber_primary_nav'    => esc_html__( 'Primary Navigation', 'bimber' ),
		'bimber_secondary_nav'  => esc_html__( 'Secondary Navigation', 'bimber' ),
		'bimber_user_nav'       => esc_html__( 'User Navigation', 'bimber' ),
		'bimber_footer_nav'     => esc_html__( 'Footer Navigation', 'bimber' ),
		'bimber_home_filters'   => esc_html__( 'Home Filters', 'bimber' ),
	) );
}

/**
 * Load default theme options
 */
function bimber_load_default_options() {
	$theme_id = bimber_get_theme_id();

	// Load options for WP Admin > Appearance > Customize.
	$customizer_option_name = $theme_id;
	$customizer_options     = get_option( $customizer_option_name );

	if ( ! $customizer_options ) {
		$bimber_customizer_defaults = bimber_get_customizer_defaults();

		if ( isset( $bimber_customizer_defaults ) ) {
			update_option( $customizer_option_name, $bimber_customizer_defaults );
		}
	}

	// Load options for WP Admin > Appearance > Theme Options.
	$theme_option_name = $theme_id . '_options';
	$theme_options     = get_option( $theme_option_name );

	if ( ! $theme_options ) {
		$bimber_theme_options_defaults = bimber_get_theme_options_defaults();

		if ( isset( $bimber_theme_options_defaults ) ) {
			update_option( $theme_option_name, $bimber_theme_options_defaults );
		}
	}
}

/**
 * Set up WPML plugin
 */
function bimber_setup_wpml() {
	if ( bimber_can_use_plugin( 'sitepress-multilingual-cms/sitepress.php' ) ) {

		// Remove @lang from term title.
		global $sitepress;

		if ( $sitepress ) {
			add_filter( 'single_term_title', array( $sitepress, 'the_category_name_filter' ) );
		}

		define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
	}
}

/**
 * Set up sidebars
 */
function bimber_setup_sidebars() {
	$user_sidebars = get_option( 'bimber_user_sidebars', array() );

	$custom_sidebars = get_option( 'bimber_custom_sidebars', array() );

	$core_sidebars = bimber_get_predefined_sidebars();

	$sidebars = array_merge( $core_sidebars, $custom_sidebars, $user_sidebars );

	$sidebars = apply_filters( 'bimber_setup_sidebars', $sidebars );

	$section_title_args = bimber_get_section_title_args( array( 'widgettitle' ) );
	$before_title = '<header>' . $section_title_args['before_with_class'];
	$after_title = $section_title_args['after'] . '</header>';

	if ( count( $sidebars ) ) {
		foreach ( $sidebars as $sidebar_id => $sidebar_config ) {
			if ( ! empty( $sidebar_config ) && isset( $sidebar_config['label'] ) ) {
				$sidebar_class = isset( $core_sidebars[ $sidebar_id ] ) ? '' : 'g1-custom';

				if ( isset( $user_sidebars[ $sidebar_id ] ) ) {
					$sidebar_class = 'g1-user';
				}

				register_sidebar( array(
					'name'          => $sidebar_config['label'],
					'id'            => $sidebar_id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => $before_title,
					'after_title'   => $after_title,
					'class'         => $sidebar_class,
					'description'   => isset( $sidebar_config['description'] ) ? $sidebar_config['description'] : '',
				) );
			}
		}
	}
}

/**
 * Adjust the $content_width WP global variable
 */
function bimber_setup_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bimber_content_width', 662 );
}

/**
 * Allow empty strings in widget titles
 *
 * @param string $title Widget title.
 *
 * @return string
 */
function bimber_allow_empty_widget_title( $title ) {
	$title = trim( $title );
	$title = ( '&nbsp;' === $title ) ? '' : $title;

	return $title;
}

function bimber_safe_style_css( $styles ) {
	$styles[] = 'top';

	return $styles;
}
