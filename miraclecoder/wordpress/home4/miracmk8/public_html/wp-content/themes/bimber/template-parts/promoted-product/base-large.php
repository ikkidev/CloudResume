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

// Get all options for this template part.
$promoted_product_title      = bimber_get_theme_option( 'promoted_product', 'title' );

$promoted_product_title_classes = array(
	'g1-delta',
	'g1-delta-2nd',
	'g1-promoted-product-title'
);

if ( empty( $promoted_product_title ) ) {
	$promoted_product_title = __('Shop <em>With</em> Me', 'bimber');
	$promoted_product_title_classes[] = 'screen-reader-text';
}

$promoted_product_title_classes = apply_filters( 'bimber_promoted_product_title_classes', $promoted_product_title_classes );

$promoted_product_show_disclosure  = bimber_get_theme_option( 'promoted_product', 'disclosure' );
$disclosure_text                   = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );

$promoted_product_link_label = bimber_get_theme_option( 'promoted_product', 'link_label' );

// Get ids and check if are provided. Thanks customizer.
$promoted_product_id = bimber_get_theme_option( 'promoted_product', 'id' );

if ( empty( $promoted_product_id ) ) {
	return;
}

$promoted_product = wc_get_product( $promoted_product_id );

if ( $promoted_product ) {
	$promoted_product_thumbnail     = get_the_post_thumbnail( $promoted_product_id, 'shop_single' );

	do_action( 'bimber_before_promoted_product' );
	?>
	<div class="g1-promoted-product">
		<?php bimber_render_section_title( wp_kses_post( $promoted_product_title ), false, $promoted_product_title_classes ); ?>
		<?php if ( $promoted_product_show_disclosure && ! empty( $disclosure_text ) ) : ?>
			<?php echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' ); ?>
		<?php endif; ?>


		<div class="g1-row">
			<div class="g1-row-inner">
				<div class="g1-column g1-column-1of2 g1-promoted-product-thumbnail">
					<?php echo( $promoted_product_thumbnail ); ?>
				</div>
				<div class="g1-column g1-column-1of2 g1-promoted-product-details">
					<h3 class="g1-alpha g1-alpha-1st product-title"><?php echo( $promoted_product->get_name() ); ?></h3>

					<p class="product-description"><?php echo( $promoted_product->get_description() ); ?></p>
					<?php
						if ( shortcode_exists( 'add_to_cart_url' ) ) {
							echo str_replace(
								'g1-button-s ',
								'g1-button-m ',
								do_shortcode( '[add_to_cart id="' . $promoted_product_id . '" style=""]' )
							);
						}
					?>
					<?php if ( ! empty( $promoted_product_link_label ) ) : ?>
						<div class="product-link"><a class="g1-link g1-link-right"
						                             href="<?php echo( esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) ); ?>"><?php echo( wp_kses_post( $promoted_product_link_label ) ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	do_action( 'bimber_after_promoted_product' );
}
wp_reset_postdata();
