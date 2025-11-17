<?php
/**
 * Helper Functions
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Register class autoloader
 */
spl_autoload_register(function ( $class_name ) {
    if ( 0 === strpos( $class_name, 'Commentace\\' ) ) {
        // Split class into parts.
        $class_name_parts = explode( '\\', $class_name );

        // Remove root of the namespace.
        unset( $class_name_parts[0] );

        // Merge to build class name.
        $class_name = implode( '-', $class_name_parts );

        // To lowercase.
        $class_name = strtolower( $class_name );

        // Convert _ to -.
        $class_name = str_replace( '_', '-', $class_name );

        // Prepend with "class-" prefix.
        $class_name = 'class-' . $class_name;

        // Directories to search through.
        $source_dirs = array(
            'includes/lib/',
            'includes/lib/comment-types/',
            'includes/lib/votes/',
            'includes/lib/widgets/',
            'includes/admin/lib/',
            'includes/admin/settings/lib/',
            'includes/admin/settings/lib/fields/',
        );

        foreach ( $source_dirs as $source_dir ) {
            $file_path = sprintf( '%s%s%s.php', plugin_dir_path( CACE_PLUGIN_FILENAME ), $source_dir, $class_name );

            if ( file_exists( $file_path ) ) {
                require_once $file_path;
            }
        }
    }
});

/**
 * Get plugin main controller instance
 *
 * @return Plugin_Controller
 */
function plugin() {
    return Plugin_Controller::get_instance();
}

/**
 * Check whether the plugin is active and plugin can rely on it
 *
 * @param string $plugin Base plugin path.
 *
 * @return bool
 */
function can_use_plugin( $plugin ) {
    // Detect plugin. For use on Front End only.
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    return is_plugin_active( $plugin );
}

/**
 * Load a template part into a template
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args Optional. Additional arguments passed to the template.
 *                     Default empty array.
 */
function get_template_part( $slug, $name = null, $args = array() ) {
	// Trim off any slashes from the slug.
	$slug = ltrim( $slug, '/' );

	if ( empty( $slug ) ) {
		return;
	}

	$parent_dir_path = trailingslashit( get_template_directory() );
	$child_dir_path  = trailingslashit( get_stylesheet_directory() );

	$files = array(
		$child_dir_path . 'comment-ace/' . $slug . '.php',
		$parent_dir_path . 'comment-ace/' . $slug . '.php',
		plugin()->get_dir() . 'templates/' . $slug . '.php',
	);

	if ( ! empty( $name ) ) {
		array_unshift(
			$files,
			$child_dir_path . 'comment-ace/' . $slug . '-' . $name . '.php',
			$parent_dir_path . 'comment-ace/' . $slug . '-' . $name . '.php',
            plugin()->get_dir() . 'templates/' . $slug . '-' . $name . '.php'
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
		load_template( $located, false, $args );
	}
}

/**
 * Assist pagination by returning correct page number
 *
 * @return int Current page number
 */
function get_paged() {
    global $wp_query;

    // Check the query var.
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );

        // Check query paged.
    } elseif ( ! empty( $wp_query->query['paged'] ) ) {
        $paged = $wp_query->query['paged'];
    }

    // Paged found.
    if ( ! empty( $paged ) ) {
        return (int) $paged;
    }

    // Default to first page.
    return 1;
}

/**
 * Return client's IP
 *
 * @return string
 */
function get_client_ip() {
    $ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

    if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return sanitize_text_field( $ip );
}

/**
 * Return client's host name
 *
 * @return string
 */
function get_client_host() {
    $host = ! empty( $_SERVER['REMOTE_HOST'] ) ? $_SERVER['REMOTE_HOST'] : '';

    if ( ! empty( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }

    return sanitize_text_field( $host );
}

function get_iso_8601_utc_offset() {
	$offset  = get_option( 'gmt_offset' );
	$hours   = (int) $offset;
	$minutes = abs( ( $offset - (int) $offset ) * 60 );

	return sprintf( '%+03d:%02d', $hours, $minutes );
}



/**
 * Return the correct admin URL based on WordPress configuration.
 *
 * @param string $path Optional. The sub-path under /wp-admin to be appended to the admin URL.
 *
 * @param string $scheme The scheme to use. Default is 'admin', which
 *                       obeys {@link force_ssl_admin()} and {@link is_ssl()}. 'http'
 *                       or 'https' can be passed to force those schemes.
 *
 * @return string        Admin url link with optional path appended.
 */
function get_admin_url( $path = '', $scheme = 'admin' ) {
    // Links belong in network admin.
    if ( is_network_admin() ) {
        $url = network_admin_url( $path, $scheme );

        // Links belong in site admin.
    } else {
        $url = admin_url( $path, $scheme );
    }

    return $url;
}

/**
 * Check whether the post type is enabled and can be used to load CommentAce on it
 *
 * @param string $post_type         Optional. Post type.
 *
 * @return bool
 */
function is_post_type_enabled( $post_type = '' ) {
    if ( ! $post_type ) {
        $post_type = get_post_type();
    }

    $enabled_post_types = get_enabled_post_types();

    $enabled = ! empty( $enabled_post_types ) && in_array( $post_type, $enabled_post_types );

    return apply_filters( 'cace_post_type_enabled', $enabled, $enabled_post_types, $post_type );
}

function cace_htmlspecialchars( $input ) {
    if ( $input ) {
        $input = htmlspecialchars( $input );
    }

    return $input;
}