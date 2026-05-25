<?php
/**
 * Image Editor functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'admin_menu',                       'photomix_register_new_image_page' );
add_action( 'wp_ajax_photomix_save_image',      'photomix_ajax_save_image' );
//add_filter( 'media_row_actions',                'photomix_add_media_action', 9, 2 );

/**
 * Register a page for new image
 */
function photomix_register_new_image_page() {
	$parent_slug = 'upload.php';

	add_submenu_page(
		$parent_slug,
		__( 'Add New Photomix', 'photomix' ),
		__( 'Add New Photomix', 'photomix' ),
		'upload_files',
		photomix_get_page_slug(),
		'photomix_render_new_image_page'
	);
}

/**
 * Render a page for new image
 */
function photomix_render_new_image_page() {
	$parent_uri = trailingslashit( photomix_get_plugin_url() ) .'modules/image-editor/';
	$version 	= photomix_get_plugin_version();
	$min        = '.min';

	if ( defined( 'PHOTOMIX_DEBUG' ) && PHOTOMIX_DEBUG ) {
		$min = '';
	}

	wp_enqueue_media();
	wp_enqueue_script( 'fabric', $parent_uri . 'assets/js/fabric/fabric.js', array(), $version, true );
	wp_enqueue_script( 'photomix-fabric-filter-blur', $parent_uri . 'assets/js/fabric-filters/gaussian-blur.js', array( 'fabric' ), $version, true );
	wp_enqueue_script( 'photomix-editor', $parent_uri . 'assets/js/image-editor' . $min . '.js', array( 'jquery', 'underscore', 'fabric', 'jquery-ui-slider', 'photomix-fabric-filter-blur' ), $version, true );

	$config = array(
		'imageId'       => photomix_get_image_id(),
		'template'      => photomix_get_image_template(),
		'queryVarId'    => photomix_get_query_var_id(),
		'export'        => array(
			'format'        => photomix_get_options( 'format' ),
			'max_width'     => photomix_get_options( 'max_width' ),
			'max_height'	=> photomix_get_options( 'max_height' ),
		),
	);

	wp_localize_script( 'photomix-editor', 'photomixConfig', $config );

	photomix_get_template_part( 'image-editor/new-image' );
}

/**
 * Save Attachment Pins
 */
function photomix_ajax_save_image() {
	// Sanitize function input.
	$args = filter_input_array( INPUT_POST,
		array(
			'photomix_image_id'         => FILTER_SANITIZE_NUMBER_INT,
			'photomix_image_title'      => FILTER_SANITIZE_STRING,
			'photomix_image_filename'   => FILTER_SANITIZE_STRING,
			'photomix_image_template'   => FILTER_SANITIZE_STRING,
			'photomix_image_data'       => FILTER_UNSAFE_RAW,
			'photomix_image_masks' => array(
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_REQUIRE_ARRAY,
			),
			'photomix_image_objects' => array(
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_REQUIRE_ARRAY,
			),
		)
	);

	if ( empty( $args ) ) {
		photomix_ajax_response_error( esc_html__( 'No data loaded.', 'photomix' ) );
		exit;
	}

	// Verify user.
	if ( ! current_user_can( 'upload_files' ) ) {
		photomix_ajax_response_error( esc_html__( 'User is not allowed do create new image.', 'photomix' ) );
		exit;
	}

	$image_data = array(
		'id'        => $args['photomix_image_id'],
		'title'     => $args['photomix_image_title'],
		'filename'  => $args['photomix_image_filename'],
		'template'  => $args['photomix_image_template'],
		'masks'     => $args['photomix_image_masks'],
		'objects'   => $args['photomix_image_objects'],
	);

	$saved = photomix_save_image( $args['photomix_image_data'], $image_data );

	if ( is_wp_error( $saved ) ) {
		photomix_ajax_response_error( $saved->get_error_message() );
		exit;
	}

	photomix_ajax_response_success( esc_html__( 'All good.', 'photomix' ), array(
		'image_id'      => $saved,
		'edit_url'      => get_edit_post_link( $saved ),
	) );
	exit;
}

/**
 * Save SVG image.
 *
 * @param string $image_data                Raw image data.
 * @param array  $args                      Image args.
 *
 * @return int|WP_Error                     Saved attachment id or WP_Error on failure.
 */
function photomix_save_image( $image_data, $args ) {
	$args = wp_parse_args( $args, array(
		'id'        => '',
		'template'  => '',
		'title'     => 'Photmix Image',
		'filename'  => 'photomix-image.png',
		'masks'     => array(),
		'objects'   => array(),
	) );

	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// Load $wp_filesystem.
	WP_Filesystem();

	global $wp_filesystem;

	if ( ! $wp_filesystem ) {
		return new WP_Error( 'photomix_filesystem_failed', esc_html__( 'Filesystem not loaded.', 'photomix' ) );
	}

	$attachment_id = ! empty( $args['id'] ) ? $args['id'] : 0;

	if ( $attachment_id ) {
		$path = get_attached_file( $attachment_id );

		// Check if its already here.
		if ( $wp_filesystem->exists( $path ) ) {
			$wp_filesystem->delete( $path );
		}

	} else {
		/*
	     * A writable uploads dir will pass this test. Again, there's no point
	     * overriding this one.
	    */
		$time = current_time( 'mysql' );

		if ( ! ( ( $uploads = wp_upload_dir( $time ) ) && false === $uploads['error'] ) ) {
			return new WP_Error( 'photomix_uploads_not_writable', esc_html__( 'Upload dir is not writable.', 'photomix' ) );
		}

		$filename = wp_unique_filename( $uploads['path'], $args['filename'] );

		$path = $uploads['path'] . "/$filename";
	}

	// Lets try saving.
	$image_raw      = $image_data;
	$image_filtered = explode( ',', $image_raw );
	$image_decoded  = base64_decode( $image_filtered[1] );

	if ( ! $wp_filesystem->put_contents( $path, $image_decoded, FS_CHMOD_FILE ) ) {
		return new WP_Error( 'photomix_save_failed', esc_html__( 'Failed to save image on disk.', 'photomix' ) );
	}

	// Save data for new attachment.
	if ( ! $attachment_id ) {
		$attachment_info = array(
			'post_mime_type'    => 'image/png',
			'post_title'        => $args['title'],
			'post_content'      => '',
			'post_status'       => 'inherit',
		);

		// Insert into library.
		$attachment_id = wp_insert_attachment( $attachment_info, $path, 0 );
	}

	// Generate meta for it.
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $path );

	// Update image meta.
	wp_update_attachment_metadata( $attachment_id, $attachment_data );

	// Save meta data.
	update_post_meta( $attachment_id, '_photomix', true );
	update_post_meta( $attachment_id, '_photomix_template', $args['template'] );
	update_post_meta( $attachment_id, '_photomix_masks', $args['masks'] );
	update_post_meta( $attachment_id, '_photomix_objects', $args['objects'] );

	return $attachment_id;
}

function photomix_get_mask_config( $maskId, $imageId = null ) {
	$config = array();

	if ( ! $imageId ) {
		$imageId = photomix_get_image_id();
	}

	if ( $imageId ) {
		$masks = get_post_meta( $imageId, '_photomix_masks', true );

		if ( ! empty( $masks[ $maskId ] ) ) {
			$config = $masks[ $maskId ];
		}
	}

	$config[ 'id' ] = $maskId;

	return $config;
}

function photomix_get_mask_js_config( $maskId, $imageId = null ) {
	return wp_json_encode( photomix_get_mask_config( $maskId, $imageId ) );
}

function photomix_get_objects_config( $imageId = null ) {
	$config = array();

	if ( ! $imageId ) {
		$imageId = photomix_get_image_id();
	}

	if ( $imageId ) {
		$objects = get_post_meta( $imageId, '_photomix_objects', true );

		if ( ! empty( $objects ) ) {
			$config = $objects;
		}
	}

	return $config;
}

function photomix_get_objects_js_config( $imageId = null ) {
	return wp_json_encode( photomix_get_objects_config( $imageId ) );
}

function photomix_get_query_var_id() {
	return apply_filters( 'photomix_id_query_var', 'photomix-id' );
}

function photomix_get_page_slug() {
	return 'photomix-new-image';
}

/**
 * Enables linking to Photomix straight from the media library
 */
function photomix_add_media_action( $actions, $post ) {
	if ( ! get_post_meta( $post->ID, '_photomix', true ) ) {
		return $actions;
	}

	$url = photomix_get_image_edit_url( $post->ID );

	$newaction['photomix_edit'] = sprintf(
		'<a href="%s" aria-label="%s" rel="permalink">%s</a>',
		esc_url( $url ),
		__( 'Edit Photomix', 'photomix' ),
		__( 'Edit Photomix', 'photomix' )
	);

	return array_merge( $actions, $newaction );
}

/**
 * Get image template.
 *
 * @param int $image_id     Optional. Image id.
 *
 * @return mixed            Template of false if not set.
 */
function photomix_get_image_template( $image_id = false ) {
	$image_id = photomix_get_image_id( $image_id );

	if ( $image_id ) {
		$template = get_post_meta( $image_id, '_photomix_template', true );
	} else {
		$template = filter_input( INPUT_GET, 'photomix-template', FILTER_SANITIZE_STRING );
	}

	return $template;
}

/**
 * Get image id.
 *
 * @param int $image_id     Optional. Image id.
 *
 * @return mixed            Image id or false if not set.
 */
function photomix_get_image_id( $image_id = false ) {
	if ( ! $image_id ) {
		$image_id = filter_input( INPUT_GET, photomix_get_query_var_id(), FILTER_SANITIZE_STRING );
	}

	return $image_id;
}

/**
 * Get image title.
 *
 * @param int $image_id     Optional. Image id.
 *
 * @return mixed            Title of false if not set.
 */
function photomix_get_image_title( $image_id = false ) {
	$title      = '';
	$image_id   = photomix_get_image_id( $image_id );

	if ( $image_id ) {
		$title = get_the_title( $image_id );
	}

	return $title;
}


/**
 * Get image edit url.
 *
 * @param int $image_id     Optional. Image id.
 *
 * @return string
 */
function photomix_get_image_edit_url( $image_id = false ) {
	$image_id = photomix_get_image_id( $image_id );

	$url = admin_url( sprintf(
		'upload.php?page=%s&%s=%d',
		photomix_get_page_slug(),
		photomix_get_query_var_id(),
		$image_id
	) );

	if ( FORCE_SSL_ADMIN ) {
		$url = str_replace( 'http:', 'https:', $url );
	}

	return $url;
}

/**
 * Get canvas editor config
 *
 * @return array
 */
function photomix_get_editor_config() {
	$width  = photomix_get_options( 'editor_width' );
	$height = photomix_get_format_height( $width );

	return array(
		'width' 			=> $width,
		'height' 			=> $height,
		'background_color' 	=> photomix_get_options( 'background_color' ),
		'shape_color' 	    => photomix_get_options( 'shape_color' ),
		'gutter_on'			=> 'standard' === photomix_get_options( 'gutter' ),
		'gutter_color'		=> photomix_get_options( 'gutter_color' ),
	);
}

