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
	'g1-row-layout-page',
	'archive-featured',
);

if ( ! $bimber_template_data['featured_entries_title_hide'] ) {
	$bimber_featured_class[] = 'archive-featured-with-title';
}

?>

<?php if ( $bimber_query->have_posts() ) {
	$bimber_index = 0;

	bimber_set_template_part_data( $bimber_featured_entries );
	?>

	<section class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_featured_class ) ); ?>">
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php bimber_render_section_title( wp_kses_post( $bimber_template_data['featured_entries_title'] ), $bimber_template_data['featured_entries_title_hide'], array( 'archive-featured-title' ) ); ?>
				<div class="g1-module g1-module-01">
					<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post();
						$bimber_index ++;?>

						<?php if ( 2 === $bimber_index || 4 === $bimber_index ) :?>
							<div class="g1-module-column">
						<?php endif; ?>
								<div class="g1-module-item g1-module-item-<?php echo absint( $bimber_index ); ?>">
									<?php if ( 1 === $bimber_index ) {
										get_template_part( 'template-parts/content-grid-module-l', get_post_format() );
									} else {
										get_template_part( 'template-parts/content-grid-module', get_post_format() );
									}
									?>
								</div>
						<?php if ( 3 === $bimber_index || 5 === $bimber_index ) :?>
							</div>
						<?php endif; ?>

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
