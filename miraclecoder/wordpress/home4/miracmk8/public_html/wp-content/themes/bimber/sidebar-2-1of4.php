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
<div id="tertiary" <?php bimber_render_sidebar_class( array('g1-column', 'g1-column-1of4' ) ); ?>>
	<?php
	$bimber_default_sidebar = apply_filters( 'bimber_default_sidebar_2nd', 'secondary' );
	$bimber_sidebar         = '';

	if ( is_home() ) {
		$bimber_sidebar = 'home_2nd';
	}

	if ( is_archive() ) {
		$bimber_sidebar = 'post_archive_2nd';
	}

	$bimber_sidebar = apply_filters( 'bimber_sidebar_2nd', $bimber_sidebar );

	if ( empty( $bimber_sidebar ) || ! is_active_sidebar( $bimber_sidebar ) ) {
		$bimber_sidebar = $bimber_default_sidebar;
	}

	do_action( 'bimber_sidebar_start' );
	dynamic_sidebar( $bimber_sidebar );
	do_action( 'bimber_sidebar_end' );
	?>
</div><!-- #secondary -->
