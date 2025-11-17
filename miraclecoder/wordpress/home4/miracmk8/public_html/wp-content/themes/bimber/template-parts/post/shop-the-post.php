<?php
/**
 * The Template Part for displaying "About Author" box.
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
// Make sure that we have WooCommerce on board. If not leave!
if ( ! bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) && ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
	return;
}

$metadata = apply_filters( 'adace_shop_the_post_metadata', array(
    'id'         => get_the_ID(),
    'type'       => 'post',
    'key_suffix' => ! empty( $args['block_id'] ) ? sprintf( '_%s', $args['block_id'] ) : '',
) );

wp_enqueue_script( 'flickity', trailingslashit( get_template_directory_uri() ) . 'js/flickity/flickity.pkgd.min.js', array( 'jquery' ), '2.0.9', true );

$part_data = bimber_get_template_part_data();

// We need to disable some actions for this display.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// Get Options
$excerpt = get_option( 'adace_shop_the_post_excerpt', adace_options_get_defaults( 'adace_shop_the_post_excerpt' ) );
$excerpt_hide_on_single = get_option( 'adace_shop_the_post_excerpt_hide_on_single', adace_options_get_defaults( 'adace_shop_the_post_excerpt_hide_on_single' ) );
// Add excerpt.
if ( $excerpt ) {
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
}
if ( is_single() && $excerpt_hide_on_single ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
}
// Add disclosure
$show_disclosure = get_option( 'adace_shop_the_post_disclosure', adace_options_get_defaults( 'adace_shop_the_post_disclosure' ) );
$disclosure_text = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );

$post_related_products = array();

if ( function_exists( 'bimber_get_post_related_products' ) ) {
    // Special case. zigzag template.
    if ( isset($part_data['template'] ) && 'zigzag' === $part_data['template'] ) {
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        $post_related_products  = apply_filters( 'bimber_post_related_products', bimber_get_post_related_products( $metadata['id'], '3', $metadata ) );
    } else {
        $post_related_products  = apply_filters( 'bimber_post_related_products', bimber_get_post_related_products( $metadata['id'], 4, $metadata ) );
    }
}

// Get options.
$related_products_type = get_metadata( $metadata['type'], $metadata['id'], 'adace_related_products_type' . $metadata['key_suffix'], true );
if ( isset( $part_data['related_products_shortcode'] ) ) {
	$related_products_title = $part_data['related_products_shortcode']['title'];
	$related_products_type = 'woocommerce';
} else {
	$related_products_title      = get_metadata( $metadata['type'], $metadata['id'], 'adace_related_products_title' . $metadata['key_suffix'], true );
	$related_products_embed      = get_metadata( $metadata['type'], $metadata['id'], 'adace_related_products_embed' . $metadata['key_suffix'], true );
}
$title_classes          = array( 'g1-delta', 'g1-delta-2nd' );

// We need to enable back some actions.
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// Remove excerpt.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
// Add them back.
if ( isset($part_data['template'] ) && 'zigzag' === $part_data['template'] ) {
	add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	$title_classes = array( 'g1-epsilon', 'g1-epsilon-2nd' );
}

if ( ( ! empty( $post_related_products ) && 'woocommerce' === $related_products_type ) || ( ! empty( $related_products_embed ) && 'embed' === $related_products_type ) ) :
	do_action( 'bimber_before_related_products' );
?>
<div class="adace-shop-the-post<?php if ( ! empty( $args['block_id'] ))  echo ' ' . sanitize_html_class( 'adace-shop-the-post-' . $args['block_id'] ); ?>">
	<?php if ( ! empty( $related_products_title ) ) : ?>
		<h2 class="adace-shop-the-post-title <?php echo implode( ' ', array_map( 'sanitize_html_class', $title_classes ) ); ?>"><?php echo( wp_kses_post( $related_products_title ) ); ?></h2>
	<?php endif; ?>
	<?php if ( $show_disclosure && ! empty( $disclosure_text ) ) : ?>
		<?php echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' ); ?>
	<?php endif; ?>
	<?php if ( ! empty( $related_products_embed ) && 'embed' === $related_products_type ) : ?>
		<div class="adace-shop-the-post-embed">
			<?php echo filter_var( html_entity_decode( $related_products_embed, ENT_QUOTES ) ); ?>
		</div>
	<?php endif; ?>
	<?php if ( 'woocommerce' === $related_products_type && ! empty( $post_related_products ) ) : ?>
		<div class="adace-shop-the-post-wrap carousel-wrap">
			<?php
			if ( bimber_can_use_plugin( 'amp/amp.php' ) && is_amp_endpoint() ) {
				get_template_part( 'amp/shop-the-post' );
			} else {
				echo ( wp_kses_post( $post_related_products ) );
			}
			?>
		</div>
	<?php endif; ?>
</div>
<?php
	do_action( 'bimber_after_related_products' );
	wp_reset_postdata();
endif;
