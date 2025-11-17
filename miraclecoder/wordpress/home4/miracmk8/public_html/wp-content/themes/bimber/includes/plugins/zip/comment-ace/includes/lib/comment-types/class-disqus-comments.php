<?php
/**
 * Disqus Comments class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Disqus_Comments extends Comment_Type {

    /**
     * Get unique id
     *
     * @return string
     */
    public function get_id() {
        return CACE_COMMENT_TYPE_DISQUS;
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function get_name() {
        return esc_html_x( 'Disqus', 'Comment Type Name', 'cace' );
    }

    /**
     * Render post comments
     */
    public function render() {
        $this->enqueue_scripts();

        get_template_part( 'disqus-comments', '', array(
            'comment_type' => $this
        ) );
    }

    /**
     * Return URL of Disqus JS SDK
     *
     * @return string
     */
    public function get_sdk_url() {
        $url = sprintf('https://%s.disqus.com/embed.js', $this->get_disqus_shortname() );

        return apply_filters( 'cace_dsq_sdk_url', $url );
    }

    /**
     * Return URL of Disqus Count JS
     *
     * @return string
     */
    public function get_count_js_url() {
        $url = sprintf('//%s.disqus.com/count.js', $this->get_disqus_shortname() );

        return apply_filters( 'cace_dsq_count_js_url', $url );
    }

    /**
     * Return Disqus shortname
     *
     * @return string
     */
    public function get_disqus_shortname() {
        return get_dsq_shortname();
    }

    /**
     * Enqueue scripts
     */
    protected function enqueue_scripts() {
        wp_enqueue_script( 'commentace-dsq-comments', plugin()->get_url() . 'assets/js/dsq-comments.js', array( 'commentace-comments' ), plugin()->get_version() );

        $locale = explode( '_', get_locale() );
        $lang_code = $locale[0];

        $config = array(
            'page_url'      => get_permalink(),
            'sdk_url'       => $this->get_sdk_url(),
            'count_js_url'  => $this->get_count_js_url(),
            'shortname'     => $this->get_disqus_shortname(),
            'language'      => $lang_code,
        );

        wp_localize_script( 'commentace-dsq-comments', 'commentace_dsq', $config );
    }
}
