<?php
/**
 * Post template tags
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
 * Get microdata (http://schema.org) itemtype.
 *
 * @return string
 */
function bimber_get_entry_microdata_itemtype() {
	// Default value.
	$result = 'http://schema.org/CreativeWork';

	switch ( get_post_type() ) {
		case 'page' :
			$result = 'http://schema.org/WebPage';
			break;

		case 'post' :
			$result = 'http://schema.org/Article';
			break;
	}

	return apply_filters( 'bimber_get_entry_microdata_itemtype', $result );
}



/**
 * Render entry title.
 *
 * @param string $before Before title.
 * @param string $after Before title.
 * 
 * @since 6.0.0
 */
function bimber_render_entry_title( $before, $after ) {
	$title_data = apply_filters( 'bimber_entry_title_data', array(
		'permalink' => get_permalink(),
		'before'    => $before,
		'after'     => $after,
	) );

	the_title( sprintf( $title_data['before'], esc_url( apply_filters( 'the_permalink', $title_data['permalink']) ) ), $title_data['after'] );
}


/**
 * Render entry subtitle.
 *
 * @param string $before Before sutitle.
 * @param string $after Before sutitle.
 *
 * @since 6.2.0
 */
function bimber_render_entry_subtitle( $before, $after ) {
	if ( bimber_can_use_plugin( 'wp-subtitle/wp-subtitle.php' ) ) {
		the_subtitle( $before, $after );
	}
}



/**
 * Render entry statistics.
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_stats( $args = array() ) {
	echo bimber_capture_entry_stats( $args );
}

/**
 * Capture entry statistics.
 *
 * @param array $args Arguments.
 *
 * @return string   Escaped HTML
 */
function bimber_capture_entry_stats( $args = array() ) {

	$defaults = array(
		'class'             => '',
		'before'            => '<p class="%s">',
		'after'             => '</p>',
		'share_count'       => true,
		'view_count'        => true,
		'comment_count'     => true,
		'download_count'    => true,
		'vote_count'        => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$final_class = array(
		'entry-meta',
		'entry-stats',
		'g1-meta'
	);
	$final_class = array_merge( $final_class, explode( ' ', $args['class'] ) );

	$args['before'] = sprintf( $args['before'], implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) );

	$stats = array();

	if ( $args['share_count'] ) {
		$stats[] = bimber_capture_entry_share_count();
	}

	if ( $args['view_count'] ) {
		$stats[] = bimber_capture_entry_view_count();
	}

	if ( $args['download_count'] ) {
		$stats[] = bimber_capture_entry_download_count();
	}

	if ( $args['comment_count'] ) {
		$stats[] = bimber_capture_entry_comments_link();
	}

	if ( $args['vote_count'] ) {
		$stats[] = bimber_capture_entry_vote_count();
	}

	// Filter empty strings.
	$stats = array_filter( $stats );

	$out_escaped = '';
	if ( count( $stats ) ) {
		$out_escaped .= $args['before'];
		$out_escaped .= implode( '', $stats );
		$out_escaped .= $args['after'];
	}

	return $out_escaped;
}


/**
 * Whether to show the total share count for the current entry.
 *
 * @return boolean
 */
function bimber_show_entry_share_count() {
	$show        = true;
	$share_count = bimber_get_entry_share_count();

	if ( $share_count < 0 ) {
		$show = false;
	}

	return apply_filters( 'bimber_show_entry_share_count', $show, $share_count );
}

/**
 * Get the total share count for entry.
 *
 * @return int
 */
function bimber_get_entry_share_count() {
	return apply_filters( 'bimber_entry_share_count', - 1 );
}


/**
 * Render the total share count for the current entry.
 */
function bimber_render_entry_share_count() {
	echo bimber_capture_entry_share_count();
}

/**
 * Capture the total share count for the current entry.
 *
 * @return string   Escaped HTML
 */
function bimber_capture_entry_share_count() {
	$out_escaped = '';

	if ( bimber_show_entry_share_count() ) {
		$share_count           = bimber_get_entry_share_count();
		$share_count_formatted = bimber_format_number( $share_count );

		$out_escaped .= '<span class="entry-shares">';
		$out_escaped .= sprintf( wp_kses_post( '<strong>%s</strong><span> '. __( 'Shares', 'bimber' ) .'</span>' ), esc_html( $share_count_formatted ) );
		$out_escaped .= '</span>';
	}

	return $out_escaped;
}


/**
 * Whether to show the total page view count for the current entry.
 *
 * @return bool
 */
function bimber_show_entry_view_count() {
	$show       = true;
	$view_count = bimber_get_entry_view_count();

	if ( $view_count < 0 ) {
		$show = false;
	} else {
		$views_threshold = absint( bimber_get_theme_option( 'posts', 'views_threshold' ) );

		if ( $views_threshold && $views_threshold >= $view_count ) {
			$show = false;
		}
	}

	return apply_filters( 'bimber_show_entry_view_count', $show, $view_count );
}

/**
 * Get the total page view count for entry.
 *
 * @return int
 */
function bimber_get_entry_view_count() {
	return apply_filters( 'bimber_entry_view_count', - 1 );
}


/**
 * Render the total page view count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 */
function bimber_render_entry_view_count( $extra_css_class = '' ) {
	echo bimber_capture_entry_view_count( $extra_css_class );
}

/**
 * Capture the total page view count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 *
 * @return string       Escaped HTML
 */
function bimber_capture_entry_view_count( $extra_css_class = '' ) {
	$out_escaped = '';

	if ( bimber_show_entry_view_count() ) {
		$count = bimber_get_entry_view_count();

		$final_class = array(
			'entry-views'
		);

		if ( bimber_is_post_trending() ) {
			$final_class[] = 'entry-views-trending';
		} elseif ( bimber_is_post_hot() ) {
			$final_class[] = 'entry-views-hot';
		} elseif ( bimber_is_post_popular() ) {
			$final_class[] = 'entry-views-popular';
		}

		$final_class = array_merge( $final_class, explode( ' ', $extra_css_class ) );

		if ( apply_filters( 'bimber_shorten_view_count', true ) ) {
			$count_str = bimber_shorten_number( (int) $count );
		} else {
			$count_str = number_format_i18n( intval( $count ) );
		}

		$out_escaped .= '<span class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '">';
		$out_escaped .= sprintf( wp_kses_post( '<strong>%s</strong><span> '. _n( 'View', 'Views', $count, 'bimber' ) .'</span>' ), $count_str );
		$out_escaped .= '</span>';
	}

	return apply_filters( 'bimber_entry_comments_link_html', $out_escaped );
}


/**
 * Whether to show the comments link for entry.
 *
 * @return bool
 */
function bimber_show_entry_comments_link() {
	$show               = true;
	$comments_threshold = absint( bimber_get_theme_option( 'posts', 'comments_threshold' ) );
	if ( $comments_threshold && $comments_threshold > get_comments_number() ) {
		$show = false;
	}

	return apply_filters( 'bimber_show_entry_comments_link', $show );
}

/**
 * Render the comments link for entry.
 */
function bimber_render_entry_comments_link() {
	echo bimber_capture_entry_comments_link();
}

/**
 * Capture the comments link for entry.
 *
 * @return string       Escaped HTML
 */
function bimber_capture_entry_comments_link() {
	$out_escaped = '';

	if ( bimber_show_entry_comments_link() ) {

		$number = (int) get_comments_number( get_the_ID() );

		if ( apply_filters( 'bimber_hide_comments_link_below_number', false, $number ) ) {
			return '';
		}

		$final_class = array(
			'entry-comments-link',
		);

		if ( 0 === $number ) {
			$final_class[] = 'entry-comments-link-0';
		} else if ( 1 === $number ) {
			$final_class[] = 'entry-comments-link-1';
		} else {
			$final_class[] = 'entry-comments-link-x';
		}

		$out_escaped .= '<span class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '">';

		ob_start();
		comments_popup_link(
			wp_kses_post( __( '<strong itemprop="commentCount">0</strong> <span>Comments</span>', 'bimber' ) ),
			wp_kses_post( __( '<strong itemprop="commentCount">1</strong> <span>Comment</span>', 'bimber' ) ),
			wp_kses_post( __( '<strong itemprop="commentCount">%</strong> <span>Comments</span>', 'bimber' ) )
		);
		$out_escaped .= ob_get_clean();

		$out_escaped .= '</span>';
	}

	return apply_filters( 'bimber_entry_comments_link_html', $out_escaped );
}

/**
 * Whether to show the total download count for the current entry.
 *
 * @return bool
 */
function bimber_show_entry_download_count() {
	$show       = true;
	$count = bimber_get_entry_download_count();

	if ( $count < 0 ) {
		$show = false;
	} else {
		$threshold = absint( bimber_get_theme_option( 'dm', 'downloads_threshold' ) );

		if ( $threshold && $threshold >= $count ) {
			$show = false;
		}
	}

	return apply_filters( 'bimber_show_entry_download_count', $show, $count );
}

/**
 * Whether to show the total vote count for the current entry.
 *
 * @return bool
 */
function bimber_show_entry_vote_count() {
	return apply_filters( 'bimber_show_entry_vote_count', false );
}

/**
 * Get the total download count for entry.
 *
 * @return int
 */
function bimber_get_entry_download_count() {
	return apply_filters( 'bimber_entry_download_count', - 1 );
}

/**
 * Get the total vote count for entry.
 *
 * @return int
 */
function bimber_get_entry_vote_count() {
	return apply_filters( 'bimber_entry_vote_count', - 1 );
}


/**
 * Render the total download count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 */
function bimber_render_entry_download_count( $extra_css_class = '' ) {
	echo bimber_capture_entry_download_count( $extra_css_class );
}

/**
 * Capture the total download count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 *
 * @return string       Escaped HTML
 */
function bimber_capture_entry_download_count( $extra_css_class = '' ) {
	$out_escaped = '';

	if ( bimber_show_entry_download_count() ) {
		$count = bimber_get_entry_download_count();

		$final_class = array(
			'entry-downloads'
		);

		if ( bimber_is_post_trending() ) {
			$final_class[] = 'entry-downloads-trending';
		} elseif ( bimber_is_post_hot() ) {
			$final_class[] = 'entry-downloads-hot';
		} elseif ( bimber_is_post_popular() ) {
			$final_class[] = 'entry-downloads-popular';
		}

		$final_class = array_merge( $final_class, explode( ' ', $extra_css_class ) );

		if ( apply_filters( 'bimber_shorten_download_count', true ) ) {
			$count_str = bimber_shorten_number( (int) $count );
		} else {
			$count_str = number_format_i18n( intval( $count ) );
		}

		$out_escaped .= '<span class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '">';
		$out_escaped .= sprintf( wp_kses_post( '<strong>%s</strong><span> '. __( 'Downloads', 'bimber' ) .'</span>' ), $count_str );
		$out_escaped .= '</span>';
	}

	return apply_filters( 'bimber_entry_downloads_html', $out_escaped );
}

/**
 * Render the total page vote count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 */
function bimber_render_entry_vote_count( $extra_css_class = '' ) {
	echo bimber_capture_entry_vote_count( $extra_css_class );
}

/**
 * Capture the total vote count for entry.
 *
 * @param string $extra_css_class Extra CSS class.
 *
 * @return string       Escaped HTML
 */
function bimber_capture_entry_vote_count( $extra_css_class = '' ) {
	$out_escaped = '';

	if ( bimber_show_entry_vote_count() ) {
		$count = bimber_get_entry_vote_count();

		$final_class = array(
			'entry-votes'
		);

		if ( bimber_is_post_trending() ) {
			$final_class[] = 'entry-votes-trending';
		} elseif ( bimber_is_post_hot() ) {
			$final_class[] = 'entry-votes-hot';
		} elseif ( bimber_is_post_popular() ) {
			$final_class[] = 'entry-votes-popular';
		}

		$final_class = array_merge( $final_class, explode( ' ', $extra_css_class ) );

		if ( apply_filters( 'bimber_shorten_vote_count', true ) ) {
			$count_str = bimber_shorten_number( (int) $count );
		} else {
			$count_str = number_format_i18n( intval( $count ) );
		}

		$out_escaped .= '<span class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '">';
		$out_escaped .= sprintf( wp_kses_post( '<strong>%s</strong><span> '.  _n( 'Vote', 'Votes', $count, 'bimber' ) .'</span>' ), $count_str );
		$out_escaped .= '</span>';
	}

	return apply_filters( 'bimber_entry_votes_html', $out_escaped );
}


/**
 * Render entry categories for the current post
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_categories( $args = array() ) {
	echo bimber_capture_entry_categories( $args );
}

/**
 * Capture entry categories for the current post
 *
 * @param array $args Arguments.
 *
 * @return string Escaped HTML
 */
function bimber_capture_entry_categories( $args = array() ) {
	$out_escaped = '';

	$defaults = array(
		'before'        => '<span class="entry-categories %s"><span class="entry-categories-inner"><span class="entry-categories-label">' . esc_html__( 'in', 'bimber') . '</span> ',
		'after'         => '</span></span>',
		'class'         => '',
		'use_microdata' => false,
	);

	$args = wp_parse_args( $args, $defaults );

	// Sanitize HTML classes.
	$args['class'] = explode( ' ', $args['class'] );
	$args['class'] = implode( ' ', array_map( 'sanitize_html_class', $args['class'] ) );

	$args['before'] = sprintf( $args['before'], $args['class'] );

	$term_list = get_the_terms( get_the_ID(), apply_filters( 'bimber_entry_categories_taxonomy', 'category' ) );

	if ( is_array( $term_list ) ) {
		$out_escaped .= $args['before'];

		foreach ( $term_list as $term ) {
			$term_link = is_wp_error( get_term_link( $term->slug, 'category' ) ) ? '#' : get_term_link( $term->slug, 'category' );
			if ( $args['use_microdata'] ) {
				$out_escaped .= sprintf(
					'<a href="%s" class="entry-category %s"><span itemprop="articleSection">%s</span></a>, ',
					esc_url( $term_link ),
					sanitize_html_class( 'entry-category-item-' . $term->term_taxonomy_id ),
					wp_kses_post( $term->name )
				);
			} else {
				$out_escaped .= sprintf(
					'<a href="%s" class="entry-category %s">%s</a>, ',
					esc_url( $term_link ),
					sanitize_html_class( 'entry-category-item-' . $term->term_taxonomy_id ),
					wp_kses_post( $term->name )
				);
			}
		}

		// Remove the last comma.
		$out_escaped = trim( $out_escaped, ', ' );

		$out_escaped .= $args['after'];
	}
	return apply_filters( 'bimber_entry_categories_html', $out_escaped );
}


/**
 * Render entry tags for the current post
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_tags( $args = array() ) {
	echo bimber_capture_entry_tags( $args );
}


/**
 * Capture entry tags for the current post
 *
 * @param array $args Arguments.
 *
 * @return string Escaped HTML
 */
function bimber_capture_entry_tags( $args = array() ) {
	if ( ! isset( $args['elements']['tags'] ) || ! $args['elements']['tags'] ) {
		return '';
	}

	$out_escaped = '';

	$defaults = array(
		'before' => '<p class="entry-tags %s"><span class="entry-tags-inner">',
		'after'  => '</span></p>',
		'class'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	// Sanitize HTML classes.
	$args['class'] = explode( ' ', $args['class'] );
	$args['class'] = implode( ' ', array_map( 'sanitize_html_class', $args['class'] ) );

	$args['before'] = sprintf( $args['before'], $args['class'] );

	$term_list = get_the_terms( get_the_ID(), 'post_tag' );

	if ( is_array( $term_list ) ) {
		$out_escaped .= $args['before'];

		foreach ( $term_list as $term ) {
			$term_link = is_wp_error( get_term_link( $term->slug, 'post_tag' ) ) ? '#' : get_term_link( $term->slug, 'post_tag' );
			$out_escaped .= sprintf( '<a href="%s" class="entry-tag %s">%s</a>',
				esc_url( $term_link ),
				sanitize_html_class( 'entry-tag-' . $term->term_taxonomy_id ),
				wp_kses_post( $term->name )
			);
		}

		$out_escaped .= $args['after'];
	}

	return $out_escaped;
}


/**
 * Wrapper for the_tags function
 */
function bimber_the_tags() {
	the_tags();
}


/**
 * Render date information for the current post.
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_date( $args = array() ) {
	$defaults = array(
		'use_microdata' => false,
		'use_timeago'   => false,
		'is_single'     => false,

	);

	$args = wp_parse_args( $args, $defaults );

	// What to display?
	$args['dates'] = $args['is_single'] ? bimber_get_theme_option( 'post', 'dates' ) : bimber_get_theme_option( 'posts', 'dates' );

	do_action( 'bimber_before_entry_date' );

	// Date of publication.
	$pub_date = false;
	$pub_time = false;
	$pub_html = '';

	// Date of modification.
	$mod_date = false;
	$mod_time = false;
	$mod_html = '';

	$timestamp_margin = 60;

	switch ( $args['dates'] ) {
		case 'publication':
			$pub_date = get_the_time( get_option( 'date_format' ) );
			$pub_time = get_the_time( get_option( 'time_format' ) );
			$pub_sep  = $pub_time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';
			break;

		case 'modification':
			if ( get_the_modified_time('U') > ( get_the_time('U') + $timestamp_margin ) ) {
				$mod_date = get_the_modified_time( get_option( 'date_format' ) );
				$mod_time = get_the_modified_time( get_option( 'time_format' ) );
				$mod_sep  = $mod_time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';
			} else {
				$pub_date = get_the_time( get_option( 'date_format' ) );
				$pub_time = get_the_time( get_option( 'time_format' ) );
				$pub_sep  = $pub_time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';
			}

			break;

		case 'both':
		default:
			$pub_date = get_the_time( get_option( 'date_format' ) );
			$pub_time = get_the_time( get_option( 'time_format' ) );
			$pub_sep  = $pub_time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';

			if ( get_the_modified_time('U') > ( get_the_time('U') + $timestamp_margin ) ) {
				$mod_date = get_the_modified_time( get_option( 'date_format' ) );
				$mod_time = get_the_modified_time( get_option( 'time_format' ) );
				$mod_sep  = $mod_time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';
			}
			break;
	}

	// Date of publication markup.
	if ( $pub_date ) {
		if ( $args['use_timeago'] ) {
			$pub_html = sprintf(
				_x( '%s ago', '%s = human-readable time difference', 'bimber' ),
				human_time_diff( get_the_date( 'U' ) ),
				current_time( 'timestamp' )
			);
		} else {
			$pub_html = $pub_date . $pub_sep . $pub_time;
		}

		if ( $args['use_microdata'] ) {
			$pub_html = sprintf(
				'<time class="entry-date" datetime="%1$s" itemprop="datePublished">%2$s</time>',
				esc_attr( get_the_time( 'Y-m-d' ) . 'T' . get_the_time( 'H:i:s' ) ) . bimber_get_iso_8601_utc_offset(),
				esc_html( $pub_html )
			);
		} else {
			$pub_html = sprintf(
				'<time class="entry-date" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_time( 'Y-m-d' ) . 'T' . get_the_time( 'H:i:s' ) . bimber_get_iso_8601_utc_offset() ),
				esc_html( $pub_html )
			);
		}
	}

	// Date of modification markup.
	if ( $mod_date ) {
		if ( $args['use_timeago'] ) {
			$mod_html = sprintf(
				_x( '%s ago', '%s = human-readable time difference', 'bimber' ),
				human_time_diff( get_the_modified_date( 'U' ) ),
				current_time( 'timestamp' )
			);
		} else {
			$mod_html = $mod_date . $mod_sep . $mod_time;
		}


		if ( $args['use_microdata'] ) {
			$mod_html = sprintf(
				'<span class="entry-date">' . str_replace( '%2$s', '<time datetime="%1$s" itemprop="dateModified">%2$s</time>' , esc_html__( 'updated %2$s', 'bimber' ) ) . '</span>',
				esc_attr( get_the_modified_time( 'Y-m-d' ) . 'T' . get_the_modified_time( 'H:i:s' ) ) . bimber_get_iso_8601_utc_offset(),
				esc_html( $mod_html )
			);
		} else {
			$mod_html = sprintf(
				'<span class="entry-date">' . str_replace( '%2$s', '<time datetime="%1$s">%2$s</time>' , esc_html__( 'updated %2$s', 'bimber' ) ) . '</span>',
				esc_attr( get_the_modified_time( 'Y-m-d' ) . 'T' . get_the_modified_time( 'H:i:s' ) . bimber_get_iso_8601_utc_offset() ),
				esc_html( $mod_html )
			);
		}
	}

	echo apply_filters( 'bimber_entry_date_html', $pub_html . $mod_html, $args );
}

/**
 * Render modified date information for the current post.
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_modified_date( $args = array() ) {
	$defaults = array(
		'use_microdata' => false,
		'use_timeago'   => false,
	);

	$args = wp_parse_args( $args, $defaults );

	do_action( 'bimber_before_entry_date' );

	$date = get_the_modified_time( get_option( 'date_format' ) );
	$time = get_the_modified_time( get_option( 'time_format' ) );
	$sep  = $time ? apply_filters( 'bimber_entry_date_time_separator', ', ' ) : '';

	if ( $args['use_timeago'] ) {
		$html = sprintf(
			_x( '%s ago', '%s = human-readable time difference', 'bimber' ),
			human_time_diff( get_the_modified_date( 'U' ) ),
			current_time( 'timestamp' )
		);
	} else {
		$html = $date . $sep . $time;
	}

	if ( $args['use_microdata'] ) {
		printf(
			'<time class="entry-date" datetime="%1$s" itemprop="dateModified">%2$s</time>',
			esc_attr( get_the_modified_time( 'Y-m-d' ) . 'T' . get_the_modified_time( 'H:i:s' ) ),
			esc_html( $html )
		);
	} else {
		printf(
			'<time class="entry-date" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_modified_time( 'Y-m-d' ) . 'T' . get_the_modified_time( 'H:i:s' ) ),
			esc_html( $html )
		);
	}
}

/**
 * Check whether to show featured media
 *
 * @param bool $show            Default value.
 *
 * @return mixed|null|void
 */
function bimber_show_entry_featured_media( $show = true ) {
	global $page;

	if ( $page > 1 ) {
		$show = false;
	} else {
		$options = get_post_meta( get_the_ID(), '_bimber_single_options', true );

		// If not set, global setting will be used.
		if ( ! empty( $options ) && ! empty( $options['featured_media'] ) ) {
			$show = 'none' !== $options['featured_media'];
		}
	}

	return apply_filters( 'bimber_show_entry_featured_media', $show );
}

/**
 * Render author information for entry.
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_author( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'avatar'        => true,
		'avatar_size'   => 30,
		'use_microdata' => false,
	) );
	ob_start();
	?>
	<?php if ( $args['use_microdata'] ) : ?>
		<span class="entry-author" itemscope="" itemprop="author" itemtype="http://schema.org/Person">
	<?php else : ?>
		<span class="entry-author">
	<?php endif; ?>

		<span class="entry-meta-label"><?php esc_html_e( 'by', 'bimber' ); ?></span>
			<?php
				printf(
					'<a href="%s" title="%s" rel="author">',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'Posts by %s', 'bimber' ), get_the_author() ) )
				);
			?>

			<?php
			if ( $args['avatar'] ) :
				echo get_avatar( get_the_author_meta( 'ID' ), $args['avatar_size'] );
			endif;
			?>

			<?php if ( $args['use_microdata'] ) : ?>
				<strong itemprop="name"><?php echo esc_html( get_the_author() ); ?></strong>
			<?php else : ?>
				<strong><?php echo esc_html( get_the_author() ); ?></strong>
			<?php endif; ?>
		</a>
	</span>
	<?php
	$out = ob_get_clean();
	echo apply_filters( 'bimber_entry_author_html', $out );
}


/**
 * Render flags for entry.
 */
function bimber_render_entry_flags( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'show_collections' => true,
		'show_reactions_single' => false,
	) );

	$flags = array();

	if ( $args['show_collections'] ) {
		if ( bimber_has_trending_collection() && bimber_is_post_trending() ) {
			$flags['trending'] = array(
				'label' => __( 'Trending', 'bimber' ),
				'url'   => bimber_get_trending_page_url()
			);
		}

		if ( bimber_has_hot_collection() && bimber_is_post_hot() ) {
			$flags['hot'] = array(
				'label' =>  __( 'Hot', 'bimber' ),
				'url'   => bimber_get_hot_page_url()
			);
		}

		if ( bimber_has_popular_collection() && bimber_is_post_popular() ) {
			$flags['popular'] = array(
				'label' => __( 'Popular', 'bimber' ),
				'url'   => bimber_get_popular_page_url()
			);
		}
	}


	$flags = apply_filters( 'bimber_get_entry_flags', $flags );

	if ( $args['show_reactions_single'] ) {
		$show_reactions = bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ) && apply_filters( 'bimber_show_entry_reactions_single', true );
	} else {
		$show_reactions = bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ) && apply_filters( 'bimber_show_entry_reactions', true );
	}

	$wyr_terms = array();

	if ( $show_reactions ) {
		$wyr_terms = wyr_get_post_reactions();
	}
	?>
	<?php if ( count( $flags ) || count( $wyr_terms ) ) : ?>
		<p class="entry-flags">
			<?php foreach ( $flags as $flag_id => $flag_args ) : ?>
				<?php if ( empty( $flag_args['url'] ) ) : ?>
					<span class="entry-flag entry-flag-<?php echo sanitize_html_class( $flag_id ); ?>" title="<?php echo esc_attr( ! empty( 	$flag_args['title'] ) ? $flag_args['title'] : $flag_args['label'] ); ?>">
						<?php echo esc_html( $flag_args['label'] ); ?>
					</span>
				<?php else: ?>
					<a class="entry-flag entry-flag-<?php echo sanitize_html_class( $flag_id ); ?>" href="<?php echo esc_url( $flag_args['url'] ); ?>" title="<?php echo esc_attr( ! empty( 	$flag_args['title'] ) ? $flag_args['title'] : $flag_args['label'] ); ?>">
						<?php echo esc_html( $flag_args['label'] ); ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( $show_reactions ) : ?>
				<?php foreach ( $wyr_terms as $wyr_term ) :
					$term_link = is_wp_error( get_term_link( $wyr_term ) ) ? '#' : get_term_link( $wyr_term );
					?>
					<a class="entry-flag entry-flag-reaction" href="<?php echo esc_url( $term_link ); ?>" title="<?php echo esc_attr( $wyr_term->name ); ?>">
						<?php wyr_render_reaction_icon( $wyr_term->term_id ); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
	<?php endif;
}

/**
 * Render social sharing buttons before the content.
 */
function bimber_render_top_share_buttons() {
	do_action( 'bimber_render_top_share_buttons' );
}

/**
 * Render social sharing buttons after the content.
 */
function bimber_render_bottom_share_buttons() {
	do_action( 'bimber_render_bottom_share_buttons' );
}

/**
 * Render social sharing buttons next to the content.
 */
function bimber_render_side_share_buttons() {
	do_action( 'bimber_render_side_share_buttons' );
}


/**
 * Render compact social sharing buttons.
 */
function bimber_render_compact_share_buttons() {
	do_action( 'bimber_render_compact_share_buttons' );
}



/**
 * Render mini social sharing buttons.
 */
function bimber_render_mini_share_buttons() {
	do_action( 'bimber_render_mini_share_buttons' );
}

/**
 * Render CTA button
 */
function bimber_render_entry_cta_button( $args = array() ) {
	$defaults = array(
		'class' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$final_class = array(
		'entry-cta',
	);
	$final_class = array_merge( $final_class, explode( ' ', $args['class'] ) );

	add_filter( 'the_permalink', 'bimber_the_permalink' );
	?>
	<p class="entry-ctas">
		<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) ?>" href="<?php the_permalink(); ?>">
			<?php echo esc_html( apply_filters( 'bimber_entry_cta_button_label', __( 'Read More', 'bimber' ) ) ); ?>
		</a>
	</p>
	<?php
	remove_filter( 'the_permalink', 'bimber_the_permalink' );
}

/**
 * Render breadcrumbs.
 */
function bimber_render_breadcrumbs() {
	bimber_breadcrumbs()->render();
}

/**
 * Whether to show breadcrumbs.
 *
 * @return boolean
 */
function bimber_show_breadcrumbs() {
	$show = ( 'standard' === bimber_get_theme_option( 'breadcrumbs', '' ) );
		if ( bimber_is_auto_load() ) {
		$show = false;
	}
	return apply_filters( 'bimber_show_breadcrumbs', $show );
}




function bimber_render_entry_featured_media_caption() {
	$attachment = get_post( get_post_thumbnail_id() );

	if ( ! $attachment ) {
		return;
	}

	$allowed_tags = apply_filters( 'bimber_entry_featured_media_caption_allowed_tags', array(
		'a' => array(
			'href'      => array(),
			'title'     => array(),
			'class'     => array(),
			'rel'       => array(),
			'target'    => array(),
		),
	) );

	$caption = apply_filters( 'bimber_entry_featured_media_caption', trim( $attachment->post_excerpt ) );
	?>

	<?php if ( strlen( $caption ) ) : ?>
		<div class="g1-meta entry-media-credit">
			<?php echo wp_kses( $caption, $allowed_tags ); ?>
		</div>
	<?php endif;
}


function bimber_render_pagination_single( $args ) {
	wp_link_pages();
}


function bimber_render_newsletter( $args ) {
	if ( isset( $args['elements']['newsletter'] ) && $args['elements']['newsletter'] ) {
		get_template_part( 'template-parts/newsletter/newsletter-after-content' );
	}
}

function bimber_render_nav_single( $args ) {
	if ( isset( $args['elements']['navigation'] ) && $args['elements']['navigation'] ) {
		get_template_part( 'template-parts/nav-single' );
	}
}

function bimber_render_author_info( $args ) {
	if ( isset( $args['elements']['author_info'] ) && $args['elements']['author_info'] ) {
		get_template_part( 'template-parts/author-info' );
	}
}


function bimber_render_related_entries( $args ) {
	get_template_part( 'template-parts/ads/ad-before-related-entries' );
	if ( bimber_is_auto_load_no_sidebar() ) {
		$args['layout'] = 'row';
	}

	if ( isset( $args['elements']['related_entries'] ) && $args['elements']['related_entries'] ) {
		get_template_part( 'template-parts/collection-related', $args['layout'] );
	}
}

function bimber_render_more_from( $args ) {
	get_template_part( 'template-parts/ads/ad-before-more-from' );
	if ( bimber_is_auto_load_no_sidebar() ) {
		$args['layout'] = 'row';
	}

	if ( isset( $args['elements']['more_from'] ) && $args['elements']['more_from'] ) {
		get_template_part( 'template-parts/collection-more-from' );
	}
}

function bimber_render_dont_miss( $args ) {
	get_template_part( 'template-parts/ads/ad-before-dont-miss' );
	if ( bimber_is_auto_load_no_sidebar() ) {
		$args['layout'] = 'row';
	}

	if ( isset( $args['elements']['dont_miss'] ) && $args['elements']['dont_miss'] ) {
		get_template_part( 'template-parts/collection-dont-miss', $args['layout'] );
	}
}

function bimber_render_comments( $args ) {
	if ( isset( $args['elements']['comments'] ) && $args['elements']['comments'] ) {
		get_template_part( 'template-parts/comments' );
	}
}

/**
 * Render missing metadata for the schema.org integration.
 *
 * @param array $args Arguments.
 */
function bimber_render_missing_metadata( $args ) {
	$elements = $args['elements'];
	if ( ! is_array( $elements ) ) {
		return;
	}

	$elements['date_modification'] = false;
    $elements['date_publication'] = false;

	if ( $elements['date'] ) {
		switch ( bimber_get_theme_option( 'post', 'dates' ) ) {
			case 'publication':
				$elements['date_modification'] = true;
				break;

			case 'modification':
				$elements['date_publication'] = true;
				break;

			case 'both':
			default:
				break;
		}
	} else {
		$elements['date_modification'] = true;
		$elements['date_publication'] = true;
	}
	?>

	<meta itemprop="mainEntityOfPage" content="<?php echo esc_url( get_permalink() ); ?>"/>

	<?php if ( $elements['date_publication'] ) : ?>
		<meta itemprop="datePublished"
		      content="<?php echo esc_attr( get_the_time( 'Y-m-d' ) . 'T' . get_the_time( 'H:i:s' ) ); ?>"/>
	<?php endif; ?>

	<?php if ( $elements['date_modification'] ) : ?>
		<meta itemprop="dateModified"
		      content="<?php echo esc_attr( get_the_modified_time( 'Y-m-d' ) . 'T' . get_the_modified_time( 'H:i:s' ) ); ?>"/>
	<?php endif; ?>

	<span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
		<meta itemprop="name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
		<meta itemprop="url" content="<?php echo esc_attr( home_url() ); ?>" />
		<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
			<meta itemprop="url" content="<?php echo esc_url( bimber_get_microdata_organization_logo_url() ); ?>" />
		</span>
	</span>
	<?php

	if ( ! bimber_show_entry_featured_media( $elements['featured_media'] ) || apply_filters( 'bimber_force_missing_image', false ) ) : ?>
		<span itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
			<?php $bimber_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );?>
			<meta itemprop="url" content="<?php echo esc_url( $bimber_image[0] ); ?>" />
			<meta itemprop="width" content="<?php echo intval( $bimber_image[1] ); ?>" />
			<meta itemprop="height" content="<?php echo intval( $bimber_image[2] ); ?>" />
		</span>
	<?php endif;
	if ( ! $elements['author'] ) : ?>
		<span class="entry-author" itemscope="" itemprop="author" itemtype="http://schema.org/Person">
			<meta itemprop="name" content="<?php echo esc_html( get_the_author() ); ?>" >
		</span>
	<?php endif;
}

/**
 * Render Bunchy style "Open List" badge"
 *
 * @return void
 */
function bimber_render_open_list_badge() {
	if ( bimber_can_use_plugin( 'snax/snax.php' ) ) :
		$show = 'bunchy' === bimber_get_current_stack();
		apply_filters( 'bimber_render_open_list_badge', $show );
		if ( snax_is_post_open_list( ) && $show ) : ?>
			<a class="entry-badge entry-badge-open-list" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Open list', 'bimber' ); ?></a>
		<?php endif;
	endif;
}

/**
 * Render next post button for auto loading
 */
function bimber_render_next_post_button() {
	$allowed_types = apply_filters( 'bimber_allowed_auto_load_post_types', array( 'post', 'snax_quiz', 'snax_poll' ) );

	if ( apply_filters( 'bimber_render_next_post_button', bimber_get_theme_option( 'posts', 'auto_load_enable' ) && is_singular( $allowed_types ) ) ) {
		$in_same_term   = (bool) bimber_get_theme_option( 'posts', 'auto_load_in_same_category' );
		$excluded_terms = apply_filters( 'bimber_next_post_excluded_terms', '' );
		$taxonomy       = apply_filters( 'bimber_next_post_taxonomy', 'category' );

		ob_start();
		previous_post_link(
			'<span class="bimber-load-next-post">%link<i class="g1-collection-more-spinner"></i></span>',
			esc_html__( 'Next post', 'bimber' ),
			$in_same_term,
			$excluded_terms,
			$taxonomy
		);
		$html = ob_get_clean();

		if ( preg_match( '/href=\"(.*)\"/U', $html, $output_array ) ) {
		    $url = apply_filters( 'bimber_next_post_analytics_url', $output_array[1] );
		    $attr = 'data-bimber-analytics-href="' . $url . '"';
		    $html = str_replace( 'href', $attr . ' href', $html );
		}

		echo $html;
	}
}

/**
 * Render URL waypoints for autoload
 */
function bimber_add_url_waypoint() {
	$allowed_types = apply_filters( 'bimber_allowed_auto_load_post_types', array('post', 'snax_quiz') );
	if ( apply_filters( 'bimber_add_url_waypoint', bimber_get_theme_option( 'posts', 'auto_load_enable' ) && is_singular( $allowed_types ) ) ) {
		$current_page = (int) get_query_var('page');
		$url = $current_page > 1 ? trailingslashit( get_the_permalink() ) . $current_page . '/' : get_the_permalink();
	    printf( '<span class="bimber-url-waypoint" data-bimber-post-title="%s" data-bimber-post-url="%s"></span>', get_the_title() ,esc_url( $url ) );
	}
}

/**
 * Fixes lack of link for featured section templates.
 */
function bimber_ensure_featured_section_link_when_no_thumbnail() {
	if ( ! has_post_thumbnail() ) {
		echo '<figure class="entry-featured-media "><a class="g1-frame" href="' . get_the_permalink() . '"></a></figure>';
	}
}
