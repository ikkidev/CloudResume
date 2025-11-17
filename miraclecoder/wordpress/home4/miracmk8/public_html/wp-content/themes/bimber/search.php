<?php
/**
 * The Template for displaying search results.
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

	<div id="primary" class="g1-primary-max">
		<div id="content" role="main">

			<header class="page-header page-header-01 archive-header g1-row g1-row-layout-page">
				<div class="g1-row-inner">

					<div class="g1-column">
						<?php
						if ( bimber_show_breadcrumbs() ) :
							bimber_render_breadcrumbs();
						endif;
						?>

						<h1 class="g1-alpha g1-alpha-2nd page-title archive-title"><?php printf( esc_html__( 'Search Results for: %s', 'bimber' ), esc_html( get_search_query() ) ); ?></h1>

						<?php get_search_form(); ?>
					</div>

				</div>
				<div class="g1-row-background"></div>
			</header>

			<?php
			if ( get_search_query() ) {
				$bimber_search_settings = bimber_get_search_settings();
				bimber_set_template_part_data( $bimber_search_settings );
				add_filter( 'bimber_show_archive_featured_entries', '__return_false' );

				do_action( 'bimber_search_before_main_collection' );
				get_template_part( 'template-parts/archive-' . $bimber_search_settings['template'] );
				do_action( 'bimber_search_after_main_collection' );

				remove_filter( 'bimber_show_archive_featured_entries', '__return_false' );

				bimber_reset_template_part_data();
			} else {
				get_template_part( 'template-parts/search/empty-search-text' );
			}
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
