<?php
/**
 * Template part for displaying single post related entries.
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

$bimber_max_posts_to_show = bimber_get_related_posts_limit();
$bimber_min_posts_to_show = apply_filters( 'bimber_related_posts_min_entries', bimber_get_related_posts_limit() ); // If there is not enough related posts, list will be supplemented with recent posts.

$bimber_related_posts_ids = bimber_get_related_posts_ids(
	get_the_ID(),
	$bimber_max_posts_to_show,
	$bimber_min_posts_to_show
);

if ( ! empty( $bimber_related_posts_ids ) ) {
	$bimber_args = array(
		'post__in'            => $bimber_related_posts_ids,
		'orderby'             => 'post__in',
		'posts_per_page'      => $bimber_max_posts_to_show,
		'ignore_sticky_posts' => true,
	);
} else {
	$bimber_args = array();
}

$bimber_query = new WP_Query( $bimber_args );
?>

<?php if ( $bimber_query->have_posts() ) : ?>
	<aside class="g1-related-entries">

		<?php
		$bimber_related_elements = bimber_conver_string_to_bool_array(
			bimber_get_theme_option( 'post', 'related_hide_elements' ),
			apply_filters( 'bimber_post_related_hide_elements_defaults', array(
				'featured_media' => true,
				'categories'     => true,
				'summary'        => true,
				'author'         => true,
				'avatar'         => true,
				'date'           => true,
				'shares'         => true,
				'views'          => true,
				'comments_link'  => true,
				'downloads'      => true,
				'votes'          => true,
			) )
		);

		$bimber_related_entries_settings = apply_filters( 'bimber_entry_related_entries_settings', array(
			'elements' => $bimber_related_elements,
		) );

		bimber_set_template_part_data( $bimber_related_entries_settings );
		?>

		<?php bimber_render_section_title( __( 'You May Also Like', 'bimber' ), false, array( 'g1-collection-title' ) ); ?>
		<div class="g1-collection g1-collection-columns-2">
			<div class="g1-collection-viewport">
				<ul class="g1-collection-items  ">
					<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

						<li class="g1-collection-item g1-collection-item-1of3">
							<?php get_template_part( 'amp/content-grid-standard', get_post_format() ); ?>
						</li>

					<?php endwhile; ?>
				</ul>
			</div>
		</div>

		<?php bimber_reset_template_part_data(); ?>
		<?php wp_reset_postdata(); ?>
	</aside>
<?php endif;






