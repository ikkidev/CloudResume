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


$section_id = 'g1ui-settings-section-apis';

add_settings_section(
	$section_id,                // ID used to identify this section and with which to register options.
	'',                    // Title to be displayed on the administration page.
	'',
	$this->get_page()           // Page on which to add this section of options.
);

//
// Facebook
//

add_settings_field(
    'apis_facebook_header',
    '<h2>' . _x( 'Facebook', 'Theme Options Heading', 'bimber' ) . '</h2>',
    '__return_empty_string',
    $this->get_page(),
    $section_id
);

// Facebook > App Id

add_settings_field(
    'facebook_app_id',
    _x( 'App ID', 'Settings', 'bimber' ),
    'bimber_render_facebook_app_id',
    $this->get_page(),
    $section_id
);

// Facebook > App Id

add_settings_field(
    'facebook_app_secret',
    _x( 'App Secret', 'Settings', 'bimber' ),
    'bimber_render_facebook_app_secret',
    $this->get_page(),
    $section_id
);

/**
 * Render the Facebook App ID field
 */
function bimber_render_facebook_app_id() {
    $app_id    = bimber_get_facebook_app_id();
    $field_name = sprintf( '%s[%s]', bimber_get_theme_options_id(), 'facebook_app_id' );

    ?>
    <input type="text" id="facebook_app_id" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $app_id ); ?>" />
    <p class="description">
        <?php echo wp_kses_post( sprintf( __( 'How do I get my <strong>App ID</strong>? Use this <a href="%s" target="_blank">guide</a> for help.', 'bimber' ), esc_url( 'https://bimber.bringthepixel.com/docs/facebook-api/#create-and-bind-an-application' ) ) ); ?>
    </p>
    <?php
}

/**
 * Render the Facebook App ID field
 */
function bimber_render_facebook_app_secret() {
    $app_id    = bimber_get_facebook_app_secret();
    $field_name = sprintf( '%s[%s]', bimber_get_theme_options_id(), 'facebook_app_secret' );

    ?>
    <input type="text" id="facebook_app_secret" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $app_id ); ?>" />
    <p class="description">
        <?php echo wp_kses_post( sprintf( __( 'How do I get my <strong>App Secret</strong>? Use this <a href="%s" target="_blank">guide</a> for help.', 'bimber' ), esc_url( 'https://bimber.bringthepixel.com/docs/facebook-api/#create-and-bind-an-application' ) ) ); ?>
    </p>
    <?php
}
