<?php
/**
 * Header Builder template
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<?php if ( bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) && apply_filters( 'bimber_show_mini_cart', true ) ) : ?>
	<?php $bimber_count = WC()->cart->get_cart_contents_count(); ?>
	<div class="g1-drop g1-drop-with-anim g1-drop-before g1-drop-the-cart  <?php bimber_hb_get_element_class_from_settings( 'cart' );?>">
		<a class="g1-drop-toggle" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
			<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Cart', 'bimber' ); ?></span>
				<?php if ( $bimber_count ) : ?>
					<span class="g1-drop-toggle-badge"><?php echo (int) $bimber_count; ?></span>
				<?php else : ?>
					<span class="g1-drop-toggle-badge g1-drop-toggle-badge-hidden">0</span>
				<?php endif; ?>
			<span class="g1-drop-toggle-arrow"></span>
		</a>

		<div class="g1-drop-content">

			<?php
				the_widget( 'WC_Widget_Cart', 'title=' );
			?>
		</div>

	</div>
<?php endif;
