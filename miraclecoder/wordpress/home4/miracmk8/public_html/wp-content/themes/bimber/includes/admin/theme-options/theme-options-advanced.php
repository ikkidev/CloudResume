<?php
/**
 * Theme options "Dynamic style" section
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


$section_id = 'g1ui-settings-section-advanced';

add_settings_section(
	$section_id,                // ID used to identify this section and with which to register options.
	'',                         // Title to be displayed on the administration page.
	'',
	$this->get_page()           // Page on which to add this section of options.
);

// Section fields.
// Dynamic cache.
$cache_log_status = '';

// Show warning.
if ( bimber_use_external_dynamic_style() && ! bimber_dynamic_style_is_cache_dir_writable() ) {
    $cache_log_status .= '<span style="color: #ff0000;">';
    $cache_log_status .= sprintf( _x( 'ERROR. External CSS file cannot be saved to disk. Change permissions to allow server to write to directory %s', 'Theme Options', 'bimber' ), '<code>' . bimber_dynamic_style_get_cache_dir() . '</code>' );
    $cache_log_status .= '</span>';
}

$cache_log        = get_transient( 'bimber_dynamic_style_cache_log' );

if ( false !== $cache_log && is_array( $cache_log ) ) {
	$cache_log_status .= '<br /><br />';
	$cache_log_status .= '<h4>' . esc_html__( 'Last action status', 'bimber' ) . ':</h4>';

	$cache_log_status .= '<div class="g1-log g1-log-' . $cache_log['type'] . '">' . $cache_log['message'] . ' (' . $cache_log['date'] . ')</div>';
}

add_settings_field(
	'advanced_dynamic_style',
	esc_html__( 'Load dynamic CSS using', 'bimber' ),
	array(
		$this,
		'render_select',
	),
	$this->get_page(),
	$section_id,
	array(
		'field_name'    => 'advanced_dynamic_style',
		'options'       => array(
			'internal'     => esc_html__( 'internal stylesheet (inside <head>)', 'bimber' ),
			'external_css' => esc_html__( 'external CSS file (recommended)', 'bimber' ),

		),
		'default_value' => $bimber_theme_options_defaults['advanced_dynamic_style'],
		'hint'          => $cache_log_status,
	)
);
