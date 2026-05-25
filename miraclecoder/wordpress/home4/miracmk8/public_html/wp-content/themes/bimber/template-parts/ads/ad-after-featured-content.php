<?php
/**
 * The Template for displaying ad after the featured content.
 *
 * @package Bimber_Theme 5.0
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
set_query_var( 'plugin_required_notice_slot_id', esc_html__( 'After featured content', 'bimber' ) );
$bimber_class = array(
	'g1-advertisement',
	'g1-advertisement-after-featured-content',
	'g1-row-layout-page',
);
?>

<?php if ( bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) && ( quads_has_ad( 'bimber_after_featured_content' ) ) ) : ?>
	<div <?php bimber_render_row_class( $bimber_class ); ?>>
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php echo bimber_sanitize_ad( quads_ad( array( 'location' => 'bimber_after_featured_content', 'echo' => false ) ) ); ?>
			</div>
		</div>
	</div><!-- .g1-row -->
<?php endif;

if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) && ( adace_is_ad_slot( 'bimber_after_featured_content' ) ) ) : ?>
	<div <?php bimber_render_row_class( $bimber_class ); ?>>
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php echo bimber_sanitize_ad( adace_get_ad_slot( 'bimber_after_featured_content' ) ); ?>
			</div>
		</div>
	</div><!-- .g1-row -->
<?php endif;
