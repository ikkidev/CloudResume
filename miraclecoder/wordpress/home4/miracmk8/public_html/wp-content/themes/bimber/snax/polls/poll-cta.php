<?php
/**
 * Poll template part
 *
 * @package snax 1.11
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<a href="<?php echo esc_url( wp_login_url() );?>" class="g1-button g1-button-l g1-button-wide g1-button-solid snax-login-required"><?php echo esc_html__( 'Please login/register to play', 'snax' );?></a>