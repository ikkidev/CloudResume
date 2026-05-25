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
 * Class bimber_breadcrumbs
 */
class Bimber_Breadcrumbs {
	/**
	 * Items separator
	 *
	 * @var string
	 */
	private $separator;

	/**
	 * Items
	 *
	 * @var array
	 */
	private $breadcrumbs;

	/**
	 * bimber_breadcrumbs constructor.
	 */
	public function __construct() {
		$this->set_separator( ' &rsaquo; ' );
	}

	/**
	 * Set items separator
	 *
	 * @param string $val		Separator.
	 */
	public function set_separator( $val ) {
		$this->separator = $val;
	}

	/**
	 * Return items separator
	 *
	 * @return string
	 */
	public function get_separator() {
		return $this->separator;
	}

	/**
	 * Gets breadcrumbs for the current context.
	 *
	 * If you want to add/delete some choices, hook into the g1_breadcrumbs custom filter.
	 *
	 * @return array
	 */
	public function get() {
		global $post;

		$this->breadcrumbs = array();
		$this->breadcrumbs[] = array(
			'href' => home_url( '/' ),
			'text' => __( 'Home', 'bimber' ),
		);

		// Blog Page.
		if ( is_home() && ! is_front_page() ) {
			$id = intval( get_option( 'page_for_posts' ) );

			$id = apply_filters( 'bimber_breadcrumb_page_id', $id );

			if ( $id ) {
				$href = get_permalink( $id );
				$text = get_the_title( $id );

				$this->breadcrumbs[] = array(
					'href' => $href,
					'text' => $text,
				);
			}
		} elseif ( is_singular() ) {
			if ( ! is_page() ) {
				if ( 'post' === get_post_type() ) {
					$id = intval( get_option( 'page_for_posts' ) );

					$id = apply_filters( 'bimber_breadcrumb_page_id', $id );

					if ( $id ) {
						$href = get_permalink( $id );
						$text = get_the_title( $id );

						$this->breadcrumbs[] = array(
							'href' => $href,
							'text' => $text,
						);
					}
				} elseif ( ! is_attachment() ) {
					$post_type = get_post_type();
					// bbPress fix.
					$post_type = 'topic' === $post_type ? 'forum' : $post_type;

					$post_type_obj = get_post_type_object( $post_type );

					if ( $post_type_obj ) {
						$href = get_post_type_archive_link( $post_type );
						$text = apply_filters( 'post_type_archive_title', $post_type_obj->labels->name, $post_type );

						$this->breadcrumbs[] = array(
							'href' => $href,
							'text' => $text,
						);
					}
				}
			}

			// Add sub pages if any.
			if ( $post->post_parent ) {
				$parent_id = $post->post_parent;
				$parent_breadcrumbs = array();

				while ( $parent_id ) {
					$page = get_post( $parent_id );
					$parent_breadcrumbs[] = array(
						'href' => get_permalink( $page->ID ),
						'text' => get_the_title( $page->ID ),
					);

					$parent_id = $page->post_parent;
				}

				$parent_breadcrumbs = array_reverse( $parent_breadcrumbs );

				$this->breadcrumbs = array_merge( $this->breadcrumbs, $parent_breadcrumbs );
			}

			// Add categories if any.
			$cat_items = $this->get_category_items( $post );

			if ( ! empty( $cat_items ) ) {
				$this->breadcrumbs = array_merge( $this->breadcrumbs, $cat_items );
			}

			// Add the current page.
			$this->breadcrumbs[] = array(
				'href' => get_permalink( $post->ID ),
				'text' => get_the_title( $post->ID ),
			);
		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type();
			$href = get_post_type_archive_link( $post_type );
			$text = post_type_archive_title( '', false );

			$this->breadcrumbs[] = array(
				'href' => $href,
				'text' => $text,
			);
		} elseif ( is_category() ) {
			$category_id = get_query_var( 'cat' );
			$category = get_category( $category_id );

			// Temporary array for the current category and parents (if any).
			$temp = array();

			while ( $category_id ) {
				$temp[] = array(
					'href' => get_category_link( $category_id ),
					'text' => get_cat_name( $category_id ),
				);

				// Check for a parent category.
				if ( $category->category_parent ) {
					$category_id = $category->category_parent;
					$category = get_category( $category_id );
				} else {
					$category_id = 0;
				}
			}

			if ( count( $temp ) ) {
				$temp = array_reverse( $temp );
			}

			// Merge with temporary array.
			$this->breadcrumbs = array_merge( $this->breadcrumbs, $temp );
		} elseif ( is_tag() ) {
			$this->add_tag_breadcrumbs();
		} elseif ( is_tax() ) {
			$this->add_tax_breadcrumbs();
		} elseif ( is_year() ) {
			$this->add_year_breadcrumbs();
		} elseif ( is_month() ) {
			$this->add_month_breadcrumbs();
		} elseif ( is_day() ) {
			$this->add_day_breadcrumbs();
		} elseif ( is_author() ) {
			$this->add_author_breadcrumbs();
		} elseif ( is_search() ) {
			$this->add_search_breadcrumbs();
		} elseif ( is_404() ) {
			$this->add_404_breadcrumbs();
		}

		// Call the functions added to a filter hook.
		$this->breadcrumbs = apply_filters( 'g1_breadcrumbs', $this->breadcrumbs );

		$this->remove_duplicates();

		return $this->breadcrumbs;
	}

	/**
	 * Return items from category
	 *
	 * @param WP_Post $post		Post.
	 *
	 * @return array
	 */
	protected function get_category_items( $post ) {
		$tax_objects = get_object_taxonomies( $post, 'objects' );
		$category_tax_name = null;

		foreach ( $tax_objects as $tax_name => $tax_object ) {
			if ( $tax_object->query_var || $tax_object->hierarchical ) {
				$category_tax_name = $tax_name;
				break;
			}
		}

		if ( $category_tax_name ) {
			// Parent categories are added first so have lower term_id.
			$post_cat_ids = wp_get_object_terms( $post->ID, $category_tax_name, array(
				'orderby'   => 'term_id',
				'order'     => 'ASC',
			) );

			// List of all post categories.
			$post_cat_id_list = wp_list_pluck( $post_cat_ids, 'term_id' );

			if ( ! is_wp_error( $post_cat_ids ) && ! empty( $post_cat_ids ) ) {
				$post_cat    = array_pop( $post_cat_ids );
				$post_cat_id = $post_cat->term_id;

				// Omit the "Uncategorized" category.
				if ( 1 !== $post_cat_id ) {
					$post_cat = get_term( $post_cat_id, $category_tax_name );

					$breadcrumb_items = array();

					$breadcrumb_items[] = array(
						'href' => is_wp_error( get_term_link( $post_cat->term_id, $category_tax_name ) ) ? '#' : get_term_link( $post_cat->term_id, $category_tax_name ),
						'text' => $post_cat->name,
					);

					while ( $post_cat->parent ) {
						$post_cat = get_term( $post_cat->parent, $category_tax_name );

						// Add to the breadcrumb only if the post is assigned to the parent category.
						if ( in_array( $post_cat->term_id, $post_cat_id_list ) ) {
							$breadcrumb_items[] = array(
								'href' => is_wp_error( get_term_link( $post_cat->term_id, $category_tax_name ) ) ? '#' : get_term_link( $post_cat->term_id, $category_tax_name ),
								'text' => $post_cat->name,
							);
						}
					}

					return array_reverse( $breadcrumb_items );
				}
			}
		}

		return array();
	}

	/**
	 * Removes duplicated items
	 */
	protected function remove_duplicates() {
		$uniques = array();

		foreach ( $this->breadcrumbs as $k => $v ) {
			if ( in_array( $v['href'], $uniques, true ) ) {
				unset( $this->breadcrumbs[ $k ] );
			} else {
				$uniques[] = $v['href'];
			}
		}

		// Re-index array.
		$this->breadcrumbs = array_values( $this->breadcrumbs );
	}

	/**
	 * Tag archive
	 */
	public function add_tag_breadcrumbs() {
		$term_id = get_queried_object()->term_id;


		$this->add_breadcrumb(
			get_term_link( $term_id, 'post_tag' ),
			sprintf( __( 'Tag Archives: %s', 'bimber' ), single_term_title( '', false )	)
		);
	}

	/**
	 * Tax archive
	 */
	public function add_tax_breadcrumbs() {
		$this->add_breadcrumb( '', single_term_title( '', false ) );
	}

	/**
	 * Year
	 */
	public function add_year_breadcrumbs() {
		$this->add_breadcrumb( '', get_the_time( 'Y' ) );
	}

	/**
	 * Month
	 */
	public function add_month_breadcrumbs() {
		$this->add_breadcrumb( get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) );
		$this->add_breadcrumb( '', get_the_time( 'F' ) );
	}

	/**
	 * Day
	 */
	public function add_day_breadcrumbs() {
		$this->add_breadcrumb( get_year_link( get_the_time( 'Y' ) ),  get_the_time( 'Y' ) );

		$this->add_breadcrumb( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) );

		$this->add_breadcrumb( '', get_the_time( 'd' ) );
	}

	/**
	 * Author
	 */
	public function add_author_breadcrumbs() {
		$curauth = null;

		if ( get_query_var( 'author_name' ) ) {
			$curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
		}

		if ( get_query_var( 'author' ) ) {
			$curauth = get_user_by( 'id', get_query_var( 'author' ) );
		}

		$this->add_breadcrumb( '',  sprintf( __( 'Author Archives: %s', 'bimber' ), $curauth->display_name ) );
	}

	/**
	 * Search
	 */
	public function add_search_breadcrumbs() {
		$this->add_breadcrumb( '', __( 'Search results', 'bimber' ) );
	}

	/**
	 * 404
	 */
	public function add_404_breadcrumbs() {
		$this->add_breadcrumb( '', __( '404 - page not found', 'bimber' ) );
	}

	/**
	 * Add new item to the list
	 *
	 * @param string $href		Url.
	 * @param string $text		Label.
	 */
	public function add_breadcrumb( $href, $text ) {
		$this->breadcrumbs[] = array(
			'href' => $href,
			'text' => $text,
		);
	}

	/**
	 * Captures breadcrumbs navigation markup
	 *
	 * @return string
	 */
	public function capture() {
		$breadcrumbs = $this->get();

		// Compose output.
		$out = '';
		$classes = array(
			'g1-breadcrumbs',
		);

		// Ellipsis.
		$ellipsis = (bool) bimber_get_theme_option( 'breadcrumbs', 'ellipsis' );
		if ( apply_filters( 'bimber_breadcrumb_ellipsis', $ellipsis ) ) {
			$classes[] = 'g1-breadcrumbs-with-ellipsis';
		}

		$classes[] = 'g1-meta';

		$counter = count( $breadcrumbs );

		if ( 1 < $counter ) {
			for ( $i = 0; $i < $counter; $i++ ) {
				if ( 0 === strlen( $breadcrumbs[ $i ]['text'] ) ) {
					continue;
				}

				if ( ( $counter - 1 ) === $i ) {
					$out .= '<li class="g1-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name">' . $breadcrumbs[ $i ]['text'] . '</span>
					<meta itemprop="position" content="'. absint( $i + 1 ) . '" />
					<meta itemprop="item" content="'. $breadcrumbs[ $i ]['href'] . '" />
					</li>';
				} else {
					$out .= '<li class="g1-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<a itemprop="item" content="'. $breadcrumbs[ $i ]['href'] . '" href="' . $breadcrumbs[ $i ]['href'] . '">
						<span itemprop="name">' . $breadcrumbs[ $i ]['text'] . '</span>
						<meta itemprop="position" content="'. absint( $i + 1 ) . '" />
						</a>
						</li>';
				}
			}

			$out = '<nav class="' . implode( ' ', array_map( 'sanitize_html_class', $classes ) ) . '">
				<p class="g1-breadcrumbs-label">' . __( 'You are here: ', 'bimber' ) . '</p>
				<ol itemscope itemtype="http://schema.org/BreadcrumbList">' .
				$out .
				'</ol>
				</nav>';
		}

		$out = apply_filters( 'bimber_breadcrumb_html', $out );
		return $out;
	}

	/**
	 * Render list
	 */
	public function render() {
		echo $this->capture();
	}
}

/**
 * Instance
 *
 * @return Bimber_Breadcrumbs
 */
function bimber_breadcrumbs() {
	return new Bimber_Breadcrumbs();
}
