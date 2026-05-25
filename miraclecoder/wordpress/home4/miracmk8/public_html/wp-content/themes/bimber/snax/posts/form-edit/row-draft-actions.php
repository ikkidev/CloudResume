<?php
/**
 * Snax News Post Row Actions
 *
 * @package snax
 * @subpackage Theme
 */

?>

<div class="snax-draft-post-row-actions">
	<?php $snax_preview_url = snax_get_post_preview_url(); ?>
	<input type="submit" name="snax-save-draft" value="<?php esc_attr_e( 'Save Draft', 'snax' ); ?>" class="g1-button g1-button-xs g1-button-simple snax-button snax-button-save-post" />
	<button data-snax-preview-url="<?php echo esc_url( snax_get_post_preview_url() ); ?>" class="g1-button g1-button-xs g1-button-simple snax-button snax-button-preview"<?php disabled( empty( $snax_preview_url ) ); ?>><?php esc_attr_e( 'Preview', 'snax' ); ?></button>
</div>
