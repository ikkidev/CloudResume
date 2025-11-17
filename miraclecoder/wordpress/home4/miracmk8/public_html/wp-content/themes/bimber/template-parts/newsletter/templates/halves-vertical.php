<?php
/**
 * The template part for displaying a vertical newsletter
 *
 * @package Bimber_Theme
 */

global $bimber_mc4wp_data;

if ( empty( $bimber_mc4wp_data ) ) {
	return;
}

$bimber_mc4wp_data['classes'] = array_merge( array(
		'g1-newsletter',
		'g1-newsletter-vertical',
	),
	$bimber_mc4wp_data['classes']
);

$newsletter_large_cover = wp_get_attachment_image_src( $bimber_mc4wp_data['background_image_id'], 'full' );
?>

<div id="<?php echo esc_attr( $bimber_mc4wp_data['id'] ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_mc4wp_data['classes'] ) ); ?>">
	<div class="g1-newsletter-cover">
		<?php if ( $newsletter_large_cover ) : ?>
			<?php if ( function_exists( 'mace_get_lazy_load_images' ) && mace_get_lazy_load_images() ) : ?>
				<div class="g1-newsletter-cover-background lazyload" data-bg="<?php echo( esc_url( $newsletter_large_cover[0] ) ); ?>">
			<?php else : ?>
				<div class="g1-newsletter-cover-background" style="background-image:url(<?php echo( esc_url( $newsletter_large_cover[0] ) ); ?>);">
			<?php endif; ?>


			</div>
		<?php endif; ?>
	</div>

	<div class="g1-newsletter-content">
		<h3 class="g1-mega g1-mega-1st"><?php echo wp_kses_post( $bimber_mc4wp_data['title'] ); ?></h3>

		<?php if ( ! empty( $bimber_mc4wp_data['subtitle'] ) ) : ?>
			<p><?php echo( esc_html( $bimber_mc4wp_data['subtitle'] ) ); ?></p>
		<?php endif; ?>

		<div class="g1-newsletter-form-wrap">
			<?php echo do_shortcode( '[mc4wp_form]' ); ?>
		</div>
	</div>
</div><!-- .g1-newsletter -->
