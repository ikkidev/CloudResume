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

$newsletter_config = bimber_mc4wp_get_slot_config( 'before_collection' );

if ( empty( $newsletter_config ) ) {
	return;
}

$newsletter_classes = apply_filters( 'bimber_newsletter_before_main_collection_class', array(
	'g1-newsletter',
	'g1-newsletter-horizontal',
	'g1-newsletter-fancy',
) );
?>

<div class="g1-row g1-row-layout-page g1-row-with-newsletter g1-light">
	<div class="g1-row-background"></div>
	<div class="g1-row-inner">
		<div class="g1-column">
			<?php
			echo do_shortcode( sprintf(
				'[bimber_mc4wp_form title="%s" subtitle="%s" avatar_id="%d" background_image_id="%d" template="%s" class="%s"]',
				$newsletter_config['title'],
				$newsletter_config['subtitle'],
				$newsletter_config['avatar_id'],
				$newsletter_config['background_image_id'],
				$newsletter_config['template'],
				implode( ' ', $newsletter_classes )
			));
			?>
		</div>
	</div>
</div><!-- .g1-row -->
