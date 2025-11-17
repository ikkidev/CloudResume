<?php
namespace NewfoldLabs\WP\Module\Migration\Reports;

use NewfoldLabs\WP\Module\Migration\Services\Tracker;
use NewfoldLabs\WP\Module\Migration\Helpers\Permissions;
/**
 * Class to add a page report to see the tracking informations.
 *
 * @package wp-module-migration
 */
class MigrationReport {
	/**
	 * Identifier for page and assets.
	 *
	 * @var string
	 */
	public static $slug = 'nfd-migration';

	/**
	 * The tracker instance.
	 *
	 * @var Tracker
	 */
	protected $tracker;

	/**
	 * UIReport constructor.
	 */
	public function __construct() {
		$this->tracker = new Tracker();
		add_action( 'admin_menu', array( $this, 'add_page' ) );
	}

	/**
	 * Add the report page to the admin menu.
	 *
	 * @return void
	 */
	public function add_page() {
		$hook = add_submenu_page(
			self::$slug,
			__( 'Migration Report', 'wp-module-migration' ),
			'',
			Permissions::ADMIN,
			self::$slug,
			array( $this, 'get_report_page' ),
		);

		remove_menu_page( $hook );
	}

	/**
	 * Get the report page callback.
	 *
	 * @return void
	 */
	public function get_report_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Migration Report', 'wp-module-migration' ); ?></h1>
			<div class="nfd-migration-report">
				<?php
				$report_content = $this->get_report_content();
				if ( ! empty( $report_content ) ) {
					echo '<ul>';
					foreach ( $report_content as $step => $values ) {
						echo '<li>';
						echo '<h3>' . esc_html( $step ) . '</h3>';
						foreach ( $values as $key => $value ) {
							if ( ! empty( $value ) && ! is_array( $value ) ) {
								echo '<ul>';
								echo '<li><strong>' . esc_html( $key ) . '</strong>: ' . esc_html( $value ) . '</li>';
								echo '</ul>';
							} elseif ( is_array( $value ) && ! empty( $value ) ) {
								echo '<ul>';
								echo '<li><strong>' . esc_html( $key ) . '</strong>: ';
								echo '<ul>';
								foreach ( $value as $sub_key => $sub_value ) {
									if ( is_array( $sub_value ) ) {
										echo '<pre>' . print_r( $sub_value, true ) . '</pre>'; // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.Security.EscapeOutput.OutputNotEscaped
									} else {
										echo '<li>' . esc_html( $sub_key ) . ': ' . esc_html( $sub_value ) . '</li>';
									}
								}
								echo '</ul>';
								echo '</li>';
								echo '</ul>';
							}
						}
						echo '</li>';
					}
					echo '</ul>';
				} else {
					echo '<p class="no-report">' . esc_html__( 'No report available.', 'wp-module-migration' ) . '</p>';
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get the report content.
	 *
	 * @return array
	 */
	private function get_report_content() {
		return $this->tracker->get_track_content();
	}
}