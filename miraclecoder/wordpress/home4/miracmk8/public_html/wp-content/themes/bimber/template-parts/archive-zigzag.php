<?php
/**
 * The Template for displaying archive body.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.0.2
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php
$bimber_template_data = bimber_get_template_part_data();
?>

<?php
if ( bimber_show_archive_featured_entries() ) :
	get_template_part( 'template-parts/featured/' . bimber_get_theme_option('archive', 'featured_entries_template') );
	get_template_part( 'template-parts/ads/ad-after-featured-content' );
endif;
?>

<?php if ( have_posts() ) : ?>
	<div <?php bimber_render_archive_body_class(); ?>>
		<div class="g1-row-inner">
			<div id="primary" class="g1-column">
				<?php get_template_part( 'template-parts/collection/title', 'archive' ); ?>

				<div <?php bimber_render_collection_class( array( 'g1-collection-zigzag' ) ); ?>>
					<div class="g1-collection-viewport">
						<ul class="g1-collection-items">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php do_action( 'bimber_archive_loop_before_post', 'zigzag', $wp_query->current_post + 1 ); ?>

								<?php if ( ($wp_query->current_post + 1) % 2 === 0 ) : ?>
									<li class="g1-collection-item g1-collection-item-entry-even">
										<?php get_template_part( 'template-parts/content-zigzag', get_post_format() ); ?>
									</li>
								<?php else : ?>
									<li class="g1-collection-item g1-collection-item-entry-odd">
										<?php get_template_part( 'template-parts/content-zigzag', get_post_format() ); ?>
									</li>
								<?php endif; ?>

								<?php do_action( 'bimber_archive_loop_after_post', 'zigzag', $wp_query->current_post + 1 ); ?>
							<?php endwhile; ?>
						</ul>
					</div>

					<?php get_template_part( 'template-parts/archive/pagination', $bimber_template_data['pagination'] ); ?>
				</div><!-- .g1-collection -->

			</div><!-- .g1-column -->

		</div>
		<div class="g1-row-background"></div>
	</div><!-- .g1-row -->
<?php else : ?>
	<?php get_template_part( 'template-parts/archive/notice--no-results' ); ?>
<?php endif;
