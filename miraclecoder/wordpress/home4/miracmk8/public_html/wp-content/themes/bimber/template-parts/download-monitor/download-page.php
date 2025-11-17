<?php
/**
 * The Template Part for displaying "Download Page".
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

global $bimber_download_data;
?>

<div id="g1-download-page-content">

	<h1 class="g1-alpha g1-alpha-1st"><?php echo esc_html( sprintf( __( 'Downloading "%s"', 'bimber' ), get_the_title( $bimber_download_data['download_id'] ) ) ); ?></h1>

	<?php if ( $bimber_download_data['delay'] > 0 ) : ?>
		<p id="g1-download-countdown" class="g1-epsilon g1-epsilon-1st g1-countdown">
			<?php
			$bimber_delay = (int) $bimber_download_data['delay'];
			echo wp_kses_post( sprintf( _n( 'in %s second', 'in %s seconds', $bimber_delay, 'bimber' ), '<span class="g1-countdown-number">'. $bimber_download_data['delay'] .'</span>' ) );
			?>
		</p>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/ads/ad-download-page' ); ?>

	<p id="g1-download-fallback">
		<?php echo wp_kses_post( sprintf( __( 'Please <a href="%s">click here</a> if downloading doesn\'t run automatically.', 'bimber' ), $bimber_download_data['target_url'] ) ); ?>
	</p>
	<script>
		(function($) {
			var runDownload = function() {
				window.location.href = '<?php echo esc_url( $bimber_download_data['target_url'] ); ?>';
			};
			var delay = parseInt(<?php echo absint( $bimber_download_data['delay'] ); ?>, 10);
			var singular = '<?php echo wp_kses_post( sprintf( _n( 'in %s second', 'in %s seconds', 1, 'bimber' ), '<span class="g1-countdown-number">1</span>' ) ); ?>';
			var interval = setInterval(function() {
				if ( delay > 0 ) {
					delay--;
					if (delay > 1) {
						$('#g1-download-countdown .g1-countdown-number').text(delay);
					} else {
						$('#g1-download-countdown').html(singular);
					}
				} else {
					clearInterval(interval);
					runDownload();
				}
			}, 1000);
		})(jQuery);
	</script>
</div>
