<?php
/**
 * MyCred plugin shortcodes
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
 * Replacement for mycred_render_rank_list shortcode
 *
 * @param array  $atts  		Shortcode atts.
 * @param string $row_template  Shortcode content.
 * @return string
 */
function bimber_mycred_render_rank_list( $atts, $row_template = null ) {
	$atts = shortcode_atts( array(
		'order' => 'DESC',
		'ctype' => MYCRED_DEFAULT_TYPE_KEY,
		'wrap'  => 'div',
	), $atts );
	extract( $atts );
	$output    = '';
	$all_ranks = mycred_get_ranks( 'publish', '-1', $order, $ctype );
	if ( ! empty( $all_ranks ) ) {
		$all_ranks = array_reverse( $all_ranks );
		if ( $row_template === NULL || empty( $row_template ) ) {
			$wrap = 'ul';
			$row_template = '<li>';
			$row_template .= '%rank_logo%';
			$row_template .= '<span><h3 class="g1-gamma">%rank%</h3>';
			$row_template .= '<span class="g1-meta g1-mycred-ranks-count"></span></span>';
			$row_template .= '<span class="g1-gamma g1-gamma-3rd g1-mycred-ranks-range">%min% - %max% ' . __( 'pts', 'bimber' ) . '</span>';
			$row_template .= '</li>';
		}
		if ( $wrap != '' ) {
			$output .= '<' . $wrap . ' class="mycred-rank-list">';
		}
		foreach ( $all_ranks as $rank ) {
			$mycred  = mycred( $rank->point_type );
			$row     = apply_filters( 'mycred_rank_list', $row_template, $atts, $mycred );
			$row     = str_replace( '%rank%',             $rank->title, $row );
			$row     = str_replace( '%rank_logo%',        mycred_get_rank_logo( $rank->post_id ), $row );
			$row     = str_replace( '%min%',              $mycred->format_creds( $rank->minimum ), $row );
			$row     = str_replace( '%max%',              $mycred->format_creds( $rank->maximum ), $row );
			$row     = str_replace( '%count%',            $rank->count, $row );
			$row     = $mycred->template_tags_general( $row );
			$output .= $row;
		}
		if ( $wrap != '' ) {
			$output .= '</' . $wrap . '>';
		}
	}
	return $output;
}

/**
 * Replacement for mycred_badges shortcode
 */
function bimber_mycred_render_badge_list() {
	$all_badges = mycred_get_badge_ids();
	ob_start();
		$columns_class = 'g1-collection-columns-3';
	?>
	<div class="bimber-badges-shortcode">
		<div class="g1-collection <?php echo sanitize_html_class( $columns_class )?>">
			<div class="g1-collection-viewport">
				<ul class="g1-collection-items">
					<?php
					if ( ! empty( $all_badges ) ) {
						usort( $all_badges, 'bimber_mycred_sort_badges_by_order' );
						foreach ( $all_badges as $badge_id ) {
							$badge  = mycred_get_badge( $badge_id, 0 );
							$image = preg_replace( '/width=\".*\"/U', '', $badge->level_image );
							$image = preg_replace( '/height=\".*\"/U', '', $image );
							$requirements = mycred_display_badge_requirements( $badge_id );
							$requirements = preg_replace("/\(.*\)/U", "", $requirements );
							$excerpt =  get_post_field( 'post_excerpt', $badge_id );
							$class = '';

							$level_str = __( 'Level %s', 'mycred' );
							$level_str = trim( str_replace( '%s', '', $level_str ) );

							if ( substr_count( $requirements, '<strong>' . $level_str ) < 2 ) {
								$class = 'bimber-badges-badge-single-level';
							}
							$requirements = str_replace( '<strong', '<strong class="g1-zeta g1-zeta-1st"', $requirements )
							?>
							<li class="g1-collection-item g1-collection-item-1of3 <?php echo sanitize_html_class( $class )?>">
								<div class="bimber-badges-badge">
								<div class="bimber-badges-image"><?php echo wp_kses_post( $image );?></div>
								<h3  class="bimber-badges-title"><?php echo esc_html( $badge->title );?></h3>
								<p class="g1-meta"><?php esc_html_e( 'Users with badge: ', 'bimber' ); ?><?php echo esc_html( $badge->earnedby );?></p>
								<div class="bimber-badges-requirements">
									<?php
									if ( empty( $excerpt ) ) {
										echo wp_kses_post( $requirements );
									} else {
										echo wp_kses_post( $excerpt );
									} ?>
								</div>
								</div>
							</li>
							<?php
						}
					}?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	$output = ob_get_clean();
	return apply_filters( 'mycred_badges', $output );
}

/**
 * Replacement for mycred_my_badges shortcode
 */
function bimber_mycred_render_my_badge_list( $atts, $content = '' ) {
	$all_badges = mycred_get_badge_ids();
	$atts = shortcode_atts( array(
		'show'     => 'earned',
		'user_id'  => 'current',
	), $atts );
	$user_id = mycred_get_user_id( $atts['user_id'] );
	$users_badges = mycred_get_users_badges( $user_id );
	ob_start();
		$columns_class = 'g1-collection-columns-3';
	?>
	<div class="bimber-badges-shortcode">
		<div class="g1-collection <?php echo sanitize_html_class( $columns_class )?>">
			<div class="g1-collection-viewport">
				<ul class="g1-collection-items">
					<?php
					if ( ! empty( $all_badges ) ) {
						usort( $all_badges, 'bimber_mycred_sort_badges_by_order' );
						$earned = array();
						$locked = array();
						foreach ( $all_badges as $badge_id ) {
							if ( array_key_exists( $badge_id, $users_badges ) ) {
								$earned[] = $badge_id;
							} else {
								$locked[] = $badge_id;
							}
						}
						$all_badges = array_merge( $earned, $locked );
						$all_badges = apply_filters( 'bimber_mycred_filter_my_badges_ids', $all_badges );
						foreach ( $all_badges as $badge_id ) {
							$level = false;
							if ( array_key_exists( $badge_id, $users_badges ) ) {
								$level = $users_badges[ $badge_id ];
							}
							$badge  = mycred_get_badge( $badge_id, $level );
							if ( false !== $level ) {
								$image = preg_replace( '/width=\".*\"/U', '', $badge->level_image );
							} else {
								$image = preg_replace( '/width=\".*\"/U', '', $badge->main_image );
							}
							$image = preg_replace( '/height=\".*\"/U', '', $image );
							$requirements = mycred_display_badge_requirements( $badge_id );
							$requirements = preg_replace("/\(.*\)/U", "", $requirements );
							$excerpt =  get_post_field( 'post_excerpt', $badge_id );
							$class = '';

							$level_str = __( 'Level %s', 'mycred' );
							$level_str = trim( str_replace( '%s', '', $level_str ) );

							if ( substr_count( $requirements, '<strong>' . $level_str ) < 2 ) {
								$class = 'bimber-badges-badge-single-level';
							}
							$requirements = str_replace( '<strong', '<strong class="g1-zeta g1-zeta-1st"', $requirements )
							?>
							<li class="g1-collection-item g1-collection-item-1of3 <?php echo sanitize_html_class( $class )?>">
								<div class="bimber-badges-badge">
								<div class="bimber-badges-image"><?php echo wp_kses_post( $image );?></div>
								<h3  class="bimber-badges-title"><?php echo esc_html( $badge->title );?></h3>
								<?php
								if ( false !== $level ) {
									?><p class="g1-meta"><?php esc_html_e( 'Level: ', 'bimber' ); ?><?php echo esc_html( $level + 1 );?></p><?php
								} else {
									?><p class="g1-meta"><?php esc_html_e( 'Locked', 'bimber' ); ?></p><?php
								}
								?>
								<div class="bimber-badges-requirements">
									<?php
									if ( empty( $excerpt ) ) {
										echo wp_kses_post( $requirements );
									} else {
										echo wp_kses_post( $excerpt );
									} ?>
								</div>
								</div>
							</li>
							<?php
						}
					}?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	$output = ob_get_clean();
	return apply_filters( 'mycred_my_badges', $output );
}

/**
 * Sort badge ids by order.
 *
 * @param int $a A.
 * @param int $b	B.
 * @return int
 */
function bimber_mycred_sort_badges_by_order( $a, $b ) {
	$a = get_post_field( 'menu_order', $a );
	$b = get_post_field( 'menu_order', $b );
	if ( $a == $b ) {
		return 0;
	}
	return ( $a < $b ) ? -1 : 1;
}

add_action( 'wp_loaded', 'bimber_mycred_setup_shortcodes' );
/**
 * Setup shortcodes with replacements
 */
function bimber_mycred_setup_shortcodes() {
	remove_shortcode( 'mycred_list_ranks' );
	remove_shortcode( 'mycred_badges' );
	remove_shortcode( 'mycred_my_badges' );
	if ( bimber_mycred_is_addon_enabled( 'ranks' ) ) {
		add_shortcode( 'mycred_list_ranks',  'bimber_mycred_render_rank_list' );
	}
	if ( bimber_mycred_is_addon_enabled( 'badges' ) ) {
		add_shortcode( 'mycred_badges',      'bimber_mycred_render_badge_list' );
		add_shortcode( 'mycred_my_badges',      'bimber_mycred_render_my_badge_list' );
	}
}
