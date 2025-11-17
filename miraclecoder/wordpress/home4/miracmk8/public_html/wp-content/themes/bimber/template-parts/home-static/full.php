<?php
/**
 * The template for displaying homepage static part, without sidebar.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="g1-row g1-row-layout-page archive-intro">
	<div class="g1-row-inner">

		<div class="g1-column">

			<?php the_content(); ?>

		</div>

	</div>
	<div class="g1-row-background"></div>
</div>