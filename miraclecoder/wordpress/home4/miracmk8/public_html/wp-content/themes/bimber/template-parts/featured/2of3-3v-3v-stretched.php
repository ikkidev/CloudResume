<?php
/**
 * The template part for displaying the featured content.
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

$bimber_template_data                      = bimber_get_template_part_data();
$bimber_featured_entries                   = $bimber_template_data['featured_entries'];


$bimber_featured_ids = bimber_get_featured_posts_ids( $bimber_featured_entries );

$bimber_query_args = array();

if ( ! empty( $bimber_featured_ids ) ) {
	$bimber_query_args['post__in']            = $bimber_featured_ids;
	$bimber_query_args['orderby']             = 'post__in';
	$bimber_query_args['ignore_sticky_posts'] = true;
}

$bimber_query_args = apply_filters( 'bimber_featured_entries_query_args', $bimber_query_args );

$bimber_query = new WP_Query( $bimber_query_args );

$bimber_featured_class = array(
	'g1-row',
	'g1-row-fluid',
	'g1-row-nogutter',
	'g1-row-layout-page',
	'archive-featured',
	'archive-featured-stretched',
);

if ( ! $bimber_template_data['featured_entries_title_hide'] ) {
	$bimber_featured_class[] = 'archive-featured-with-title';
}

if ( $bimber_template_data['featured_entries_gutter'] ) {
	$bimber_featured_class[] = 'archive-featured-with-gutter';
}

?>

<?php if ( $bimber_query->have_posts() ) {
	$bimber_index = 0;

	bimber_set_template_part_data( $bimber_featured_entries );
	?>

	<section class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_featured_class ) ); ?>">
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php bimber_render_section_title( wp_kses_post( '<strong>' . $bimber_template_data['featured_entries_title'] . '</strong>' ), $bimber_template_data['featured_entries_title_hide'], array( 'archive-featured-title' ) ); ?>

				<div class="g1-mosaic g1-mosaic-2of3-3v-3v">
					<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post();
						$bimber_index ++; ?>

						<div class="g1-mosaic-item g1-mosaic-item-<?php echo absint( $bimber_index ); ?>">
							<?php
							if ( 1 === $bimber_index ) {
								get_template_part( 'template-parts/content-tile-xl', get_post_format() );
							} else {
								get_template_part( 'template-parts/content-tile-standard', get_post_format() );
							}
							?>
						</div>

					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</section>

	<?php

	bimber_reset_template_part_data();
	wp_reset_postdata();
}
