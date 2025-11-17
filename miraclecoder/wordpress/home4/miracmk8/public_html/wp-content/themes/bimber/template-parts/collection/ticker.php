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
$bimber_title_size = 'h6';
$bimber_title_align = $bimber_data['title_align'];
?>
<div class="g1-collection g1-collection-ticker g1-collection-no-js">
	<?php if ( ! empty( $bimber_title ) ) : ?>
		<?php echo do_shortcode( '[bimber_title size="' . $bimber_title_size . '" align="' . $bimber_title_align . '" class="g1-collection-title"]' . $bimber_title . '[/bimber_title]' ); ?>
	<?php endif; ?>

	<?php if ( $bimber_query->have_posts() ) : ?>
		<div class="g1-collection-viewport">
			<div class="g1-collection-items">
				<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>
					<?php if ( $bimber_query->current_post ) : ?>
						<div class="g1-collection-item">
					<?php else: ?>
						<div class="g1-collection-item">
					<?php endif; ?>
						<?php get_template_part( 'template-parts/content-ticker', get_post_format() ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	<?php endif; ?>
</div><!-- .g1-collection -->
<?php wp_enqueue_script( 'bimber-collection-ticker' ); ?>