<?php
/**
 * The Template for displaying podcast.
 *
 * @package Bimber_Theme 5.3.2
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Lets make sure that we can use G1 Socials.
if ( bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ) {
	global $bimber_social_section_positon;
	$color_class = 'g1-light';
	$color_scheme = bimber_get_theme_option( 'social', $bimber_social_section_positon . '_color_scheme' );
	$color_class = 'g1-' . $color_scheme;
	?>
		<div class="g1-row g1-row-layout-page g1-socials-section <?php echo( $color_class ); ?>">
			<div class="g1-row-inner">
				<div class="g1-column">
					<?php echo do_shortcode( '[g1_socials icon_size="32" icon_color="text"]' ); ?>
				</div>
			</div>
		</div>
	<?php
}
