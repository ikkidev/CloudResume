<?php
/**
 * The Template for displaying patron.
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
	<div class="g1-row g1-row-layout-page g1-stripe g1-stripe-patreon">
		<div class="g1-row-inner">
			<div class="g1-column">
				<div class="g1-stripe-csstodo">
					<?php get_template_part( 'template-parts/patreon/base-standard' ); ?>

					<div class="g1-stripe-background">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
