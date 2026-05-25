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
function bimber_run_migration() {
	$version = (int) bimber_get_theme_version();    // Cast to major version number.
	$migration_required = in_array( $version, array( 1, 4 ), true );

	if ( ! $migration_required ) {
		return;
	}

	// Backward compatibility.
	$version = $version . '_0'; // 4_0.

	$migration_id = 'bimber_migration_' . $version;

	$migration_done = get_option( $migration_id );

	if ( $migration_done ) {
		return;
	}

	if ( is_callable( $migration_id ) ) {
		$res = call_user_func( $migration_id );

		update_option( $migration_id, $res );
	}
}

/**
 * Migrate to version 4.0
 *
 * @return bool             Migration status
 */
function bimber_migration_4_0() {
	$options = get_option( bimber_get_theme_id() );

	// Fresh WP installation, no need to migrate.
	if ( ! $options ) {
		return true;
	}

	// Home: Featured entries template.
	switch ( $options['home_template'] ) {
		case 'one-featured-grid-sidebar':
		case 'one-featured-list-sidebar':
			$options['home_featured_entries_template'] = '1-sidebar';
			break;

		default:
			$options['home_featured_entries_template'] = '2of3-3v-3v-boxed';
			break;
	}

	// Home: Template.
	switch ( $options['home_template'] ) {
		case 'one-featured-list-sidebar':
		case 'three-featured-list-sidebar':
			$options['home_template'] = 'list-sidebar';
			break;

		case 'two-featured-grid':
		case 'three-featured-grid':
			$options['home_template'] = 'grid';
			break;

		case 'one-featured-classic-sidebar':
		case 'three-featured-classic-sidebar':
			$options['home_template'] = 'classic-sidebar';
			break;

		case 'three-featured-stream-sidebar':
			$options['home_template'] = 'stream-sidebar';
			break;

		case 'three-featured-stream':
			$options['home_template'] = 'stream';
			break;

		default:
			$options['home_template'] = 'grid-sidebar';
			break;
	}


	// Archive: Featured entries template.
	switch ( $options['home_template'] ) {
		case 'one-featured-grid-sidebar':
		case 'one-featured-list-sidebar':
			$options['archive_featured_entries_template'] = '1-sidebar';
			break;

		default:
			$options['archive_featured_entries_template'] = '2of3-3v-3v-boxed';
	}

	// Archive: Template.
	switch ( $options['archive_template'] ) {
		case 'one-featured-list-sidebar':
		case 'three-featured-list-sidebar':
			$options['archive_template'] = 'list-sidebar';
			break;

		case 'two-featured-grid':
		case 'three-featured-grid':
			$options['archive_template'] = 'grid';
			break;

		case 'one-featured-classic-sidebar':
		case 'three-featured-classic-sidebar':
			$options['archive_template'] = 'classic-sidebar';
			break;

		case 'three-featured-stream-sidebar':
			$options['archive_template'] = 'stream-sidebar';
			break;

		case 'three-featured-stream':
			$options['archive_template'] = 'stream';
			break;

		default:
			$options['archive_template'] = 'grid-sidebar';
			break;
	}

	$status = update_option( bimber_get_theme_id(), $options );

	// Deactivate plugins. Upgrade required.
	$plugins = array();

	if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
		$plugins[] = 'snax/snax.php';
	}

	if ( bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ) ) {
		$plugins[] = 'whats-your-reaction/whats-your-reaction.php';
	}

	if ( ! empty( $plugins ) ) {
		deactivate_plugins( $plugins );
	}

	// Force TGMPA to show all notices, even if user previously dismissed them.
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_snax', null, true ); 	// Snax.
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_tgmpa', null, true );	// Bimber.

	return $status;
}

/**
 * Run migration script if needed
 */
function bimber_run_bunchy_migration() {
	$version = (float) bimber_get_theme_version();

	$bunchy = get_option( 'bimber_demo_installed','main' ) === 'bunchy';
	$migration_required = $version >= 4.10;

	if ( ! $migration_required || ! $bunchy ) {
		return;
	}
	// Backward compatibility.
	$version = '4_10';

	$migration_id = 'bimber_migration_' . $version;

	$migration_done = get_option( $migration_id );

	if ( $migration_done ) {
	 	return;
	}

	$res = call_user_func( 'bimber_migration_4_10' );

	update_option( $migration_id, $res );
}

/**
 * Migrate to version 4.0
 *
 * @return bool             Migration status
 */
function bimber_migration_4_10() {
	$options = get_option( bimber_get_theme_id() );
	$bunchy_options = get_option( 'bunchy_theme', '' );

	if ( empty( $bunchy_options ) ) {
		return;
	}

	$skip = array( 'global_width' );
	foreach ( $bunchy_options as $key => $value ) {
		if ( ! in_array( $key, $skip ) ) {
			$options[ $key ] = $bunchy_options [ $key ];
		}
	}

	if ( 'classic' === $options['post_template'] ) {
		$options['post_template'] = 'classic-v3';
	}
	if ( 'classic-no-sidebar' === $options['post_template'] ) {
		$options['post_template'] = 'classic-v3-no-sidebar';
	}

	switch ( $options['home-template'] ) {
		case 'one-featured-list-sidebar':
			$options['home_template'] = 'list-sidebar';
			$options['home_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'one-featured-grid-sidebar':
			$options['home_template'] = 'grid-sidebar';
			$options['home_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'one-featured-classic-sidebar':
			$options['home_template'] = 'bunchy';
			$options['home_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'grid':
			$options['home_template'] = 'list-sidebar';
			$options['home_featured_entries'] = 'none';
			break;
	}

	switch ( $options['archive_template'] ) {
		case 'one-featured-list-sidebar':
			$options['archive_template'] = 'list-sidebar';
			$options['archive_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'three-featured-list-sidebar':
			$options['archive_template'] = 'list-sidebar';
			$options['archive_featured_entries_template'] = '3-3-3-stretched';
			break;
		case 'one-featured-grid-sidebar':
			$options['archive_template'] = 'grid-sidebar';
			$options['archive_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'three-featured-grid-sidebar':
			$options['archive_template'] = 'grid-sidebar';
			$options['archive_featured_entries_template'] = '3-3-3-stretched';
			break;
		case 'one-featured-classic-sidebar':
			$options['archive_template'] = 'bunchy';
			$options['archive_featured_entries_template'] = '1-sidebar-bunchy';
			break;
		case 'three-featured-classic-sidebar':
			$options['archive_template'] = 'bunchy';
			$options['archive_featured_entries_template'] = '3-3-3-stretched';
			break;
		case 'grid':
			$options['archive_template'] = 'list-sidebar';
			$options['archive_featured_entries'] = 'none';
			break;
		case 'three-featured-grid':
			$options['archive_template'] = 'list-sidebar';
			$options['archive_featured_entries_template'] = '3-3-3-stretched';
			break;
	}

	$status = update_option( bimber_get_theme_id(), $options );

	$bunchy_theme_mods_name = apply_filters( 'bunchy_theme_mods_name', 'theme_mods_bunchy-child-theme' );
	$bunchy_theme_mods = get_option( $bunchy_theme_mods_name, '' );
	if ( empty( $bunchy_theme_mods ) ) {
		$bunchy_theme_mods = get_option( 'theme_mods_bunchy', '' );
	}

	if ( ! empty( $bunchy_theme_mods ) && is_array( $bunchy_theme_mods ) ) {
		foreach ( $bunchy_theme_mods as $key => $value ) {
			set_theme_mod( $key, $value );
		}
	}

	return $status;
}
