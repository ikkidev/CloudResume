<?php
/**
 * Snax Collection Icon Template Part
 *
 * @package snax 1.11
 * @subpackage Theme
 */

$snax_class = array(
	'snax-collection',
	'snax-collection-slug-' . snax_collection_get_slug( $post ),
	'snax-collection-tpl-icon',
);
?>
<a href="<?php the_permalink(); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
	<div class="snax-collection-icon">
	</div>

	<div class="snax-collection-body">
		<?php if ( $snax_collections_elements['author'] ) : ?>
		<p class="snax-collection-before-title">
			<?php if ( $snax_collections_elements['author'] ) : ?>
				<?php
					snax_render_collection_author( array(
						'avatar_size' => 32,
					) );
				?>
			<?php endif; ?>
		</p>
		<?php endif; ?>

		<?php snax_render_collection_title( '<h3 class="snax-collection-title g1-epsilon g1-epsilon-1st">', '</h3>' ); ?>
		<p class="g1-meta">
			<?php snax_render_collection_item_count(); ?>
		</p>
	</div>
</a>