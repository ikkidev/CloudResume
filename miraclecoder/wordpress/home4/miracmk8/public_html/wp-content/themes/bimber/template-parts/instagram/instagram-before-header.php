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

$invert = bimber_get_theme_option( 'instagram', $bimber_instagram_section_positon . '_invert' );
?>
<div
	class="g1-row g1-row-wide g1-row-layout-page g1-instagram-section<?php echo( $invert ? ' g1-dark' : '' ); ?>">
	<div class="g1-row-inner">
		<div class="g1-column">
			<?php
			if ( 'compressed' === $bimber_instagram_section_type ) {
				get_template_part( 'template-parts/instagram/feed', 'compressed' );
			} else {
				echo '<div class="g1-instagram-csstodo-boxed">';
				get_template_part( 'template-parts/instagram/feed', 'expanded' );
				echo '</div>';
			}
			?>
		</div>
	</div>
</div>
