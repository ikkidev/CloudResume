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
<?php if ( bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ) :
	ob_start();
	echo do_shortcode( '[g1_socials icon_size="48" icon_color="text"]' );
	$html = ob_get_clean();
	$class =  'g1-socials-items-tpl-grid g1-socials-hb-list ' . bimber_hb_get_element_class_from_settings( 'social_icons_full', false );
	$html = str_replace( 'g1-socials-items-tpl-grid', $class, $html );
	echo $html;
endif; ?>
