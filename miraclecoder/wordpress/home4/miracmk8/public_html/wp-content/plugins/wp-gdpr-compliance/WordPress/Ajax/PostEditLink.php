<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\WordPress\Plugin;

/**
 * Class PostEditLink
 * @package WPGDPRC\WordPress\Ajax
 */
class PostEditLink extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_post_edit_link';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

    /**
     * Lists the required data keys
     * @return array
     */
    public static function requiredData() {
        return ['post'];
    }

	/**
	 * Determines if AJAX call is sending a data attribute
	 * @return bool
	 */
	public static function hasData() {
		return false;
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
        if (!isset($_POST['post'])) {
            wp_send_json_error(__('No post ID provided', WP_GDPR_C_SLUG));
        }
		$message = get_edit_post_link( filter_var( wp_unslash( $_POST['post'] ), FILTER_SANITIZE_NUMBER_INT ) );
		static::returnSuccess( htmlspecialchars_decode( $message ) );
	}

}
