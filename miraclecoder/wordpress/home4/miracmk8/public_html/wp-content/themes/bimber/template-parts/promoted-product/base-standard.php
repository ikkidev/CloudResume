<?php
/**
 * The Template Part for displaying promoted products.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Make sure that we have AdAce and WooCommerce on board. If not leave!
if ( ! ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) && bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) ) ) {
	return;
}

// Change button classes.
add_filter( 'woocommerce_loop_add_to_cart_link', 'bimber_woocommerce_promote_product_link', 10, 2 );

// Get all options for this template part.
$promoted_product_title = bimber_get_theme_option( 'promoted_product', 'title' );

// Get ids and check if are provided. Thanks customizer.
$promoted_product_id = bimber_get_theme_option( 'promoted_product', 'id' );
if ( empty( $promoted_product_id ) ) {
	return;
}

$promoted_product = wc_get_product( $promoted_product_id );

if ( $promoted_product ) {
	$product_type = $promoted_product->get_type();
	if ( 'external' !== $product_type ) {
		$product_permalink = $promoted_product->get_permalink();
		$product_target    = '_self';
	} else {
		$product_external_url = $promoted_product->get_product_url();
		$product_permalink    = empty( $product_external_url ) ? $promoted_product->get_permalink() : $product_external_url;
		$product_target       = '_blank';
	}
	$promoted_product_thumbnail = get_the_post_thumbnail( $promoted_product_id, 'thumbnail' );
	do_action( 'bimber_before_promoted_product' );
	?>
	<div class="g1-stripe-inner <?php echo( $promoted_product_thumbnail ? 'with-thumbnail' : '' ); ?>">
		<?php if ( $promoted_product_thumbnail ) : ?>
			<a class="g1-stripe-thumbnail" href="<?php echo( esc_url( $product_permalink ) ); ?>" rel="bookmark"
			   target="<?php esc_html( $product_target ); ?>">
				<?php echo( $promoted_product_thumbnail ); ?>
			</a>
		<?php endif; ?>
		<div class="g1-stripe-body">
			<div class="g1-stripe-content">
				<h2 class="g1-zeta g1-zeta-2nd g1-stripe-label"><?php esc_html_e( 'Promoted Product', 'bimber' ); ?></h2>

				<h3 class="entry-title g1-stripe-title">
					<a href="<?php echo( esc_url( $product_permalink ) ); ?>" rel="bookmark" target="<?php esc_attr( $product_target ); ?>">
						<?php echo esc_html( $promoted_product->get_name() ); ?>
					</a>
				</h3>

			</div>

			<div class="g1-stripe-actions">
				<?php
				if ( shortcode_exists( 'add_to_cart' ) ) {
					echo( do_shortcode( '[add_to_cart id="' . $promoted_product_id . '" style="" class="g1-light"]' ) );
				}
				?>
			</div>
		</div>
	</div>
	<?php
	do_action( 'bimber_after_promoted_product' );
}
wp_reset_postdata();

remove_filter( 'woocommerce_loop_add_to_cart_link', 'bimber_woocommerce_promote_product_link', 10 );
