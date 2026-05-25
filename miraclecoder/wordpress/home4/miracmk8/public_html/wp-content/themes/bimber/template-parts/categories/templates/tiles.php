<?php
/**
 * The Template for displaying categories.
 *
 * @package Bimber_Theme 6.5
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$bimber_data = bimber_get_template_part_data();
$bimber_terms = $bimber_data['terms'];
$bimber_columns = $bimber_data['columns'];
$bimber_elements = $bimber_data['elements'];

$bimber_class = array(
	'g1-terms',
	'g1-terms-tpl-tiles',
	'g1-terms-columns-' . $bimber_columns,
);
?>
<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
	<?php
		bimber_set_template_part_data( $bimber_data );
		get_template_part( 'template-parts/categories/header' );
		bimber_reset_template_part_data();
	?>

	<ul class="g1-terms-items">
		<?php foreach ( $bimber_terms as $bimber_term ) : ?>
			<?php
			// Tile image.
			$bimber_image = get_term_meta( $bimber_term->term_id, 'bimber_taxonomy_image', true );
			$bimber_image = wp_get_attachment_image_src( $bimber_image, 'full' );
			$bimber_image = is_array( $bimber_image ) ? $bimber_image[0] : '';

			$bimber_class = array(
				'g1-term',
				'g1-term-tpl-tile',
				'g1-dark',
			);
			?>
			<li class="g1-terms-item">
				<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>" href="<?php echo esc_url( get_term_link( $bimber_term ) ); ?>" style="background-image:url(<?php echo esc_url( $bimber_image ); ?>)">
					<div class="g1-term-body">
						<?php if ( $bimber_elements['icon'] ) : ?>
							<?php bimber_render_term_featured_image( $bimber_term ); ?>
						<?php endif; ?>
						
						<h4 class="g1-term-title"><?php echo $bimber_term->name; ?></h4>

						<?php if ( $bimber_elements['count'] ) : ?>
							<p class="g1-meta">
								<span class="g1-term-count"><?php
								printf(
									str_replace(
										'%d',
										'<strong>%d</strong>',
										esc_html( _n( '%d entry', '%d entries', $bimber_term->count, 'bimber' ) )
									),
									$bimber_term->count
								);
								?></span>
							</p>
						<?php endif; ?>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
