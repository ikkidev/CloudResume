<?php
/**
 * Options Page
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Get AdAce Options slug. Its used in few places, this makes it easy to get/change.
 *
 * @return string Options page slug.
 */
function adace_options_page_slug() {
	return 'adace_options';
}

add_action( 'admin_menu', 'adace_add_options_page' );
/**
 * Add options page.
 */
function adace_add_options_page() {
	add_submenu_page(
		'options-general.php',
		esc_html__( 'AdAce', 'adace' ), // Page title.
		esc_html__( 'AdAce', 'adace' ), // Menu title.
		'manage_options', // Capability.
		adace_options_page_slug(), // Slug.
		'adace_options_page_renderer_callback' // Page renderer callback.
	);
}

/**
 * Options page renderer.
 */
function adace_options_page_renderer_callback() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'adace' ) );
	}
	// Declare tabs. In array for future.
	$tabs = array(
		'adace_slots'   => array(
			'path'     => add_query_arg( array(
				'page' => adace_options_page_slug(),
				'tab'  => 'adace_slots',
			), '' ),
			'label'    => esc_html__( 'Ad Slots', 'adace' ),
			'settings' => 'adace_slots_options',
		),
        'adace_ad_free'   => array(
            'path'     => add_query_arg( array(
                'page' => adace_options_page_slug(),
                'tab'  => 'adace_ad_free',
            ), '' ),
            'label'    => esc_html__( 'Ad Free', 'adace' ),
            'settings' => 'adace_ad_free_options',
        ),
		'adace_general' => array(
			'path'     => add_query_arg( array(
				'page' => adace_options_page_slug(),
				'tab'  => 'adace_general',
			), '' ),
			'label'    => esc_html__( 'General', 'adace' ),
			'settings' => 'adace_general_options',
		),
	);
	$tabs = apply_filters( 'adace_options_tabs', $tabs );
	// Get active tab, check if any is selected.
	$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
	if ( null === $current_tab ) {
		$current_tab = key( $tabs );
	}
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'AdAce Options', 'adace' ); ?></h2>
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $tab_key => $tab ) : ?>
				<a href="<?php echo( esc_attr( $tab['path'] ) ); ?>" class="nav-tab <?php echo( sanitize_html_class( $current_tab === $tab_key ? 'nav-tab-active' : '' ) ); ?>">
					<?php echo( esc_html( $tab['label'] ) ); ?>
				</a>
			<?php endforeach; ?>
		</h2>
		<form id="<?php echo( sanitize_html_class( $current_tab ) ); ?>-form" method="post" action="options.php">
			<?php
			settings_fields( $current_tab );
			if ( 'adace_slots' === $current_tab ) {
				adace_do_slots_settings_sections( $current_tab );
			} else {
				do_settings_sections( $current_tab );
			}
            do_action( 'adace_options_form_before_submit', $current_tab );
			submit_button();
			?>
		</form>
	<?php
}
