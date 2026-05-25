<?php
/**
 * Partner.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Partner class.
 */
class Partner {

	/**
	 * Is fresh installation.
	 *
	 * @var Boolean
	 */
	public $is_fresh_install;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		if ( get_option( 'nfd_module_activation_fresh_install', false ) ) {
			$this->is_fresh_install = true;
		} else {
			$this->is_fresh_install = false;
		}
	}
}
