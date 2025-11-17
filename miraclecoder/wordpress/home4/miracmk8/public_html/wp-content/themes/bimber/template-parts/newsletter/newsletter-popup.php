<?php
/**
 * Newsletter popup
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.5
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
if ( ! bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ){
	return;
}

$bimber_config = bimber_mc4wp_get_slot_config( 'popup' );
?>
<div class="g1-popup g1-popup-newsletter">
	<div class="g1-popup-overlay">
	</div>

	<div class="g1-popup-inner">
		<?php
			echo do_shortcode( sprintf(
				'[bimber_mc4wp_form title="%s" subtitle="%s" avatar_id="%d" background_image_id="%d" template="%s" class="%s"]',
				$bimber_config['title'],
				$bimber_config['subtitle'],
				$bimber_config['avatar_id'],
				$bimber_config['background_image_id'],
				'halves-vertical',
				''// CSS classes
			));
		?>

		<a href="#" class="g1-popup-closer"><?php echo esc_html_x( 'Close', 'button', 'bimber' ); ?></a>
	</div>
</div><!-- .g1-popup -->
