<?php
/**
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Bimber_Walker_Nav_Menu
 */
class Bimber_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Current menu item for which mega menu is applied.
	 *
	 * @var stdClass
	 */
	public $bimber_mega_menu;

	/**
	 * Starts the element output.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param WP_Post $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An object of wp_nav_menu() arguments.
	 * @param int $id Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		// Next menu item on the same level?
		if ( isset( $this->bimber_mega_menu->ID ) && (int) $item->menu_item_parent !== (int) $this->bimber_mega_menu->ID ) {
			// Reset custom setup.
			unset( $this->bimber_mega_menu );
			$args->after = $args->orig_after;
		}

		$enable_mega_menu = 'standard' === get_post_meta( $item->ID, '_menu_item_g1_mega_menu', true );


		if ( 0 === $depth ) {
			// Normalize before use.
			$item->classes = empty( $item->classes ) ? array() : (array) $item->classes;

			if ( $enable_mega_menu ) {
				$this->bimber_mega_menu = $item; // Set flag.

				// Store current value.
				$args->orig_after = $args->after;

				$item->classes[] = 'menu-item-g1-mega';

				// Is a category?
				if ( 'taxonomy' === $item->type && 'category' === $item->object ) {
					// Mega menu children?
					if ( isset( $this->bimber_mega_menu->ID ) && (int) $item->menu_item_parent === (int) $this->bimber_mega_menu->ID ) {
						return; // Don't render.
					}

					// We will render sub-menu, so we need proper CSS class.
					$item->classes[] = 'menu-item-has-children';

					$category_id = $item->object_id;

					$query_args = array(
						'posts_per_page'      => 4,
						'ignore_sticky_posts' => true,
						'cat'                 => $category_id,
					);

					$query = new WP_Query( $query_args );
					set_query_var( 'bimber_query', $query );

					ob_start();
					get_template_part( 'template-parts/menu/sub-menu-term' );
					// Override it to load our mega menu.
					$args->after = ob_get_clean();
				} else {
					// Not a category and has no children.
					if ( ! $this->has_children ) {
						// We will render sub-menu, so we need proper CSS class.
						$item->classes[] = 'menu-item-has-children';


						ob_start();
						set_query_var( 'bimber_more_url', $item->url );
						get_template_part( 'template-parts/menu/sub-menu-cats' );
						// Override it to load our mega menu.
						$args->after = ob_get_clean();
					}
				}
			} else {
				$item->classes[] = 'menu-item-g1-standard';
			}
		}

		parent::start_el( $output, $item, $depth, $args, $id );
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $this->bimber_mega_menu ) ) {
			// Category.
			if ( 'category' === $this->bimber_mega_menu->object ) {
				// Don't show sub-items, if any.
				$output .= '';
			} else {
				$output .= '<div class="sub-menu-wrapper">';
				$this->bimber_wrapper_opened = $depth;

				parent::start_lvl( $output, $depth, $args );

				// Reset custom setup.
				unset( $this->bimber_mega_menu );
				$args->after = $args->orig_after;
			}
		} else {
			parent::start_lvl( $output, $depth, $args );
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( ! isset( $this->bimber_mega_menu ) ) {
			parent::end_lvl( $output, $depth, $args );

			if ( isset( $this->bimber_wrapper_opened ) && $depth === $this->bimber_wrapper_opened ) {
				$output .= '</div>';

				unset( $this->bimber_wrapper_opened );
			}
		}
	}
}
