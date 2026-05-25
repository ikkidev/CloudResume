<?php

namespace NewfoldLabs\WP\Module\Staging;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Staging\Staging;
use function NewfoldLabs\WP\ModuleLoader\container as getContainer;

/**
 * Child class for a feature
 *
 * Child classes should define a name property as the feature name for all API calls. This name will be used in the registry.
 * Child class naming convention is {FeatureName}Feature.
 */
class StagingFeature extends \NewfoldLabs\WP\Module\Features\Feature {
	/**
	 * The feature name.
	 *
	 * @var string
	 */
	protected $name = 'staging';

	/**
	 * The feature value. Defaults to on.
	 *
	 * @var boolean
	 */
	protected $value = true;

	/**
	 * Initialize staging feature.
	 */
	public function initialize() {
		if ( function_exists( 'add_action' ) ) {

			// Register module
			add_action(
				'plugins_loaded',
				function () {
					new Staging( getContainer() );
				}
			);
		}
	}
}
