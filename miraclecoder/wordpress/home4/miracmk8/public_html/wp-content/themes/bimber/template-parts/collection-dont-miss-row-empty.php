<?php
/**
 * The Template for displaying info about empty post list based on views counter.
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

<?php if ( current_user_can( 'edit_plugins' ) ) : ?>
	<div class="g1-dont-miss-row">
		<aside class="g1-dont-miss">
			<?php bimber_render_section_title( __( 'Don\'t Miss', 'bimber' ), false, array( 'g1-collection-title' ) ); ?>

			<div class="g1-message g1-message-warning">
				<div class="g1-message-inner">
					<p><?php esc_html_e( 'Please check if the Wordpress Popular Posts plugin is activated. The theme uses it to calculate this list entries.', 'bimber' ); ?></p>
				</div>
			</div>
		</aside>
	</div>
<?php endif;
