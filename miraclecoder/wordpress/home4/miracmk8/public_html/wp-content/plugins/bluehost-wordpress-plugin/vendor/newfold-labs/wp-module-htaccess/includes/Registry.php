<?php
/**
 * Registry for Htaccess fragments.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Holds fragment instances registered by other modules.
 *
 * @since 1.0.0
 */
class Registry {

	/**
	 * Registered fragments keyed by fragment ID.
	 *
	 * @var array<string, Fragment>
	 */
	protected $fragments = array();

	/**
	 * Register (or replace) a fragment.
	 *
	 * Idempotent: a fragment with the same ID will be replaced.
	 * Returns true if the registry changed (added or replaced), false otherwise.
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment $fragment Fragment instance to register.
	 * @return bool True if state changed, false if ignored (e.g., empty ID).
	 */
	public function register( Fragment $fragment ) {
		$id = $this->normalize_id( $fragment->id() );
		if ( '' === $id ) {
			return false;
		}

		$changed                = ! array_key_exists( $id, $this->fragments ) || $this->fragments[ $id ] !== $fragment;
		$this->fragments[ $id ] = $fragment;

		return $changed;
	}

	/**
	 * Register multiple fragments at once.
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment[] $fragments Array/iterable of fragments.
	 * @return int Number of fragments that changed the registry.
	 */
	public function register_many( $fragments ) {
		$count = 0;
		if ( is_array( $fragments ) || $fragments instanceof \Traversable ) {
			foreach ( $fragments as $f ) {
				if ( $f instanceof Fragment && $this->register( $f ) ) {
					++$count;
				}
			}
		}

		return $count;
	}

	/**
	 * Unregister a fragment by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fragment ID.
	 * @return bool True if it existed and was removed; false if not present.
	 */
	public function unregister( $id ) {
		$id = $this->normalize_id( $id );
		if ( '' === $id || ! array_key_exists( $id, $this->fragments ) ) {
			return false;
		}
		unset( $this->fragments[ $id ] );

		return true;
	}

	/**
	 * Unregister multiple fragments by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $ids Fragment IDs.
	 * @return int Number of removed fragments.
	 */
	public function unregister_many( $ids ) {
		$removed = 0;
		foreach ( (array) $ids as $id ) {
			if ( $this->unregister( $id ) ) {
				++$removed;
			}
		}

		return $removed;
	}

	/**
	 * Check if a fragment exists by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fragment ID.
	 * @return bool
	 */
	public function has( $id ) {
		$id = $this->normalize_id( $id );
		return ( '' !== $id ) && array_key_exists( $id, $this->fragments );
	}

	/**
	 * Get a fragment by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fragment ID.
	 * @return Fragment|null
	 */
	public function get( $id ) {
		$id = $this->normalize_id( $id );
		return ( '' !== $id && array_key_exists( $id, $this->fragments ) ) ? $this->fragments[ $id ] : null;
	}

	/**
	 * Return all registered fragments (unsorted).
	 *
	 * @since 1.0.0
	 *
	 * @return Fragment[]
	 */
	public function all() {
		return array_values( $this->fragments );
	}

	/**
	 * Return registered fragment IDs (unsorted).
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	public function ids() {
		return array_keys( $this->fragments );
	}

	/**
	 * Return the total number of registered fragments.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function count() {
		return count( $this->fragments );
	}

	/**
	 * Return only fragments that are enabled for the given context,
	 * sorted by ascending priority (lower number renders earlier),
	 * then by ID for stable ordering.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $context Optional context object passed to fragments.
	 * @return Fragment[]
	 */
	public function enabled_fragments( $context = null ) {
		$enabled = array();

		foreach ( $this->fragments as $fragment ) {
			// Guard: fragment must implement the expected API.
			if ( ! method_exists( $fragment, 'is_enabled' ) || ! method_exists( $fragment, 'priority' ) ) {
				continue;
			}

			if ( true === $fragment->is_enabled( $context ) ) {
				$enabled[] = $fragment;
			}
		}

		usort(
			$enabled,
			function ( $a, $b ) {
				$pa = (int) ( method_exists( $a, 'priority' ) ? $a->priority() : 0 );
				$pb = (int) ( method_exists( $b, 'priority' ) ? $b->priority() : 0 );

				if ( $pa === $pb ) {
					$ia = method_exists( $a, 'id' ) ? (string) $a->id() : '';
					$ib = method_exists( $b, 'id' ) ? (string) $b->id() : '';
					return strcmp( $ia, $ib );
				}

				return ( $pa < $pb ) ? -1 : 1;
			}
		);

		return $enabled;
	}

	/**
	 * Clear all registered fragments.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function clear() {
		$this->fragments = array();
	}

	/**
	 * Normalize an ID to a trimmed string (empty if invalid).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $id Raw ID.
	 * @return string Normalized ID or '' if invalid.
	 */
	protected function normalize_id( $id ) {
		return trim( (string) $id );
	}
}
