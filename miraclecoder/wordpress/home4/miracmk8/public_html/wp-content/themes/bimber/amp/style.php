<?php
require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/front/lib/class-bimber-color.php';

$bimber_skin = bimber_get_theme_option( 'global', 'skin' );
$bimber_stack = bimber_get_current_stack();
$bimber_direction = is_rtl() ? '-rtl' : '';
?>


<?php
$bimber_stack 	 = bimber_get_current_stack();
$icon_style		 = bimber_get_theme_option( 'global', 'icon_style' );
if ( 'default' === $icon_style && 'bunchy' === $bimber_stack ) {
	$icon_style = 'line';
} elseif ( 'default' === $icon_style ) {
	$icon_style = 'solid';
}
if ( 'line' === $icon_style ) {
	$bimber_font_dir_uri = trailingslashit( get_template_directory_uri() ) . 'css/' . bimber_get_css_theme_ver_directory() . '/bunchy/fonts/';
} else {
	$bimber_font_dir_uri = trailingslashit( get_template_directory_uri() ) . 'css/' . bimber_get_css_theme_ver_directory() . '/bimber/fonts/';
}
?>
@font-face {
	font-family: "bimber";
	src:url("<?php echo $bimber_font_dir_uri; ?>bimber.eot");
	src:url("<?php echo $bimber_font_dir_uri; ?>bimber.eot?#iefix") format("embedded-opentype"),
	url("<?php echo $bimber_font_dir_uri; ?>bimber.woff") format("woff"),
	url("<?php echo $bimber_font_dir_uri; ?>bimber.ttf") format("truetype"),
	url("<?php echo $bimber_font_dir_uri; ?>bimber.svg#bimber") format("svg");
	font-weight: normal;
	font-style: normal;
}
<?php
// @todo Maybe we shouldn't include it like this:
include trailingslashit( get_template_directory() ) . '/css/' . bimber_get_css_theme_ver_directory() . '/styles' . '/' . $bimber_stack . '/amp-'. $bimber_skin . $bimber_direction . '.min.css';
?>


.amp-wp-iframe-placeholder {
	background-image: url( <?php echo esc_url( $this->get( 'placeholder_image_url' ) ); ?> );
}


<?php
$bimber_cs_1_accent1                = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_1_accent1' ) );
$bimber_cs_2_text1                  = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_2_text1' ) );
$bimber_cs_2_background             = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_2_background_color' ) );
?>
a {color:#<?php echo sanitize_hex_color_no_hash( $bimber_cs_1_accent1->get_hex() ); ?>;}

.g1-nav-single-prev > a > span:before,
.g1-nav-single-next > a > span:after,
.mashsb-count {
color:#<?php echo sanitize_hex_color_no_hash( $bimber_cs_1_accent1->get_hex() ); ?>;
}



.g1-button-solid,
.g1-arrow-solid {
border-color:#<?php echo sanitize_hex_color_no_hash( $bimber_cs_2_background->get_hex() ); ?>;
background-color:#<?php echo sanitize_hex_color_no_hash( $bimber_cs_2_background->get_hex() ); ?>;
color:#<?php echo sanitize_hex_color_no_hash( $bimber_cs_2_text1->get_hex() ); ?>;
}

<?php
$bimber_mobile_logo = bimber_get_small_logo();
$bimber_mobile_logo_margin_top    	= (int) bimber_get_theme_option( 'header', 'mobile_logo_margin_top' );
$bimber_mobile_logo_margin_bottom 	= (int) bimber_get_theme_option( 'header', 'mobile_logo_margin_bottom' );
?>
.g1-id {
margin: <?php echo (int) $bimber_mobile_logo_margin_top; ?>px auto <?php echo (int) $bimber_mobile_logo_margin_bottom; ?>px;
}

.g1-logo {
max-width: <?php echo (int) $bimber_mobile_logo['width']; ?>px;
}

<?php
// Get the HB row with logo in it and use its color scheme.
$bimber_row = bimber_hb_get_row_with_mobile_logo();
$bimber_row = $bimber_row ? $bimber_row : 'a';

$bimber_header_text       = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $bimber_row . '_text_color' ) );
$bimber_header_accent     = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $bimber_row . '_accent_color' ) );
$bimber_header_bg1        = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $bimber_row . '_background_color' ) );
$bimber_header_bg2 = bimber_get_theme_option( 'header', 'builder_' . $bimber_row . '_gradient_color' );
$bimber_header_bg2 = strlen( $bimber_header_bg2 ) ? new Bimber_Color( $bimber_header_bg2 ) : $bimber_header_bg1;

?>
.g1-header > .g1-row-background {
	background-color:#<?php echo sanitize_hex_color_no_hash( $bimber_header_bg1->get_hex() )?>;
<?php if ( $bimber_header_bg1->get_hex() !== $bimber_header_bg2->get_hex() ) : ?>
	background-image: -webkit-linear-gradient(to right, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg1->get_hex() ); ?>, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg2->get_hex() ); ?>);
	background-image:    -moz-linear-gradient(to right, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg1->get_hex() ); ?>, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg2->get_hex() ); ?>);
	background-image:      -o-linear-gradient(to right, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg1->get_hex() ); ?>, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg2->get_hex() ); ?>);
	background-image:         linear-gradient(to right, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg1->get_hex() ); ?>, #<?php echo sanitize_hex_color_no_hash( $bimber_header_bg2->get_hex() ); ?>);
<?php endif; ?>
}
.g1-header .g1-hamburger {
	color: #<?php echo sanitize_hex_color_no_hash( $bimber_header_text->get_hex() ); ?>;
}

<?php
$bimber_bg1_color = new Bimber_Color( bimber_get_theme_option( 'footer', 'cs_1_background_color' ) );
?>

.g1-footer > .g1-row-background {
background-color: #<?php echo sanitize_hex_color_no_hash( $bimber_bg1_color->get_hex() ); ?>;
}


<?php
// Archives colors.
$bimber_terms = get_terms( array(
	'taxonomy'   => 'category',
	'hide_empty' => false,
	'meta_query' => array(
		array(
			'key'       => 'bimber_label_color',
			'compare'   => 'EXISTS'
		),
	),
) );
?>
<?php foreach ( $bimber_terms as $bimber_term ) : ?>
	<?php
	$bimber_txt_color = get_term_meta( $bimber_term->term_id, 'bimber_label_color', true );
	$bimber_txt_color = strlen( $bimber_txt_color ) ? new Bimber_Color( $bimber_txt_color) : false;
	$bimber_bg_color  = get_term_meta( $bimber_term->term_id, 'bimber_label_background_color', true );
	$bimber_bg_color  = strlen( $bimber_bg_color ) ? new Bimber_Color( $bimber_bg_color) : false;
	?>
	.entry-categories .entry-category-item-<?php echo esc_attr( $bimber_term->term_id );?>{
	<?php if ( $bimber_txt_color ) : ?>
		color: <?php echo sanitize_hex_color( $bimber_txt_color->format_hex() );?>;
	<?php endif; ?>
	<?php if ( $bimber_bg_color ) : ?>
		background-color: <?php echo sanitize_hex_color( $bimber_bg_color->format_hex() );?>;
	<?php endif; ?>
	}
<?php endforeach; ?>