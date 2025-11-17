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

$bimber_config = bimber_mc4wp_get_slot_config( 'before_footer' );

if ( empty( $bimber_config ) ) {
	return;
}
$bimber_class = array(
	'g1-row',
	'g1-row-layout-page',
	'g1-newsletter-as-row',
	'g1-newsletter-as-row-before_footer',
	'g1-before_footer',
	'g1-' . bimber_get_theme_option( 'newsletter', 'before_footer_color_scheme' ),
);

?>
<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
	<div class="g1-row-background"></div>

	<div class="g1-row-inner">
		<div class="g1-column">
			<?php
			echo do_shortcode( sprintf(
				'[bimber_mc4wp_form title="%s" subtitle="%s" avatar_id="%d" background_image_id="%d" template="%s" class="%s"]',
				$bimber_config['title'],
				$bimber_config['subtitle'],
				$bimber_config['avatar_id'],
				$bimber_config['background_image_id'],
				'block-horizontal',
				''
			));
			?>
		</div>
	</div>
</div>

