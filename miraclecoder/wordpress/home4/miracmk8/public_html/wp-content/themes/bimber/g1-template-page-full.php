<?php
/**
 * Template Name: Page: No Sidebar
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
	<div id="primary" class="g1-primary-max">
		<div id="content" role="main">

			<?php
			while ( have_posts() ) : the_post();

				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-page-full-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'template-parts/content-page-full', get_post_format() );
			endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
