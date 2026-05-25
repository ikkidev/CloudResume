<?php
/**
 * The Template for displaying ad after post content.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
set_query_var( 'plugin_required_notice_slot_id', esc_html__( 'Inside header', 'bimber' ) );
?>

<?php

if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) && (  adace_is_ad_slot( 'bimber_inside_header' ) ) ) : ?>
	<div class="g1-row g1-row-layout-page g1-advertisement g1-advertisement-inside-header">
		<div class="g1-row-inner">
			<div class="g1-column">

				<?php echo bimber_sanitize_ad( adace_get_ad_slot( 'bimber_inside_header' ) ); ?>

			</div>
		</div>
		<div class="g1-row-background"></div>
	</div>
<?php endif;
