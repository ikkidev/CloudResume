<?php
/**
 * The Template for displaying archive "Load More" pagination.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.5
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$posts_per_page = absint( get_query_var( 'bimber_posts_per_page' ) );
if ( ! $posts_per_page ) {
	$posts_per_page = bimber_get_posts_per_page();
}
$found_posts 	= absint( $wp_query->found_posts );
$max_num_pages  = ceil( $found_posts / $posts_per_page );
if ( 1 === (int) $max_num_pages ) {
	return;
}
?>

<div <?php bimber_render_collection_more_class( array( 'infinite-scroll' )); ?>>
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
