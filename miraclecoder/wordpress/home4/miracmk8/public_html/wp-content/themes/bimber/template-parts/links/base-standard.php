<?php
/**
 * The Template for links.
 *
 * @package Bimber_Theme
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
if ( ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) {
	return;
}
global $bimber_links_section_positon;
$links_title    = bimber_get_theme_option( 'links_' . $bimber_links_section_positon, 'title' );
$links_category = bimber_get_theme_option( 'links_' . $bimber_links_section_positon, 'category' );

$links_simple       = bimber_get_theme_option( 'links_' . $bimber_links_section_positon, 'simple' );
$links_transparent  = bimber_get_theme_option( 'links_' . $bimber_links_section_positon, 'transparent' );
$links_output       = adace_get_links_list( $links_category, $links_simple, 0, $links_transparent );
$show_disclosure 	= bimber_get_theme_option( 'links_' . $bimber_links_section_positon, 'disclosure' );
$disclosure_text    = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );
?>
<?php if ( ! empty( $links_output ) ) : ?>
	<div class="g1-section g1-section-links">
		<?php if ( ! empty( $links_title ) ) : ?>
			<?php bimber_render_section_title( $links_title, false, array( 'g1-links-title' ) ); ?>
		<?php endif; ?>
		<?php if ( $show_disclosure && ! empty( $disclosure_text ) ) : ?>
			<p class="adace-disclosure g1-meta g1-meta-s"><?php echo wp_kses_post( html_entity_decode( $disclosure_text ) ); ?></p>
		<?php endif; ?>
		<?php echo( wp_kses_post( $links_output ) ); ?>
	</div>
<?php endif;
