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

<div class="g1-box-icon g1-patreon-logo">
</div>

<h2 class="g1-zeta g1-zeta-2nd g1-patreon-label"><?php echo( wp_kses_post( html_entity_decode( $adace_patreon_label ) ) ); ?></h2>
<h3 class="entry-title g1-patreon-title"><a href="<?php echo( esc_url( $adace_patreon_link ) ); ?>" rel="nofollow" target="_blank"><?php echo( wp_kses_post( html_entity_decode( $adace_patreon_title ) ) ); ?></a></h3>

<p class="g1-patreon-more">
	<a class="g1-button g1-button-solid" href="<?php echo( esc_url( $adace_patreon_link ) ); ?>" rel="nofollow" target="_blank"><?php esc_html_e( 'Become a Patron', 'adace' ); ?></a>
</p>
