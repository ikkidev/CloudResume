<?php

namespace NewfoldLabs\WP\Module\Migration\Helpers;

/**
 * Helper class for brand-specific logic in the Migration module.
 */
class BrandHelper {
	/**
	 * Checks if the given brand is whitelisted for migration tools.
	 *
	 * @param string $brand The brand ID to check.
	 * @return bool
	 */
	public static function is_whitelisted( $brand ) {
		return defined( 'NFD_MIGRATION_BRAND_WHITELIST' )
			&& in_array( $brand, NFD_MIGRATION_BRAND_WHITELIST, true );
	}
}
