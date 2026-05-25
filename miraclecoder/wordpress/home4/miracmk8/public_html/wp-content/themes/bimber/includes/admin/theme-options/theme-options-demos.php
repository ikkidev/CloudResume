<?php
/**
 * Theme options "Welcome" section (demo data installation step)
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$section_id = 'g1ui-settings-section-demos';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	'',        // Title to be displayed on the administration page.
	null,
	$this->get_page()                   // Page on which to add this section of options.
);

add_settings_field(
	'theme_dashboard_welcome',
	'',
	'bimber_render_theme_dashborad_welcome_section',
	$this->get_page(),
	$section_id
);

/**
 * Render dashboard welcome section
 */
function bimber_render_theme_dashborad_welcome_section() {
	$plugins = bimber_get_theme_plugins_config();

	$nonce = wp_create_nonce( 'bimber-change-mode-ajax-nonce' );

	/**
	 * Automatic plugin installation and activation library
	 *
	 * @var TGM_Plugin_Activation $tgmpa
	 */
	global $tgmpa;

	// Set flag to remove importer after finishing (if it was not installed before).
	if ( ! $tgmpa->is_plugin_installed( 'wordpress-importer' ) ) {
		// Set only once.
		if ( false === get_transient( 'bimber_wp_importer_not_installed' ) ) {
			set_transient( 'bimber_wp_importer_not_installed', true );
		}
	}
	?>
	</td></tr>
	<tr>
	<td colspan="2" style="padding-left: 0;">

	<div style="margin-top: -3em;"></div>

	<?php if ( ! bimber_is_theme_registered() ): ?>

		<div class="bimber-error-message">

			<h1><?php esc_html_e( 'You have to register the Bimber Theme to import demos', 'bimber' ); ?></h1>

			<p>
				<?php printf( esc_html__( 'Please visit the %s section and follow activation steps to import all demos.', 'bimber' ), '<a href="' . esc_url( admin_url( 'themes.php?page=theme-options&group=registration' ) ) . '">'. esc_html__( 'Registration', 'bimber' ) .'</a>' ); ?>
			</p>

		</div>

		<?php return; ?>

	<?php endif; ?>

	<div class="about-wrap theme-options-demos">
		<h1><?php esc_html_e( 'Choose Your Demo', 'bimber' ); ?></h1>
		<br />

		<?php bimber_render_import_demo_response(); ?>

		<?php
		$plugins_to_install = array();

		foreach ( $plugins as $plugin ) {
			$install_plugin_with_demos = $plugin['install_with_demo'];

			if ( 'none' === $install_plugin_with_demos ) {
				continue;
			}

			// Skip if plugin already intalled and activated.
			if ( $tgmpa->is_plugin_active( $plugin['slug'] ) ) {
				continue;
			}

			$action = 'install';

			// Display the 'Install' action link if the plugin is not yet available.
			if ( ! $tgmpa->is_plugin_installed( $plugin['slug'] ) ) {
				$action = 'install';
			} else {
				// Display the 'Update' action link if an update is available and WP complies with plugin minimum.
				if ( false !== $tgmpa->does_plugin_have_update( $plugin['slug'] ) && $tgmpa->can_plugin_update( $plugin['slug'] ) ) {
					$action = 'update';
				}

				// Display the 'Activate' action link, but only if the plugin meets the minimum version.
				if ( $tgmpa->can_plugin_activate( $plugin['slug'] ) ) {
					$action = 'activate';
				}
			}

			$plugins_to_install[] = array(
				'slug'        => $plugin['slug'],
				'name'        => str_replace( 'G1 ', '', $plugin['name'] ),
				'description' => isset( $plugin['description'] ) ? $plugin['description'] : '',
				'rel_demos'   => $install_plugin_with_demos,
				'install_url' => esc_url(
					wp_nonce_url(
						add_query_arg(
							array(
								'plugin'           => urlencode( $plugin['slug'] ),
								'tgmpa-' . $action => $action . '-plugin',
							),
							$tgmpa->get_tgmpa_url()
						),
						'tgmpa-' . $action,
						'tgmpa-nonce'
					)
				),
			);
		}
		?>

		<ul class="g1ui-demo-items">
			<?php
			$bimber_demos           = bimber_get_demos();
			$bimber_demos_installed = get_option( 'bimber_demos_installed', array() );
			?>

			<?php foreach ( $bimber_demos as $demo_id => $demo_config ) : ?>
				<?php
				$classes = array(
					'g1ui-demo',
					'g1ui-demo-' . $demo_id,
				);

				$test_demo_installed  = bimber_htmlspecialchars( filter_input( INPUT_GET, 'test-demo-installed' ) );

				if ( ! empty( $test_demo_installed ) ) {
					$bimber_demos_installed[ $test_demo_installed ] = array(
						'types' => array(
							'content',
							'widgets'
						)
					);
				}

				$classes[] = isset( $bimber_demos_installed[ $demo_id ] ) ? 'g1ui-demo-installed' : 'g1ui-demo-uninstalled';
				?>

				<li class="g1ui-demo-item">
					<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>" data-g1-demo-id="<?php echo esc_attr( $demo_id ); ?>">

						<div class="g1ui-demo-header">
							<strong class="g1ui-demo-title"><?php echo esc_html( $demo_config['name'] ); ?></strong>
						</div>

						<div class="g1ui-demo-body">
							<img class="g1ui-demo-image" width="720" height="720" src="<?php echo esc_url( $demo_config['preview_img'] ); ?>" alt="" />

							<div class="g1ui-demo-uninstall">
								<?php esc_html_e( 'Select components to uninstall:', 'bimber' ); ?>

								<?php if ( isset( $bimber_demos_installed[ $demo_id ] ) ): ?>

									<?php $bimber_content_installed = bimber_is_demo_data_installed( $bimber_demos_installed[ $demo_id ]['types'], 'content' ); ?>
									<?php $bimber_widgets_installed = bimber_is_demo_data_installed( $bimber_demos_installed[ $demo_id ]['types'], 'widgets' ); ?>

									<?php $bimber_input_disabled = ' disabled="disabled" title="' . esc_html__( 'This component was not installed', 'bimber' ) . '"'; ?>

									<p class="action g1-uninstall-type">
										<label><input type="checkbox"<?php checked( $bimber_content_installed ); ?> value="content"<?php if ( ! $bimber_content_installed ) echo $bimber_input_disabled; ?> /><?php esc_html_e( 'Content (posts, pages, images, menus)', 'bimber' ); ?></label><br />

										<label><input type="checkbox"<?php checked( $bimber_widgets_installed ); ?> value="widgets"<?php if ( ! $bimber_widgets_installed ) echo $bimber_input_disabled; ?> /><?php esc_html_e( 'Widgets', 'bimber' ); ?></label>
									</p>

								<?php endif; ?>
							</div>

							<div class="g1ui-demo-install g1ui-plugicon-checkbox-wrapper action g1-import-type">
								<p class="g1ui-demo-import-title"><?php esc_html_e( 'Import:', 'bimber' ); ?></p>

								<ul class="g1ui-demo-import-checkboxes">
									<li>
										<label><input type="checkbox" value="all" checked="checked" /><?php esc_html_e( 'All', 'bimber' ); ?></label>
									</li>
									<li>
										<label><input type="checkbox" value="content" /><?php esc_html_e( 'Content (posts, pages, images, menus)', 'bimber' ); ?></label>
									</li>
									<li>
										<label><input type="checkbox" value="theme-options" /><?php esc_html_e( 'Theme Options', 'bimber' ); ?></label>
									</li>
									<li>
										<label><input type="checkbox" value="widgets" /><?php esc_html_e( 'Widgets', 'bimber' ); ?></label>
									</li>
								</ul>
							</div>

							<div class="g1ui-progress">
								<div class="g1ui-progress-bar"></div>
								<div class="g1ui-progress-percentage">1%</div>
							</div>
						</div>

						<div class="g1ui-demo-actions g1ui-demo-actions-install">
							<a
							href="<?php echo esc_url( bimber_get_import_demo_url( $demo_id ) ); ?>"
							class="g1ui-plugicon-action-install action g1-install-demo-data button button-primary">
								<?php esc_html_e( 'Install', 'bimber' ); ?>
							</a>

							<?php if ( ! empty( $demo_config['preview_url'] ) ) : ?>
							<a href="<?php echo esc_url( $demo_config['preview_url'] ); ?>" target="_blank" class="g1ui-plugicon-action-preview button button-secondary action"><?php esc_html_e( 'Preview', 'bimber' ); ?></a>
							<?php endif; ?>
						</div>

						<div class="g1ui-demo-actions g1ui-demo-actions-uninstall">
							<a
								href="<?php echo esc_url( bimber_get_uninstall_demo_data_url( $demo_id ) ); ?>"
								class="g1-uninstall-demo-data action button button-primary">
								<?php esc_html_e( 'Uninstall', 'bimber' ); ?>
							</a>
						</div>

					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( ! empty( $plugins_to_install ) ) : ?>
			<div class="g1ui-plugicons">
				<?php foreach ( $plugins_to_install as $plugin ) : ?>
					<?php $classes = array(
						'g1ui-plugicon',
						'g1ui-plugicon-' . $plugin['slug'],
						'g1ui-plugicon-checked',
					);
					if ( is_array( $plugin['rel_demos'] ) ) {
						foreach ( $plugin['rel_demos'] as $rel_demo ) {
							$classes[] = 'g1-demo-' . $rel_demo;
						}
					} else {
						$classes[] = 'g1-demos-' . $plugin['rel_demos'];
					}
					?>
					<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ?>" data-g1-plugin-id="<?php echo esc_attr( $plugin['slug'] ); ?>">
						<span class="g1ui-plugicon-icon"></span>
						<span class="g1ui-plugicon-title"><?php echo esc_html( $plugin['name'] ); ?></span>
						<span class="g1ui-plugicon-desc"><?php echo esc_html( $plugin['description'] ); ?></span>

						<div class="g1ui-plugicon-bar">
							<input 	type="checkbox" class="g1-plugin-to-install g1ui-plugicon-checkbox"
								   	name="<?php echo esc_attr( $plugin['slug'] ); ?>"
								   	data-g1-install-url="<?php echo esc_url( $plugin['install_url'] ); ?>"
									checked="checked" />
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div id="g1-demo-import-log"></div>

		<input type="hidden" id="g1-change-mode-ajax-nonce" value="<?php echo esc_attr( $nonce ); ?>"/>
		<input type="hidden" id="g1-upload-demo-images-start" value="<?php echo esc_url( admin_url( 'admin.php?action=bimber_import_demo_images_start&import=bimber' ) ); ?>"/>
		<input type="hidden" id="g1-upload-demo-image" value="<?php echo esc_url( admin_url( 'admin.php?action=bimber_import_demo_image&import=bimber' ) ); ?>"/>
		<input type="hidden" id="g1-upload-demo-images-end" value="<?php echo esc_url( admin_url( 'admin.php?action=bimber_import_demo_images_end&import=bimber' ) ); ?>"/>
	</div>
	<?php
}

/**
 * Render response after import demo data
 */
function bimber_render_import_demo_response() {
	$import_response = get_transient( 'bimber_import_demo_response' );

	if ( false !== $import_response ) {
		delete_transient( 'bimber_import_demo_response' );

		foreach( $import_response as $res ) {
			$response_status_class = 'success' === $res['status'] ? 'notice' : 'error';
			?>
			<div class="updated is-dismissible <?php echo sanitize_html_class( $response_status_class ); ?>">
				<p>
					<strong><?php echo wp_kses_post( $res['message'] ); ?></strong><br/>
				</p>
				<button type="button" class="notice-dismiss"><span
						class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'bimber' ); ?></span></button>
			</div>
			<?php
		}
	}
}
