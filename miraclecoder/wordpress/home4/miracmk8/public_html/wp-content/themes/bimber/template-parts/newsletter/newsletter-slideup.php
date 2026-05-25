<?php
/**
 * Newsletter template
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

if ( ! bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
	return;
}

$newsletter_config = bimber_mc4wp_get_slot_config( 'slideup' );

if ( empty( $newsletter_config ) ) {
	return;
}
?>

<div class="g1-slideup-wrap">
	<div class="g1-slideup-base"></div>
	<div class="g1-slideup-newsletter">
		<div class="g1-slideup-newsletter-body">
			<?php
			echo do_shortcode( sprintf(
				'[bimber_mc4wp_form title="%s" subtitle="%s" avatar_id="%d" background_image_id="%d" template="%s"]',
				$newsletter_config['title'],
				$newsletter_config['subtitle'],
				$newsletter_config['avatar_id'],
				$newsletter_config['background_image_id'],
				'horizontal-l'
			));
			?>
		</div>
	</div>
	<a href="#" class="g1-slideup-newsletter-closer"><?php echo esc_html_x( 'Close', 'button', 'bimber' );?></a>
</div>
