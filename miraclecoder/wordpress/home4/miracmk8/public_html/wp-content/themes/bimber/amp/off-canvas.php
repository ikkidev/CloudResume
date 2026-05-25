<?php
/**
 * The template part for displaying the off-canvas area of AMP.
 *
 * @package Bimber_Theme 4.10
 */
?>
<amp-sidebar id='g1-canvas' layout='nodisplay'>

	<!-- BEGIN .g1-primary-nav -->
	<?php
	if ( has_nav_menu( 'bimber_primary_nav' ) ) :
		wp_nav_menu( array(
			'theme_location'  => 'bimber_primary_nav',
			'container'       => 'nav',
			'container_class' => 'g1-primary-nav',
			'container_id'    => 'g1-canvas-primary-nav',
			'menu_class'      => 'g1-primary-nav-menu',
			'menu_id'         => 'g1-canvas-primary-nav-menu',
			'depth'           => 0,
		) );
	endif;
	?>
	<!-- END .g1-primary-nav -->

	<!-- BEGIN .g1-secondary-nav -->
	<?php
	if ( has_nav_menu( 'bimber_secondary_nav' ) ) :
		wp_nav_menu( array(
			'theme_location'  => 'bimber_secondary_nav',
			'container'       => 'nav',
			'container_class' => 'g1-secondary-nav',
			'container_id'    => 'g1-canvas-secondary-nav',
			'menu_class'      => 'g1-secondary-nav-menu',
			'menu_id'         => 'g1-canvas-secondary-nav-menu',
			'depth'           => 0,
		) );
	endif;
	?>
	<!-- END .g1-secondary-nav -->

</amp-sidebar>