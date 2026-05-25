<?php
/**
 * Daily WP-Cron health check for the Htaccess module.
 *
 * Schedules and executes a 24-hour scan of the .htaccess file:
 * - Whole-file validation and basic HTTP reachability (via Scanner::diagnose()).
 * - NFD block drift check and self-heal (via Scanner::scan() and ::remediate()).
 * - Emergency restore from backup when clearly broken (via Scanner::restore_latest_backup_verified()).
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Cron
 *
 * @since 1.0.0
 */
class Cron {

	/**
	 * WP-Cron hook name.
	 *
	 * @var string
	 */
	protected $hook = 'nfd_htaccess_daily_scan';

	/**
	 * Module version string used in NFD header when remediating.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Register cron scheduling and handler.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'ensure_scheduled' ) );
		add_action( $this->hook, array( $this, 'execute' ) );
		$this->version = defined( 'NFD_MODULE_HTACCESS_VERSION' ) ? NFD_MODULE_HTACCESS_VERSION : '1.0.0';
	}

	/**
	 * Ensure the daily cron event is scheduled (every 24 hours).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ensure_scheduled() {
		if ( ! function_exists( 'wp_next_scheduled' ) || ! function_exists( 'wp_schedule_event' ) ) {
			return;
		}

		if ( ! wp_next_scheduled( $this->hook ) ) {
			// Start roughly an hour from now to avoid heavy startup load.
			$start = time() + HOUR_IN_SECONDS;
			wp_schedule_event( $start, 'daily', $this->hook );
		}
	}

	/**
	 * Execute the daily health check and remediation flow.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function execute() {
		// Build dependencies for the scanner.
		$updater   = new Updater();
		$validator = new Validator();
		$scanner   = new Scanner( $updater, $validator );

		// Snapshot runtime context and enabled fragments.
		$context   = class_exists( __NAMESPACE__ . '\Context' ) ? Context::from_wp( array() ) : null;
		$fragments = Api::enabled_fragments( $context );

		// 1) Whole-file pre-check (syntax + 5xx reachability).
		$diag = $scanner->diagnose( $context );

		// 2) NFD block check and self-heal if drift detected.
		$scan = $scanner->scan( $context, $fragments );
		if ( ! empty( $scan['can_remediate'] ) ) {
			$scanner->remediate( $context, $fragments, $this->version );
		}

		// 3) If the file is clearly broken, attempt a verified restore from latest backup.
		$needs_restore = ( empty( $diag['file_valid'] ) ) || ( isset( $diag['http_status'] ) && $diag['http_status'] >= 500 && $diag['http_status'] < 600 );
		if ( $needs_restore ) {
			$scanner->restore_latest_backup_verified( $context, $fragments, $this->version );
		}
	}
}
