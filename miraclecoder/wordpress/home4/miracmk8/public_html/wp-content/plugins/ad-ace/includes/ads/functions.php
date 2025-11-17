<?php
/**
 * Common Functions
 *
 * @package AdAce.
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Global access to ad slots via function, we don't need a class to do this
 *
 * @param mixed $new_value  (optional) Set new value.
 * @return array
 */
function adace_access_ad_slots( $new_value = false ) {
	static $adace_ad_slots = array();
	if ( is_array( $new_value ) ) {
		$adace_ad_slots = $new_value;
	}
	return $adace_ad_slots;
}

/**
 * Global access to ad sections via function, we don't need a class to do this
 *
 * @param mixed $new_value  (optional) Set new value.
 * @return array
 */
function adace_access_ad_sections( $new_value = false ) {
	static $adace_ad_sections = array();
	if ( is_array( $new_value ) ) {
		$adace_ad_sections = $new_value;
	}
	return $adace_ad_sections;
}

/**
 * Return ad slot defaults
 */
function adace_get_slot_default_args() {
	$slot_defaults = array(
		'id'      => '',
		'name'    => '',
		'desc'    => '',
		'section' => 'default',
		'is_repeater' => false,
		'options' => array(
			'is_home'                    => true,
			'is_home_editable'           => true,
			'is_search'                  => true,
			'is_search_editable'         => true,
			'is_singular'                => 'default',
			'is_singular_editable'       => true,
			'is_archive'                 => 'default',
			'is_archive_editable'        => true,
			'is_user_logged_in'          => true,
			'is_user_logged_in_editable' => true,
			'is_amp'                     => true,
			'is_amp_editable'            => true,
			'min_width'                  => 0,
			'min_width_editable'         => true,
			'max_width'                  => 0,
			'max_width_editable'         => true,
			'alignment'                  => 'none',
			'alignment_editable'         => true,
			'margin'                  => 0,
			'margin_editable'         => true,
			'callback'                   => '',
		),
		'custom_options' => array(),
	);
	return apply_filters( 'adace_slot_default_args', $slot_defaults );
}

/**
 * Return supported post types
 *
 * @return array of supported posts.
 */
function adace_get_supported_post_types() {
	$supported_post_types = array(
		'post' => esc_html__( 'Post', 'adace' ),
		'page' => esc_html__( 'Page', 'adace' ),
	);
	return apply_filters( 'adace_get_supported_post_types', $supported_post_types );
}

/**
 * Return supported taxonomies
 *
 * @return array of supported posts.
 */
function adace_get_supported_taxonomies() {
	$supported_taxonomies = array();
	foreach ( get_taxonomies() as $key => $value ) {
		$taxonomy = get_taxonomy( $key );
		if ( $taxonomy->publicly_queryable ) {
			$supported_taxonomies[ $taxonomy->name ] = $taxonomy->label;
		}
	};

	$supported_taxonomies['author_archive'] = 'Author archives';
	$supported_taxonomies['date_archive'] = 'Date archives';

	unset( $supported_taxonomies['wp_log_type'] );
	unset( $supported_taxonomies['product_shipping_class'] );

	asort( $supported_taxonomies );

	return apply_filters( 'adace_get_supported_taxonomies', $supported_taxonomies );
}

/**
 * Register ad slot.
 *
 * @param array $args Options for ad slot.
 * @return bool
 */
function adace_register_ad_slot( $args ) {
	// Check if args are provied, and at least id is given.
	if ( ! isset( $args ) || empty( $args ) || ! is_array( $args ) || ! isset( $args['id'] ) ) {
		return false;
	}

	$adace_ad_slots = adace_access_ad_slots();

	// Parse provided args (wp_parse_args is not recursive).
	$args_default = adace_get_slot_default_args();
	if ( isset( $args['options'] ) ) {
		$args['options'] = wp_parse_args( $args['options'], $args_default['options'] );
	}
	$args_checked = wp_parse_args( $args, $args_default );

	$adace_ad_slots[ $args_checked['id'] ] = $args_checked;

	adace_access_ad_slots( $adace_ad_slots );

	do_action( 'adace_slot_registered', $args );

	return true;
}

/**
 * Register new ad section
 *
 * @param string $section_slug  Slug.
 * @param string $section_label Label.
 */
function adace_register_ad_section( $section_slug, $section_label ) {

	$adace_ad_sections = adace_access_ad_sections();

	$adace_ad_sections[] = array(
		'slug'  => $section_slug,
		'label' => $section_label,
	);

	adace_access_ad_sections( $adace_ad_sections );
}

/**
 * Unregister ad slot.
 *
 * @param string $slot_id Id of ad slot.
 * @return bool
 */
function adace_unregister_ad_slot( $slot_id ) {
	// Check if id is provided and is string.
	if ( ! isset( $slot_id ) || empty( $slot_id ) || ! is_string( $slot_id ) ) {
		return false;
	}

	$adace_ad_slots = adace_access_ad_slots();

	// Check if slot is registered.
	if ( is_array( $adace_ad_slots ) && isset( $adace_ad_slots[ $slot_id ] ) ) {
		unset( $adace_ad_slots[ $slot_id ] );
		adace_access_ad_slots( $adace_ad_slots );
		return true;
	}
	return false;
}

add_action( 'parse_query', 'adace_allow_is_amp' );
/**
 * Enable the adace_is_amp function.
 */
function adace_allow_is_amp() {
	global $adace_is_amp_available;
	$adace_is_amp_available = true;
}

/**
 * Check wheter we're on AMP endpoint
 *
 * @return bool
 */
function adace_is_amp() {
	global $adace_is_amp_available;
	if ( true !== $adace_is_amp_available ) {
		return false;
	}
	if ( adace_can_use_plugin( 'amp/amp.php' ) ) {
		return is_amp_endpoint();
	} else {
		return false;
	}
}

/**
 * Check if slot have ad and if ad exist.
 *
 * @param string $slot_id Id of ad slot.
 * @return bool
 */
function adace_is_ad_slot( $slot_id ) {
	$result = true;
	// Check if id is provided and is string.
	if ( ! isset( $slot_id ) || empty( $slot_id ) || ! is_string( $slot_id ) ) {
		$result = false;
	}

	$adace_ad_slots = adace_access_ad_slots();

	// Check if any slots are registered.
	if ( ! is_array( $adace_ad_slots ) || ! isset( $adace_ad_slots[ $slot_id ] ) ) {
		$result = false;
	}

	// Get this slot options.
	$slot_options = get_option( 'adace_slot_' . $slot_id . '_options' );

	if ( adace_disable_ads_per_post( $slot_id ) ) {
		$result = false;
	}

	// Check if slot have ad assigned.
	if ( ! isset( $slot_options['ad_id'] ) || empty( $slot_options['ad_id'] ) ) {

		$result = false;
	} else {
		// Check if given ad exist.
		$is_random_or_cat = '-1' === $slot_options['ad_id'] || '-2' === $slot_options['ad_id'];
		if ( ! is_string( get_post_status( $slot_options['ad_id'] ) ) && ! $is_random_or_cat ) {
			$result = false;
		}
	}

	return apply_filters( 'adace_is_slot_active_filter', $result, $slot_id );
}

add_filter( 'adace_is_slot_active_filter', 'adace_slot_default_logic', 10, 2 );
/**
 * Default slot logic.
 *
 * @param bool   $slot_is_active slot active.
 * @param string $slot_id Id of ad slot.
 * @return bool
 */
function adace_slot_default_logic( $slot_is_active, $slot_id ) {
	// Get slot registered data.
	$adace_ad_slots = adace_access_ad_slots();
	$slot_register = $adace_ad_slots[ $slot_id ];

	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . $slot_id . '_options' );
	$defaults = adace_get_slot_default_args();
	$slot_options = wp_parse_args( $slot_options, $defaults['options'] );
	// Fill slot data.
	$slot_logic = array(
		'is_home' => ( $slot_register['options']['is_home_editable'] ? $slot_options['is_home'] : $slot_register['options']['is_home'] ),
		'is_search' => ( $slot_register['options']['is_search_editable'] ? $slot_options['is_search'] : $slot_register['options']['is_search'] ),
		'is_singular' => ( $slot_register['options']['is_singular_editable'] ? $slot_options['is_singular'] : $slot_register['options']['is_singular'] ),
		'is_archive' => ( $slot_register['options']['is_archive_editable'] ? $slot_options['is_archive'] : $slot_register['options']['is_archive'] ),
		'is_user_logged_in' => ( $slot_register['options']['is_user_logged_in_editable'] ? $slot_options['is_user_logged_in'] : $slot_register['options']['is_user_logged_in'] ),
		'is_amp' => ( $slot_register['options']['is_amp_editable'] ? $slot_options['is_amp'] : $slot_register['options']['is_amp'] ),
	);

	// Check if user is logged in and if slot should be displayed.
	if ( is_user_logged_in() && false === $slot_logic['is_user_logged_in'] ) {
		return false;
	}

	// Check if home being displayed in and if slot should be displayed.
    // is_front_page() used instead of is_home() because:
    // - if "Front page displays" is set to "A static page", this function will return true when viewing that page.
    // - if "Front page displays" is set to "Latest Posts", this function is the same as is_home()
	if ( is_front_page() && false === $slot_logic['is_home'] ) {
		return false;
	}

	// Check if search being displayed in and if slot should be displayed.
	if ( is_search() && false === $slot_logic['is_search'] ) {
		return false;
	}

	// Check if is singular and if show slot on this post_type. Check is_front_page() to as a homepage can be a single page as well.
	if ( ! is_front_page() && is_singular() && ( ! is_singular( $slot_logic['is_singular'] ) || empty( $slot_logic['is_singular'] ) ) ) {
		return false;
	}

	if ( is_archive() && ! is_front_page() ) {

		if ( is_author() && ! in_array( 'author_archive', (array) $slot_logic['is_archive'], true ) ) {
			return false;
		}

        if ( is_date() && ! in_array( 'date_archive', (array) $slot_logic['is_archive'], true ) ) {
            return false;
        }

		if ( ! is_tax( $slot_logic['is_archive'] ) && ! is_tag() && ! is_category() && ! is_author() && ! is_date() ) {
			return false;
		}

		if ( is_category() && ! in_array( 'category', (array) $slot_logic['is_archive'], true ) ) {
			return false;
		}

		if ( is_tag() && ! in_array( 'post_tag', (array) $slot_logic['is_archive'], true ) ) {
			return false;
		}
	}

	if ( is_404() ) {
		return false;
	}
	if ( is_feed() ) {
		return false;
	}

	if ( adace_is_amp() && ! $slot_logic['is_amp'] ) {
		return false;
	}
	return $slot_is_active;
}

/**
 * Whether to disable a given slot for a given post id
 *
 * @param str $slot_id   The slot id.
 * @return bool
 */
function adace_disable_ads_per_post( $slot_id ) {
	$disable = false;
	if ( is_singular() ) {
		global $post;
		$post_id = $post->ID;

		$disable_array = get_post_meta( $post_id, 'adace_disable', true );
		if ( is_array( $disable_array ) ) {
			$disable_ad_all_slots	= $disable_array['adace_disable_all_slots'];
			$disable_ad_slots  		= $disable_array['adace_disable_slots'];
			$disable_ad_widgets 	= $disable_array['adace_disable_widgets'];
			$disable_ad_shortcodes 	= $disable_array['adace_disable_shortcodes'];

			if ( strpos( $slot_id, 'shortcode' ) !== false ) {
				$disable = $disable_ad_shortcodes;
			} elseif (  strpos( $slot_id, 'widget' ) !== false ) {
				$disable = $disable_ad_widgets;
			} else {
				if ( isset( $disable_ad_slots[ $slot_id ] ) ) {
					$disable = $disable_ad_slots[ $slot_id ];
				}
				if ( $disable_ad_all_slots ) {
					$disable = true;
				}
			}
		}
	}
	return apply_filters( 'adace_disable_ads_per_post', $disable, $slot_id );
}


add_filter( 'adace_display_slot', 			'adace_global_ad_limit', 1, 1 );
add_filter( 'adace_display_shortcode', 		'adace_global_ad_limit', 1, 1 );
add_filter( 'adace_display_widget', 		'adace_global_ad_limit', 1, 1 );
/**
 * Increments the ad counter and return whether to display the ad
 *
 * @param bool $display Whether to display ad.
 * @return bool $display Whether to display ad.
 */
function adace_global_ad_limit( $display ) {
	static $initialized 	= false;
	static $ads_number 		= 0;
	static $global_limit 	= 0;

	// get the option only once.
	if ( false === $initialized ) {
		$global_limit = intval( get_option( 'adace_general_ad_limit', 0 ) );
		$initialized = true;
	}
	$ads_number += 1;
	if ( 0 === $global_limit ) {
		return $display;
	} else {
		return $ads_number <= $global_limit;
	}
}

/**
 * Get all ads
 *
 * @param mixed $group Optional ad group.
 * @return ads
 */
function adace_get_all_ads( $group = false ) {
	$args = array(
		'post_type'              => array( 'adace-ad' ),
		'post_status'            => 'publish',
		'posts_per_page'         => '-1',
		'orderby' 				 => 'menu_order date',
	);
	if ( $group ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'adace-ad-group',
				'field' => 'slug',
				'terms' => $group,
			),
			);
	}
	return get_posts( $args );
}

/**
 * Query ad from DB for access in template
 *
 * @param int $ad_id    Ad id.
 * @return bool  		True if ad exists.
 */
function adace_query_ad( $ad_id ) {
    // Run only if ad is published.
    if ( 'publish' !== get_post_status( $ad_id ) ) {
        return false;
    }

	$general = get_post_meta( $ad_id, 'adace_general', true );
	if ( ! is_array( $general ) ) {
		return false;
	}
	$type = $general['adace_ad_type'];

	if ( adace_is_amp() && $general['adace_disable_amp'] ) {
		return false;
	}
	$custom 	= get_post_meta( $ad_id, 'adace_custom', true );
	$adsense 	= get_post_meta( $ad_id, 'adace_adsense', true );
	$result = array(
		'ID' 					=> $ad_id,
		'type' 					=> $type,
		'disable_desktop'		=> $general['adace_disable_desktop'],
		'disable_landscape'		=> $general['adace_disable_landscape'],
		'disable_portrait'		=> $general['adace_disable_portrait'],
		'disable_phone'			=> $general['adace_disable_phone'],
		'disable_amp'			=> $general['adace_disable_amp'],
		'exclude_from_random'	=> $general['adace_exclude_from_random'],
		'custom' 				=> $custom,
		'adsense' 				=> $adsense,
	);
	$result = apply_filters( 'adace_pre_query_ad', $result, $ad_id );
	set_query_var( 'adace_query_ad', $result );
	return true;
}

/**
 * Retrieve currently queried ad
 *
 * @return mixed  False if no ad queried or array of settings.
 */
function adace_ad_get_query() {
	return get_query_var( 'adace_query_ad' );
}

/**
 * Query slot for access in template
 *
 * @param int $slot_id    Slot id.
 * @param int    $occurrence  Optional occurence for repeaters.
 * @return bool  		True if slot exists.
 */
function adace_query_slot( $slot_id, $occurrence = 1 ) {

	if ( strpos( $slot_id, 'shortcode' ) !== false || strpos( $slot_id, 'widget' ) !== false ) {
		$result = array(
			'slot_id' => $slot_id,
		);
		$result = apply_filters( 'adace_pre_query_slot', $result, $slot_id );
		set_query_var( 'adace_query_slot', $result );
		return true;
	}

	$adace_ad_slots = adace_access_ad_slots();
	if ( ! isset( $adace_ad_slots[ $slot_id ] ) ) {
		return false;
	}


	$slot_register = $adace_ad_slots[ $slot_id ];
	$slot_options = get_option( 'adace_slot_' . $slot_id . '_options' );

	$slot_options['ad_id_amp'] = isset( $slot_options['ad_id_amp'] ) ? $slot_options['ad_id_amp'] : '-3';
	$slot_options['ad_id_mobile'] = isset( $slot_options['ad_id_mobile'] ) ? $slot_options['ad_id_mobile'] : '-3';
	$slot_options['ad_id_tablet'] = isset( $slot_options['ad_id_tablet'] ) ? $slot_options['ad_id_tablet'] : '-3';

	$ad_id = $slot_options['ad_id'];

	// Handle AMP override.
	if ( adace_is_amp() && '-3' !== $slot_options['ad_id_amp'] && '' !== $slot_options['ad_id_amp'] ) {
		$ad_id = $slot_options['ad_id_amp'];
	}

	// Handle randomization.
	if ( '-1' === $ad_id ) {
		if ( ! empty( $slot_options['ad_group'] ) ) {
			$ads = adace_get_all_ads( $slot_options['ad_group'] );
		} else {
			$ads = adace_get_all_ads( );
		}
		if ( ! empty( $ads ) ) {
			$ads = array_filter( $ads, function( $ad ) {
				$general_settings = get_post_meta( $ad->ID, 'adace_general', true );
				return ! $general_settings['adace_exclude_from_random'];
			});
			if ( true === $slot_options['no_repeat'] ) {
				$ads_count = count( $ads );
				if ( $occurrence > $ads_count ) {
					return false;
				}
				// We shuffle the array using the time and slot name. Time is rounded to 15min to avoid repetition over multiple requests in collections.
				$radnomization_timeout = apply_filters( 'adace_randomization_timeout', 900 );
				$seed_time = intval( time() / $radnomization_timeout );
				$seed = $slot_id . $seed_time;
				$ads = adace_seed_shuffle_array( $ads, $seed );
				$ad = $ads[ $occurrence - 1 ];
				$ad_id = $ad->ID;
			} else {
				$ad = $ads[ array_rand( $ads ) ];
				$ad_id = $ad->ID;
			}
		}
	}

	// Handle group.
	if ( '-2' === $ad_id ) {
		if ( ! empty( $slot_options['ad_group'] ) ) {
			$ads = adace_get_all_ads( $slot_options['ad_group'] );
		} else {
			$ads = adace_get_all_ads();
		}
		if ( ! empty( $ads ) ) {
			$ads_count = count( $ads );
			if ( $occurrence > $ads_count && true === $slot_options['no_repeat'] ) {
				return false;
			}
			if ( $occurrence > $ads_count && false === $slot_options['no_repeat'] ) {
				$occurrence = $occurrence % $ads_count;
			}
			$occurrence = $occurrence -1;
			$occurrence = 0 > $occurrence ? count( $ads ) - 1 : $occurrence;
			$ad = $ads[ $occurrence ];
			$ad_id = $ad->ID;
		}
	}

	$result = array(
		'slot_id' 		=> $slot_id,
		'ad_id' 		=> $ad_id,
		'ad_id_mobile' 	=> $slot_options['ad_id_mobile'],
		'ad_id_tablet' 	=> $slot_options['ad_id_tablet'],
		'ad_id_amp' 	=> $slot_options['ad_id_amp'],
		'min_width' 	=> ( $slot_register['options']['min_width_editable'] ? $slot_options['min_width'] : $slot_register['options']['min_width'] ),
		'max_width' 	=> ( $slot_register['options']['max_width_editable'] ? $slot_options['max_width'] : $slot_register['options']['max_width'] ),
		'alignment' 	=> ( $slot_register['options']['alignment_editable'] ? $slot_options['alignment'] : $slot_register['options']['alignment'] ),
		'margin' 		=> ( $slot_register['options']['margin_editable'] ? $slot_options['margin'] : $slot_register['options']['margin'] ),
	);

	$result = apply_filters( 'adace_pre_query_slot', $result, $slot_id );
	if ( ! adace_query_ad( $result['ad_id'] ) ) {
		return false;
	};
	set_query_var( 'adace_query_slot', $result );
	return true;
}

/**
 * Retrieve currently queried slot
 *
 * @return mixed  False if no slot queried or array of settings.
 */
function adace_slot_get_query() {
	return get_query_var( 'adace_query_slot' );
}

/**
 * Get ad slot
 *
 * @param string $slot_id Id of ad slot.
 * @param int    $occurrence  Optional occurence for repeaters.
 * @return string  Slot output.
 */
function adace_get_ad_slot( $slot_id, $occurrence = 1 ) {

	if ( ! adace_is_ad_slot( $slot_id ) ) { return ''; }

	if ( ! apply_filters( 'adace_display_slot',  true, $slot_id ) ) {
		return;
	}

	$adace_ad_slots = adace_access_ad_slots();
	$slot_register = $adace_ad_slots[ $slot_id ];

	if ( ! adace_query_slot( $slot_id, $occurrence ) ) {
		return '';
	}

	// Check if slot have callback, if not go with default.
	if ( ! empty( $slot_register['options']['callback'] ) ) {
		$output = call_user_func( $slot_register['options']['callback'] );
	} else {
		ob_start();
		adace_get_template_part( 'content', 'slot' );
		$output = ob_get_clean();
	}
	return apply_filters( 'adace_get_ad_slot_output_filter', $output, $slot_id );
}

/**
 * Get HTML for standard ad template
 *
 * @param int   $ad_id  	    Ad ID.
 * @param str   $slot_id 	Slot id added as a class to the wrapper.
 * @param array $args	 	Optional args to pass to the template.
 * @return str
 */
function adace_capture_ad_standard_template( $ad_id, $slot_id, $args = array() ) {

	if ( ! adace_query_slot( $slot_id ) ) {
		return '';
	}
	if ( ! adace_query_ad( $ad_id ) ) {
		return '';
	}

	global $adace_standard_template_args;
	$adace_standard_template_args = $args;

	ob_start();
	adace_get_template_part( 'content', 'ad' );
	return ob_get_clean();
}

/**
 * @param array $ad_config  Ad config.
 *
 * @return array            Array of Link URL and CSS class.
 */
function adace_get_ad_link( $ad_config ) {
    $link = array(
        'url'       => '',
        'css_class' => '',
    );

    // Default value. Backward compatibility.
    if ( ! isset( $ad_config['adace_ad_link_type'] ) ) {
        $ad_config['adace_ad_link_type'] = '';
    }

    switch ( $ad_config['adace_ad_link_type'] ) {
        // Login Page.
        case 'login':
            $link['url'] = wp_login_url();
            break;

        // Custom Link.
        case '':
            $link['url'] = $ad_config['adace_ad_link'];
            break;
    }

    return apply_filters( 'adace_ad_link', $link, $ad_config['adace_ad_link_type'] );
}

/**
 * Get standardized ad output from queried ad
 */
function adace_render_custom_ad() {
	$ad = adace_ad_get_query();
	$custom = $ad['custom'];
	if ( ! $ad || ! is_array( $custom ) ) {
		return;
	}

	$ad_link = adace_get_ad_link( $custom );

	$ad_classes = array(
		'adace_ad_' . uniqid(),
	);

	$ad_classes = array_merge( $ad_classes, adace_generate_visibillity_classes( $ad ) );

	if ( empty( $custom['adace_ad_content'] ) ) {
		$ad_classes[] = 'adace-dont-remove';
	}

	$css = apply_filters( 'adace_custom_css', '' );
	ob_start();
	echo $css;
	?>
	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $ad_classes ) ); ?>">

		<?php if ( ! empty( $ad_link['url'] ) ) : ?>
			<a href="<?php echo esc_url( $ad_link['url'] ); ?>" class="<?php echo sanitize_html_class( $ad_link['css_class'] ) ?>" target="_blank" rel="nofollow noopener">
		<?php endif; ?>

			<?php
				if ( ! empty( $custom['adace_ad_image'] ) ){
					$ad_image = wp_get_attachment_image_src( $custom['adace_ad_image'], 'full' );

					if ( ! empty( $ad_image ) ) {

						// Default non-retina img.
						$img = sprintf(
							'<img src="%1$s" width="%2$d" height="%3$d" alt="" />',
							esc_url( $ad_image[0] ),
							$ad_image[1],
							$ad_image[2]
						);

						// Handle retina.
						if ( ! empty( $custom['adace_ad_image_retina'] ) ) {
							$ad_retina_image = wp_get_attachment_image_src( $custom['adace_ad_image_retina'], 'full' );

							if ( ! empty( $ad_retina_image ) ) {

								$img = sprintf(
									'<img src="%1$s" srcset="%2$s 2x, %1$s 1x" width="%3$d" height="%4$d" alt="" />',
									esc_url( $ad_image[0] ),
									esc_url( $ad_retina_image[0] ),
									$ad_image[1],
									$ad_image[2]
								);
							}
						}

						if ( function_exists( 'mace_get_lazy_load_images' ) && mace_get_lazy_load_images() ) {
							$img = mace_lazy_load_content_image( $img );
						}

						echo $img;
					}
				}
			?>

			<?php echo html_entity_decode( $custom['adace_ad_content'], ENT_QUOTES ); ?>

		<?php if ( ! empty( $ad_link ) ) : ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
	$html = ob_get_clean();

 	$async_ad = isset( $custom['adace_ad_content_async'] ) && ( 'standard' === $custom['adace_ad_content_async'] );

	$html = apply_filters( 'adace_custom_output', $html, $async_ad );

	if ( apply_filters( 'adace_custom_ad_allow_shortcodes', true ) ) {
		echo do_shortcode( $html );
	} else {
		echo $html;
	}
}

/**
 * Get standardized adsense output from queried ad
 */
function adace_render_adsense_ad() {
	if ( adace_is_amp() ) {
		adace_amp_render_adsense();
		return;
	}

	$ad = adace_ad_get_query();
	$adsense = $ad['adsense'];
	if ( ! $ad || ! is_array( $adsense ) ) {
		return;
	}
	$pub 	= $adsense['adace_adsense_pub'];
	$unit 	= $adsense['adace_adsense_slot'];
	$type 	= $adsense['adace_adsense_type'];
	$format = $adsense['adace_adsense_format'];
	$width 	= (int) $adsense['adace_adsense_width'];
	$height	= (int) $adsense['adace_adsense_height'];

	$ad_class = 'adace_adsense_' . uniqid();

	$visibility_classes = adace_generate_visibillity_classes( $ad );

	if ( ! empty( $visibility_classes ) ) {
		$ad_class .= ' ';
		$ad_class .= implode( ' ', $visibility_classes );
	}

	$css = adace_generate_adsense_css( $adsense, $ad_class );

	$adsense_before = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>' . $css;
	$adsense_before = apply_filters( 'adace_adsense_before', $adsense_before );

	$adsense_after 	= '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
	$adsense_after 	= apply_filters( 'adace_adsense_after', $adsense_after );

	$style = 'display:block;';

	if ( 'fixed' === $type && $width > 0 && $height > 0 ) {
		$style .= 'width:' . $width . 'px;height:' . $height . 'px;';
		$format = '';
	} else {
		$format = 'data-ad-format="' . $format . '"';
	}

	$style = apply_filters( 'adace_adsense_style', $style );
	if ( defined( 'BTP_DEV' ) && BTP_DEV ) {
		$ins = '
		<ins class="adsbygoogle"
		 data-ad-test="on"
		 style="%s"
		 data-ad-client="%s"
		 data-ad-slot="%s"
		 %s
		 ></ins>
		 ';
	} else {
		$ins = '
		<ins class="adsbygoogle"
		style="%s"
		data-ad-client="%s"
		data-ad-slot="%s"
		%s
		></ins>
		';
	}

	$ins = sprintf( $ins, $style, $pub, $unit, $format );

	$html =
        '<div class="' . $ad_class . '">' .
            $adsense_before .
                $ins .
            $adsense_after .
        '</div>';

	$async_ad = true;

	$html = apply_filters( 'adace_adsense_output', $html, $async_ad );
	echo $html;
}

add_filter( 'adace_custom_output', 'adace_wrap_in_loader', 10, 2 );
add_filter( 'adace_adsense_output', 'adace_wrap_in_loader', 10, 2 );

/**
 * Wrap the ad in the loader script.
 *
 * @param string $html          Ad html.
 * @param bool   $async_ad      Can ad be loaded in async manner?
 *
 * @return string
 */
function adace_wrap_in_loader( $html, $async_ad = false ) {
	if ( ! $async_ad ) {
		return $html;
	}

	if( strpos( $html, 'adace-dont-remove' ) ) {
		return $html;
	}
	$content = rawurlencode( $html );
	$default_breakpoints = adace_general_get_default_breakepoints();
	$tablet_start = (int) get_option( 'adace_general_portrait_breakpoint', $default_breakpoints['adace_general_portrait_breakpoint'] );
	$landscape_start = (int) get_option( 'adace_general_landscape_breakpoint', $default_breakpoints['adace_general_landscape_breakpoint'] );
	$tablet_end = (int) get_option( 'adace_general_desktop_breakpoint', $default_breakpoints['adace_general_desktop_breakpoint'] );
	$unique_id = uniqid();
	ob_start();?>
	<div class="adace-loader adace-loader-<?php echo esc_attr( $unique_id );?>">
		<script>
			(function ($) {
			var $self = $('.adace-loader-<?php echo esc_attr( $unique_id );?>');
			var $wrapper = $self.closest('.adace-slot-wrapper');

			"use strict";
			var adace_load_<?php echo esc_attr( $unique_id );?> = function(){
				var viewport = $(window).width();
				var tabletStart = <?php echo esc_attr( $tablet_start );?>;
				var landscapeStart = <?php echo esc_attr( $landscape_start );?>;
				var tabletEnd = <?php echo esc_attr( $tablet_end );?>;
				var content = '<?php echo esc_attr( $content );?>';
				var unpack = true;
				if(viewport<tabletStart){
					<?php
					if( strpos( $html, 'adace-hide-on-mobile' ) ) {
						echo 'unpack = false;';
					}
					?>
					if ($wrapper.hasClass('.adace-hide-on-mobile')){
						$wrapper.remove();
					}
				}
				if(viewport>=tabletStart && viewport<landscapeStart){
					<?php
					if( strpos( $html, 'adace-hide-on-portrait' ) ) {
						echo 'unpack = false;';
					}
					?>
					if ($wrapper.hasClass('.adace-hide-on-portrait')){
						$wrapper.remove();
					}
				}
				if(viewport>=landscapeStart && viewport<tabletEnd){
					<?php
					if( strpos( $html, 'adace-hide-on-landscape' ) ) {
						echo 'unpack = false;';
					}
					?>
					if ($wrapper.hasClass('.adace-hide-on-landscape')){
						$wrapper.remove();
					}
				}
				if(viewport>=tabletStart && viewport<tabletEnd){
					<?php
					if( strpos( $html, 'adace-hide-on-tablet' ) ) {
						echo 'unpack = false;';
					}
					?>
					if ($wrapper.hasClass('.adace-hide-on-tablet')){
						$wrapper.remove();
					}
				}
				if(viewport>=tabletEnd){
					<?php
					if( strpos( $html, 'adace-hide-on-desktop' ) ) {
						echo 'unpack = false;';
					}
					?>
					if ($wrapper.hasClass('.adace-hide-on-desktop')){
						$wrapper.remove();
					}
				}
				if(unpack) {
					$self.replaceWith(decodeURIComponent(content));
				}
			}
			if($wrapper.css('visibility') === 'visible' ) {
				adace_load_<?php echo esc_attr( $unique_id );?>();
			} else {
				//fire when visible.
				var refreshIntervalId = setInterval(function(){
					if($wrapper.css('visibility') === 'visible' ) {
						adace_load_<?php echo esc_attr( $unique_id );?>();
						clearInterval(refreshIntervalId);
					}
				}, 999);
			}


			})(jQuery);
		</script>
	</div>
	<?php
	$html = ob_get_clean();
	return $html;
}

/**
 * Create CSS for adsense
 *
 * @param array $args  Adsense options.
 * @param str   $class CSS class to use.
 * @return str
 */
function adace_generate_adsense_css( $args, $class ) {
	$breakpoints = array( 'phone', 'portrait' , 'landscape', 'desktop' );
	$result = "\n<style>\n";
	$n = 0;
	foreach ( $breakpoints as $breakpoint ) {
		$use 	= $args[ 'adace_adsense_use_size_' . $breakpoint ];
		$width 	= $args[ 'adace_adsense_width_' . $breakpoint ];
		$height = $args[ 'adace_adsense_height_' . $breakpoint ];
		if ( $use && $width > 0 && $height > 0 ) {
			$css = sprintf( '.%s {width:%spx; height:%spx}', $class, $width, $height );
			$result .= adace_create_media_query( $breakpoint, $css );
			$n += 1;
		} else {
			$width 	= $args['adace_adsense_width'];
			$height = $args['adace_adsense_height'];
			$css = sprintf( '.%s {width:%spx; height:%spx}', $class, $width, $height );
			$result .= adace_create_media_query( $breakpoint, $css );
		}
	}
	$result .= '</style>';
	if ( 0 === $n ) {
		return '';
	}
	return $result;
}

/**
 * Create visibility classes
 *
 * @param array $args  Ad options.
 *
 * @return array
 */
function adace_generate_visibillity_classes( $args ) {
	$breakpoints = array( 'phone', 'portrait' , 'landscape', 'desktop' );

	$classes = array();

	foreach ( $breakpoints as $breakpoint ) {
		$disable = $args[ 'disable_' . $breakpoint ];

		if ( $disable ) {
			$classes[] = 'adace-hide-on-' . $breakpoint;
		}
	}

	return $classes;
}

/**
 * Generate media queries for set breakpoints
 *
 * @param  str $breakpoint  Breakpoint name.
 * @param  str $contents CSS code to use.
 * @return str
 */
function adace_create_media_query( $breakpoint, $contents ) {
	$default_breakpoints = adace_general_get_default_breakepoints();
	if ( 'phone' === $breakpoint ) {
		$option_name = 'adace_general_portrait_breakpoint';
		$max_width = (int) get_option( $option_name, $default_breakpoints[ $option_name ] );
		$max_width = $max_width - 1;
		$query = '@media(max-width: ' . $max_width . 'px) {' . $contents . "}\n";
	} else {
		$option_name = 'adace_general_' . $breakpoint . '_breakpoint';
		$min_width = (int) get_option( $option_name, $default_breakpoints[ $option_name ] );
		$query = '@media(min-width: ' . $min_width . 'px) {' . $contents . "}\n";
	}
	return $query;
}

add_action( 'wp_footer', 'adace_render_override_breakpoints_styles' );
/**
 * Render style and script for per device disables.
 */
function adace_render_override_breakpoints_styles() {
	$default_breakpoints = adace_general_get_default_breakepoints();
	$tablet_start = (int) get_option( 'adace_general_portrait_breakpoint', $default_breakpoints['adace_general_portrait_breakpoint'] );
	$landscape_start = (int) get_option( 'adace_general_landscape_breakpoint', $default_breakpoints['adace_general_landscape_breakpoint'] );
	$tablet_end = (int) get_option( 'adace_general_desktop_breakpoint', $default_breakpoints['adace_general_desktop_breakpoint'] );?>
	<style type="text/css">
		@media only screen and (max-width: <?php echo esc_attr( $tablet_start - 1 );?>px ) {
			.adace-hide-on-mobile{
				display:none !important;
			}
			.adace-hide-on-phone{
				display:none !important;
			}
		}
		@media only screen and (min-width: <?php echo esc_attr( $tablet_start );?>px  ) and  (max-width: <?php echo esc_attr( $landscape_start - 1 );?>px ){
			.adace-hide-on-portrait{
				display:none !important;
			}
		}
		@media only screen and (min-width: <?php echo esc_attr( $landscape_start );?>px  ) and  (max-width: <?php echo esc_attr( $tablet_end - 1 );?>px ){
			.adace-hide-on-landscape{
				display:none !important;
			}
		}
		@media only screen and (min-width: <?php echo esc_attr( $tablet_start );?>px  ) and  (max-width: <?php echo esc_attr( $tablet_end - 1 );?>px ){
			.adace-hide-on-tablet{
				display:none !important;
			}
		}
		@media only screen and (min-width: <?php echo esc_attr( $tablet_end );?>px  ){
			.adace-hide-on-desktop{
				display:none !important;
			}
		}
	</style>
	<?php
}
