<?php
/**
 * The Template for displaying WooCommerce products.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$stp_products_query_args = array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
);

$stp_products_query = new WP_Query( $stp_products_query_args );

if ( $stp_products_query->have_posts() ) {
	while ( $stp_products_query->have_posts() ) {
		$stp_products_query->the_post();

		$stp_term_ids = adace_stp_get_wc_product_term_ids( get_the_ID() );
		$product              = wc_get_product( get_the_ID() );
		$product_price        = $product->get_price_html();
		$product_type         = $product->get_type();
		if ( 'external' !== $product_type ) {
			$product_permalink  = $product->get_permalink();
		} else {
			$product_permalink = $product->get_product_url();
		}
		?>
		<li data-id="<?php echo esc_attr( get_the_ID() ) ?>" data-category-ids="<?php echo esc_attr( implode( ',', $stp_term_ids ) ); ?>" data-price="<?php echo( esc_html( $product_price ) ); ?>" data-url="<?php echo( esc_url( $product_permalink ) ); ?>" class="attachment save-ready">
			<div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
				<div class="thumbnail">
					<div class="centered">
						<?php the_post_thumbnail( 'shop_thumbnail' ); ?>
					</div>
				</div>
				<p class="title">
					<?php the_title(); ?>
				</p>
			</div>

			<button type="button" class="check" tabindex="0">
				<span class="media-modal-icon"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Deselect', 'adace' ); ?></span>
			</button>
		</li>
		<?php
	}
}
wp_reset_postdata();
