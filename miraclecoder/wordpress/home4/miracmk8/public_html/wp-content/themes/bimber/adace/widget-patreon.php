<?php
/**
 * Adace Patreon Widget
 *
 * @package adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="g1-box-inner">
	<p class="g1-zeta g1-zeta-2nd"><?php echo( wp_kses_post( html_entity_decode( $adace_patreon_label ) ) ); ?></p>
	<h3 class="g1-beta g1-beta-1st"><?php echo( wp_kses_post( html_entity_decode( $adace_patreon_title ) ) ); ?></h3>
	<p>
		<a class="g1-button g1-button-solid g1-button-m" href="<?php echo( esc_url( $adace_patreon_link ) ); ?>" rel="nofollow" target="_blank"><?php esc_html_e( 'Become a Patron', 'bimber' ); ?></a>
	</p>
</div>
<div class="g1-box-background">
</div>

