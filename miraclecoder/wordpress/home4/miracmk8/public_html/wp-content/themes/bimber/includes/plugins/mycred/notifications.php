<?php
/**
 * MyCred plugin notifications extension
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
add_filter( 'mycred_badge_user_value', 'bimber_mycred_add_badge_notice', 10, 3 );
add_action( 'mycred_user_got_demoted', 'bimber_mycred_rank_demotion_notice', 10, 3 );
add_action( 'mycred_user_got_promoted', 'bimber_mycred_rank_promotion_notice', 10, 3 );

/**
 * Add notice for badge assignement.
 *
 * @param int $level    Badge level.
 * @param int $user_id  User id.
 * @param int $badge_id Badge id.
 */
function bimber_mycred_add_badge_notice( $level, $user_id, $badge_id ) {
	if ( ! function_exists( 'mycred_add_new_notice' ) ) {
		return;
	}
	$badge = mycred_get_badge( $badge_id, $level );

	$badge_image_id = null;
	if (  isset( $badge->levels[$badge->current_level] ) ) {
		$badge_image_id = $badge->levels[$badge->current_level]['attachment_id'];
	}

	$data = array(
		'notice_type' 	=> 'badge',
		'badge' 		=> $badge_id,
		'badge_name' 	=> $badge->title,
		'logo_id' 		=> $badge_image_id,
		'level'			=> $level,
		'levels_count'	=> count($badge->levels),
	);
	$message = 'BIMBER_JSON:' . json_encode( $data );
	$args = array(
		'user_id' => $user_id,
		'message' => $message,
	);

	mycred_add_new_notice( $args );
	return $level;
}

/**
 * Add notice for user demotion.
 *
 * @param int $user_id User id.
 * @param int $rank_id Rank id.
 * @param int $results Points total.
 */
function bimber_mycred_rank_demotion_notice( $user_id, $rank_id, $results ) {
	bimber_mycred_rank_change_notice( $user_id, $rank_id, $results, 'demotion' );
}

/**
 * Add notice for user demotion.
 *
 * @param int $user_id User id.
 * @param int $rank_id Rank id.
 * @param int $results Points total.
 */
function bimber_mycred_rank_promotion_notice( $user_id, $rank_id, $results ) {
	bimber_mycred_rank_change_notice( $user_id, $rank_id, $results, 'promotion' );
}

/**
 * Add notice for rank change.
 *
 * @param int    $user_id User id.
 * @param int    $rank_id Rank id.
 * @param int    $results Points total.
 * @param string $type "promotion" or "demotion".
 */
function bimber_mycred_rank_change_notice( $user_id, $rank_id, $results, $type ) {
	if ( ! function_exists( 'mycred_add_new_notice' ) ) {
		return;
	}
	$rank = mycred_get_rank( $rank_id );
	$rank_name = $rank->title;
	$logo_id = $rank->logo_id;
	$data = array(
		'notice_type' 	=> 'rank',
		'rank' 			=> $rank,
		'rank_name' 	=> $rank_name,
		'logo_id' 		=> $logo_id,
		'direction'		=> $type,
	);
	$message = 'BIMBER_JSON:' . json_encode( $data , JSON_UNESCAPED_SLASHES );
	$args = array(
		'user_id' => $user_id,
		'message' => $message,
	);
	mycred_add_new_notice( $args );
}

add_filter( 'mycred_notifications', 'bimber_mycred_override_notifications', 9999, 1 );

/**
 * Override the default notification render.
 *
 * @param array $notifications Notifications.
 * @return array
 */
function bimber_mycred_override_notifications( $notifications ) {
	if ( empty( $notifications ) ) {
		return $notifications;
	}
	$standard_notifications = array();
	$special_notifications = array();
	foreach ( $notifications as $notification ) {
		if ( strpos( $notification, 'BIMBER_JSON:' ) > -1 ) {
			$notification = str_replace( 'BIMBER_JSON:', '', $notification );
			$data = json_decode( stripslashes( $notification ), true );
			$special_notifications[] = $data;
		} else {
			$standard_notifications[] = $notification;
		}
	}
	?>
	<div class="bimber-mycred-notifications">
	<?php
	if ( ! empty( $special_notifications ) ) {
		foreach ( $special_notifications as $notification ) {
			set_query_var( 'bimber_mycred_special_notification', $notification );
			get_template_part( 'template-parts/mycred/notification-' . $notification['notice_type'] );
		}
	}
	if ( ! empty( $standard_notifications ) ) {
		$settings = get_option( 'mycred_pref_core' );
		set_query_var( 'bimber_mycred_standard_notifications', $standard_notifications );
		if ( isset( $settings['notifications']['duration'] ) && $settings['notifications']['duration'] > 0 ) {
			set_query_var( 'bimber_mycred_standard_notifications_duration', $settings['notifications']['duration'] );
		}
		get_template_part( 'template-parts/mycred/notifications' );
	}
	?>
	</div>
	<?php
	return array();
}

