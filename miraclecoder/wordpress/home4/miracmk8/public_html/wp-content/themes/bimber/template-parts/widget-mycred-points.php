<?php
/**
 * The template for MyCred widget.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.4
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$user_id = get_query_var( 'bimber_mycred_widget_user_id' );

// Get Total.
$total = mycred_display_users_balance( $user_id );

// Get Today.
$log = new myCRED_Query_Log( array(
	'user_id' => array( bp_displayed_user_id() ),
	'time' => array(
		'dates'   => array( strtotime( 'today' ), time() ),
		'compare' => 'BETWEEN',
	),
) );
$today = 0;
if ( $log->have_entries() ) {
	foreach ( $log->results as $entry ) {
		if ( MYCRED_DEFAULT_TYPE_KEY === $entry->ctype ) {
			$today += $entry->creds;
		}
	}
}

// Get Best.
$best = 0;
$best_data = $wpdb->get_results( $wpdb->prepare(
	'
	SELECT SUM(creds) as total_points
	FROM ' . mycred()->log_table . '
	WHERE user_id = %s
	AND ctype = %s
	GROUP BY DATE(FROM_UNIXTIME(time))
	ORDER BY total_points DESC
	LIMIT 1;
	',
	$user_id,
	MYCRED_DEFAULT_TYPE_KEY
) );
if ( ! empty( $best_data ) ) {
	$best = $best_data[0]->total_points;
}
?>

<div id="<?php echo esc_attr( $bimber_mycred_widget_id ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_mycred_widget_classes ) ); ?>">
	<div class="bimber-mycred-widget-counter bimber-mycred-widget-total">
		<div class="bimber-mycred-widget-counter-value g1-giga g1-giga-1st"><?php echo esc_html( $total );?></div>
		<div class="bimber-mycred-widget-counter-title g1-gamma g1-gamma-3rd"><?php esc_html_e( 'Total', 'bimber' ); ?></div>
	</div>
	<div class="bimber-mycred-widget-counter bimber-mycred-widget-today">
		<div class="bimber-mycred-widget-counter-value g1-alpha g1-alpha-1st"><?php echo esc_html( $today );?></div>
		<div class="bimber-mycred-widget-counter-title g1-zeta g1-zeta-3rd"><?php esc_html_e( 'Today', 'bimber' ); ?></div>
	</div>
	<div class="bimber-mycred-widget-counter bimber-mycred-widget-best">
		<div class="bimber-mycred-widget-counter-value g1-alpha g1-alpha-1st"><?php echo esc_html( $best );?></div>
		<div class="bimber-mycred-widget-counter-title g1-zeta g1-zeta-3rd"><?php esc_html_e( 'Best', 'bimber' ); ?></div>
	</div>
	<?php if ( $bimber_mycred_widget_view_all_url ) : ?>
		<p class="g1-more-results">
			<a class="g1-link g1-link-s g1-link-right" href="<?php echo esc_url( $bimber_mycred_widget_view_all_url ); ?>"><?php esc_html_e( 'View all points', 'bimber' ); ?></a>
		</p>
	<?php endif; ?>
</div>
