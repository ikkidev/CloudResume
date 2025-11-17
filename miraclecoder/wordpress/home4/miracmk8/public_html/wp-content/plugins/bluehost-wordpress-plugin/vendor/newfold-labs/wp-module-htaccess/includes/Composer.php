<?php
/**
 * Composer for Htaccess fragments.
 *
 * Responsible for assembling registered fragments into a single,
 * canonical .htaccess payload with a standard header and normalized
 * whitespace/newlines.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Class Composer
 *
 * @since 1.0.0
 */
class Composer {

	/**
	 * Module version used in the header. Override via setter if needed.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Site host cached for header rendering.
	 *
	 * @var string
	 */
	protected $host = '';

	/**
	 * Constructor: initialize version from global constant if available.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( defined( 'NFD_MODULE_HTACCESS_VERSION' ) && is_string( NFD_MODULE_HTACCESS_VERSION ) ) {
			$this->version = NFD_MODULE_HTACCESS_VERSION;
		}
	}

	/**
	 * Set the version string to be emitted in the header.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version Semantic version string.
	 * @return void
	 */
	public function set_version( $version ) {
		$this->version = (string) $version;
	}

	/**
	 * Set the site host (used in header).
	 *
	 * @since 1.0.0
	 *
	 * @param string $host Host label (e.g., example.com).
	 * @return void
	 */
	public function set_host( $host ) {
		$this->host = (string) $host;
	}

	/**
	 * Compose the final .htaccess text from enabled fragments.
	 *
	 * Adds a standardized header with version, host and checksum,
	 * then concatenates fragment renders in order, normalizing line
	 * endings and ensuring a single trailing newline.
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment[] $fragments Enabled, sorted fragments.
	 * @param mixed      $context   Optional render context passed to fragments.
	 * @return string Canonical .htaccess content.
	 */
	public function compose( $fragments, $context = null ) {
		$blocks = array();

		if ( is_array( $fragments ) ) {
			foreach ( $fragments as $fragment ) {
				if ( ! $fragment instanceof Fragment ) {
					continue;
				}
				$rendered = (string) $fragment->render( $context );
				$rendered = Text::normalize_lf( $rendered, false );
				$rendered = Text::trim_surrounding_blank_lines( $rendered );

				if ( '' !== $rendered ) {
					$blocks[] = $rendered;
				}
			}
		}

		$body = implode( "\n\n", $blocks );
		$body = Text::ensure_single_trailing_newline( $body );

		$header = $this->build_header( $body );
		$out    = $header . "\n" . $body;

		return Text::ensure_single_trailing_newline( $out );
	}

	/**
	 * Build the standardized header including checksum.
	 *
	 * @since 1.0.0
	 *
	 * @param string $body Canonical body used for checksum.
	 * @return string Header text (without trailing newline).
	 */
	protected function build_header( $body ) {
		$checksum = hash( 'sha256', (string) $body );
		$host     = $this->host;
		if ( '' === $host ) {
			$host = $this->detect_host();
		}
		if ( '' === $host ) {
			$host = '-';
		}

		$applied = $this->utc_now_iso8601();

		$lines = array(
			'# Managed by Newfold Htaccess Manager v' . $this->version . ' (' . $host . ')',
			'# STATE sha256: ' . $checksum . ' applied: ' . $applied,
		);

		return implode( "\n", $lines );
	}

	/**
	 * Best-effort host detection for header context.
	 *
	 * @since 1.0.0
	 *
	 * @return string Host (example.com) or empty string.
	 */
	protected function detect_host() {
		if ( function_exists( 'home_url' ) ) {
			$url = home_url( '/' );
			$hp  = wp_parse_url( $url, PHP_URL_HOST );
			return is_string( $hp ) ? $hp : '';
		}
		return '';
	}

	/**
	 * Current UTC time in ISO8601 (YYYY-MM-DDTHH:MM:SSZ).
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function utc_now_iso8601() {
		try {
			$dt = new DateTimeImmutable( 'now', new DateTimeZone( 'UTC' ) );
			return $dt->format( 'Y-m-d\TH:i:s\Z' );
		} catch ( \Exception $e ) { // phpcs:ignore WordPress.PHP.EscapeOutput.OutputNotEscaped
			return gmdate( 'Y-m-d\TH:i:s\Z' );
		}
	}

	/**
	 * Compose NFD fragments into a single body (no header), separated by a blank line.
	 * Normalizes line-endings and trims leading/trailing whitespace for each fragment.
	 * Returns NO trailing newline (stable checksums).
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment[] $fragments Fragments to render.
	 * @param mixed      $context   Optional render context passed to fragments.
	 * @return string Body text (no trailing newline).
	 */
	public static function compose_body_only( $fragments, $context = null ) {
		$blocks = array();

		if ( is_array( $fragments ) ) {
			foreach ( $fragments as $fragment ) {
				if ( ! $fragment instanceof Fragment ) {
					continue;
				}
				$rendered = (string) $fragment->render( $context );
				// Normalize and trim like the existing call sites.
				$rendered = Text::normalize_lf( $rendered, false );
				$rendered = Text::trim_surrounding_blank_lines( $rendered );
				if ( '' !== $rendered ) {
					$blocks[] = $rendered;
				}
			}
		}

		$body = implode( "\n\n", $blocks );
		return rtrim( $body, "\n" ); // no trailing newline
	}
}
