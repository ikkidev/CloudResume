<?php
/**
 * The Template Part for displaying "Link Landing Page".
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Holds configuration settings for the link
 *
 * array['href']        string Url.
 *      ['classes']     array  CSS classes.
 *      ['target']      string Open method.
 *
 * @param array $bimber_link_data (See above)
 **/
global $bimber_link_data;
$bimber_link_data['classes'] = array_merge( array(
		'g1-button',
		'g1-button-subtle',
		'g1-button-l',
		'g1-button-wide',
		'g1-cta-button',
	),
	$bimber_link_data['classes']
);
?>
<div class="g1-cta">
	<p class="g1-cta-button-wrap">
		<a
			href="<?php echo esc_url( $bimber_link_data['href'] ); ?>"
			class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_link_data['classes'] ) ); ?>"
			target="<?php echo esc_attr( $bimber_link_data['target'] ); ?>"
			rel="nofollow">
			<?php echo esc_html( bimber_get_theme_option( 'post_link', 'visit_direct_link_label' ) ); ?>
		</a>
	</p>
</div>







