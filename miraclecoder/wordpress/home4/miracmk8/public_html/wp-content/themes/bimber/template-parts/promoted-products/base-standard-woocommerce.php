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

// Make sure that we have WooCommerce on board. If not leave!
if ( ! bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
	return;
}

// Hide on WooCommerce pages.
if ( apply_filters( 'bimber_promoted_products_hide_on_wc_pages', is_woocommerce() || is_cart() || is_checkout() ) ) {
	return;
}

// Get all options for this template part.
$promoted_products_title           	= bimber_get_theme_option( 'promoted_products', 'title' );
$promoted_products_description     	= bimber_get_theme_option( 'promoted_products', 'description' );
$promoted_products_hide_price      	= bimber_get_theme_option( 'promoted_products', 'hide_price' );
$promoted_products_hide_add_to_cart	= bimber_get_theme_option( 'promoted_products', 'hide_add_to_cart' );
$promoted_products_show_disclosure 	= bimber_get_theme_option( 'promoted_products', 'disclosure' );
$disclosure_text                   	= get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );


$promoted_products_link_href      	= get_permalink( wc_get_page_id( 'shop' ) );
$promoted_products_link_label      	= bimber_get_theme_option( 'promoted_products', 'link_label' );
$promoted_products_link_show        = empty( $promoted_products_link_href ) ? false : true;

// Get categories and check if are provided.
$promoted_products_categories     = bimber_get_theme_option( 'promoted_products', 'categories' );
$are_promoted_products_categories = is_array( $promoted_products_categories ) && '' !== $promoted_products_categories[0] ? true : false;

// Posts per page.
$posts_per_page = 6;
// Build base for products query.
$products_query_args = array(
	'post_type'           => 'product',
	'posts_per_page'      => $posts_per_page,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'post__in'            => array(),
	'orderby'             => 'post__in',
);

// Build query for taxonomies, we need to split these if we have already post__in.
if ( ( $are_promoted_products_categories ) ) {
	$products_tax_query_args = array(
		'post_type'           => 'product',
		'posts_per_page'      => $posts_per_page,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'tax_query'           => array(),
		'fields'              => 'ids',
	);

	$products_tax_query_args['tax_query'] = array();

	// Add categories.
	if ( $are_promoted_products_categories ) {
		$products_tax_query_args['tax_query'][] = array(
			'relation' => 'OR',
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $promoted_products_categories,
		);
	}

	$products_tax_query = new WP_Query( $products_tax_query_args );
	// If we get results form tax query add them to proper query.
	if ( $products_tax_query->have_posts() ) {
		$products_query_args['post__in'] = array_merge( $products_query_args['post__in'], $products_tax_query->posts );
	}
}// End if().

$products_query = new WP_Query( $products_query_args );

if ( $products_query->have_posts() ) :
	do_action( 'bimber_before_promoted_products' );
	// We need to disable some actions for this display.
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	if ( $promoted_products_hide_add_to_cart ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
	if ( $promoted_products_hide_price ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 1 );
	}
	// Add excerpt.
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
	?>
	<div class="g1-promoted-products">
		<header class="g1-promoted-products-header">
			<?php if ( ! empty( $promoted_products_title ) ) : ?>
				<?php bimber_render_section_title( wp_kses_post( $promoted_products_title ), false, array( 'g1-promoted-products-title' ) ); ?>
			<?php endif; ?>

			<?php if ( $promoted_products_show_disclosure && ! empty( $disclosure_text ) ) : ?>
				<?php echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' ); ?>
			<?php endif; ?>
		</header>

		<?php if ( ! empty( $promoted_products_description ) ) : ?>
			<div class="g1-promoted-products-desc"><?php echo( wp_kses_post( $promoted_products_description ) ); ?></div>
		<?php endif; ?>

		<div class="g1-promoted-products-wrap woocommerce">
			<?php
				wc_set_loop_prop( 'columns', 6 );
				woocommerce_product_loop_start();
			?>
			<?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
				<?php
				add_filter( 'woocommerce_short_description', '__return_false' );
				wc_get_template_part( 'content', 'product' );
				remove_filter( 'woocommerce_short_description', '__return_false' );
				?>
			<?php endwhile; ?>
			<?php woocommerce_product_loop_end(); ?>
		</div>
		<?php if ( $promoted_products_link_show ) : ?>
			<p class="g1-promoted-products-link">
				<a class="g1-link g1-link-s g1-link-right" href="<?php echo esc_url( $promoted_products_link_href  ); ?>"><?php echo wp_kses_post( $promoted_products_link_label ); ?></a>
			</p>
		<?php endif; ?>
	</div>
	<?php
	do_action( 'bimber_after_promoted_products' );
	wp_reset_postdata();
// We need to enable back some actions back.
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	if ( $promoted_products_hide_price ) {
		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 1 );
	}
// Remove excerpt.
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 2 );
endif;
