<?php

namespace NewfoldLabs\WP\Module\Migration\Steps;

use NewfoldLabs\WP\Module\Migration\Steps\AbstractStep;

/**
 * Monitors InstaWp instawp_migration_details option update to intercept the migration push step.
 *
 * @package wp-module-migration
 */
class Push extends AbstractStep {
	/**
	 * Construct. Init basic parameters.
	 */
	public function __construct() {
		$this->set_step_slug( 'Push' );
		$this->set_status( $this->statuses['running'] );
	}
}
