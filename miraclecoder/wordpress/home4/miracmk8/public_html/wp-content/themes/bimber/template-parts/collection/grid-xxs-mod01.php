<?php
/**
 * The Template for displaying collection.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_data = bimber_get_template_part_data();
$bimber_query = $bimber_data['query'];
$bimber_title = $bimber_data['title'];
$bimber_title_size = $bimber_data['title_size'];
$bimber_title_align = $bimber_data['title_align'];
$bimber_columns = $bimber_data['columns'];

$classes = array(
	'g1-collection',
	'g1-collection-grid-xxs-mod01',
);
?>
<div class="<?php echo  implode( ' ', array_map('sanitize_html_class', $classes) ); ?>">
	<?php if ( ! empty( $bimber_title ) ) : ?>
		<?php echo do_shortcode( '[bimber_title size="' . $bimber_title_size . '" align="' . $bimber_title_align . '" class="g1-collection-title"]' . $bimber_title . '[/bimber_title]' ); ?>
	<?php endif; ?>

	<?php if ( $bimber_query->have_posts() ) : ?>
		<div class="g1-collection-viewport">
			<ul class="g1-collection-items">
				<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>
					<?php if ( $bimber_query->current_post ) : ?>
						<?php
						// Override elements visibility.
						$bimber_data['elements'] = bimber_hide_highlighted_elements( $bimber_data['elements'] );
						bimber_set_template_part_data( $bimber_data );
						?>

						<li class="g1-collection-item g1-collection-item-std">
							<?php get_template_part( 'template-parts/content-grid-xxs', get_post_format() ); ?>
						</li>
						<?php bimber_reset_template_part_data(); // Restore original elements visibility. ?>
					<?php else : ?>
						<li class="g1-collection-item g1-collection-item-feat">
							<?php get_template_part( 'template-parts/content-grid-standard', get_post_format() ); ?>
						</li>
					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; ?>
</div><!-- .g1-collection -->