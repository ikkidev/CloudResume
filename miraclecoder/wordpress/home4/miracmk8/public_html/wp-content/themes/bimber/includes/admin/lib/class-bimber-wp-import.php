<?php
/**
 * Class for importing/exporting theme data (options, widgets)
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

if ( ! class_exists( 'Bimber_WP_Import' ) ) {
	/**
	 * Class Bimber_Import_Export
	 */
	class Bimber_WP_Import extends WP_Import {

		function process_categories() {
			$this->categories = apply_filters( 'wp_import_categories', $this->categories );

			if ( empty( $this->categories ) )
				return;

			foreach ( $this->categories as $cat ) {
				// if the category already exists leave it alone
				$term_id = term_exists( $cat['category_nicename'], 'category' );
				if ( $term_id ) {
					if ( is_array($term_id) ) $term_id = $term_id['term_id'];
					if ( isset($cat['term_id']) )
						$this->processed_terms[intval($cat['term_id'])] = (int) $term_id;
					continue;
				}

				$category_parent = empty( $cat['category_parent'] ) ? 0 : category_exists( $cat['category_parent'] );
				$category_description = isset( $cat['category_description'] ) ? $cat['category_description'] : '';
				$catarr = array(
					'category_nicename' => $cat['category_nicename'],
					'category_parent' => $category_parent,
					'cat_name' => $cat['cat_name'],
					'category_description' => $category_description
				);
				$catarr = wp_slash( $catarr );

				$id = wp_insert_category( $catarr );
				if ( ! is_wp_error( $id ) ) {
					if ( isset($cat['term_id']) )
						$this->processed_terms[intval($cat['term_id'])] = $id;
				} else {
					printf( __( 'Failed to import category %s', 'wordpress-importer' ), esc_html($cat['category_nicename']) );
					if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
						echo ': ' . $id->get_error_message();
					echo '<br />';
					continue;
				}

				// $id is not an array.
				//$this->process_termmeta( $cat, $id['term_id'] );

				// $id from wp_insert_category() is int.
				$this->process_termmeta( $cat, $id );
			}

			unset( $this->categories );
		}
	}
}
