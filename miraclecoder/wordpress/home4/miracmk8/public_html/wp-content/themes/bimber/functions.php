<?php
/**
 * Bimber Theme functions and definitions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

define( 'BIMBER_THEME_DIR',         trailingslashit( get_template_directory() ) );
define( 'BIMBER_THEME_DIR_URI',     trailingslashit( get_template_directory_uri() ) );
define( 'BIMBER_INCLUDES_DIR',      trailingslashit( get_template_directory() ) . 'includes/' );
define( 'BIMBER_ADMIN_DIR',         trailingslashit( get_template_directory() ) . 'includes/admin/' );
define( 'BIMBER_ADMIN_DIR_URI',     trailingslashit( get_template_directory_uri() ) . 'includes/admin/' );
define( 'BIMBER_FRONT_DIR',         trailingslashit( get_template_directory() ) . 'includes/front/' );
define( 'BIMBER_FRONT_DIR_URI',     trailingslashit( get_template_directory_uri() ) . 'includes/front/' );
define( 'BIMBER_PLUGINS_DIR',       trailingslashit( get_template_directory() ) . 'includes/plugins/' );
define( 'BIMBER_PLUGINS_DIR_URI',   trailingslashit( get_template_directory_uri() ) . 'includes/plugins/' );

// Load common resources (required by both, admin and front, contexts).
require_once BIMBER_INCLUDES_DIR . 'functions.php';

// Load context resources.
if ( is_admin() ) {
	require_once BIMBER_ADMIN_DIR . 'functions.php';
} else {
	require_once BIMBER_FRONT_DIR . 'functions.php';
}


add_filter( 'img_caption_shortcode_width', 'bimber_img_caption_shortcode_width', 11, 3 );
function bimber_img_caption_shortcode_width( $width, $atts, $content ) {
	if ( 'aligncenter' === $atts['align'] ) {
		$width = 0;
	}

	return $width;
}


function bimber_the_permalink( $permalink ) {
	return apply_filters( 'bimber_the_permalink', $permalink );
}

add_filter( 'gettext_with_context', 'bimber_recent_comments_change_markup', 10, 4 );
function bimber_recent_comments_change_markup( $translated, $text, $context, $domain ) {
	if ( '%1$s on %2$s' == $text && 'widgets' == $context && 'default' == $domain ) {
		$translated = _x('<div class="g1-meta">%1$s on </div><div class="entry-title g1-epsilon g1-epsilon-1st">%2$s</div>', 'widgets', 'bimber');
	}

	return $translated;
}


add_filter( 'bimber_single_featured_media_allow_video', 'bimber_single_featured_media_allow_video' );
function bimber_single_featured_media_allow_video( $formats ) {
	if ( (bool) bimber_get_theme_option( 'post_video', 'single_featured_media_allow_video' ) ) {
		$formats[] = 'video';
	}

	return $formats;
}





add_filter( 'bimber_html_class', 'bimber_html_class_canvas' );
function bimber_html_class_canvas( $classes ) {
	$sticky = explode( ',', bimber_get_theme_option( 'canvas', 'sticky' ) );

	if ( count( array_intersect( array('home', 'all'), $sticky ) ) ) {
		$classes[] = 'g1-off-inside';
	}

	$is_sticky = false;

	if ( in_array( 'all', $sticky ) ) {
		$is_sticky = true;
	} else {
		if ( is_home() && in_array( 'home', $sticky ) ) {
			$is_sticky = true;
		}
	}

	if ( $is_sticky ) {
		$classes[] = 'g1-off-global-desktop';
	}

	return $classes;
}
















function bimber_render_entry_inner_class( $r = array() ) {
	$final = array(
		'entry-inner',
	);

	$cardstyle = bimber_get_theme_option( 'cards', 'single_content' );
	if ( 'none' !== $cardstyle ) {
		$final[] = 'g1-card';
		$final[] = 'g1-card-' . $cardstyle;
	}

	$final = array_merge( $final, $r );
	//$final = apply_filters( 'bimber_get_todo_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}


add_filter( 'bimber_page_header_class', 'bimber_page_header_class' );
function bimber_page_header_class( $r ) {
	$cards_content = 'none';

	if ( is_archive() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'archive_content' );
	} else if ( is_search() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'search_content' );
	}

	if ( 'solid' === $cards_content ) {
		$r[] = 'g1-row-bg-alt';
	}

	return $r;
}


function bimber_render_page_body_class( $r = array() ) {
	$final = array(
		'page-body',
		'g1-row',
		'g1-row-layout-page',
		'g1-row-padding-m',
	);

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_page_body_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}



function bimber_render_sidebar_class( $r = array() ) {
	$final = array(
		'g1-sidebar',
	);

	$cards_content = 'none';
	$cards_sidebar = 'none';

	if ( is_home() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'home_content' );
		$cards_sidebar = bimber_get_theme_option( 'cards', 'home_sidebar' );
	} elseif ( is_archive() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'archive_content' );
		$cards_sidebar = bimber_get_theme_option( 'cards', 'archive_sidebar' );
	} else if ( is_single() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'single_content' );
		$cards_sidebar = bimber_get_theme_option( 'cards', 'single_sidebar' );
	} else if ( is_search() ) {
		$cards_content = bimber_get_theme_option( 'cards', 'search_content' );
		$cards_sidebar = bimber_get_theme_option( 'cards', 'search_sidebar' );
	}

	if ( 'none' !== $cards_content ) {
		$final[] = 'g1-with-cards';
	}

	if ( 'none' === $cards_content && 'none' === $cards_sidebar && ! in_array( 'g1-column-1of4', $r, true ) ) {
		$final[] = 'g1-sidebar-padded';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_sidebar_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}





function bimber_render_comment_body_class( $r = array() ) {
	$final = array(
		'comment-body',
	);

	$card_style = bimber_get_theme_option( 'cards', 'single_comments' );
	if ( 'none' !== $card_style ) {
		$final[] = 'g1-card';
		$final[] = 'g1-card-' . $card_style;
	}

	$final = array_merge( $final, $r );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}






function bimber_render_collection_class( $r = array() ) {
	$final = array(
		'g1-collection',
	);

	$card_style = 'none';

	if ( is_home() ) {
		$card_style = bimber_get_theme_option( 'cards', 'home_content' );
	} else if ( is_archive() ) {
		$card_style = bimber_get_theme_option( 'cards', 'archive_content' );
	} else if ( is_search() ) {
		$card_style = bimber_get_theme_option( 'cards', 'search_content' );
	}

	if ( 'none' !== $card_style ) {
		$final[] = 'g1-collection-with-cards';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_collection_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}

function bimber_render_row_class( $r = array() ) {
	$final = array(
		'g1-row',
	);

	$card_style = 'none';

	if ( is_home() ) {
		$card_style = bimber_get_theme_option( 'cards', 'home_content' );
	} else if ( is_archive() ) {
		$card_style = bimber_get_theme_option( 'cards', 'archive_content' );
	} else if ( is_single() ) {
		$card_style = bimber_get_theme_option( 'cards', 'single_content' );
	} else if ( is_search() ) {
		$card_style = bimber_get_theme_option( 'cards', 'search_content' );
	}

	if ( 'solid' === $card_style ) {
		$final[] = 'g1-row-bg-alt';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_row_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}




function bimber_render_global_featured_class( $r = array() ) {
	$final = array(
		'g1-row',
		'g1-row-layout-page',
		'g1-featured-row',
	);

	$card_style = 'none';

	if ( is_home() ) {
		$card_style = bimber_get_theme_option( 'cards', 'home_content' );
	} else if ( is_archive() ) {
		$card_style = bimber_get_theme_option( 'cards', 'archive_content' );
	} else if ( is_single() ) {
		$card_style = bimber_get_theme_option( 'cards', 'single_content' );
	}

	if ( 'solid' === $card_style ) {
		$final[] = 'g1-row-bg-alt';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_global_featured_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}



function bimber_render_collection_more_class( $r = array() ) {
	$final = array(
		'g1-collection-more',
	);

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_collection_more_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}



function bimber_render_nav_single_class( $r = array() ) {
	$final = array(
		'g1-nav-single',
	);

	$card_style = bimber_get_theme_option( 'cards', 'single_content' );
	if ( 'none' !== $card_style ) {
		$final[] = 'g1-card';
		$final[] = 'g1-card-' . $card_style;
		$final[] = 'g1-card-l';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_nav_single_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}



add_filter( 'wyr_get_reactions_body_class',     'bimber_wyr_get_reactions_body_class' );
function bimber_wyr_get_reactions_body_class( $r ) {
	$card_style = bimber_get_theme_option( 'cards', 'single_content' );
	if ( 'none' !== $card_style ) {
		$r[] = 'g1-card';
		$r[] = 'g1-card-' . $card_style;
		$r[] = 'g1-card-l';
	}

	return $r;
}




function bimber_render_svg( $sprite, $target, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'width' => 200,
		'height' => 200,
	) );

	$href = trailingslashit( get_template_directory_uri() ) . 'images/';
	$href = $href . $sprite . '.svg#' . $target;
	?>
	<svg viewbox="0 0 <?php echo (int) $args['width']; ?> <?php echo (int) $args['height']; ?>" width="<?php echo (int) $args['width']; ?>" height="<?php echo (int) $args['height']; ?>">
		<use xlink:href="<?php echo esc_url( $href ); ?>" />
	</svg>
	<?php
}


function bimber_render_logo() {
	get_template_part( 'template-parts/header-builder/elements/logo' );
}

function bimber_render_mobile_logo() {
	get_template_part( 'template-parts/header-builder/elements/mobile_logo' );
}


function bimber_render_featured_entries() {
	if ( bimber_show_global_featured_entries() ) {
		get_template_part( 'template-parts/collection-featured' );
	}
}

function bimber_get_documentation_link( $article ) {
	$link = 'https://bimber.bringthepixel.com/docs/';

	switch ( $article ) {
		case 'nsfw':
			$link .= 'nsfw/';
			break;
	}

	return $link;
}




add_action( 'wp_head', 'bimber_wp_head_theme_color' );
function bimber_wp_head_theme_color() {
	$color = bimber_get_theme_option( 'meta', 'theme_color' );

	if ( strlen( $color ) ) {
		require_once BIMBER_FRONT_DIR . 'lib/class-bimber-color.php';

		$color = new Bimber_Color( $color );

		echo '<meta name="theme-color" content="' . sanitize_hex_color( $color->format_hex() ) . '" />' . "\r\n";
	}
}


add_filter( 'bimber_render_back_to_top', 'bimber_toggle_back_to_top', 5 );
function bimber_toggle_back_to_top( $bool ) {
	$bool = bimber_get_theme_option( 'back_to_top', '' );
	$bool = 'none' === $bool ? false : true;

	return $bool;
}


add_filter( 'wp_resource_hints', 'bimber_google_fonts_resource_hints', 10, 2 );
function bimber_google_fonts_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.gstatic.com/';
	}

	return $urls;
}


add_filter( 'wpgdprc_wordpress_field', 'bimber_wpgdprc_wordpress_field', 10, 2 );
function bimber_wpgdprc_wordpress_field( $field, $submitField ) {
	if ( false !== strpos( $field, '<label style="font-size: 14px;"><i>' ) ) {

		$field = str_replace(
			array(
				'<p class="wpgdprc-checkbox"><input',
				'<label style="font-size: 14px;"><i>',
				'</i></label>',
			),
			array(
				'<input',
				'<p class="wpgdprc-checkbox"><span>',
				'</span>'
			),
			$field
		);
	}

	return $field;
}

