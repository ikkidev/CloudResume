<?php
/**
 * The Template for displaying ad inside collection (grid).
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
set_query_var( 'plugin_required_notice_slot_id', esc_html__( 'Inside grid collection', 'bimber' ) );
global $bimber_ad_offset;

?>
<li class="g1-collection-item g1-collection-item-1of3 g1-injected-unit">
	<?php if ( bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) ) : ?>

		<?php if ( quads_has_ad( 'bimber_inside_grid' ) ) : ?>

			<div class="g1-advertisement g1-advertisement-inside-grid">
				<div class="g1-advertisement-inner">

					<?php quads_ad( array( 'location' => 'bimber_inside_grid' ) ); ?>

				</div>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/ads/notice-not-allowed' ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) : ?>

		<?php if (  adace_is_ad_slot( 'bimber_inside_grid' ) ) : ?>

			<div class="g1-advertisement g1-advertisement-inside-grid">
				<div class="g1-advertisement-inner">

					<?php
					$ad = bimber_sanitize_ad( adace_get_ad_slot( 'bimber_inside_grid', $bimber_ad_offset + 1 ) );
					if ( empty( $ad ) ) {
						global $bimber_skip_adace_slot_inside_collection;
						$bimber_skip_adace_slot_inside_collection = true;
					}
					echo $ad;
					?>

				</div>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/ads/notice-not-allowed' ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) && ! bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ) : ?>

		<?php get_template_part( 'template-parts/ads/notice-plugin-required' ); ?>

	<?php endif; ?>
</li>
