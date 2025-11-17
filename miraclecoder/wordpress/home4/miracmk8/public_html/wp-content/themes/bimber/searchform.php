<?php
/**
 * The Template Part for displaying search form.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.4
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php
	$bimber_searchform = array(
		'final_class'       => array( 'g1-searchform-tpl-default' ),
		'input_label'       => _x( 'Search for:', 'placeholder', 'bimber' ),
		'input_placeholder' => bimber_get_search_input_placholder(),
		'submit_label'      => _x( 'Search', 'submit button', 'bimber' ),
	);

	if ( bimber_is_ajax_search_enabled() ) {
		$bimber_searchform['final_class'][] = 'g1-searchform-ajax';
	}
?>

<div role="search" class="search-form-wrapper">
	<form method="get"
	      class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_searchform['final_class'] ) ); ?> search-form"
	      action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
			<span class="screen-reader-text"><?php echo esc_attr( $bimber_searchform['input_label'] ); ?></span>
			<input type="search" class="search-field"
			       placeholder="<?php echo esc_attr( $bimber_searchform['input_placeholder'] ); ?>"
			       value="<?php echo esc_attr( get_search_query() ); ?>" name="s"
			       title="<?php echo esc_attr( $bimber_searchform['input_label'] ); ?>" />
		</label>
		<button class="search-submit"><?php echo esc_html( $bimber_searchform['submit_label'] ); ?></button>
	</form>

	<?php if ( bimber_is_ajax_search_enabled() ) : ?>
		<div class="g1-searches g1-searches-ajax"></div>
	<?php endif; ?>
</div>
