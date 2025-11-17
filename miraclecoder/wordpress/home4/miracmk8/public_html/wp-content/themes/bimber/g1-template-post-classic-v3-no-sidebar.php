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

	<div class="g1-row g1-row-layout-page g1-row-padding-m">
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">

			<div class="g1-column" id="primary">
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
						get_template_part( 'template-parts/content-single-classic-v3-no-sidebar', get_post_format() );

						bimber_reset_template_part_data();

					endwhile;
					?>

				</div><!-- #content -->
			</div><!-- #primary -->

		</div>
	</div><!-- .g1-row -->


<?php get_footer();
