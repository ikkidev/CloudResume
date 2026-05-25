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
<?php if ( $links_query->have_posts() ) : ?>
	<div class="g1-links g1-csstodo-linklist">
		<ul class="adace-links-list">
		<?php while ( $links_query->have_posts() ) : $links_query->the_post(); ?>
			<li>
				<?php
					// Get link meta.
					$link_link      = get_post_meta( get_the_id(), 'adace_link_link', true );
					$link_nofollow  = get_post_meta( get_the_id(), 'adace_link_nofollow', true );
				?>
				<a class="link" href="<?php echo esc_url( $link_link ); ?>" <?php echo $link_nofollow ? 'rel="nofollow"' : ''; ?>><?php the_title(); ?></a>
			</li>
		<?php endwhile; ?>
		</ul>
	</div>
<?php endif;
wp_reset_postdata();
