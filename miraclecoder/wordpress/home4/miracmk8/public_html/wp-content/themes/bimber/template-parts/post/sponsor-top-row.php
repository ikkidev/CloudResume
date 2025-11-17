<?php
/**
 * The Template Part for displaying "Sponsored by".
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<?php if ( function_exists( 'bimber_adace_sponsor_before_content' ) ) : ?>
	<div class="g1-row g1-row-layout-page">
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php bimber_adace_sponsor_before_content(); ?>
			</div>
		</div>
	</div>
<?php endif;
