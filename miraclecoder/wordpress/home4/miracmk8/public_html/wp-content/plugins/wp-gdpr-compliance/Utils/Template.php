<?php
namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;

/**
 * Class Template
 * @package WPGDPRC\Utils
 */
class Template {

	/**
	 * Renders the template file
	 * @param string $path
	 * @param array  $args
	 */
	public static function render( $path = '', $args = [] ) {
		$path = Plugin::getTemplatesDir() . $path;
		if ( ! Helper::endsWith( $path, '.php' ) ) {
			$path .= '.php';
		}

		self::includePath( $path, $args );
	}

	/**
	 * Gets the content of the rendered template file
	 * @param string $path
	 * @param array  $args
	 * @return string
	 */
	public static function get( $path = '', $args = [] ) {
		ob_start();
		static::render( $path, $args );
		return ob_get_clean();
	}

	/**
	 * Renders the svg file
	 * @param string $path
	 */
	public static function renderSvg( $path = '' ) {
		self::includePath( Plugin::getSvgDir() . $path );
	}

	/**
	 * Gets the content of the rendered svg file
	 * @param string $path
	 * @param bool   $inline
	 * @return string
	 */
	public static function getSvg( $path = '', $inline = false ) {
		ob_start();
		static::renderSvg( $path );
		return ob_get_clean();
	}

	/**
	 * Render fa icon.
	 *
	 * @param $icon
	 * @param string $sprite
	 */
	public static function renderIcon( $icon, $sprite = 'fontawesome-pro-light' ) {
		self::includePath(
			Plugin::getTemplatesDir() . 'Common/icon.php',
			[
				'sprite' => $sprite,
				'icon'   => $icon,
			]
		);
	}

	/**
	 * Gets the content of the icon
	 * @param string $path
	 * @param bool   $inline
	 * @return string
	 */
	public static function getIcon( $icon, $sprite = 'fontawesome-pro-light' ) {
		ob_start();
		static::renderIcon( $icon, $sprite );
		return ob_get_clean();
	}

	/**
	 * @param string $path
	 * @param array  $args
	 */
	public static function includePath( $path = '', $args = [] ) {
		if ( ! is_readable( $path ) ) {
			if ( ! is_user_logged_in() ) {
				return;
			}
			wp_die( 'Could not find or read file at: ' . esc_html( $path ) );
		}

		extract( $args );
		/** @noinspection PhpIncludeInspection */
		include $path;
	}

}
