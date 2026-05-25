<?php
/**
 * WP Customizer panel section to handle posts archive options
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

$wp_customize->add_section( 'bimber_posts_archive_section', array(
	'title'         => __( 'Archive', 'bimber' ),
	'priority'      => 40,
	'panel'         => 'bimber_posts_panel',
	'description'   =>
		__( 'Set up <strong>the default options</strong> for all post archive pages.', 'bimber' ) . ' ' .
		__( 'You can override them for any individual category or tag while editing it.', 'bimber' ),
) );

// Header.
$wp_customize->add_setting( 'bimber_archive_header_divider', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_archive_header_divider', array(
    'section'  => 'bimber_posts_archive_section',
    'settings' => 'bimber_archive_header_divider',
    'html'     =>
        '<h2>' . esc_html__( 'Header', 'bimber' ) . '</h2>',
) ) );

// Header > Composition.
$wp_customize->add_setting( $bimber_option_name . '[archive_header_composition]', array(
    'default'           => $bimber_customizer_defaults['archive_header_composition'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_header_composition', array(
    'label'    => _x( 'Composition', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_header_composition]',
    'type'     => 'select',
    'choices'  => bimber_get_archive_header_compositions(),
) );


// Header > Filters.
$wp_customize->add_setting( $bimber_option_name . '[archive_filters]', array(
    'default'           => $bimber_customizer_defaults['archive_filters'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_filters', array(
    'label'    => _x( 'Filters', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_filters]',
    'choices'  => bimber_get_archive_filters(),
) ) );

// Header > Default Filter.
$wp_customize->add_setting( $bimber_option_name . '[archive_default_filter]', array(
    'default'           => $bimber_customizer_defaults['archive_default_filter'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_archive_default_filter', array(
    'label'    => _x( 'Default Filter', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_default_filter]',
    'type'     => 'select',
    'choices'  => bimber_get_archive_filters(),
) );

// Header > Hide Elements.
$wp_customize->add_setting( $bimber_option_name . '[archive_header_hide_elements]', array(
    'default'           => $bimber_customizer_defaults['archive_header_hide_elements'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_header_hide_elements', array(
    'label'    => _x( 'Hide Elements', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_header_hide_elements]',
    'choices'  => bimber_get_archive_header_elements_to_hide(),
) ) );

// Header > Background color.
$wp_customize->add_setting( $bimber_option_name . '[archive_header_background_color]', array(
    'default'           => $bimber_customizer_defaults['archive_header_background_color'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_archive_header_background_color', array(
    'label'    => _x( 'Background Color', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_header_background_color]',
) ) );

// Header > Gradient background.
$wp_customize->add_setting( $bimber_option_name . '[archive_header_background2_color]', array(
    'default'           => $bimber_customizer_defaults['archive_header_background2_color'],
    'type'              => 'option',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_archive_header_background2_color', array(
    'label'    => _x( 'Optional Background Gradient', 'Settings', 'bimber' ),
    'section'  => 'bimber_posts_archive_section',
    'settings' => $bimber_option_name . '[archive_header_background2_color]',
) ) );


// =====================================================================================================================


// Featured Entries section.
$wp_customize->add_setting( 'bimber_archive_featured_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_archive_featured_divider', array(
	'section'  => 'bimber_posts_archive_section',
	'settings' => 'bimber_archive_featured_divider',
	'html'     =>
	    '<h2>' . esc_html__( 'Featured Entries', 'bimber' ) . '</h2>',
) ) );


// Featured Entries type.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_featured_entries', array(
	'label'    => __( 'Type', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_featured_entries]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_featured_entries_types(),
) );

// Featured entries title.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_title]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_title'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_featured_entries_title', array(
	'label'           => __( 'Title', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_featured_entries_title]',
	'type'            => 'text',
	'input_attrs'     => array(
		'placeholder' => __( 'Leave empty to use the default value', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) );

// Featured entries hide title.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_title_hide]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_title_hide'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_featured_entries_title_hide', array(
	'label'    => __( 'Hide Title', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_featured_entries_title_hide]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) );


// Featured Entries Template.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_template]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_template'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_archive_featured_entries_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_featured_entries_template]',
	'type'     => 'select',
	'columns'  => 2,
	'choices'  => bimber_get_archive_featured_entries_templates(),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) ) );

// Featured entries gutter.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_gutter]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_gutter'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_featured_entries_gutter', array(
	'label'    => __( 'Gutter', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_featured_entries_gutter]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) );


// Featured Entries Time range.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_time_range]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_time_range'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_featured_entries_time_range', array(
	'label'           => __( 'Time Range', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_featured_entries_time_range]',
	'type'            => 'select',
	'choices'         => bimber_get_archive_featured_entries_time_ranges(),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) );


// Featured Entries Hide Elements.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_hide_elements]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_hide_elements'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_featured_entries_hide_elements', array(
	'label'           => __( 'Hide Elements', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_featured_entries_hide_elements]',
	'choices'         => apply_filters( 'bimber_archive_featured_entries_hide_elements_choices', array(
		'shares'        => __( 'Shares', 'bimber' ),
		'views'         => __( 'Views', 'bimber' ),
		'comments_link' => __( 'Comments Link', 'bimber' ),
		'categories'    => __( 'Categories', 'bimber' ),
		'call_to_action'=> __( 'Call to Action', 'bimber' ),
	) ),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) ) );

// Featured Entries Call To Action Hide Buttons.
$wp_customize->add_setting( $bimber_option_name . '[archive_featured_entries_call_to_action_hide_buttons]', array(
	'default'           => $bimber_customizer_defaults['archive_featured_entries_call_to_action_hide_buttons'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_featured_entries_call_to_action_hide_buttons', array(
	'label'           => __( 'Call to Action - Hide Buttons', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_featured_entries_call_to_action_hide_buttons]',
	'choices'         => bimber_get_post_call_to_action_buttons(),
	'active_callback' => 'bimber_customizer_archive_has_featured_entries',
) ) );

/**
 * Check whether featured entries are enabled for archive pages
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_archive_has_featured_entries( $control ) {
	$type = $control->manager->get_setting( bimber_get_theme_id() . '[archive_featured_entries]' )->value();

	return 'none' !== $type;
}


// Divider.
$wp_customize->add_setting( 'bimber_archive_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_archive_divider', array(
	'section'  => 'bimber_posts_archive_section',
	'settings' => 'bimber_archive_divider',
	'html'     =>
		'<hr />
		<h2>' . esc_html__( 'Main Collection', 'bimber' ) . '</h2>',
) ) );

// Title.
$wp_customize->add_setting( $bimber_option_name . '[archive_title]', array(
	'default'           => $bimber_customizer_defaults['archive_title'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_title', array(
	'label'           => __( 'Title', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_title]',
	'type'            => 'text',
	'input_attrs'     => array(
		'placeholder' => __( 'Leave empty to use the default value', 'bimber' ),
	),
) );


// Hide title.
$wp_customize->add_setting( $bimber_option_name . '[archive_title_hide]', array(
	'default'           => $bimber_customizer_defaults['archive_title_hide'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_title_hide', array(
	'label'    => __( 'Hide Title', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_title_hide]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
) );


// Template.
$wp_customize->add_setting( $bimber_option_name . '[archive_template]', array(
	'default'           => $bimber_customizer_defaults['archive_template'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_archive_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_template]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_templates(),
	'columns'  => 3,
) ) );

// Sidebar.
$wp_customize->add_setting( $bimber_option_name . '[archive_sidebar_location]', array(
	'default'           => $bimber_customizer_defaults['archive_sidebar_location'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_archive_sidebar_location', array(
	'label'       => __( 'Sidebar Location', 'bimber' ),
	'section'     => 'bimber_posts_archive_section',
	'settings'    => $bimber_option_name . '[archive_sidebar_location]',
	'type'        => 'select',
	'choices'     => array(
		'left'          => _x( 'Left', 'sidebar location', 'bimber' ),
		'standard'      => _x( 'Right', 'sidebar location', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_archive_is_template_with_sidebar',
) );

/**
 * Check whether there are many comment types active
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_archive_is_template_with_sidebar( $control ) {
	$template = bimber_get_theme_option( 'archive', 'template' );
	return strpos( $template, 'sidebar' ) > -1 || strpos( $template, 'bunchy' ) > -1;
}

// Highlight items.
$wp_customize->add_setting( $bimber_option_name . '[archive_highlight_items]', array(
	'default'           => $bimber_customizer_defaults['archive_highlight_items'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_archive_highlight_items', array(
	'label'    => __( 'Highlight items', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_highlight_items]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
	'active_callback' => 'bimber_customizer_is_archive_list_template_selected',
) );

/**
 * Check whether archive template is a list
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_archive_list_template_selected( $control ) {
	$template = $control->manager->get_setting( bimber_get_theme_id() . '[archive_template]' )->value();
	$list_templates = array(
		'list-sidebar',
		'list-s-sidebar',
		'upvote-sidebar',
	);

	return in_array( $template, $list_templates );
}

// Highlight items offset.
$wp_customize->add_setting( $bimber_option_name . '[archive_highlight_items_offset]', array(
	'default'           => $bimber_customizer_defaults['home_highlight_items_offset'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_archive_highlight_items_offset', array(
	'label'    => __( 'Highlight item at position', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_highlight_items_offset]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_highlight_items_selected',
) );

// Highlight items repeat.
$wp_customize->add_setting( $bimber_option_name . '[archive_highlight_items_repeat]', array(
	'default'           => $bimber_customizer_defaults['archive_highlight_items_repeat'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_archive_highlight_items_repeat', array(
	'label'    => __( 'Repeat highlighted item after each X positions', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_highlight_items_repeat]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_highlight_items_selected',
) );

/**
 * Check whether archive highlight items selected
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_archive_highlight_items_selected( $control ) {
	if ( ! bimber_customizer_is_archive_list_template_selected( $control ) ) {
		return false;
	}

	return $control->manager->get_setting( bimber_get_theme_id() . '[archive_highlight_items]' )->value() === 'standard';
}

// archive inject embeds.
$wp_customize->add_setting( $bimber_option_name . '[archive_inject_embeds]', array(
	'default'           => $bimber_customizer_defaults['archive_inject_embeds'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_inject_embeds', array(
	'label'    => __( 'Inject embeds into featured media', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_inject_embeds]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
) );

// Posts Per Page.
$wp_customize->add_setting( $bimber_option_name . '[archive_posts_per_page]', array(
	'default'           => $bimber_customizer_defaults['archive_posts_per_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_posts_per_page', array(
	'label'    => __( 'Entries per page', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_posts_per_page]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Pagination.
$wp_customize->add_setting( $bimber_option_name . '[archive_pagination]', array(
	'default'           => $bimber_customizer_defaults['archive_pagination'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_pagination', array(
	'label'    => __( 'Pagination', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_pagination]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_pagination_types(),
) );


// Hide Elements.
$wp_customize->add_setting( $bimber_option_name . '[archive_hide_elements]', array(
	'default'           => $bimber_customizer_defaults['archive_hide_elements'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_hide_elements', array(
	'label'    => __( 'Hide Elements', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_hide_elements]',
	'choices'  => bimber_get_archive_elements_to_hide(),
) ) );

// Call To Action Hide Buttons.
$wp_customize->add_setting( $bimber_option_name . '[archive_call_to_action_hide_buttons]', array(
	'default'           => $bimber_customizer_defaults['archive_call_to_action_hide_buttons'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_archive_call_to_action_hide_buttons', array(
	'label'           => __( 'Call to Action - Hide Buttons', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_call_to_action_hide_buttons]',
	'choices'         => bimber_get_post_call_to_action_buttons(),
) ) );


// Newsletter.
$wp_customize->add_setting( $bimber_option_name . '[archive_newsletter]', array(
	'default'           => $bimber_customizer_defaults['archive_newsletter'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_newsletter', array(
	'label'    => __( 'Newsletter', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_newsletter]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_newsletter_options(),
) );

// Newsletter at position.
$wp_customize->add_setting( $bimber_option_name . '[archive_newsletter_after_post]', array(
	'default'           => $bimber_customizer_defaults['archive_newsletter_after_post'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_newsletter_after_post', array(
	'label'           => __( 'Inject newsletter at position', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_newsletter_after_post]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '2' ) ),
		'min'         => 1,
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_newsletter_checked',
) );

// Newsletter repeat.
$wp_customize->add_setting( $bimber_option_name . '[archive_newsletter_repeat]', array(
	'default'           => $bimber_customizer_defaults['archive_newsletter_repeat'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_newsletter_repeat', array(
	'label'           => __( 'Repeat newsletter after each X positions', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_newsletter_repeat]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '12' ) ),
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_newsletter_checked',
) );

/**
 * Check whether newsletter is enabled for archive pages
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_archive_newsletter_checked( $control ) {
	return $control->manager->get_setting( bimber_get_theme_id() . '[archive_newsletter]' )->value() === 'standard';
}


// Ad.
$wp_customize->add_setting( $bimber_option_name . '[archive_ad]', array(
	'default'           => $bimber_customizer_defaults['archive_ad'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_ad', array(
	'label'    => __( 'Ad', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_ad]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_ad_options(),
) );

// Ad edit link.
$wp_customize->add_setting( 'bimber_archive_ad_edit_link', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_archive_ad_edit_link', array(
    'section'  => 'bimber_posts_archive_section',
    'settings' => 'bimber_archive_ad_edit_link',
    'html'     => sprintf( __( '<a href="%s" target="_blank">%s</a>', 'bimber' ), esc_url( admin_url( 'admin.php?action=bimber_redirect_to_ad_settings&type=archive-injected' ) ), _x( 'Edit ad settings', 'Customizer Settings', 'bimber' ) ),
    'active_callback' => 'bimber_customizer_is_archive_ad_checked',
) ) );

// Ad at position.
$wp_customize->add_setting( $bimber_option_name . '[archive_ad_after_post]', array(
	'default'           => $bimber_customizer_defaults['archive_ad_after_post'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_ad_after_post', array(
	'label'           => __( 'Inject ad at position', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_ad_after_post]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '4' ) ),
		'min'         => 1,
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_ad_checked',
) );

// Ad repeat.
$wp_customize->add_setting( $bimber_option_name . '[archive_ad_repeat]', array(
	'default'           => $bimber_customizer_defaults['archive_ad_repeat'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_ad_repeat', array(
	'label'           => __( 'Repeat ad after every X positions', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_ad_repeat]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '12' ) ),
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_ad_checked',
) );

/**
 * Check whether ad is enabled for archive pages
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_archive_ad_checked( $control ) {
	return $control->manager->get_setting( bimber_get_theme_id() . '[archive_ad]' )->value() === 'standard';
}

// Product.
$wp_customize->add_setting( $bimber_option_name . '[archive_product]', array(
	'default'           => $bimber_customizer_defaults['archive_product'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_product', array(
	'label'    => __( 'Product', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings' => $bimber_option_name . '[archive_product]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_product_options(),
) );

// Product at position.
$wp_customize->add_setting( $bimber_option_name . '[archive_product_after_post]', array(
	'default'           => $bimber_customizer_defaults['archive_product_after_post'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_product_after_post', array(
	'label'           => __( 'Inject product at position', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_product_after_post]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '6' ) ),
		'min'         => 1,
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_product_checked',
) );

// Product repeat.
$wp_customize->add_setting( $bimber_option_name . '[archive_product_repeat]', array(
	'default'           => $bimber_customizer_defaults['archive_product_repeat'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_archive_product_repeat', array(
	'label'           => __( 'Repeat product after each X positions', 'bimber' ),
	'section'  => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_product_repeat]',
	'type'            => 'number',
	'input_attrs'     => array(
		'placeholder' => esc_html( sprintf( __( 'e.g., %s', 'bimber'), '12' ) ),
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_customizer_is_archive_product_checked',
) );

// Product category.
$wp_customize->add_setting( $bimber_option_name . '[archive_product_category]', array(
	'default'           => $bimber_customizer_defaults['archive_product_category'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'bimber_sanitize_multi_choice',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Select_Control( $wp_customize, 'bimber_archive_product_category', array(
	'label'           => __( 'Inject products from category', 'bimber' ),
	'description'     => __( 'You can choose more than one.', 'bimber' ),
	'section'         => 'bimber_posts_archive_section',
	'settings'        => $bimber_option_name . '[archive_product_category]',
	'choices'         => bimber_customizer_get_product_category_choices(),
	'active_callback' => 'bimber_customizer_is_archive_product_checked',
) ) );

/**
 * Check whether product is enabled for archive pages
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_archive_product_checked( $control ) {
	return $control->manager->get_setting( bimber_get_theme_id() . '[archive_product]' )->value() === 'standard';
}
