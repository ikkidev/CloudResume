<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

/**
 * APIs for getting the result from the Type Sense (Multi Site search)
 */
class MultiSearchController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'newfold-multi-search/v1';

	/**
	 * The base of this controller's route
	 *
	 * @var string
	 */
	protected $rest_base = 'multi_search';

	/**
	 * The API key for the multi-search service
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * The endpoint for the multi-search service
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * Constructor to initialize the API key and endpoint
	 */
	public function __construct() {
		$this->api_key  = 'B9wvYIokTPPgXEM3isTqsxbDOva21igT';
		$this->endpoint = 'https://search.hiive.cloud/multi_search?x-typesense-api-key=' . $this->api_key;
	}

	/**
	 * Register the routes for this objects of the controller
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_multi_search_result' ),
					'args'                => array(
						'query' => array(
							'required' => true,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		/**
		 * Register the routes for this objects of the controller
		 */
		register_rest_route(
			$this->namespace,
			'/tooltip_search',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_tooltip_search_result' ),
					'args'                => array(
						'postId' => array(
							'required' => true,
							'type'     => 'string',
						),
						'locale' => array(
							'required' => false,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Fetch the result from typesense
	 *
	 * @param \WP_REST_Request $request the REST request object
	 */
	public function get_multi_search_result( \WP_REST_Request $request ) {
		$brand = sanitize_text_field( $request->get_param( 'brand' ) );
		$query = sanitize_text_field( $request->get_param( 'query' ) );

		$params = array(
			'searches' => array(
				array(
					'q'                         => $query,
					'query_by'                  => 'post_title,post_content',
					'group_by'                  => 'post_title',
					'group_limit'               => 1,
					'sort_by'                   => '_text_match:desc,post_likes:desc',
					'filter_by'                 => 'post_category:=' . $brand,
					'prioritize_token_position' => true,
					'limit_hits'                => 3,
					'per_page'                  => 3,
					'highlight_full_fields'     => 'post_title,post_content',
					'collection'                => 'nfd_help_articles',
					'page'                      => 1,
				),
			),
		);

		$args = array(
			'body'    => wp_json_encode( $params ),
			'headers' => array(
				'Content-Type'        => 'application/json',
				'X-TYPESENSE-API-KEY' => $this->api_key,
			),
		);

		$response = wp_remote_post( $this->endpoint, $args );
		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'request_failed', __( 'The request failed', 'wp-module-help-center' ), array( 'status' => 500 ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		if ( empty( $data ) ) {
			return new \WP_Error( 'no_data', __( 'No data found', 'wp-module-help-center' ), array( 'status' => 404 ) );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Fetch the result from typesense
	 *
	 * @param \WP_REST_Request $request the REST request object
	 */
	public function get_tooltip_search_result( \WP_REST_Request $request ) {
		$postId = sanitize_text_field( $request->get_param( 'postId' ) );
		$locale = get_user_locale();

		$url = USER_INTERACTION_SERVICE_BASE . 'postContent/';

		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'timeout' => 60,
			'body'    => wp_json_encode(
				array(
					'postId' => $postId,
					'locale' => $locale,
				)
			),
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'request_failed', __( 'The request failed', 'wp-module-help-center' ), array( 'status' => 500 ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		if ( isset( $data['data']['status'] ) && 404 === $data['data']['status'] ) {
			return new \WP_Error( 'no_data', __( 'No data found', 'wp-module-help-center' ), array( 'status' => 404 ) );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Check permissions for routes.
	 *
	 * @return \WP_Error|boolean
	 */
	public function check_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'You must be authenticated to make this call', 'wp-module-help-center' ),
				array( 'status' => 401 )
			);
		}
		return true;
	}
}
