<?php
/**
 * New image page
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! current_user_can('upload_files') ) {
	wp_die(__('Sorry, you are not allowed to upload files.'));
}

$photomix_template  = photomix_get_image_template();
$photomix_id        = photomix_get_image_id();
$photomix_title     = photomix_get_image_title();
?>

<div class="wrap">
	<?php if ( empty( $photomix_template ) ) : ?>
	<h1><?php esc_html_e( 'Create New Image', 'photomix' ); ?></h1>
	<?php endif; ?>

	<div class="photomix-workspace">
		<?php if ( $photomix_template ) : ?>
			<div class="photomix-workspace-header">
				<?php photomix_get_template_part( 'image-editor/object-library' ); ?>

				<div class="photomix-canvas-actions">
					<input type="text" id="photomix-title" name="photomix-title" value="<?php echo esc_attr( $photomix_title ); ?>" autocomplete="off" placeholder="<?php esc_attr_e( 'Enter title here', 'photomix' ); ?>">
					<a class="button button-secondary" href="#" id="photomix-download"><?php esc_html_e( 'Download', 'photomix' ); ?></a>
					<a class="button button-primary" href="#" id="photomix-save" data-photomix-saving="<?php esc_attr_e ( 'Saving. Please wait...', 'photomix' ); ?>"><?php esc_html_e( 'Save', 'photomix' ); ?></a>
					<?php if ( ! empty( $photomix_id ) ): ?>
						<a href="<?php echo esc_url( get_edit_post_link( $photomix_id ) ); ?>" target="_blank"><?php esc_html_e( 'View in Media Library', 'photomix' ) ?></a>
					<?php endif; ?>
				</div>
			</div>

			<?php photomix_get_template_part( 'image-editor/templates/' .$photomix_template ); ?>

		<?php else : ?>

			<?php photomix_get_template_part( 'image-editor/template-list' ); ?>

		<?php endif; ?>
	</div>

</div>
