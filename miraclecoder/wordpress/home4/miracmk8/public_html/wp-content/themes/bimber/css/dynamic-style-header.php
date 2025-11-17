<?php
/**
 * Header styles
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

$bimber_filter_hex = array( 'options' => array( 'regexp' => '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/' ) );
$bimber_lazy_loading = function_exists( 'mace_get_lazy_load_images' ) && mace_get_lazy_load_images();

$bimber_logo_margin_top    			= (int) bimber_get_theme_option( 'header', 'logo_margin_top' );
$bimber_logo_margin_bottom 			= (int) bimber_get_theme_option( 'header', 'logo_margin_bottom' );
$bimber_mobile_logo_margin_top    	= (int) bimber_get_theme_option( 'header', 'mobile_logo_margin_top' );
$bimber_mobile_logo_margin_bottom 	= (int) bimber_get_theme_option( 'header', 'mobile_logo_margin_bottom' );

$bimber_quicknav_margin_top    		= (int) bimber_get_theme_option( 'header', 'quicknav_margin_top' );
$bimber_quicknav_margin_bottom 		= (int) bimber_get_theme_option( 'header', 'quicknav_margin_bottom' );
$bimber_primary_nav_margin_top    	= (int) bimber_get_theme_option( 'header', 'primary_nav_margin_top' );
$bimber_primary_nav_margin_bottom 	= (int) bimber_get_theme_option( 'header', 'primary_nav_margin_bottom' );
?>
/*customizer_preview_margins*/
<?php if ( 0 === $bimber_logo_margin_top ) : ?>
	.g1-hb-row-normal .g1-id {
		margin-top: 0;
	}
<?php endif; ?>

<?php if ( 0 === $bimber_logo_margin_bottom ) : ?>
	.g1-hb-row-normal .g1-id {
		margin-bottom: 0;
	}
<?php endif; ?>

<?php if ( 0 === $bimber_mobile_logo_margin_top ) : ?>
	.g1-hb-row-mobile .g1-id {
		margin-top: 0;
	}
<?php endif; ?>

<?php if ( 0 === $bimber_mobile_logo_margin_bottom ) : ?>
	.g1-hb-row-mobile .g1-id {
	margin-bottom: 0;
	}
<?php endif; ?>

@media only screen and ( min-width: 801px ) {
	.g1-hb-row-normal .g1-id {
		margin-top: <?php echo intval( $bimber_logo_margin_top ); ?>px;
		margin-bottom: <?php echo intval( $bimber_logo_margin_bottom ); ?>px;
	}

	.g1-hb-row-normal .g1-quick-nav {
		margin-top: <?php echo intval( $bimber_quicknav_margin_top ); ?>px;
		margin-bottom: <?php echo intval( $bimber_quicknav_margin_bottom ); ?>px;
	}
}


.g1-hb-row-mobile .g1-id {
	margin-top: <?php echo intval( $bimber_mobile_logo_margin_top ); ?>px;
	margin-bottom: <?php echo intval( $bimber_mobile_logo_margin_bottom ); ?>px;
}

.g1-hb-row-normal .g1-primary-nav {
	margin-top: <?php echo intval( $bimber_primary_nav_margin_top ); ?>px;
	margin-bottom: <?php echo intval( $bimber_primary_nav_margin_bottom ); ?>px;
}

/*customizer_preview_margins_end*/

<?php
$bimber_header_text       = new Bimber_Color( bimber_get_theme_option( 'header', 'text_color' ) );
$bimber_header_accent     = new Bimber_Color( bimber_get_theme_option( 'header', 'accent_color' ) );
$bimber_header_bg1        = new Bimber_Color( bimber_get_theme_option( 'header', 'background_color' ) );

$bimber_header_bg2 = bimber_get_theme_option( 'header', 'bg2_color' );
$bimber_header_bg2 = strlen( $bimber_header_bg2 ) ? new Bimber_Color( $bimber_header_bg2 ) : $bimber_header_bg1;

$bimber_header_border = bimber_get_theme_option( 'header', 'border_color' );
$bimber_header_border = strlen( $bimber_header_border ) ? new Bimber_Color( $bimber_header_border ) : '';

$bimber_submenu_background = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_background_color' ) );
$bimber_submenu_text       = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_text_color' ) );
$bimber_submenu_accent     = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_accent_color' ) );

$bimber_skinmode_submenu_background = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_skinmode_background_color' ) );
$bimber_skinmode_submenu_text       = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_skinmode_text_color' ) );
$bimber_skinmode_submenu_accent     = new Bimber_Color( bimber_get_theme_option( 'header', 'submenu_skinmode_accent_color' ) );
?>

<?php
function bimber_hb_generate_row_css( $row ) {
	$text_color       		= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_text_color' ) );
	$accent_color  	   		= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_accent_color' ) );
	$background_color       = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_background_color' ) );
	$gradient_color       	= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_gradient_color' ) );
	if ( ! bimber_get_theme_option( 'header', 'builder_' . $row . '_gradient_color' ) ) {
		$gradient_color = $background_color;
	}

	$border_color	       	= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_border_color' ) );
	$bimber_hb_button_bg    = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_button_background' ) );
	$bimber_hb_button_text  = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_button_text' ) );

	$skinmode_text_color        = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_text_color' ) );
	$skinmode_accent_color      = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_accent_color' ) );
	$skinmode_background_color  = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_background_color' ) );
	$skinmode_gradient_color    = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_gradient_color' ) );
	if ( ! bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_gradient_color' ) ) {
		$skinmode_gradient_color = $skinmode_background_color;
	}

	$skinmode_border_color      = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row . '_skinmode_border_color' ) );
	?>
	:root {
		--g1-hb<?php echo esc_attr( $row ); ?>-itxt-color:<?php echo sanitize_hex_color( $text_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-atxt-color:<?php echo sanitize_hex_color( $accent_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-bg-color:<?php echo sanitize_hex_color( $background_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-gradient-color:<?php echo sanitize_hex_color( $gradient_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-border-color:<?php echo sanitize_hex_color( $border_color->format_hex() ); ?>;

		--g1-hb<?php echo esc_attr( $row ); ?>-2-itxt-color:<?php echo sanitize_hex_color( $bimber_hb_button_text->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-2-bg-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-2-border-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
	}

	.g1-hb-row-<?php echo esc_attr( $row ); ?> .site-description,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-hb-search-form .search-field,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-hb-search-form .search-submit,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .menu-item > a,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-hamburger,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-drop-toggle,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-socials-item-link {
		color:<?php echo sanitize_hex_color( $text_color->format_hex() ); ?>;
		color:var(--g1-hb<?php echo esc_attr( $row ); ?>-itxt-color);
	}

	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-row-background {
		<?php if ( bimber_get_theme_option( 'header', 'builder_' . $row . '_border_color' ) ) : ?>
			border-bottom: 1px solid <?php echo sanitize_hex_color( $border_color->format_hex() ); ?>;
			border-color: <?php echo sanitize_hex_color( $border_color->format_hex() ); ?>;
			border-color:var(--g1-hb<?php echo esc_attr( $row ); ?>-border-color);
		<?php endif; ?>


		background-color: <?php echo sanitize_hex_color( $background_color->format_hex() ); ?>;
		background-color: var(--g1-hb<?php echo esc_attr( $row ); ?>-bg-color);
		background-image: linear-gradient(to right, <?php echo sanitize_hex_color( $background_color->format_hex() ); ?>, <?php echo sanitize_hex_color( $gradient_color->format_hex() ); ?>);
		background-image: linear-gradient(to right, var(--g1-hb<?php echo esc_attr( $row ); ?>-bg-color), var(--g1-hb<?php echo esc_attr( $row ); ?>-gradient-color));
	}

	.g1-hb-row-<?php echo esc_attr( $row ); ?> .site-title,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .menu-item:hover > a,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .current-menu-item > a,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .current-menu-ancestor > a,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .menu-item-object-post_tag > a:before,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-socials-item-link:hover {
		color:<?php echo sanitize_hex_color( $accent_color->format_hex() ); ?>;
		color:var(--g1-hb<?php echo esc_attr( $row ); ?>-atxt-color);
	}

	.g1-hb-row-<?php echo esc_attr( $row ); ?> .g1-drop-toggle-badge,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .snax-button-create,
	.g1-hb-row-<?php echo esc_attr( $row ); ?> .snax-button-create:hover {
		border-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
		border:var(--g1-hb<?php echo esc_attr( $row ); ?>-2-bg-color);
		background-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
		background-color:var(--g1-hb<?php echo esc_attr( $row ); ?>-2-bg-color);
		color:<?php echo sanitize_hex_color( $bimber_hb_button_text->format_hex() ); ?>;
		color:var(--g1-hb<?php echo esc_attr( $row ); ?>-2-itxt-color);
	}

	.g1-skinmode {
		--g1-hb<?php echo esc_attr( $row ); ?>-itxt-color:<?php echo sanitize_hex_color( $skinmode_text_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-atxt-color:<?php echo sanitize_hex_color( $skinmode_accent_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-bg-color:<?php echo sanitize_hex_color( $skinmode_background_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-gradient-color:<?php echo sanitize_hex_color( $skinmode_gradient_color->format_hex() ); ?>;
		--g1-hb<?php echo esc_attr( $row ); ?>-border-color:<?php echo sanitize_hex_color( $skinmode_border_color->format_hex() ); ?>;
	}
<?php
}

bimber_hb_generate_row_css( 'a' );
bimber_hb_generate_row_css( 'b' );
bimber_hb_generate_row_css( 'c' );
?>

<?php

$bimber_hb_text       		= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_text_color' ) );
$bimber_hb_accent  	   		= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_accent_color' ) );
$bimber_hb_background       = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_background_color' ) );
$gradient_option 			= bimber_get_theme_option( 'header', 'builder_canvas_gradient_color' );
$bg_image		 			= bimber_get_theme_option( 'header', 'builder_canvas_background_image' );
$bg_image = $bimber_lazy_loading ? '' : $bg_image;
$bimber_hb_gradient       	= new Bimber_Color( $gradient_option );

$bg_size 		= bimber_get_theme_option( 'header', 'builder_canvas_background_size' );
$bg_repeat 		= bimber_get_theme_option( 'header', 'builder_canvas_background_repeat' );
$bg_position 	= bimber_get_theme_option( 'header', 'builder_canvas_background_position' );

$bg_opacity 	= (int) bimber_get_theme_option( 'header', 'builder_canvas_background_opacity' );
$bimber_hb_button_bg       	= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_button_background' ) );
$bimber_hb_button_text     	= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_button_text' ) );


$bimber_skinmode_text_color         = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_skinmode_text_color' ) );
$bimber_skinmode_accent_color       = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_skinmode_accent_color' ) );
$bimber_skinmode_background_color   = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_skinmode_background_color' ) );
$bimber_skinmode_border_color       = new Bimber_Color( bimber_get_theme_option( 'header', 'builder_canvas_skinmode_border_color' ) );
?>
:root {
	--g1-canvas-itxt-color:<?php echo sanitize_hex_color( $bimber_hb_text->format_hex() ); ?>;
	--g1-canvas-atxt-color:<?php echo sanitize_hex_color( $bimber_hb_accent->format_hex() ); ?>;
	--g1-canvas-bg-color:<?php echo sanitize_hex_color( $bimber_hb_background->format_hex() ); ?>;
	--g1-canvas-2-itxt-color:<?php echo sanitize_hex_color( $bimber_hb_button_text->format_hex() ); ?>;
	--g1-canvas-2-bg-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
	--g1-canvas-bg-image:url(<?php echo esc_url( $bg_image ); ?>);
	--g1-canvas-bg-size:<?php echo $bg_size; ?>;
	--g1-canvas-bg-repeat:<?php echo $bg_repeat; ?>;
	--g1-canvas-bg-position:<?php echo $bg_position; ?>;
	--g1-canvas-bg-opacity:<?php echo 0.01 * $bg_opacity; ?>;
}


.g1-canvas-content,
.g1-canvas-toggle,
.g1-canvas-content .menu-item > a,
.g1-canvas-content .g1-hamburger,
.g1-canvas-content .g1-drop-toggle,
.g1-canvas-content .g1-socials-item-link{
	color:<?php echo sanitize_hex_color( $bimber_hb_text->format_hex() ); ?>;
	color:var(--g1-canvas-itxt-color);
}

.g1-canvas-content .menu-item:hover > a,
.g1-canvas-content .current-menu-item > a,
.g1-canvas-content .current-menu-ancestor > a,
.g1-canvas-content .menu-item-object-post_tag > a:before,
.g1-canvas-content .g1-socials-item-link:hover {
	color:<?php echo sanitize_hex_color( $bimber_hb_accent->format_hex() ); ?>;
	color:var(--g1-canvas-atxt-color);
}

.g1-canvas-global {
	background-color:<?php echo sanitize_hex_color( $bimber_hb_background->format_hex() ); ?>;
	background-color:var(--g1-canvas-bg-color);

	<?php if ( $gradient_option ) : ?>
		background-image:   linear-gradient(to bottom, <?php echo sanitize_hex_color( $bimber_hb_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_hb_gradient->format_hex() ); ?>);
	<?php endif; ?>
}

.g1-canvas-background,
.g1-canvas-background.lazyloaded {
		background-image:url(<?php echo esc_url( $bg_image ); ?>);
		background-image:var(--g1-canvas-bg-image);
		background-size:<?php echo esc_attr( $bg_size ); ?>;
		background-size:var(--g1-canvas-bg-size);
		background-repeat:<?php echo esc_attr( $bg_repeat ); ?>;
		background-repeat:var(--g1-canvas-bg-repeat);
		background-position:<?php echo esc_attr( $bg_position ); ?>;
		background-position:var(--g1-canvas-bg-position);
		opacity:<?php echo esc_attr( 0.01 * $bg_opacity ); ?>;
		opacity:var(--g1-canvas-bg-opacity);
}
.g1-canvas-background.lazyload,
.g1-canvas-background.lazyloading {
	opacity: 0;
}


.g1-canvas-content .snax-button-create {
	border-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
	border-color:var(--g1-canvas-2-bg-color);
	background-color:<?php echo sanitize_hex_color( $bimber_hb_button_bg->format_hex() ); ?>;
	background-color:var(--g1-canvas-2-bg-color);
	color:<?php echo sanitize_hex_color( $bimber_hb_button_text->format_hex() ); ?>;
	color:var(--g1-canvas-2-itxt-color);
}

.g1-skinmode {
	--g1-canvas-itxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_text_color->format_hex() ); ?>;
	--g1-canvas-atxt-color:<?php echo sanitize_hex_color( $bimber_skinmode_accent_color->format_hex() ); ?>;
	--g1-canvas-bg-color:<?php echo sanitize_hex_color( $bimber_skinmode_background_color->format_hex() ); ?>;
}


:root {
	--g1-submenu-rtxt-color: <?php echo sanitize_hex_color( $bimber_submenu_text->format_hex() ); ?>;
	--g1-submenu-atxt-color: <?php echo sanitize_hex_color( $bimber_submenu_accent->format_hex() ); ?>;
	--g1-submenu-bg-color: <?php echo sanitize_hex_color( $bimber_submenu_background->format_hex() ); ?>;
}

/*customizer_preview_submenu*/
.g1-hb-row .sub-menu {
border-color: <?php echo sanitize_hex_color( $bimber_submenu_background->format_hex() ); ?>;
border-color: var(--g1-submenu-bg-color);
background-color: <?php echo sanitize_hex_color( $bimber_submenu_background->format_hex() ); ?>;
background-color: var(--g1-submenu-bg-color);
}

.g1-hb-row .sub-menu .menu-item > a {
color: <?php echo sanitize_hex_color( $bimber_submenu_text->format_hex() ); ?>;
color: var(--g1-submenu-rtxt-color);
}

.g1-hb-row .g1-link-toggle {
color:<?php echo sanitize_hex_color( $bimber_submenu_background->format_hex() ); ?>;
color:var(--g1-submenu-bg-color);
}

.g1-hb-row .sub-menu .menu-item:hover > a,
.g1-hb-row .sub-menu .current-menu-item > a,
.g1-hb-row .sub-menu .current-menu-ancestor > a {
color: <?php echo sanitize_hex_color( $bimber_submenu_accent->format_hex() ); ?>;
color: var(--g1-submenu-atxt-color);
}
/*customizer_preview_submenu_row_end*/

.g1-skinmode {
	--g1-submenu-bg-color: <?php echo sanitize_hex_color( $bimber_skinmode_submenu_background->format_hex() ); ?>;
	--g1-submenu-rtxt-color: <?php echo sanitize_hex_color( $bimber_skinmode_submenu_text->format_hex() ); ?>;
	--g1-submenu-atxt-color: <?php echo sanitize_hex_color( $bimber_skinmode_submenu_accent->format_hex() ); ?>;
}



<?php if ( 'justified' === bimber_get_theme_option( 'header', 'primarynav_layout' ) ) : ?>
	.g1-bin-grow-on .g1-primary-nav{
		flex-grow:1;
		display:flex;
		margin-left:0px;
		margin-right:0px;
	}
	.g1-bin-grow-on .g1-primary-nav-menu{
		flex-grow:1;
		display:flex;
		justify-content:space-between;
		-webkit-justify-content:space-between;
	}
<?php endif;

// we try to get the HB row with logo in it and use it's bg color for simplified header.
$row_letter = bimber_hb_get_row_with_logo();
if ( $row_letter ) :
	$bimber_hb_background   	= new Bimber_Color( bimber_get_theme_option( 'header', 'builder_' . $row_letter . '_background_color' ) );
?>
.g1-header-simplified > .g1-row-background {
	background-color:<?php echo sanitize_hex_color( $bimber_hb_background->format_hex() )?>;
	background-color:var(--g1-hb<?php echo esc_attr( $row_letter ); ?>-bg-color );
}
<?php endif;
