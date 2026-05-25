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
?>
	<div class="g1-row g1-row-layout-page g1-row-padding-m g1-promoted-products-section g1-promoted-products-boxed">
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php get_template_part( 'template-parts/promoted-products/base-standard' ); ?>
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</div>
<?php
