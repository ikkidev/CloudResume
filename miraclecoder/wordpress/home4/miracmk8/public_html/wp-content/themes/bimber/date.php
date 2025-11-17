<?php
/**
 * The Template for displaying date archive pages.
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

$bimber_archive_title    = '';
$bimber_archive_subtitle = '';

if ( is_year() ) {
	$bimber_archive_title    = get_the_date( 'Y' );
	$bimber_archive_subtitle = esc_html__( 'Yearly Archives', 'bimber' );
}

if ( is_month() ) {
	$bimber_archive_title    = get_the_date( 'F Y' );
	$bimber_archive_subtitle = esc_html__( 'Monthly Archives', 'bimber' );
}

if ( is_day() ) {
	$bimber_archive_title    = get_the_date();
	$bimber_archive_subtitle = esc_html__( 'Daily Archives', 'bimber' );
}

get_header();
?>
	<div id="primary" class="g1-primary-max">
		<div id="content" role="main">

			<header class="g1-row g1-row-layout-page archive-header">
				<div class="g1-row-inner">
					<div class="g1-column">
						<?php if ( ! empty( $bimber_archive_title ) ) : ?>
							<h1 class="g1-alpha g1-alpha-2nd archive-title"><?php echo wp_kses_post( $bimber_archive_title ); ?></h1>
						<?php endif; ?>
						<?php if ( ! empty( $bimber_archive_subtitle ) ) : ?>
							<h2 class="g1-delta g1-delta-3rd archive-subtitle"><?php echo wp_kses_post( $bimber_archive_subtitle ); ?></h2>
						<?php endif; ?>
					</div>
				</div>
				<div class="g1-row-background"></div>
			</header>

			<?php
			$bimber_archive_settings = bimber_get_archive_settings();
			bimber_set_template_part_data( $bimber_archive_settings );

			do_action( 'bimber_archive_before_main_collection' );
			get_template_part( 'template-parts/archive-' . $bimber_archive_settings['template'] );
			do_action( 'bimber_archive_after_main_collection' );

			bimber_reset_template_part_data();
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
