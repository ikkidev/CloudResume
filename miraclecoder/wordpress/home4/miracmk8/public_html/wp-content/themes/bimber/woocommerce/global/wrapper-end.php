<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 	3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if ( is_product() ) : ?>
		</div>
		<?php if ( 'show' === bimber_get_theme_option( 'woocommerce', 'single_product_sidebar' ) ) : ?>
			</div>
			<?php get_sidebar(); ?>
		<?php else : ?>
			</div>
		<?php  endif; ?><!-- .g1-column -->
<?php else : ?>
	</div><!-- .g1-column -->
<?php endif;