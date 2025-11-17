<?php
/**
 * Snax List widget
 *
 * @package snax
 * @subpackage Theme
 */

?>
<div id="<?php echo esc_attr( $snax_list_id ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_list_classes ) ); ?>">
	<?php if ( $snax_list_query->have_posts() ) : ?>

		<ul class="snax-list-collection">
			<?php while ( $snax_list_query->have_posts() ) : $snax_list_query->the_post(); ?>

				<li class="snax-list-collection-item">
					<div>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_post_thumbnail( snax_get_collection_item_image_size() ); ?></a>
						<h4 class="entry-title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
						</h4>
					</div>
				</li>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</ul>

	<?php else : ?>
		<p>
			<?php esc_html_e( 'Sorry. No data so far.', 'snax' ); ?>
		</p>
	<?php endif; ?>
</div>
