<?php
/**
 * Template part for displaying single post "Don't Miss" section.
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
?>
<?php
$use_cache = apply_filters( 'bimber_dont_miss_query_use_cache', true );

$bimber_query = $use_cache ? get_transient( 'bimber_dont_miss_query' ) : false;

$template = bimber_get_theme_option( 'post', 'dont_miss_template' );
switch ( $template ) {
	case 'grid':
		$template_path = 'template-parts/content-grid-standard';
		break;
	case 'list':
		$template_path = 'template-parts/content-list-standard';
		break;
	case 'list-s':
		$template_path = 'template-parts/content-list-s';
		break;
}

if ( false === $bimber_query ) {
	$bimber_query_args = array(
		'posts_per_page'      => bimber_get_dont_miss_posts_limit(),
		'time_range'          => 'month',
		'ignore_sticky_posts' => true,
	);

	// Exclude the current post.
	if ( is_single() ) {
		$bimber_query_args['post__not_in'] = array( get_the_ID() );
	}

	// Short circuit.
	if ( has_filter( 'bimber_pre_dont_miss_query_args', false ) ) {
		$bimber_query_args = apply_filters( 'bimber_pre_dont_miss_query_args', $bimber_query_args );
	} else {
		$bimber_query_args = bimber_get_most_viewed_query_args( $bimber_query_args, 'dont_miss' );
	}

	$bimber_query = new WP_Query( $bimber_query_args );

	set_transient( 'bimber_dont_miss_query', $bimber_query );
}

$bimber_collection_class = array();
$bimber_card_style = bimber_get_theme_option( 'cards', 'single_content' );
if ( 'none' !== $bimber_card_style ) {
	$bimber_collection_class[] = 'g1-collection-with-cards';
}
if ( 'grid' === $template ) {
	$bimber_collection_class[] = 'g1-collection-columns-2';
}
?>
<?php if ( $bimber_query->have_posts() ) : ?>

	<aside class="g1-dont-miss">
		<?php bimber_render_section_title( __( 'Don\'t Miss', 'bimber' ), false, array( 'g1-collection-title' ) ); ?>

		<?php
		$bimber_dont_miss_elements = bimber_conver_string_to_bool_array(
			bimber_get_theme_option( 'post', 'dont_miss_hide_elements' ),
			apply_filters( 'bimber_post_dont_miss_hide_elements_defaults', array(
				'featured_media' => true,
				'subtitle'       => true,
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
				'call_to_action' => true,
				'action_links'   => false,
			) )
		);

		$bimber_settings = apply_filters( 'bimber_entry_dont_miss_settings', array(
			'elements'                      => $bimber_dont_miss_elements,
			'card_style'                    => $bimber_card_style,
			'call_to_action_hide_buttons'   => bimber_get_theme_option( 'post', 'dont_miss_call_to_action_hide_buttons' ),
		) );

		bimber_set_template_part_data( $bimber_settings );
		?>
		<div <?php bimber_render_collection_class( $bimber_collection_class ); ?>>
			<div class="g1-collection-viewport">
				<ul class="g1-collection-items">
					<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

						<li class="g1-collection-item <?php if ( 'grid' === $template ) :?>g1-collection-item-1of3<?php endif;?>">
							<?php get_template_part( $template_path, get_post_format() ); ?>
						</li>

					<?php endwhile; ?>
				</ul>
			</div>
		</div>

		<?php bimber_reset_template_part_data(); ?>
		<?php wp_reset_postdata(); ?>
	</aside>
<?php else : ?>
	<?php get_template_part( 'template-parts/collection-dont-miss-empty' ); ?>
<?php endif; ?>



