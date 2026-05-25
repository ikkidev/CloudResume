<?php
/**
 * Global styles
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

$bimber_stack 	 = bimber_get_current_stack();

$bimber_page_layout = bimber_get_theme_option( 'global', 'layout' );

$bimber_body_background          = array();
$bimber_body_background['color'] = new Bimber_Color( bimber_get_theme_option( 'global', 'background_color' ) );

$bimber_body_skinmode_background          = array();
$bimber_body_skinmode_background['color'] = new Bimber_Color( bimber_get_theme_option( 'global', 'skinmode_background_color' ) );

$bimber_cs_1_text1                  = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_1_text1' ) );
$bimber_cs_1_text2                  = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_1_text2' ) );
$bimber_cs_1_text3                  = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_1_text3' ) );
$bimber_cs_1_accent1                = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_1_accent1' ) );
$bimber_cs_2_background             = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_2_background_color' ) );
$bimber_cs_2_text1                  = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_2_text1' ) );

$bimber_cs_2_background2 = $bimber_cs_2_background;
if ( strlen( bimber_get_theme_option( 'content', 'cs_2_background2_color' ) ) ) {
	$bimber_cs_2_background2 = new Bimber_Color( bimber_get_theme_option( 'content', 'cs_2_background2_color' ) );
}


$bimber_trending_background         	= new Bimber_Color( bimber_get_theme_option( 'trending', 'background_color' ) );
$bimber_hot_background              	= new Bimber_Color( bimber_get_theme_option( 'hot', 'background_color' ) );
$bimber_popular_background          	= new Bimber_Color( bimber_get_theme_option( 'popular', 'background_color' ) );
$bimber_members_only_background         = new Bimber_Color( bimber_get_theme_option( 'members_only', 'background_color' ) );
$bimber_coupon_inside_background        = new Bimber_Color( bimber_get_theme_option( 'coupon_inside', 'background_color' ) );
$bimber_trending_text         			= new Bimber_Color( bimber_get_theme_option( 'trending', 'text_color' ) );
$bimber_hot_text              			= new Bimber_Color( bimber_get_theme_option( 'hot', 'text_color' ) );
$bimber_popular_text          			= new Bimber_Color( bimber_get_theme_option( 'popular', 'text_color' ) );
$bimber_members_only_text          		= new Bimber_Color( bimber_get_theme_option( 'members_only', 'text_color' ) );
$bimber_coupon_inside_text          	= new Bimber_Color( bimber_get_theme_option( 'coupon_inside', 'text_color' ) );
$bimber_trending_optional_gradient  	= new Bimber_Color( bimber_get_theme_option( 'trending', 'optional_gradient_color' ) );
$bimber_hot_optional_gradient       	= new Bimber_Color( bimber_get_theme_option( 'hot', 'optional_gradient_color' ) );
$bimber_popular_optional_gradient   	= new Bimber_Color( bimber_get_theme_option( 'popular', 'optional_gradient_color' ) );
$bimber_members_only_optional_gradient  = new Bimber_Color( bimber_get_theme_option( 'members_only', 'optional_gradient_color' ) );
$bimber_coupon_inside_optional_gradient = new Bimber_Color( bimber_get_theme_option( 'coupon_inside', 'optional_gradient_color' ) );
?>
:root {
	--g1-layout-bg-color: <?php echo sanitize_hex_color( $bimber_body_background['color']->format_hex() ); ?>;
}
body.g1-layout-boxed .g1-body-inner {
	background-color: <?php echo sanitize_hex_color( $bimber_body_background['color']->format_hex() ); ?>;
	background-color: var(--g1-layout-bg-color);
}

:root.g1-skinmode {
	--g1-layout-bg-color: <?php echo sanitize_hex_color( $bimber_body_skinmode_background['color']->format_hex() ); ?>;
}


.g1-layout-boxed .g1-row-layout-page {
max-width: 1212px;
}

/* Global Color Scheme */
a,
.entry-title > a:hover,
.entry-meta a:hover,
.menu-item > a:hover,
.current-menu-item > a,
.mtm-drop-expanded > a,
.g1-nav-single-prev > a > span:before,
.g1-nav-single-next > a > span:after,
.g1-nav-single-prev > a:hover > strong,
.g1-nav-single-prev > a:hover > span,
.g1-nav-single-next > a:hover > strong,
.g1-nav-single-next > a:hover > span,
.mashsb-count,
.archive-title:before,
.snax .snax-item-title > a:hover,
.woocommerce .star-rating span::before,
.woocommerce-page .star-rating span::before {
color: <?php echo sanitize_hex_color( $bimber_cs_1_accent1->format_hex() ); ?>;
}

input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.g1-button-solid,
.g1-button-solid:hover,
.g1-arrow-solid,
<?php if ( ! in_array( $bimber_stack, array( 'original-2018', 'app' ) ) ): ?>.entry-categories .entry-category:hover, <?php endif; ?>
.author-link,
.author-info .author-link,
.g1-box-icon,
<?php if ( 'bunchy' === $bimber_stack) :?>
.snax .snax-formats .snax-format:hover,
.entry-badge,
a.g1-arrow,
a.g1-arrow:hover,
<?php endif; ?>
.g1-collection-ticker .g1-collection-title,
.wyr-reaction:hover .wyr-reaction-button,
.wyr-reaction-voted .wyr-reaction-button,
.wyr-reaction .wyr-reaction-bar {
border-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_cs_2_text1->format_hex() ); ?>;
}

<?php if ( 'news' === $bimber_stack ) : ?>
	.g1-box-icon {
		background-color: transparent;
		color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
	}

	.g1-box-background {
		border-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
	}
<?php endif; ?>


.entry-counter:before {
border-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_cs_2_text1->format_hex() ); ?>;
}



.g1-quick-nav-tabs .menu-item-type-g1-trending > a,
.entry-flag-trending {
border-color: <?php echo sanitize_hex_color( $bimber_trending_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_trending_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_trending_text->format_hex() ); ?>;
<?php if ( bimber_get_theme_option( 'trending', 'optional_gradient_color' ) && $bimber_trending_background->get_hex() !== $bimber_trending_optional_gradient->get_hex() ) : ?>
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_trending_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_trending_optional_gradient->format_hex() ); ?>);
<?php endif; ?>
}

.g1-quick-nav-tabs .menu-item-type-g1-hot > a,
.entry-flag-hot {
border-color: <?php echo sanitize_hex_color( $bimber_hot_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_hot_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_hot_text->format_hex() ); ?>;
<?php if ( bimber_get_theme_option( 'hot', 'optional_gradient_color' ) && $bimber_hot_background->get_hex() !== $bimber_hot_optional_gradient->get_hex() ) : ?>
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_hot_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_hot_optional_gradient->format_hex() ); ?>);
<?php endif; ?>
}

.g1-quick-nav-tabs .menu-item-type-g1-popular > a,
.entry-flag-popular {
border-color: <?php echo sanitize_hex_color( $bimber_popular_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_popular_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_popular_text->format_hex() ); ?>;
<?php if ( bimber_get_theme_option( 'popular', 'optional_gradient_color' ) && $bimber_popular_background->get_hex() !== $bimber_popular_optional_gradient->get_hex() ) : ?>
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_popular_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_popular_optional_gradient->format_hex() ); ?>);
<?php endif; ?>
}

.entry-flag-members_only {
border-color: <?php echo sanitize_hex_color( $bimber_members_only_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_members_only_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_members_only_text->format_hex() ); ?>;
<?php if ( bimber_get_theme_option( 'members_only', 'optional_gradient_color' ) && $bimber_members_only_background->get_hex() !== $bimber_members_only_optional_gradient->get_hex() ) : ?>
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_members_only_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_members_only_optional_gradient->format_hex() ); ?>);
<?php endif; ?>
}

.entry-flag-coupon {
border-color: <?php echo sanitize_hex_color( $bimber_coupon_inside_background->format_hex() ); ?>;
background-color: <?php echo sanitize_hex_color( $bimber_coupon_inside_background->format_hex() ); ?>;
color: <?php echo sanitize_hex_color( $bimber_coupon_inside_text->format_hex() ); ?>;
<?php if ( bimber_get_theme_option( 'coupon_inside', 'optional_gradient_color' ) && $bimber_coupon_inside_background->get_hex() !== $bimber_coupon_inside_optional_gradient->get_hex() ) : ?>
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_coupon_inside_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_coupon_inside_optional_gradient->format_hex() ); ?>);
<?php endif; ?>
}

<?php if ( $bimber_cs_2_background->get_hex() !== $bimber_cs_2_background2->get_hex() ) : ?>
.g1-featured-without-title .entry-tpl-gridxs .entry-header {
	background-color: transparent;
	background-image: linear-gradient(to bottom right, rgba(<?php echo (int) $bimber_cs_2_background->get_red();?>,<?php echo (int) $bimber_cs_2_background->get_green();?>, <?php echo (int) $bimber_cs_2_background->get_blue();?>, 0.8 ), rgba(<?php echo (int) $bimber_cs_2_background2->get_red();?>,<?php echo (int) $bimber_cs_2_background2->get_green();?>, <?php echo (int) $bimber_cs_2_background2->get_blue();?>, 0.8 ));
}
<?php endif; ?>

<?php if ( 'miami' === $bimber_stack ) : ?>
	.entry-categories .entry-category {
	border-top-color: <?php echo sanitize_hex_color( $bimber_cs_1_accent1->format_hex() ); ?>;
	}
<?php endif; ?>

<?php if ( 'app' === $bimber_stack ) : ?>
	.entry-categories .entry-category {
		color: <?php echo sanitize_hex_color( $bimber_cs_1_accent1->format_hex() ); ?>;
	}
<?php endif; ?>

<?php if ( 'miami' === $bimber_stack && $bimber_cs_2_background->get_hex() !== $bimber_cs_2_background2->get_hex() ) : ?>
	.g1-box-background {
	background-image:         linear-gradient(to bottom right, <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_2_background2->format_hex() ); ?>);
	}
<?php endif; ?>



<?php if ( 'original-2018' === $bimber_stack && $bimber_cs_2_background->get_hex() !== $bimber_cs_2_background2->get_hex() ) : ?>
	.entry-counter:before {
		background-image: -webkit-linear-gradient(to top left, <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_2_background2->format_hex() ); ?>);
		background-image:    -moz-linear-gradient(to top left, <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_2_background2->format_hex() ); ?>);
		background-image:      -o-linear-gradient(to top left, <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_2_background2->format_hex() ); ?>);
		background-image:         linear-gradient(to top left, <?php echo sanitize_hex_color( $bimber_cs_2_background->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_cs_2_background2->format_hex() ); ?>);
	}
<?php endif; ?>




<?php
// Archive related styles.
$bimber_bg1_color = bimber_get_theme_option( 'archive', 'header_bg1_color' );
$bimber_bg1_color = strlen( $bimber_bg1_color ) ? new Bimber_Color( $bimber_bg1_color) : false;

$bimber_bg2_color = bimber_get_theme_option( 'archive', 'header_bg2_color' );
$bimber_bg2_color = strlen( $bimber_bg2_color ) ? new Bimber_Color( $bimber_bg2_color) : false;
?>

<?php if ( $bimber_bg1_color  ) : ?>
.archive-header > .g1-row-background {
	background-color: <?php echo sanitize_hex_color( $bimber_bg1_color->format_hex() ); ?>;

	<?php if ( $bimber_bg2_color ) : ?>
	background-image: -webkit-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_bg1_color->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_bg2_color->format_hex() ); ?>);
	background-image:    -moz-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_bg1_color->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_bg2_color->format_hex() ); ?>);
	background-image:      -o-linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_bg1_color->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_bg2_color->format_hex() ); ?>);
	background-image:         linear-gradient(to right, <?php echo sanitize_hex_color( $bimber_bg1_color->format_hex() ); ?>, <?php echo sanitize_hex_color( $bimber_bg2_color->format_hex() ); ?>);
	<?php endif; ?>
}
<?php endif; ?>
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

<?php
//Translatable phrases for MyCred Integration:
?>
.member-header .mycred-my-rank:after {
	content:"<?php echo esc_html__( 'Rank', 'bimber' );?>";
}
<?php // GDPR Styles.
$g1_theme_options   = get_option( bimber_get_theme_options_id() );
if ( bimber_can_use_plugin( 'wp-gdpr-compliance/wp-gdpr-compliance.php' ) && isset( $g1_theme_options['gdpr_enabled'] ) && 'on' === $g1_theme_options['gdpr_enabled'] ) :
?>
.snax-wpsl-gdpr-consent{
	font-weight:300;
}
@-webkit-keyframes g1-flash {
	from,
	50%,
	to {
	  opacity: 1;
	}

	25%,
	75% {
	  opacity: 0;
	}
}

@keyframes g1-flash {
	from,
	50%,
	to {
	  opacity: 1;
	}

	25%,
	75% {
	  opacity: 0;
	}
}
.snax-wpsl-gdpr-consent-blink{
	-webkit-animation: g1-flash 0.5s 2 linear;
	animation: g1-flash 0.5s 2 linear;
}
.wp-social-login-provider-list a{
	opacity:0.5;
	pointer-events:none;
}
.wp-social-login-provider-list-active a{
	opacity:1;
	pointer-events:unset;
}
<?php endif;?>
