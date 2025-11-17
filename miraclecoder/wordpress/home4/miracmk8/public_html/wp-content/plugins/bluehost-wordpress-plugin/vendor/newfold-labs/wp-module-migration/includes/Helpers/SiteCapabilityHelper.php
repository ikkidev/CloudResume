<?php

namespace NewfoldLabs\WP\Module\Migration\Helpers;

use NewfoldLabs\WP\Module\Data\SiteCapabilities;

/**
 * Helper class for retrieving site capability values related to migration.
 */
class SiteCapabilityHelper {

	/**
	 * Returns whether the current site can use the migration tool.
	 *
	 * @return bool
	 */
	public static function can_migrate_site() {
		$capabilities = new SiteCapabilities();
		return (bool) $capabilities->get( 'canMigrateSite' );
	}
}
