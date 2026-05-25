<?php
/**
 * The Template for displaying the home page.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

get_header();
?>

<?php
$bimber_home_settings = bimber_get_home_settings();
bimber_set_template_part_data( $bimber_home_settings );
?>

<?php
if ( bimber_show_home_featured_entries() ) :
	get_template_part( 'template-parts/featured/' . $bimber_home_settings['featured_entries_template'] );
	get_template_part( 'template-parts/ads/ad-after-featured-content' );
endif;
?>

	<?php do_action( 'bimber_home_before_main_collection' ); ?>

	<div <?php bimber_render_archive_body_class(); ?>>
		<div class="g1-row-inner">

			<div id="primary" class="g1-column">

				<?php if ( have_posts() ) : ?>
					<div <?php bimber_render_collection_class( array( 'g1-collection-grid-l', 'g1-collection-columns-2' ) ); ?>>
						<?php get_template_part( 'template-parts/collection/title', 'home' ); ?>

						<div class="g1-collection-viewport">
							<ul class="g1-collection-items">
								<?php bimber_set_template_part_data( $bimber_home_settings ); ?>
								<?php while ( have_posts() ) : the_post(); ?>

									<?php do_action( 'bimber_home_loop_before_post', 'grid-l', $wp_query->current_post + 1 ); ?>

									<li class="g1-collection-item g1-collection-item-1of2">
										<?php get_template_part( 'template-parts/content-grid-l', get_post_format() ); ?>
									</li>

									<?php do_action( 'bimber_home_loop_after_post', 'grid-l', $wp_query->current_post + 1 ); ?>
								<?php endwhile; ?>
							</ul>
						</div>

						<?php get_template_part( 'template-parts/archive/pagination', $bimber_home_settings['pagination'] ); ?>
					</div><!-- .g1-collection -->
				<?php else : ?>
					<?php get_template_part( 'template-parts/archive/notice-no-results' ); ?>
				<?php endif; ?>

			</div><!-- .g1-column -->

		</div>
		<div class="g1-row-background"></div>
	</div>

<?php bimber_reset_template_part_data(); ?>

<?php get_footer();
