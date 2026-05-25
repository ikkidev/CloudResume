<?php
/**
 * The Template Part for displaying the sharebar.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php if ( apply_filters( 'bimber_show_sharebar', false ) ) : ?>
	<aside class="g1-row g1-sharebar g1-sharebar-off">
		<div class="g1-row-inner">
			<div class="g1-column g1-sharebar-inner">
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</aside>
<?php endif;
