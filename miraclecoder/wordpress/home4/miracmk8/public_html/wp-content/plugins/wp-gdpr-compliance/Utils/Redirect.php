<?php
namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;

/**
 * Class Redirect
 * @package WPGDPRC\Utils
 */
class Redirect {

	/**
	 * Redirects to a given url (with optional message)
	 * @param string $url
	 * @param null $message
	 */
	public static function goToUrl( $url = '', $message = null ) {
		if ( is_null( $message ) ) {
			/* translators: %s: URL */
			$message = sprintf( __( 'Redirecting to %s', 'wp-gdpr-compliance' ), $url );
		}
		echo esc_html( $message );
		?><script>location.href = '<?php echo esc_js( $url ); ?>';</script>
		<?php
		die();
	}

	/**
	 * Replaces and changes the history with a given url
	 * @param string $url
	 */
	public static function replaceUrl( $url = '' ) {
		if ( empty( $url ) ) {
			return;
		}
        var_dump($url);
        exit();
		?>
		<script>history.replaceState({},'','<?php echo esc_js( $url ); ?>');</script>
		<?php
	}

	/**
	 * @param string $url
	 */
	public static function openNewTab( $url = '' ) {
		if ( empty( $url ) ) {
			return;
		}
		?>
		<script>window.open('<?php echo esc_js( $url ); ?>', '_blank');</script>
		<?php
	}

}
