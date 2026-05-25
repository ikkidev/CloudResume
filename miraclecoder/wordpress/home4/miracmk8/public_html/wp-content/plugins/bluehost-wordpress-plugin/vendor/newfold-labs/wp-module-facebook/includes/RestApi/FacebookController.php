<?php
namespace NewfoldLabs\WP\Module\Facebook\RestApi;

use NewfoldLabs\WP\Module\Data\HiiveConnection;
use NewfoldLabs\WP\Module\Facebook\Services\FacebookService;
use NewfoldLabs\WP\Module\Facebook\Services\UtilityService;

class FacebookController
{
    protected $namespace = 'newfold-facebook/v1';

    protected $rest_base = '/facebook';

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base . '/hiive',
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_hiive_token'),
                    'permission_callback' => array($this, 'rest_is_authorized_admin')
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/logout',
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'logout'),
                    'permission_callback' => array($this, 'rest_is_authorized_admin')
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/details',
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_fb_details'),
                    'permission_callback' => array($this, 'rest_is_authorized_admin')
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/get-token',
            array(
                array(
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'post_fb_token'),
                    'permission_callback' => array($this, 'rest_is_authorized_admin')
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/post-token',
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_fb_token'),
                    'permission_callback' => array($this, 'rest_is_authorized_admin')
                ),
            )
        );
    }

    public function get_hiive_token()
    {
        $hiive_token = FacebookService::get_hiive_token();
        return new \WP_REST_Response(
            array(
                'token' => $hiive_token ? $hiive_token : '',
            ),
            200
        );
    }

    public function logout()
    {
        delete_option('nfd_fb_token');
        delete_option('nfd_fb_details');
        $fb = FacebookService::delete_token();
        return new \WP_REST_Response(
            array(
                'status' => 'success',
                'loggedIn' => false
            ),
            200
        );
    }

    public function get_fb_details()
    {
        $fb_details = FacebookService::get_fb_details();
        if (!empty($fb_details->error) || !empty($fb_details['error'])) {
            return new \WP_Error(
                'Bad request',
                $fb_details['error'],
                array('status' => 400)
            );
        }
        if ( is_string( $fb_details ) && preg_match( '/^token not found!$/', $fb_details ) ) {
			return new \WP_Error(
				'nfd_module_facebook_error',
				__( 'Details not found.', 'wp-module-facebook' ),
				array(
					'status' => 404,
				),
			);
        }
        else {
            return new \WP_REST_Response(
                array(
                    'status' => 'success',
                    'details' => $fb_details
                ),
                200
            );
        }
    }

    public function post_fb_token($request)
    {
        $body = json_decode($request->get_body());
        $message = FacebookService::post_token($body);
        return new \WP_REST_Response(
            array(
                'status' => 'success',
                'message' => $message
            ),
            200
        );
    }

    public function get_fb_token()
    {
        $fb_token = UtilityService::decrypt_token();
        if (!isset($fb_token)) {
            $fb_token = FacebookService::get_token();
            $fb_token = $fb_token->token;
        }
        return new \WP_REST_Response(
            array(
                'status' => 'success',
                'fb_token' => $fb_token
            ),
            200
        );
    }

    public static function rest_is_authorized_admin()
    {
        $admin = 'manage_options';
        return \is_user_logged_in() && \current_user_can($admin);
    }
}
