<?php
/**
 * Easy Social Share Buttons Init
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


/**
 * Initial setup
 */
function bimber_essb_run_init_setup() {
	$options = get_option( ESSB3_OPTIONS_NAME );

	$options = bimber_essb_defaults( $options );
	$options = bimber_essb_setup_networks( $options );
	$options = bimber_essb_register_supported_post_types( $options );
	$options = bimber_essb_setup_bimber_positions( $options );
	$options = bimber_essb_activate_bimber_positions( $options );
	$options = bimber_essb_setup_fake_shares( $options );
	$options = bimber_essb_setup_sharebar( $options );

	update_option( ESSB3_OPTIONS_NAME, $options );
}

/**
 * Default setup
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_defaults( $options ) {
	$options['live_customizer_disabled'] = true;

	return $options;
}

/**
 * Default setup for the Bimber positions
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_setup_networks( $options ) {
	$is_new_installation = $options['networks'] === array( 'facebook', 'twitter', 'google', 'pinterest', 'linkedin' );

	// Set up networks only if user hasn't changed them (we can assume it's new installation).
	if ( ! $is_new_installation ) {
		return $options;
	}

	$mashsb_settings = get_option( 'mashsb_settings', array() );

	$show_more_button_after_networks = isset( $mashsb_settings['visible_services'] ) ? (int) $mashsb_settings['visible_services'] : -1;

	$mashsb_networks = isset( $mashsb_settings['networks'] ) ? $mashsb_settings['networks'] : array();
	$essb_networks = array();

	foreach( $mashsb_networks as $mashsb_network_index => $mashsb_network_config  ) {
		if ( ! isset( $mashsb_network_config['status'] ) || ! $mashsb_network_config['status'] ) {
			continue;
		}

		$network_id    = $mashsb_network_config['id'];
		$network_label = $mashsb_network_config['name'];

		// Map to ESSB network id.
		if ( 'subscribe' === $network_id ) {
			$network_id = 'mail';
		}

		$essb_networks[] = $network_id;

		if ( $mashsb_network_index === $show_more_button_after_networks  ) {
			$essb_networks[] = 'more';
		}

		// Set label.
		if ( ! empty( $network_label ) ) {
			$options[ 'user_network_name_' . $network_id ] = $network_label;
		}
	}

	if ( ! empty( $essb_networks ) ) {
		$options['networks'] = $essb_networks;
	} else {
		// Load defaults.
		$options['networks'] = array(
			'facebook',
			'twitter',
			'more',
			'mail',
			'pinterest'
		);
	}

	// More button.
	if ( -1 !== $show_more_button_after_networks ) {
		$options['more_button_func'] = 1;
		$options['more_button_icon'] = 'dots';
	}

	return $options;
}

/**
 * Add support for custom post types
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_register_supported_post_types( $options ) {
	if ( ! isset( $options['display_in_types'] ) || ! is_array( $options['display_in_types'] ) ) {
		$options['display_in_types'] = array();
	}

	$options['display_in_types'][] = 'snax_quiz';
	$options['display_in_types'][] = 'snax_poll';
	$options['display_in_types'][] = 'snax_item';

	return $options;
}

/**
 * Default setup for the Bimber positions
 *
 * @param array $options        ESSB options.
 *
 * @return array
 */
function bimber_essb_setup_bimber_positions( $options ) {
	$positions_options =  array(
		// Top.
		'bimber_top_activate'                           => true,    // Personalize the positions.
		'bimber_top_template'                           => 7,
		'bimber_top_button_style'                       => 'button',
		'bimber_top_button_size'                        => 'xl',
		'bimber_top_counter_pos'                        => 'hidden',
		'bimber_top_total_counter_pos'                  => 'leftbig',
		'bimber_top_fullwidth_align'                    => 'left',
		'bimber_top_fullwidth_share_buttons_columns'    => 1,
		'bimber_top_more_button_func'                   => 1,
		'bimber_top_share_button_func'                  => 1,
		'bimber_top_share_button_counter'               => 'hidden',
		'bimber_top_button_pos'                         => 'center',
		'bimber_top_show_counter'                       => true,
		'bimber_top_button_width'                       => 'flex',

		// Bottom.
		'bimber_bottom_activate'                           => true,    // Personalize the positions.
		'bimber_bottom_template'                           => 7,
		'bimber_bottom_button_style'                       => 'button',
		'bimber_bottom_button_size'                        => 'xl',
		'bimber_bottom_counter_pos'                        => 'hidden',
		'bimber_bottom_total_counter_pos'                  => 'leftbig',
		'bimber_bottom_fullwidth_align'                    => 'left',
		'bimber_bottom_fullwidth_share_buttons_columns'    => 1,
		'bimber_bottom_more_button_func'                   => 1,
		'bimber_bottom_share_button_func'                  => 1,
		'bimber_bottom_share_button_counter'               => 'hidden',
		'bimber_bottom_button_pos'                         => 'center',
		'bimber_bottom_show_counter'                       => true,
		'bimber_bottom_button_width'                       => 'flex',

		// Sharebar.
		'topbar_activate'                           => true,    // Personalize the positions.
		'topbar_template'                           => 7,
		'topbar_button_style'                       => 'button',
		'topbar_button_size'                        => 'xl',
		'topbar_counter_pos'                        => 'hidden',
		'topbar_total_counter_pos'                  => 'leftbig',
		'topbar_fullwidth_align'                    => 'left',
		'topbar_fullwidth_share_buttons_columns'    => 1,
		'topbar_more_button_func'                   => 1,
		'topbar_share_button_func'                  => 1,
		'topbar_share_button_counter'               => 'hidden',
		'topbar_button_pos'                         => 'center',
		'topbar_show_counter'                       => true,
		'topbar_button_width'                       => 'flex',
		'topbar_top_loggedin'                       => 32,
		'topbar_maxwidth'                           => 1152,

		// Side.
		'bimber_side_activate'                      => true,
	    'bimber_side_template'                      => 7,
	    'bimber_side_button_style'                  => 'icon',
	    'bimber_side_button_size'                   => 'xxl',
	    'bimber_side_show_counter'                  => true,
	    'bimber_side_counter_pos'                   => 'hidden',
	    'bimber_side_total_counter_pos'             => 'before',
	    'bimber_side_fullwidth_align'               => 'left',
	    'bimber_side_fullwidth_share_buttons_columns' => 1,
	    'bimber_side_more_button_func'              => 1,
	    'bimber_side_share_button_func'             => 1,
	    'bimber_side_share_button_counter'          => 'hidden',
        'bimber_side_button_pos'                    => 'center',

		// Mini.
		'bimber_mini_activate'                      => true,
	    'bimber_mini_template'                      => 7,
	    'bimber_mini_button_style'                  => 'icon',
	    'bimber_mini_button_pos'                    => 'right',
	    'bimber_mini_button_size'                   => 'm',
	    'bimber_mini_counter_pos'                   => 'hidden',
	    'bimber_mini_total_counter_pos'             => 'hidden',
	    'bimber_mini_fullwidth_align'               => 'left',
	    'bimber_mini_fullwidth_share_buttons_columns' => 1,
	    'bimber_mini_more_button_func'              => 1,
	    'bimber_mini_share_button_func'             => 1,
	    'bimber_mini_share_button_counter'          => 'hidden',
	    'bimber_mini_nospace'                       => true,
	    'bimber_mini_networks' => array(
			'facebook',
	        'twitter',
	    ),

		// Compact.
		'bimber_compact_activate'                   => true,
		'bimber_compact_template'                   => 7,
		'bimber_compact_button_style'               => 'icon',
		'bimber_compact_button_pos'                 => 'right',
		'bimber_compact_button_size'                => 'm',
		'bimber_compact_counter_pos'                => 'hidden',
		'bimber_compact_total_counter_pos'          => 'hidden',
		'bimber_compact_fullwidth_align'            => 'left',
		'bimber_compact_fullwidth_share_buttons_columns' => 1,
		'bimber_compact_more_button_func'           => 1,
		'bimber_compact_share_button_func'          => 1,
		'bimber_compact_share_button_counter'       => 'hidden',
		'bimber_compact_nospace'                    => true,
		'bimber_compact_networks' => array(
			'facebook',
			'twitter',
		),
	);

	// Reset positions.
	foreach( $positions_options as $option_id => $option_value ) {
		$options[ $option_id ] = $option_value;
	}

	return $options;
}

/**
 * Activate Bimber positions
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_activate_bimber_positions( $options ) {
	$mashsb_settings = get_option( 'mashsb_settings', array() );

	$mashsb_buttons_position = isset( $mashsb_settings['mashsharer_position'] ) ? $mashsb_settings['mashsharer_position'] : 'both';

	if ( ! isset( $options['button_position'] ) || ! is_array( $options['button_position'] ) ) {
		$options['button_position'] = array();
	}

	// Activate the "Top" position.
	if ( in_array( $mashsb_buttons_position, array( 'before', 'both' ) ) ) {
		$options['button_position'][] = 'bimber_top';
	}

	// Activate the "Bottom" position.
	if ( in_array( $mashsb_buttons_position, array( 'after', 'both' ) ) ) {
		$options['button_position'][] = 'bimber_bottom';
	}

	// Disable the "Primary Content Display Position".
	if ( ! empty( $options['button_position'] ) ) {
		$options['content_position'] = 'content_manual';
	}

	// Activate other positions.
	$options['button_position'][] = 'bimber_compact';
	$options['button_position'][] = 'bimber_mini';
	$options['button_position'][] = 'bimber_side';

	return $options;
}

/**
 * Set up fake shares
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_setup_fake_shares( $options ) {
	$mashsb_settings = get_option( 'mashsb_settings', array() );

	$mashsb_fake_count      = isset( $mashsb_settings['fake_count'] ) ? $mashsb_settings['fake_count'] : 0;
	$mashsb_hide_sharecount = isset( $mashsb_settings['hide_sharecount'] ) ? $mashsb_settings['hide_sharecount'] : 0;

	// Activate the "Fake Share Counter" feature.
	$options['activate_fake'] = true;

	// Enable the "Avoid Social Negative Proof" option.
	$options['social_proof_enable'] = true;

	// Set threshold.
	if ( empty( $options['total_counter_hidden_till'] ) ) {
		if ( $mashsb_hide_sharecount > 0 ) {
			// Based on Mashshare.
			$options['total_counter_hidden_till'] = $mashsb_hide_sharecount;
		} else {
			// Default.
			$options['total_counter_hidden_till'] = 1;
		}
	}

	// Set up fake shares.
	if ( $mashsb_fake_count > 0 ) {
		$fake = get_option( 'essb-fake' );

		// If Facebook fake counter is not in use, store Mashshare fakes in it.
		if ( empty( $fake['fake_facebook'] ) ) {
			$fake['fake_facebook'] = $mashsb_fake_count;
		}

		update_option( 'essb-fake', $fake );
	}

	return $options;
}

/**
 * Set up sharebar
 *
 * @param array $options        Plugin options.
 *
 * @return array
 */
function bimber_essb_setup_sharebar( $options ) {
	if ( ! isset( $options['button_position'] ) || ! is_array( $options['button_position'] ) ) {
		$options['button_position'] = array();
	}

	// Setup sharebar position.
	$options['topbar_top_onscroll']     = 1;        // Show when 1% of content is visible.
	$options['topbar_buttons_align']    = 'center';

	// Enable sharebar if alredy enabled via theme's option.
	if ( 'none' !== bimber_get_theme_option( 'post', 'sharebar' ) ) {
		$options['button_position'][] = 'topbar';
	}

	return $options;
}
