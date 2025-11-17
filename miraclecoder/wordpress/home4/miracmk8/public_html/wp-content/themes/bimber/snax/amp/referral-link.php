<?php
/**
 * Referral Link for AMP
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$link = '';
$post = get_post( );
$ref_link = get_post_meta( $post->ID, '_snax_ref_link', true );
if ( ! empty( $ref_link ) ) {
?>
	<p>
		<a class="g1-button g1-button-l g1-button-wide g1-button-solid" href="<?php echo esc_url_raw( $ref_link ); ?>">
			<?php esc_html_e( 'Buy now', 'snax' ); ?>
		</a>
	</p>
	<?php
}
