<?php
/**
 * WPML plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'bimber_hot_page_id', 			'bimber_wpml_translate_page_id' );
add_filter( 'bimber_popular_page_id', 		'bimber_wpml_translate_page_id' );
add_filter( 'bimber_trending_page_id', 		'bimber_wpml_translate_page_id' );
add_filter( 'bimber_top_page_id', 			'bimber_wpml_translate_page_id' );
add_filter( 'bimber_breadcrumb_page_id',	'bimber_wpml_translate_page_id' );
add_filter( 'bimber_home_elementor_page_id','bimber_wpml_translate_page_id' );
add_filter( 'bimber_home_vc_page_id',       'bimber_wpml_translate_page_id' );

add_action( 'bimber_wpml_add_language_selector', 'bimber_wpml_add_canvas_switcher' );

// Disable our collections cache.
add_filter( 'bimber_featured_entries_use_cache', '__return_false' );
add_filter( 'bimber_dont_miss_query_use_cache', '__return_false' );

// Mailchimp integration.
add_filter( 'shortcode_atts_mc4wp_form', 'bimber_wpml_translate_shortcode_atts', 10, 4 );

/**
 * Return page id in current language
 *
 * @param int $page_id          Page id.
 *
 * @return int
 */
function bimber_wpml_translate_page_id( $page_id ) {
	$page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true );

	return $page_id;
}

/**
 * Render language switcher in canvas.
 */
function bimber_wpml_add_canvas_switcher() {
	global $sitepress;

	$ret = array();
	$css_prefix = 'wpml-ls-';

	$get_ls_args = array(
		'skip_missing' => true,
	);

	$languages = $sitepress->get_ls_languages( $get_ls_args );
	$languages = is_array( $languages ) ? $languages : array();

	$languages = $sitepress->order_languages( $languages );

	if ( $languages ) {
		foreach ( $languages as $code => $data ) {
			$css_classes = array(
				$css_prefix . 'slot-shortcode_actions',
				$css_prefix . 'item',
				$css_prefix . 'item-' . $code,
			);

			$is_current_language = $code === $sitepress->get_current_language();

			$ret[ $code ] = array(
				'code' => $code,
				'url'  => $data['url'],
			);

			$ret[ $code ]['url'] = apply_filters( 'wpml_ls_language_url', $ret[ $code ]['url'], $data );

			$ret[ $code ]['url']            = apply_filters( 'wpml_ls_language_url', $ret[ $code ]['url'], $data );
			$ret[ $code ]['url']            = $sitepress->get_wp_api()->is_admin() ? '#' : $ret[ $code ]['url'];
			$ret[ $code ]['flag_url']       = $data['country_flag_url'];
			$ret[ $code ]['flag_title']     = $data['native_name'];
			$ret[ $code ]['native_name']    = $data['native_name'];
			$ret[ $code ]['display_name']   = $data['translated_name'];

			if ( $is_current_language ) {
				$ret[ $code ]['is_current'] = true;

				array_push( $css_classes, $css_prefix . 'current-language' );
			}

			$ret[ $code ]['css_classes'] = $css_classes;
		}

		$i = 1;

		foreach ( $ret as &$lang ) {
			if ( 1 === $i ) {
				array_push( $lang['css_classes'], $css_prefix . 'first-item' );
			}

			if ( count( $ret ) === $i ) {
				array_push( $lang['css_classes'], $css_prefix . 'last-item' );
			}

			$lang['css_classes'] = apply_filters( 'wpml_ls_model_language_css_classes', $lang['css_classes'] );
			$lang['css_classes'] = implode( ' ', $lang['css_classes'] );

			$i++;
		}
	}
	?>
	<div class="wpml-ls-statics-shortcode_actions wpml-ls wpml-ls-legacy-list-horizontal">
		<ul>
			<?php foreach ( $ret as $code => $language ) : ?>

				<li class="<?php echo $language['css_classes']; ?> wpml-ls-item-legacy-list-horizontal">
					<a href="<?php echo esc_url( $language['url'] ); ?>">
						<?php if ( ! empty( $language['flag_url'] ) ) : ?>
						<img class="wpml-ls-flag" src="<?php echo esc_url( $language['flag_url'] ); ?>" alt="<?php echo esc_attr( $language['code'] ); ?>" title="<?php echo esc_attr( $language['flag_title'] ); ?>">
						<?php endif; ?>

						<span class="wpml-ls-display"><?php echo esc_html( $language['display_name'] ); ?></span>
					</a>
				</li>

			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}


add_action( 'wp_enqueue_scripts',           'bimber_wpml_enqueue_head_styles', 20 );
/**
 * Enqueue Download Monitor Plugin integration stylesheets.
 */
function bimber_wpml_enqueue_head_styles() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	// Global styles.
	wp_enqueue_style( 'bimber-wpml', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/wpml-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-wpml', 'rtl', 'replace' );
}

/**
 * Translate a shortcode's attributes.
 *
 * @param array  $out       The output array of shortcode attributes.
 * @param array  $pairs     The supported attributes and their defaults.
 * @param array  $atts      The user defined shortcode attributes.
 * @param string $shortcode The shortcode name.
 *
 * @return array
 */
function bimber_wpml_translate_shortcode_atts( $out, $pairs, $atts, $shortcode ) {
    // Missing shortcode attribute.
    if ( ! isset( $out['id'] ) ) {
        return $out;
    }

    // Set default form id.
    if ( empty( $out['id'] ) ) {
        $form_id = (int) get_option( 'mc4wp_default_form_id', 0 );

        // Default form id not set.
        if ( 0 === $form_id ) {
            return $out;
        }

        $out['id'] = $form_id;
    }

    $out['id'] = apply_filters( 'wpml_object_id', $out['id'], 'mc4wp-form', true );

    return $out;
}
