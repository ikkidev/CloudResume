<?php
/**
 * The Template Part for displaying archive "Prev/Next" pagination.
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

global $wp_query;

$range = 3;

$posts_per_page = absint( get_query_var( 'bimber_posts_per_page' ) );

if ( 0 === $posts_per_page ) {
	$posts_per_page = absint( get_query_var( 'posts_per_page' ) );
}

$found_posts 	= absint( $wp_query->found_posts );
$paged          = absint( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$max_num_pages  = ceil( $found_posts / $posts_per_page );

$max_num_links = 2 * $range + 1;
$start_at      = 0;
$end_at        = 0;

if ( $max_num_links >= $max_num_pages ) {
	$start_at = 1;
	$end_at   = $max_num_pages;
} else {
	// Determine first page to display.
	$start_at = $paged - $range;
	if ( $start_at < 1 ) {
		$start_at = 1;
	}

	// Determine last page to display.
	$end_at = $paged + $range;
	if ( $end_at > $max_num_pages ) {
		$end_at = $max_num_pages;
	}
}
?>

<?php if ( $max_num_pages > 1 ) : ?>
	<?php get_template_part( 'template-parts/ads/ad-before-pagination' ); ?>

	<nav class="g1-pagination">
		<p class="g1-pagination-label g1-pagination-label-links"><?php esc_html_e( 'Pages', 'bimber' ); ?></p>

		<ul>

			<?php
				// Previous Page Link.
				$prev_page = $paged - 1;
			?>
			<?php if ( $prev_page >= 1 ) : ?>
				<li class="g1-pagination-item g1-pagination-item-prev">
					<a class="g1-link g1-link-m g1-link-left prev" href="<?php echo esc_url( get_pagenum_link( $prev_page ) ); ?>"><?php esc_html_e( 'Previous', 'bimber' ); ?></a>
				</li>
			<?php endif; ?>

			<?php for ( $i = $start_at; $i <= $end_at; $i ++ ) : ?>
				<?php if ( $i !== $paged ) : ?>
					<li class="g1-pagination-item">
						<a class="g1-link g1-link-m" href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>"><?php echo intval( $i ); ?></a>
					</li>
				<?php else : ?>
					<li class="g1-pagination-item g1-pagination-item-current">
						<strong class="g1-link g1-link-xl"><?php echo intval( $i ); ?></strong>
					</li>
				<?php endif; ?>
			<?php endfor; ?>

			<?php
				// Next Page Link.
				$next_page = $paged + 1;
			?>
			<?php if ( $next_page <= $max_num_pages ) : ?>
				<li class="g1-pagination-item g1-pagination-item-next">
					<a class="g1-link g1-link-m g1-link-right next" href="<?php echo esc_url( get_pagenum_link( $next_page ) ); ?>"><?php esc_html_e( 'Next', 'bimber' ); ?></a>
				</li>
			<?php endif; ?>

		</ul>
	</nav>

	<?php get_template_part( 'template-parts/ads/ad-after-pagination' ); ?>
<?php endif;
