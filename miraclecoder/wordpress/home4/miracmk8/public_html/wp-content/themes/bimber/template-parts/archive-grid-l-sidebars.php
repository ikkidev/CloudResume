<?php
/**
 * The Template for displaying archive body.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.5
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php
$bimber_template_data = bimber_get_template_part_data();
$featured_entries_template = bimber_get_theme_option( 'archive', 'featured_entries_template' );
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

			<div id="primary" class="g1-column g1-column-1of2">

				<?php
				if ( bimber_show_archive_featured_entries() ) :
					get_template_part( 'template-parts/featured/with-sidebar/' . $featured_entries_template );
				endif;
				?>

				<div class="g1-collection g1-collection-grid-l">
					<?php get_template_part( 'template-parts/collection/title', 'archive' ); ?>

					<div class="g1-collection-viewport">
						<ul class="g1-collection-items">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php do_action( 'bimber_archive_loop_before_post', 'grid-l', $wp_query->current_post + 1 ); ?>

								<li class="g1-collection-item">
									<?php get_template_part( 'template-parts/content-grid-l', get_post_format() ); ?>
								</li>

								<?php do_action( 'bimber_archive_loop_after_post', 'grid-l', $wp_query->current_post + 1 ); ?>
							<?php endwhile; ?>
						</ul>
					</div>

					<?php get_template_part( 'template-parts/archive/pagination', $bimber_template_data['pagination'] ); ?>
				</div><!-- .g1-collection -->

			</div><!-- .g1-column -->

			<?php get_sidebar('1of4'); ?>
			<?php get_sidebar('2-1of4'); ?>

		</div>
		<div class="g1-row-background"></div>
	</div><!-- .g1-row -->
<?php else : ?>
	<?php get_template_part( 'template-parts/archive/notice-no-results' ); ?>
<?php endif;
