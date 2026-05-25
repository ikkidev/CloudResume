<?php
/**
 * Deactivation event.
 *
 * @package NewfoldLabs\WP\Module\Deactivation
 */

namespace NewfoldLabs\WP\Module\Deactivation\Events;

use WP_REST_Request;

/**
 * Event class.
 */
class Event {
	/**
	 * Key representing the event action that occurred.
	 *
	 * @var string
	 */
	public $action;

	/**
	 * Event category.
	 *
	 * @var string
	 */
	public $category;

	/**
	 * Array of extra data related to the event.
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Data module endpoint to send the event to.
	 *
	 * @var string
	 */
	public $endpoint = '/newfold-data/v1/events/';

	/**
	 * WP_REST_Request instance.
	 *
	 * @var WP_REST_Request
	 */
	public $request;

	/**
	 * Constructor.
	 *
	 * @param string $action   Key representing the event action that occurred.
	 * @param string $category Event category.
	 * @param array  $data     Array of extra data related to the event.
	 */
	public function __construct( $category = 'plugin_deactivation', $data = array() ) {
		$this->category = $category;
		$this->data     = $data;
		$this->request  = new WP_REST_Request( 'POST', $this->endpoint );
	}

	/**
	 * Send the event to the data module endpoint.
	 *
	 * @return WP_REST_Response REST response
	 */
	public function sendEvent() {
		$event = array(
			'action'   => $this->action,
			'category' => $this->category,
			'data'     => $this->data,
			'queue'    => false,
		);
		
		$this->request->set_body( wp_json_encode( $event ) );
		return rest_do_request( $this->request );
	}
}
