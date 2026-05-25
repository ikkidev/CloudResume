<?php
/**
 * Template part for displaying item navigation.
 *
 * @package snax
 * @subpackage Theme
 */

?>

<nav class="snax-item-nav" role="navigation">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Item navigation', 'bimber' ); ?></h2>

	<p>
		<?php if ( snax_get_previous_item_id() ) : ?>
			<a class="snax-item-prev g1-arrow g1-arrow-left"
			   href="<?php the_permalink( snax_get_previous_item_id() ); ?>"><?php esc_html_e( 'Previous submission', 'bimber' ); ?></a>
		<?php else : ?>
			<span class="snax-item-prev g1-arrow g1-arrow-left"><?php esc_html_e( 'Previous submission', 'bimber' ); ?></span>
		<?php endif; ?>

		<?php if ( snax_get_next_item_id() ) : ?>
			<a class="snax-item-next g1-arrow g1-arrow-right g1-arrow-solid" href="<?php the_permalink( snax_get_next_item_id() ); ?>"><?php esc_html_e( 'Next submission', 'bimber' ); ?></a>
		<?php else : ?>
			<span class="snax-item-next g1-arrow g1-arrow-right"><?php esc_html_e( 'Next submission', 'bimber' ); ?></span>
		<?php endif; ?>

		<a class="snax-item-back"
		   href="<?php the_permalink( snax_get_item_parent_id() ); ?>"><?php esc_html_e( 'View full list', 'bimber' ); ?></a>
	</p>
</nav>





