<?php

namespace NewfoldLabs\WP\Module\Migration\Steps;

/**
 * Monitors InstaWp instawp_last_migration_details option update to intercept when migration is finished.
 *
 * @package wp-module-migration
 */
class LastStep extends AbstractStep {
	/**
	 * Construct. Init basic parameters.
	 */
	public function __construct() {
		$this->set_step_slug( 'LastStep' );
		$this->set_status( $this->statuses['running'] );
	}
}
