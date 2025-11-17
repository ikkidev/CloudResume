<?php
/**
 * Quiz template part
 *
 * @package snax 1.11
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php
$snax_quiz_class = array(
	'quiz',
);
$snax_quiz_class[] = 'snax-quiz-amp';
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_quiz_class ) ); ?>">

	<p class="snax-quiz-actions">
		<a class="g1-button g1-button-l g1-button-wide g1-button-solid" href="<?php echo esc_attr( get_permalink( ) ); ?>">
			<?php esc_html_e( 'Let\'s play', 'snax' ); ?>
		</a>
	</p>


</div><!-- .quiz -->
