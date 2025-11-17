<?php
namespace NewfoldLabs\WP\Module\Performance\RestApi;

use NewfoldLabs\WP\Module\Performance\Skip404\Skip404;

/**
 * Class Settings
 *
 * @package NewfoldLabs\WP\Module\Performance
 */
class Skip404Controller {

	/**
	 * The REST route namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'newfold-performance/v1';

	/**
	 * The REST route base.
	 *
	 * @var string
	 */
	protected $rest_base = '/skip404';

	/**
	 * Register API routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'set_options' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				),
			)
		);
	}

	/**
	 * Set Jetpack options.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response
	 */
	public function set_options( $request ) {
		try {
			$params = $request->get_params();

			if ( ! isset( $params['field'] ) || ! is_array( $params['field'] ) ) {
				return new \WP_REST_Response(
					array(
						'success' => false,
						'error'   => __( "The parameter 'field' is missing or invalid.", 'wp-module-performance' ),
					),
					400
				);
			}

			$field = $params['field'];

			if ( ! isset( $field['id'], $field['value'] ) ) {
				return new \WP_REST_Response(
					array(
						'success' => false,
						'error'   => __( "The fields 'id' and 'value' are required.", 'wp-module-performance' ),
					),
					400
				);
			}

			switch ( $field['id'] ) {
				case 'skip404':
					$bool_value    = filter_var( $field['value'], FILTER_VALIDATE_BOOLEAN );
					$current_value = get_option( Skip404::OPTION_NAME, 'not_set' );
					if ( 'not_set' === $current_value ) {
						add_option( Skip404::OPTION_NAME, $bool_value );
						break;
					}
					if ( $current_value !== $bool_value ) {
						$result = update_option( Skip404::OPTION_NAME, $bool_value );
						if ( false === $result ) {
							return new \WP_REST_Response(
								array(
									'success' => false,
									'error'   => __( 'An error occurred while updating the option.', 'wp-module-performance' ),
								),
								500
							);
						}
					}
					break;
				default:
					return new \WP_REST_Response(
						array(
							'success' => false,
							'error'   => __( 'Invalid field ID provided.', 'wp-module-performance' ),
						),
						400
					);
			}

			return new \WP_REST_Response(
				array(
					'success'        => true,
					'updated_option' => $field['id'],
					'updated_value'  => isset( $bool_value ) ? $bool_value : $field['value'],
				),
				200
			);
		} catch ( \Exception $e ) {
			return new \WP_REST_Response(
				array(
					'success' => false,
					'error'   => __( 'An error occurred while updating the option.', 'wp-module-performance' ) . $e->getMessage(),
				),
				500
			);
		}
	}
}
