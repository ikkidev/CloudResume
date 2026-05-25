<?php
namespace WPGDPRC\WordPress\Shortcodes;

use WPGDPRC\Utils\Debug;
use WPGDPRC\Utils\Helper;
use WPGDPRC\WordPress\Settings;

/**
 * Class AbstractShortcode
 * @package WPGDPRC\WordPress\Shortcodes
 */
abstract class AbstractShortcode {

	const SHORTCODE = '';

	/**
	 * AbstractShortcode constructor
	 */
	public function __construct() {
		$this->addShortcode( static::getShortcode() );
	}

	/**
	 * Adds the shortcode to WordPress setup
	 * @param string $shortcode
	 */
	public function addShortcode( string $shortcode = '' ) {
		add_shortcode( strtolower( $shortcode ), [ $this, 'parseShortcode' ] );
		add_shortcode( strtoupper( $shortcode ), [ $this, 'parseShortcode' ] );
	}

	/**
	 * Returns the shortcode slug
	 * @return string
	 */
	public static function getShortcode(): string {
		return static::SHORTCODE;
	}

	/**
	 * Generates the shortcode output
	 * @param array  $args
	 * @param string $content
	 * @return string
	 */
	abstract public function generateOutput( array $args = [], string $content = '' ): string;

	/**
	 * Parses the shortcode
	 * @param mixed  $args
	 * @param string $content
	 * @return string
	 */
	public function parseShortcode( $args = [], string $content = '' ): string {
		$args = ! is_iterable( $args ) ? [] : Helper::lowerKeys( $args );
		$args = $this->parseArgs( $args );
		return $this->generateOutput( $args, $content ) . $this->debugInfo( $args );
	}

	/**
	 * Parses the arguments
	 * @param array $args
	 * @return array
	 */
	protected function parseArgs( array $args = [] ): array {
		$defaults = $this->defaultArgs();
		if ( empty( $defaults ) ) {
			return $args;
		}

		return array_replace_recursive( $defaults, $args );
	}

	/**
	 * Lists the default arguments
	 * @return array
	 */
	protected function defaultArgs(): array {
		return [];
	}

	/**
	 * Lists shortcode args for debugging purposes
	 * @param array $args
	 * @return string
	 */
	protected function debugInfo( array $args = [] ): string {
		if ( ! is_user_logged_in() ) {
			return '';
		}
		if ( ! Debug::debugMode() ) {
			return '';
		}
		if ( empty( $args ) ) {
			return '';
		}

		return '<pre class="debug-dump">[' . static::getShortcode() . '] + ' . print_r( $args, true ) . '</pre>';
	}

	/**
	 * Lists all the post IDs using this shortcode in the content
	 * @return array
	 */
	public static function getPublishedPosts(): array {

		if ( ! empty( $result = Settings::getTransient( Settings::KEY_PUBLISHED_POSTS ) ) ) {
			return $result;
		}

		$result    = [];
		$shortcode = static::getShortcode();

		$args = [
			'post_type'   => 'any',
			'post_status' => [ 'publish' ],
			's'           => '[' . $shortcode . ']',
		];
		$list = get_posts( $args );
		if ( empty( $list ) ) {
			return $result;
		}

		foreach ( $list as $post ) {
			if ( ! has_shortcode( strtolower( $post->post_content ), $shortcode ) ) {
				continue;
			}
			$result[] = $post->ID;
		}

		Settings::setTransient( Settings::KEY_PUBLISHED_POSTS, $result, DAY_IN_SECONDS );
		return $result;
	}
}
