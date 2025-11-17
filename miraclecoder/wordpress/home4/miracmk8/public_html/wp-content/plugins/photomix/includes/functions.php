<?php
/**
 * Common Functions
 *
 * @package photomix
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Plugin acitvation
 */
function photomix_activate() {}

/**
 * Plugin deacitvation
 */
function photomix_deactivate() {}

/**
 * Plugin uninstallation
 */
function photomix_uninstall() {}

/**
 * Load a template part into a template
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 */
function photomix_get_template_part( $slug, $name = null ) {
	// Trim off any slashes from the slug.
	$slug = ltrim( $slug, '/' );

	if ( empty( $slug ) ) {
		return;
	}

	$parent_dir_path = trailingslashit( get_template_directory() );
	$child_dir_path  = trailingslashit( get_stylesheet_directory() );

	$files = array(
		$child_dir_path . 'photomix/' . $slug . '.php',
		$parent_dir_path . 'photomix/' . $slug . '.php',
		photomix_get_plugin_dir() . 'templates/' . $slug . '.php',
	);

	if ( ! empty( $name ) ) {
		array_unshift(
			$files,
			$child_dir_path . 'photomix/' . $slug . '-' . $name . '.php',
			$parent_dir_path . 'photomix/' . $slug . '-' . $name . '.php',
			photomix_get_plugin_dir() . 'templates/' . $slug . '-' . $name . '.php'
		);
	}

	$located = '';

	foreach ( $files as $file ) {
		if ( empty( $file ) ) {
			continue;
		}

		if ( file_exists( $file ) ) {
			$located = $file;
			break;
		}
	}

	if ( strlen( $located ) ) {
		load_template( $located, false );
	}
}

/**
 * Return plugin default options
 *
 * @return array
 */
function photomix_get_default_options() {
	return apply_filters( 'photomix_default_options', array(
		'editor_width'		=> 800,
		'format' 			=> '16_9',
		'max_width' 		=> 800,
		'max_height'		=> 450,
		'background_color'	=> '#d9d9d9',
		'shape_color'		=> '#08ffc6',
		'gutter'			=> 'standard',
		'gutter_color'		=> '#ffffff',
	) );
}

/**
 * Return plugin options
 *
 * @param string $name			Optional. Option name.
 *
 * @return array|mixed
 */
function photomix_get_options( $name = '' ) {
	$options = wp_parse_args( get_option( 'photomix_options', array() ), photomix_get_default_options() );

	if ($name) {
		return isset( $options[ $name ] ) ? $options[ $name ] : false;
	}

	return $options;
}

/**
 * Return calculated editor's height based on width and format values
 *
 * @param $width
 *
 * @return int
 */
function photomix_get_format_height( $width ) {
	$format = photomix_get_options( 'format' );

	switch( $format ) {
		case '4_3':
			return round( $width * 3 / 4 );

		case '16_9':
			return round( $width * 9 / 16 );
	}

	return 0;
}

add_filter( 'admin_body_class', 'photomix_body_class' );

/**
 * Set css class for body for editor
 *
 * @param  array $classes  CSS classes.
 * @return array
 */
function photomix_body_class( $classes ) {
	$photomix_template = filter_input( INPUT_GET, 'photomix-template' );
	if ( ! empty( $photomix_template ) ) {
		$classes .= ' media_page_photomix-new-image-edit';
	}
	return $classes;
}
