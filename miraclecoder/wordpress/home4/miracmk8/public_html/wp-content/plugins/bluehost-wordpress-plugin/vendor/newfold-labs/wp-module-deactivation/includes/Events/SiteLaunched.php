<?php
/**
 * Site launched deactivation event.
 *
 * @package NewfoldLabs\WP\Module\Deactivation
 */

namespace NewfoldLabs\WP\Module\Deactivation\Events;

/**
 * Site launched event class.
 */
class SiteLaunched extends Event {
	/**
	 * send event.
	 * 
	 * @return void
	 */
	public function send() {
		$this->action = 'site_launched';
		$this->data   = array(
			'ttl' => $this->getInstallTime(),
		);

		return $this->sendEvent();
	}

	/**
	 * Calculate install time.
	 * 
	 * @return int
	 */
	private function getInstallTime() {
		$mm_install_time = get_option( 'mm_install_date', gmdate( 'M d, Y' ) );

		return time() - strtotime( $mm_install_time );
	}
}
