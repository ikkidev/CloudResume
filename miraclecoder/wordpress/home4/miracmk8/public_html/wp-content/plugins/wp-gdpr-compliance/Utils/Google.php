<?php

namespace WPGDPRC\Utils;

use Cassandra\Set;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Google
 * @package WPGDPRC\Utils
 */
class Google {

	/**
	 * Array with the top 50 popular Google Fonts
	 * @return string[]
	 */
	public static function getPopularFonts() {
		return [
			'Anton',
			'Arimo',
			'Barlow',
			'Bebas Neue',
			'Bitter',
			'Cabin',
			'Dosis',
			'Fira Sans',
			'Heebo',
			'Hind Siliguri',
			'Inconsolata',
			'Inter',
			'Josefin Sans',
			'Karla',
			'Lato',
			'Libre Baskerville',
			'Libre Franklin',
			'Lora',
			'Merriweather',
			'Montserrat',
			'Mukta',
			'Mulish',
			'Nanum Gothic',
			'Noto Sans',
			'Noto Sans JP',
			'Noto Sans KR',
			'Noto Sans TC',
			'Noto Serif',
			'Nunito',
			'Nunito Sans',
			'Open Sans',
			'Oswald',
			'Oxygen',
			'PT Sans',
			'PT Sans Narrow',
			'PT Serif',
			'Playfair Display',
			'Poppins',
			'Quicksand',
			'Raleway',
			'Roboto',
			'Roboto Condensed',
			'Roboto Mono',
			'Roboto Slab',
			'Rubik',
			'Source Code Pro',
			'Source Sans Pro',
			'Titillium Web',
			'Ubuntu',
			'Work Sans',
		];
	}

	/**
	 * Setup array with key values
	 * @return array
	 */
	public static function getPopularFontsList() {
		$fonts = static::getPopularFonts();
		$list  = [];

		if ( ! $fonts ) {
			return $list;
		}

		foreach ( $fonts as $font ) {
			$list[ $font ] = $font;
		}

		return $list;
	}

}
