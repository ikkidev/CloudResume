<?php
/**
 * Custom Sidebars
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

add_action( 'admin_enqueue_scripts',            'bimber_sidebars_enqueue_styles' );
add_action( 'admin_enqueue_scripts',            'bimber_sidebars_enqueue_scripts' );
add_action( 'sidebar_admin_page',               'bimber_sidebars_render_form' );
add_action( 'load-widgets.php',                 'bimber_sidebars_save_sidebar', 100 );
add_action( 'wp_ajax_bimber_remove_sidebar',    'bimber_sidebars_ajax_remove' );

/**
 * Enqueue styles
 */
function bimber_sidebars_enqueue_styles( $hook ) {
	if ( 'widgets.php' === $hook ) {
		$version = bimber_get_theme_version();

		wp_enqueue_style( 'bimber-sidebars', BIMBER_ADMIN_DIR_URI . 'css/custom-sidebars.css', array(), $version, 'screen' );
	}
}

/**
 * Enqueue scripts
 *
 * @param string $hook      Page slug.
 */
function bimber_sidebars_enqueue_scripts( $hook ) {
	if ( 'widgets.php' === $hook ) {
		wp_enqueue_script( 'bimber-sidebars', BIMBER_ADMIN_DIR_URI . 'js/custom-sidebars.js', array( 'jquery' ), bimber_get_theme_version(), true );

		wp_localize_script( 'bimber-sidebars','bimber_sidebars_config', array(
			'i18n'=> array(
				'remove'            => esc_html__( 'Remove Sidebar', 'bimber' ),
				'confirm_removal'   => esc_html__( 'Are you sure you want to do this?', 'bimber' ),
				'removal_failed'    => esc_html__( 'Some errors occurred. Sidebar couldn\'t be removed.', 'bimber' ),
				'removing'          => esc_html__( 'Removing&hellip;', 'bimber' ),
				'sidebar_not_empty' => esc_html__( 'Sidebar is not empty. Plese remove all widgets first.', 'bimber' ),
			),
		) );
	}
}

/**
 * Render new sidebar form
 */
function bimber_sidebars_render_form() {
	global $bimber_add_sidebar_error;

	$nonce =  wp_create_nonce ('bimber-custom-sidebar-ajax-nonce');

	$final_class = array(
		'g1ui-sidegen'
	);

	if ( ! empty( $bimber_add_sidebar_error ) ) {
		$final_class[] = 'g1ui-sidegen-has-errors';
	}

	?>
	<div id="g1ui-sidegen" class="<?php echo implode(' ', array_map( 'sanitize_html_class', $final_class ) ); ?>">
		<form method="post">
			<input type="hidden" name="bimber-custom-sidebar-ajax-nonce" value="<?php echo esc_attr( $nonce ); ?>" />
			<h2><?php _e( 'Add a new sidebar', 'bimber' ); ?></h2>
			<?php
			if ( ! empty( $bimber_add_sidebar_error ) ) {
				echo '<p class="g1ui-sidegen-error">' . $bimber_add_sidebar_error . '</p>';
				unset( $bimber_add_sidebar_error );
			}
			?>
			<div class="g1ui-sidegen-inline">
				<input id="bimber-sidebar-text" type="text" name="bimber_new_sidebar" placeholder="<?php esc_html_e( 'Enter a unique name', 'bimber' ); ?>" />
				<input class="button button-primary" type="submit" value="<?php _e( 'Add', 'bimber' ); ?>" />
			</div>
		</form>
	</div>
	<?php
}

/**
 * Save a sidebar
 */
function bimber_sidebars_save_sidebar() {
	if ( !empty( $_POST['bimber_new_sidebar'] ) ) {
		$sidebar_name = sanitize_text_field( $_POST['bimber_new_sidebar'] );
		$sidebar_id = bimber_get_unique_sidebar_id( $sidebar_name );

		if ( $sidebar_id ) {
			$sidebars = get_option( 'bimber_user_sidebars', array() );

			$sidebars[ $sidebar_id ] = array(
				'label' => $sidebar_name,
			);

			update_option( 'bimber_user_sidebars', $sidebars );
			wp_redirect( admin_url('widgets.php') );
		} else {
			global $bimber_add_sidebar_error;
			$bimber_add_sidebar_error = sprintf( __( 'The "%s" sidebar already exists! Please use a different name.', 'bimber' ), $sidebar_name );
		}
	}
}

/**
 * Remove a sidebar
 */
function bimber_sidebars_ajax_remove() {
	check_ajax_referer( 'bimber-custom-sidebar-ajax-nonce', 'security' );

	$ajax_data = $_POST['ajax_data'];

	$sidebar_id = $ajax_data['sidebar_id'];

	$response = '';

	if ( !empty( $sidebar_id ) ) {
		$sidebars = get_option( 'bimber_user_sidebars', array() );

		if ( isset( $sidebars[ $sidebar_id ] ) ) {
			unset( $sidebars[ $sidebar_id ] );
			update_option( 'bimber_user_sidebars', $sidebars );
			$response = 'success';
		}
	}

	echo $response;
	exit;
}

/**
 * Return unique sidebar name
 *
 * @param string $sidebar_name  Sidebar name.
 *
 * @return mixed    Sidebar name or null if failed
 */
function bimber_get_unique_sidebar_id ( $sidebar_name ) {
	global $wp_registered_sidebars;

	$sidebar_id = preg_replace('/[^\d\w_]|_/i', '-', $sidebar_name);
	$sidebar_id = preg_replace('/-+/', '-', $sidebar_id);
	$sidebar_id = strtolower( $sidebar_id );

	if ( ! isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		return $sidebar_id;
	}

	return null;
}
