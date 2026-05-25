<?php
/**
 * The Template for displaying archive pages.
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

			<?php
			$bimber_class = array(
				'g1-row',
				'g1-row-layout-page',
				'page-header',
				'page-header-03',
				'archive-header',
			);
			$bimber_class = apply_filters( 'bimber_page_header_class', $bimber_class );
			?>

			<header class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
				<div class="g1-row-background">
				</div>
				<div class="g1-row-inner">
					<div class="g1-column g1-column-2of3">
						<?php
						// Get user by id.
						$bimber_user = get_user_by( 'id', get_query_var( 'author' ) );

						// If id not set, get it via slug.
						if ( false === $bimber_user ) {
							$bimber_user = get_user_by( 'slug', get_query_var( 'author_name' ) );
						}

						$bimber_title   = $bimber_user->display_name;

						$bimber_subtitle    = '';
						$bimber_description = get_the_author_meta( 'description', $bimber_user->ID );
						?>

						<?php
							if ( bimber_show_breadcrumbs() ) :
								bimber_render_breadcrumbs();
							endif;
						?>

						<div class="page-icon"><?php echo get_avatar( $bimber_user->ID, 70 ); ?></div>

						<h1 class="g1-alpha g1-alpha-2nd page-title archive-title"><?php echo wp_kses_post( $bimber_title ); ?></h1>

						<?php if ( strlen( $bimber_subtitle ) ) : ?>
							<h2 class="g1-delta g1-delta-3rd page-subtitle archive-subtitle"><?php echo wp_kses_post( $bimber_subtitle ); ?></h2>
						<?php endif; ?>

						<?php if ( bimber_show_user_profile_link( $bimber_user->ID ) ) : ?>
							<p class="archive-bp-profile-link"><a href="<?php echo esc_url( bp_core_get_user_domain( $bimber_user->ID ) ); ?>"><?php esc_html_e( 'View community profile', 'bimber' ); ?></a></p>
						<?php endif; ?>

						<?php if ( strlen( $bimber_description ) ) : ?>
							<p class="archive-description"><?php echo wp_kses_post( $bimber_description ); ?></p>
						<?php endif; ?>
					</div>
				</div>

			</header>

			<?php
			$bimber_archive_settings                       = bimber_get_archive_settings();
			$bimber_archive_settings['elements']['author'] = false;
			$bimber_archive_settings['elements']['avatar'] = false;

			bimber_set_template_part_data( $bimber_archive_settings );

			get_template_part( 'template-parts/archive-' . $bimber_archive_settings['template'] );

			bimber_reset_template_part_data();
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
