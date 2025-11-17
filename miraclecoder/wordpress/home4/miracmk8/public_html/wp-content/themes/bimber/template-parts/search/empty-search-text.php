<?php
/**
 * The Template Part for displaying the "No results" message, when there are no posts in the search.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="g1-row g1-row-layout-page g1-row-padding-m">
	<div class="g1-row-inner">
		<div class="g1-column">

			<?php if ( apply_filters( 'bimber_show_search_no_results', true ) ) : ?>
			<p class="no-results">
				<?php esc_html_e( 'Please enter some text to search.', 'bimber' ); ?>
			</p>
			<?php endif; ?>

		</div>
	</div>
	<div class="g1-row-background">
	</div>
</div>
