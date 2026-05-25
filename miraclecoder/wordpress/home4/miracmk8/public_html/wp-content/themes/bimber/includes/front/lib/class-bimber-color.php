<?php
/**
 * Class to get color properties (hex, rgb, lightness etc)
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


if ( ! class_exists( 'Bimber_Color' ) ) :

	/**
	 * Class Bimber_Color
	 */
	class Bimber_Color {
		const SYSTEM_RGB = 'rgb';
		const SYSTEM_HSL = 'hsl';
		const SYSTEM_HEX = 'hex';

		/**
		 * RGB representation of color
		 *
		 * @var array $rgb
		 */
		protected $rgb;

		/**
		 * HSL representation of color
		 *
		 * @var array $hsl
		 */
		protected $hsl;

		/**
		 * Bimber_Color constructor.
		 *
		 * @param string $color String representation of color.
		 * @param string $system Color representation system.
		 */
		public function __construct( $color, $system = self::SYSTEM_HEX ) {

			switch ( $system ) {
				case self::SYSTEM_RGB:
					$this->set_rgb( $color );
					break;

				case self::SYSTEM_HSL:
					$this->set_hsl( $color );
					break;

				default:
					$this->set_hex( $color );
					break;
			}
		}

		/**
		 * Checks whether a color is black ( no lightness )
		 *
		 * @return bool
		 */
		public function is_black() {
			return 0 === $this->hsl[2] ? true : false;
		}


		/**
		 * Checks whether a color is white ( maximum lightness )
		 *
		 * @return bool
		 */
		public function is_white() {
			return 100 === $this->hsl[2] ? true : false;
		}


		/**
		 * Checks whether a color is gray ( no saturation )
		 *
		 * @return bool
		 */
		public function is_gray() {
			return 0 === $this->hsl[1] ? true : false;
		}


		/**
		 * Get RGB representation
		 *
		 * @return array
		 */
		public function get_rgb() {
			return $this->rgb;
		}

		/**
		 * Get HSL representation
		 *
		 * @return array
		 */
		public function get_hsl() {
			return $this->hsl;
		}

		/**
		 * Get the "Red" part of the RGB representation
		 *
		 * @return int
		 */
		public function get_red() {
			return $this->rgb[0];
		}

		/**
		 * Get the "Green" part of the RGB representation
		 *
		 * @return int
		 */
		public function get_green() {
			return $this->rgb[1];
		}

		/**
		 * Get the "Blue" part of the RGB representation
		 *
		 * @return int
		 */
		public function get_blue() {
			return $this->rgb[2];
		}

		/**
		 * Get the "Hue" part of the HSL representation
		 *
		 * @return int
		 */
		public function get_hue() {
			return $this->hsl[0];
		}

		/**
		 * Get the "Saturation" part of the RGB representation
		 *
		 * @return int
		 */
		public function get_saturation() {
			return $this->hsl[1];
		}

		/**
		 * Get the "Lightness" part of the RGB representation
		 *
		 * @return int
		 */
		public function get_lightness() {
			return $this->hsl[2];
		}


		/**
		 * Set RGB value
		 *
		 * @param array $v RGB representation.
		 */
		public function set_rgb( $v ) {
			$this->rgb = $v;
			$this->hsl = $this->rgb_to_hsl( $this->rgb );
		}

		/**
		 * Set the "Red" part of the RGB representation.
		 *
		 * @param int $v Value.
		 */
		public function set_red( $v ) {
			$this->rgb[0] = $v;
			$this->hsl    = $this->rgb_to_hsl( $this->rgb );
		}

		/**
		 * Set the "Green" part of the RGB representation.
		 *
		 * @param int $v Value.
		 */
		public function set_green( $v ) {
			$this->rgb[1] = $v;
			$this->hsl    = $this->rgb_to_hsl( $this->rgb );
		}

		/**
		 * Set the "Blue" part of the RGB representation.
		 *
		 * @param int $v Value.
		 */
		public function set_blue( $v ) {
			$this->rgb[2] = $v;
			$this->hsl    = $this->rgb_to_hsl( $this->rgb );
		}


		/**
		 * Set HSL value
		 *
		 * @param array $v Value.
		 */
		public function set_hsl( $v ) {
			$this->hsl = $v;
			$this->rgb = $this->hsl_to_rgb( $this->hsl );
		}

		/**
		 * Set the "Hue" part of the HSL representation.
		 *
		 * @param int $v Value.
		 */
		public function set_hue( $v ) {
			$this->hsl[0] = $v;
			$this->rgb    = $this->hsl_to_rgb( $this->hsl );
		}

		/**
		 * Set the "Saturation" part of the HSL representation.
		 *
		 * @param int $v Value.
		 */
		public function set_saturation( $v ) {
			$this->hsl[1] = $v;
			$this->rgb    = $this->hsl_to_rgb( $this->hsl );
		}

		/**
		 * Set the "Lightness" part of the HSL representation.
		 *
		 * @param int $v Value.
		 */
		public function set_lightness( $v ) {
			$v = $v > 100 ? 100 : $v;
			$v = $v < 0 ? 0 : $v;

			$this->hsl[2] = $v;
			$this->rgb    = $this->hsl_to_rgb( $this->hsl );
		}


		/**
		 * Get hexadecimal representation
		 *
		 * @return string
		 */
		public function get_hex() {
			$rgb = $this->get_rgb();

			$rgb = array_map( 'round', $rgb );

			return str_pad( dechex( $rgb[0] ), 2, '0', STR_PAD_LEFT ) .
			       str_pad( dechex( $rgb[1] ), 2, '0', STR_PAD_LEFT ) .
			       str_pad( dechex( $rgb[2] ), 2, '0', STR_PAD_LEFT );
		}

		/**
		 * Format hexadecimal representation
		 *
		 * @return string
		 */
		public function format_hex() {
			return '#' . $this->get_hex();
		}



		/**
		 * Set hexadecimal value
		 *
		 * @param string $color Hexadecimal representation of color.
		 */
		public function set_hex( $color ) {
			switch ( strlen( $color ) ) {
				case 4:
					$color = substr( $color, 1 );
				case 3:
					$this->set_rgb( array(
						hexdec( $color[0] . $color[0] ),
						hexdec( $color[1] . $color[1] ),
						hexdec( $color[2] . $color[2] ),
					) );

					break;

				case 7:
					$color = substr( $color, 1 );
				case 6:
					$this->set_rgb( array(
						hexdec( $color[0] . $color[1] ),
						hexdec( $color[2] . $color[3] ),
						hexdec( $color[4] . $color[5] ),
					) );

					break;

				default:
					$this->set_rgb( array( 255, 0, 0 ) );

					break;
			}
		}

		/**
		 * Convert HSL value to RGB
		 *
		 * @param array $hsl HSL representation.
		 *
		 * @return array
		 */
		public function hsl_to_rgb( $hsl ) {
			$h = $hsl[0];
			$s = $hsl[1];
			$l = $hsl[2];

			$h /= 360;
			$s /= 100;
			$l /= 100;

			$r = 0;
			$g = 0;
			$b = 0;

			if ( 0 === $s ) {
				// Achromatic.
				$r = $l;
				$g = $l;
				$b = $l;
			} else {
				$q = $l < 0.5 ? $l * ( 1 + $s ) : $l + $s - $l * $s;
				$p = 2 * $l - $q;
				$r = $this->hue_to_rgb( $p, $q, $h + 1 / 3 );
				$g = $this->hue_to_rgb( $p, $q, $h );
				$b = $this->hue_to_rgb( $p, $q, $h - 1 / 3 );
			}

			return array( $r * 255, $g * 255, $b * 255 );
		}

		/**
		 * Convert hue to RGB
		 *
		 * @param int   $p Minimum of R,G,B.
		 * @param int   $q Maximum of R,G,B.
		 * @param float $t Value angle hue.
		 *
		 * @return mixed
		 */
		public function hue_to_rgb( $p, $q, $t ) {
			if ( $t < 0 ) {
				$t += 1;
			}

			if ( $t > 1 ) {
				$t -= 1;
			}

			if ( $t < 1 / 6 ) {
				return $p + ( $q - $p ) * 6 * $t;
			}
			if ( $t < 1 / 2 ) {
				return $q;
			}
			if ( $t < 2 / 3 ) {
				return $p + ( $q - $p ) * ( 2 / 3 - $t ) * 6;
			}

			return $p;

		}

		/**
		 * Convert RGB to HSL
		 *
		 * @param array $rgb RGB representation.
		 *
		 * @return array
		 */
		public function rgb_to_hsl( $rgb ) {
			$r = $rgb[0];
			$g = $rgb[1];
			$b = $rgb[2];

			$r /= 255;
			$g /= 255;
			$b /= 255;

			$max = max( $r, $g, $b );
			$min = min( $r, $g, $b );

			$h = ( $max + $min ) / 2;
			$s = ( $max + $min ) / 2;
			$l = ( $max + $min ) / 2;

			if ( $max === $min ) {
				// Achromatic.
				$h = 0;
				$s = 0;
			} else {
				$d = $max - $min;

				if ( $l > 0.5 ) {
					$s = $d / ( 2 - $max - $min );
				} else {
					$s = $d / ( $max + $min );
				}

				switch ( $max ) {
					case $r:
						$h = ( $g - $b ) / $d + ( $g < $b ? 6 : 0 );
						break;
					case $g:
						$h = ( $b - $r ) / $d + 2;
						break;
					case $b:
						$h = ( $r - $g ) / $d + 4;
						break;
				}
				$h /= 6;
			}

			return array( $h * 360, $s * 100, $l * 100 );
		}
	}
endif;
