<?php
/**
 * The Template for displaying a single post.
 *
 * For the full license information, please view the Licensing folder
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
	<?php if ( bimber_show_breadcrumbs() ) : ?>
		<div <?php bimber_render_row_class( array( 'g1-row-padding-xs','g1-row-layout-page', 'g1-row-breadcrumbs' ) ); ?>>
			<div class="g1-row-background">
			</div>

			<div class="g1-row-inner">
				<div class="g1-column">
					<?php bimber_render_breadcrumbs(); ?>
				</div>
			</div>
		</div><!-- .g1-row -->
	<?php endif; ?>

	<div <?php bimber_render_row_class( array( 'g1-row-padding-m', 'g1-row-layout-page' )); ?>>
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">

			<div class="g1-column g1-column-2of3" id="primary">
				<div id="content" role="main">

					<?php
					while ( have_posts() ) : the_post();

						$bimber_post_settings = bimber_get_post_settings();
						bimber_set_template_part_data( $bimber_post_settings );

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-single-classic-v3-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'template-parts/content-single-classic-v3', get_post_format() );

						bimber_reset_template_part_data();

					endwhile;
					?>

				</div><!-- #content -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div>
	</div><!-- .g1-row -->


<?php get_footer();
