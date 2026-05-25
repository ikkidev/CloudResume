<?php
/**
 * The template part
 *
 * @package Bimber_Theme
 */

global $bimber_mc4wp_data;

if ( empty( $bimber_mc4wp_data ) ) {
	return;
}
$bimber_mc4wp_data['classes'] = array_merge( array(
		'g1-box',
		'g1-box-tpl-background',
		'g1-newsletter',
		'g1-newsletter-vertical',
		'g1-dark',
	),
	$bimber_mc4wp_data['classes']
);

// Don't show avatar by default.
if ( ! isset( $bimber_mc4wp_data['show_avatar'] ) ) {
	$bimber_mc4wp_data['show_avatar'] = false;
}

$bimber_bg = wp_get_attachment_image_src( $bimber_mc4wp_data['background_image_id'], 'full' );
?>
<aside id="<?php echo esc_attr( $bimber_mc4wp_data['id'] ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_mc4wp_data['classes'] ) ); ?>">
	<?php if ( $bimber_bg ) : ?>
		<style scoped="scoped">
			#<?php echo esc_attr($bimber_mc4wp_data['id']); ?> .g1-box-background { background-image: url( <?php echo esc_attr( $bimber_bg[0] ); ?>  ); }
		</style>
	<?php endif; ?>

	<div class="g1-box-inner">
		<header>
			<?php bimber_render_section_title( esc_html__( 'Newsletter', 'bimber' ) ); ?>
		</header>

		<?php if ( $bimber_mc4wp_data['show_avatar'] ) : ?>
			<div class="g1-newsletter-avatar">
				<?php
					if ( $bimber_mc4wp_data['avatar_id'] ) :
						echo wp_get_attachment_image( $bimber_mc4wp_data['avatar_id'] );
					endif;
				?>
			</div>
		<?php endif; ?>

		<p class="g1-alpha g1-alpha-1st"><?php echo esc_html( $bimber_mc4wp_data['title'] ); ?></p>

		<?php if ( ! empty( $bimber_mc4wp_data['subtitle'] ) ) : ?>
			<p><?php echo esc_html( $bimber_mc4wp_data['subtitle'] ); ?></p>
		<?php endif; ?>

		<?php echo do_shortcode( '[mc4wp_form]' ); ?>
	</div>

	<div class="g1-box-background">
	</div>
</aside>