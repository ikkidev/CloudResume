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

$promoted_products_embed_code = bimber_get_theme_option( 'promoted_products', 'embed_code' );

if ( empty( $promoted_products_embed_code ) ) {
	return;
}

$promoted_products_title           = bimber_get_theme_option( 'promoted_products', 'title' );
$promoted_products_description     = bimber_get_theme_option( 'promoted_products', 'description' );
$promoted_products_show_disclosure = bimber_get_theme_option( 'promoted_products', 'disclosure' );
$disclosure_text                   = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );

// Get categories and check if are provided.
$promoted_products_categories     = bimber_get_theme_option( 'promoted_products', 'categories' );
$are_promoted_products_categories = is_array( $promoted_products_categories ) && '' !== $promoted_products_categories[0] ? true : false;
?>
<div class="g1-promoted-products">
	<?php if ( ! empty( $promoted_products_title ) ) : ?>
		<?php bimber_render_section_title( wp_kses_post( $promoted_products_title ), false, array( 'g1-promoted-products-title' ) ); ?>
	<?php endif; ?>
	<?php if ( $promoted_products_show_disclosure && ! empty( $disclosure_text ) ) : ?>
		<?php echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' ); ?>
	<?php endif; ?>
	<?php if ( ! empty( $promoted_products_description ) ) : ?>
		<div class="g1-promoted-products-desc"><?php echo( wp_kses_post( $promoted_products_description ) ); ?></div>
	<?php endif; ?>
	<div class="g1-promoted-products-wrap columns-6">
		<?php echo filter_var( html_entity_decode( $promoted_products_embed_code, ENT_QUOTES ) ); ?>
	</div>
</div>
