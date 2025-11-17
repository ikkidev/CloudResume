<?php
/**
 * Download Monitor Customizer integration
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

add_filter( 'bimber_customizer_defaults',       'bimber_dm_add_customizer_defaults' );
add_action( 'bimber_after_customize_register',   'bimber_dm_register_customizer_options', 10, 1 );

// Register new element to hide.
add_filter( 'bimber_post_hide_elements_choices',                        'bimber_dm_customizer_register_choice' ); // Single post.
add_filter( 'bimber_home_hide_elements_choices',                        'bimber_dm_customizer_register_choice' ); // Home > Main.
add_filter( 'bimber_home_featured_entries_hide_elements_choices',       'bimber_dm_customizer_register_choice' ); // Home > Featured.
add_filter( 'bimber_archive_hide_elements_choices',                     'bimber_dm_customizer_register_choice' ); // Archives > Main.
add_filter( 'bimber_archive_featured_entries_hide_elements_choices',    'bimber_dm_customizer_register_choice' ); // Archive > Featured.
add_filter( 'bimber_search_hide_elements_choices',                      'bimber_dm_customizer_register_choice' ); // Search.
add_filter( 'bimber_post_collection_hide_elements_choices',             'bimber_dm_customizer_register_choice' ); // Post collections (You May Also Like, More From, Dont Miss).


/**
 * Register plugin defaults
 *
 * @param array $defaults       Default values.
 *
 * @return array
 */
function bimber_dm_add_customizer_defaults( $defaults ) {
	$defaults['dm_downloads_threshold']     = 10;
	$defaults['dm_download_method']         = 'direct';
	$defaults['dm_download_page']           = '';
	$defaults['dm_download_delay']          = 10;

	return $defaults;
}

/**
 * Add plugin panel
 *
 * @param WP_Customize_Manager $wp_customize        Customizer instance.
 */
function bimber_dm_register_customizer_options( $wp_customize ) {

	$defaults = bimber_get_customizer_defaults();

	/**
	 * Sections
	 */

	$wp_customize->add_section( 'bimber_dm_general_section', array(
		'title'    => __( 'Download Monitor Plugin', 'bimber' ),
		'priority' => 540,
	) );

	/**
	 * Controls
	 */

	$option_name = bimber_get_theme_id();

	// General > Threshold.
	$wp_customize->add_setting( $option_name . '[dm_downloads_threshold]', array(
		'default'           => $defaults['dm_downloads_threshold'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'bimber_dm_downloads_threshold', array(
		'label'       => __( 'Hide downloads', 'bimber' ),
		'description' => __( 'If you fill in any number here, the downloads for a specific post are not shown until the download count of this number is reached.', 'bimber' ),
		'section'     => 'bimber_dm_general_section',
		'settings'    => $option_name . '[dm_downloads_threshold]',
		'type'        => 'number',
		'input_attrs' => array(
			'class' => 'small-text',
		),
	) );

	// General > Download method.
	$wp_customize->add_setting( $option_name . '[dm_download_method]', array(
		'default'           => $defaults['dm_download_method'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_dm_download_method', array(
		'label'       => __( 'Download method', 'bimber' ),
		'section'     => 'bimber_dm_general_section',
		'settings'    => $option_name . '[dm_download_method]',
		'type'        => 'select',
		'choices'     => array(
			'direct'        => __( 'Direct download', 'bimber' ),
			'download_page' => __( 'Download page', 'bimber' ),
		),
	) );


	// General > Download page.
	$wp_customize->add_setting( $option_name . '[dm_download_page]', array(
		'default'           => $defaults['dm_download_page'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'bimber_dm_download_page', array(
		'label'             => __( 'Download page', 'bimber' ),
		'description'       => __( 'Run a download action from a separate page.', 'bimber' ),
		'section'           => 'bimber_dm_general_section',
		'settings'          => $option_name . '[dm_download_page]',
		'type'              => 'dropdown-pages',
		'active_callback'   => 'bimber_customizer_dm_is_download_page_method',
	) );

	// General > Download delay.
	$wp_customize->add_setting( $option_name . '[dm_download_delay]', array(
		'default'           => $defaults['dm_download_delay'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'bimber_dm_download_delay', array(
		'label'             => __( 'Download delay (in seconds)', 'bimber' ),
		'description'       => __( 'Set to 0 to disable the delay counter.', 'bimber' ),
		'section'           => 'bimber_dm_general_section',
		'settings'          => $option_name . '[dm_download_delay]',
		'type'              => 'number',
		'active_callback'   => 'bimber_customizer_dm_is_download_page_method',
	) );
}

/**
 * Add the "Downloads" entry to the choices list
 *
 * @param array $choices    Select choices.
 *
 * @return array
 */
function bimber_dm_customizer_register_choice( $choices ) {
	// Insert before the Comments Link.
	if ( isset( $choices['comments_link'] ) ) {
		$new_choices = array();

		// Rewrite array as there is no easy way to insert new element into assoc array at the key position.
		foreach( $choices as $choice_id => $choice_label ) {
			// Insert before.
			if ( 'comments_link' === $choice_id ) {
				$new_choices['downloads'] = __( 'Downloads', 'bimber' );
			}

			$new_choices[ $choice_id ] = $choice_label;
		}

		$choices = $new_choices;
	// If not exists, add at the end.
	} else {
		$choices['downloads'] = __( 'Downloads', 'bimber' );
	}

	return $choices;
}

/**
 * Check whether the download method is set to the "Download page"
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_dm_is_download_page_method( $control ) {
	$method = $control->manager->get_setting( bimber_get_theme_id() . '[dm_download_method]' )->value();

	return 'download_page' === $method;
}
