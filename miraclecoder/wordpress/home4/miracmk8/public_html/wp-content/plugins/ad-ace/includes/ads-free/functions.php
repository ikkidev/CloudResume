<?php
/**
 * Ads Free Functions
 *
 * @package AdAce.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'wp_login',                         'adace_check_if_ad_free_mode_enabled', 10, 2 );
add_filter( 'adace_is_slot_active_filter',      'adace_disable_all_ads', 99, 1 );
add_filter( 'adace_display_shortcode',          'adace_disable_all_ads', 10, 1 );
add_filter( 'adace_display_widget',             'adace_disable_all_ads', 10, 1 );
add_action( 'wp_ajax_adace_get_wc_products',    'adace_ajax_get_wc_products' );

function adace_check_if_ad_free_mode_enabled( $login, $user ) {
    $logged_in_enabled  = adace_logged_in_ad_free_mode_enabled( $user );
    $roles_enabled      = adace_roles_ad_free_mode_enabled( $user );
    $wc_enabled         = adace_wc_ad_free_mode_enabled( $user );
    $rcp_enabled        = adace_rcp_ad_free_mode_enabled( $user );

    $enabled = $logged_in_enabled || $roles_enabled || $wc_enabled || $rcp_enabled;

    update_user_meta( $user->ID, 'adace_ad_free_enabled', $enabled );
}

function adace_ad_free_mode_enabled() {
    if ( ! is_user_logged_in() ) {
        return false;
    }

    $enabled = get_user_meta( get_current_user_id(), 'adace_ad_free_enabled', true );

    return apply_filters( 'adace_ad_free_mode_enabled', $enabled );
}

function adace_disable_all_ads( $load_ad_slot ) {
    if ( adace_ad_free_mode_enabled() ) {
        $load_ad_slot = false;
    }

    return $load_ad_slot;
}

function adace_logged_in_ad_free_mode_enabled( $user ) {
    return 'standard' === get_option( 'adace_ad_free_logged_in', 'none' );
}

/**
 * @param WP_User $user
 */
function adace_roles_ad_free_mode_enabled( $user ) {
    $allowed_roles = get_option( 'adace_ad_free_user_roles', '' );

    if ( ! is_array( $allowed_roles ) ) {
        $allowed_roles = array();
    }

    // Common roles.
    $roles = array_intersect( $user->roles, $allowed_roles );

    return ! empty( $roles );
}

function adace_ad_free_get_wp_product_id() {
    return (int) get_option( 'adace_ad_free_wc_product_id' );
}

function adace_wc_ad_free_mode_enabled( $user ) {
    // Skip if WooCommerce not active.
    if ( ! adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
        return false;
    }

    $ad_free_product_id = adace_ad_free_get_wp_product_id();

    $wc_product = wc_get_product( $ad_free_product_id );

    // Skip if the product not exists.
    if ( ! $wc_product ) {
        return false;
    }

    $enabled = wc_customer_bought_product( $user->user_email, $user->ID, $wc_product->get_id() );

    return apply_filters( 'adace_ad_free_wc_mode_enabled', $enabled );
}

function adace_rcp_ad_free_mode_enabled( $user ) {
    // Skip if WooCommerce not active.
    if ( ! adace_can_use_plugin( 'restrict-content-pro/restrict-content-pro.php' ) ) {
        return false;
    }

    $ad_free_level_ids = get_option( 'adace_ad_free_rcp_membership_level_ids' );

    if ( ! is_array( $ad_free_level_ids ) ) {
        $ad_free_level_ids = array();
    }

    // Skip if the levels not defined.
    if ( empty( $ad_free_level_ids ) ) {
        return false;
    }

    $customer  = rcp_get_customer_by_user_id( $user->ID );
    $is_active = rcp_user_has_active_membership( $user->ID );

    $enabled = $is_active && ! empty( $customer ) && count( array_intersect( rcp_get_customer_membership_level_ids( $customer->get_id() ), $ad_free_level_ids ) );

    return apply_filters( 'adace_ad_free_rcp_mode_enabled', $enabled );
}

function adace_ajax_get_wc_products() {
    $search_text = filter_input( INPUT_GET, 'text', FILTER_SANITIZE_STRING );

    if ( ! $search_text || strlen( $search_text ) < 3 ) {
        echo wp_json_encode( array(
            'status'   => 'error',
        ) );
        exit;
    }

    $products = get_posts( array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $search_text,
        'posts_per_page' => 10
    ) );

    $id_name_map = array();

    if ( ! empty( $products ) ) {
        foreach ($products as $product) {
            $id_name_map[ $product->ID ] = $product->post_title;
        }
    }

    echo wp_json_encode( array(
        'status'   => 'success',
        'products' => $id_name_map,
    ) );
    exit;
}
