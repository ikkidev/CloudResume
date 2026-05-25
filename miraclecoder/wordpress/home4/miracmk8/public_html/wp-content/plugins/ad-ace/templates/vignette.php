<?php
/**
 * Adace Vignette Ad.
 *
 * @package adace
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$slot_options = get_option( 'adace_slot_' . adace_get_vignette_slot_id() . '_options' );

$adace_close_label  = ! empty( $slot_options['close_label'] ) ? $slot_options['close_label'] : _x( 'Close', 'Full Screen Vignette Close Button Label', 'adace' );
$adace_delay        = isset( $slot_options['delay'] ) ? absint( $slot_options['delay'] ) : 10;
$adace_skips        = isset( $slot_options['skips'] ) ? absint( $slot_options['skips'] ) : 5;
$adace_ads_campaign = $slot_options['ad_id'] > 0 ? 'campaign_' . $slot_options['ad_id'] : 'global';
?>
<style type="text/css">
    .adace-vignette-hidden { display: none; }
</style>

<div class="adace-vignette adace-vignette-hidden" data-skips="<?php echo absint( $adace_skips ); ?>" data-ads-campaign="<?php echo esc_attr( $adace_ads_campaign ); ?>">
	<div class="adace-vignette-header">

        <?php if ( $adace_delay > 0 ): ?>
		<p class="adace-vignette-countdown">
			<?php
			echo wp_kses_post( sprintf( _n( 'Redirecting in %s second', 'Redirecting in %s seconds', $adace_delay, 'adace' ), '<span class="adace-vignette-countdown-number">'. $adace_delay .'</span>' ) );
			?>
		</p>
        <?php endif; ?>

		<a href="#" class="adace-vignette-close adace-vignette-button g1-button g1-button-m g1-button-solid"><?php echo esc_html( $adace_close_label ); ?></a>
	</div>
	<div class="adace-vignette-content">

		<?php echo adace_get_ad_slot( adace_get_vignette_slot_id() ); ?>

	</div>
</div>
<?php wp_enqueue_script( 'adace-slot-vignette' );
