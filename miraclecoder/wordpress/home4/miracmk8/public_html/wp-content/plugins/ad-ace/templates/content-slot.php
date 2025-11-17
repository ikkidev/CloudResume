<?php
/**
 * Adace Slot
 *
 * @package adace
 * @subpackage Frontend Slot
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$slot = adace_slot_get_query();
if ( empty( $slot['ad_id_mobile'] ) ) {
	$slot['ad_id_mobile'] = '-3';
}
if ( empty( $slot['ad_id_tablet'] ) ) {
	$slot['ad_id_tablet'] = '-3';
}
$ad = adace_ad_get_query();
// Output styles.
$slot_styles_array = array();
if ( 0 !== $slot['min_width'] ) {
	$slot_styles_array['min_width'] = 'min-width:' . $slot['min_width'] . 'px;';
}
if ( 0 !== $slot['max_width'] ) {
	$slot_styles_array['max_width'] = 'max-width:' . $slot['max_width'] . 'px;';
}

$alignement_class = '';
if ( ! empty( $slot['alignment'] ) && 'none' !== $slot['alignment'] ) {
	$slot_styles_array['alignment'] = 'text-align:' . $slot['alignment'] . ';';
	$alignement_class = 'adace-align-' . $slot['alignment'];
	if ( isset( $slot['wrap'] ) && $slot['wrap'] ) {
		$alignement_class .= ' -wrap';
	}
}

if ( $slot['margin'] > 0 ) {
	$slot_styles_array['margin'] = 'margin:' . $slot['margin'] . 'px;';
}



$slot_styles_array = apply_filters( 'adace_slot_styles', $slot_styles_array, $slot );
if ( ! empty( $slot_styles_array ) ) {
	$slot_styles = 'style="' . esc_attr( join( '', $slot_styles_array ) ) . '"';
} else {
	$slot_styles = '';
}

$adace_disclaimer = get_option( 'adace_general_disclaimer', '' );
?>
<?php
$adace_slot_base_class = array(
	'adace-slot-wrapper',
	$slot['slot_id'],
	$alignement_class,
);

$adace_slot_extra_class = array(
	'adace-slot-wrapper-main'
);

if ( '-3' !== $slot['ad_id_mobile'] ) {
	$adace_slot_extra_class[] = 'adace-hide-on-mobile';
}

if ( '-3' !== $slot['ad_id_tablet'] ) {
	$adace_slot_extra_class[] = 'adace-hide-on-tablet';
}

$adace_slot_class = array_merge( $adace_slot_base_class, $adace_slot_extra_class );
?>
<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $adace_slot_class ) ); ?>" <?php echo( $slot_styles ); ?>>
	<div class="adace-disclaimer">
		<?php echo $adace_disclaimer; ?>
	</div>
	<div class="adace-slot">
		<?php
			do_action( 'adace_standard_slot_start' );

			if ( 'adsense' === $ad['type'] ) {
				adace_render_adsense_ad();
			} else {
				adace_render_custom_ad();
			}
			do_action( 'adace_standard_slot_end' );
		?>
	</div>
</div>

<?php
	if ( adace_is_amp() ) {
		return;
	}
?>

<?php if ( '-3' !== $slot['ad_id_mobile'] ) : ?>
	<?php
		/**
		 * Optional phone specific ad.
		 */

		adace_query_ad( $slot['ad_id_mobile'] );

		$adace_slot_class = array_merge( $adace_slot_base_class, array(
			'adace-hide-on-tablet',
			'adace-hide-on-desktop',
		) );
	?>
	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $adace_slot_class ) ); ?>" <?php echo( $slot_styles ); ?>>
		<div class="adace-disclaimer">
			<?php echo $adace_disclaimer; ?>
		</div>
		<div class="adace-slot">
			<?php
				do_action( 'adace_standard_slot_start' );

				if ( 'adsense' === $ad['type'] ) {
					adace_render_adsense_ad();
				} else {
					adace_render_custom_ad();
				}

				do_action( 'adace_standard_slot_end' );
			?>
		</div>
	</div>
<?php endif; ?>

<?php if ( '-3' !== $slot['ad_id_tablet'] ) : ?>
	<?php
		/**
		 * Optional tablet specific ad.
		 */

		adace_query_ad( $slot['ad_id_tablet'] );

		$adace_slot_class = array_merge( $adace_slot_base_class, array(
			'adace-hide-on-mobile',
			'adace-hide-on-desktop',
		) );
	?>
	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $adace_slot_class )); ?>" <?php echo( $slot_styles ); ?>>
		<div class="adace-disclaimer">
			<?php echo $adace_disclaimer; ?>
		</div>
		<div class="adace-slot">
			<?php
				do_action( 'adace_standard_slot_start' );

				if ( 'adsense' === $ad['type'] ) {
					adace_render_adsense_ad();
				} else {
					adace_render_custom_ad();
				}

				do_action( 'adace_standard_slot_end' );
			?>
		</div>
	</div>
<?php endif;
