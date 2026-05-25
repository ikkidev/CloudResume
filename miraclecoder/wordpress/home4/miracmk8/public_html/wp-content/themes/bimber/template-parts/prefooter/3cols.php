<?php
/**
 * The Template for displaying prefooter
 *
 * @package Bimber_Theme
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class=" g1-prefooter g1-prefooter-3cols g1-row g1-row-layout-page">
	<div class="g1-row-inner">

		<div class="g1-column g1-column-1of3">
			<?php
			if ( is_active_sidebar( 'footer-1' ) ) {
				dynamic_sidebar( 'footer-1' );
			}
			?>
		</div>

		<div class="g1-column g1-column-1of3">
			<?php
			if ( is_active_sidebar( 'footer-2' ) ) {
				dynamic_sidebar( 'footer-2' );
			}
			?>
		</div>

		<div class="g1-column g1-column-1of3">
			<?php
			if ( is_active_sidebar( 'footer-3' ) ) {
				dynamic_sidebar( 'footer-3' );
			}
			?>
		</div>

	</div>
	<div class="g1-row-background">
		<div class="g1-row-background-media">
		</div>
	</div>
</div><!-- .g1-prefooter -->
