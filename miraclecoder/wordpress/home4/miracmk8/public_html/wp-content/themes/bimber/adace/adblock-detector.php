<?php
/**
 * Adace Adblock Detector.
 *
 * @package adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$title 			= get_option( 'adace_adblock_detector_title', adace_options_get_defaults( 'adace_adblock_detector_title' ) );
$description 	= get_option( 'adace_adblock_detector_description', adace_options_get_defaults( 'adace_adblock_detector_description' ) );
$page 			= get_option( 'adace_adblock_detector_page', adace_options_get_defaults( 'adace_adblock_detector_page' ) );
?>

<div class="adace-popup adace-popup-detector">
	<div class="adace-popup-inner">
		<div class="adace-detector-flag">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
				<path d="M8,0c0,2-7,2-7,2s0,6,0,8.4c0,4,7,5.2,7,5.6c0-0.4,7-1.6,7-5.6C15,8,15,2,15,2S8,2,8,0z M10.9,10.2l-0.7,0.7L8,8.7l-2.2,2.2
	l-0.7-0.7L7.3,8L5.1,5.8l0.7-0.7L8,7.3l2.2-2.2l0.7,0.7L8.7,8L10.9,10.2z"/>
			</svg>
		</div>

		<h2 class="adace-detector-title g1-beta g1-beta-1st"><?php echo esc_html( $title );?></h2>

		<div class="adace-detector-content"><?php echo wp_kses_post( $description );?></div>

		<p class="adace-detector-buttons">
			<?php if ( '-1' !== $page ) :
				$page_url 		= get_the_permalink( $page );
			?>
				<a href="<?php echo esc_url( $page_url );?>" class="adace-detector-button-disable g1-button-solid g1-button g1-button-m"><?php echo esc_html__( 'How to disable?', 'adace' );?></a>
			<?php endif;?>
			<a  class="adace-detector-button-refresh g1-button-simple g1-button g1-button-m"><?php echo esc_html__( 'Refresh', 'adace' );?></a>
		</p>
	</div>

	<div class="adace-popup-background">
	</div>
</div>
