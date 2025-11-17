<?php
namespace NewfoldLabs\WP\Module\Staging;

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * This class adds staging functionality.
 **/
class Staging {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Slug used for the Staging module's admin page.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'nfd-staging';


	/**
	 * Constructor.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		// Module functionality goes here
		add_action(
			'rest_api_init',
			function () {
				$instance = new StagingApi( $this->container );
				$instance->register_routes();
			}
		);
		add_action( 'wp_loaded', array( StagingMenu::class, 'init' ), 100 );

		// Mark Safe Mode as confirmed so the banner never re-appears
		add_action( 'init', array( $this, 'confirm_jetpack_safe_mode' ), 20 );

		// add isStaging as computed value to container
		$this->container->set(
			'isStaging',
			$this->container->computed(
				function () {
					return $this->isStaging();
				}
			)
		);

		// add CLI commands
		add_action(
			'cli_init',
			function () {
				\WP_CLI::add_command(
					'newfold staging',
					'NewfoldLabs\WP\Module\Staging\StagingCLI',
					array(
						'shortdesc' => 'Operations for Newfold staging.',
						'longdesc'  => 'Internal commands to handle staging environment.' .
										PHP_EOL . 'Subcommands: create, clone, destroy, sso_staging, deploy, deploy_files,' .
										' deploy_db, deploy_files_db, save_state, restore_state, sso_production',
					)
				);
			}
		);

		add_action( 'init', array( __CLASS__, 'loadTextDomain' ), 100 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'initialize_staging_app' ) );

		add_action( 'admin_menu', array( $this, 'add_log_admin_page' ) );

		new Constants( $container );
	}

	/**
	 * Confirms Jetpack Safe Mode programmatically to suppress the banner.
	 *
	 * When a staging site is detected, this method sets the internal Jetpack option
	 * `safe_mode_confirmed` to `true`, which tells Jetpack that the user has already
	 * acknowledged Safe Mode. This prevents repeated prompts or blocked rendering on admin pages.
	 *
	 * @return void
	 */
	public function confirm_jetpack_safe_mode() {
		if ( $this->isStaging() && class_exists( 'Jetpack_Options' ) ) {
			\Jetpack_Options::update_option( 'safe_mode_confirmed', true );
		}
	}

	/**
	 * Initializes the Staging module by registering and enqueuing its assets.
	 *
	 * @return void
	 */
	public static function initialize_staging_app() {
		self::register_staging_assets();
	}

	/**
	 * Registers and enqueues the JavaScript and CSS assets for the Staging module.
	 *
	 * @return void
	 */
	public static function register_staging_assets() {
		$build_dir  = NFD_STAGING_BUILD_DIR;
		$build_url  = NFD_STAGING_BUILD_URL;
		$asset_file = $build_dir . '/staging/staging.min.asset.php';

		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;

			wp_register_script(
				self::PAGE_SLUG,
				$build_url . '/staging/staging.min.js',
				$asset['dependencies'],
				$asset['version'],
				true
			);

			wp_register_style(
				self::PAGE_SLUG,
				$build_url . '/staging/staging.min.css',
				array(),
				$asset['version']
			);
		}

		$screen = \get_current_screen();
		if (
			isset( $screen->id ) &&
			(
				false !== strpos( $screen->id, self::PAGE_SLUG ) ||
				false !== strpos( $screen->id, container()->plugin()->id )
			)
		) {
			wp_enqueue_script( self::PAGE_SLUG );
			wp_enqueue_style( self::PAGE_SLUG );
		}
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function loadTextDomain() {
		\load_plugin_textdomain(
			'wp-module-staging',
			false,
			dirname( plugin_basename( NFD_STAGING_DIR ) ) . '/' . basename( NFD_STAGING_DIR ) . '/languages'
		);
	}

	/**
	 * Get an instance of this class.
	 *
	 * @return Staging
	 */
	public static function getInstance() {
		return new self( container() );
	}

	/**
	 * Get the staging configuration.
	 *
	 * @param bool $cache Whether or not to hit the cached config on this function call.
	 *
	 * @return array
	 */
	public function getConfig( $cache = true ) {
		static $config;

		if ( ! isset( $config ) || false === $cache ) {
			$config = get_option( 'staging_config', array() );
		}

		return $config;
	}

	/**
	 * Get a specific staging configuration value.
	 *
	 * Allowed keys:
	 *  - production_dir
	 *  - production_url
	 *  - staging_dir
	 *  - staging_url
	 *  - creation_date
	 *
	 * @param string $key     Configuration name.
	 * @param string $std Return default value if key doesn't exist.
	 *
	 * @return string
	 */
	public function getConfigValue( $key, $std = '' ) {
		$config = $this->getConfig();

		return isset( $config[ $key ] ) ? $config[ $key ] : $std;
	}

	/**
	 * Get the production directory
	 *
	 * @return string
	 */
	public function getProductionDir() {
		return $this->getConfigValue( 'production_dir', ABSPATH );
	}

	/**
	 * Get the production URL
	 *
	 * @return string
	 */
	public function getProductionUrl() {
		return $this->getConfigValue( 'production_url', site_url() );
	}

	/**
	 * Get the staging directory
	 *
	 * @return string
	 */
	public function getStagingDir() {
		return $this->getConfigValue( 'staging_dir' );
	}

	/**
	 * Get the staging URL
	 *
	 * @return string
	 */
	public function getStagingUrl() {
		return $this->getConfigValue( 'staging_url' );
	}

	/**
	 * Get the staging creation date
	 *
	 * @return string
	 */
	public function getCreationDate() {
		return $this->getConfigValue( 'creation_date' );
	}

	/**
	 * Get the name of the current environment.
	 *
	 * @return string|false
	 */
	public function getEnvironment() {
		return get_option( 'staging_environment', 'production' );
	}

	/**
	 * Get production screenshot URL.
	 *
	 * @return string
	 */
	public function getProductionScreenshotUrl() {
		return '';
	}

	/**
	 * Get staging screenshot URL.
	 *
	 * @return string
	 */
	public function getStagingScreenshotUrl() {
		return '';
	}

	/**
	 * Check if the current environment matches a specific value.
	 *
	 * @param string $env Environment name (production or staging).
	 *
	 * @return bool
	 */
	public function isEnvironment( $env ) {
		return $this->getEnvironment() === $env;
	}

	/**
	 * Check if the current environment is staging.
	 *
	 * @return bool
	 */
	public function isStaging() {
		return $this->isEnvironment( 'staging' );
	}

	/**
	 * Check if the current environment is production.
	 *
	 * @return bool
	 */
	public function isProduction() {
		return $this->isEnvironment( 'production' );
	}

	/**
	 * Check if the staging exists
	 *
	 * @return bool
	 */
	public function stagingExists() {
		$stagingDir = $this->getStagingDir();
		return ! empty( $stagingDir ) && file_exists( $stagingDir );
	}

	/**
	 * Clone production environment to staging.
	 *
	 * @return array|\WP_Error
	 */
	public function cloneProductionToStaging() {
		if ( ! $this->isProduction() ) {
			return new \WP_Error(
				'invalid_environment',
				__( 'Cloning can only be done from the production environment.', 'wp-module-staging' )
			);
		}

		return $this->runCommand( 'clone' );
	}

	/**
	 * Run a compatibility check to see if the environment supports staging.
	 *
	 * @return array|\WP_Error
	 */
	public function compatibilityCheck() {
		return $this->runCommand( 'compat_check' );
	}

	/**
	 * Create a staging environment.
	 *
	 * @return array|\WP_Error
	 */
	public function createStaging() {
		if ( $this->stagingExists() ) {
			return new \WP_Error(
				'environment_exists',
				__( 'Staging environment already exists!', 'wp-module-staging' )
			);
		}

		return $this->runCommand( 'create' );
	}

	/**
	 * Deploy changes from staging to production.
	 *
	 * @param string $type Deployment type. One of `db`, `files`, or `all`.
	 *
	 * @return array|\WP_Error
	 */
	public function deployToProduction( $type = 'all' ) {
		switch ( $type ) {
			case 'db':
				return $this->runCommand( 'deploy_db' );
			case 'files':
				return $this->runCommand( 'deploy_files' );
			default:
				return $this->runCommand( 'deploy_files_db' );
		}
	}

	/**
	 * Destroy the staging environment.
	 *
	 * @return array|\WP_Error
	 */
	public function destroyStaging() {
		if ( ! $this->isProduction() ) {
			return new \WP_Error(
				'invalid_environment',
				__( 'You must switch to the production environment before destroying staging.', 'wp-module-staging' )
			);
		}

		return $this->runCommand( 'destroy' );
	}

	/**
	 * Switch to a different environment.
	 *
	 * @param string $env     Environment name (staging or production).
	 * @param int    $user_id User ID to login as.
	 *
	 * @return array|\WP_Error
	 */
	public function switchTo( $env, $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		if ( $this->isEnvironment( $env ) ) {
			return new \WP_Error(
				'invalid_environment',
				__( 'Switch to an environment you are already in, you cannot.', 'wp-module-staging' )
			);
		}

		if ( 'staging' === $env ) {
			return $this->runCommand( 'sso_staging', array( $user_id ) );
		}

		return $this->runCommand( 'sso_production', array( $user_id ) );
	}

	/**
	 * Execute a staging CLI command.
	 *
	 * @param string     $command CLI command to be run.
	 * @param array|null $args    CLI command arguments to be passed.
	 *
	 * @return array|\WP_Error
	 */
	protected function runCommand( $command, $args = null ) {

		$allowedCommands = array(
			'clone'           => true,
			'compat_check'    => true,
			'create'          => true,
			'deploy_db'       => true,
			'deploy_files'    => true,
			'deploy_files_db' => true,
			'destroy'         => true,
			'sso_production'  => true,
			'sso_staging'     => true,
		);

		// Check if command is allowed
		if ( ! array_key_exists( $command, $allowedCommands ) ) {
			return new \WP_Error(
				'invalid_command',
				__( 'Invalid staging CLI command.', 'wp-module-staging' )
			);
		}

		$config = $this->getConfig();

		// If config is empty, then we are creating a staging environment.
		if ( empty( $config ) || 'create' === $command ) {

			$uniqueId = wp_rand( 1000, 9999 );

			$config = array(
				'creation_date'  => gmdate( 'M j, Y' ),
				'production_dir' => ABSPATH,
				'production_url' => get_option( 'siteurl' ),
				'staging_dir'    => ABSPATH . 'staging/' . $uniqueId,
				'staging_url'    => get_option( 'siteurl' ) . '/staging/' . $uniqueId,
			);

			update_option( 'staging_config', $config );

		}

		$token = wp_generate_password( 32, false );
		set_transient( 'staging_auth_token', $token, 60 );

		$plugin_basename = explode( '/', container()->plugin()->basename );

		$plugin_slug = is_array( $plugin_basename ) && ! empty( $plugin_basename ) ? $plugin_basename[0] : null;

		$command = array(
			$command,
			$token,
			$config['production_dir'],
			$config['staging_dir'],
			$config['production_url'],
			$config['staging_url'],
			get_current_user_id(),
			container()->plugin()->id,
			$plugin_slug,
			container()->plugin()->name,
		);

		if ( $args && is_array( $args ) ) {
			$command = array_merge( $command, array_values( $args ) );
		}

		$command = implode( ' ', array_map( 'escapeshellarg', $command ) );

		// Check for invalid characters
		$invalidChars = array( ';', '&', '|' );
		foreach ( $invalidChars as $char ) {
			if ( false !== strpos( $command, $char ) ) {
				return new \WP_Error(
					'invalid_character',
					// translators: Invalid character that was entered
					sprintf( __( 'Invalid character (%s) in command.', 'wp-module-staging' ), $char )
				);
			}
		}

		$script = container()->plugin()->dir . 'vendor/newfold-labs/wp-module-staging/lib/.staging';

		$disabled_functions = explode( ',', ini_get( 'disable_functions' ) );
		if ( is_array( $disabled_functions ) && in_array( 'exec', array_map( 'trim', $disabled_functions ), true ) ) {
			return new \WP_Error( 'error_response', __( 'Unable to execute script (disabled_function).', 'wp-module-staging' ) );
		}

		// Verify staging script file permissions using WP_Filesystem API
		global $wp_filesystem;

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$creds = request_filesystem_credentials( '', '', false, false, null );

		if ( false === $creds ) {
			return new \WP_Error( 'error_response', __( 'Filesystem credentials required.', 'wp-module-staging' ) );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			return new \WP_Error( 'error_response', __( 'Unable to initialize WP Filesystem.', 'wp-module-staging' ) );
		}

		if ( $wp_filesystem->exists( $script ) ) {
			if ( $wp_filesystem->is_writable( $script ) ) {
				$wp_filesystem->chmod( $script, 0755 );
			} else {
				return new \WP_Error( 'error_response', __( 'Unable to execute script (permission error).', 'wp-module-staging' ) );
			}
		}

		putenv( 'PATH=' . getenv( 'PATH' ) . PATH_SEPARATOR . '/usr/local/bin' ); // phpcs:ignore

		do_action( 'newfold_staging_command', $command ); // bh_staging_command

		$json = exec( "{$script} {$command}" ); // phpcs:ignore

		// Check if we can properly decode the JSON
		$response = json_decode( $json, true );

		if ( ! $response ) {
			return new \WP_Error( 'json_decode', __( 'Something gone wrong, please get in touch with our support.', 'wp-module-staging' ) );
		}

		// Check if response is an error response.
		if ( isset( $response->status, $response->message ) && 'error' === $response->status ) {
			return new \WP_Error( 'error_response', $response->message );
		}

		return $response;
	}

	/**
	 * Add the log admin page to the Tools menu.
	 */
	public function add_log_admin_page() {
		$hook = add_submenu_page(
			'nfd-staging-log',
			__( 'Log Staging', 'wp-module-staging' ),
			'',
			'manage_options',
			'nfd-staging-log',
			array( $this, 'render_log_admin_page' )
		);
		remove_menu_page( $hook );
	}

	/**
	 * Render the log admin page.
	 */
	public function render_log_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( "Don't have capabilities to access this page", 'wp-module-staging' ) );
		}

		$log_file = $this->getProductionDir() . 'nfd-staging.log';

		$logs        = array();
		$filter_date = isset( $_GET['log_date'] ) ? sanitize_text_field( $_GET['log_date'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$per_page    = isset( $_GET['per_page'] ) ? max( 1, intval( $_GET['per_page'] ) ) : 30; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page        = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( file_exists( $log_file ) ) {
			$lines = file( $log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
			foreach ( $lines as $line ) {
				$log_date = substr( $line, 0, 19 );
				if ( $filter_date ) {
					if ( strpos( $log_date, $filter_date ) === 0 ) {
						$logs[] = $line;
					}
				} else {
					$logs[] = $line;
				}
			}
		}

		$total_logs   = count( $logs );
		$total_pages  = $per_page > 0 ? ceil( $total_logs / $per_page ) : 1;
		$start        = ( $page - 1 ) * $per_page;
		$logs_to_show = array_slice( $logs, $start, $per_page );
		$instance     = $this;

		include __DIR__ . '/../views/staging-log.php';
	}
}
