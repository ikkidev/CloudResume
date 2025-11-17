<?php

namespace NewfoldLabs\WP\Module\Migration\Services;

use NewfoldLabs\WP\Module\Migration\Data\Events;
use NewfoldLabs\WP\Module\Data\Event;
use NewfoldLabs\WP\Module\Data\EventManager;
use NewfoldLabs\WP\Module\Data\HiiveConnection;

/**
 * Class for handling analytics events.
 */
class EventService {

	/**
	 * Sends a Hiive Event to the data module API.
	 *
	 * @param array $event The event to send.
	 * @return WP_REST_Response|WP_Error|bool
	 */
	public static function send( $event ) {
		$event = self::validate( $event );
		if ( ! $event ) {
			return new \WP_Error(
				'nfd_module_migration_error',
				__( 'Bad event structure/value.', 'wp-module-migration' )
			);
		}

		if ( 'migration_completed' === $event['action'] ) {
			$event_to_send = new Event(
				$event['category'],
				$event['action'],
				$event['data']
			);

			$event_manager = new EventManager();
			$event_manager->push( $event_to_send );
			$event_manager->add_subscriber(
				new HiiveConnection()
			);
			$event_manager->shutdown();

			return true;
		}

		$event_data_request = new \WP_REST_Request(
			\WP_REST_Server::CREATABLE,
			NFD_MODULE_DATA_EVENTS_API
		);
		$event_data_request->set_body_params( $event );

		$response = rest_do_request( $event_data_request );
		if ( $response->is_error() ) {
			return $response->as_error();
		}

		return $response;
	}
	/**
	 * Validates the category of an event.
	 *
	 * @param string $category The category of an event.
	 * @return boolean
	 */
	public static function validate_category( $category ) {
		$default_categories = Events::get_category();
		foreach ( $default_categories as $event_category ) {
			if ( $event_category === $category ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Validates the action performed in an event.
	 *
	 * @param string $action The action performed in an event.
	 * @return boolean
	 */
	public static function validate_action( $action ) {
		$valid_actions = Events::get_valid_actions();
		if ( ! isset( $valid_actions[ $action ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitizes and validates the action and category parameters of an event.
	 *
	 * @param array $event The event to sanitize and validate.
	 * @return array|boolean
	 */
	public static function validate( $event ) {
		if ( ! isset( $event['action'] ) || ! self::validate_action( $event['action'] ) ) {
			return false;
		}

		if ( ! isset( $event['category'] ) || ! self::validate_category( $event['category'] ) ) {
			return false;
		}

		return $event;
	}

	/**
	 * Sends a Hiive event to the Cloudflare worker's /events endpoint.
	 *
	 * @param string $key  The event action key.
	 * @param array  $data The event data payload (must include d_id and dest_url).
	 * @return bool|WP_Error
	 */
	public static function send_application_event( $key, $data ) {
		if ( get_option( 'nfd_migration_status_sent', false ) ) {
			return true; // Event already sent, no need to send again.
		}

		// Validate input parameters.
		if ( ! is_string( $key ) || empty( $key ) || ! is_array( $data ) ) {
			return new \WP_Error(
				'nfd_module_migration_invalid_input',
				__( 'Invalid input for event key or data.', 'wp-module-migration' )
			);
		}

		$category = Events::get_category()[1];
		$event    = array(
			'action'   => $key,
			'category' => $category,
			'data'     => $data,
		);
		$event    = self::validate( $event );
		if ( ! $event ) {
			return new \WP_Error(
				'nfd_module_migration_invalid_event',
				__( 'Invalid event payload.', 'wp-module-migration' )
			);
		}

		$site_url = get_site_url();

		$url     = trailingslashit( NFD_MIGRATION_PROXY_WORKER ) . 'events';
		$payload = array(
			'key'         => $event['action'],
			'category'    => $event['category'],
			'request'     => array(
				'url' => $site_url,
			),
			'environment' => array(
				'brand' => BRAND_PLUGIN,
			),
			'data'        => array_merge(
				$event['data'],
				array(
					'dest_url' => $site_url,
					'url'      => $site_url,
				)
			),
		);
		$body    = wp_json_encode( $payload );
		if ( false === $body ) {
			return new \WP_Error(
				'nfd_module_migration_encoding_failed',
				__( 'Failed to encode event JSON.', 'wp-module-migration' )
			);
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body'    => $body,
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) {
			return new \WP_Error(
				'nfd_module_migration_forward_failed',
				__( 'Failed to forward event to Hiive.', 'wp-module-migration' ),
				array(
					'status_code' => $code,
					'body'        => wp_remote_retrieve_body( $response ),
				)
			);
		}

		return true;
	}
}
