<?php
/**
 * The template for displaying homepage static part, with sidebar.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="g1-row g1-row-layout-page">
	<div class="g1-row-inner">

		<div class="g1-column g1-column-2of3">

			<?php the_content(); ?>

		</div>

		<div class="g1-column g1-column-1of3">
			<?php
			$bimber_sidebar = apply_filters( 'bimber_home_static_sidebar', 'bimber_vc_home_static' );

			dynamic_sidebar( $bimber_sidebar );
			?>
		</div>

	</div>
	<div class="g1-row-background"></div>
</div>
