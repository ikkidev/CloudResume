<?php
/**
 * Video Post Format Metabox
 *
 * @package bimber
 * @subpackage Metaboxes
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'add_meta_boxes', 'bimber_add_video_post_format_metabox', 10 ,2 );
add_action( 'save_post',      'bimber_save_video_post_format_metabox' );

/**
 * Register metabox
 *
 * @param string  $post_type    Post type.
 * @param WP_Post $post         Post object.
 */
function bimber_add_video_post_format_metabox( $post_type, $post ) {
	$allowed_post_types = apply_filters( 'bimber_video_post_format_metabox_post_types', array( 'post' ) );

	if ( ! in_array( $post_type, $allowed_post_types ) ) {
		return;
	}

	if ( 'video' !== get_post_format( $post ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_others_posts' ) ) {
		return;
	}

	add_meta_box(
		'bimber_video_post_format',
		_x( 'Video Options', 'Metabox title', 'bimber' ),
		'bimber_video_post_format_metabox',
		$post_type,
		'normal'
	);

	do_action( 'bimber_register_video_post_format_metabox' );
}

/**
 * Render metabox
 *
 * @param WP_Post $post         Post object.
 */
function bimber_video_post_format_metabox( $post ) {
	// Secure the form with nonce field.
	wp_nonce_field(
		'bimber_video_post_format',
		'bimber_video_post_format_nonce'
	);

	$value = get_post_meta( $post->ID, '_bimber_post_video_length', true );

	// MediaAce integration.
	$auto_video_length_enabled = ( bimber_can_use_plugin( 'media-ace/media-ace.php' ) && function_exists( 'mace_is_auto_video_length_enabled' ) && mace_is_auto_video_length_enabled() );
	$video_length = false;
	$video_url    = false;
	$video_error  = false;

	if ( $auto_video_length_enabled ) {
		$video_data  = mace_get_post_video_data( $post->ID );
		$video_error = mace_get_video_fetching_error_message( $post->ID );

		if ( ! empty( $video_data ) ) {
			$video_length = bimber_convert_seconds_into_hms( $video_data['length'] );
			$video_url    = sprintf( '<a href="%s" target="_blank">%s</a>', $video_data['url'], $video_data['url'] );
		}
	}
	?>
	<div id="bimber-metabox">
		<?php if ( ! $auto_video_length_enabled ): ?>
		<p>
			<?php esc_html_e( 'The "Auto Video Length" module from the MediaAce plugin is not enabled. Video length cannot be set automatically.', 'bimber' ); ?>
			<a href="https://bimber.bringthepixel.com/docs/video-lenght/" target="_blank"><?php echo esc_html_x( 'Learn more', 'Link to documentation', 'bimber' ); ?></a>
		</p>
		<?php endif; ?>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Video length', 'bimber' ); ?>
				</th>
				<td>
					<?php
					if ( $video_length ) {
						echo esc_html( $video_length );
					} else {
						echo esc_html_x( 'unknown', 'Video meta box label', 'bimber' );
					}
					?>
				</td>
			</tr>
			<?php if ( $auto_video_length_enabled && $video_error ): ?>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Error', 'bimber' ); ?>
					</th>
					<td>
						<?php echo esc_html( $video_error ); ?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th scope="row">
					<label for="_bimber_post_video_length">
						<?php esc_html_e( 'Manual video length', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<input type="number" id="_bimber_post_video_length" name="_bimber_post_video_length" value="<?php echo esc_attr( $value ) ?>" />
					<?php esc_html_e( 'seconds', 'bimber' ); ?>
					<p class="description">
						<?php echo esc_html_x( 'Use this field if the video length cannot be automatically obtained', 'Video metabox field description', 'bimber' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Video URL', 'bimber' ); ?>
				</th>
				<td>
					<?php
					if ( $video_url ) {
						echo wp_kses_post( $video_url );
					} else {
						echo esc_html_x( 'unknown', 'Video meta box label', 'bimber' );
					}
					?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
<?php
}

/**
 * Save metabox data
 *
 * @param int $post_id      Post id.
 *
 * @return mixed
 */
function bimber_save_video_post_format_metabox( $post_id ) {
	// Nonce sent?
	$nonce = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_video_post_format_nonce' ) );

	if ( ! $nonce ) {
		return $post_id;
	}

	// Don't save data automatically via autosave feature.
	if ( bimber_is_doing_autosave() ) {
		return $post_id;
	}

	// Don't save data when doing preview.
	if ( bimber_is_doing_preview() ) {
		return $post_id;
	}

	// Don't save data when using Quick Edit.
	if ( bimber_is_inline_edit() ) {
		return $post_id;
	}

	$post_type = bimber_htmlspecialchars( filter_input( INPUT_POST, 'post_type' ) );

	// Check permissions.
	$post_type_obj = get_post_type_object( $post_type );

	if ( ! current_user_can( $post_type_obj->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Verify nonce.
	if ( ! check_admin_referer( 'bimber_video_post_format', 'bimber_video_post_format_nonce' ) ) {
		wp_die( esc_html__( 'Nonce incorrect!', 'bimber' ) );
	}

	$video_length = filter_input( INPUT_POST, '_bimber_post_video_length', FILTER_SANITIZE_NUMBER_INT );

	// Sanitize if not empty.
	if ( ! empty( $video_length ) ) {
		$video_length = absint( $video_length );
	}

	update_post_meta( $post_id, '_bimber_post_video_length', $video_length );

	do_action( 'bimber_save_video_post_format_metabox', $post_id );

	return $post_id;
}
