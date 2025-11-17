<?php
namespace NewfoldLabs\WP\Module\Facebook\Services;

class FacebookHelperService
{
    public static function get_fb_posts($result, $FacebookData, $fb_token)
    {
        $post_url = NFD_FACEBOOK_GRAPH_BASE . '/' . $result->id . '/posts?fields=id,name,message,story,created_time,link,description,caption,attachments{media,type,subattachments}&limit=10&access_token=' . $fb_token . '&format=json';
        $post_results = wp_remote_get(
            $post_url,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $posts_response = json_decode(wp_remote_retrieve_body($post_results));
        if ($posts_response && $posts_response->data) {
            $FacebookData->get_user()->set_posts($posts_response->data);
        }
    }

    public static function get_fb_images($result, $FacebookData, $fb_token)
    {
        $image_url = NFD_FACEBOOK_GRAPH_BASE . '/me/photos/uploaded?fields=link,picture,alt_text,created_time,id&limit=10&access_token=' . $fb_token . '&format=json';
        $image_results = wp_remote_get(
            $image_url,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $images_response = json_decode(wp_remote_retrieve_body($image_results));
        if ($images_response && $images_response->data) {
            $FacebookData->get_user()->set_images($images_response->data);
        }
    }

    public static function get_fb_business($result, $FacebookData, $fb_token)
    {
        $business_url = NFD_FACEBOOK_GRAPH_BASE . '/me/accounts?fields=category%2Ccategory_list%2Cname%2Cid%2Ctasks&access_token=' . $fb_token . '&format=json';
        $business_results = wp_remote_get(
            $business_url,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $business_response = json_decode(wp_remote_retrieve_body($business_results));

        if ($business_response && $business_response->data) {
            $FacebookData->get_business()->set_profile($business_response->data);
        }
    }

    public static function get_fb_business_posts($result, $FacebookData, $fb_token)
    {
        $business_post_token = NFD_FACEBOOK_GRAPH_BASE . '/' . $FacebookData->get_business()->get_profile()[0]->id . '?fields=access_token&access_token=' . $fb_token . '&format=json';
        $business_post_token_request = wp_remote_get(
            $business_post_token,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $business_post_token_result = json_decode(wp_remote_retrieve_body($business_post_token_request));

        if ($business_post_token_result) {
            $business_posts = NFD_FACEBOOK_GRAPH_BASE . '/' . $FacebookData->get_business()->get_profile()[0]->id . '/feed?access_token=' . $business_post_token_result->access_token . '&format=json';
            $business_post_response = wp_remote_get(
                $business_posts,
                array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ),
                )
            );;
            $business_posts_results = json_decode(wp_remote_retrieve_body($business_post_response));

            if ($business_posts_results && $business_posts_results->data) {
                $FacebookData->get_business()->set_posts($business_posts_results->data);
            }
        }
    }

    public static function get_fb_business_images($result, $FacebookData, $fb_token)
    {
        $business_images = NFD_FACEBOOK_GRAPH_BASE . '/' . $FacebookData->get_business()->get_profile()[0]->id . '/photos?fields=created_time,alt_text,picture,id&limit=10&access_token=' . $fb_token . '&format=json';
        $business_images_response = wp_remote_get(
            $business_images,
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ),
            )
        );
        $business_images_results = json_decode(wp_remote_retrieve_body($business_images_response));

        if ($business_images_results && $business_images_results->data) {
            $FacebookData->get_business()->set_images($business_images_results->data);
        }
    }
}
