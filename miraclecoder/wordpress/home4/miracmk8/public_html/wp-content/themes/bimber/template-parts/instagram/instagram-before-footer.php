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
global $bimber_instagram_section_type;
global $bimber_instagram_section_positon;
global $bimber_instagram_single_row;
$color_scheme = bimber_get_theme_option( 'instagram', $bimber_instagram_section_positon . '_color_scheme' );
$bimber_instagram_single_row = bimber_get_theme_option( 'instagram', $bimber_instagram_section_positon . '_single_row' );
$color_class = 'g1-' . $color_scheme;

?>
<div
	class="g1-row g1-row-wide g1-row-layout-page g1-row-instagram-before-footer g1-instagram-section <?php echo( $color_class ); ?>">
	<div class="g1-row-inner">
		<div class="g1-column">
			<?php
			if ( 'compressed' === $bimber_instagram_section_type ) {
				get_template_part( 'template-parts/instagram/feed', 'compressed' );
			} else {
				echo '<div class="g1-instagram-csstodo-boxed">';

				if ( $bimber_instagram_single_row ) {
					get_template_part( 'template-parts/instagram/feed', 'expanded-r1' );
				} else {
					get_template_part( 'template-parts/instagram/feed', 'expanded-r2' );
				}
				echo '</div>';
			}
			?>
		</div>
	</div>
</div>

