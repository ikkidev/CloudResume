<?php
/**
 * Restrict Content Pro plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/restrict-content-pro/menu-endpoints.php';

add_filter( 'the_excerpt',              'bimber_rcp_message_filter', 1, 1 );
add_filter( 'the_content',              'bimber_rcp_message_filter', 1, 1 );
add_filter( 'bimber_get_entry_flags',   'bimber_rcp_add_restricted_flag' );
add_filter( 'comments_open',            'bimber_rcp_comments_open', 1, 2 );
add_filter( 'get_comments_number',      'bimber_rcp_get_comments_number', 1, 2 );

// Hide elements for users with no access.
add_filter( 'bimber_wyr_load_post_voting_box',  'bimber_wyr_rcp_hide_post_voting_box', 11, 1 );
add_filter( 'snax_render_post_voting_box',      'snax_rcp_render_post_voting_box', 11, 1 );

/**
 * Add custom box to restricted content message.
 *
 * @param string $content           Original RCP message.
 *
 * @return string
 */
function bimber_rcp_message_filter( $content ) {
	// Execute only once.
	static $done = false;
	if ( is_singular() && ! $done ) {
		ob_start();
			get_template_part( 'template-parts/rcp/message', 'paid' );
		$paid_box = ob_get_clean();
		ob_start();
			get_template_part( 'template-parts/rcp/message', 'free' );
		$free_box = ob_get_clean();
		// Get RCP Options so we can know were we are. Simply $message is not enough.
		global $rcp_options;
		$paid = isset( $rcp_options['paid_message'] ) ? $rcp_options['paid_message'] : '';
		$free = isset( $rcp_options['free_message'] ) ? $rcp_options['free_message'] : '';
		$rcp_options['paid_message'] = $paid_box . $paid;
		$rcp_options['free_message'] = $free_box . $free;
		$done = true;
	}
	return $content;
}

function bimber_rcp_add_restricted_flag( $flags ) {
	if ( function_exists( 'rcp_user_can_access' ) && ! rcp_user_can_access( get_current_user_id(), get_the_ID() ) ) {
		$flags['members_only'] = array(
			'label' => sprintf( esc_html__( 'Members %s Only', 'bimber' ), '<br>' ),
			'url'   => '',
			'title' => esc_html__( 'Members Only', 'bimber' ),
		);
	}

	return $flags;
}

function bimber_rcp_comments_open( $open, $post_id ) {
	if ( ! rcp_is_active() && rcp_is_paid_content( $post_id ) ) {
		$open = false;
	}

	return $open;
}

function bimber_rcp_get_comments_number( $count, $post_id ) {
	if ( ! rcp_is_active() && rcp_is_paid_content( $post_id ) ) {
		$count = 0;
	}

	return $count;
}

/**
 * Hide Reactions box if user can't access the post
 *
 * @param bool $show        Whether to show it or not.
 *
 * @return bool
 */
function bimber_wyr_rcp_hide_post_voting_box( $show ) {
	if ( ! rcp_user_can_access() ) {
		$show = false;
	}

	return $show;
}

/**
 * Hide Voting box if user can't access the post
 *
 * @param bool $show        Whether to show it or not.
 *
 * @return bool
 */
function snax_rcp_render_post_voting_box( $show ) {
	if ( ! rcp_user_can_access() ) {
		$show = false;
	}

	return $show;
}