<?php
/**
 * Template part for displaying posts from the same category as current post.
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

$bimber_post_first_category = bimber_get_post_first_category( get_the_ID() );

if ( ! empty( $bimber_post_first_category ) ) {
	$bimber_args = array(
		'cat'                 => $bimber_post_first_category->term_id,
		'post__not_in'        => array( get_the_ID() ), // Exclude current post.
		'posts_per_page'      => bimber_get_more_from_posts_limit(),
		'ignore_sticky_posts' => true,
	);
} else {
	$bimber_args = array();
}

$bimber_query = new WP_Query( $bimber_args );
?>

<?php if ( $bimber_query->have_posts() ) : ?>

	<?php
	$bimber_more_from_elements = bimber_conver_string_to_bool_array(
		bimber_get_theme_option( 'post', 'more_from_hide_elements' ),
		apply_filters( 'bimber_post_more_from_hide_elements_defaults', array(
			'featured_media' => true,
			'avatar'         => true,
			'categories'     => true,
			'summary'        => true,
			'author'         => true,
			'date'           => true,
			'shares'         => true,
			'views'          => true,
			'comments_link'  => true,
			'downloads'      => true,
			'votes'          => true,
		) )
	);

	$bimber_settings = apply_filters( 'bimber_entry_more_from_settings', array(
		'elements' => $bimber_more_from_elements,
	) );

	bimber_set_template_part_data( $bimber_settings );
	?>
	<aside class="g1-more-from">
		<?php bimber_render_section_title( sprintf( wp_kses_post( __( 'More From: <a href="%s">%s</a>', 'bimber' ) ), esc_url( get_category_link( $bimber_post_first_category->term_id ) ), esc_html( $bimber_post_first_category->name ) ), false, array( 'g1-collection-title' ) ); ?>
		<div class="g1-collection">
			<div class="g1-collection-viewport">
				<ul class="g1-collection-items">
					<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

						<li class="g1-collection-item">
							<?php get_template_part( 'amp/content-list-standard', get_post_format() ); ?>
						</li>

					<?php endwhile; ?>
				</ul>
			</div>
		</div>

		<?php bimber_reset_template_part_data(); ?>
		<?php wp_reset_postdata(); ?>
	</aside>
<?php endif;
