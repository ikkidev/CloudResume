<?php
/**
 * Snax News Post Row Actions
 *
 * @package snax
 * @subpackage Theme
 */

?>

<div class="snax-edit-post-row-actions">

	<?php if ( current_user_can( 'snax_publish_posts' ) ) : ?>

		<input type="submit" value="<?php esc_attr_e( 'Publish', 'snax' ); ?>"
		       class="g1-button g1-button-l g1-button-solid snax-button snax-button-publish-post" />

	<?php else : ?>

		<input type="submit" value="<?php esc_attr_e( 'Submit for Review', 'snax' ); ?>"
		       class="g1-button g1-button-l g1-button-solid snax-button snax-button-submit-post" />

	<?php endif; ?>

	<a href="<?php echo esc_url( snax_get_frontend_submission_page_url() );?>" class="snax-cancel-submission g1-button g1-button-wide g1-button-s g1-button-subtle" data-snax-cancel-nonce="<?php echo wp_create_nonce( 'snax-cancel' ); ?>"><?php esc_attr_e( 'Cancel', 'snax' ); ?></a>
</div>
