<?php
/**
 * Collection Single Item Template Part
 *
 * @package snax
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $snax_collection_id;
?>

<article class="snax-entry snax-entry-tpl-olistxs">
	<div class="snax-entry-counter">
        <?php echo absint( $args['item_nr'] ); ?>
	</div>
	<figure class="snax-entry-media">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</a>
	</figure>

	<div class="snax-entry-body">
		<h3 class="snax-entry-title entry-title g1-epsilon g1-epsilon-1st"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php snax_render_entry_author( array( 'class' => 'g1-meta' ) ); ?>
		<?php snax_render_entry_date( array( 'class' => 'g1-meta' ) ); ?>
		<?php snax_render_collection_item_action_links( $snax_collection_id ); ?>
	</div>
</article>
