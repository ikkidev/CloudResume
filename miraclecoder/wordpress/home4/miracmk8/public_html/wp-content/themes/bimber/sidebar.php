<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div id="secondary" <?php bimber_render_sidebar_class( array('g1-column', 'g1-column-1of3' ) ); ?>>
	<?php
	$bimber_default_sidebar = apply_filters( 'bimber_default_sidebar', 'primary' );
	$bimber_sidebar         = '';

	if ( is_home() ) {
		$bimber_sidebar = 'home';
	}
	if ( is_page() ) {
		$bimber_sidebar = 'page';
	}
	if ( is_single() ) {
		$bimber_sidebar = 'post_single';
	}
	if ( is_archive() ) {
		$bimber_sidebar = 'post_archive';
	}

	$bimber_sidebar = apply_filters( 'bimber_sidebar', $bimber_sidebar );

	if ( empty( $bimber_sidebar ) || ! is_active_sidebar( $bimber_sidebar ) ) {
		$bimber_sidebar = $bimber_default_sidebar;
	}

	do_action( 'bimber_sidebar_start' );
	dynamic_sidebar( $bimber_sidebar );
	do_action( 'bimber_sidebar_end' );
	?>
</div><!-- #secondary -->
