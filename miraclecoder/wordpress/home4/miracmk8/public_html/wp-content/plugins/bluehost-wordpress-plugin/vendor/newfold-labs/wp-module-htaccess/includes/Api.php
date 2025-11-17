<?php
/**
 * Public API for the Htaccess module.
 *
 * Provides static helpers other modules can call without needing the
 * DI container: register/unregister fragments and queue an apply.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Api
 *
 * @since 1.0.0
 */
class Api {

	/**
	 * Shared registry instance.
	 *
	 * @var Registry|null
	 */
	protected static $registry = null;

	/**
	 * Manager reference for queueing applies.
	 *
	 * @var Manager|null
	 */
	protected static $manager = null;

	/**
	 * Set the shared registry (called by the module during boot).
	 *
	 * @since 1.0.0
	 *
	 * @param Registry $registry Registry instance.
	 * @return void
	 */
	public static function set_registry( Registry $registry ) {
		self::$registry = $registry;
	}

	/**
	 * Set the manager reference (called by the module during boot).
	 *
	 * When a manager is set, any fragments registered before boot are
	 * flushed from the early-fragment option into persistent state.
	 *
	 * @since 1.0.0
	 *
	 * @param Manager $manager Manager instance.
	 * @return void
	 */
	public static function set_manager( Manager $manager ) {
		self::$manager = $manager;

		$option_key = Options::get_option_name( 'early_fragments' );
		$fragments  = get_site_option( $option_key, array() );

		if ( empty( $fragments ) || ! is_array( $fragments ) ) {
			return;
		}

		// Persist any fragments captured before the manager was available.
		self::flush_early_fragments_to_manager( $fragments );
	}

	/**
	 * Get the current registry, creating a local one if none set.
	 *
	 * @since 1.0.0
	 *
	 * @return Registry
	 */
	public static function registry() {
		if ( ! self::$registry instanceof Registry ) {
			self::$registry = new Registry();
		}
		return self::$registry;
	}

	/**
	 * Register (or replace) a fragment and queue an apply.
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment $fragment Fragment to add.
	 * @param bool     $apply    Whether to queue an apply immediately (default true).
	 * @return void
	 */
	public static function register( Fragment $fragment, $apply = true ) {
		$apply = (bool) $apply;

		$reason_label = self::fragment_reason_label( $fragment );

		$changed = false;
		if ( self::$manager instanceof Manager ) {
			self::registry()->register( $fragment );
			$changed = (bool) self::$manager->persist_fragment_state( $fragment );
		} else {
			// No manager yet: stash the fragment and mark that persistence/update is needed.
			self::stash_early_fragment( $fragment );
		}

		// Detect if this fragment contributes patches (so we still need an apply).
		$ctx         = Context::from_wp( array() );
		$has_patches = ( method_exists( $fragment, 'patches' ) && ! empty( (array) $fragment->patches( $ctx ) ) );

		if ( $apply && ( $changed || $has_patches || ! ( self::$manager instanceof Manager ) ) ) {
			self::queue_apply( 'register:' . $reason_label );
		}
	}

	/**
	 * Unregister a fragment by ID and queue an apply.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id    Fragment ID.
	 * @param bool   $apply Whether to queue an apply immediately (default true).
	 * @return void
	 */
	public static function unregister( $id, $apply = true ) {
		$apply = (bool) $apply;
		$id    = (string) $id;

		self::registry()->unregister( $id );

		if ( self::$manager instanceof Manager ) {
			self::$manager->remove_persisted_fragment( $id );
		} else {
			// Remove any matching early-stashed fragment.
			self::unstash_early_fragment_by_id( $id );
		}

		if ( $apply ) {
			self::queue_apply( 'unregister:' . $id );
		}
	}


	/**
	 * Return enabled fragments sorted by priority for a given context.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $context Optional context.
	 * @return Fragment[]
	 */
	public static function enabled_fragments( $context = null ) {
		return self::registry()->enabled_fragments( $context );
	}

	/**
	 * Queue a canonical apply via the manager or a transient fallback.
	 *
	 * @since 1.0.0
	 *
	 * @param string $reason Optional reason label.
	 * @return void
	 */
	public static function queue_apply( $reason = '' ) {
		if ( self::$manager instanceof Manager ) {
			self::$manager->queue_apply( $reason );
			return;
		}

		// Fallback if manager has not been injected yet (early calls).
		$payload = array(
			'at'     => time(),
			'reason' => (string) $reason,
		);
		set_site_transient( Options::get_option_name( 'needs_update' ), $payload, 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * Get a human-readable label for a fragment (for logging).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $fragment Fragment instance or ID.
	 * @return string
	 */
	private static function fragment_reason_label( $fragment ) {
		if ( $fragment instanceof Fragment && method_exists( $fragment, 'id' ) ) {
			$id = (string) $fragment->id();
			if ( '' !== $id ) {
				return $id;
			}
		}
		// Fallback to class name.
		return is_object( $fragment ) ? get_class( $fragment ) : 'unknown';
	}

	/**
	 * Persist any fragments captured before the manager was available.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $fragments Array of Fragment instances.
	 *
	 * @return void
	 */
	private static function flush_early_fragments_to_manager( $fragments ) {
		if ( ! ( self::$manager instanceof Manager ) ) {
			return;
		}

		$changed_any = false;

		foreach ( $fragments as $fragment ) {
			if ( $fragment instanceof Fragment ) {
				try {
					self::registry()->register( $fragment );
					$changed_any = self::$manager->persist_fragment_state( $fragment ) || $changed_any;
				} catch ( \Throwable $e ) {
					// Keep going; one bad fragment shouldn't block the rest.
				}
			}
		}

		// Clear the stash once processed.
		$option_key = Options::get_option_name( 'early_fragments' );
		delete_site_option( $option_key );

		// Queue if any flushed fragment might patch (even if body unchanged).
		$ctx         = Context::from_wp( array() );
		$needs_apply = $changed_any;
		foreach ( $fragments as $fragment ) {
			if ( $fragment instanceof Fragment && method_exists( $fragment, 'patches' ) ) {
				$patches = (array) $fragment->patches( $ctx );
				if ( ! empty( $patches ) ) {
					$needs_apply = true;
					break;
				}
			}
		}
		// If anything changed, make sure we run a canonical apply.
		if ( $needs_apply ) {
			self::queue_apply( 'boot:early-fragments' );
		}
	}

	/**
	 * Stash a fragment in a site option until the manager is available.
	 *
	 * @param Fragment $fragment Fragment to stash.
	 * @return void
	 */
	private static function stash_early_fragment( Fragment $fragment ) {
		$option_key = Options::get_option_name( 'early_fragments' );
		$fragments  = get_site_option( $option_key, array() );

		if ( ! is_array( $fragments ) ) {
			$fragments = array();
		}

		$fragments[] = $fragment;

		update_site_option( $option_key, $fragments );
	}

	/**
	 * Remove a stashed fragment (if present) by its ID.
	 *
	 * @param string $id Fragment ID.
	 * @return void
	 */
	private static function unstash_early_fragment_by_id( $id ) {
		$option_key = Options::get_option_name( 'early_fragments' );
		$fragments  = get_site_option( $option_key, array() );

		if ( empty( $fragments ) || ! is_array( $fragments ) ) {
			return;
		}

		$filtered = array();
		foreach ( $fragments as $fragment ) {
			if ( $fragment instanceof Fragment && method_exists( $fragment, 'id' ) ) {
				if ( (string) $fragment->id() === $id ) {
					// Skip (remove) this one.
					continue;
				}
			}
			$filtered[] = $fragment;
		}

		// Update or delete based on remaining count.
		if ( empty( $filtered ) ) {
			delete_site_option( $option_key );
		} else {
			update_site_option( $option_key, $filtered );
		}
	}
}
