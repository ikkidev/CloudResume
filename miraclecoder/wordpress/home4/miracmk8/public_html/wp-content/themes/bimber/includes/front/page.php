<?php
/**
 * Page functions
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
 * Adjust the HTML markup of pagination links
 *
 * @param array $args Arguments.
 *
 * @return array
 */
function bimber_filter_wp_link_pages_args( $args ) {
	global $page, $numpages, $multipage, $more, $pagenow;

	$pagination_defaults = array(
		'overview'          => bimber_get_theme_option( 'post', 'pagination_overview' ),
		'adjacent_label'    => bimber_get_theme_option( 'post', 'pagination_adjacent_label' ),
		'adjacent_style'    => bimber_get_theme_option( 'post', 'pagination_adjacent_style' ),
		'next_post'         => bimber_get_theme_option( 'post', 'pagination_next_post' ),
	);

	$pagination = isset( $args['bimber_pagination'] ) ? $args['bimber_pagination'] : array();

	$pagination = wp_parse_args( $pagination, $pagination_defaults );

	$overview = $pagination['overview'];

	$before = '';
	$before .= '<nav class="g1-pagination pagelinks">';

		if ( 'none' === $overview ) {
			$before .= '<p class="g1-pagination-label g1-pagination-label-none">' . esc_html__( 'Pages:', 'bimber' ) . '</p>';
		} elseif ( 'page_xofy' === $overview ) {
			$before .= '<p class="g1-pagination-label g1-pagination-label-xofy">' . esc_html( sprintf( __( 'Page %1$d of %2$d', 'bimber' ), $page, $numpages ) ) . '</p>';
		} else {
			$before .= '<p class="g1-pagination-label g1-pagination-label-links"><strong>' . esc_html__( 'Pages:', 'bimber' ) . '</strong></p>';
		}


		if ( 'arrow' === $pagination['adjacent_label'] ) {
			$before .= '<ul class="g1-pagination-just-arrows">';
		} else {
			$before .= '<ul>';
		}

	$after = '';
		$after .= '</ul>';
	$after .= '</nav>';

	$nextpagelink = __( 'Next', 'bimber' );
	$previouspagelink = __( 'Previous', 'bimber' );

	if ( 'adjacent_page' === $pagination['adjacent_label'] ) {
		$nextpagelink       = __( 'Next page', 'bimber' );
		$previouspagelink   = __( 'Previous page', 'bimber' );
	}

	$args = array_merge(
		$args,
		array(
			'before'           => $before,
			'after'            => $after,
			'current_before'   => '<strong class="current">',
			'current_after'    => '</strong>',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'next_or_number'   => 'next_and_number',
			'separator'        => '',
			'nextpagelink'     => esc_html( $nextpagelink ),
			'previouspagelink' => esc_html( $previouspagelink ),
		)
	);

	// Based on: http://www.velvetblues.com/web-development-blog/wordpress-number-next-previous-links-wp_link_pages/ .
	if ( 'next_and_number' === $args['next_or_number'] ) {
		$args['next_or_number'] = 'number';
		$prev                   = '';
		$next                   = '';
		if ( $multipage ) {
			if ( $more ) {
				// Previous element.
				$i = $page - 1;
				$is_prev_link = $i && $more;

				$prev .= $is_prev_link ? _wp_link_page( $i ) : '<a >';
				$prev .= $args['link_before'] . $args['previouspagelink'] . $args['link_after'] . '</a>';



				if ( 'button' === $pagination['adjacent_style'] ) {
					if ( 'page_links' === $overview ) {
						$prev = str_replace( '<a ', '<a class="g1-arrow g1-arrow-left g1-arrow-simple prev" ', $prev );
					} else {
						$prev = str_replace( '<a ', '<a class="g1-arrow g1-arrow-xl g1-arrow-left g1-arrow-simple prev" ', $prev );
					}
				} else {
					$prev = str_replace( '<a ', '<a class="g1-link g1-link-left g1-delta g1-delta-1st prev" ', $prev );
				}

				if ( ! $is_prev_link ) {
					$prev = str_replace(
						array(
							'<a class="g1-arrow ',
							'<a class="g1-link ',
							'</a>'
						),
						array(
							'<span class="g1-arrow g1-arrow-disabled ',
							'<span class="g1-link g1-link-disabled ',
							'</span>'
						),
						$prev
					);
				}

				$prev = '<li class="g1-pagination-item g1-pagination-item-prev">' . $prev . '</li>';


				// Next element.
				$i = $page + 1;
				$is_next_link   = $i <= $numpages && $more;
				$is_next_post   = false;
				$next_post_link = '';

				if ( 'standard' === $pagination['next_post'] && $page === $numpages ) {
					$next_post_link = get_next_post_link();

					if ( ! empty( $next_post_link ) ) {
						// We need just an open tag.
						$link_parts = explode( '>', $next_post_link );

						$next_post_link = ! empty( $link_parts ) ? $link_parts[0] . '>' : '';
					}

					$next_post_link = apply_filters( 'bimber_next_post_link', $next_post_link );

					$is_next_post   = ! empty( $next_post_link );
				}

				if ( $is_next_link ) {
					$next .= _wp_link_page( $i );
				} elseif ( $is_next_post ) {
					$next .= $next_post_link;

					$args['nextpagelink'] = __( 'Next post', 'bimber' );
				} else {
					$next .= '<a >';
				}

				$next .= $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>';

				if ( 'button' === $pagination['adjacent_style'] ) {
					if ( 'page_links' === $overview ) {
						$next = str_replace( '<a ', '<a class="g1-arrow g1-arrow-right g1-arrow-solid next" ', $next );
					} else {
						$next = str_replace( '<a ', '<a class="g1-arrow g1-arrow-xl g1-arrow-right g1-arrow-solid next" ', $next );
					}
				} else {
					$next = str_replace( '<a ', '<a class="g1-link g1-link-right g1-delta g1-delta-1st next" ', $next );
				}

				if ( ! $is_next_link && ! $is_next_post ) {
					$next = str_replace(
						array(
							'<a class="g1-arrow ',
							'<a class="g1-link ',
							'</a>'
						),
						array(
							'<span class="g1-arrow g1-arrow-disabled ',
							'<span class="g1-link g1-link-disabled ',
							'</span>'
						),
						$next
					);
				}

				$next = '<li class="g1-pagination-item g1-pagination-item-next">' . $next . '</li>';
			}
		}
		$args['before'] = $args['before'] . $prev;
		$args['after']  = $next . $args['after'];
	}

	return $args;
}

/**
 * Add some markup to the output of the wp_link_pages_link function
 *
 * @param string $link Markup.
 * @param int    $i Index.
 *
 * @return string
 */
function bimber_filter_wp_link_pages_link( $link, $i ) {
	global $page, $numpages;

	if ( $i === $page ) {
		$link = '<li class="g1-pagination-item g1-pagination-item-current">' . $link . '</li>';
	} else {
		$link = '<li class="g1-pagination-item">' . $link . '</li>';
	}

	return $link;
}

/**
 * Append all collections (Trending/Hot/Popular) to the content of the Top page
 *
 * @param string $content Post content.
 *
 * @return string
 */
function bimber_top_page( $content ) {
	if ( bimber_is_top_page() ) {
		remove_filter( 'the_content', 'bimber_top_page', 11 );

		ob_start();
		get_template_part( 'template-parts/top-page' );
		$extra_content = ob_get_clean();

		add_filter( 'the_content', 'bimber_top_page', 11 );

		$content .= $extra_content;
	}

	return $content;
}

/**
 * Append the Hot entries collection to the content of the Hot page
 *
 * @param string $content Post content.
 *
 * @return string
 */
function bimber_list_hot_entries( $content ) {
	if ( bimber_is_hot_page() ) {
		remove_filter( 'the_content', 'bimber_list_hot_entries', 11 );

		ob_start();
		get_template_part( 'template-parts/collection-hot' );
		$extra_content = ob_get_clean();

		add_filter( 'the_content', 'bimber_list_hot_entries', 11 );

		$content .= $extra_content;
	}

	return $content;
}

/**
 * Append the Popular entries collection to the content of the Popular page
 *
 * @param string $content Post content.
 *
 * @return string
 */
function bimber_list_popular_entries( $content ) {
	if ( bimber_is_popular_page() ) {
		remove_filter( 'the_content', 'bimber_list_popular_entries', 11 );

		ob_start();
		get_template_part( 'template-parts/collection-popular' );
		$extra_content = ob_get_clean();

		add_filter( 'the_content', 'bimber_list_popular_entries', 11 );

		$content .= $extra_content;
	}

	return $content;
}

/**
 * Append the TrendingHot entries collection to the content of the Trending page
 *
 * @param string $content Post content.
 *
 * @return string
 */
function bimber_list_trending_entries( $content ) {
	if ( bimber_is_trending_page() ) {
		remove_filter( 'the_content', 'bimber_list_trending_entries', 11 );

		ob_start();
		get_template_part( 'template-parts/collection-trending' );
		$extra_content = ob_get_clean();

		add_filter( 'the_content', 'bimber_list_trending_entries', 11 );

		$content .= $extra_content;
	}

	return $content;
}




function bimber_get_page_header_options( $post_id ) {
	$options = get_post_meta( $post_id, '_bimber_page_header_options', true );
	$defaults = array(
		'composition'   => '01',
		'bg_color'      => '',
		'bg2_color'     => '',
		'bg_image'      => '',
		'bg_size'       => '',
		'bg_repeat'     => '',
	);
	$options = wp_parse_args( $options, $defaults );

	return $options;
}

function bimber_enqueue_page_styles( $post_id ) {
	$options = bimber_get_page_header_options( $post_id );

	require_once BIMBER_FRONT_DIR . 'lib/class-bimber-color.php';

	$inline_css = '';

	$color = $options['bg_color'];
	if ( strlen( $color ) ) {
		$color = new Bimber_Color( $color );
		$inline_css .= 'background-color: #' . $color->get_hex() . ';';

		// Background gradient.
		$gradient = $options['bg2_color'];
		if ( strlen( $gradient ) ) {
			$gradient = new Bimber_Color( $gradient );

			$direction = is_rtl() ? 'left' : 'right';
			$inline_css .= 'background-image: -webkit-linear-gradient(to ' . $direction . ', #' . $color->get_hex() . ', #' . $gradient->get_hex() . ');';
			$inline_css .= 'background-image:    -moz-linear-gradient(to ' . $direction . ', #' . $color->get_hex() . ', #' . $gradient->get_hex() . ');';
			$inline_css .= 'background-image:      -o-linear-gradient(to ' . $direction . ', #' . $color->get_hex() . ', #' . $gradient->get_hex() . ');';
			$inline_css .= 'background-image:         linear-gradient(to ' . $direction . ', #' . $color->get_hex() . ', #' . $gradient->get_hex() . ');';
		}
	}

	// Background image.
	$image = wp_get_attachment_image_src( $options['bg_image'], 'full' );
	$image = is_array( $image ) ? $image[0] : '';

	if ( strlen( $image ) ) {
		$inline_css .= 'background-image: url(' . esc_url( $image ) . ');';
		$inline_css .= 'background-position: center center;';

		// Background size.
		if ( in_array( $options['bg_size'], array('auto', 'cover', 'contain' ) ) ) {
			$inline_css .= 'background-size: ' . $options['bg_size'] . ';';
		}

		// Background repeat.
		if ( in_array( $options['bg_repeat'], array('no-repeat', 'repeat', 'repeat-x', 'repeat-y' ) ) ) {
			$inline_css .= 'background-repeat: ' . $options['bg_repeat'] . ';';
		}
	}

	// Compose final CSS rules.
	if ( strlen( $inline_css ) ) {
		add_filter( 'bimber_page_header_class', 'bimber_add_dark_color_scheme_class' );

		$inline_css = '.page-header > .g1-row-background { ' . $inline_css . ' }';

		wp_add_inline_style( 'g1-main', $inline_css );
	}
}

function bimber_maybe_enqueue_page_styles() {
	if ( is_page() ) {
		global $post;

		bimber_enqueue_page_styles( $post->ID );
	}
}

