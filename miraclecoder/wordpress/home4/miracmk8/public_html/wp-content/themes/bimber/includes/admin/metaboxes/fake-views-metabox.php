<?php
/**
 * Fake Views Metabox
 *
 * @package bimber
 * @subpackage Metaboxes
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Register metabox
 *
 * @param string  $post_type    Post type.
 * @param WP_Post $post         Post object.
 */
function bimber_add_fake_views_metabox( $post_type, $post ) {
    $allowed_post_types = apply_filters( 'bimber_fake_views_post_types', array( 'post' ) );

	if ( ! in_array( $post_type, $allowed_post_types ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_others_posts' ) ) {
		return;
	}

	add_meta_box(
		'bimber_fake_views',
		__( 'Fake Views', 'bimber' ),
		'bimber_fake_views_metabox',
		$post_type,
		'normal'
	);

	do_action( 'bimber_register_fake_views_metabox' );
}

/**
 * Render metabox
 *
 * @param WP_Post $post         Post object.
 */
function bimber_fake_views_metabox( $post ) {
	// Secure the form with nonce field.
	wp_nonce_field(
		'bimber_fake_views',
		'bimber_fake_views_nonce'
	);

	$value = get_post_meta( $post->ID, '_bimber_fake_view_count', true );
	?>
	<div id="bimber-metabox">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="_bimber_fake_view_count">
						<?php esc_html_e( 'Fake view count', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<input type="number" id="_bimber_fake_view_count" name="_bimber_fake_view_count" value="<?php echo esc_attr( $value ) ?>" size="5" />
					<span class="description">
						<?php esc_html_e( 'Leave empty to use global settings or use a positive number (inclusive 0) to override them.', 'bimber' ); ?>
					</span>
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
function bimber_save_fake_views_metabox( $post_id ) {
	// Nonce sent?
	$nonce = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_fake_views_nonce' ) );

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
	if ( ! check_admin_referer( 'bimber_fake_views', 'bimber_fake_views_nonce' ) ) {
		wp_die( esc_html__( 'Nonce incorrect!', 'bimber' ) );
	}

	$view_count = bimber_htmlspecialchars( filter_input( INPUT_POST, '_bimber_fake_view_count' ) );

	// Sanitize if not empty.
	if ( ! empty( $view_count ) ) {
		$view_count = absint( $view_count );
	}

	update_post_meta( $post_id, '_bimber_fake_view_count', $view_count );

	do_action( 'bimber_save_list_post_metabox', $post_id );

	return $post_id;
}
