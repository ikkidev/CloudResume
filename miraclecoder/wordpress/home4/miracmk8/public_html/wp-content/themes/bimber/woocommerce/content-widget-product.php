<?php
/**
 * The template for displaying product widget entries
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$product_type         = $product->get_type();
$product_external_url = $product->get_permalink();
$product_target       = '_self';

if ( 'external' !== $product_type ) {
	$product_permalink = $product->get_permalink();
	$product_target    = '_self';
} else {
	$product_external_url = $product->get_product_url();
	$product_permalink    = empty( $product_external_url ) ? $product->get_permalink() : $product_external_url;
	$product_target       = '_blank';
}
?>

<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

	<a class="product-media" href="<?php echo esc_url( $product_permalink ); ?>"
	   title="<?php echo esc_attr( $product->get_title() ); ?>" target="<?php echo esc_html( $product_target ); ?>">
		<?php echo $product->get_image(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>

	<div class="product-body">
		<a class="g1-epsilon g1-epsilon-1st" href="<?php echo esc_url( $product_permalink ); ?>" target="<?php echo esc_html( $product_target ); ?>">
			<span class="product-title"><?php echo wp_kses_post( $product->get_name() ); ?></span>
		</a>

		<?php if ( ! empty( $show_rating ) ) : ?>
			<?php echo wc_get_rating_html( $product->get_average_rating() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
		<?php echo $product->get_price_html(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>


