<?php
/**
 * The Template for displaying ad on the left side of a stream template.
 *
 * @package Bimber_Theme 5.0
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
set_query_var( 'plugin_required_notice_slot_id', esc_html__( 'On the left side of stream collection', 'bimber' ) );
?>

<?php if ( bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) ) : ?>

	<?php if ( quads_has_ad( 'bimber_left_stream' ) ) : ?>

		<div class="g1-advertisement g1-advertisement-left-stream">

			<?php quads_ad( array( 'location' => 'bimber_left_stream' ) ); ?>

		</div>

	<?php endif; ?>

<?php endif; ?>

<?php if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) : ?>

	<?php if (  adace_is_ad_slot( 'bimber_left_stream' ) ) : ?>

		<div class="g1-advertisement g1-advertisement-left-stream">

			<?php echo bimber_sanitize_ad( adace_get_ad_slot( 'bimber_left_stream' ) ); ?>

		</div>

	<?php else : ?>

		<?php get_template_part( 'template-parts/ads/notice-not-allowed' ); ?>

	<?php endif; ?>

<?php endif; ?>

<?php if ( ! bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) && ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) : ?>

	<?php get_template_part( 'template-parts/ads/notice-plugin-required' ); ?>

<?php endif; ?>
