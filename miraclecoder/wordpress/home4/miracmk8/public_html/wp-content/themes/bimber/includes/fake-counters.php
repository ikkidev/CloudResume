<?php
/**
 * Fake counters
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
 * Fake post view count
 *
 * @param int $view_count		Real view count.
 *
 * @return int
 */
function bimber_fake_view_count( $view_count ) {
	$post = get_post();

	if ( empty( $post ) ) {
		return $view_count;
	}

	// Get value defined for that single post (can be a number or empty string).
	$fake_count = get_post_meta( $post->ID, '_bimber_fake_view_count', true );

	// If user has not defined the counter explicitly, calculate it based on global setup.
	if ( '' === $fake_count ) {
		$fake_base = (int) bimber_get_theme_option( 'posts', 'fake_view_count_base' );

		// Only if fake base is set, we can apply fake count.
		if ( $fake_base > 0 ) {
			$fake_factor = bimber_get_fake_factor( $post->post_date );

			$title_lenght = strlen( $post->post_title );
			srand( $title_lenght );
			$per_post_factor = 1 + ( rand( 0,10 ) / 10 );

			$fake_count = round( $fake_base * $fake_factor * $per_post_factor );
		}
	}

	// Cast to int. It's safe only here.
	$fake_count = (int) $fake_count;

	$fake_count = (int) apply_filters( 'bimber_fake_view_count', $fake_count, $post->ID );

	return $view_count + $fake_count;
}

/**
 * Return fake factor based on post creation date
 *
 * @param string $date			Post creation date.
 *
 * @return float
 */
function bimber_get_fake_factor( $date ) {
	$current_time = time();
	$date_time 	  = strtotime( $date );

	$day_in_seconds = 24 * 60 * 60;

	$days_diff = round( abs( $current_time - $date_time ) / $day_in_seconds );

	$t = $days_diff;	// Current time.
	$b = 0.1;			// Start value.
	$c = 0.9;			// Change in value.
	$d = 30;			// Duration.

	// Factor function doesn't return value equal to 1 after $d time.
	// Which is normal, as it's sinus, but we want to have 1 value after $d duration.
	if ( $days_diff > $d ) {
		return 1;
	}

	// EaseOutSine.
	$factor = $c * sin( $t / $d * (pi() / 2 ) ) + $b;

	return $factor;
}

