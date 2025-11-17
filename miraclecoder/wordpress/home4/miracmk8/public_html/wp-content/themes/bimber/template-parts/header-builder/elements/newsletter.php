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
<?php if ( bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) : ?>
		<div class="g1-drop g1-drop-with-anim g1-drop-before g1-drop-the-newsletter <?php bimber_hb_get_element_class_from_settings( 'newsletter' );?>">
			<span class="g1-drop-toggle">
				<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Subscribe', 'bimber' ); ?></span>
				<span class="g1-drop-toggle-arrow"></span>
			</span>

			<div class="g1-drop-content">
				<?php echo do_shortcode( '[bimber_mc4wp_form title="" template="vertical"]' ); ?>
			</div>
		</div>
	<?php endif; ?>
