<?php

namespace NewfoldLabs\WP\Module\Facebook\Services;

use NewfoldLabs\WP\Module\Data\HiiveConnection;
use NewfoldLabs\WP\Module\Facebook\Accessors\SocialData;
use NewfoldLabs\WP\Module\Facebook\Services\FacebookHelperService;
use NewfoldLabs\WP\Module\Facebook\Services\UtilityService;

class FacebookService
{
    public static function get_hiive_token()
    {
        $hiive_token = HiiveConnection::get_auth_token();
        $hash_token = get_option('nfd_fb_hash_hiive_token');
        $encrypted_token = null;
        if (!$hash_token) {
            $encrypted_token = $hiive_token ? wp_hash($hiive_token) : '';
            update_option('nfd_fb_hash_hiive_token', $encrypted_token);
        }
        return $hash_token ? $hash_token : $encrypted_token;
    }

    public static function post_token($data)
    {
        $details = array(
            'token' => UtilityService::encrypt_token($data->token),
            'expires_on' => $data->expiresIn
        );
        update_option('nfd_fb_token', $details);
        UtilityService::storeTokenInCookie($details);
        return "updated successfully!";
    }

    public static function get_token()
    {
        $hiive_token = FacebookService::get_hiive_token();
        $url = NFD_FACEBOOK_WORKER . 'get/token?hiive_token=' . $hiive_token;
        $request = wp_remote_get(
            $url,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $response = json_decode(wp_remote_retrieve_body($request));

        if (is_wp_error($request)) {
            return array('error' => "we're unable to process the request!");
        }
        if (!empty($response) && $response->token) {
            $details = array(
                'token' => UtilityService::encrypt_token($response->token),
                'expires_on' => $response->expiresIn
            );
            update_option('nfd_fb_token', $details);
            UtilityService::storeTokenInCookie($details);
        }
        return $response ? $response : null;
    }

    public static function delete_token()
    {
        $hiive_token = FacebookService::get_hiive_token();
        $url = NFD_FACEBOOK_WORKER . 'delete/token?hiive_token=' . $hiive_token;
        $request = wp_remote_get(
            $url,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        if (is_wp_error($request)) {
            return array('error' => "we're unable to process the request!");
        }
        UtilityService::deleteTokenFromCookie();
        return array('message' => 'token deleted successfully!');
    }

    public static function get_fb_details()
    {
        $FacebookData = new SocialData();
        $data = get_option('nfd_fb_details');
        if ($data) {
            return array($data);
        }
        $fb_token = UtilityService::decrypt_token();
        if (!($fb_token)) {
            $fb_token = FacebookService::get_token();
            $fb_token = $fb_token && $fb_token->token;
        }
        if (isset($fb_token) && $fb_token) {
            $url = NFD_FACEBOOK_GRAPH_BASE . '/me?fields=id,name,email,picture&access_token=' . $fb_token;
            $request = wp_remote_get(
                $url,
                array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ),
                )
            );
            if (is_wp_error($request)) {
                return array('error' => 'Error while trying to fetch the data from facebook!');
            }
            $response = json_decode(wp_remote_retrieve_body($request));
            if ($response && $response->id) {
                FacebookHelperService::get_fb_posts($response, $FacebookData, $fb_token);
                FacebookHelperService::get_fb_images($response, $FacebookData, $fb_token);
                FacebookHelperService::get_fb_business($response, $FacebookData, $fb_token);
                FacebookHelperService::get_fb_business_posts($response, $FacebookData, $fb_token);
                FacebookHelperService::get_fb_business_images($response, $FacebookData, $fb_token);
            } else {
                return array('error' => "we're unable to process the request");
            }

            $FacebookData->set_source('facebook');
            $FacebookData->get_user()->set_profile($response);

            // need to fetch and attach data for future
            update_option('nfd_fb_details', $FacebookData);
            return array($FacebookData);
        }
        return 'token not found!';
    }
}
