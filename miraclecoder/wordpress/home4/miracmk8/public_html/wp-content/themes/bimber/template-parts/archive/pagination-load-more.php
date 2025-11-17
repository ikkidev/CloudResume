<?php
/**
 * The Template for displaying archive "Load More" pagination.
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
<?php if ( null !== get_next_posts_link() ) : ?>
	<?php get_template_part( 'template-parts/ads/ad-before-pagination' ); ?>

	<div <?php bimber_render_collection_more_class(); ?>>
		<div class="g1-collection-more-inner">
			<a href="#"
			   class="g1-button g1-button-m g1-button-solid g1-load-more"
			   data-g1-next-page-url="<?php echo esc_url( get_next_posts_page_link() ); ?>">
				<?php esc_html_e( 'Load More', 'bimber' ) ?>
			</a>
			<i class="g1-collection-more-spinner"></i>
			<div class="g1-pagination-end">
				<?php esc_html_e( "Congratulations. You've reached the end of the internet.", 'bimber' ) ?>
			</div>
		</div>
	</div>

	<?php get_template_part( 'template-parts/ads/ad-after-pagination' ); ?>
<?php endif;
