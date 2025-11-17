<?php
/**
 * Bending cat
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

if ( is_customize_preview() ) {
	add_action( 'wp_footer', 'bimber_bending_cat_apply_styles_live_preview', 999 );
} else {
	add_filter( 'bimber_dynamic_styles', 'bimber_bending_cat_apply_styles', 999, 1 );
	add_action( 'get_footer', 'bimber_bending_cat_enqueue_fonts' );
}

add_action( 'customize_preview_init', 'bimber_enqueue_live_preview_script' );
/**
 * Apply bending cat styles.
 */
function bimber_bending_cat_apply_styles_live_preview() {
	$template = bimber_bending_cat_get_style_live_preview();
	echo $template;
}

/**
 * Apply bending cat styles.
 *
 * @param string $styles Styles.
 */
function bimber_bending_cat_apply_styles( $styles ) {
	$template = bimber_bending_cat_get_style();
	return $styles . $template;
}

/**
 * Get style template.
 *
 * @return string
 */
function bimber_bending_cat_get_style() {
	ob_start();
	if ( defined( 'BTP_DEV2' ) && BTP_DEV2 ) {
	?>
	@media only screen and (min-width: 801px){
		.g1-row-inner{
			max-width:<?php echo esc_attr( bimber_get_theme_option( 'page_width', '' ) );?>px
		}
	}
	<?php
	}
	$selectors = bimber_get_customizable_selectors_settings();
	foreach ( $selectors as $key ) {
		$option = json_decode( bimber_get_theme_option( $key, '' ), true );
		if ( empty( $option ) ) {
			continue;
		}
		$disable_attributes = array();
		if ( 'typo_button' === $key ) {
			$disable_attributes[] = 'font-size';
			$disable_attributes[] = 'font-size-tablet';
			$disable_attributes[] = 'font-size-mobile';
		}
		?>
		<?php echo $option['selector'];?>{
			<?php bimber_bending_cat_render_css_attributes( $option, 'all', $disable_attributes );?>
		}
		@media only screen and (min-width: 1025px) {
			<?php echo $option['selector'];?>{
				<?php bimber_bending_cat_render_css_attributes( $option, 'desktop', $disable_attributes );?>
			}
		}
		@media only screen and (min-width: 768px) and (max-width: 1023px){
			<?php echo $option['selector'];?>{
				<?php bimber_bending_cat_render_css_attributes( $option, 'tablet', $disable_attributes );?>
			}
		}
		@media only screen and (max-width: 767px){
			<?php echo $option['selector'];?>{
				<?php bimber_bending_cat_render_css_attributes( $option, 'mobile', $disable_attributes );?>
			}
		}
		<?php if ( 'typo_button' === $key ) {
			bimber_bending_cat_render_button_font_sizes( $option );
		}
	}
	$template = ob_get_clean();
	return apply_filters( 'bimber_bending_cat_style', $template );
}

/**
 * Get style template.
 *
 * @return string
 */
function bimber_bending_cat_get_style_live_preview() {
	ob_start();
	$selectors = bimber_get_customizable_selectors_settings();
	?>
	<div id="bending-cat-customizer">
	<?php
	if ( defined( 'BTP_DEV2' ) && BTP_DEV2 ) {
		?>
	<div id="g1-bending-cat-page-width">
		<style type="text/css" media="screen">
			@media only screen and (min-width: 801px){
				.g1-row-inner{
					max-width:<?php echo esc_attr( bimber_get_theme_option( 'page_width', '' ) );?>px
				}
			}
		</style>
	</div>
	<?php
	}
	foreach ( $selectors as $key ) {
		$disable_attributes = array();
		if ( 'typo_button' === $key ) {
			$disable_attributes[] = 'font-size';
			$disable_attributes[] = 'font-size-tablet';
			$disable_attributes[] = 'font-size-mobile';
		}
		$option = json_decode( bimber_get_theme_option( $key, '' ), true );
		if ( empty( $option ) ) {
			continue;
		}
		?>
		<div id="g1-bending-cat-<?php echo sanitize_html_class( $key ) ?>">
			<style type="text/css" media="screen">
					<?php echo $option['selector'];?>{
						<?php bimber_bending_cat_render_css_attributes( $option, 'all', $disable_attributes );?>
					}
				@media only screen and (min-width: 1025px) {
					<?php echo $option['selector'];?>{
						<?php bimber_bending_cat_render_css_attributes( $option, 'desktop', $disable_attributes );?>
					}
				}
				@media only screen and (min-width: 768px) and (max-width: 1023px){
					<?php echo $option['selector'];?>{
						<?php bimber_bending_cat_render_css_attributes( $option, 'tablet', $disable_attributes );?>
					}
				}
				@media only screen and (max-width: 767px){
					<?php echo $option['selector'];?>{
						<?php bimber_bending_cat_render_css_attributes( $option, 'mobile', $disable_attributes );?>
					}
				}
				<?php if ( 'typo_button' === $key ) {
					bimber_bending_cat_render_button_font_sizes( $option );
				}?>
			</style>
		</div>
	<?php
	}
	$fonts_url = bimber_bending_cat_get_font_url();
	if ( $fonts_url ) {
	?>
		<div id="bending-cat-google-fonts">
			<link rel="stylesheet" id="bimber-google-fonts-customized-css" href="<?php echo esc_url($fonts_url);?>" type="text/css" media="all">
		</div>
	<?php }?>
	<div id="bending-cat-google-fonts-live"></div>
	</div>
	<?php
	$template = ob_get_clean();
	return apply_filters( 'bimber_bending_cat_style', $template );
}

/**
 * Load customizer live preview js.
 */
function bimber_enqueue_live_preview_script() {
	$version = bimber_get_theme_version();

	wp_enqueue_script(
		'bimber-themecustomizer',
		BIMBER_ADMIN_DIR_URI . 'customizer/js/theme-customizer.js',
		array( 'jquery','customize-preview' ),
		$version,
		true
	);
	$data = array(
		'selectors' 	=> bimber_get_customizable_selectors_settings(),
		'attributes'	=> bimber_bending_cat_get_typography_cutomization_options(),
		'fonts'			=> bimber_bending_cat_get_available_font_families( true ),
	);
	wp_localize_script( 'bimber-themecustomizer', 'bimber_themecustomizer', $data );
}

/**
 * Get customizable selectors
 *
 * @return array
 */
function bimber_get_customizable_selectors_settings() {
	$selectors = apply_filters( 'bimber_get_customizable_selectors_settings', array(
		'typo_body',
		'typo_tags',
		'typo_categories',
		'typo_tabs',
		'typo_button',
		'typo_meta',
		'typo_link',
		'typo_primary_nav',
		'typo_secondary_nav',
		'typo_quick_nav',
		'typo_submenus',
		'typo_drop_toggle',
		'typo_xl',
		'typo_giga',
		'typo_mega',
		'typo_mega_2nd',
		'typo_alpha',
		'typo_alpha_2nd',
		'typo_beta',
		'typo_beta_2nd',
		'typo_gamma',
		'typo_gamma_2nd',
		'typo_gamma_3rd',
		'typo_delta',
		'typo_delta_2nd',
		'typo_delta_3rd',
		'typo_epsilon',
		'typo_epsilon_2nd',
		'typo_epsilon_3rd',
		'typo_zeta',
		'typo_zeta_2nd',
		'typo_zeta_3rd',
		'typo_quote',
	) );
	return $selectors;
}

/**
 * Render css code from array of attributes.
 *
 * @param array $option  		Array of attributes.
 * @param array $media_query  	Filter atts by media query.
 */
function bimber_bending_cat_render_css_attributes( $option, $media_query, $disable_attributes ) {

	$attributes = bimber_bending_cat_get_typography_cutomization_options();
	$out = '';
	$mobile = array();
	$tablet = array();
	foreach ( $option as $key => $value ) {
		if ( in_array( $key, $disable_attributes, true ) ) {
			continue;
		}
		$not_empty = 'g1-none' !== $value && ! empty( $value );
		if ( isset( $attributes[ $key ] ) && isset( $attributes[ $key ]['template'] )  && $media_query === $attributes[ $key ]['media-query'] && $not_empty ) {
			$template = $attributes[ $key ];
			$template = $template['template'];
			$template = str_replace( '%val%', $value, $template );
			$out .= $template;
		}
	}
	if ( isset( $option['font-family'] ) || isset( $option['font-style'] ) ) {
		$out = bimber_bending_cat_render_font_css( $option, $out );
	}
	echo apply_filters( 'bimber_bending_cat_render_css_attributes', $out, $option );
}

/**
 * Render font sizes for buttons
 *
 * @param array $option  Option value.
 */
function bimber_bending_cat_render_button_font_sizes( $option ) {
		if ( isset( $option['font-size'] ) && 'g1-none' !== $option['font-size'] && ! empty( $option['font-size'] ) ) :
		?>
			<?php bimber_bending_cat_render_button_calculatd_font_sizes( $option['font-size'] )?>
		<?php
		endif;
		if ( isset( $option['font-size-tablet'] ) && 'g1-none' !== $option['font-size-tablet'] && ! empty( $option['font-size-tablet'] ) ) :
		?>
		@media only screen and (min-width: 768px) and (max-width: 1023px){
			<?php bimber_bending_cat_render_button_calculatd_font_sizes( $option['font-size-tablet'] )?>
		}
		<?php
		endif;
		if ( isset( $option['font-size-mobile'] ) && 'g1-none' !== $option['font-size-mobile'] && ! empty( $option['font-size-mobile'] ) ) :
		?>
		@media only screen and (max-width: 767px){
			<?php bimber_bending_cat_render_button_calculatd_font_sizes( $option['font-size-mobile'] )?>
		}
	<?php
	endif;
}

/**
 * Render calculated font sizes.
 *
 * @param [type] $font_size_m  Font size for m button.
 */
function bimber_bending_cat_render_button_calculatd_font_sizes( $font_size_m ) { ?>
	.g1-button-xs{
		font-size:<?php echo esc_attr( $font_size_m - 4 );?>px;
	}
	.g1-button-s{
		font-size:<?php echo esc_attr( $font_size_m - 2 );?>px;
	}
	.g1-button-m{
		font-size:<?php echo esc_attr( $font_size_m );?>px;
	}
	.g1-button-l{
		font-size:<?php echo esc_attr( $font_size_m + 2 );?>px;
	}
	.g1-button-xl{
		font-size:<?php echo esc_attr( $font_size_m + 4 );?>px;
	}
	<?php
}

/**
 * * Redner CSS for font family and style.
 *
 * @param array  $option  Array of attributes.
 * @param string $out    CSS string.
 * @return string
 */
function bimber_bending_cat_render_font_css( $option, $out ) {
	$font_family 		 = isset( $option['font-family'] ) ? $option['font-family'] : false;
	$font_style_setting	 = isset( $option['font-style'] ) ? $option['font-style'] : false;
	if ( $font_style_setting ) {
		$font_style = str_replace( 'regular', '400', $font_style_setting );
		if ( strpos( $font_style, 'italic') > -1 ) {
			$font_style = str_replace( 'italic', '', $font_style );
			$out .= 'font-style:italic;';
		}
		if ( ! empty( $font_style ) ) {
			$out .= 'font-weight:' . $font_style . ';';
		}
	}
	if ( $font_family ) {
		$default_fonts = bimber_bending_cat_get_default_fonts();
		if ( isset( $default_fonts['fonts'][ $font_family ] ) ) {
			$out .= 'font-family:' . $default_fonts['fonts'][ $font_family ]['css_value'] . ';';
		} else {
			$fonts = bimber_bending_cat_get_available_font_families( true );
			$out .= 'font-family:' . $fonts[ $font_family ]['css_value'] . ';';
			bimber_bending_cat_enqueued_google_fonts( $font_family, $font_style_setting );
		}
	}
	return $out;
}

/**
 * Add the font to the list of Google Fonts to be enqueued or get the list when run without optional arguments.
 *
 * @param mixed $font_family			Font family.
 * @param mixed $font_style_setting	    Font style.
 */
function bimber_bending_cat_enqueued_google_fonts( $font_family = false, $font_style_setting = false ) {
	static $fonts = array();

	// Return registered fonts.
	if ( ! $font_family ) {
		return $fonts;
	}

	// Register new font.
	$font_style_setting = str_replace( 'italic', 'i', (string) $font_style_setting );

	if ( ! isset( $fonts[ $font_family ] ) ) {
		$fonts[ $font_family ] = array();
	}

	if ( $font_style_setting ) {
		$fonts[ $font_family ][] = $font_style_setting;
	}
}

/**
 * Get fonts url.
 *
 * @return string
 */
function bimber_bending_cat_get_font_url() {
	$google_fonts = bimber_bending_cat_enqueued_google_fonts();

	if ( empty( $google_fonts ) ) {
		return false;
	}

	$family_arr = array();

	foreach ( $google_fonts as $font_family => $font_styles ) {
		if ( ! empty( $font_styles ) ) {
			$family_arr[] = sprintf( '%s:%s', $font_family, implode( ',', array_unique( $font_styles ) ) );
		} else {
			$family_arr[] = $font_family;
		}
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $family_arr ) ),
		'subset' => urlencode( bimber_get_google_font_subset() ),
	);

	$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

	return $fonts_url;
}

/**
 * Enqueue the fonts.
 */
function bimber_bending_cat_enqueue_fonts() {
	$url = bimber_bending_cat_get_font_url();
	$version = bimber_get_theme_version();
	if ( $url ) {
		wp_enqueue_style( 'bimber-google-fonts-customized', esc_url_raw( $url ), array(), $version );
	}
}

/**
 * Get typography customization options.
 *
 * @return array
 */
function bimber_bending_cat_get_typography_cutomization_options() {
	$options = array(
		'font-family' => array(
			'label'		=> _x( 'Font', 'font family', 'bimber' ),
			'type' 		=> 'font-picker',
			'default' 	=> '-1',
			'media-query'	=> 'all',
		),
		'font-style' => array(
			'label'		=> _x( 'Style', 'font style', 'bimber' ),
			'type' 		=> 'select',
			'choices'	=> array(
				'-1'	=> esc_html__( '', 'bimber' ),
			),
			'default'	=> '-1',
			'media-query'	=> 'all',
		),
		'font-size' => array(
			'label'		=> _x( 'Size', 'font size', 'bimber' ),
			'type' 		=> 'range',
			'min'  		=> 11,
			'max'  		=> 128,
			'step' 		=> 1,
			'default' 	=> false,
			'template'	=> 'font-size:%val%px;',
			'media-query'	=> 'desktop',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'line-height' => array(
			'label'		=> __( 'Line Height', 'bimber' ),
			'type' 		=> 'range',
			'min'  		=> 0.8,
			'max'  		=> 4,
			'step' 		=> 0.05,
			'default' 	=> false,
			'template'	=> 'line-height:%val%;',
			'media-query'	=> 'desktop',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'letter-spacing' => array(
			'label'		=> __( 'Letter Spacing', 'bimber' ),
			'type' 		=> 'select',
			'choices'	=> bimber_bending_cat_get_letter_spacing_choices(),
			'default' 	=> '-1',
			'template'	=> 'letter-spacing:%val%em;',
			'media-query'	=> 'all',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'text-transform' => array(
			'label'			=> __( 'Text Transform', 'bimber' ),
			'type' 			=> 'select',
			'choices'		=> array(
				'g1-none'		=> __( '- None -', 'bimber' ),
				'none'			=> _x( 'don\'t change', 'text transform',  'bimber' ),
				'uppercase'		=> _x( 'UPPERCASE', 'text transform', 'bimber' ),
				'lowercase'		=> _x( 'lowercase', 'text transform', 'bimber' ),
				'capitalize'	=> _x( 'Capitalize Each Word', 'text transform', 'bimber' ),
			),
			'default'		=> '-1',
			'template'		=> 'text-transform:%val%;',
			'media-query'	=> 'all',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'font-size-tablet' => array(
			'label'			=> _x( 'Size', 'font size', 'bimber' ),
			'type' 			=> 'range',
			'min'  			=> 11,
			'max'  			=> 128,
			'step' 			=> 1,
			'default' 		=> false,
			'template'		=> 'font-size:%val%px;',
			'media-query'	=> 'tablet',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'line-height-tablet' => array(
			'label'			=> __( 'Line Height', 'bimber' ),
			'type' 			=> 'range',
			'min'  			=> 0.8,
			'max'  			=> 4,
			'step' 			=> 0.1,
			'default' 		=> false,
			'template'		=> 'line-height:%val%;',
			'media-query'	=> 'tablet',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'font-size-mobile' => array(
			'label'			=> _x( 'Size', 'font size', 'bimber' ),
			'type' 			=> 'range',
			'min'  			=> 11,
			'max'  			=> 128,
			'step' 			=> 1,
			'default' 		=> false,
			'template'		=> 'font-size:%val%px;',
			'media-query'	=> 'mobile',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
		'line-height-mobile' => array(
			'label'			=> __( 'Line Height', 'bimber' ),
			'type' 			=> 'range',
			'min'  			=> 0.8,
			'max'  			=> 4,
			'step' 			=> 0.1,
			'default' 		=> false,
			'template'		=> 'line-height:%val%;',
			'media-query'	=> 'mobile',
			'input-class'	=> 'g1-typo-setting-half-width',
		),
	);
	return apply_filters( 'bimber_customizer_typography_cutomization_options', $options );
}

/**
 * Get letter spacing choices.
 *
 * @return array
 */
function bimber_bending_cat_get_letter_spacing_choices() {
	$choices = array(
		'g1-none' => esc_html__( '- None -', 'bimber' ),
		'-0.050'	=> '-50',
		'-0.025'    => '-25',
		'-0.010'	=> '-10',
		'-0.005'    => '-5',
		'0'		    => '0',
		'0.005'     => '5',
		'0.01'	    => '10',
		'0.025'	    => '25',
		'0.05'	    => '50',
		'0.075'	    => '75',
		'0.1'	    => '100',
		'0.15'	    => '150',
		'0.2'	    => '200',
		'0.25'	    => '250',
		'0.3'	    => '300',
		'0.4'	    => '400',
	);
	return apply_filters( 'bimber_bending_cat_letter_spacing_choices', $choices );
}

/**
 * Get avaiable font families (cached if possible)
 *
 * @param bool $unsorted  True - return unsorted fonts. False - return fonts in categories.
 * @return array
 */
function bimber_bending_cat_get_available_font_families( $unsorted = false ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';

	WP_Filesystem();

	/**
	 * Safe way to access filesystem
	 *
	 * @var WP_Filesystem_Base $wp_filesystem
	 */
	global $wp_filesystem;

	$content = $wp_filesystem->get_contents( BIMBER_INCLUDES_DIR . 'google-fonts-list.json' );

	$content = apply_filters( 'bimber_bending_cat_get_available_font_families', $content );
	$cached_fonts = json_decode( $content, true) ;
	if ( $cached_fonts ) {
		$fonts = $cached_fonts;
	} else {
		$fonts = bimber_bending_cat_capture_available_font_families();
	}
	if ( $unsorted ) {
		$unsorted_fonts = array();
		foreach ( $fonts as $index => $cat ) {
			$unsorted_fonts = array_merge( $unsorted_fonts, $cat['fonts'] );
		}
		$fonts = $unsorted_fonts;
	}
	return $fonts;
}

/**
 * Get avaiable font families (cached if possible)
 *
 * @return array
 */
function bimber_bending_cat_capture_available_font_families() {

	$fonts['default'] = bimber_bending_cat_get_default_fonts();
	$fonts['sans-serif'] = array(
		'label' => __( 'Sans Serif Google Fonts', 'bimber' ),
		'fonts' => array(),
	);
	$fonts['serif'] = array(
		'label' => __( 'Serif Google Fonts', 'bimber' ),
		'fonts' => array(),
	);
	$fonts['display'] = array(
		'label' => __( 'Display Google Fonts', 'bimber' ),
		'fonts' => array(),
	);
	$fonts['handwriting'] = array(
		'label' => __( 'Handwriting Google Fonts', 'bimber' ),
		'fonts' => array(),
	);
	$fonts['monospace'] = array(
		'label' => __( 'Monospace Google Fonts', 'bimber' ),
		'fonts' => array(),
	);

	$api_key = apply_filters( 'bimber_google_fonts_api_key', '' );
	$api_key = 'nope';
	$api_key = ! empty( $api_key ) ? '?key=' . $api_key : '';
	$response = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts' . $api_key, array( 'sslverify' => false ) );

	if ( ! is_wp_error( $response ) ) {
		$response = json_decode( $response['body'], true );

		if ( isset( $response['items'] ) ) {
			foreach ( $response['items'] as $key => $value ) {

				$type = str_replace(
					array(
						'display',
						'handwriting',
						'monospace',
					),
					array(
						'cursive',
						'cursive',
						'cursive',
					),
					$value['category']
				);
				$css_value = "'" . $value['family'] . "' ," . $type;

				$link_variants = implode( ',',$value['variants'] );
				$link_variants = str_replace( 'italic', 'i', $link_variants );
				$link_string = $value['family'] . ':' . $link_variants;
				$query_args = array(
					'family' => urlencode( $link_string ),
					'subset' => urlencode( bimber_get_google_font_subset() ),
				);
				$css_link = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

				$fonts[ $value['category'] ]['fonts'][ $value['family'] ] = array(
					'variants' => $value['variants'],
					'subsets' => $value['subsets'],
					'css_value' => $css_value,
					'css_link'	=> $css_link,
				);
			}
		}
	}

	// generate labels.
	foreach ( $fonts as $category => $category_fonts ) {
		foreach ( $fonts[ $category ]['fonts'] as $index => $font ) {
			$variant_names = array();
			foreach ( $fonts[ $category ]['fonts'][ $index ]['variants'] as $key => $variant_id ) {
				$variant_label = str_replace(
					array(
						'100',
						'200',
						'300',
						'400',
						'500',
						'600',
						'700',
						'800',
						'900',
						'italic',
						'regular',
					),
					array(
						_x( 'Thin',         'font option', 'bimber' ),
						_x( 'Extra Light',  'font option', 'bimber' ),
						_x( 'Light',        'font option', 'bimber' ),
						_x( 'Normal',       'font option', 'bimber' ),
						_x( 'Medium',       'font option', 'bimber' ),
						_x( 'Semi Bold',    'font option', 'bimber' ),
						_x( 'Bold',         'font option', 'bimber' ),
						_x( 'Extra Bold',   'font option', 'bimber' ),
						_x( 'Black',        'font option', 'bimber' ),
						_x( 'Italic',       'font option', 'bimber' ),
						_x( 'Regular',      'font option', 'bimber' ),
					),
				$variant_id );
				$variant_names[ $key ] = $variant_label;
			}
			$fonts[ $category ]['fonts'][ $index ]['variant_names'] = $variant_names;
		}
	}
	return $fonts;
}

/**
 * Get default web fonts.
 *
 * @return array
 */
function bimber_bending_cat_get_default_fonts() {
	$fonts = array(
		'label' => __( 'Default Web Fonts', 'bimber' ),
		'fonts' => array(
			'Arial'               => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Arial, Helvetica, sans-serif' ),
			'Century Gothic'      => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Century Gothic", sans-serif' ),
			'Courier New'         => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Courier New", Courier, monospace' ),
			'Georgia'             => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Georgia, serif' ),
			'Helvetica'           => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Helvetica Neue,Helvetica,Arial,sans-serif' ),
			'Impact'              => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Impact, Charcoal, sans-serif' ),
			'Lucida Console'      => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Lucida Console", Monaco, monospace' ),
			'Lucida Sans Unicode' => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ),
			'Palatino Linotype'   => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Palatino Linotype", "Book Antiqua", Palatino, serif' ),
			'sans-serif'          => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'sans-serif' ),
			'serif'               => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'serif' ),
			'Tahoma'              => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Tahoma, Geneva, sans-serif' ),
			'Trebuchet MS'        => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => '"Trebuchet MS", Helvetica, sans-serif' ),
			'Verdana'             => array( 'variants' => array( 'regular', 'italic', '700', '700italic' ), 'css_value' => 'Verdana, Geneva, sans-serif' ),
		),
	);
	return apply_filters( 'bimber_bending_cat_get_default_fonts', $fonts );
}
