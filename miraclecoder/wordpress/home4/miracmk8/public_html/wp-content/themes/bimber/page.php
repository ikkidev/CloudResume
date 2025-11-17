<?php
/**
 * The Template for displaying pages.
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

	<div class="g1-primary-max">
		<div id="content" role="main">

			<?php
			while ( have_posts() ) : the_post();

				// Include the page content template.
				get_template_part( 'template-parts/content', 'page' );
			endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
