<?php

namespace WPGDPRC\Objects;

/**
 * Simple memory caching class.
 */
class Cache {
	private static $instance;

	private $cache = [];

	public static function getInstance(): Cache {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function toKey( $key ): string {
		return md5( wp_json_encode( $key ) );
	}

	public function get( $key ) {
		$key = $this->toKey( $key );

		if ( array_key_exists( $key, $this->cache ) ) {
			return $this->cache[ $key ];
		}

		return null;
	}

	public function isset( $key ): bool {
		$key = $this->toKey( $key );

		return array_key_exists( $key, $this->cache );
	}

	public function set( $key, $value ) {
		$key = $this->toKey( $key );

		$this->cache[ $key ] = $value;
	}
}
