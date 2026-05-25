<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

use NewfoldLabs\WP\Module\HelpCenter\HelpCenter;
use NewfoldLabs\WP\Module\HelpCenter\Data\Brands;
use NewfoldLabs\WP\Module\Data\SiteCapabilities;

use function NewfoldLabs\WP\ModuleLoader\container as getContainer;

/**
 * Child class for a feature
 *
 * Child classes should define a name property as the feature name for all API calls. This name will be used in the registry.
 * Child class naming convention is {FeatureName}Feature.
 */
class HelpCenterFeature extends \NewfoldLabs\WP\Module\Features\Feature {
	/**
	 * The feature name.
	 *
	 * @var string
	 */
	protected $name = 'helpCenter';

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
					$container = getContainer();
					define( 'NFD_HELPCENTER_PLUGIN_DIRNAME', dirname( $container->plugin()->basename ) );
					define( 'NFD_HELPCENTER_PLUGIN_URL', $container->plugin()->url );
					new HelpCenter( $container );
					// Define the brand
					Brands::set_current_brand( $container );
				}
			);
		}
	}

	/**
	 * Checks for capability and if user has permissions to toggle.
	 *
	 * @return bool True if the feature toggle is allowed, false otherwise.
	 */
	public function canToggle() {
		$capabilies       = new SiteCapabilities();
		$hasCapability    = $capabilies->get( 'canAccessHelpCenter' );
		$canManageOptions = current_user_can( 'manage_options' );
		return (bool) $hasCapability && $canManageOptions;
	}
}
