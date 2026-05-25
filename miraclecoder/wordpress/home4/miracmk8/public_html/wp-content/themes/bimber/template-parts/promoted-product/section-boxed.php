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
global $bimber_promoted_product_section_positon;
?>
<?php if ( 'above_collection' === $bimber_promoted_product_section_positon ) : ?>
	<div class="g1-row g1-row-layout-page g1-stripe g1-stripe-promoted-product">
		<div class="g1-row-background">
		</div>

		<div class="g1-row-inner">
			<div class="g1-column">
				<div class="g1-stripe-csstodo">
					<div class="g1-stripe-background">
					</div>

					<?php get_template_part( 'template-parts/promoted-product/base-standard' ); ?>
				</div>
			</div>
		</div>
	</div><!-- .g1-row -->
<?php else : ?>
	<div class="g1-row g1-row-layout-page">
		<div class="g1-row-background">
		</div>

		<div class="g1-row-inner">
			<div class="g1-column">
				<?php get_template_part( 'template-parts/promoted-product/base-large' ); ?>
			</div>
		</div>
	</div><!-- .g1-row -->
<?php endif;
