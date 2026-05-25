<?php
/**
 * Demo content functions
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

add_action( 'admin_enqueue_scripts', 'bimber_demo_content_enqueue_scripts' );

add_action( 'admin_init',   'bimber_handle_import_action' );
add_action( 'init',         'bimber_flush_rewrite_rules_after_demo_import' );

// Ajax.
add_action( 'wp_ajax_bimber_demo_import_started',   'bimber_ajax_demo_import_started' );
add_action( 'wp_ajax_bimber_demo_import_ended',     'bimber_ajax_demo_import_ended' );

// Admin actions.
add_action( 'admin_action_bimber_import_demo',              'bimber_import_demo' );
add_action( 'admin_action_bimber_import_demo_image',        'bimber_import_demo_image' );
add_action( 'admin_action_bimber_import_demo_images_start', 'bimber_import_demo_images_start' );
add_action( 'admin_action_bimber_import_demo_images_end',   'bimber_import_demo_images_end' );
add_action( 'admin_action_bimber_uninstall_demo_data',      'bimber_uninstall_demo_data' );

// Import scope.
add_action( 'import_start', 'bimber_import_start' );
add_action( 'import_end', 'bimber_import_end' );
add_action( 'wp_import_posts', 'bimber_import_posts' );

function bimber_demo_content_enqueue_scripts( $hook ) {
	if ( 'appearance_page_theme-options' === $hook ) {
		wp_enqueue_script( 'bimber-demo-installer', trailingslashit( get_parent_theme_file_uri() ) . 'includes/admin/demo-content/js/demo-installer.js', array( 'jquery' ), bimber_get_theme_version(), true );
	}
}

/**
 * Load WP Importers (if plugin Wordpress Importer active) but prevent admin import action
 */
function bimber_handle_import_action() {
	// -- Explanation
	// if $_GET['import'] is set, WP defines the WP_LOAD_IMPORTERS const (in wp-admin/admin.php).
	// This, in turn, loads WP_Import class and triggers admin import action.
	// We want to use only WP_Import class to import our demo content, import action is redundant.
	// To achieve this, we have to unset $_GET['import'] after defining const but before action call.
	// The "admin_init" hook is a right place to do that.
	if ( isset( $_GET['import'] ) && 'bimber' === $_GET['import'] ) { // Input var okey.
		unset( $_GET['import'] ); // Input var okey.
	}
}

/**
 * Import demo data
 */
function bimber_import_demo() {
	$allowed_types = array( 'all', 'content', 'theme-options', 'widgets' );
	$demo          = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : ''; // Input var okey.
	$type_str      = isset( $_GET['import-type'] ) ? sanitize_text_field( wp_unslash( $_GET['import-type'] ) ) : ''; // Input var okey.
	$types         = explode( ',', $type_str );

	foreach ( $types as $type ) {
		if ( ! in_array( $type, $allowed_types, true ) ) {
			wp_die(
				'<h1>' . esc_html__( 'Cheatin&#8217; uh?', 'bimber' ) . '</h1>
			<p>' . sprintf( esc_html__( 'Demo data import type not allowed. Allowed values: %s.', 'bimber' ), esc_html( implode( ', ', $allowed_types ) ) ) . '</p>',
				403
			);
		}
	}

	require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/admin/demo-content/lib/class-bimber-demo-data.php';

	$demo_data = new Bimber_Demo_Data( $demo );
	$response = array();

	$succeed = true;

	if ( in_array( 'all', $types ) ) {
		$res = $demo_data->import_all();

		if ( 'error' === $res['status'] ) {
			$succeed = false;
		}

		// Normalize.
		$types = array(
			'content',
			'theme-options',
			'widgets',
		);

		$response[] = $res;
	} else {
		if ( in_array( 'content', $types ) ) {
			$res = $demo_data->import_content();

			if ( 'error' === $res['status'] ) {
				$succeed = false;
			}

			$response[] = $res;
		}

		if ( in_array( 'theme-options', $types ) ) {
			$res = $demo_data->import_theme_options();

			if ( 'error' === $res['status'] ) {
				$succeed = false;
			}

			$response[] = $res;
		}

		if ( in_array( 'widgets', $types ) ) {
			$res = $demo_data->import_widgets();

			if ( 'error' === $res['status'] ) {
				$succeed = false;
			}

			$response[] = $res;
		}
	}

	$only_theme_options = ( 1 === count( $types ) && in_array( 'theme-options', $types ) );

	// Demo data installed successfully.
	// Theme options can't be uninstalled so there is no sense to tag demo as installed.
	// Theme options can be installed more that once, it's like options reset.
	if ( $succeed && ! $only_theme_options ) {
		$demos_installed = get_option( 'bimber_demos_installed', array() );

		if ( ! isset( $demos_installed[ $demo ] ) ) {
			$demos_installed[ $demo ] = array();
		}

		$demos_installed[ $demo ]['types'] = $types;

		update_option( 'bimber_demos_installed', $demos_installed );
	}

	set_transient( 'bimber_import_demo_response', $response );

	wp_redirect( admin_url( 'themes.php?page=theme-options&group=demos' ) );
}

function bimber_import_demo_images_start() {
	$demo         = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : '';
	$path         = trailingslashit( get_parent_theme_file_path() ) . 'dummy-data/' .$demo . '/dummy-data.xml';
	$images_count = 0;
	$res          = 'Demo data loaded';

	// WordPress Importer plugin has to be loaded.
	if ( class_exists( 'WP_Import' ) ) {
		require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/admin/demo-content/lib/class-bimber-demo-image-importer.php';

		$importer    = new Bimber_Demo_Image_Importer();
		$import_data = $importer->parse( $path );

		if ( ! is_wp_error( $import_data ) ) {
			update_option( 'bimber_import_data', $import_data );

			$images_count = $importer->get_images_count( $import_data );
		} else {
			$res = $import_data->get_error_message();
		}
	} else {
		$res = 'WP_Import class not found!';
	}

	$config = array(
		'response' => $res,
		'count'    => $images_count,
	);

	echo wp_json_encode( $config );
	exit;
}

function bimber_import_demo_images_end() {
	// Clear cache.
	delete_option( 'bimber_import_data' );

	$config = array(
		'response' => 'Cache purged.',
	);

	echo wp_json_encode( $config );
	exit;
}

/**
 * Import demo data image
 */
function bimber_import_demo_image() {
	$nb   = isset( $_GET['nb'] ) ? intval( wp_unslash( $_GET['nb'] ) ) : 0;
	$demo = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : '';

	if ( $nb <= 0 ) {
		wp_die(
			'<h1>' . esc_html__( 'Cheatin&#8217; uh?', 'bimber' ) . '</h1>
			<p>' . esc_html_e( 'Image number has to be specified.', 'bimber' ) . '</p>',
			403
		);
	}

	// WordPress Importer plugin has to be loaded.
	if ( class_exists( 'WP_Import' ) ) {
		require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/admin/demo-content/lib/class-bimber-demo-image-importer.php';

		add_filter( 'wp_import_posts', 'bimber_import_change_attachment_path', 10, 2 );

		// Import.
		$importer = new Bimber_Demo_Image_Importer();

		$import_data = get_option( 'bimber_import_data' );

		$importer->fetch_attachments = true;
		$importer->process_image_nb = $nb;
		$importer->demo = $demo;

		$importer->import( $import_data );
	}

	exit;
}

function bimber_import_change_attachment_path( $posts ) {
	foreach ( $posts as $index => $post ) {
		if ( 'attachment' === $post['post_type'] ) {
			$remote_url = ! empty( $post['attachment_url'] ) ? $post['attachment_url'] : $post['guid'];

			// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
			if ( preg_match( '|^/[\w\W]+$|', $remote_url ) ) {
				$remote_url = rtrim( get_home_url(), '/' ) . $remote_url;

				$posts[ $index ]['attachment_url'] = $remote_url;
			}
		}
	}

	return $posts;
}

/**
 * Return url to uninstall demo data action
 *
 * @param string $demo          Demo id.
 *
 * @return string
 */
function bimber_get_uninstall_demo_data_url( $demo ) {
	return admin_url( 'admin.php?action=bimber_uninstall_demo_data&uninstall-type=%TYPE%&demo=' . $demo );
}

/**
 * Return url to import demo content action
 *
 * @param string $demo
 *
 * @return string
 */
function bimber_get_import_demo_content_url( $demo ) {
	return admin_url( 'admin.php?action=bimber_import_demo&import-type=content&demo=' . $demo . '&import=bimber' );
}

/**
 * Return url to import demo theme options action
 *
 * @param string $demo
 *
 * @return string
 */
function bimber_get_import_demo_url( $demo ) {
	return admin_url( 'admin.php?action=bimber_import_demo&import-type=%TYPE%&demo=' . $demo . '&import=bimber' );
}

/**
 * Return url to import demo all action
 *
 * @param string $demo
 *
 * @return string
 */
function bimber_get_import_demo_all_url( $demo ) {
	return admin_url( 'admin.php?action=bimber_import_demo&import-type=all&demo=' . $demo . '&import=bimber' );
}

/**
 * Demo import started
 */
function bimber_ajax_demo_import_started() {
	$expire_in_10min = 10 * 60;

	set_transient( '_bimber_demo_import_started', true, $expire_in_10min );

	echo wp_json_encode( array(
		'status' => 'success',
	) );
	exit;
}

/**
 * Demo import ended
 */
function bimber_ajax_demo_import_ended() {
	delete_transient( '_bimber_demo_import_started' );

	echo wp_json_encode( array(
		'status' => 'success',
	) );
	exit;
}

/**
 * Flush rewrite rules after finishing demo import
 */
function bimber_flush_rewrite_rules_after_demo_import() {
	$import_response = get_transient( 'bimber_import_demo_response' );

	if ( false !== $import_response ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( get_option('permalink_structure') );
		$wp_rewrite->flush_rules();
	}
}

function bimber_import_start() {
	add_action( 'create_term',          'bimber_mark_demo_terms', 10, 3 );
	add_action( 'wp_insert_post',       'bimber_mark_demo_posts', 10, 4 );
}

function bimber_import_end() {
	remove_action( 'create_term',       'bimber_mark_demo_terms', 10 );
	remove_action( 'wp_insert_post',    'bimber_mark_demo_posts', 10 );
}

/**
 * Mark all terms from our demo content as demo terms.
 *
 * EXPLANATION
 * It's implemented this way because there is a bug in import_categories() function, in the WP Importer.
 * WP_Imported does NOT process correctly category meta data.
 *
 * @param int $term_id  Term id.
 */
function bimber_mark_demo_terms( $term_id, $tt_id = '', $taxonomy = '' ) {
	require_once BIMBER_ADMIN_DIR . 'lib/class-bimber-import-export.php';

	$demo_id = Bimber_Import_Export::$demo;

	if ( in_array( $taxonomy, array( 'category', 'post_tag' ) ) ) {
		// When two demos share the same tag, the term will always belong to the latter.
		update_term_meta( $term_id, '_g1_demo_data', $demo_id );
	}
}

function bimber_mark_demo_posts( $post_id ) {
	require_once BIMBER_ADMIN_DIR . 'lib/class-bimber-import-export.php';

	$demo_id = Bimber_Import_Export::$demo;

	// When two demos share the same post, the post will always belong to the latter.
	update_post_meta( $post_id, '_g1_demo_data', $demo_id );
}

function bimber_uninstall_demo_data() {
	$allowed_types = array( 'all', 'content', 'widgets' );
	$demo          = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : ''; // Input var okey.
	$type_str      = isset( $_GET['uninstall-type'] ) ? sanitize_text_field( wp_unslash( $_GET['uninstall-type'] ) ) : ''; // Input var okey.
	$types         = explode( ',', $type_str );

	foreach ( $types as $type ) {
		if ( ! in_array( $type, $allowed_types, true ) ) {
			wp_die(
				'<h1>' . esc_html__( 'Cheatin&#8217; uh?', 'bimber' ) . '</h1>
			<p>' . sprintf( esc_html__( 'Demo data uninstall type not allowed. Allowed values: %s.', 'bimber' ), esc_html( implode( ', ', $allowed_types ) ) ) . '</p>',
				403
			);
		}
	}

	$status          = '';
	$demos_installed = get_option( 'bimber_demos_installed', array() );

	if ( isset( $demos_installed[ $demo ] ) ) {
		if ( in_array( 'all', $types ) ) {
			// Normalize.
			$types = array( 'content', 'widgets' );
		}

		if ( in_array( 'content', $types ) ) {
			bimber_delete_demo_posts( $demo );
			bimber_delete_demo_terms( $demo );

			$found_index = array_search( 'content', $types );

			if ( false !== $found_index ) {
				unset( $types[ $found_index ] );
			}
		}

		if ( in_array( 'widgets', $types ) ) {
			bimber_delete_demo_widgets( $demo );

			$found_index = array_search( 'widgets', $types );

			if ( false !== $found_index ) {
				unset( $types[ $found_index ] );
			}
		}

		if ( empty( $types ) ) {
			$status = 'demo-uninstalled';

			unset( $demos_installed[ $demo ] );

			update_option( 'bimber_demos_installed', $demos_installed );
		}
	}

	wp_safe_redirect( admin_url( 'themes.php?page=theme-options&group=demos&status=' . $status ) );
}

function bimber_delete_demo_posts( $demo ) {
	$deleted = 0;

	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_status' => 'any',
			'post_type' => get_post_types('', 'names'),
			'meta_query' => array(
				array(
					'key'       => '_g1_demo_data',
					'value'     => $demo,
					'compare'   => '=',
				)
			)
		)
	);

	foreach( $posts as $post ) {
		if ( wp_delete_post( $post->ID, true ) ) {
			$deleted++;
		}
	}

	return $deleted;
}

function bimber_delete_demo_terms( $demo ) {
	$deleted = 0;

	$taxonomies = get_taxonomies();

	$taxonomy_list = array_keys( $taxonomies );

	$args = array(
		'taxonomy'      => $taxonomy_list,
		'numberposts'   => -1,
		'hide_empty'    => 0,
		'meta_key'      => '_g1_demo_data',
		'meta_value'    => $demo
	);

	$terms = get_terms( $args);

	foreach( $terms as $term ){
		if ( wp_delete_term( $term->term_id, $term->taxonomy ) ) {
			$deleted++;
		}
	}

	return $deleted;
}

function bimber_delete_demo_widgets( $demo ) {
	$deleted = 0;

	$demo_widgets = get_option( 'bimber_demo_widgets', array() );

	if ( ! isset( $demo_widgets[ $demo ] ) || empty( $demo_widgets[ $demo ] ) ) {
		return $deleted;
	}

	$widgets_to_delete = $demo_widgets[ $demo ];

	$sidebars_array = get_option( 'sidebars_widgets' );

	foreach(  $widgets_to_delete as $widget_id => $widget_data ) {
		$sidebar_id      = $widget_data['sidebar_id'];
		$widget_index    = $widget_data['widget_index'];
		$widget_group_id = $widget_data['widget_group'];

		$widget_group = get_option( 'widget_' . $widget_group_id );

		if ( isset( $widget_group[ $widget_index ] ) ) {
			unset( $widget_group[ $widget_index ] );

			// Update widget group data.
			update_option( 'widget_' . $widget_group_id, $widget_group );

			if ( isset( $sidebars_array[ $sidebar_id ] ) ) {
				$found_index = array_search( $widget_id, $sidebars_array[ $sidebar_id ] );

				if ( false !== $found_index ) {
					unset( $sidebars_array[ $sidebar_id ][ $found_index ] );
				}
			}

			unset( $demo_widgets[ $demo ][ $widget_id ] );

			$deleted++;
		}
	}

	// Update widget to sidebar relation.
	update_option( 'sidebars_widgets', $sidebars_array );

	// Update demo widgets.
	update_option( 'bimber_demo_widgets', $demo_widgets );

	return $deleted;
}

function bimber_import_posts( $posts ) {
	$menu_items = array();

	foreach( $posts as $i => $post ) {
		if ( 'nav_menu_item' == $post['post_type'] ) {
			$menu_slug = false;

			if ( isset( $post['terms'] ) ) {
				// Loop through terms, assume first nav_menu term is correct menu.
				foreach ( $post['terms'] as $term ) {
					if ( 'nav_menu' == $term['domain'] ) {
						$menu_slug = $term['slug'];
						break;
					}
				}
			}

			// No nav_menu term associated with this menu item.
			if ( ! $menu_slug ) {
				// echo 'Menu item skipped due to missing menu slug';
				continue;
			}

			$menu_id = term_exists( $menu_slug, 'nav_menu' );

			if ( is_array( $menu_id ) ) {
				$menu_id = $menu_id['term_id'];
			}

			if ( ! $menu_id ) {
				// echo 'Menu item skipped due to missing menu';
				continue;
			}

			// Store menu items.
			if ( ! isset( $menu_items[ $menu_id ] ) ) {
				$menu_items[ $menu_id ] = get_objects_in_term( $menu_id, 'nav_menu' );
			}

			// List of menu item (post) ids.
			$existing_items = $menu_items[ $menu_id ];

			// Menu item is a page? If so, it has no slug in the post_name field. Post id is there instead.
			if ( is_numeric( $post['post_name'] ) ) {
				$post_page_id = 0;

				// Look through post meta to related page id.
				foreach ( $post['postmeta'] as $postmeta ) {
					if ( '_menu_item_object_id' === $postmeta['key'] ) {
						$post_page_id = $postmeta['value'];
					}
				}

				if ( $post_page_id === 0 ) {
					// echo 'Post page id not set!';
					continue;
				}

				$post_name = '';

				// Look through all posts for that page.
				foreach ( $posts as $post ) {
					if ( $post_page_id == $post['post_id'] ) {
						$post_name = $post['post_name'];
						break;
					}
				}

				if ( empty( $post_name ) ) {
					// echo 'Post name not found!';
					continue;
				}

				// Call db to check if that page already exists.
				$found_posts = get_posts( array(
					'name'           => $post_name,
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'posts_per_page' => 1
				) );

				if ( empty( $found_posts ) ) {
					// echo 'Page not found';
					continue;
				}

				$found_post = $found_posts[0];

				$db_page_id = $found_post->ID;

				// Check if menu item exists by checking if post with meta _menu_item_object_id = $db_page_id.
				$found_posts = get_posts( array(
					'post_type'      => 'nav_menu_item',
					'post_status'    => 'publish',
					'post__in'       => $existing_items,
					'posts_per_page' => 1,
					'meta_key'       => '_menu_item_object_id',
					'meta_value'     => $db_page_id,
					'orderby'        => 'ID',
					'order'          => 'ASC',
				) );
			} else {
				// Get post by name check if it's already an item of the menu.
				$found_posts = get_posts( array(
					'name'           => $post['post_name'],
					'post_type'      => 'nav_menu_item',
					'post_status'    => 'publish',
					'posts_per_page' => 1
				) );
			}

			if ( empty( $found_posts ) ) {
				// echo 'Menu item not found (name: ' . $post['post_name'] . '). It\'s not a duplicate';
				continue;
			}

			$found_post = $found_posts[0];

			// Menu items already exists, remove it from post list to process.
			if ( in_array( $found_post->ID, $existing_items ) ) {
				// echo 'Menu item removed (' . $found_post->ID . ').';
				unset( $posts[ $i ] );
			}
		}
	}

	return $posts;
}

function bimber_is_demo_data_installed( $installed_types, $check_type ) {
	if ( in_array( 'all', $installed_types ) ) {
		return true;
	}

	foreach ( $installed_types as $installed_type ) {
		if ( $installed_type === $check_type ) {
			return true;
		}
	}

	return false;
}