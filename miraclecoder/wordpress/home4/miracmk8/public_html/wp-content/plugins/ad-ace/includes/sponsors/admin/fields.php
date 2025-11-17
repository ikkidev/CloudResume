<?php
/**
 * Add fields for sponsor taxonomy.
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'adace-sponsor_add_form_fields', 'adace_add_sponsor_custom_fields' );
/**
 * Add Sponsor Custom Fields.
 */
function adace_add_sponsor_custom_fields() {
?>
	<div class="form-field term-url-wrap">
		<label for="tag-url"><?php esc_html_e( 'Sponsor URL', 'adace' ); ?></label>
		<input name="adace_sponsor_url" id="tag-url" type="text" value="" size="40">
		<p><?php esc_html_e( 'The URL that sponsor links to.', 'adace' ); ?></p>
	</div>
	<div class="form-field adace-sponsor-logo-wrap">
		<div class="adace-image-upload">
			<a class="button button-secondary adace-add-image" href="#"><?php esc_html_e( 'Add Sponsor Logo Image', 'adace' ); ?></a>
			<div class="adace-image">
			</div>
			<a class="button button-secondary adace-delete-image" href="#"><?php esc_html_e( 'Remove Sponsor Logo Image', 'adace' ); ?></a>
			<input class="adace-image-id" id="adace_sponsor_logo_image" name="adace_sponsor_logo_image" type="hidden" value="" />
		</div>
		<p><?php esc_html_e( 'Upload here logo for this new sponsor.', 'adace' ); ?></p>
	</div>

	<?php
}

add_action( 'adace-sponsor_edit_form_fields', 	'adace_edit_sponsor_custom_fields', 10, 2 );
/**
 * Edit Sponsor Custom Fields.
 *
 * @param object $tag Term object.
 * @param string $taxonomy Taxonomy name.
 */
function adace_edit_sponsor_custom_fields( $tag, $taxonomy ) {
	$adace_sponsor_logo_image = get_term_meta( $tag->term_id, 'adace_sponsor_logo_image', true );
	$adace_sponsor_logo_url   = get_term_meta( $tag->term_id, 'adace_sponsor_url', true );
	?>
	<table class="form-table">
		<tbody>
			<tr class="form-field term-url-wrap">
				<th scope="row"><label for="adace_sponsor_url"><?php esc_html_e( 'Sponsor URL', 'adace' ); ?></label></th>
							<td><input name="adace_sponsor_url" id="adace_sponsor_url" type="text" value="<?php echo esc_attr( $adace_sponsor_logo_url ); ?>" size="40">
				<p class="description"><?php esc_html_e( 'The URL that sponsor links to.', 'adace' ); ?></p></td>
			</tr>
			<tr class="form-field adace-sponsor-logo-wrap">
				<th scope="row">
					<label><?php esc_html_e( 'Sponsor Logo', 'adace' ); ?></label>
				</th>
				<td>
					<div class="adace-image-upload">
						<a class="button button-secondary adace-add-image" href="#"><?php esc_html_e( 'Add Sponsor Logo Image', 'adace' ); ?></a>
						<div class="adace-image">
							<?php if ( ! empty( $adace_sponsor_logo_image ) ) :  ?>
								<?php echo wp_get_attachment_image( $adace_sponsor_logo_image, 'full' ); ?>
							<?php endif; ?>
						</div>
						<a class="button button-secondary adace-delete-image" href="#"><?php esc_html_e( 'Remove Sponsor Logo Image', 'adace' ); ?></a>
						<input class="adace-image-id" id="adace_sponsor_logo_image" name="adace_sponsor_logo_image" type="hidden" value="<?php echo esc_attr( $adace_sponsor_logo_image ); ?>" />
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

add_action( 'create_adace-sponsor', 'adace_sponsor_save_custom_form_fields', 10, 2 );
add_action( 'edited_adace-sponsor', 'adace_sponsor_save_custom_form_fields', 10, 2 );
/**
 * Save Sponsor Custom Fields.
 *
 * @param string $term_id Term ID.
 */
function adace_sponsor_save_custom_form_fields( $term_id ) {
	$adace_sponsor_logo_image 	= filter_input( INPUT_POST, 'adace_sponsor_logo_image', FILTER_SANITIZE_STRING );
	$adace_sponsor_url 			= filter_input( INPUT_POST, 'adace_sponsor_url', FILTER_SANITIZE_STRING );
	update_term_meta( $term_id, 'adace_sponsor_logo_image', $adace_sponsor_logo_image );
	update_term_meta( $term_id, 'adace_sponsor_url', $adace_sponsor_url );
}
