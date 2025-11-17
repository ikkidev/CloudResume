<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
?>
<nav class="g1-pagination woocommerce-pagination">
	<p class="g1-pagination-label g1-pagination-label-links"><?php esc_html_e( 'Pages', 'bimber' ); ?></p>

	<?php
		echo str_replace(
			array(
				'<ul class=\'page-numbers\'>',
				'<li><a class="prev page-numbers"',
				'<li><a class="page-numbers"',
				'<li><span class="page-numbers current">',
				'<li><span aria-current="page" class="page-numbers current">',
				'</span></li>',
				'<li><a class="next page-numbers"',
			),
			array(
				'<ul>',
				'<li class="g1-pagination-item g1-pagination-item-prev"><a class="g1-link g1-link-left prev"',
				'<li class="g1-pagination-item"><a class="g1-link"',
				'<li class="g1-pagination-item g1-pagination-item-current"><span class="g1-link">',
				'<li aria-current=\'page\' class="g1-pagination-item g1-pagination-item-current"><span class="g1-link">',
				'</span></li>',
				'<li class="g1-pagination-item g1-pagination-item-next"><a class="g1-link g1-link-right next"',
			),
			paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
				'base'         => $base,
				'format'       => $format,
				'add_args'     => false,
				'current'      => max( 1, $current ),
				'total'        => $total,
				'prev_text'    => __( 'Previous', 'bimber' ),
				'next_text'    => __( 'Next', 'bimber' ),
				'type'         => 'list',
				'end_size'     => 3,
				'mid_size'     => 3,
			) ) )
		);
	?>
</nav>
