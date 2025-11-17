<?php

namespace NewfoldLabs\WP\Module\Performance\Fonts;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Performance\Fonts\FontSettings;

/**
 * Manages the initialization of font optimization settings and listeners.
 */
class FontManager {

	/**
	 * Constructor to initialize the FontManager.
	 *
	 * Registers settings and conditionally initializes related services.
	 *
	 * @param Container $container Dependency injection container.
	 */
	public function __construct( Container $container ) {
		$this->initialize_settings( $container );
	}

	/**
	 * Initializes the FontSettings class to register settings.
	 *
	 * @param Container $container Dependency injection container.
	 */
	private function initialize_settings( Container $container ) {
		new FontSettings( $container );
	}
}
