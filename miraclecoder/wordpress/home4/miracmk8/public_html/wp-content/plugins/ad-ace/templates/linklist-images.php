<?php
/**
 * Linklist
 *
 * @package Adace
 * @subpackage Links
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<?php if ( $links_query -> have_posts() ) : ?>
<div class="adace-linklist-with-images-csstodo">
	<ul class="adace-links-list <?php echo sanitize_html_class( $transparent ? 'transparent' : '' ); ?>">
	<?php while ( $links_query->have_posts() ) : $links_query->the_post(); ?>
			<?php
				// Get link meta.
				$link_link      = get_post_meta( get_the_id(), 'adace_link_link', true );
				$link_nofollow  = get_post_meta( get_the_id(), 'adace_link_nofollow', true );

				$classes = array(
					'item',
				);

				if ( $links_query->current_post < intval( $highlighted ) ) {
					$classes[] = 'item-highlighted';
				}
			?>
		<li class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
			<a href="<?php echo esc_url( $link_link ); ?>" <?php $link_nofollow ? 'rel="nofollow"' : ''; ?>>
				<?php the_post_thumbnail( apply_filters( 'adace_link_size', 'original' ) ); ?>
			</a>
		</li>
	<?php endwhile; ?>
	</ul>
</div>
<?php endif;
wp_reset_postdata();