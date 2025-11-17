<?php
/**
 * BuddyPress Navigation Walker Class
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

if ( class_exists( 'BP_Walker_Nav_Menu' ) ) {
	/**
	 * Create HTML list of BP nav items.
	 *
	 * @since 1.7.0
	 */
	class Bimber_BP_Walker_Nav_Menu extends BP_Walker_Nav_Menu {

		// Sub-menu buffer.
		public $sub_items = array();

		// Allow processing flag.
		private $process_sub  = false;

		/**
		 * Display the current <li> that we are on.
		 *
		 * @see Walker::start_el() for complete description of parameters.
		 *
		 * @since 1.7.0
		 *
		 * @param string $output Passed by reference. Used to append
		 *                       additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding. Optional,
		 *                       defaults to 0.
		 * @param array  $args   Optional. See {@link Walker::start_el()}.
		 * @param int    $id     Menu item ID. Optional.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			if ( 'classic' === $item->css_id || 'home' === $item->css_id ) {
				$depth = 1;
			}

			// If we're someway down the tree, indent the HTML with the appropriate number of tabs.
			$indent = $depth ? str_repeat( "\t", $depth ) : '';

			// Start processing sub-menu in next walker step.
			if ( in_array( 'current-menu-parent', $item->class ) ) {
				$this->process_sub = true;
			}

			/**
			 * Filters the classes to be added to the nav menu markup.
			 *
			 * @since 1.7.0
			 *
			 * @param array  $value Array of classes to be added.
			 * @param object $item  Menu item data object.
			 * @param array  $args  Array of arguments for the item.
			 */
			if ( is_array( $item->class ) ) {
				$item->class[] = 'g1-tab-item';
				if ( in_array( 'current-menu-parent', $item->class ) ) {
					$item->class[] = 'g1-tab-item-current';
				}
			}


			$class_names = join( ' ', apply_filters( 'bp_nav_menu_css_class', array_filter( $item->class ), $item, $args ) );
			$class_names = ! empty( $class_names ) ? ' class="' . esc_attr( $class_names ) . '"' : '';

			// Add HTML ID
			$id = sanitize_html_class( $item->css_id . '-personal-li' );  // Backpat with BP pre-1.7.

			/**
			 * Filters the value to be used for the nav menu ID attribute.
			 *
			 * @since 1.7.0
			 *
			 * @param string $id   ID attribute to be added to the menu item.
			 * @param object $item Menu item data object.
			 * @param array  $args Array of arguments for the item.
			 */
			$id = apply_filters( 'bp_nav_menu_item_id', $id, $item, $args );
			$id = ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

			// Don't render sub-nav li.
			if ( $depth > 0) {
				$output .= '';
			} else {
				$output .= $indent . '<li' . $id . $class_names . '>';
			}

			// Add href attribute.
			$attributes = ! empty( $item->link ) ? ' class="g1-tab" id="user-' . $item->css_id .'" href="' . esc_url( $item->link ) . '"' : '';

			// Construct the link.
			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';

			/**
			 * Filters the link text to be added to the item output.
			 *
			 * @since 1.7.0
			 *
			 * @param string $name  Item text to be applied.
			 * @param int    $value Post ID the title is for.
			 */
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->name, 0 ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			// Buffer sub-menu.
			if ( $depth > 0 ) {
				if ( $this->process_sub ) {
					$this->sub_items[] = '<li' . $id . $class_names . '>' . $item_output . '</li>';
				}
				if ( 0 !== $item->parent ) {
					global $bimber_bp_nav_sub_items_used_css_ids;
					global $bimber_bp_nav_sub_items;
					$sub_item = '<li' . $id . $class_names . '>' . $item_output . '</li>';

					// Duplicate detection.
					if ( in_array( $item->css_id, $bimber_bp_nav_sub_items_used_css_ids, true ) ) {
						$duplicate = true;
					}

					if ( ! isset( $duplicate ) ) {
						$bimber_bp_nav_sub_items[ $item->parent ][] = '<li' . $id . $class_names . '>' . $item_output . '</li>';
						$bimber_bp_nav_sub_items_used_css_ids[] = $item->css_id;
					}
				}

				// Don't add it to main walker output.
				$item_output = '';
			}
			// Stop processing.
			if ( ! in_array( 'current-menu-parent', $item->class ) && $depth == 0 ) {
				$this->process_sub = false;
			}

			// Don't render sub-menus other then current.
			if ( $depth > 0 && ! $this->process_sub ) {
				return;
			}

			/**
			 * Filters the final result for the menu item.
			 *
			 * @since 1.7.0
			 *
			 * @param string $item_output Constructed output for the menu item to append to output.
			 * @param object $item        Menu item data object.
			 * @param int    $depth       Depth of menu item. Used for padding.
			 * @param array  $args        Array of arguments for the item.
			 */
			$output .= apply_filters( 'bp_walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * Ends the element output, if needed.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_el()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param WP_Post  $item   Page data object. Not used.
		 * @param int      $depth  Depth of page. Not Used.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			// Don't render sub-nav li.
			if ( $depth > 0) {
				$output .= '';
			} else {
				$output .= "</li>{$n}";
			}
		}

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			// Don't render sub-nav ul.
			$output .= '';
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			// Don't render sub-nav ul.
			$output .= '';
		}
	}

}
