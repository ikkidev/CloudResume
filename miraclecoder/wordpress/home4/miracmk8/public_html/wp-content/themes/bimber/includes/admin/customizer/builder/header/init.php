<?php
/**
 * Initialize Header Builder Module
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

add_action( 'bimber_after_customize_register',          'bimber_customize_hb_register' );
add_action( 'customize_controls_enqueue_scripts',       'bimber_customize_hb_load_assets' );
add_action( 'customize_controls_print_footer_scripts',  'bimber_customize_hb_render' );

add_action( 'wp_ajax_bimber_hb_load_preset',            'bimber_ajax_hb_load_preset' );

/**
 * Load preset via AJAX.
 *
 * @return void
 */
function bimber_ajax_hb_load_preset() {
	$composition_name = bimber_htmlspecialchars( filter_input( INPUT_POST, 'composition' ) );
	$layouts = bimber_hb_get_layouts();
	if ( isset( $layouts[ $composition_name ] ) ) {
		$composition = json_decode( $layouts[ $composition_name ], true );
		ob_start();
		bimber_customize_hb_render( $composition );
		$output = ob_get_clean();
	} else {
		$output =  '';
	}

	$layouts_settings = bimber_hb_get_settings();
	if ( isset( $layouts_settings[ $composition_name ] ) ) {
		$settings = $layouts_settings[ $composition_name ];
	} else {
		$settings = array();
	}
	$res = array(
		'html' => $output,
		'settings' => $settings,
	);
	echo wp_json_encode( $res );
	exit();
}

/**
 * Add options to customizer
 *
 * @param WP_Customize $wp_customize  Wp customize object.
 */
function bimber_customize_hb_register( $wp_customize ) {
	$bimber_option_name = bimber_get_theme_id();
	$layouts = bimber_hb_get_layouts();
	$default = json_decode( $layouts['original'], true );

	// Mobile composition.
	$wp_customize->add_setting( $bimber_option_name . '[header_builder]', array(
		'default'           => $default,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'bimber_sanitize_hb_composition',
		'transport'  		=> 'postMessage',
	) );

	$wp_customize->add_control( 'bimber_hb_open_button', array(
		'type' => 'button',
		'settings' => array(),
		'section' => 'bimber_header_layout_section',
		'priority'	=> '1',
		'input_attrs' => array(
			'value' => __( 'Open Header Builder', 'bimber' ),
			'class' => 'button button-primary g1-hb-open',
		),
	));

}

/**
 * Load assets
 */
function bimber_customize_hb_load_assets() {

	wp_enqueue_style( 'bimber-customizer-builder', BIMBER_ADMIN_DIR_URI . 'customizer/builder/css/builder.css' );
	wp_enqueue_script( 'bimber-customizer-builder', BIMBER_ADMIN_DIR_URI . 'customizer/builder/js/builder.js', array( 'jquery' ), null, true );

	$data = array(
		'values' 	=> bimber_get_theme_option( 'header_builder', '' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'bimber-customizer-builder', 'bimber_customizer_builder', $data );
}

/**
 * Render the builder
 *
 * @param mixed $composition  Composition.
 */
function bimber_customize_hb_render( $composition = false ) {
	$g1_hb_current = is_array( $composition ) ? $composition : bimber_get_theme_option( 'header_builder', '' );
	?>
	<div class="g1-hb">
		<div class="g1-hb-top-bar">
			<div data-bimber-tab="desktop" class="g1-hb-button g1-hb-tab-button g1-hb-tabs-switcher-desktop g1-hb-tab-active"><?php echo esc_html__( 'Desktop', 'bimber' )?></div>
			<div data-bimber-tab="mobile" class="g1-hb-button g1-hb-tab-button g1-hb-tabs-switcher-mobile"><?php echo esc_html__( 'Mobile', 'bimber' )?></div>
			<div data-bimber-tab="canvas" class="g1-hb-button g1-hb-tab-button g1-hb-tabs-switcher-canvas"><?php echo esc_html__( 'Off-Canvas', 'bimber' )?></div>
			<div class="g1-hb-button g1-hb-tabs-switcher-close"><?php echo esc_html_x( 'Close', 'button', 'bimber' )?></div>
		</div>
		<div class="g1-hb-tabs g1-hb-tabs-desktop g1-hb-tabs-active">
			<div class="g1-hb-tabs-content">
				<?php bimber_hb_render_tab( 'normal', $g1_hb_current['normal'], true );?>
			</div>
		</div>
		<div class="g1-hb-tabs g1-hb-tabs-mobile">
			<div class="g1-hb-tabs-content">
				<?php bimber_hb_render_tab( 'mobile', $g1_hb_current['mobile'], true );?>
			</div>
		</div>
		<div class="g1-hb-tabs g1-hb-tabs-canvas">
			<div class="g1-hb-tabs-content">
				<?php bimber_hb_render_canvas_tab( 'canvas', $g1_hb_current['canvas'], true );?>
			</div>
		</div>
		<?php
		if ( defined( 'BTP_DEV' ) && BTP_DEV && defined( 'BTP_HB_PRESET' ) && BTP_HB_PRESET ) { ?>
			Preset to copy (this is only visible in DEV mode) <input type="text" class="g1-hb-preset" value="">
		<?php }?>
	</div>
	<?php
}

/**
 * Render a tab.
 *
 * @param string  $tab_slug		Tab's slug.
 * @param array   $values_tab		Tab's settings.
 * @param boolean $active 		Is tab active.
 */
function bimber_hb_render_tab( $tab_slug, $values_tab, $active = false ) {
	?>
	<div
	data-bimber-tab="<?php echo esc_attr( $tab_slug );?>"
	class="g1-hb-tabs-content-tab g1-hb-tabs-content-<?php echo sanitize_html_class( $tab_slug );?> <?php if ( $active ) { echo sanitize_html_class( 'g1-hb-tabs-content-active' ); } ?>">
		<div class="g1-hb-layout">
			<?php bimber_hb_render_row( 1, $values_tab[1], $tab_slug ); ?>
			<?php bimber_hb_render_row( 2, $values_tab[2], $tab_slug ); ?>
			<?php bimber_hb_render_row( 3, $values_tab[3], $tab_slug ); ?>
		</div>
		<div class="g1-hb-elements g1-hb-element-container">
			<h3 class="g1-hb-element-container-header">Unused elements</h3>
			<?php
				$elements = bimber_hb_get_unused_elements( $values_tab );
				bimber_hb_render_elements( $tab_slug, $elements );
			?>
		</div>
	</div>
	<?php
}

/**
 * Render a canvas tab.
 *
 * @param string  $tab_slug		Tab's slug.
 * @param array   $values_tab	Tab's settings.
 * @param boolean $active 		Is tab active.
 */
function bimber_hb_render_canvas_tab( $tab_slug, $values_tab, $active = false ) {
	?>
	<div
	data-bimber-tab="<?php echo esc_attr( $tab_slug );?>"
	class="g1-hb-tabs-content-tab g1-hb-tabs-content-<?php echo sanitize_html_class( $tab_slug );?> <?php if ( $active ) { echo sanitize_html_class( 'g1-hb-tabs-content-active' ); } ?>">
		<div class="g1-hb-layout">
			<div class="g1-hb-layout-row-wrapper">
				<div class="g1-hb-layout-row g1-hb-layout-row-1">
					<div class="g1-hb-layout-col g1-hb-layout-col-canvas g1-hb-layout-col-1">
						<div class="g1-hb-layout-row-content g1-hb-element-container">
							<?php bimber_hb_render_elements( $tab_slug, $values_tab[1]['cols'][1]['elements'] );?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="g1-hb-elements g1-hb-element-container">
			<h3 class="g1-hb-element-container-header">Available elements</h3>
			<?php
				$elements = bimber_hb_get_unused_elements( $values_tab );
				bimber_hb_render_elements( $tab_slug, $elements );
			?>
		</div>
	</div>
	<?php
}

/**
 * Get unused elements
 *
 * @param array $values_tab	Tab's settings.
 */
function bimber_hb_get_unused_elements( $values_tab ) {
	$result = bimber_hb_get_elements();
	foreach ( $values_tab as $row_index => $row ) {
		foreach ( $row['cols'] as $col_index => $col ) {
			foreach ( $col['elements'] as $element ) {
				unset( $result[ $element ] );
			}
		}
	}
	return array_keys( $result );
}

/**
 * Render elements.
 *
 * @param string $tab_slug Tab's slug.
 * @param array  $elements Elements.
 */
function bimber_hb_render_elements( $tab_slug, $elements ) {
	$all_elements = bimber_hb_get_elements();
	?>
		<?php foreach ( $elements as $slug ) :
			if ( isset( $all_elements[ $slug ] ) ) {
				$element = $all_elements[ $slug ];
			} else {
				continue;
			}
			?>
			<?php if ( in_array( $tab_slug, $element['tabs'], true ) ) :?>
				<div
					data-bimber-element="<?php echo esc_attr( $slug );?>"
					<?php if ( isset( $element['control'] ) ) : ?>
						data-bimber-control="<?php echo esc_attr( $element['control'] );?>"
					<?php endif;?>
					<?php if ( isset( $element['section'] ) ) : ?>
						data-bimber-section="<?php echo esc_attr( $element['section'] );?>"
					<?php endif;?>
					<?php if ( isset( $element['panel'] ) ) : ?>
						data-bimber-panel="<?php echo esc_attr( $element['panel'] );?>"
					<?php endif;?>
					<?php if ( isset( $element['highlight'] ) ) : ?>
						data-bimber-highlight="<?php echo esc_attr( $element['highlight'] );?>"
					<?php endif;?>
					class="g1-hb-element
					<?php
					if ( isset( $element['plugin'] ) ) {
						if ( ! bimber_can_use_plugin( $element['plugin'] ) ) {
							echo ' g1-hb-gray-out-element';
						}
					}
					?>"><?php echo wp_kses_post( $element['label'] );?></div>
			<?php endif;?>
		<?php endforeach; ?>
	<?php
}

/**
 * Render a row.
 *
 * @param int    $row_number  Row's number.
 * @param array  $values_row  Rows settings.
 * @param string $tab_slug	  Tab's slug.
 */
function bimber_hb_render_row( $row_number, $values_row, $tab_slug ) {
	?>
	<div
	data-bimber-letter ="<?php echo esc_attr( $values_row['letter'] );?>"
	data-bimber-style="<?php echo esc_attr( $values_row['style'] );?>"
	data-bimber-sticky="<?php echo esc_attr( $values_row['sticky'] );?>"
	data-bimber-shadow="<?php echo esc_attr( $values_row['shadow'] );?>"
	data-bimber-icons="<?php echo esc_attr( $values_row['icons'] );?>"
	class="g1-hb-layout-row-wrapper">
	<div class="g1-hb-layout-row-letter"><?php echo esc_attr( $values_row['letter'] );?></div>
	<div class="g1-hb-layout-row-handle"></div>
		<?php bimber_hb_render_row_settings( $values_row );?>
		<div class="g1-hb-layout-row g1-hb-layout-row-<?php echo sanitize_html_class( $row_number );?>">
			<?php
				bimber_hb_render_column( 1, $values_row['cols'][1], $tab_slug, $row_number );
				bimber_hb_render_column( 2, $values_row['cols'][2], $tab_slug, $row_number );
				bimber_hb_render_column( 3, $values_row['cols'][3], $tab_slug, $row_number );
			?>
		</div>
	</div>
	<?php
}

/**
 * Render row's settings.
 *
 * @param array  $values_row  Rows settings.
 */
function bimber_hb_render_row_settings( $values_row ) {
	?>
	<div class="g1-hb-layout-row-settings g1-hb-layout-button-settings">
		<div class="g1-hb-layout-settings-box">
			<div class="g1-hb-layout-row-style">
				<label><?php echo esc_html__( 'Style:', 'bimber' )?></label>
				<select class="g1-hb-layout-style-select">
					<option value="full" <?php selected( 'full', $values_row['style'], true );?>><?php echo esc_html_x( 'Stretched', 'layout', 'bimber' )?></option>
					<option value="boxed" <?php selected( 'boxed', $values_row['style'], true );?>><?php echo esc_html_x( 'Boxed', 'layout', 'bimber' )?></option>
				</select>
			</div>
			<div class="g1-hb-layout-row-sticky">
				<label><?php echo esc_html__( 'Sticky:', 'bimber' )?></label>
				<select class="g1-hb-layout-row-sticky-select">
					<option value="on" <?php selected( 'on', $values_row['sticky'], true );?>>On</option>
					<option value="off" <?php selected( 'off', $values_row['sticky'], true );?>>Off</option>
				</select>
			</div>
			<div class="g1-hb-layout-row-shadow">
				<label><?php echo esc_html__( 'Shadow:', 'bimber' )?></label>
				<select class="g1-hb-layout-row-shadow-select">
					<option value="on" <?php selected( 'on', $values_row['shadow'], true );?>>On</option>
					<option value="off" <?php selected( 'off', $values_row['shadow'], true );?>>Off</option>
				</select>
			</div>
			<div class="g1-hb-settings-box-close-button"></div>
		</div>
	</div>
	<?php
}

/**
 * Render a column.
 *
 * @param int    $col_number  		Col's number.
 * @param array  $values__column  	Cols settings.
 * @param string $tab_slug	  		Tab's slug.
 * @param int    $row_number  		Row's number.
 */
function bimber_hb_render_column( $col_number, $values_column, $tab_slug, $row_number ) {
	?>
	<div class="g1-hb-layout-col g1-hb-layout-col-<?php echo sanitize_html_class( $col_number );?>"
		data-bimber-col-grow="0" data-bimber-col-align="left">
		<?php
		$unique_id_for_input_names = $tab_slug . '-' . $row_number . '-' . $col_number;
		bimber_hb_render_col_settings( $values_column, $unique_id_for_input_names );?>
		<div class="g1-hb-layout-row-content g1-hb-element-container">
			<?php bimber_hb_render_elements( $tab_slug, $values_column['elements'] );?>
		</div>
	</div>
	<?php
}

/**
 * Render col settings.
 *
 * @param array  $values_column					Col's settings.
 * @param string $unique_id_for_input_names		Unique id for inputs.
 */
function bimber_hb_render_col_settings( $values_column, $unique_id_for_input_names ) {
	?>
	<div class="g1-hb-layout-col-settings g1-hb-layout-button-settings">
		<div class="g1-hb-layout-settings-box">
			<div class="g1-hb-layout-col-align g1-hb-radios">
				<label><?php echo esc_html__( 'Align:', 'bimber' )?></label>
					<label for="left">
						<input name="<?php echo $unique_id_for_input_names; ?>-align" type="radio" value="left" <?php checked( 'left', $values_column['align'], true );?>>
						<?php echo esc_html__( 'Left', 'bimber' )?>
					</label>
					<label for="center">
						<input name="<?php echo $unique_id_for_input_names; ?>-align" type="radio" value="center" <?php checked( 'center', $values_column['align'], true );?>>
						<?php echo esc_html__( 'Center', 'bimber' )?>
					</label>
					<label for="right">
						<input name="<?php echo $unique_id_for_input_names; ?>-align" type="radio" value="right" <?php checked( 'right', $values_column['align'], true );?>>
						<?php echo esc_html__( 'Right', 'bimber' )?>
					</label>
			</div>
			<div class="g1-hb-layout-col-grow g1-hb-radios">
				<label><?php echo esc_html__( 'Grow:', 'bimber' )?></label>
					<label for="on">
						<input name="<?php echo $unique_id_for_input_names?>-grow" type="radio" value="on" <?php checked( 'on', $values_column['grow'], true );?>>
						<?php echo esc_html_x( 'On', 'on off', 'bimber' )?>
					</label>
					<label for="off">
						<input name="<?php echo $unique_id_for_input_names?>-grow" type="radio" value="off" <?php checked( 'off', $values_column['grow'], true );?>>
						<?php echo esc_html_x( 'Off', 'on off', 'bimber' )?>
					</label>
				</span>
			</div>
			<div class="g1-hb-settings-box-close-button"></div>
		</div>
	</div>
	<?php
}
