<?php
/**
 * The Template for displaying 404 pages (Not Found).
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.4
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

get_header();
?>

	<div id="primary" class="g1-primary-max">
		<div id="content" role="main">

			<article id="post-0">
				<?php
				// Prepare template part data.
				set_query_var( 'bimber_title',      __( 'Ooops, sorry! We couldn\'t find it', 'bimber' )  );
				set_query_var( 'bimber_subtitle',   __( 'You have requested a page or file which doesn\'t exist', 'bimber' ) );

				// Load template part.
				get_template_part( 'template-parts/page/header', '01' );
				?>

				<div class="g1-row g1-row-layout-page g1-row-padding-l entry-content">
					<div class="g1-row-inner">

						<div class="g1-column g1-column-1of3 g1-404-search ">
							<i class="g1-404-icon"></i>
							<?php bimber_render_section_title( __( 'Search Our Website', 'bimber' ) );?>
							<?php get_search_form(); ?>
						</div><!-- .g1-column -->

						<div class="g1-column g1-column-1of3 g1-404-report">
							<i class="g1-404-icon"></i>
							<?php bimber_render_section_title( __( 'Report a Problem', 'bimber' ) );?>
							<p><?php printf( wp_kses_post( __( 'Please write some descriptive information about your problem, and email our <a href="%s">webmaster</a>.', 'bimber' ) ), esc_url( 'mailto:' . antispambot( get_option( 'admin_email' ), true ) ) ); ?></p>
						</div><!-- .g1-column -->

						<div class="g1-column g1-column-1of3 g1-404-back">
							<i class="g1-404-icon"></i>
							<?php bimber_render_section_title( __( 'Back to the Homepage', 'bimber' ) );?>
							<p><?php printf( wp_kses_post( __( 'You can also <a href="%s">go back to the homepage</a> and start browsing from there.', 'bimber' ) ), esc_url( home_url() ) ); ?></p>
						</div>
					</div>

					<div class="g1-row-background">
					</div>
				</div><!-- .entry-content -->

			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
