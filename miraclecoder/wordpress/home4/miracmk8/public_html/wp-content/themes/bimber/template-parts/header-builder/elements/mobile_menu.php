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
<?php if ( has_nav_menu( 'bimber_primary_nav' ) ) : ?>
	<a class="g1-hamburger g1-hamburger-show <?php bimber_hb_get_element_class_from_settings( 'mobile_menu' );?>" href="#">
		<span class="g1-hamburger-icon"></span>
			<span class="g1-hamburger-label
			<?php
			if ( 'standard' !== bimber_get_theme_option( 'header_builder', 'element_label_mobile_menu' ) ) {
				echo sanitize_html_class( 'g1-hamburger-label-hidden' );
			}
			?>
			"><?php esc_html_e( 'Menu', 'bimber' ); ?></span>
	</a>
<?php endif; ?>
