<?php
/**
 * Register theme sections into the WP Customizer
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

add_action( 'customize_register',                   'bimber_customize_register' );
add_action( 'customize_preview_init',               'bimber_customizer_live_preview' );
add_action( 'customize_controls_enqueue_scripts',   'enqueue_customizer_scripts' );
add_action( 'wp_ajax_bimber_tag_search',            'bimber_ajax_tag_search' );

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/admin/customizer/builder/header/init.php';


/**
 * Register theme options
 *
 * @param WP_Customize_Manager $wp_customize        WP Customizer instance.
 */
function bimber_customize_register( $wp_customize ) {

	// Load helpers.
	require_once BIMBER_INCLUDES_DIR . 'options.php';

	// Load custom controls classes.
	$customizer_path = trailingslashit( get_parent_theme_file_path() ) . 'includes/admin/customizer/';

	require_once $customizer_path . 'lib/class-bimber-customize-html-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-multi-checkbox-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-multi-radio-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-multi-select-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-tag-select-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-sortable-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-custom-range-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-custom-radio-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-typography-control.php';
	require_once $customizer_path . 'lib/class-bimber-customize-typography-selector-control.php';

	// Load defaults.
	$bimber_customizer_defaults = bimber_get_customizer_defaults();

	require_once $customizer_path . 'panels/site-identity.php';


	// Define design panel.
	$wp_customize->add_panel( 'bimber_home_panel', array(
		'title'    => esc_html__( 'Home', 'bimber' ),
		'priority' => 180,
	) );

	require_once $customizer_path . 'panels/home/featured-entries.php';
	require_once $customizer_path . 'panels/home/before-main-collection.php';
	require_once $customizer_path . 'panels/home/main-collection.php';

	// Define posts panel.
	$wp_customize->add_panel( 'bimber_posts_panel', array(
		'title'    => esc_html__( 'Posts', 'bimber' ),
		'priority' => 200,
	) );

	require_once $customizer_path . 'panels/posts/single.php';
	require_once $customizer_path . 'panels/posts/archive.php';
	require_once $customizer_path . 'panels/posts/global.php';
	require_once $customizer_path . 'panels/posts/nsfw.php';
	require_once $customizer_path . 'panels/posts/auto-load.php';
	require_once $customizer_path . 'panels/posts/video-format.php';
	require_once $customizer_path . 'panels/posts/link-format.php';
	require_once $customizer_path . 'panels/posts/gallery-format.php';

	// Define desing panel.
	$wp_customize->add_panel( 'bimber_design_panel', array(
		'title'    => esc_html__( 'Design', 'bimber' ),
		'priority' => 220,
	) );

	require_once $customizer_path . 'panels/design/general.php';
	require_once $customizer_path . 'panels/design/cards.php';
	require_once $customizer_path . 'panels/design/colors.php';
	require_once $customizer_path . 'panels/design/colors-flags.php';

	// Before we fix the memory problem for that module, it's a way to disable it and restore access to the Customizer.
	if ( apply_filters( 'bimber_customize_load_module_typography', true ) ) {
        require_once $customizer_path . 'panels/design/typography.php';
    }

	require_once $customizer_path . 'panels/design/breadcrumbs.php';


	// Define header panel.
	$wp_customize->add_panel( 'bimber_header_panel', array(
		'title'    => esc_html__( 'Header', 'bimber' ),
		'priority' => 221,
	) );

	require_once $customizer_path . 'panels/design/header.php';

	// Define footer panel.
	$wp_customize->add_panel( 'bimber_footer_panel', array(
		'title'    => esc_html__( 'Footer', 'bimber' ),
		'priority' => 222,
	) );

	require_once $customizer_path . 'panels/footer/general.php';
	require_once $customizer_path . 'panels/footer/colors.php';
	require_once $customizer_path . 'panels/footer/modules.php';

	require_once $customizer_path . 'panels/search.php';

	$wp_customize->get_section('custom_css')->priority = 999;

	do_action( 'bimber_after_customize_register', $wp_customize, $bimber_customizer_defaults );
}

/**
 * Force theme to use head inline css (for dynamic styles) in WP Customize Preview mode
 */
function bimber_customizer_live_preview() {
	add_filter( 'bimber_dynamic_style_type', 'bimber_use_internal_dynamic_style_in_customizer_preview' );
	add_filter( 'transient_bimber_featured_entries_query', '__return_false', 99 );
	add_filter( 'pre_bimber_has_skin_mode', '__return_true', 99 );
}

/**
 * Return dynamic style type used in live preview
 *
 * @return string
 */
function bimber_use_internal_dynamic_style_in_customizer_preview() {
	return 'internal';
}

/**
 * Return list of categories
 *
 * @return array
 */
function bimber_customizer_get_category_choices() {
	$choices    = array();
	$categories = get_categories( 'hide_empty=0' );

	foreach ( $categories as $category_obj ) {
		$choices[ $category_obj->slug ] = $category_obj->name;
	}

	return $choices;
}

/**
 * Return list of pages that can used to inject into homepage
 */
function bimber_customizer_get_inject_page_choices() {
    $choices = array(
        '' => _x( '-- don\'t inject --', 'Customizer', 'bimber' ),
    );

    $pages = get_pages( array(
        'parent' => 0,
        'post_status'  => array( 'publish', 'draft', 'pending' ),
    ) );

    if ( ! empty( $pages ) ) {
        foreach ( $pages as $page ) {
            $status = '';

            if ( 'publish' !== $page->post_status ) {
                $status = sprintf( ' (%s)', $page->post_status );
            }

            $choices[ $page->ID ] = $page->post_title . $status;
        }
    }

    return $choices;
}

/**
 * Sanitize value of multi-choice control
 *
 * @param array $input   List of choices.
 *
 * @return array
 */
function bimber_sanitize_multi_choice( $input ) {
	return $input;
}

/**
 * Sanitize value of header builder composition
 *
 * @param array $input   List of choices.
 *
 * @return array
 */
function bimber_sanitize_hb_composition( $input ) {
	return $input;
}

/**
 * Sanitize value of embed code
 *
 * @param array $input   List of choices.
 *
 * @return array
 */
function bimber_sanitize_embed_code( $input ) {
	return $input;
}

function bimber_customizer_get_product_category_choices() {
	$terms = get_terms( 'product_cat', array(
		'hide_empty' => false,
	) );

	$choices[''] = esc_html__( '- None -', 'bimber' );

	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term_obj ) {
			$choices[ $term_obj->slug ] = $term_obj->name;
		}
	}

	return apply_filters( 'bimber_customizer_product_category_choices', $choices );
}

/**
 * Allowed tags for of section title.
 *
 * @return array
 */
function bimber_get_section_title_allowed_tags() {
	return array(
		'em' => array(),
		'br' => array(),
	);
}

/**
 * Sanitize section title.
 *
 * @param string $input Section title input.
 *
 * @return string
 */
function bimber_sanitize_section_title_allowed_tags( $input ) {
	return wp_kses( $input, bimber_get_section_title_allowed_tags() );
}

/**
 * Enqueue customizer scripts
 *
 * @return void
 */
function enqueue_customizer_scripts() {
	wp_enqueue_script( 'tags-box' );
}

/**
 * Sanitize instagram username.
 *
 * @param string $input Username input.
 * @return $input
 */
function bimber_sanitize_instagram_username( $input ) {
	// Remove @ from input.
	$input = str_replace( '@', '', $input );
	// Sanitize and return.
	return sanitize_text_field( $input );
}


/**
 *   Determine the device view size and icons in Customizer
 */
function bimber_print_customizer_sizes_style() {

	$mobile_margin_left = '-240px';
	$mobile_width = '480px';
	$mobile_height = '720px';

	$mobile_landscape_width = '720px';
	$mobile_landscape_height = '480px';

	$tablet_width = '780px';
	$tablet_height = '1000px';

	$tablet_landscape_width = '1000px';
	$tablet_landscape_height = '780px';

	?>
	<style>
		.wp-customizer .preview-mobile .wp-full-overlay-main {
			margin-left: <?php echo $mobile_margin_left; ?>;
			width: <?php echo $mobile_width; ?>;
			height: <?php echo $mobile_height; ?>;
		}

		.wp-customizer .preview-mobile-landscape .wp-full-overlay-main {

			width: <?php echo $mobile_landscape_width; ?>;
			height: <?php echo $mobile_landscape_height; ?>;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.wp-customizer .preview-tablet .wp-full-overlay-main {

			width: <?php echo $tablet_width; ?>;
			height: <?php echo $tablet_height; ?>;
		}

		.wp-customizer .preview-tablet-landscape .wp-full-overlay-main {

			width: <?php echo $tablet_landscape_width; ?>;
			height: <?php echo $tablet_landscape_height; ?>;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.wp-full-overlay-footer .devices .preview-tablet-landscape:before {
			content: "\f167";
		}

		.wp-full-overlay-footer .devices .preview-mobile-landscape:before {
			content: "\f167";
		}
	</style>
	<?php

}

add_action( 'customize_controls_print_styles', 'bimber_print_customizer_sizes_style' );

/**
 *   Set device button settings and order
 *
 * @param array $devices Devices.
 */
function bimber_customizer_preview_devices( $devices ) {
	$custom_devices['desktop'] = $devices['desktop'];
	$custom_devices['tablet'] = $devices['tablet'];
	$custom_devices['tablet-landscape'] = array(
			'label' => __( 'Enter tablet landscape preview mode', 'bimber' ),
			'default' => false,
	);
	$custom_devices['mobile'] = $devices['mobile'];
	$custom_devices['mobile-landscape'] = array(
			'label' => __( 'Enter mobile landscape preview mode', 'bimber' ),
			'default' => false,
	);

	foreach ( $devices as $device => $settings ) {
		if ( ! isset( $custom_devices[ $device ] ) ) {
			$custom_devices[ $device ] = $settings;
		}
	}

	return $custom_devices;
}

add_filter( 'customize_previewable_devices', 'bimber_customizer_preview_devices' );

/**
 * Check whether user chose page for Posts
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_posts_page_selected( $control ) {
	$show_on_front = $control->manager->get_setting( 'show_on_front' )->value();

	// Front page displays.
	if ( 'posts' === $show_on_front ) {
		// Your Latest posts.
		return true;
	} else {
		// A static page.
		$page_for_posts = $control->manager->get_setting( 'page_for_posts' )->value();

		// A page is selected (0 means no selection).
		return '0' !== $page_for_posts;
	}
}

/**
 * Return list of products categories
 *
 * @return array
 */
function bimber_customizer_get_adace_links_categories_choices() {
	// Prep array for return.
	$categories_choices = array();
	// Lets make small doggies cry and add this empty choice.
	$categories_choices[''] = esc_html__( '- None -', 'bimber' );
	// Get terms and loop to add them to choices.
	$categories = get_terms( array(
		'taxonomy'   => 'adace_link_category',
		'hide_empty' => true,
	) );

	if ( ! is_wp_error( $categories ) ) {
		foreach ( $categories as $category_obj ) {
			$categories_choices[ $category_obj->slug ] = $category_obj->name;
		}
	}

	return $categories_choices;
}
