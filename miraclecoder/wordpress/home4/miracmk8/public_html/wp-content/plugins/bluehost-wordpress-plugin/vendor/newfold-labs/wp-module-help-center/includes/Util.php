<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

/**
 * The utility pass through for interacting with the
 * AI service
 */
class Util {
	/**
	 * The function to proxy to the AI service and get a response
	 *
	 * @param integer $post_id  the Post Id
	 * @param string  $feedback the feedback
	 */
	public static function post_feedback(
		$post_id,
		$feedback
	) {
		if ( ! function_exists( 'wp_json_encode' ) ) {
			require_once ABSPATH . 'wp-includes/functions.php';
		}

		$response = wp_remote_post(
			USER_INTERACTION_SERVICE_BASE . 'incrementMeta/',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 60,
				'body'    => wp_json_encode(
					array(
						'postId' => $post_id,
						'meta'   => 'helpful' === $feedback ? 'likes' : 'dislikes',
					)
				),
			)
		);
		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return false;
		}
		return true;
	}
}
