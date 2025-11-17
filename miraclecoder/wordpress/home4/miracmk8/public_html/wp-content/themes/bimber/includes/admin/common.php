<?php
/**
 * Admin common functions
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

/**
 * Enqueue admin CSS
 */
function bimber_admin_enqueue_styles( $hook ) {
	$version = bimber_get_theme_version();

	if ( in_array( $hook, array( 'term.php', 'post.php', 'settings_page_adace_options' ), true ) ) {
		wp_enqueue_style( 'wp-color-picker' );
	}

	// Register.
	wp_enqueue_style( 'bimber-admin', BIMBER_ADMIN_DIR_URI . 'css/admin.css', array(), $version, 'screen' );
}

/**
 * Enqueue admin JS
 */
function bimber_admin_enqueue_scripts( $hook ) {
	$child_theme_uri = trailingslashit( get_stylesheet_directory_uri() );
	if ( in_array( $hook, array( 'term.php', 'post.php', 'settings_page_adace_options' ), true ) ) {
		wp_enqueue_media();
		wp_enqueue_script( 'bimber-category', BIMBER_ADMIN_DIR_URI . 'js/term.js', array( 'wp-color-picker' ), false, true );
	}

	if ( BIMBER_THEME_DIR_URI !== $child_theme_uri ) {
		wp_enqueue_script( 'bimber-modifications-admin', $child_theme_uri . 'modifications-admin.js', array( 'jquery' ), false, true );
	}
}

/**
 * Add editor styles
 */
function bimber_add_editor_styles() {
	add_editor_style( 'css/editor-style.css' );
}

/**
 * Add the "Id" column to post list table in admin area
 *
 * @param array $columns        List of current columns.
 *
 * @return array                Modified column list.
 */
function bimber_post_list_add_id_column( $columns ) {
	$new_columns = array();

	foreach ( $columns as $k => $v ) {
		$new_columns[ $k ] = $v;
		if ( 'cb' === $k ) {
			$new_columns['id'] = 'ID';
		}
	}

	return $new_columns;
}

/**
 * Render the "Id" column on post list table in admin area
 *
 * @param string $name      Column name.
 */
function bimber_post_list_render_id_column( $name ) {
	global $post;

	if ( 'id' === $name ) {
		echo intval( $post->ID );
	}
}

/**
 * Register custom column headers
 *
 * @param array $columns    List of columns.
 *
 * @return mixed            Modified colum list.
 */
function bimber_post_list_custom_columns( $columns ) {
	$columns['featured_image'] = esc_html__( 'Featured Image', 'bimber' );

	return $columns;
}

/**
 * Render custom column value
 *
 * @param string $column         Column name.
 */
function bimber_post_list_custom_columns_data( $column ) {
	if ( 'featured_image' === $column ) {
		the_post_thumbnail( 'thumbnail' );
	}
}

/**
 * Check whether we are in autosave state
 *
 * @return bool
 */
function bimber_is_doing_autosave() {
	return defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ? true : false;
}

/**
 * Check whether inline edit was requested
 *
 * @return bool
 */
function bimber_is_inline_edit() {
	return isset( $_REQUEST['_inline_edit'] ) ? true : false;  // Input var okey.
}

/**
 * Check whether we are in preview state
 *
 * @return bool
 */
function bimber_is_doing_preview() {
	return ! empty( $_REQUEST['wp-preview'] ); // Input var okey.
}

/**
 * Register the About page
 */
function bimber_register_about_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! bimber_about_page_exists() ) {
		return;
	}

	// About.
//	add_dashboard_page(
//		__( 'Welcome to Bimber',  'bimber' ),
//		__( 'Welcome to Bimber',  'bimber' ),
//		'manage_options',
//		'bimber-about',
//		'render_bimber_about_page'
//	);
}

/**
 * Render the About page
 */
function render_bimber_about_page() {
	$version = bimber_get_theme_version();

	get_template_part( 'includes/admin/about/version-' . $version );
}

/**
 * Checks whether current theme version page exists.
 *
 * @param string $version		Version to check. Optional.
 *
 * @return bool
 */
function bimber_about_page_exists( $version = '' ) {
	if ( empty( $version ) ) {
		$version = bimber_get_theme_version();
	}

	return file_exists( BIMBER_ADMIN_DIR . 'about/version-' . $version . '.php' );
}

/**
 * Allow editing the "js" file type in child theme editor
 *
 * @param array    $allowed_types		List of current types.
 * @param WP_Theme $theme				Theme object.
 *
 * @return array
 */
function bimber_allow_editing_child_theme_js_files( $allowed_types, $theme ) {
	// Not for main theme.
	if ( bimber_get_theme_name() !== strtolower( $theme->Name ) ) {
		$allowed_types[] = 'js';
	}

	return $allowed_types;
}

/**
 * Fires just before the move buttons of a nav menu item in the menu editor.
 *
 * @since 5.4.0
 *
 * @param int      $item_id Menu item ID.
 * @param WP_Post  $item    Menu item data object.
 * @param int      $depth   Depth of menu item. Used for padding.
 */
function bimber_add_menu_item_custom_fields( $item_id, $item, $depth ) {
    if ( $depth > 0 ) {
        return;
    }
    ?>
    <p class="description description-wide">
        <label for="edit-menu-item-g1-mega-menu-<?php echo $item_id; ?>">
            <input type="checkbox" id="edit-menu-item-g1-mega-menu-<?php echo $item_id; ?>" class="widefat edit-menu-item-g1-mega-menu" name="menu-item-g1-mega-menu[<?php echo $item_id; ?>]" value="standard"<?php checked( $item->g1_mega_menu, 'standard' ); ?> />
            <?php _e( 'Turn into Mega Menu', 'bimber' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Save menu custom fields
 *
 * @param int   $menu_id                Menu id.
 * @param int   $menu_item_db_id        Menu database id.
 * @param array $args                   Menu args.
 */
function bimber_wp_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
	if ( isset( $_REQUEST['menu-item-g1-mega-menu'] ) && is_array( $_REQUEST['menu-item-g1-mega-menu'] ) ) {
		if ( isset( $_REQUEST['menu-item-g1-mega-menu'][ $menu_item_db_id ] ) ) {
			$value = $_REQUEST['menu-item-g1-mega-menu'][ $menu_item_db_id ];

			update_post_meta( $menu_item_db_id, '_menu_item_g1_mega_menu', $value );
		// Unchecked checkbox doesn't send any value.
		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_g1_mega_menu' );
		}
	// Unchecked checkbox doesn't send any value. If there are no other selections, won't be an array too.
	} else {
		delete_post_meta( $menu_item_db_id, '_menu_item_g1_mega_menu' );
	}
}

/**
 * Add custom fields to $item nav object
 * in order to be used in custom Walker
 *
 * @param stdClass $menu_item           Menu item object.
 *
 * @return stdClass
 */
function bimber_wp_setup_nav_menu_item( $menu_item ) {
	$menu_item->g1_mega_menu = get_post_meta( $menu_item->ID, '_menu_item_g1_mega_menu', true );

	return $menu_item;
}

/**
 * Renders an image radio control
 *
 * @param array $args       Config.
 */
function bimber_ui_render_image_radio( $args ) {
	$defaults = array(
		'options'       => array(),
		'width'         => 75,
		'height'        => 45,
		'value'         => '',
		'html_name'     => 'image_radio',
		'html_id'       => '',
		'html_class'    => '',
		'img_base_url'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$final_class = array_merge( array( 'g1ui-img-radios' ), explode( ' ', $args['html_class'] ) );
	?>
	<ul id="<?php echo esc_attr( $args['html_id'] ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $final_class ) ); ?>">
		<?php foreach ( $args['options'] as $option_value => $option_args ): ?>
			<?php
			$radio_id = $args['html_name'] . '_' . $option_value;
			?>
			<li class="g1ui-img-radio">
				<input type="radio" id="<?php echo esc_attr( $radio_id ); ?>" name="<?php echo esc_attr( $args['html_name'] ); ?>" value="<?php echo esc_attr( $option_value ); ?>"<?php checked( $option_value, $args['value'] ); ?> />
				<label for="<?php echo esc_attr( $radio_id ); ?>">
					<img class="g1-image-radio-choice" width="<?php echo absint( $args['width'] ); ?>" height="<?php echo absint( $args['height'] ); ?>" src="<?php echo esc_url( $option_args['path'] ); ?>" alt="<?php echo esc_attr( $option_args['label'] ); ?>" />
					<span><span><?php echo esc_html( $option_args['label'] ); ?></span></span>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Add styles to stop columns from overflowing
 */
function bimber_post_list_styles() {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}
	if ( 'edit-post' === $screen->id && 'post' === $screen->post_type ) :
	?>
		<style>
		#tags, .tags,
		#date, .date,
		#mashsb_shares, .mashsb_shares{
			width:10%;
		}
		#snax_format,.snax_format{
			width:8%;
		}
		#categories, .categories{
			width:10%;
		}

		.featured_image img{
			max-width:100%;
			height:auto;
		}

		#id, .id{
			width:5%;
		}
		#mashsb_shares, .mashsb_shares{
			width:5%;
		}
		#date,.date{
			width:8%;
		}

		</style>
	<?php
	endif;
}

add_action( 'page_attributes_misc_attributes', 'bimber_add_sidebar_location_to_page_attributes_meta_box', 10, 1 );

/**
 * Add sidebar location select to the page attributes meta box.
 *
 * @param WP_Post $post The current post.
 */
function bimber_add_sidebar_location_to_page_attributes_meta_box( $post ) {
	$value = get_post_meta( $post->ID, '_bimber_single_page_options', true );
	if ( ! is_array( $value) ) {
		$value = array();
	}
	if ( ! isset( $value['sidebar_location'] ) ) {
		$value['sidebar_location'] = '';
	}
	?>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="bimber_sidebar_location"><?php  esc_html_e( 'Sidebar Location', 'bimber' ); ?></label></p>
	<select name="bimber_sidebar_location" id="bimber_sidebar_location">
	<option
		value=""<?php selected( $value['sidebar_location'], '' ); ?>><?php esc_html_e( 'inherit', 'bimber' ); ?></option>
	<option
		value="left"<?php selected( $value['sidebar_location'], 'left' ); ?>><?php echo esc_html_x( 'Left', 'sidebar location', 'bimber' ); ?></option>
	<option
		value="standard"<?php selected( $value['sidebar_location'], 'standard' ); ?>><?php echo esc_html_x( 'Right', 'sidebar location', 'bimber' ); ?></option>
	</select>
	<?php wp_nonce_field( 'bimber_page_sidebar_location', 'bimber_page_sidebar_location' ); ?>
<?php
}

add_action( 'save_post', 'bimber_save_sidebar_location_for_page' );

/**
 * Save sidebar location for page.
 *
 * @param int $post_id Post id.
 */
function bimber_save_sidebar_location_for_page( $post_id ) {
	if ( 'page' !== get_post_type( $post_id ) ) {
		return;
	}
	$nonce = false;
	if ( isset( $_REQUEST['bimber_page_sidebar_location'] ) ) {
		$nonce = $_REQUEST['bimber_page_sidebar_location'];
	}
	if ( ! wp_verify_nonce( $nonce, 'bimber_page_sidebar_location' ) ) {
		return;
	}
	$value = get_post_meta( $post_id, '_bimber_single_page_options', true );
	if ( ! is_array( $value) ) {
		$value = array();
	}
	$value['sidebar_location'] = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_sidebar_location' ) );
	update_post_meta( $post_id, '_bimber_single_page_options', $value );
}

/**
 * Listed on General settings page update and update absolute paths if protocol has changed
 */
function bimber_update_customizer_absolute_paths() {
	$updated = bimber_htmlspecialchars( filter_input( INPUT_GET, 'settings-updated' ) );

	// Run only on update settings action.
	if ( $updated ) {
		$home_url          = home_url();
		$home_url_parts    = parse_url( $home_url );
		$customizer_images = bimber_get_theme_image_options();

		foreach( $customizer_images as $customizer_image ) {
			$option_parts = explode( '_', $customizer_image, 2 );

			$option_base = $option_parts[0];
			$option_key  = $option_parts[1];

			$image_url = bimber_get_theme_option( $option_base, $option_key );

			// Image set.
			if ( ! empty( $image_url ) ) {
				$image_url_parts = parse_url( $image_url );

				// Update image schema (http or https) if it differs from home scheme.
				if ( $image_url_parts['scheme'] !== $home_url_parts['scheme'] ) {
					$image_url = str_replace( $image_url_parts['scheme'], $home_url_parts['scheme'], $image_url );

					bimber_set_theme_option( $option_base, $option_key, $image_url );
				}
			}
		}
	}
}

function bimber_show_menu_metaboxes( $result ) {
	// Remove Snax and Bimber from hidden metaboxes array.
	$result = array_diff( $result, array( 'snax_menu_endpoints', 'bimber_menu_endpoints' ) );

	return $result;
}

/**
 * Add a post display state for special theme pages in the page list table
 *
 * @param array   $post_states  An array of post display states.
 * @param WP_Post $post         The current post object.
 *
 * @return array
 */
function bimber_add_display_post_states( $post_states, $post ) {
    // Link landing page.
    if ( 'landing_page' === bimber_get_theme_option( 'post_link', 'open_method' ) && $post->ID === (int) bimber_get_theme_option( 'post', 'link_landing_page' ) ) {
        $post_states['bimber_link_landing_page'] = _x( 'Bimber, Link Landing Page', 'Admin page label', 'bimber' );
    }

    // Top 10.
    if ( $post->ID === bimber_get_top_page_id() ) {
        $post_states['bimber_top_posts_page'] = _x( 'Bimber, Top Posts Page', 'Admin page label', 'bimber' );
    }

    // Trending page.
    if ( $post->ID === bimber_get_trending_page_id() ) {
        $post_states['bimber_trending_posts_page'] = _x( 'Bimber, Trending Posts Page', 'Admin page label', 'bimber' );
    }

    // Hot page.
    if ( $post->ID === bimber_get_hot_page_id() ) {
        $post_states['bimber_hot_posts_page'] = _x( 'Bimber, Hot Posts Page', 'Admin page label', 'bimber' );
    }

    // Popular page.
    if ( $post->ID === bimber_get_popular_page_id() ) {
        $post_states['bimber_popular_posts_page'] = _x( 'Bimber, Popular Posts Page', 'Admin page label', 'bimber' );
    }

    return $post_states;
}

function bimber_redirect_to_ad_settings() {
    $type = bimber_htmlspecialchars( filter_input( INPUT_GET, 'type' ) );

    if ( empty( $type ) ) {
        die( __( 'Cheatin&#8217; uh?', 'bimber' ) );
    }

    $slot = '';

    switch ( $type ) {
        case 'home-injected':
            $home_tpl = bimber_get_theme_option( 'home', 'template' );
            $slot = bimber_get_ad_slot_by_template( $home_tpl );
            break;

        case 'archive-injected':
            $archive_tpl = bimber_get_theme_option( 'archive', 'template' );
            $slot = bimber_get_ad_slot_by_template( $archive_tpl );
            break;
    }

    $url = apply_filters( 'bimber_ad_settings_url', '', $slot );

    if ( ! empty( $url ) ) {
        wp_safe_redirect( $url );
    } else {
        _e( 'You have to activate the AdAce plugin to utilize that ad spot.', 'bimber' );
        exit;
    }
}
