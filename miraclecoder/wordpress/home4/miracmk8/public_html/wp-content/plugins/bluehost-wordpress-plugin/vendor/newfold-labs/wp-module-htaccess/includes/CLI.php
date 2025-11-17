<?php
/**
 * WP-CLI commands for the Htaccess module.
 *
 * Usage:
 *   wp newfold htaccess status
 *   wp newfold htaccess diagnose
 *   wp newfold htaccess scan
 *   wp newfold htaccess apply --version=1.0.0
 *   wp newfold htaccess remediate --version=1.0.0
 *   wp newfold htaccess restore --version=1.0.0
 *   wp newfold htaccess list_backups
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

use WP_CLI;
use WP_CLI\Utils;

if ( ! class_exists( '\WP_CLI' ) ) {
	return;
}

/**
 * Manage the Newfold-managed marker block.
 *
 * This command provides inspection and remediation helpers for the .htaccess file:
 * - Whole-file validation + loopback HTTP reachability
 * - Scan and self-heal ONLY the NFD-managed block (idempotent)
 * - Emergency restore from timestamped backups of the ENTIRE file
 *
 * Subcommands include: status, diagnose, scan, apply, remediate, restore, list-backups.
 */
class CLI {

	/**
	 * Registry service (provided by Api).
	 *
	 * @var Registry|null
	 */
	protected $registry;

	/**
	 * Updater service.
	 *
	 * @var Updater
	 */
	protected $updater;

	/**
	 * Validator service.
	 *
	 * @var Validator
	 */
	protected $validator;

	/**
	 * Scanner service.
	 *
	 * @var Scanner
	 */
	protected $scanner;

	/**
	 * Construct and wire services.
	 */
	public function __construct() {
		// Registry is exposed by Api.
		$this->registry = null;
		if ( class_exists( __NAMESPACE__ . '\Api' ) && method_exists( __NAMESPACE__ . '\Api', 'registry' ) ) {
			$this->registry = Api::registry();
		}

		// Local, standalone instances (do not rewrite the whole file).
		$this->updater   = new Updater();
		$this->validator = new Validator();
		$this->scanner   = new Scanner( $this->updater, $this->validator );
	}

	/**
	 * Combined quick status: diagnose (whole file + HTTP) and scan (NFD block).
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render format for the summary. One of: table, json, yaml
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # Quick status overview (whole-file + NFD block)
	 *     $ wp newfold htaccess status
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. May contain 'format'.
	 * @return void
	 */
	public function status( $args, $assoc_args ) {
		$fmt       = $this->normalize_format( $assoc_args, 'table' );
		$context   = $this->ctx();
		$fragments = $this->get_enabled_fragments( $context );

		$diag = (array) $this->scanner->diagnose( $context );
		$scan = (array) $this->scanner->scan( $context, $fragments );

		$rows = array(
			array(
				'key'   => 'file_valid',
				'value' => $this->yn( ! empty( $diag['file_valid'] ) ),
			),
			array(
				'key'   => 'http_status',
				'value' => (string) $this->get( $diag, 'http_status', '' ),
			),
			array(
				'key'   => 'reachable',
				'value' => $this->yn( ! empty( $diag['reachable'] ) ),
			),
			array(
				'key'   => 'nfd_status',
				'value' => (string) $this->get( $scan, 'status', '' ),
			),
			array(
				'key'   => 'nfd_can_remediate',
				'value' => $this->yn( ! empty( $scan['can_remediate'] ) ),
			),
		);

		$this->print_rows( $rows, array( 'key', 'value' ), $fmt );

		if ( 'table' === $fmt ) {
			$issues = (array) $this->get( $diag, 'file_issues', array() );
			if ( ! empty( $issues ) ) {
				WP_CLI::log( 'File issues:' );
				foreach ( $issues as $issue ) { WP_CLI::log( ' - ' . $issue ); }
			}
			$nfd_issues = (array) $this->get( $scan, 'issues', array() );
			if ( ! empty( $nfd_issues ) ) {
				WP_CLI::log( 'NFD issues:' );
				foreach ( $nfd_issues as $issue ) { WP_CLI::log( ' - ' . $issue ); }
			}
		}

		$ok = ! empty( $diag['file_valid'] ) && ! empty( $diag['reachable'] ) && 'ok' === $this->get( $scan, 'status', '' );
		$this->maybe_strict_halt( $assoc_args, $ok );
	}

	/**
	 * Diagnose the current .htaccess (whole-file) and perform a loopback HTTP check.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess diagnose
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. Unused.
	 * @return void
	 */
	public function diagnose( $args, $assoc_args ) {
		$fmt     = $this->normalize_format( $assoc_args, 'table' );
		$context = $this->ctx();
		$diag    = (array) $this->scanner->diagnose( $context );

		$rows = array(
			array(
				'key'   => 'file_valid',
				'value' => $this->yn( ! empty( $diag['file_valid'] ) ),
			),
			array(
				'key'   => 'http_status',
				'value' => (string) $this->get( $diag, 'http_status', '' ),
			),
			array(
				'key'   => 'reachable',
				'value' => $this->yn( ! empty( $diag['reachable'] ) ),
			),
		);
		$this->print_rows( $rows, array( 'key', 'value' ), $fmt );

		$issues = (array) $this->get( $diag, 'file_issues', array() );
		if ( 'table' === $fmt && ! empty( $issues ) ) {
			WP_CLI::warning( 'File issues:' );
			foreach ( $issues as $issue ) { WP_CLI::log( ' - ' . $issue ); }
		}

		$ok = ! empty( $diag['file_valid'] ) && ! empty( $diag['reachable'] );
		$this->maybe_strict_halt( $assoc_args, $ok );
	}

	/**
	 * Scan ONLY the NFD-managed block for drift/corruption.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess scan
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. Unused.
	 * @return void
	 */
	public function scan( $args, $assoc_args ) {
		$fmt       = $this->normalize_format( $assoc_args, 'table' );
		$context   = $this->ctx();
		$fragments = $this->get_enabled_fragments( $context );
		$scan      = (array) $this->scanner->scan( $context, $fragments );

		$rows = array(
			array(
				'key'   => 'status',
				'value' => (string) $this->get( $scan, 'status', '' ),
			),
			array(
				'key'   => 'current_checksum',
				'value' => (string) $this->get( $scan, 'current_checksum', '' ),
			),
			array(
				'key'   => 'expected_checksum',
				'value' => (string) $this->get( $scan, 'expected_checksum', '' ),
			),
			array(
				'key'   => 'can_remediate',
				'value' => $this->yn( ! empty( $scan['can_remediate'] ) ),
			),
		);
		$this->print_rows( $rows, array( 'key', 'value' ), $fmt );

		$issues = (array) $this->get( $scan, 'issues', array() );
		if ( 'table' === $fmt && ! empty( $issues ) ) {
			WP_CLI::warning( 'NFD issues:' );
			foreach ( $issues as $issue ) { WP_CLI::log( ' - ' . $issue ); }
		}

		$ok = 'ok' === $this->get( $scan, 'status', '' );
		$this->maybe_strict_halt( $assoc_args, $ok );
	}

	/**
	 * Apply the current fragments to the NFD block (idempotent safe write).
	 *
	 * ## OPTIONS
	 *
	 * [--version=<version>]
	 * : Version string written to the managed header. Default: 1.0.0
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess apply --version=1.0.0
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. May contain 'version'.
	 * @return void
	 */
	public function apply( $args, $assoc_args ) {
		$version   = $this->default_version( $assoc_args );
		$context   = $this->ctx();
		$fragments = $this->get_enabled_fragments( $context );

		$ok = $this->scanner->remediate( $context, $fragments, $version );
		if ( $ok ) {
			WP_CLI::success( 'Applied NFD block successfully (or no-op if unchanged).' );
		} else {
			WP_CLI::error( 'Failed to apply NFD block.' );
		}
	}

	/**
	 * Remediate the NFD block if drift is detected (scan + apply if needed).
	 *
	 * ## OPTIONS
	 *
	 * [--version=<version>]
	 * : Version string written to the managed header. Default: 1.0.0
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess remediate --version=1.0.0
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. May contain 'version'.
	 * @return void
	 */
	public function remediate( $args, $assoc_args ) {
		$version   = $this->default_version( $assoc_args );
		$context   = $this->ctx();
		$fragments = $this->get_enabled_fragments( $context );

		$scan = (array) $this->scanner->scan( $context, $fragments );
		if ( ! empty( $scan['can_remediate'] ) ) {
			$ok = $this->scanner->remediate( $context, $fragments, $version );
			if ( $ok ) {
				WP_CLI::success( 'Remediation applied.' );
			} else {
				WP_CLI::error( 'Remediation failed.' );
			}
		} else {
			WP_CLI::success( 'No remediation needed.' );
		}
	}

	/**
	 * Restore the latest .htaccess backup (ENTIRE FILE), validate, and self-heal NFD.
	 *
	 * ## OPTIONS
	 *
	 * [--version=<version>]
	 * : Version string written to the managed header on re-apply. Default: 1.0.0
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess restore --version=1.0.0
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. May contain 'version'.
	 * @return void
	 */
	public function restore( $args, $assoc_args ) {
		$fmt       = $this->normalize_format( $assoc_args, 'table' );
		$version   = $this->default_version( $assoc_args );
		$context   = $this->ctx();
		$fragments = $this->get_enabled_fragments( $context );

		$report = (array) $this->scanner->restore_latest_backup_verified( $context, $fragments, $version );

		$rows = array(
			array(
				'key'   => 'restored',
				'value' => $this->yn( ! empty( $report['restored'] ) ),
			),
			array(
				'key'   => 'restored_backup',
				'value' => (string) $this->get( $report, 'restored_backup', '' ),
			),
			array(
				'key'   => 'full_file_valid',
				'value' => $this->yn( ! empty( $report['full_file_valid'] ) ),
			),
			array(
				'key'   => 'nfd_status',
				'value' => (string) $this->get( $this->get( $report, 'nfd_scan', array() ), 'status', '' ),
			),
			array(
				'key'   => 'nfd_remediated',
				'value' => $this->yn( ! empty( $report['nfd_remediated'] ) ),
			),
		);
		$this->print_rows( $rows, array( 'key', 'value' ), $fmt );

		if ( 'table' === $fmt ) {
			$pre = (array) $this->get( $report, 'precheck', array() );
			if ( ! empty( $pre ) ) {
				WP_CLI::log( 'Precheck:' );
				WP_CLI::log( ' - file_valid: ' . $this->yn( ! empty( $pre['file_valid'] ) ) );
				WP_CLI::log( ' - http_status: ' . (string) $this->get( $pre, 'http_status', '' ) );
				WP_CLI::log( ' - reachable: ' . $this->yn( ! empty( $pre['reachable'] ) ) );
				foreach ( (array) $this->get( $pre, 'file_issues', array() ) as $iss ) {
					WP_CLI::log( '   * ' . $iss );
				}
			}
			foreach ( (array) $this->get( $report, 'full_file_issues', array() ) as $issue ) {
				WP_CLI::warning( 'Full-file issue: ' . $issue );
			}
			foreach ( (array) $this->get( $this->get( $report, 'nfd_scan', array() ), 'issues', array() ) as $issue ) {
				WP_CLI::warning( 'NFD issue: ' . $issue );
			}
		}

		$ok = ! empty( $report['restored'] ) && ! empty( $report['full_file_valid'] );
		$this->maybe_strict_halt( $assoc_args, $ok );
	}

	/**
	 * List available .htaccess backups.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp newfold htaccess list-backups
	 *
	 * @param array $args       Positional arguments. Unused.
	 * @param array $assoc_args Associative arguments. Unused.
	 * @return void
	 */
	public function list_backups( $args, $assoc_args ) {
		$list = $this->scanner->list_backups();
		$list = is_array( $list ) ? $list : array();

		if ( empty( $list ) ) {
			WP_CLI::success( 'No backups found.' );
			return;
		}
		$rows = array();
		foreach ( $list as $name ) {
			$rows[] = array( 'backup' => (string) $name );
		}
		Utils\format_items( 'table', $rows, array( 'backup' ) );
	}

	/**
	 * Build a Context snapshot from WP.
	 *
	 * @return Context
	 */
	protected function ctx() {
		if ( class_exists( __NAMESPACE__ . '\Context' ) && method_exists( __NAMESPACE__ . '\Context', 'from_wp' ) ) {
			return Context::from_wp( array() );
		}
		// Extremely defensive fallback (should not happen with this module).
		return new Context( array() );
	}

	/**
	 * Fetch enabled fragments for the current site context.
	 *
	 * @param Context $context Context.
	 * @return Fragment[]
	 */
	protected function get_enabled_fragments( $context ) {
		if ( $this->registry && method_exists( $this->registry, 'enabled_fragments' ) ) {
			return $this->registry->enabled_fragments( $context );
		}

		if ( class_exists( __NAMESPACE__ . '\Api' ) && method_exists( __NAMESPACE__ . '\Api', 'enabled_fragments' ) ) {
			return Api::enabled_fragments( $context );
		}

		// Fallback: empty array to avoid fatal; apply() / remediate() will no-op.
		return array();
	}

	/**
	 * Helper: yes/no string from bool.
	 *
	 * @param bool $v Value.
	 * @return string
	 */
	private function yn( $v ) {
		return $v ? 'yes' : 'no';
	}

	/**
	 * Helper: safe array get with default.
	 *
	 * @param array  $arr     Array.
	 * @param string $key     Key.
	 * @param mixed  $fallback Default value if key not found.
	 * @return mixed
	 */
	private function get( $arr, $key, $fallback = '' ) {
		return ( is_array( $arr ) && array_key_exists( $key, $arr ) ) ? $arr[ $key ] : $fallback;
	}

	/**
	 * Normalize and validate format argument.
	 *
	 * @param array  $assoc_args Associative args.
	 * @param string $fallback    Default format if not specified or invalid.
	 * @return string
	 */
	private function normalize_format( $assoc_args, $fallback = 'table' ) {
		$fmt = isset( $assoc_args['format'] ) ? strtolower( (string) $assoc_args['format'] ) : $fallback;
		return in_array( $fmt, array( 'table', 'json', 'yaml' ), true ) ? $fmt : $fallback;
	}

	/**
	 * Print rows in the specified format.
	 *
	 * @param array  $rows    Rows of data (array of associative arrays).
	 * @param array  $headers Headers (keys to display).
	 * @param string $format  Format: table, json, yaml.
	 * @return void
	 */
	private function print_rows( $rows, $headers, $format ) {
		if ( 'table' === $format ) {
			Utils\format_items( 'table', $rows, $headers );
			return;
		}
		$out = array();
		foreach ( $rows as $r ) {
			$out[] = $r;
		}
		WP_CLI::print_value( $out, array( 'format' => $format ) );
	}

	/**
	 * Sanitize and validate a version string.
	 *
	 * @param array $assoc_args Associative args.
	 * @return string
	 */
	private function default_version( $assoc_args ) {
		$ver = isset( $assoc_args['version'] ) ? (string) $assoc_args['version'] : ( defined( 'NFD_MODULE_HTACCESS_VERSION' ) ? NFD_MODULE_HTACCESS_VERSION : '1.0.0' );
		// allow digits, letters, dots, dashes, underscores
		return preg_replace( '/[^0-9A-Za-z._-]/', '', $ver );
	}

	/**
	 * If --strict is set and $ok is false, halt with error code 1.
	 *
	 * @param array $assoc_args Associative args.
	 * @param bool  $ok         Operation success flag.
	 * @return void
	 */
	private function maybe_strict_halt( $assoc_args, $ok ) {
		if ( ! empty( $assoc_args['strict'] ) && ! $ok ) {
			WP_CLI::halt( 1 );
		}
	}
}
