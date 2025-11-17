<?php

namespace NewFoldLabs\WP\Module\Notifications;

use function NewfoldLabs\WP\ModuleLoader\container;

add_action( 'admin_notices', array( AdminNotices::class, 'maybeRenderAdminNotices' ) );
add_action( 'rest_api_init', array( NotificationsApi::class, 'registerRoutes' ) );

add_action(
	'nfd_event_log',
	function ( $key ) {
		$events = array(
			'login',
			'sso',
			'plugin_activated',
			'site_launched',
			'jetpack_connected',
			'first_post_published',
			'fifth_post_published',
			'plugin_search',
		);
		if ( in_array( $key, $events, true ) ) {
			$notifications = get_transient( 'newfold_notifications' );
			set_transient( 'newfold_notifications', array_filter( (array) $notifications ), 5 );
		}
	}
);

add_filter(
	container()->plugin()->id . '_admin_page_data',
	function ( $data ) {

		// Grab the latest settings using an internal REST API request
		$request = new \WP_REST_Request( 'GET', '/newfold-notifications/v1/notifications' );
		$request->set_query_params( array( 'context' => container()->plugin()->id . '-plugin' ) );
		$response = rest_do_request( $request );
		$server   = rest_get_server();

		$data['notifications'] = $server->response_to_data( $response, false );

		return $data;
	}
);
