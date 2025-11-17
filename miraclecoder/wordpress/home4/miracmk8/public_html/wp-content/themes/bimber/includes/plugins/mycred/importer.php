<?php
/**
 * MyCred import functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

add_action( 'wp_ajax_bimber_mycred_import', 'bimber_mycred_ajax_import' );
add_action( 'wp_ajax_bimber_mycred_import_reset', 'bimber_mycred_ajax_import_reset' );

/**
 * Import MyCred Ajax handler.
 */
function bimber_mycred_ajax_import() {
	check_ajax_referer( 'bimber_mycred_import_nonce', 'security' );

	if ( filter_var( $_POST['options']['import_mycred_ranks'], FILTER_VALIDATE_BOOLEAN ) ) {
		$args = array(
			'post_type' 	=> 'mycred_rank',
			'posts_per_page'   => -1,
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		}
	}

	$modules = apply_filters( 'bimber_mycred_modules_list', array() );
	bimber_mycred_import_setup_hooks();
	foreach ( $modules as $module ) {
		$perform = filter_var( $_POST['options'][ 'import_mycred_' . $module ], FILTER_VALIDATE_BOOLEAN );

		if ( $perform ) {
			require_once BIMBER_PLUGINS_DIR . 'mycred/imports/' . $module . '.php';
			do_action( 'bimber_mycred_import_' . $module );
		}
	}
	do_action( 'bimber_mycred_import_done' );
	echo 1;
	exit;
}

/**
 * Import MyCred Ajax handler.
 */
function bimber_mycred_ajax_import_reset() {
	check_ajax_referer( 'bimber_mycred_import_nonce_reset', 'security' );

	bimber_reset_mycred();
	echo 1;
	exit;
}

/**
 * Register SVG as an allowed mime type.
 *
 * @param array $mimes Mime types.
 * @return array
 */
function bimber_mycred_allow_svg( $mimes ) {
	if ( ! bimber_can_use_plugin( 'safe-svg/safe-svg.php' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}

/**
 * Create a mycred badge
 *
 * @param array $args  Arguments.
 */
function bimber_mycred_add_badge( $args ) {
	$perform = filter_var( $_POST['options']['import_mycred_badges'], FILTER_VALIDATE_BOOLEAN );
	if ( ! $perform ) {
		return;
	}

	if ( isset( $args['menu_order'] ) ){
		$order = (int) $args['menu_order'];
	} else {
		$order = 10;
	}

	$post = array(
		'post_title'    => wp_strip_all_tags( $args['name'] ),
		'post_status'   => 'publish',
		'post_type' 	=> 'mycred_badge',
		'post_excerpt'	=> $args['excerpt'],
		'menu_order'	=> $order,
	);
	$post_id = wp_insert_post( $post );
	// convert image names to attachement ids before we add them to meta.
	$args['main_image'] = bimber_mycred_sideload_image( $args['main_image'], $post_id );
	if ( null === $args['main_image'] ) {
		return;
	}
	foreach ( $args['levels'] as $i => $level ) {
		$level['attachment_id'] = bimber_mycred_sideload_image( $level['attachment_id'], $post_id);
		if ( null === $level['attachment_id'] ) {
			return;
		}
		$args['levels'][$i] = $level;
	}

	update_post_meta( $post_id, 'manual_badge', $args['manual'] );
	update_post_meta( $post_id, 'main_image', $args['main_image'] );
	update_post_meta( $post_id, 'badge_prefs', $args['levels'] );
	if ( isset( $args['importslug'] ) ) {
		update_post_meta( $post_id, '_bimber_mycred_import_slug', $args['importslug'] );
	}
}

/**
 * Create a mycred rank
 *
 * @param array $args  Arguments.
 */
function bimber_mycred_add_rank( $args ) {
	$perform = filter_var( $_POST['options']['import_mycred_ranks'], FILTER_VALIDATE_BOOLEAN );
	if ( ! $perform ) {
		return;
	}

	$post = array(
		'post_title'    => wp_strip_all_tags( $args['name'] ),
		'post_status'   => 'publish',
		'post_type' 	=> 'mycred_rank',
	);
	$post_id = wp_insert_post( $post );

	$img_id = bimber_mycred_sideload_image( $args['img'], $post_id );
	if ( null === $img_id ) {
		return;
	}
	set_post_thumbnail( $post_id, $img_id );

	update_post_meta( $post_id, 'ctype', $args['ctype'] );
	update_post_meta( $post_id, 'mycred_rank_min', $args['min'] );
	update_post_meta( $post_id, 'mycred_rank_max', $args['max'] );
	update_post_meta( $post_id, 'mycred_rank_users', 0 );
	if ( isset( $args['importslug'] ) ) {
		update_post_meta( $post_id, '_bimber_mycred_import_slug', $args['importslug'] );
	}
}

/**
 * Sideload a badge image to post.
 *
 * @param string $img  Bagde image filename.
 * @param int    $post_id Post id.
 * @return int
 */
function bimber_mycred_sideload_image( $img, $post_id ) {
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename = BIMBER_PLUGINS_DIR_URI . 'mycred/assets/' . $img;

	$image_tmp = download_url( $filename );
	if ( is_wp_error( $image_tmp ) ) {
		return null;
	} else {
		$image_size = filesize( $image_tmp );
		$image_name = basename( $filename );
		$file = array(
			'name' => $image_name,
			'type' => 'image/jpg',
			'tmp_name' => $image_tmp,
			'error' => 0,
			'size' => $image_size,
		 );
		add_filter( 'upload_mimes', 'bimber_mycred_allow_svg' );
		$thumb_id = media_handle_sideload( $file, $post_id, 'badge' );
		remove_filter( 'upload_mimes', 'bimber_mycred_allow_svg' );
	}
	return $thumb_id;
}

/**
 * Enable needed addons.
 *
 * @param array $addons  The addons.
 */
function bimber_mycred_enable_addons( $addons ) {
	$current_setting = get_option( 'mycred_pref_addons' );
	foreach ( $addons as $addon ) {
		if ( ! in_array( $addon, $current_setting['active'] ) ){
			array_push( $current_setting['active'],$addon );
		}
	}
	update_option( 'mycred_pref_addons', $current_setting );
}

/**
 * Enable needed hooks
 *
 * @param array $active     "Active" part of the setting.
 * @param array $hook_prefs "Hook prefs" part of the setting.
 */
function bimber_mycred_add_hooks( $active, $hook_prefs ) {
	$perform = filter_var( $_POST['options']['import_mycred_hooks'], FILTER_VALIDATE_BOOLEAN );
	if ( ! $perform ) {
		return;
	}


	$option = get_option( 'mycred_pref_hooks' );
	if ( ! is_array( $option ) ) {
		$option = array();
		$new_active = array();
		$new_hook_prefs = array();
	} else {
		$new_active = $option['active'];
		$new_hook_prefs = $option['hook_prefs'];
	}

	foreach ( $active as $key ) {
		if ( ! in_array( $key, $new_active, true ) ) {
			array_push( $new_active, $key );
		}
	}
	foreach ( $hook_prefs as $key => $value ) {
		$new_hook_prefs[ $key ] = $value;
	}

	$option['active'] = $new_active;
	$option['hook_prefs'] = $new_hook_prefs;
	update_option( 'mycred_pref_hooks', $option );
}

/**
 * Enable needed hooks
 */
function bimber_mycred_import_setup_hooks() {
	$perform = filter_var( $_POST['options']['import_mycred_hooks'], FILTER_VALIDATE_BOOLEAN );
	if ( ! $perform ) {
		return;
	}

	$option = get_option( 'mycred_pref_hooks' );
	if ( ! is_array( $option ) ) {
		$option = array();
	}

	$hooks = new myCRED_Hooks_Module;
	$hooks = $hooks->get();

	$option['installed'] = $hooks;
	update_option( 'mycred_pref_hooks', $option );
}

/**
 * Dev utility to reset the export.
 */
function bimber_reset_mycred() {

	$args = array(
		'post_type' 	=> 'mycred_rank',
		'posts_per_page'   => -1,
	);
	$posts = get_posts( $args );
	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	$args = array(
		'post_type' 	=> 'mycred_badge',
		'posts_per_page'   => -1,
	);
	$posts = get_posts( $args );
	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	$option = get_option( 'mycred_pref_hooks' );
	$option['active'] = array();
	$option['hook_prefs'] = array();
	update_option( 'mycred_pref_hooks', $option );
}
