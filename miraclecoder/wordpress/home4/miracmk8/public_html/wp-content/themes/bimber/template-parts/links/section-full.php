<?php
/**
 * The Template for displaying podcast.
 *
 * @package Bimber_Theme
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
global $bimber_links_section_positon;
$bimber_links_above_footer_color_scheme          = bimber_get_theme_option( 'links', 'above_footer_color_scheme' );
?>
	<div
		class="g1-row g1-row-layout-page g1-row-padding-m g1-links-<?php echo( $bimber_links_section_positon ); ?> g1-section-row g1-<?php echo( $bimber_links_above_footer_color_scheme ); ?>">
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php get_template_part( 'template-parts/links/base', 'standard' ); ?>
			</div>
		</div>
		<div class="g1-row-background"></div>
	</div>
<?php
