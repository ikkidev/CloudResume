<?php
/**
 * Default ad sections
 *
 * @package AdAce.
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

adace_register_ad_section( 'content', __( 'Post content', 'adace' ) );

adace_register_ad_section( 'global', __( 'Global slots', 'adace' ) );
