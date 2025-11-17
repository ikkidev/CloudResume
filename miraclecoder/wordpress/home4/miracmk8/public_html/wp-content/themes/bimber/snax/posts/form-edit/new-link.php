<?php
/**
 * New item form
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php do_action( 'snax_frontend_submission_form_before_new_link' ); ?>

<input type="hidden" name="snax-add-link-item-nonce" value="<?php echo esc_attr( wp_create_nonce( 'snax-add-link-item' ) ); ?>"/>

<div class="snax-new-links">
	<h3 class="snax-link-add-header"><?php esc_html_e( 'Paste the URL', 'snax' ); ?></h3>
	<p class="snax-new-links-url">
		<input class="snax-link-url" size="100" placeholder="<?php esc_attr_e( 'Paste link URL', 'snax' ); ?>" />
	</p>

	<p class="snax-new-links-actions">
		<a href="#" class="g1-button g1-button-simple g1-button-m snax-add-link-item"><?php esc_html_e( 'Add', 'snax' ); ?></a>
	</p>
</div>

<?php $snax_link_demos = snax_link_get_demos(); ?>

<?php if ( ! empty( $snax_link_demos ) ) : ?>
	<?php $snax_show_button = ( 1 === count( $snax_link_demos ) ); ?>

	<h3 class="snax-link-example-header"><?php esc_html_e( 'or use our example URL', 'snax' ); ?></h3>

	<ul class="snax-example-items">
		<?php foreach ( $snax_link_demos as $snax_link_demo ) : ?>
			<li class="snax-example-item">
				<div class="snax-link-example">
					<a class="snax-link-example-card snax-link-example-url" href="<?php echo esc_url( $snax_link_demo['url'] ); ?>">
						<?php echo get_the_post_thumbnail( $snax_link_demo['post_id'], 'bimber-list-xxs' ); ?>
						<div class="snax-link-example-text">
							<h3 class="g1-delta"><?php echo get_the_title( $snax_link_demo['post_id'] ); ?></h3>
						<span>
							<?php echo esc_url( $snax_link_demo['url'] ); ?>
						</span>
						</div>
					</a>
					<?php if ( $snax_show_button ) : ?>
					<input type="button" value="<?php esc_html_e( 'Use this example URL', 'snax' ); ?>" class="snax-link-example-url snax-use-this-url-button g1-button g1-button-s g1-button-simple">
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php do_action( 'snax_frontend_submission_form_after_new_link' );

