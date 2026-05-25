<?php
/**
 * Htaccess Manager Module.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

use NewfoldLabs\WP\ModuleLoader\Container;
use WP_CLI;

/**
 * Manages all functionality for the Htaccess module.
 *
 * Responsibilities:
 * - Tracks and composes NFD-managed fragments (in-memory each request).
 * - Persists fragment blocks and the composed body to avoid recomposition churn.
 * - Schedules a single, debounced write at shutdown (safe contexts only).
 * - Reconciles saved state vs. on-disk block and queues a single apply if drift.
 *
 * Notes:
 * - Writes only occur once at shutdown and only if content changes (Updater no-ops).
 * - Never writes a header-only/blank managed block.
 *
 * @since 1.0.0
 */
class Manager {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Registry of fragments.
	 *
	 * @var Registry
	 */
	protected $registry;

	/**
	 * Validator service.
	 *
	 * @var Validator
	 */
	protected $validator;

	/**
	 * Updater service (marker-based merge).
	 *
	 * @var Updater
	 */
	protected $updater;

	/**
	 * Cron service (scheduling and handler).
	 *
	 * @var Cron
	 */
	protected $cron;

	/**
	 * Whether or not an apply is queued (dirty state).
	 *
	 * @var bool
	 */
	protected $dirty = false;

	/**
	 * Persisted state option key.
	 *
	 * @var string
	 */
	protected $state_option_key;

	/**
	 * Whether the plugin is currently being deactivated.
	 *
	 * Used to avoid queuing applies during deactivation.
	 *
	 * @var bool
	 */
	protected $deactivating = false;

	/**
	 * Constructor.
	 *
	 * Keep construction minimal; heavy lifting happens in boot().
	 *
	 * @since 1.0.0
	 *
	 * @param Container $container Module container instance.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Module bootstrap. Attach hooks and initialize services here.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function boot() {
		// Instantiate internal services.
		$this->registry         = Api::registry();
		$this->validator        = new Validator();
		$this->updater          = new Updater();
		$this->cron             = new Cron();
		$this->state_option_key = Options::get_option_name( 'saved_state' );

		// Expose to static API for other modules to use without the container.
		Api::set_manager( $this );

		// Reconcile persisted block vs. disk at init (queues if drift found).
		// Admin: run after everyone else has registered fragments.
		add_action( 'admin_init', array( $this, 'reconcile_saved_block' ), PHP_INT_MAX );

		// Queue on common events that affect rewrite rules or fragments.
		add_action( 'permalink_structure_changed', array( $this, 'queue_apply' ) );

		// Option watcher (e.g., permalink, home/siteurl, module toggles).
		add_action( 'updated_option', array( $this, 'maybe_queue_on_option' ), 10, 3 );

		// Single debounced write at safe boundaries (admin/cron/CLI only).
		add_action( 'shutdown', array( $this, 'maybe_apply_on_shutdown' ), 2 );

		if ( defined( 'WP_CLI' ) && WP_CLI && class_exists( '\WP_CLI' ) ) {
			WP_CLI::add_command( 'newfold htaccess', CLI::class );
		}

		// Register cron scheduling and handler.
		$this->cron->register();

		register_deactivation_hook( $this->container->plugin()->file, array( $this, 'on_plugin_deactivation' ) );
	}

	/**
	 * Queue a rebuild/apply of the canonical .htaccess state.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $reason Optional reason for logging/telemetry.
	 * @return void
	 */
	public function queue_apply( $reason = '' ) {
		$payload = array(
			'at'     => time(),
			'reason' => is_scalar( $reason ) ? (string) $reason : '',
		);

		set_site_transient( Options::get_option_name( 'needs_update' ), $payload, 5 * MINUTE_IN_SECONDS );
		$this->mark_dirty();
	}

	/**
	 * Queue an apply if certain options change.
	 *
	 * Currently watches 'permalink_structure', 'home', and 'siteurl'.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option    Option name.
	 * @param mixed  $old_value Old value.
	 * @param mixed  $value     New value.
	 * @return void
	 */
	public function maybe_queue_on_option( $option, $old_value, $value ) {
		if ( 'permalink_structure' === $option || 'home' === $option || 'siteurl' === $option ) {
			$this->queue_apply( 'option:' . $option );
		}
	}

	/**
	 * Is this a safe context to apply at shutdown?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function is_safe_context() {
		$is_cli  = ( defined( 'WP_CLI' ) && WP_CLI );
		$is_rest = ( defined( 'REST_REQUEST' ) && REST_REQUEST );
		$is_ajax = ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() );

		// Admin screens (wp-admin), cron, CLI, REST API, AJAX are all safe.
		if ( is_admin() || wp_doing_cron() || $is_cli || $is_rest || $is_ajax ) {
			return true;
		}

		return false;
	}

	/**
	 * Mark the manager as dirty (needs apply).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mark_dirty() {
		$this->dirty = true;
	}

	/**
	 * Debounced apply on shutdown, only in safe contexts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function maybe_apply_on_shutdown() {
		if ( $this->deactivating || ! ( $this->is_safe_context() && $this->dirty ) ) {
			return;
		}

		// Clear the transient to avoid re-entrance in the same request.
		delete_site_transient( Options::get_option_name( 'needs_update' ) );
		$this->dirty = false;

		$this->apply_canonical_state();
	}

	/**
	 * Deactivation hook to perform when plugin is deactivated or feature is disabled
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function on_plugin_deactivation() {
		$this->deactivating = true;
		$this->remove_canonical_block();
	}

	/**
	 * Acquire a write lock to prevent concurrent writes.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if lock acquired, false if already locked.
	 */
	protected function acquire_lock() {
		$lock_key = Options::get_option_name( 'write_lock' );

		if ( get_site_transient( $lock_key ) ) {
			// Lock already held.
			return false;
		}

		// Try to acquire lock for 30 seconds.
		return set_site_transient( $lock_key, 1, 30 );
	}

	/**
	 * Release the write lock.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function release_lock() {
		delete_site_transient( Options::get_option_name( 'write_lock' ) );
	}

	/**
	 * Compose, validate, and merge ONLY the NFD-managed block into .htaccess.
	 *
	 * - Prefers saved state's composed body when available (durable/idempotent).
	 *   the result back to saved state (then clears that transient).
	 * - Collects legacy labels from BOTH current fragments and the saved body.
	 * - Validates/remediates before writing; Updater no-ops if unchanged.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function apply_canonical_state() {
		if ( ! $this->acquire_lock() ) {
			return; // another request is writing
		}

		try {
			// Context & metadata.
			$context = Context::from_wp( array() );
			$host    = $context->host();
			$version = defined( 'NFD_MODULE_HTACCESS_VERSION' ) ? NFD_MODULE_HTACCESS_VERSION : '1.0.0';

			// Load saved state and flags.
			$saved           = $this->load_saved_state();
			$have_saved_body = ( is_array( $saved ) && ! empty( $saved['body'] ) );

			// Enabled fragments (non-WP, exclusivity enforced).
			$enabled = $this->enabled_non_wp_fragments( $context );

			// Source of truth for body.
			if ( $have_saved_body ) {
				// Reuse persisted body; still gather current fragments for labels.
				$body = (string) $saved['body'];
			} else {
				// Compose afresh from enabled fragments (first boot or forced persist).
				$body = Composer::compose_body_only( $enabled, $context );
			}

			// Validate/remediate.
			if ( '' !== $body && ! $this->validator->is_valid( $body, array() ) ) {
				$body = $this->validator->remediate( $body );
				if ( ! $this->validator->is_valid( $body, array() ) ) {
					return; // do not write invalid content
				}
			}

			// Legacy labels from fragments + saved state.
			$labels_from_frags = $this->collect_legacy_marker_labels( $enabled, $context );
			$labels_from_state = $this->collect_labels_from_saved_state( $have_saved_body ? $saved : array() );
			$legacy_labels     = array_values( array_unique( array_merge( $labels_from_frags, $labels_from_state ) ) );

			// Merge into the managed marker block.
			$this->updater->apply_managed_block( $body, $host, $version, $legacy_labels );
		} finally {
			$this->release_lock();
		}
	}

	/**
	 * Remove ONLY the NFD-managed block immediately (single-shot).
	 *
	 * Leaves saved state intact so re-activation can restore it.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function remove_canonical_block() {
		if ( ! $this->acquire_lock() ) {
			return;
		}
		try {
			$context = Context::from_wp( array() );
			$host    = $context->host();
			$version = defined( 'NFD_MODULE_HTACCESS_VERSION' ) ? NFD_MODULE_HTACCESS_VERSION : '1.0.0';

			// Empty body => Updater will delete our block atomically (and run post-write checks).
			$this->updater->apply_managed_block( '', $host, $version );
		} finally {
			$this->release_lock();
		}
	}

	/**
	 * On init, ensure the persisted NFD block equals the on-disk managed block.
	 *
	 * Fast path: compare checksums; if drift/missing OR legacy blocks exist,
	 * re-apply persisted body into the "# BEGIN marker" block and migrate
	 * legacy blocks in the same single write (no-op if identical and no legacy).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function reconcile_saved_block() {
		// Avoid double writes in the same request.
		if ( $this->dirty || get_site_transient( Options::get_option_name( 'needs_update' ) ) ) {
			return;
		}

		$saved = $this->load_saved_state();
		if ( empty( $saved['checksum'] ) ) {
			return;
		}

		$current = $this->read_current_htaccess();
		$context = Context::from_wp( array() );

		// --- Build legacy labels from BOTH current fragments and persisted state.
		$labels_from_frags = $this->collect_legacy_marker_labels( $this->enabled_non_wp_fragments( $context ), $context );
		$labels_from_state = $this->collect_labels_from_saved_state( $saved );
		$legacy_labels     = array_values( array_unique( array_merge( $labels_from_frags, $labels_from_state ) ) );

		if ( '' === $current ) {
			// Missing/unreadable: write saved state and migrate in one go.
			$this->updater->apply_managed_block(
				(string) $saved['body'],
				(string) $saved['host'],
				(string) $saved['version'],
				$legacy_labels
			);
			return;
		}

		// Compute current block body hash.
		$current_hash = (string) $this->updater->get_current_body_hash();

		// Probe for legacy blocks present in the file.
		$has_legacy = false;
		if ( ! empty( $legacy_labels ) ) {
			$migrator     = new Migrator();
			$normalized   = Text::normalize_lf( (string) $current, false );
			$probe_result = $migrator->remove_legacy_blocks( $normalized, $legacy_labels );
			$has_legacy   = ( is_array( $probe_result ) && ! empty( $probe_result['removed'] ) );
		}

		// Also detect if any currently enabled fragments (if any) want to patch.
		$fragments         = Api::enabled_fragments( $context );
		$needs_patch_apply = false;
		foreach ( (array) $fragments as $frag ) {
			if ( $frag instanceof Fragment && method_exists( $frag, 'patches' ) ) {
				$patches = (array) $frag->patches( $context );
				if ( ! empty( $patches ) ) { $needs_patch_apply = true;
					break; }
			}
		}

		// Rewrite if checksum differs OR legacy blocks are present.
		if ( $current_hash !== (string) $saved['checksum'] || $has_legacy || $needs_patch_apply ) {
			$this->updater->apply_managed_block(
				(string) $saved['body'],
				(string) $saved['host'],
				(string) $saved['version'],
				$legacy_labels
			);
		}
	}

	/**
	 * Collect legacy marker labels by parsing the saved state's body text.
	 *
	 * Looks for lines like "# BEGIN Something ...", "# END Something ...".
	 *
	 * @since 1.0.0
	 *
	 * @param array $saved_state Result of load_saved_state().
	 * @return string[] Unique marker labels found.
	 */
	protected function collect_labels_from_saved_state( $saved_state ) {
		$labels = array();

		if ( ! is_array( $saved_state ) || empty( $saved_state['body'] ) || ! is_string( $saved_state['body'] ) ) {
			return $labels;
		}

		$body  = (string) $saved_state['body'];
		$lines = preg_split( '/\r\n|\r|\n/', $body );

		foreach ( $lines as $line ) {
			// Match "# BEGIN Some Label" or "# END Some Label"
			if ( preg_match( '/^\s*#\s*(?:BEGIN|END)\s+(.+)$/i', $line, $m ) ) {
				$label = trim( $m[1] );
				if ( '' !== $label ) {
					$labels[ $label ] = true;
				}
			}
		}

		return array_keys( $labels );
	}

	/**
	 * Read current .htaccess contents (best-effort; no error silencing).
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function read_current_htaccess() {
		$path = $this->resolve_htaccess_path();
		if ( '' === $path || ! is_readable( $path ) ) {
			return '';
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$buf = file_get_contents( $path );
		return is_string( $buf ) ? $buf : '';
	}

	/**
	 * Optional accessor to the DI container (read-only usage suggested).
	 *
	 * @since 1.0.0
	 *
	 * @return Container
	 */
	public function container() {
		return $this->container;
	}

	/**
	 * Persist (or update) a single fragment's rendered block into saved state.
	 *
	 * Renders the fragment in the current context, stores its body and priority
	 * keyed by fragment ID, then recomposes/saves the full persisted body + checksum.
	 * Idempotent: returns false if nothing effectively changed.
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment $fragment Fragment instance.
	 * @return bool True if persisted composed body changed; false if no-op.
	 */
	public function persist_fragment_state( Fragment $fragment ) {
		if ( ! $fragment instanceof Fragment ) {
			return false;
		}

		$context = class_exists( __NAMESPACE__ . '\Context' ) ? Context::from_wp( array() ) : null;
		$host    = $context ? $context->host() : '';
		$version = '1.0.0';

		// Render this fragment alone (normalized, trimmed).
		$block = (string) $fragment->render( $context );
		$block = Text::normalize_lf( $block, false );
		$block = Text::trim_surrounding_blank_lines( $block );

		$id       = method_exists( $fragment, 'id' ) ? (string) $fragment->id() : get_class( $fragment );
		$priority = (int) ( method_exists( $fragment, 'priority' ) ? $fragment->priority() : 0 );

		$state = $this->load_saved_state();
		if ( empty( $state['blocks'] ) || ! is_array( $state['blocks'] ) ) {
			$state['blocks'] = array();
		}

		$prev = isset( $state['blocks'][ $id ] ) ? $state['blocks'][ $id ] : null;

		// If new render is empty but we already have a non-empty stored block,
		// DO NOT overwrite the stored body. Update only the priority metadata.
		$next_body = (string) $block;

		// If the stored block is identical (body + priority), it's a no-op.
		if ( is_array( $prev )
			&& array_key_exists( 'body', $prev )
			&& array_key_exists( 'priority', $prev )
			&& (string) $prev['body'] === $next_body
			&& (int) $prev['priority'] === $priority
		) {
			return false;
		}

		// Update this fragment's entry.
		$state['blocks'][ $id ] = array(
			'body'     => $next_body,
			'priority' => $priority,
		);

		$old_body = isset( $state['body'] ) ? (string) $state['body'] : '';
		$new_body = $this->compose_from_saved_blocks( $state );

		// If recomposed body is unchanged, no persistence or apply needed.
		if ( (string) $new_body === (string) $old_body ) {
			return false;
		}

		// Validate/remediate non-empty bodies before saving.
		if ( '' !== $new_body && ! $this->validator->is_valid( $new_body, array() ) ) {
			$new_body = $this->validator->remediate( $new_body );
			if ( ! $this->validator->is_valid( $new_body, array() ) ) {
				return false;
			}
		}

		$this->save_state_full( $state, $new_body, $host, $version );

		return true;
	}

	/**
	 * Remove a fragment's block from saved state by ID and recompute persisted body.
	 *
	 * Idempotent: returns false if nothing effectively changed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fragment ID.
	 * @return bool True if persisted composed body changed; false if no-op.
	 */
	public function remove_persisted_fragment( $id ) {
		$state = $this->load_saved_state();

		if ( empty( $state['blocks'] ) || ! is_array( $state['blocks'] ) ) {
			return false;
		}

		$id = (string) $id;
		if ( ! isset( $state['blocks'][ $id ] ) ) {
			return false;
		}

		unset( $state['blocks'][ $id ] );

		$context = class_exists( __NAMESPACE__ . '\Context' ) ? Context::from_wp( array() ) : null;
		$host    = $context ? $context->host() : '';
		$version = '1.0.0';

		$old_body = isset( $state['body'] ) ? (string) $state['body'] : '';
		$new_body = $this->compose_from_saved_blocks( $state );

		if ( (string) $new_body === (string) $old_body ) {
			return false;
		}

		if ( '' !== $new_body && ! $this->validator->is_valid( $new_body, array() ) ) {
			$new_body = $this->validator->remediate( $new_body );
			if ( ! $this->validator->is_valid( $new_body, array() ) ) {
				return false;
			}
		}

		$this->save_state_full( $state, $new_body, $host, $version );
		return true;
	}

	/**
	 * Compose NFD body from saved state blocks sorted by priority then ID.
	 *
	 * @since 1.0.0
	 *
	 * @param array $state Saved state containing 'blocks'.
	 * @return string Composed body (no trailing newline).
	 */
	protected function compose_from_saved_blocks( $state ) {
		$blocks = array();

		if ( ! empty( $state['blocks'] ) && is_array( $state['blocks'] ) ) {
			// Sort by priority asc, then ID asc for stability.
			$items = $state['blocks'];
			uksort(
				$items,
				function ( $a, $b ) use ( $items ) {
					$pa = isset( $items[ $a ]['priority'] ) ? (int) $items[ $a ]['priority'] : 0;
					$pb = isset( $items[ $b ]['priority'] ) ? (int) $items[ $b ]['priority'] : 0;
					if ( $pa === $pb ) {
						return strcmp( (string) $a, (string) $b );
					}
					return ( $pa < $pb ) ? -1 : 1;
				}
			);

			foreach ( $items as $meta ) {
				$body = isset( $meta['body'] ) ? (string) $meta['body'] : '';
				$body = Text::normalize_lf( $body, true );
				$body = Text::trim_surrounding_blank_lines( $body );
				if ( '' !== $body ) {
					$blocks[] = $body;
				}
			}
		}

		$out = implode( "\n\n", $blocks );
		return rtrim( $out, "\n" );
	}

	/**
	 * Load persisted NFD state (accumulated fragment blocks and composed body).
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function load_saved_state() {
		$payload = is_multisite() && function_exists( 'get_site_option' )
			? get_site_option( $this->state_option_key, array() )
			: get_option( $this->state_option_key, array() );

		return is_array( $payload ) ? $payload : array();
	}

	/**
	 * Save full persisted state (blocks map + composed body + checksum + metadata).
	 *
	 * Never clobbers a non-empty existing body with an empty one.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $state   State with 'blocks' map.
	 * @param string $body    Composed body (no trailing newline).
	 * @param string $host    Host label.
	 * @param string $version Version string.
	 * @return void
	 */
	protected function save_state_full( $state, $body, $host, $version ) {
		$payload = is_array( $state ) ? $state : array();

		$payload['body']      = (string) $body;
		$payload['checksum']  = hash( 'sha256', Text::normalize_lf( (string) $body ) );
		$payload['host']      = (string) $host;
		$payload['version']   = (string) $version;
		$payload['updatedAt'] = time();

		if ( is_multisite() && function_exists( 'update_site_option' ) ) {
			update_site_option( $this->state_option_key, $payload );
		} else {
			update_option( $this->state_option_key, $payload );
		}
	}

	/**
	 * Ensure WP marker helpers are available.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function ensure_wp_file_helpers() {
		if ( ! function_exists( 'insert_with_markers' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}
	}

	/**
	 * Collect legacy marker labels from fragments (for migrator).
	 *
	 * @since 1.0.0
	 *
	 * @param Fragment[] $fragments Enabled fragments.
	 * @param mixed      $context   Render context.
	 * @return string[]
	 */
	protected function collect_legacy_marker_labels( $fragments, $context ) {
		// Always include the old legacy label.
		$labels = array(
			'Newfold Headers' => true,
		);

		foreach ( (array) $fragments as $f ) {
			if ( ! $f instanceof Fragment ) {
				continue;
			}

			$label    = '';
			$rendered = (string) $f->render( $context );
			$rendered = Text::normalize_lf( $rendered, false );
			if ( preg_match( '/^\s*#\s*BEGIN\s+(.+?)\s*$/m', $rendered, $m ) ) {
				$label = trim( $m[1] );
			}

			if ( '' !== $label ) {
				$labels[ $label ] = true; // de-dupe
			}
		}

		return array_keys( $labels );
	}

	/**
	 * Return enabled fragments excluding WordPress/core fragments.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $context Context.
	 * @return Fragment[]
	 */
	protected function enabled_non_wp_fragments( $context ) {
		$all       = $this->registry->enabled_fragments( $context );
		$filtered  = array();
		$seen_excl = array();

		foreach ( $all as $f ) {
			$id = method_exists( $f, 'id' ) ? (string) $f->id() : '';

			// Skip anything WordPress-y: we do NOT manage core rules here.
			if ( '' !== $id && ( 'WordPress.core' === $id || false !== strpos( $id, 'WordPress' ) ) ) {
				continue;
			}

			// Respect exclusivity (first one wins) to avoid duplicate labels.
			$is_exclusive = ( method_exists( $f, 'exclusive' ) && true === $f->exclusive() );
			if ( $is_exclusive ) {
				if ( '' !== $id && isset( $seen_excl[ $id ] ) ) {
					continue;
				}
				if ( '' !== $id ) {
					$seen_excl[ $id ] = true;
				}
			}

			$filtered[] = $f;
		}

		return $filtered;
	}

	/**
	 * Resolve the .htaccess path (best-effort).
	 *
	 * Prefers get_home_path() if available, otherwise falls back to ABSPATH.
	 *
	 * @since 1.0.0
	 *
	 * @return string Resolved path, or '' if cannot determine.
	 */
	protected function resolve_htaccess_path() {
		if ( function_exists( 'get_home_path' ) ) {
			$home = get_home_path();
			if ( is_string( $home ) && '' !== $home ) {
				return rtrim( $home, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . '.htaccess';
			}
		}
		if ( defined( 'ABSPATH' ) ) {
			return rtrim( ABSPATH, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . '.htaccess';
		}
		return '';
	}
}
