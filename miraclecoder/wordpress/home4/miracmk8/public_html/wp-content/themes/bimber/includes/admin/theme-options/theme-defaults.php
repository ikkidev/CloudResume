<?php
/**
 * Theme default options
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

$bimber_theme_options_defaults = array(
	'shares_enabled'            => 'standard',
	'shares_debug_mode'         => 'none',
	'shares_use_shortlinks'     => 'standard',
	'shares_positions'          => false,
	'facebook_app_id'           => '',
	'facebook_app_secret'       => '',
	'advanced_dynamic_style'    => 'external_css',
	'tracking_code_head'        => '',
	'tracking_code_footer'      => '',
	'tracking_code_amp'      => '',
	'tracking_code_amp_head'      => '',
	'gdpr_enabled'              => false,
	'gdpr_wpsl_consent'         => __( 'To use social login you have to agree with the storage and handling of your data by this website.', 'bimber' ),
);
