<?php
/**
 * Random post Metabox for menu
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

add_action( 'load-nav-menus.php', 'bimber_add_menu_endpoints_metabox' );

/**
 * Register metabox
 */
function bimber_add_menu_endpoints_metabox() {
	add_meta_box(
		'bimber_menu_endpoints',
		'Bimber',
		'bimber_menu_endpoints_metabox',
		'nav-menus',
		'side',
		'default'
	);

	do_action( 'bimber_register_menu_endpoints_metabox' );
}



/**
 * Render metabox
 */
function bimber_menu_endpoints_metabox() {
	$items = array();

	// Random post.
	$items[] = array(
		'url'       => '#',
		'label'     => esc_html__( 'Random post', 'bimber' ),
		'classes'   => 'bimber-random-post-nav',
	);

    // Random posts.
    $items[] = array(
        'url'       => '#',
        'label'     => esc_html__( 'Random posts', 'bimber' ),
        'classes'   => 'menu-item-type-g1-random-posts bimber-random-posts-page-nav',
    );

	// Latest posts page.
	$latest_page_url = bimber_get_latest_page_url();

	if ( ! empty( $latest_page_url ) ) {
		$items[] = array(
			'url'       => '#',
			'label'     => bimber_get_latest_page_label(),
			'classes'   => 'menu-item-type-g1-latest bimber-latest-page-nav',
		);
	}

	// Top X posts page.
	$top_page_url = bimber_get_top_page_url();

	if ( ! empty( $top_page_url ) ) {
		$items[] = array(
			'url'       => '#',
			'label'     => bimber_get_top_page_label(),
			'classes'   => 'menu-item-type-g1-top bimber-top-page-nav',
		);
	}

	// Popular posts page.
	$popular_page_url = bimber_get_popular_page_url();

	if ( ! empty( $popular_page_url ) ) {
		$items[] = array(
			'url'       => '#',
			'label'     => bimber_get_popular_page_label(),
			'classes'   => 'menu-item-type-g1-popular bimber-popular-page-nav',
		);
	}

	// Hot posts page.
	$hot_page_url = bimber_get_hot_page_url();

	if ( ! empty( $hot_page_url ) ) {
		$items[] = array(
			'url'       => '#',
			'label'     => bimber_get_hot_page_label(),
			'classes'   => 'menu-item-type-g1-hot bimber-hot-page-nav',
		);
	}

	// Trending posts page.
	$trending_page_url = bimber_get_trending_page_url();

	if ( ! empty( $trending_page_url ) ) {
		$items[] = array(
			'url'       => '#',
			'label'     => bimber_get_trending_page_label(),
			'classes'   => 'menu-item-type-g1-trending bimber-trending-page-nav',
		);
	}

	$items = apply_filters( 'bimber_menu_endpoints', $items );

	?>
	<div id="posttype-bimber" class="posttypediv">
		<div class="tabs-panel tabs-panel-active">
			<ul class="categorychecklist form-no-clear">
				<?php foreach ( $items as $item_index => $item_data ): ?>
				<li>
					<label class="menu-item-title">
						<input type="checkbox" class="menu-item-checkbox" name="menu-item[-<?php echo absint( $item_index + 1 ); ?>][menu-item-object-id]" value="-<?php echo absint( $item_index + 1 ); ?>"> <?php echo esc_html( $item_data['label'] ); ?>
					</label>
					<input type="hidden" class="menu-item-type" name="menu-item[-<?php echo absint( $item_index + 1 ); ?>][menu-item-type]" value="custom">
					<input type="hidden" class="menu-item-title" name="menu-item[-<?php echo absint( $item_index + 1 ); ?>][menu-item-title]" value="<?php echo esc_html( $item_data['label'] ); ?>">
					<input type="hidden" class="menu-item-url" name="menu-item[-<?php echo absint( $item_index + 1 ); ?>][menu-item-url]" value="<?php echo esc_url( $item_data['url'] ); ?>">
					<input type="hidden" class="menu-item-classes" name="menu-item[-<?php echo absint( $item_index + 1 ); ?>][menu-item-classes]" value="<?php echo esc_attr( $item_data['classes'] ); ?>">
				</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<!-- Actions -->
		<p class="button-controls wp-clearfix">
			<span class="add-to-menu">
				<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'bimber' ); ?>" name="add-post-type-menu-item" id="<?php echo esc_attr( 'submit-posttype-bimber' ); ?>" />
				<span class="spinner"></span>
			</span>
		</p>
	</div>
<?php
}
