<?php
/**
 * Activation class file.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Activation class.
 */
class Activation {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container The plugin container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		new Partners( $container );
	}
}
