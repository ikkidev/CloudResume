<?php
/**
 * Theme options section
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$section_id = 'g1ui-settings-section-shares';
$css_class = bimber_shares_enabled() ? '' : 'bimber-shares-row-hidden';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	__( 'Shares', 'bimber' ),           // Title to be displayed on the administration page.
	'__return_empty_string',            // Description to be displayed below the title, on the administration page.
	$this->get_page()                   // Page on which to add this section of options.
);

//
// General
//

add_settings_field(
	'shares_general_header',
	'<h2>' . __( 'General', 'bimber' ) . '</h2>',
	'__return_empty_string',
	$this->get_page(),
	$section_id
);

// General > Enabled

add_settings_field(
	'shares_enabled',
	_x( 'Enabled', 'Shares Settings', 'bimber' ),
	'bimber_render_shares_enabled',
	$this->get_page(),
	$section_id
);

// General > Debug Mode

add_settings_field(
	'shares_debug_mode',
	_x( 'Debug mode', 'Shares Settings', 'bimber' ),
	'bimber_render_shares_debug_mode',
	$this->get_page(),
	$section_id,
	array(
		'class' => $css_class,
	)
);

// General > Facebook App Id

add_settings_field(
	'facebook_app_id_info',
	_x( 'Facebook App ID', 'Settings', 'bimber' ),
	'bimber_render_facebook_app_id_info',
	$this->get_page(),
	$section_id,
    array(
        'class' => $css_class,
    )
);

//
// Positions
//

add_settings_field(
	'shares_positions_header',
	'<h2>' . __( 'Positions', 'bimber' ) . '</h2>',
	'__return_empty_string',
	$this->get_page(),
	$section_id,
	array(
		'class' => $css_class,
	)
);

// Positions > All

add_settings_field(
	'shares_positions',
	'',
	'bimber_render_shares_positions',
	$this->get_page(),
	$section_id,
	array(
		'class' => $css_class,
	)
);

/**
 * Render the Enabled field
 */
function bimber_render_shares_enabled() {
	$enabled    = bimber_shares_enabled();
	$field_name = sprintf( '%s[%s]', bimber_get_theme_options_id(), 'shares_enabled' );

	?>
	<select id="shares_enabled" name="<?php echo esc_attr( $field_name ); ?>" xmlns="http://www.w3.org/1999/html">
		<option value="none"<?php selected( $enabled, false ); ?>><?php echo esc_html__( 'no', 'bimber' ); ?></option>
		<option value="standard"<?php selected( $enabled, true ); ?>><?php echo esc_html__( 'yes', 'bimber' ); ?></option>
	</select>
	<p class="description">
		<?php echo esc_html_x( 'Use this option to disable entire module and all share slots at once', 'Shares Settings', 'bimber' ); ?>
	</p>
	<?php
}

/**
 * Render the Debug mode field
 */
function bimber_render_shares_debug_mode() {
	$enabled    = bimber_shares_debug_mode_enabled();
	$field_name = sprintf( '%s[%s]', bimber_get_theme_options_id(), 'shares_debug_mode' );

	?>
	<select id="shares_debug_mode" name="<?php echo esc_attr( $field_name ); ?>">
		<option value="none"<?php selected( $enabled, false ); ?>><?php echo esc_html__( 'disabled', 'bimber' ); ?></option>
		<option value="standard"<?php selected( $enabled, true ); ?>><?php echo esc_html__( 'enabled', 'bimber' ); ?></option>
	</select>
	<p class="description">
		<?php echo esc_html_x( 'Enable to log share details into the JavaScript console (Google Chrome DevTools, Firefox Developer Tools)', 'Shares Settings', 'bimber' ); ?>
	</p>
	<?php
}

/**
 * Render the Facebook App ID field
 */
function bimber_render_facebook_app_id_info() {
	$fb_app_id = bimber_get_facebook_app_id();
	$apis_url = admin_url( 'themes.php?page=theme-options&group=apis' );
    ?>
	<p class="description">
		<?php if ( empty( $fb_app_id ) ) {
            _ex( 'The Facebook App ID is not set.', 'Theme Options', 'bimber' );
        } else {
            _ex( 'The Facebook App ID is set.', 'Theme Options', 'bimber' );
        }
        ?>
        <a href="<?php echo esc_url( $apis_url ) ?>"><?php echo esc_html( _x( 'Change', 'Theme Options', 'bimber' ) ); ?></a>
	</p>
	<?php
}

/**
 * Render all positions
 */
function bimber_render_shares_positions() {
	$positions = bimber_get_share_positions();

	foreach ( $positions as $position => $position_config ) {
        $positions = bimber_get_theme_option( 'shares', 'positions' );
		$enabled  = isset( $positions['active'] ) ? in_array( $position, $positions['active'] ) : false;

		// Field names.
		$enabled_field        = sprintf( '%s[%s][active][]', bimber_get_theme_options_id(), 'shares_positions' );
		$networks_field       = sprintf( '%s[%s][%s][networks][]', bimber_get_theme_options_id(), 'shares_positions', $position );
		$networks_order_field = sprintf( '%s[%s][%s][networks_order]', bimber_get_theme_options_id(), 'shares_positions', $position );

		// Options.
		$position_active_networks = bimber_get_share_position_active_networks( $position );
		$position_networks_order = bimber_get_share_position_networks_order( $position );

		$availability = $position_config['availability'];
		$active = $position_config['is_editable'] ? $availability['check'] : $availability['check'];
		?>
		<div class="g1ui-ssb-slot">
			<div class="g1ui-ssb-slot-overview">
				<h3>
					<?php echo $position_config['name']; ?> <span class="description"><?php echo $position_config['type']; ?></span>
				</h3>

				<?php if ( $active ) : ?>
					<p class="g1ui-ssb-slot-status g1ui-ssb-slot-status-available">
						<?php echo esc_html_x( 'Available', 'Shares', 'bimber' ); ?>
					</p>
				<?php else : ?>
					<p class="g1ui-ssb-slot-status g1ui-ssb-slot-status-not-available">
						<?php echo esc_html_x( 'Not available', 'Shares', 'bimber' ); ?>
						<?php if ( ! $availability['check'] ): ?>
							&mdash; <?php echo $availability['info']; ?>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>


			<?php if ( $position_config['is_editable'] ): ?>
				<?php
				$bimber_class = array(
					'g1ui-ssb-slot-details',
				);
				if ( ! $active ) {
					$bimber_class[] = 'g1ui-ssb-slot-details-hidden';
				}
				?>
				<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
					<p>
						<?php echo esc_html_x( 'Enabled', 'Shares', 'bimber' ); ?>: <input type="checkbox" name="<?php echo( esc_attr( $enabled_field ) ); ?>" value="<?php echo esc_attr( $position ) ?>" <?php checked( true, $enabled ); ?> />
					</p>

					<p>
						<strong><?php echo esc_html_x( 'Networks', 'Shares', 'bimber' ); ?></strong> <span class="description"><?php echo esc_html_x( '(drag and drop to reorder)', 'bimber' ); ?></span>
					</p>
					<div class="bimber-shares-networks-wrapper">
						<ul class="bimber-share-networks sortable">
							<?php

							foreach ( $position_networks_order as $network ) {
								$checked = in_array( $network, $position_active_networks, true );
								?>
								<li>
									<label>
										<input type="checkbox" class="bimber-share-network" name="<?php echo esc_attr( $networks_field ) ?>" value="<?php echo esc_attr( $network ); ?>"<?php checked( $checked ) ?> /> <?php echo esc_html( ucfirst( $network ) ); ?>
									</label>
								</li>
								<?php
							}
							?>
						</ul>

						<input type="hidden" class="bimber-share-networks-order" name="<?php echo esc_attr( $networks_order_field ) ?>" value="<?php echo esc_attr( implode( ',', $position_networks_order ) ); ?>" />
					</div>
				</div>
			<?php endif; ?>
			<?php if( ! $position_config['is_editable'] && $availability['check'] ): ?>
				<div class="g1ui-ssb-slot-details">
					<p>
						<a href="<?php echo esc_url( $position_config['edit_page_url'] ); ?>" target="_blank"><?php echo esc_html_x( 'Edit plugin settings', 'bimber' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
