<?php
/**
 * Functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

add_action( 'media_buttons',                            'adace_stp_add_media_button' );
add_action( 'admin_footer',                             'adace_stp_add_modal_content' );
add_action( 'admin_enqueue_scripts',                    'adace_stp_enqueue_assets' );
add_action( 'wp_ajax_adace_stp_shortcode_preview',   'adace_stp_ajax_shortcode_preview' );
add_action( 'wp_ajax_adace_stp_load_collection',     'adace_stp_ajax_load_collection' );
add_filter( 'mce_external_plugins',                     'adace_stp_tinymce_shortcode_plugin' );

function adace_stp_add_media_button( $editor_id ) {
	$screen = get_current_screen();

	if ( in_array( $screen->base, array( 'post' ), true ) ) {
		$title = __( 'Shop the post', 'adace' );

		?>
		<button type="button" class="button adace-stp-modal-button" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php echo esc_attr( $title ); ?>">
			<span class="wp-media-buttons-icon"></span><?php echo esc_html( $title ); ?>
		</button>
		<?php
	}
}

function adace_stp_add_modal_content() {
	$screen = get_current_screen();

	$supported_screens = apply_filters( 'adace_stp_add_modal_content_supported_screens', array( 'post' ) );

	if ( in_array( $screen->base, $supported_screens, true ) ) {
		require_once( trailingslashit( dirname( __FILE__ ) ) . 'templates/modal.php' );
	}
}

function adace_stp_enqueue_assets() {
	$ver = adace_get_plugin_version();

	wp_enqueue_style( 'adace-stp-modal', trailingslashit( adace_get_plugin_url() ) . 'includes/shop-the-post/assets/css/modal.css', array(), $ver );

	wp_enqueue_script( 'adace-stp-modal', trailingslashit( adace_get_plugin_url() ) . 'includes/shop-the-post/assets/js/modal.js', array( 'jquery' ), $ver, true );

	wp_localize_script( 'adace-stp-modal', 'adace_stp_modal', array(
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'adace_stp' ),
		'l10n' => array(
			'remove_shortcode'  => __( 'Are you sure?', 'adace' ),
			'loading_preview'   => __( 'Loading collection preview...', 'adace' ),
		),
	));
}

/**
 * Load Shop The Post TinyMCE plugin
 *
 * @param array $plugins            List of registered plugins.
 *
 * @return array
 */
function adace_stp_tinymce_shortcode_plugin( $plugins ) {
	$plugins['adace_shop_the_post'] = trailingslashit( adace_get_plugin_url() ) . 'includes/shop-the-post/assets/js/shortcode-tinymce.js';

	return $plugins;
}

function adace_stp_ajax_shortcode_preview() {
	if ( check_ajax_referer( 'adace_stp', 'security', false ) ) {
		$ids_str = filter_input( INPUT_POST, 'ids', FILTER_SANITIZE_STRING );
		$ids_arr = array();

		if ( ! empty( $ids_str ) ) {
			$ids_arr = explode( ',', $ids_str );
		}

		include( trailingslashit( dirname( __FILE__ ) ) . 'templates/shortcode-preview.php' );
	}

	exit;
}

function adace_stp_ajax_load_collection() {
	if ( check_ajax_referer( 'adace_stp', 'security', false ) ) {
		$id = filter_input( INPUT_GET, 'collection', FILTER_SANITIZE_STRING );

		include( trailingslashit( dirname( __FILE__ ) ) . 'templates/' . $id . '-collection.php' );
	}

	exit;
}

function adace_stp_get_wc_categories() {
	$args = array(
		'taxonomy'     => 'product_cat',
		'orderby'      => 'name',
		'show_count'   => 0,
		'pad_counts'   => 0,
		'hierarchical' => 1,
		'title_li'     => '',
		'hide_empty'   => 0
	);

	$categories = get_categories( $args );

	$choices = array();

	foreach ( $categories as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	return $choices;
}

function adace_stp_get_wc_product_term_ids( $post_id ) {
	$ids = array();

	$terms = get_the_terms( $post_id, 'product_cat' );

	foreach ( $terms as $term ) {
		$ids[] = $term->term_id;
	}

	return $ids;
}
