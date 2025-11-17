<?php
/**
 * The template part for displaying the featured content.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_template_data                      = bimber_get_template_part_data();
$bimber_featured_entries                   = $bimber_template_data['featured_entries'];

// This template shows voting box no matter what.
$bimber_featured_entries['elements']['voting_box'] = true;


$bimber_featured_ids = bimber_get_featured_posts_ids( $bimber_featured_entries );

$bimber_query_args = array();

if ( ! empty( $bimber_featured_ids ) ) {
	$bimber_query_args['post__in']            = $bimber_featured_ids;
	$bimber_query_args['orderby']             = 'post__in';
	$bimber_query_args['ignore_sticky_posts'] = true;
}

$bimber_title = wp_kses_post( __( 'Latest <span>story</span>', 'bimber' ) );

switch ( $bimber_featured_entries['type'] ) {
	case 'most_shared':
		$bimber_title = wp_kses_post( __( 'Most <span>shared</span>', 'bimber' ) );
		break;

	case 'most_viewed':
		$bimber_title = wp_kses_post( __( 'Most <span>viewed</span>', 'bimber' ) );
		break;
}

$bimber_query_args = apply_filters( 'bimber_featured_entries_query_args', $bimber_query_args );

$bimber_query = new WP_Query( $bimber_query_args );
?>


<?php if ( $bimber_query->have_posts() ) : ?>
	<section class="archive-featured archive-featured-bunchy">
		<?php bimber_set_template_part_data( $bimber_featured_entries ); ?>

		<?php bimber_render_section_title( wp_kses_post( $bimber_title ), false, array( 'archive-featured-title' ) ); ?>
		<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

			<?php get_template_part( 'template-parts/content-feat-bunchy', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php
		bimber_reset_template_part_data();
		wp_reset_postdata();
		?>
	</section>
<?php endif;
