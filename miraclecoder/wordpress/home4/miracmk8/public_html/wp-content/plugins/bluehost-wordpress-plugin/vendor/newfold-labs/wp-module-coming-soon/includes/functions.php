<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

/**
 * Check if the coming soon module is active.
 */
function isComingSoonActive(): bool {
	return ( new Service() )->is_enabled();
}

/**
 * Check if WooCommerce is activated
 */
function isWoocommerceActive(): bool {
	return class_exists( 'woocommerce' );
}

/**
 * Check if an option exists
 * 
 * @param string $option_name The option name
 */
function optionExists( $option_name ): bool {
	$value = get_option( $option_name, 'not_set' );
	return 'not_set' !== $value;
}
