<?php
/**
 * Header Builder template
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<!-- BEGIN .g1-primary-nav -->
<?php
	if ( has_nav_menu( 'bimber_primary_nav' ) ) :
		wp_nav_menu( array(
			'theme_location'  => 'bimber_primary_nav',
			'container'       => 'nav',
			'container_class' => 'g1-primary-nav',
			'container_id'    => 'g1-primary-nav',
			'menu_class'      => 'g1-primary-nav-menu g1-menu-h',
			'menu_id'         => 'g1-primary-nav-menu',
			'depth'           => 0,
			'walker' 		  => new Bimber_Walker_Nav_Menu(),
		) );
	endif;
?>
<!-- END .g1-primary-nav -->
