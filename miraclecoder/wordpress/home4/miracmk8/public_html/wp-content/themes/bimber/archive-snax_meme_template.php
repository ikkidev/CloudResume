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

			<?php get_template_part('template-parts/archive/header', bimber_get_theme_option('archive', 'header_composition') ); ?>

			<?php
			$bimber_archive_settings = bimber_get_archive_settings();
			bimber_set_template_part_data( $bimber_archive_settings );

			get_template_part( 'template-parts/archive-list-meme' );

			bimber_reset_template_part_data();
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
