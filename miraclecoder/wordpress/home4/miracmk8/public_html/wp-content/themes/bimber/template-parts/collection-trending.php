<?php
/**
 * Template for displaying trending posts.
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
?>

<?php
$bimber_limit      = bimber_get_trending_posts_limit();
$bimber_post_ids   = bimber_get_trending_post_ids( $bimber_limit );
$bimber_query_args = array();

if ( ! empty( $bimber_post_ids ) ) {
	$bimber_query_args = array(
		'post_type'             => 'any',
		'post__in'              => $bimber_post_ids,
		'orderby'               => 'post__in',
		'posts_per_page'        => $bimber_limit,
		'ignore_sticky_posts'   => true,
	);
}

$bimber_query_args = apply_filters( 'bimber_trending_posts_query_args', $bimber_query_args );

$bimber_query = new WP_Query( $bimber_query_args );
?>

<?php if ( $bimber_query->have_posts() ) : ?>

	<?php
	$bimber_settings = apply_filters( 'bimber_trending_entry_settings', array(
		'elements' => array(
			'featured_media' => true,
			'categories'     => false,
			'title'          => true,
			'summary'        => false,
			'author'         => false,
			'avatar'         => false,
			'date'           => false,
			'shares'         => false,
			'views'          => false,
			'comments_link'  => false,
			'downloads'      => false,
			'votes'          => false,
		),
	) );

	bimber_set_template_part_data( $bimber_settings );
	?>

	<div class="g1-collection g1-trending-content">
		<div class="g1-collection-viewport">
			<ul class="g1-collection-items">

				<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>
					<li class="g1-collection-item">
						<?php get_template_part( 'template-parts/content-list-fancy', get_post_format() ); ?>
					</li>
				<?php endwhile; ?>

			</ul>
		</div>

	</div><!-- .g1-collection -->

	<?php bimber_reset_template_part_data(); ?>
	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<?php get_template_part( 'template-parts/collection-trending-empty' ); ?>
<?php endif;

