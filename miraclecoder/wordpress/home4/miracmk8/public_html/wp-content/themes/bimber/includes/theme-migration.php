<?php
/**
 * Theme setup functions
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
 * Run migration script if needed
 */
function bimber_run_migrations() {
	// V5.4.
	if ( ! bimber_get_theme_option( 'migration', 'v54' ) ) {
		bimber_migration_5_4();
		bimber_set_theme_option( 'migration', 'v54', 'done' );
	}

	/*
	 * v6.0
	 *
	 * From now, please use get_option instead of bimber_get_theme_option.
	 * It will be easier to check in user's db if migration has been done or not.
	 */
	if ( ! get_option( 'bimber_migration_v60' ) ) {
		if ( bimber_migration_6_0() ) {
			update_option( 'bimber_migration_v60', 'done' );
		}
	}

	// v6.2.
	if ( ! get_option( 'bimber_migration_v62' ) ) {
		if ( bimber_migration_6_2() ) {
			update_option( 'bimber_migration_v62', 'done' );
		}
	}

	// v6.2.2
	if ( ! get_option( 'bimber_migration_v622' ) ) {
		if ( bimber_migration_6_2_2() ) {
			update_option( 'bimber_migration_v622', 'done' );
		}
	}

	// v7.2.0
	if ( ! get_option( 'bimber_migration_v720' ) ) {
		if ( bimber_migration_7_2_0() ) {
			update_option( 'bimber_migration_v720', 'done' );
		}
	}

	// v7.6
	if ( ! get_option( 'bimber_migration_v760' ) ) {
		if ( bimber_migration_7_6_0() ) {
			update_option( 'bimber_migration_v760', 'done' );
		}
	}

	// Run these actions when theme version changes.
	if ( bimber_get_theme_version() !== get_option( 'bimber_tgm_reset_ver' ) ) {
		bimber_dynamic_style_mark_cache_as_stale();
		bimber_reset_tgm_notices();
		update_option( 'bimber_tgm_reset_ver', bimber_get_theme_version() );
	}
}

/**
 * Migrate to version 5.4
 *
 * @return bool             Migration status
 */
function bimber_migration_5_4() {

	// Fallback for demos.
	$migration_done = get_option( 'bimber_migration_5_4' );
	if ( $migration_done ) {
		return;
	}

	$options = get_option( bimber_get_theme_id() );
	$defaults = bimber_get_defaults();
	$options = wp_parse_args( $options, $defaults['bimber_theme'] );


	// Featured entries count.
	if ( 'list' === $options['featured_entries_template'] ) {
		$options['featured_entries_number'] = 3;
	}
	if ( strpos( $options['featured_entries_template'], 'grid' ) > -1 ) {
		switch ( $options['featured_entries_template'] ) {
			case 'grid':
				$options['featured_entries_number'] = 6;
				$options['featured_entries_size'] = 'xs';
				break;
			case 'grid_5':
				$options['featured_entries_number'] = 5;
				$options['featured_entries_size'] = 'xs-5';
				break;
			case 'grid_4':
				$options['featured_entries_number'] = 4;
				$options['featured_entries_size'] = 'xs-4';
				break;
			default:
				break;
		}
		$options['featured_entries_template'] = 'grid';
	}

	// Header builder.
	if ( isset( $options['header_composition'] ) ) {
		$composition_name = $options['header_composition'];
	} else {
		$composition_name = 'original';
	}

	$layouts = bimber_hb_get_layouts();
	if ( isset( $layouts[ $composition_name ] ) ) {
		$composition = json_decode( $layouts[ $composition_name ], true );
	} else {
		return false;
	}

	// Fix preset for some additional settings.
	if ( isset( $options['header_social_icons'] ) && strpos( $options['header_social_icons'], 'preheader' ) > -1 ) {
		$col = $composition['normal']['2']['cols']['3'];
		foreach ( $col['elements'] as $index => $element ) {
			if ( 'social_icons_dropdown' === $element ) {
				unset( $composition['normal']['2']['cols']['3']['elements'][ $index ] );
			}
			if ( 'social_icons_full' === $element ) {
				unset( $composition['normal']['2']['cols']['3']['elements'][ $index ] );
			}
		}
		$col = $composition['normal']['3']['cols']['3'];
		foreach ( $col['elements'] as $index => $element ) {
			if ( 'social_icons_dropdown' === $element ) {
				unset( $composition['normal']['3']['cols']['3']['elements'][ $index ] );
			}
			if ( 'social_icons_full' === $element ) {
				unset( $composition['normal']['3']['cols']['3']['elements'][ $index ] );
			}
		}
		if ( strpos( $options['header_social_icons'], 'full' ) > -1 ) {
			array_unshift( $composition['normal']['1']['cols']['3']['elements'], 'social_icons_full' );
		} else {
			array_unshift( $composition['normal']['1']['cols']['3']['elements'], 'social_icons_dropdown' );
		}
	}

	if ( isset( $options['header_social_icons'] ) && strpos( $options['header_social_icons'], 'full' ) > -1 ) {
		$col = $composition['normal']['2']['cols']['3'];
		foreach ( $col['elements'] as $index => $element ) {
			if ( 'social_icons_dropdown' === $element ) {
				$composition['normal']['2']['cols']['3']['elements'][ $index ] = 'social_icons_full';
			}
		}
		$col = $composition['normal']['3']['cols']['3'];
		foreach ( $col['elements'] as $index => $element ) {
			if ( 'social_icons_dropdown' === $element ) {
				$composition['normal']['3']['cols']['3']['elements'][ $index ] = 'social_icons_full';
			}
		}
	}

	if ( '01' === $options['header_mobile_composition'] ) {
		if ( 'hardcore' === $composition_name ) {
			$col = $composition['mobile']['2']['cols']['2'];
			foreach ( $col['elements'] as $index => $element ) {
				if ( 'mobile_logo' === $element ) {
					unset( $composition['mobile']['2']['cols']['2']['elements'][ $index ] );
				}
			}
			array_push( $composition['mobile']['2']['cols']['1'][], 'mobile_logo' );
		} else {
			$col = $composition['mobile']['1']['cols']['2'];
			foreach ( $col['elements'] as $index => $element ) {
				if ( 'mobile_logo' === $element ) {
					unset( $composition['mobile']['1']['cols']['2']['elements'][ $index ] );
				}
			}
			array_push( $composition['mobile']['1']['cols']['1'], 'mobile_logo' );
		}
	}
	if ( 'none' === $options['header_sticky'] ) {
		foreach ( $composition as $headerindex => $header ) {
			foreach ( $header as $rowindex => $row ) {
				$composition[ $headerindex ][ $rowindex ]['sticky'] = 'off';
			}
		}
	}

	$options['header_builder'] = $composition;

	$layouts_settings = bimber_hb_get_settings();
	if ( isset( $layouts_settings[ $composition_name ] ) ) {
		$settings = $layouts_settings[ $composition_name ];
	} else {
		$settings = array();
	}

	// Fix preset for some additional settings.
	if ( isset( $options['header_social_icons'] ) &&  ( 'preheader_drop' === $options['header_social_icons'] || 'preheader_full' === $options['header_social_icons'] ) ) {
		$settings['header_builder_element_size_social_icons_full'] = 'g1-socials-s';
		$settings['header_builder_element_size_social_icons_dropdown'] = 'g1-drop-s';
	}

	foreach ( $settings as $key => $value ) {
		$options[ $key ] = $value;
	}

	// Colors.
	if ( 'hardcore' === $composition_name ) {
		$a_text = $options['preheader_text_color'];
		$a_accent = $options['preheader_accent_color'];
		$a_bg = $options['preheader_background_color'];
		$a_gradient = $options['preheader_bg2_color'];
		$a_border = $options['preheader_border_color'];
		$c_text = $options['header_text_color'];
		$c_accent = $options['header_accent_color'];
		$c_bg = $options['header_background_color'];
		$c_gradient = $options['header_bg2_color'];
		$c_border = $options['header_border_color'];
		$b_text = $options['header_navbar_text_color'];
		$b_accent = $options['header_navbar_accent_color'];
		$b_bg = $options['header_navbar_background_color'];
		$b_gradient = '';
		$b_border = '';
	} else {
		$a_text = $options['preheader_text_color'];
		$a_accent = $options['preheader_accent_color'];
		$a_bg = $options['preheader_background_color'];
		$a_gradient = $options['preheader_bg2_color'];
		$a_border = $options['preheader_border_color'];
		$b_text = $options['header_text_color'];
		$b_accent = $options['header_accent_color'];
		$b_bg = $options['header_background_color'];
		$b_gradient = $options['header_bg2_color'];
		$b_border = $options['header_border_color'];
		$c_text = $options['header_navbar_text_color'];
		$c_accent = $options['header_navbar_accent_color'];
		$c_bg = $options['header_navbar_background_color'];
		$c_gradient = '';
		$c_border = '';
	}

	$options['header_builder_a_text_color'] = $a_text;
	$options['header_builder_a_accent_color'] = $a_accent;
	$options['header_builder_a_background_color'] = $a_bg;
	$options['header_builder_a_gradient_color'] = $a_gradient;
	$options['header_builder_a_border_color'] = $a_border;
	$options['header_builder_b_text_color'] = $b_text;
	$options['header_builder_b_accent_color'] = $b_accent;
	$options['header_builder_b_background_color'] = $b_bg;
	$options['header_builder_b_gradient_color'] = $b_gradient;
	$options['header_builder_b_border_color'] = $b_border;
	$options['header_builder_c_text_color'] = $c_text;
	$options['header_builder_c_accent_color'] = $c_accent;
	$options['header_builder_c_background_color'] = $c_bg;
	$options['header_builder_c_gradient_color'] = $c_gradient;
	$options['header_builder_c_border_color'] = $c_border;

	$button_bg = $options['header_navbar_secondary_background_color'];
	$button_text = $options['header_navbar_secondary_text_color'];

	$options['header_builder_a_button_background'] = $button_bg;
	$options['header_builder_a_button_text'] = $button_text;
	$options['header_builder_b_button_background'] = $button_bg;
	$options['header_builder_b_button_text'] = $button_text;
	$options['header_builder_c_button_background'] = $button_bg;
	$options['header_builder_c_button_text'] = $button_text;
	if ( 'dark' === $options['global_skin'] ) {
		$canvas_text = '#999999';
		$canvas_bg = '#1a1a1a';
	} else {
		$canvas_text = '#666666';
		$canvas_bg = '#ffffff';
	}
	$options['header_builder_canvas_text_color'] = $canvas_text;
	$options['header_builder_canvas_accent_color'] = $options['content_cs_1_accent1'];
	$options['header_builder_canvas_background_color'] = $canvas_bg;
	$options['header_builder_canvas_button_background'] = $options['content_cs_2_background2_color'];
	$options['header_builder_canvas_button_text'] = $options['content_cs_2_text1'];

	// Fix mobile logo.
	if ( empty( $options['branding_logo_small'] ) ) {
		$options['branding_logo_small'] = $options['branding_logo'];
		$options['branding_logo_small_width'] = $options['branding_logo_width'];
		$options['branding_logo_small_height'] = $options['branding_logo_height'];
	}

	$status = update_option( bimber_get_theme_id(), $options );
	return $status;
}

/**
 * Migrate to version 6.0
 *
 * @return bool             Migration status
 */
function bimber_migration_6_0() {
	// Installed demo flag.
	$installed_demo = get_option( 'bimber_demo_installed' );

	if ( ! empty( $installed_demo ) ) {
		$installed_demos = get_option( 'bimber_demos_installed', array() );

		// If installed before 6.0, had to be installed entirely.
		$installed_demos[ $installed_demo ] = array(
			'types' => array(
				'content',
				'theme-options',
				'widgets',
			)
		);

		add_option( 'bimber_demos_installed', $installed_demos );
	}

	$options = get_option( bimber_get_theme_id() );
	$defaults = bimber_get_defaults();
	$options = wp_parse_args( $options, $defaults['bimber_theme'] );

	// MailChimp.
	$legacy_title = isset( $options['newsletter_title'] ) ? $options['newsletter_title'] : '';
	$legacy_subtitle = isset( $options['newsletter_subtitle'] ) ? $options['newsletter_subtitle'] : '';
	$legacy_compact = isset( $options['newsletter_compact_title'] ) ? $options['newsletter_compact_title'] : '';
	$options['newsletter_after_post_content_title'] = $legacy_title;
	$options['newsletter_in_collection_title'] = $legacy_compact;
	$options['newsletter_in_collection_subtitle'] = $legacy_subtitle;
	$options['newsletter_above_collection_title'] = $legacy_title;
	$options['newsletter_above_collection_subtitle'] = $legacy_subtitle;
	$options['newsletter_after_post_content_title'] = $legacy_title;
	$options['newsletter_after_post_content_subtitle'] = $legacy_subtitle;
	$options['newsletter_other_title'] = $legacy_compact;

	// Votes.
	$options['home_featured_entries_hide_elements']         .= ',votes'; // Hide both votes and voting box.
	$options['home_hide_elements']                          .= ',votes,voting_box';
	$options['archive_featured_entries_hide_elements']      .= ',votes';
	$options['archive_hide_elements']                       .= ',votes,voting_box';
	$options['post_hide_elements']                          .= ',votes';            // Hide only votes. Voting bax was there enabled before.
	$options['post_dont_miss_hide_elements']                .= ',votes,voting_box';
	$options['post_related_hide_elements']                  .= ',votes,voting_box';
	$options['post_more_from_hide_elements']                .= ',votes,voting_box';

	$status = update_option( bimber_get_theme_id(), $options );

	return $status;
}

/**
 * Migrate to version 6.2
 *
 * @return bool             Migration status
 */
function bimber_migration_6_2() {
	$options = get_option( bimber_get_theme_id() );
	$defaults = bimber_get_defaults();
	$options = wp_parse_args( $options, $defaults['bimber_theme'] );

	// Hide Elements.
	$hide_elements_options = array(
		// Home.
		'home_hide_elements'                    => array( 'subtitle', 'call_to_action' ),
		'home_featured_entries_hide_elements'   => array( 'call_to_action' ),
		// Archive.
		'archive_hide_elements'                 => array( 'subtitle', 'call_to_action' ),
		'archive_featured_entries_hide_elements'=> array( 'call_to_action' ),
		// Single post.
		'post_related_hide_elements'            => array( 'subtitle', 'call_to_action' ),
		'post_dont_miss_hide_elements'          => array( 'subtitle', 'call_to_action' ),
		'post_more_from_hide_elements'          => array( 'subtitle', 'call_to_action' ),
	);

	foreach( $hide_elements_options as $option_name => $elements_to_hide ) {
		$hide_elements = array_filter( explode( ',', $options[ $option_name ] ) );

		if ( in_array( 'subtitle', $elements_to_hide ) ) {
			if ( ! in_array( 'subtitle', $hide_elements ) ) {
				$hide_elements[] = 'subtitle';
			}
		}

		if ( in_array( 'call_to_action', $elements_to_hide ) ) {
			if ( ! in_array( 'call_to_action', $hide_elements ) ) {
				$hide_elements[] = 'call_to_action';
			}
		}

		$options[ $option_name ] = implode( ',', $hide_elements );
	}

	// Newsletter popup (Cover -> Backgroum Image).
	$options['newsletter_popup_background_image'] = isset( $options['newsletter_popup_cover'] ) ? $options['newsletter_popup_cover']: '';

	// Update options.
	$status = update_option( bimber_get_theme_id(), $options );

	return $status;
}

/**
 * Migrate to version 6.2.2
 *
 * @return bool             Migration status
 */
function bimber_migration_6_2_2() {
	$options = get_option( bimber_get_theme_id() );
	$defaults = bimber_get_defaults();
	$options = wp_parse_args( $options, $defaults['bimber_theme'] );

	// Footer.
	$options['footer_stamp_label_hide'] = false;

	// Update options.
	$status = update_option( bimber_get_theme_id(), $options );

	return $status;
}

/**
 * Migrate to version 7.2.0
 *
 * @return bool             Migration status
 */
function bimber_migration_7_2_0() {
	$options = get_option( bimber_get_theme_id() );
	$defaults = bimber_get_defaults();
	$options = wp_parse_args( $options, $defaults['bimber_theme'] );

	// Migrate "Cards" stack to theme options.
	if ( 'cards' === $options['global_stack'] ) {
		$options['cards_home_content'] = 'solid';
		$options['cards_archive_content'] = 'solid';
	}

	// Update options.
	$status = update_option( bimber_get_theme_id(), $options );

	return $status;
}

/**
 * Migrate to version 7.6.0
 *
 * @return bool             Migration status
 */
function bimber_migration_7_6_0() {
	$options = get_option( bimber_get_theme_id() );

	if ( isset( $options['snax_votes_threshold'] ) && $options['snax_votes_threshold'] > 0 ) {
		update_option( 'snax_hide_votes_threshold', $options['snax_votes_threshold'] );
	}

	return true;
}

/**
 * Regenerate dynamic CSS after the update
 */
function bimber_regenerate_dynamic_style_after_update() {
	$version = bimber_get_theme_version();
	$option = get_option( 'bimber_reload_cache' );
	if ( $version === $option ) {
		return;
	}
	$option_base             = bimber_get_theme_id();
	$force_cache_option_name = $option_base . '_cache_dynamic_style';
	update_option( $force_cache_option_name, true );
	update_option( 'bimber_reload_cache', $version );
}

/**
 * Regenerate dynamic CSS after the update
 */
function bimber_regenerate_dynamic_style_after_demo_import() {
	$version = bimber_get_theme_version();
	$option_base             = bimber_get_theme_id();
	$force_cache_option_name = $option_base . '_cache_dynamic_style';
	update_option( $force_cache_option_name, true );
	update_option( 'bimber_reload_cache', $version );
}
