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
<?php if ( bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ) : ?>
	<div class="g1-drop g1-drop-with-anim g1-drop-the-socials <?php bimber_hb_get_element_class_from_settings( 'social_icons_dropdown' );?>">
		<a class="g1-drop-toggle" href="#" title="<?php esc_attr_e( 'Follow us', 'bimber' ); ?>">
			<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Follow us', 'bimber' ); ?></span>
			<span class="g1-drop-toggle-arrow"></span>
		</a>
		<div class="g1-drop-content">
			<?php echo do_shortcode( '[g1_socials icon_size="48" icon_color="text"]' ); ?>
		</div>
	</div>
<?php endif;