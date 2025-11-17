<?php

namespace NewFoldLabs\WP\Module\Marketplace;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class MarketplaceApi
 */
class MarketplaceApi {

	/**
	 * Transient name where marketplace data is stored.
	 */
	const TRANSIENT = 'newfold_marketplace';

	/**
	 * Register marketplace routes.
	 */
	public static function registerRoutes() {

		// Add route for fetching marketplace products per brand
		register_rest_route(
			'newfold-marketplace/v1',
			'/marketplace',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => __CLASS__ . '::marketplace_callback',
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'newfold-marketplace/v1',
			'/products/page',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => __CLASS__ . '::product_page_callback',
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

	}

	/**
	 * Set the transient where marketplace is stored.
	 *
	 * @param string    $data json of marketplace.
	 * @param float|int $expiration    Transient expiration.
	 */
	public static function setTransient( $data, $expiration = DAY_IN_SECONDS ) {
		set_transient( self::TRANSIENT, $data, $expiration );
	}

	/**
	 * Get expiration from response
	 *
	 * @param array $marketplace Response from marketplace endpoint.
	 *
	 * @return float|int|mixed
	 */
	public static function get_expiration( $marketplace ) {
		// get response['meta']['ttl'] if it exists, otherwise set to default 24hrs
		return array_key_exists( 'meta', $marketplace ) &&
			array_key_exists( 'ttl', $marketplace['meta'] ) ?
			$marketplace['meta']['ttl'] :
			DAY_IN_SECONDS;

	}

	/**
	 * Get marketplace data
	 *
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 *
	 * @throws \NewfoldLabs\Container\NotFoundException When item is not found in the container.
	 * @throws \WP_Forge\Container\NotFoundException When item is not found in the container.
	 */
	public static function marketplace_callback( \WP_REST_Request $request ) {

		$marketplace = get_transient( self::TRANSIENT );

		if ( false === $marketplace ) {

			$args = array(
				'per_page' => 60,
				// if marketplace brand is set on container,
				// use it as brand override,
				// otherwise use plugin id (default)
				'brand'    => container()->has( 'marketplace_brand' ) ?
								container()->get( 'marketplace_brand' ) :
								container()->plugin()->id,
			);

			$products   = self::product_data( $args );
			$categories = self::category_data( $args );

			if ( $products && $categories ) {
				$marketplace = wp_json_encode(
					array(
						'categories' => $categories,
						'products'   => $products,
					)
				);
				$expiration  = self::get_expiration( $products );
				self::setTransient( $marketplace, $expiration );
			}
		}
		return rest_ensure_response( json_decode( $marketplace ) );
	}

	/**
	 * Get product data from products endpoint
	 *
	 * @param array $args Query arguments for endpoint request.
	 */
	public static function product_data( $args ) {
		// construct endpoint with args
		$marketplace_endpoint = add_query_arg(
			$args,
			NFD_HIIVE_URL . '/marketplace/v1/products'
		);

		$response = wp_remote_get(
			$marketplace_endpoint,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );
			if (
				$data &&
				is_array( $data ) &&
				array_key_exists( 'data', $data )
			) {
				return $data;
			}
		}

		return false;
	}

	/**
	 * Get category data from categories endpoint
	 *
	 * @param array $args Query arguments for endpoint request.
	 */
	public static function category_data( $args ) {
		// construct endpoint with args
		$category_endpoint = add_query_arg(
			$args,
			NFD_HIIVE_URL . '/marketplace/v1/products/categories'
		);

		$response = wp_remote_get(
			$category_endpoint,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );
			if (
				$data &&
				is_array( $data ) &&
				array_key_exists( 'data', $data )
			) {
				return $data;
			}
		}

		return false;
	}

	/**
	 * Get product page data
	 *
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public static function product_page_callback( \WP_REST_Request $request ) {
		$page                   = $request->get_param( 'id' );
		$product_pages_ednpoint = NFD_HIIVE_URL . '/marketplace/v1/products/pages/' . $page;
		$data                   = array();

		$response = wp_remote_get(
			$product_pages_ednpoint,
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
			)
		);

		if (
			! is_wp_error( $response )
			&& 200 === wp_remote_retrieve_response_code( $response )
		) {
			$body = wp_remote_retrieve_body( $response );
			if ( $body ) {
				$data['html'] = $body;
			}
		}

		return rest_ensure_response( $data );
	}
}
