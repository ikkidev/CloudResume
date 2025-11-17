<?php
/**
 * The template part for displaying the header of AMP.
 *
 * @package Bimber_Theme 5.0
 */
echo bimber_get_theme_option( 'tracking_code', 'amp' );
?>
<?php
if ( apply_filters( 'bimber_show_ad_before_header_theme_area', true ) ) :
	get_template_part( 'template-parts/ads/ad-before-header-theme-area' );
endif;

$bimber_hamburger_class = array(
	'g1-hamburger',
	'g1-hamburger-show',
	bimber_hb_get_element_class_from_settings( 'mobile_menu', false ),
);

$bimber_hamburger_label_class = array(
	'g1-hamburger-label',
);
if ( 'standard' !== bimber_get_theme_option( 'header_builder', 'element_label_mobile_menu' ) ) {
	$bimber_hamburger_label_class[] = 'g1-hamburger-label-hidden';
}
?>
<div class="g1-header g1-row g1-row-layout-page">
	<div class="g1-row-inner">
		<div class="g1-column">
			<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_hamburger_class ) ); ?>" on="tap:g1-canvas.toggle" role="button" tabindex="10">
				<span class="g1-hamburger-icon"></span>
				<span class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_hamburger_label_class ) ); ?>"><?php esc_html_e( 'Menu', 'bimber' ); ?></span>
			</div>

			<?php get_template_part( 'amp/id' ); ?>

		</div>
	</div>
	<div class="g1-row-background">
	</div>
</div>
<?php get_template_part( 'template-parts/ads/ad-before-content-theme-area' );