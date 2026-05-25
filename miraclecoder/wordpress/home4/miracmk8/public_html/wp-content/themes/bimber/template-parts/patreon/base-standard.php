<?php
/**
 * The Template for displaying patreon.
 *
 * @package Bimber_Theme
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$patreon_label = get_option( 'adace_patreon_label', adace_options_get_defaults( 'adace_patreon_label' ) );
$patreon_title = get_option( 'adace_patreon_title', adace_options_get_defaults( 'adace_patreon_title' ) );
$patreon_link  = get_option( 'adace_patreon_link', adace_options_get_defaults( 'adace_patreon_link' ) );

$color_class = 'g1-light';
global $bimber_patreon_section_positon;
if ( 'above_footer' === $bimber_patreon_section_positon ) {
	$color_scheme = bimber_get_theme_option( 'patreon', $bimber_patreon_section_positon . '_color_scheme' );
	$color_class = 'g1-' . $color_scheme;
}

?>
	<div class="g1-stripe-inner <?php echo( $color_class ); ?> ">
		<span class="g1-stripe-icon"></span>

		<div class="g1-stripe-body">
			<div class="g1-stripe-content">
				<h2 class="g1-zeta g1-zeta-2nd g1-stripe-label"><?php echo( wp_kses_post( html_entity_decode( $patreon_label ) ) ); ?></h2>

				<h3 class="entry-title g1-stripe-title">
					<a href="<?php echo( esc_url( $patreon_link ) ); ?>" rel="nofollow" target="_blank">
						<?php echo( wp_kses_post( html_entity_decode( $patreon_title ) ) ); ?>
					</a>
				</h3>
			</div>

			<div class="g1-stripe-actions">
				<a class="g1-button g1-button-m g1-button-solid" href="<?php echo( esc_url( $patreon_link ) ); ?>" rel="nofollow" target="_blank">
					<?php esc_html_e( 'Become a Patron', 'bimber' ); ?>
				</a>
			</div>
		</div>
	</div>
<?php
