<?php
/**
 * Facebook Comments class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Facebook_Comments extends Comment_Type {

    /**
     * Get unique id
     *
     * @return string
     */
    public function get_id() {
        return CACE_COMMENT_TYPE_FACEBOOK;
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function get_name() {
        return esc_html_x( 'Facebook', 'Comment Type Name', 'cace' );
    }

    /**
     * Render post comments
     */
    public function render() {
        $this->enqueue_scripts();

        get_template_part( 'facebook-comments', '', array(
            'comment_type' => $this
        ) );
    }

    /**
     * Return URL of Facebook JS SDK
     *
     * @return string
     */
    public function get_sdk_url() {
        $url = sprintf('https://connect.facebook.net/%s/sdk.js', get_locale() );

        return apply_filters( 'cace_fb_sdk_url', $url );
    }

    /**
     * Return Facebook App ID
     *
     * @return string
     */
    public function get_app_id() {
        return get_fb_app_id();
    }

    /**
     * Enqueue scripts
     */
    protected function enqueue_scripts() {
        wp_enqueue_script( 'commentace-fb-comments', plugin()->get_url() . 'assets/js/fb-comments.js', array( 'commentace-comments' ), plugin()->get_version() );

        $config = array(
            'sdk_url'       => $this->get_sdk_url(),
            'app_id'        => $this->get_app_id(),
        );

        wp_localize_script( 'commentace-fb-comments', 'commentace_fb', $config );
    }
}
