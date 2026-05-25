<?php
/**
 * Compact Sponsor Box
 *
 * @package Adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$image_size = apply_filters( 'adace_sponsor_image_size', 'adace-sponsor-2x' );
// Box Classes.
$sponsors_box_classes = array(
	'adace-sponsors-box',
	'box-compact',
);
if ( ! is_singular() ) { return; }

$sponsor_terms  = get_the_terms( get_the_id(), 'adace-sponsor' );
if ( false !== $sponsor_terms && ! empty( $sponsor_terms ) ) : ?>
	<aside class="<?php echo( esc_attr( join( ' ', apply_filters( 'adace_sponsor_box_compact_classes', $sponsors_box_classes ) ) ) ); ?>">
	<?php foreach ( $sponsor_terms as $sponsor_term ) : ?>
		<?php
			$adace_sponsor_logo_image = get_term_meta( $sponsor_term -> term_id, 'adace_sponsor_logo_image', true );
			$adace_sponsor_url = get_term_meta( $sponsor_term -> term_id, 'adace_sponsor_url', true );
		?>

		<div class="adace-sponsor <?php echo( esc_attr( $sponsor_term -> slug ) ); ?>">
			<div class="adace-sponsor-logo-wrap">
				<span class="adace-sponsor-label"><?php echo esc_html_e( 'Sponsored by', 'adace' ); ?></span>

				<?php if ( ! empty( $adace_sponsor_url ) ) : ?>
					<a rel="nofollow noopener" target="_blank" href="<?php echo esc_url( $adace_sponsor_url );?>">
				<?php endif;?>

				<?php if ( ! empty( $adace_sponsor_logo_image ) ) :  ?>
					<div class="adace-sponsor-logo">
						<?php echo wp_get_attachment_image( $adace_sponsor_logo_image, $image_size ); ?>
					</div>
				<?php else: ?>
					<h2 class="adace-sponsor-name g1-gamma g1-gamma-1st"><?php echo( esc_html( $sponsor_term -> name ) ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $adace_sponsor_url ) ) : ?>
					</a>
				<?php endif;?>

				<span class="adace-sponsor-label"><?php echo esc_html_e( 'Sponsored by', 'adace' ); ?></span>
			</div>
		</div>
	<?php endforeach; ?>
	</aside>
<?php endif;
