<?php
/**
 * Latest Reactions BuddyPress
 *
 * @package whats-your-reaction
 * @subpackage Widgets
 */
$wyr_page = isset( $_GET['wyr_page'] ) ? $_GET['wyr_page'] : 1;
?>
<p>
	<a href="<?php echo esc_url( add_query_arg( 'wyr_page', rawurlencode( $wyr_page + 1 ) ) );?>" class="g1-button g1-button-m g1-button-subtle g1-button-wide wyr-load-more"><?php esc_html_e( 'Load More', 'wyr' ); ?></a>
</p>
