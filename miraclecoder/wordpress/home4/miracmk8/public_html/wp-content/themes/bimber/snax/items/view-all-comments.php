<?php
/**
 * Template for displaying "View all comments" button
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<a class="snax-item-comments-more-link g1-link g1-link-s" href="<?php echo esc_url( get_comments_link( $args['item_id'] ) ); ?>"><?php esc_html_e( 'View More Comments', 'snax' ); ?></a>