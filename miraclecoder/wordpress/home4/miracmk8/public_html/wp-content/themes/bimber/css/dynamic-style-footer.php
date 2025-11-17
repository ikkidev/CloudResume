<?php
/**
 * Footer styles
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

$bimber_cs_1_background = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_background_color' ) );
$bimber_cs_1_gradient = bimber_get_theme_option( 'footer', 'cs_1_gradient_color' );
$bimber_cs_1_gradient = strlen( $bimber_cs_1_gradient ) ? new Bimber_Color( $bimber_cs_1_gradient ) : $bimber_cs_1_background;
$bimber_cs_1_bg_image		 	  = bimber_get_theme_option( 'footer', 'cs_1_background_image' );
if ( $bimber_cs_1_bg_image ) {
	$bimber_cs_1_bg_size 		= bimber_get_theme_option( 'footer', 'cs_1_background_size' );
	$bimber_cs_1_bg_repeat 		= bimber_get_theme_option( 'footer', 'cs_1_background_repeat' );
	$bimber_cs_1_bg_position 	= bimber_get_theme_option( 'footer', 'cs_1_background_position' );
}
$bimber_cs_1_bg_opacity 	= (int) bimber_get_theme_option( 'footer', 'cs_1_background_opacity' );

$bimber_cs_1_background_variations = bimber_get_color_variations( $bimber_cs_1_background );
$bimber_cs_1_background_5          = new Bimber_Color( $bimber_cs_1_background_variations['tone_5_90_hex'] );
$bimber_cs_1_background_10         = new Bimber_Color( $bimber_cs_1_background_variations['tone_20_20_hex'] );

$bimber_cs_1_text1   = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_text1' ) );
$bimber_cs_1_text2   = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_text2' ) );
$bimber_cs_1_text3   = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_text3' ) );
$bimber_cs_1_accent1 = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_accent1' ) );

$bimber_cs_2_background = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_2_background_color' ) );
$bimber_cs_2_text1      = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_2_text1' ) );

$bimber_instagram_above_footer_background_color  = new Bimber_Color( bimber_get_theme_option( 'instagram', 'above_footer_background_color' ) );
$bimber_patreon_above_footer_background_color    = new Bimber_Color( bimber_get_theme_option( 'patreon', 'above_footer_background_color' ) );
$bimber_newsletter_above_footer_background_color = new Bimber_Color( bimber_get_theme_option( 'newsletter', 'before_footer_background_color' ) );
$bimber_social_above_footer_background_color     = new Bimber_Color( bimber_get_theme_option( 'social', 'above_footer_background_color' ) );
$bimber_links_above_footer_background_color      = new Bimber_Color( bimber_get_theme_option( 'links', 'above_footer_background_color' ) );

$bimber_skinmode_itxt_color     = new Bimber_Color( bimber_get_theme_option( 'footer', 'skinmode_itxt_color' ) );
$bimber_skinmode_rtxt_color     = new Bimber_Color( bimber_get_theme_option( 'footer', 'skinmode_rtxt_color' ) );
$bimber_skinmode_mtxt_color     = new Bimber_Color( bimber_get_theme_option( 'footer', 'skinmode_mtxt_color' ) );
$bimber_skinmode_atxt_color     = new Bimber_Color( bimber_get_theme_option( 'footer', 'skinmode_atxt_color' ) );
$bimber_skinmode_bg_color       = new Bimber_Color( bimber_get_theme_option( 'footer', 'skinmode_bg_color' ) );
?>

:root {
--g1-footer-itxt-color:<?php echo sanitize_hex_color( $bimber_cs_1_text1->format_hex() ); ?>;
--g1-footer-rtxt-color:<?php echo sanitize_hex_color( $bimber_cs_1_text2->format_hex() ); ?>;
--g1-footer-mtxt-color:<?php echo sanitize_hex_color( $bimber_cs_1_text3->format_hex() ); ?>;
--g1-footer-atxt-color:<?php echo sanitize_hex_color( $bimber_cs_1_accent1->format_hex() ); ?>;
--g1-footer-bg-color:<?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>;
}


/* Prefooter Theme Area */
.g1-prefooter > .g1-row-background,
.g1-prefooter .g1-current-background {
	background-color:<?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>;
	background-color:var(--g1-footer-bg-color);
}
<?php if ( $bimber_cs_1_background->get_hex() !== $bimber_cs_1_gradient->get_hex() ) : ?>
	.g1-prefooter > .g1-row-background {
		background-image: -webkit-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
		background-image:    -moz-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
		background-image:      -o-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
		background-image:         linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
	}
<?php endif; ?>
<?php if ( $bimber_cs_1_bg_image ) : ?>
	.g1-prefooter > .g1-row-background > .g1-row-background-media {
		background-image: 	url(<?php echo esc_url( $bimber_cs_1_bg_image ); ?>);
		background-size: 	<?php echo esc_attr( $bimber_cs_1_bg_size ); ?>;
		background-repeat: 	<?php echo esc_attr( $bimber_cs_1_bg_repeat ); ?>;
		background-position: <?php echo esc_attr( $bimber_cs_1_bg_position ); ?>;
		opacity: <?php echo esc_attr( 0.01 * $bimber_cs_1_bg_opacity ); ?>;
	}
<?php endif; ?>

.g1-prefooter h1,
.g1-prefooter h2,
.g1-prefooter h3,
.g1-prefooter h4,
.g1-prefooter h5,
.g1-prefooter h6,
.g1-prefooter .g1-mega,
.g1-prefooter .g1-alpha,
.g1-prefooter .g1-beta,
.g1-prefooter .g1-gamma,
.g1-prefooter .g1-delta,
.g1-prefooter .g1-epsilon,
.g1-prefooter .g1-zeta,
.g1-prefooter blockquote,
.g1-prefooter .widget_recent_entries a,
.g1-prefooter .widget_archive a,
.g1-prefooter .widget_categories a,
.g1-prefooter .widget_meta a,
.g1-prefooter .widget_pages a,
.g1-prefooter .widget_recent_comments a,
.g1-prefooter .widget_nav_menu .menu a {
color:<?php echo sanitize_hex_color( $bimber_cs_1_text1->format_hex() ); ?>;
color:var(--g1-footer-itxt-color);
}

.g1-prefooter {
color:<?php echo sanitize_hex_color( $bimber_cs_1_text2->format_hex() ); ?>;
color:var(--g1-footer-rtxt-color);
}

.g1-prefooter .entry-meta {
color:<?php echo sanitize_hex_color( $bimber_cs_1_text3->format_hex() ); ?>;
color:var(--g1-footer-mtxt-color);
}

.g1-prefooter input,
.g1-prefooter select,
.g1-prefooter textarea {
border-color: <?php echo sanitize_hex_color( $bimber_cs_1_background_10->format_hex() ); ?>;
}

.g1-prefooter input[type="submit"],
.g1-prefooter input[type="reset"],
.g1-prefooter input[type="button"],
.g1-prefooter button,
.g1-prefooter .g1-button-solid,
.g1-prefooter .g1-button-solid:hover,
.g1-prefooter .g1-box-icon {
border-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_cs_2_text1->format_hex() ); ?>;
}

.g1-prefooter .g1-button-simple {
	border-color:<?php echo sanitize_hex_color( $bimber_cs_1_text1->format_hex() ); ?>;
	border-color:var(--g1-footer-itxt-color);
	color:<?php echo sanitize_hex_color( $bimber_cs_1_text1->format_hex() ); ?>;
	color:var(--g1-footer-itxt-color);
}


/* Footer Theme Area */
.g1-footer > .g1-row-background,
.g1-footer .g1-current-background {
background-color:<?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>;
background-color:var(--g1-footer-bg-color);
}

<?php if ( $bimber_cs_1_background->get_hex() !== $bimber_cs_1_gradient->get_hex() ) : ?>
	.g1-footer > .g1-row-background {
	background-image: -webkit-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
	background-image:    -moz-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
	background-image:      -o-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
	background-image:         linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_cs_1_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_1_gradient->format_hex() ); ?>);
	}
<?php endif; ?>




.g1-footer {
color:<?php echo sanitize_hex_color( $bimber_cs_1_text2->format_hex() ); ?>;
color:var(--g1-footer-rtxt-color);
}

.g1-footer-text {
color: <?php echo sanitize_hex_color( $bimber_cs_1_text3->format_hex() ); ?>;
color:var(--g1-footer-mtxt-color);
}

.g1-footer a:hover,
.g1-footer-nav a:hover {
color:<?php echo sanitize_hex_color( $bimber_cs_1_accent1->format_hex() ); ?>;
color:var(--g1-footer-atxt-color);
}

.g1-footer .current-menu-item > a {
color:<?php echo sanitize_hex_color( $bimber_cs_1_text1->format_hex() ); ?>;
color:var(--g1-footer-itxt-color);
}


<?php
if ( bimber_get_theme_option( 'podcast', 'above_footer_background_color' ) ) : ?>
.g1-row-podcast-before-footer .g1-row-background{
background-color: <?php echo sanitize_hex_color( $bimber_podcast_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

<?php
if ( bimber_get_theme_option( 'instagram', 'above_footer_background_color' ) ) : ?>
.g1-row-instagram-before-footer .g1-instagram-feed-overlay{
background-color: <?php echo sanitize_hex_color( $bimber_instagram_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

<?php
if ( bimber_get_theme_option( 'social', 'above_footer_background_color' ) ) : ?>
.g1-row.g1-socials-section{
background-color: <?php echo sanitize_hex_color( $bimber_social_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

<?php
if ( bimber_get_theme_option( 'patreon', 'above_footer_background_color' ) ) : ?>
.g1-row-patreon-before-footer .g1-row-background{
background-color: <?php echo sanitize_hex_color( $bimber_patreon_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

<?php
if ( bimber_get_theme_option( 'newsletter', 'before_footer_background_color' ) ) :
?>
.g1-newsletter-as-row>.g1-row-background{
background-color: <?php echo sanitize_hex_color( $bimber_newsletter_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

<?php
if ( bimber_get_theme_option( 'links', 'above_footer_background_color' ) ) : ?>
.g1-links-above_footer .g1-row-background{
background-color: <?php echo sanitize_hex_color( $bimber_links_above_footer_background_color->format_hex() ); ?>;
}
<?php endif; ?>

/*customizer_preview_footer*/
/*customizer_preview_footer_end*/


.g1-skinmode {
--g1-footer-itxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_itxt_color->format_hex() ); ?>;
--g1-footer-rtxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_rtxt_color->format_hex() ); ?>;
--g1-footer-mtxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_mtxt_color->format_hex() ); ?>;
--g1-footer-atxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_atxt_color->format_hex() ); ?>;
--g1-footer-bg-color:<?php echo sanitize_hex_color( $bimber_skinmode_bg_color->format_hex() ); ?>;
}
