<?php
/**
 * Header Builder template
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
?>
<?php
	$bimber_class = array(
		'g1-quick-nav',
	);
	$bimber_has_menu_items = false;
	// Get reactions.
	$wyr_terms = array();
	if ( apply_filters( 'bimber_show_reactions_in_header', true ) && bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php') ) {
		$wyr_terms = wyr_get_reactions();
		// Make sure it's always an array.
		$wyr_terms = is_wp_error( $wyr_terms ) ? array() : $wyr_terms;
		if ( count( $wyr_terms ) ) {
			$bimber_has_menu_items = true;
		}
	}
	if ( 'separate' === bimber_get_theme_option( 'posts', 'top_in_menu' ) ) {
		if (
			strlen( bimber_get_latest_page_url() ) ||
			bimber_is_popular_collection_enabled() ||
			bimber_is_hot_collection_enabled() ||
			bimber_is_trending_collection_enabled()
		) {
			$bimber_has_menu_items = true;
		}
	}
	$bimber_class[] = count( $wyr_terms ) ? 'g1-quick-nav-long' : 'g1-quick-nav-short';
	if ( 'none' === bimber_get_theme_option( 'header', 'quicknav_labels' ) ) {
		$bimber_class[] = 'g1-quick-nav-without-labels';
	}
?>
<?php if ( $bimber_has_menu_items ) : ?>
	<nav class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
		<ul class="g1-quick-nav-menu">
			<?php foreach( $wyr_terms as $wyr_term ) :
				$term_link = is_wp_error( get_term_link( $wyr_term ) ) ? '#' : get_term_link( $wyr_term );
				?>
				<li class="menu-item">
					<a href="<?php echo esc_url( $term_link );?>">
						<?php wyr_render_reaction_icon( $wyr_term->term_id ); ?>
						<?php echo $wyr_term->name; ?>
					</a>
				</li>
			<?php endforeach; ?>
			<?php if ( 'separate' === bimber_get_theme_option( 'posts', 'top_in_menu' ) ) : ?>
				<?php if ( bimber_is_latest_page_enabled() ) : ?>
					<li class="menu-item menu-item-type-g1-latest <?php if ( bimber_is_latest_page() ) {
						echo sanitize_html_class( 'current-menu-item' ); } ?>">
						<a href="<?php echo esc_url( bimber_get_latest_page_url() ); ?>">
							<span class="entry-flag entry-flag-latest"></span>
							<?php echo esc_html( bimber_get_latest_page_label() ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( bimber_is_popular_collection_enabled() ) : ?>
					<li class="menu-item menu-item-type-g1-popular <?php if ( bimber_is_popular_page() ) {
						echo sanitize_html_class( 'current-menu-item' ); } ?>">
						<a href="<?php echo esc_url( bimber_get_popular_page_url() ); ?>">
							<span class="entry-flag entry-flag-popular"></span>
							<?php echo esc_html( bimber_get_popular_page_label() ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( bimber_is_hot_collection_enabled() ) : ?>
					<li class="menu-item menu-item-type-g1-hot <?php if ( bimber_is_hot_page() ) {
						echo sanitize_html_class( 'current-menu-item' ); } ?>">
						<a href="<?php echo esc_url( bimber_get_hot_page_url() ); ?>">
							<span class="entry-flag entry-flag-hot"></span>
							<?php echo esc_html( bimber_get_hot_page_label() ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( bimber_is_trending_collection_enabled() ) : ?>
					<li class="menu-item menu-item-type-g1-trending <?php if ( bimber_is_trending_page() ) {
						echo sanitize_html_class( 'current-menu-item' ); } ?>">
						<a href="<?php echo esc_url( bimber_get_trending_page_url() ); ?>">
							<span class="entry-flag entry-flag-trending"></span>
							<?php echo esc_html( bimber_get_trending_page_label() ); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	</nav>
<?php endif; ?>
