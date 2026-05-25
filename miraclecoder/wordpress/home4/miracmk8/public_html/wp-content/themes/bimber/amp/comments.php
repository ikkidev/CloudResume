<?php
/**
 * The Template Part for displaying Comments.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.0.2
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $post;

if ( post_password_required() ) {
	return;
}
?>
<?php if ( comments_open() ) : ?>
<p>
	<a class="g1-button g1-button-l g1-button-wide g1-button-solid" href="<?php echo esc_attr( get_permalink( $post->ID ) ); ?>#respond">
		<?php esc_html_e( 'Leave a Reply', 'bimber' ); ?>
	</a>
</p>
<?php endif; ?>
