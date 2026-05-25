<?php
/**
 * Options Page for Ads Free
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'admin_menu', 'adace_add_ad_free_options_sections_and_fields' );
add_action( 'adace_options_form_before_submit', 'adace_ad_free_options_form_before_submit', 10, 1 );

function adace_ad_free_options_form_before_submit( $current_tag ) {
    if ( 'adace_ad_free' === $current_tag ) {
        ?>
	    <hr />
	    <div class="notice notice-warning inline">
	        <p>
	            <?php esc_html_e( 'Options here are bound to the user session. You need to log out and in to apply them', 'adace' ); ?>
	        </p>
	    </div>
        <?php
    }
}

/**
 * Add options page sections, fields and options.
 */
function adace_add_ad_free_options_sections_and_fields() {
    /*
     * Logged-In
     */
    add_settings_section(
        'adace_ad_free_logged_in', // Section id.
        __( 'Log-In Based', 'adace' ), // Section title.
        '', // Section renderer callback with args pass.
        'adace_ad_free' // Page.
    );

    add_settings_field(
        'adace_ad_free_logged_in', // Field ID.
        __( 'Disable ads for logged-in users', 'adace' ), // Field title.
        'adace_options_ad_free_logged_in_renderer_callback', // Callback.
        'adace_ad_free', // Page.
        'adace_ad_free_logged_in' // Section.
    );

    register_setting(
        'adace_ad_free', // Option group.
        'adace_ad_free_logged_in' // Option name.
    );

    /*
     * User Roles
     */
    add_settings_section(
        'adace_ad_free_user_roles', // Section id.
        'User-Role Based', // Section title.
        '', // Section renderer callback with args pass.
        'adace_ad_free' // Page.
    );

    add_settings_field(
        'adace_ad_free_user_roles', // Field ID.
        __( 'Disable ads for users with roles', 'adace' ), // Field title.
        'adace_options_ad_free_user_roles_renderer_callback', // Callback.
        'adace_ad_free', // Page.
        'adace_ad_free_user_roles' // Section.
    );

    register_setting(
        'adace_ad_free', // Option group.
        'adace_ad_free_user_roles' // Option name.
    );

    /*
     * WooCommerce
     */
	add_settings_section(
		'adace_ad_free_wc', // Section id.
		__( 'WooCommerce Plugin Integration', 'adace' ), // Section title.
		'', // Section renderer callback with args pass.
		'adace_ad_free' // Page.
	);

	add_settings_field(
		'adace_ad_free_wc_product_id', // Field ID.
		_x( 'Product', 'WooCommerce Integration', 'adace' ), // Field title.
		'adace_options_ad_free_wc_product_id_renderer_callback', // Callback.
		'adace_ad_free', // Page.
		'adace_ad_free_wc' // Section.
	);

	register_setting(
		'adace_ad_free', // Option group.
		'adace_ad_free_wc_product_id' // Option name.
	);

    /*
     * Restrict Content Pro
     */
    add_settings_section(
        'adace_ad_free_rcp', // Section id.
        __( 'Restrict Content Pro Plugin Integration', 'adace'), // Section title.
        '', // Section renderer callback with args pass.
        'adace_ad_free' // Page.
    );

    add_settings_field(
        'adace_ad_free_rcp_membership_level_ids', // Field ID.
        _x( 'Membership Levels', 'Restrict Content Pro Integration', 'adace' ), // Field title.
        'adace_options_ad_free_rcp_membership_level_id_renderer_callback', // Callback.
        'adace_ad_free', // Page.
        'adace_ad_free_rcp' // Section.
    );

    register_setting(
        'adace_ad_free', // Option group.
        'adace_ad_free_rcp_membership_level_ids' // Option name.
    );
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_ad_free_logged_in_renderer_callback() {
    $logged_in = 'standard' === get_option( 'adace_ad_free_logged_in', 'none' );

    ?>
    <input type="checkbox" value="standard" name="adace_ad_free_logged_in"<?php checked( $logged_in ) ?> />
    <?php
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_ad_free_user_roles_renderer_callback() {
    $roles = array_reverse( get_editable_roles() );
    $selected_roles = get_option( 'adace_ad_free_user_roles', '' );

    if ( ! is_array( $selected_roles ) ) {
        $selected_roles = array();
    }

    ?>
    <select size="10" name="adace_ad_free_user_roles[]" multiple="multiple">
        <?php foreach( $roles as $role_id => $role ) : ?>
            <option value="<?php echo esc_attr( $role_id ); ?>"<?php selected( in_array( $role_id, $selected_roles ) ) ?>><?php echo esc_html( $role['name'] ); ?></option>
        <?php endforeach; ?>
    </select>

    <p class="description">
        <?php echo esc_html__( 'You can select many.', 'adace' ); ?>
    </p>
    <?php
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_ad_free_wc_product_id_renderer_callback() {
    ?>
    <?php if ( ! adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) : ?>
        <p class="description"><?php echo esc_html_x( 'Please activate the WooCommerce plugin to enable this option.', 'adace' ); ?></p>
        <?php return; ?>
    <?php endif; ?>
    <?php
    $product_id = adace_ad_free_get_wp_product_id();
    $product_name = '';
    $product = wc_get_product( $product_id );

    if ( $product ) {
        $product_name = $product->get_name();
    }
    ?>
    <input type="number" class="adace-wc-product-autocomplete" value="<?php echo esc_attr( $product_id ); ?>" name="adace_ad_free_wc_product_id" data-product-name="<?php echo esc_attr( $product_name ); ?>" />
    <?php

    ?>
    <p class="description">
        <?php echo esc_html_x( 'Disable ads for all users who bought that product.', 'WooCommerce Integration', 'adace' ); ?>
    </p>
    <?php
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_ad_free_rcp_membership_level_id_renderer_callback() {
    ?>
    <?php if ( ! adace_can_use_plugin( 'restrict-content-pro/restrict-content-pro.php' ) ) : ?>
        <p class="description"><?php echo esc_html_x('Please activate the Restrict Content Pro plugin to enable this option.', 'adace'); ?></p>
        <?php return; ?>
    <?php endif; ?>
    <?php
    $level_ids = get_option('adace_ad_free_rcp_membership_level_ids', '' );

    if ( ! is_array( $level_ids ) ) {
         $level_ids = array();
    }

    $membership_levels = rcp_get_membership_levels();
    ?>
    <?php if ( ! empty( $membership_levels ) ): ?>

        <select name="adace_ad_free_rcp_membership_level_ids[]" multiple="multiple">
            <?php foreach( $membership_levels as $membership_level ) : ?>
                <option value="<?php echo absint( $membership_level->get_id() ); ?>"<?php selected( in_array( $membership_level->get_id(), $level_ids ) ) ?>><?php echo esc_html( $membership_level->get_name() ); ?></option>
            <?php endforeach; ?>
        </select>

        <p class="description">
            <?php echo esc_html_x( 'Disable ads for all users with the selected membership levels. You can select many.', 'WooCommerce Integration', 'adace' ); ?>
        </p>

    <?php else: ?>

        <?php echo esc_html_x( 'No levels are available. Please add levels in WP Dashboard > Restrict > Membership Levels.', 'Restrict Content Pro Integration', 'adace' ); ?>

    <?php endif; ?>
    <?php
}
