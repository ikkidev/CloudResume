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

// Don't show avatar by default.
if ( ! isset( $bimber_mc4wp_data['show_avatar'] ) ) {
	$bimber_mc4wp_data['show_avatar'] = false;
}
?>
<aside id="<?php echo esc_attr( $bimber_mc4wp_data['id'] ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_mc4wp_data['classes'] ) ); ?>">
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

	<?php if ( ! empty( $bimber_mc4wp_data['title'] ) ) : ?>
		<p class="g1-alpha g1-alpha-1st"><?php echo esc_html( $bimber_mc4wp_data['title'] ); ?></p>
	<?php endif; ?>

	<?php if ( ! empty( $bimber_mc4wp_data['subtitle'] ) ) : ?>
		<p><?php echo esc_html( $bimber_mc4wp_data['subtitle'] ); ?></p>
	<?php endif; ?>

	<?php echo do_shortcode( '[mc4wp_form]' ); ?>
</aside>