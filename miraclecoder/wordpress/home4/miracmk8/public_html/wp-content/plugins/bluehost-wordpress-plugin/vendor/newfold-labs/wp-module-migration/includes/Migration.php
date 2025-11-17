<?php
namespace NewfoldLabs\WP\Module\Migration;

use NewfoldLabs\WP\Module\Migration\Data\Constants;
use NewfoldLabs\WP\Module\Migration\Helpers\BrandHelper;
use NewfoldLabs\WP\Module\Migration\Helpers\Permissions;
use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Migration\RestApi\RestApi;
use NewfoldLabs\WP\Module\Migration\Services\InstaMigrateService;
use NewfoldLabs\WP\Module\Migration\Reports\MigrationReport;
use NewfoldLabs\WP\Module\Migration\Listeners\InstaWpOptionsUpdatesListener;
use NewfoldLabs\WP\Module\Migration\Services\UtilityService;
/**
 * Class Migration
 *
 * @package NewfoldLabs\WP\Module\Migration
 */
class Migration {

	/**
	 * Container loaded from the brand plugin.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * To create insta service instance
	 *
	 * @var insta_service
	 */
	protected $insta_service;

	/**
	 * Identifier for script handle.
	 *
	 * @var string
	 */
	public static $handle = 'nfd-migration';

	/**
	 * Migration constructor.
	 *
	 * @param Container $container Container loaded from the brand plugin.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		new Constants( $container );
		new InstaWpOptionsUpdatesListener();

		if ( Permissions::rest_is_authorized_admin() ) {
			new RestApi();
		}

		if ( Permissions::is_authorized_admin() ) {
			new MigrationReport();
			add_action( 'init', array( $this, 'load_text_domain' ), 100 );
			if ( BrandHelper::is_whitelisted( $container->plugin()->id ) ) {
				add_action( 'load-import.php', array( $this, 'register_wp_migration_tool' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'set_import_tools' ) );
			}
		}

		add_action( 'pre_update_option_nfd_migrate_site', array( $this, 'on_update_nfd_migrate_site' ) );
		add_action( 'pre_update_option_instawp_last_migration_details', array( $this, 'on_update_instawp_last_migration_details' ), 10, 1 );
	}

	/**
	 * Triggers on instawp connect installation
	 *
	 * @param boolean $option status of migration.
	 */
	public function on_update_nfd_migrate_site( $option ) {
		$this->insta_service = new InstaMigrateService();
		$this->insta_service->run();
		return $option;
	}

	/**
	 * Updates nfd_show_migration_steps option based on instawp_last_migration_details
	 *
	 * @param array $new_option status of migration.
	 */
	public function on_update_instawp_last_migration_details( $new_option ) {
		$migrate_group_uuid = isset( $new_option['migrate_group_uuid'] ) ? $new_option['migrate_group_uuid'] : '';
		if ( ! empty( $migrate_group_uuid ) ) {
			$data = UtilityService::get_migration_data( $migrate_group_uuid );
			if ( $data && is_array( $data ) && isset( $data['status'] ) && $data['status'] ) {
				$migration_status = $data['data']['status'];
				if ( 'completed' === $migration_status ) {
					update_option( 'nfd_show_migration_steps', true );
				}
			}
		}
		return $new_option;
	}

	/**
	 * Register WordPress Migration Tool to imports.
	 */
	public function register_wp_migration_tool() {
		register_importer(
			'site_migration_wordpress_importer',
			__( 'WordPress Migration Tool', 'wp-module-migration' ),
			__( 'Migrate an existing WordPress site to this WordPress instance. This tool will make a copy of an existing site and automatically import it into this WordPress instance <strong>This will overwrite all the content.</strong>', 'wp-module-migration' ),
			array( $this, 'wordpress_migration_tool' )
		);
	}

	/**
	 * Initiates the Migration service redirects it the instawp screen
	 */
	public function wordpress_migration_tool() {
		$this->insta_service = new InstaMigrateService();
		$response            = $this->insta_service->run();
		if ( ! is_wp_error( $response ) ) {
			wp_safe_redirect( apply_filters( 'nfd_build_url', $response['redirect_url'] ) );
		} else {
			wp_safe_redirect( apply_filters( 'nfd_build_url', admin_url( 'import.php' ) ) );
		}
		die();
	}

	/**
	 * Changes the text WordPress to WordPress content in import page
	 */
	public function set_import_tools() {
		global $pagenow;

		$script_subpath = 'includes/import-tools-changes.js';
		$style_subpath  = 'includes/styles.css';
		$script_path    = NFD_MIGRATION_DIR . $script_subpath;
		$style_path     = NFD_MIGRATION_DIR . $style_subpath;
		$script_version = file_exists( $script_path ) ? filemtime( $script_path ) : '1.0';
		$style_version  = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0';

		wp_register_script(
			'nfd_migration_tool',
			NFD_MIGRATION_PLUGIN_URL . 'vendor/newfold-labs/wp-module-migration/' . $script_subpath,
			array( 'jquery' ),
			$script_version,
			true
		);

		wp_register_style(
			'nfd_migration_tool',
			NFD_MIGRATION_PLUGIN_URL . 'vendor/newfold-labs/wp-module-migration/' . $style_subpath,
			array(),
			$style_version,
			'all'
		);

		$is_ui_tracking_page = isset( $_GET['page'] ) && 'nfd-migration' === $_GET['page'] && 'admin.php' === $pagenow; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $is_ui_tracking_page ) {
			wp_enqueue_style( 'nfd_migration_tool' );
		}
		if ( 'import.php' === $pagenow ) {
			wp_enqueue_script( 'nfd_migration_tool' );
			wp_enqueue_style( 'nfd_migration_tool' );

			$migration_data = array(
				'migration_title'       => __( 'Preparing your site', 'wp-module-migration' ),
				'migration_description' => __( 'Please wait a few seconds while we get your new account ready to import your existing WordPress site.', 'wp-module-migration' ),
				'wordpress_title'       => __( 'WordPress Content', 'wp-module-migration' ),
				'restApiUrl'            => \get_home_url( null, '/index.php?rest_route=' ),
				'restApiNonce'          => \wp_create_nonce( 'wp_rest' ),
			);
			wp_localize_script( 'nfd_migration_tool', 'migration', $migration_data );
		}
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public function load_text_domain() {
		load_plugin_textdomain(
			'wp-module-migration',
			false,
			dirname( $this->container->plugin()->basename ) . '/vendor/newfold-labs/wp-module-migration/languages'
		);
	}
}
