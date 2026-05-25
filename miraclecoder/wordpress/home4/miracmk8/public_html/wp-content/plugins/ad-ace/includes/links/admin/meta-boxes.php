<?php
/**
 * Meta boxes
 *
 * @package AdAce
 * @subpackage Links
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'add_meta_boxes_adace_link', 'adace_add_links_meta_boxes' );
/**
 * Register ad metaboxes.
 */
function adace_add_links_meta_boxes() {
	add_meta_box(
		'adace_links_meta_box',
		esc_html( 'Links Options', 'adace' ),
		'adace_links_meta_box_render_callback'
	);
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_links_meta_box_render_callback( $post ) {
	$current_link_link  = get_post_meta( $post -> ID, 'adace_link_link', true );
	if ( ! $current_link_link ) {
		$current_link_link = '';
	}
	$current_link_nofollow  = get_post_meta( $post -> ID, 'adace_link_nofollow', true );
	?>
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Link', 'adace' ); ?></th>
						<td>
							<input type="text" style="width:100%;" name="adace_link_link" id="adace_link_link" value="<?php echo( esc_url( $current_link_link ) ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Nofollow Link', 'adace' ); ?></th>
						<td>
							<input type="checkbox"name="adace_link_nofollow" id="adace_link_nofollow" <?php checked( $current_link_nofollow, true ) ?>>
						</td>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( adace_get_plugin_basename(),'adace_save_link_meta_nonce' ); ?>
		</fieldset>
	<?php
}

add_action( 'save_post', 'adace_links_meta_box_data_save' );
/**
 * Meta box saver.
 *
 * @param string $post_id Post id.
 */
function adace_links_meta_box_data_save( $post_id ) {
    // Nonce sent?
    $nonce = filter_input( INPUT_POST, 'adace_save_link_meta_nonce', FILTER_SANITIZE_STRING );

    if ( ! $nonce ) {
        return;
    }

    // Verify that nonce.
    if ( ! wp_verify_nonce( $nonce, adace_get_plugin_basename() ) ) {
        return;
    }

	// Sanitize args.
	$args = filter_input_array( INPUT_POST,
		array(
			'post_type'                   => FILTER_SANITIZE_STRING,
			'adace_link_link'            => FILTER_SANITIZE_URL,
			'adace_link_nofollow'        => FILTER_VALIDATE_BOOLEAN,
		)
	);

	// Check if post_type is correct.
	if ( 'adace_link' !== $args['post_type'] ) {
		return;
	}
	// If user can edit this type.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// Save new meta value.
	$args['adace_link_link'] = apply_filters(
		'adace_link_link_save_filter',
		$args['adace_link_link']
	);
	update_post_meta( $post_id, 'adace_link_link', $args['adace_link_link'] );
	// Save new meta value.
	$args['adace_link_nofollow'] = apply_filters(
		'adace_link_nofollow_save_filter',
		$args['adace_link_nofollow']
	);
	update_post_meta( $post_id, 'adace_link_nofollow', $args['adace_link_nofollow'] );
}
