<?php
/**
 * The template part for displaying quick navigation
 *
 * @package Bimber_Theme 4.10
 */

?>
<?php if ( bimber_show_quick_nav_menu() ) : ?>
	<?php
		$bimber_class = array(
			'g1-quick-nav',
		);

		$bimber_has_menu_items = false;

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

		$bimber_class[] = 'g1-quick-nav-long';

		if ( 'none' === bimber_get_theme_option( 'header', 'quicknav_labels' ) ) {
			$bimber_class[] = 'g1-quick-nav-without-labels';
		}
	?>

	<?php if ( $bimber_has_menu_items ) : ?>
		<nav class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
			<ul class="g1-quick-nav-menu">
				<?php if ( 'separate' === bimber_get_theme_option( 'posts', 'top_in_menu' ) ) : ?>
                    <?php if ( bimber_is_latest_page_enabled() ) : ?>
						<li class="menu-item menu-item-type-g1-latest <?php if ( bimber_is_latest_page() ) {
							echo sanitize_html_class( 'current-menu-item' ); } ?>">
							<a href="<?php echo esc_url( bimber_get_latest_page_url() ); ?>">
								<?php echo esc_html( bimber_get_latest_page_label() ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( bimber_is_popular_collection_enabled() ) : ?>
						<li class="menu-item menu-item-type-g1-popular <?php if ( bimber_is_popular_page() ) {
							echo sanitize_html_class( 'current-menu-item' ); } ?>">
							<a href="<?php echo esc_url( bimber_get_popular_page_url() ); ?>">
								<?php echo esc_html( bimber_get_popular_page_label() ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( bimber_is_hot_collection_enabled() ) : ?>
						<li class="menu-item menu-item-type-g1-hot <?php if ( bimber_is_hot_page() ) {
							echo sanitize_html_class( 'current-menu-item' ); } ?>">
							<a href="<?php echo esc_url( bimber_get_hot_page_url() ); ?>">
								<?php echo esc_html( bimber_get_hot_page_label() ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( bimber_is_trending_collection_enabled() ) : ?>
						<li class="menu-item menu-item-type-g1-trending <?php if ( bimber_is_trending_page() ) {
							echo sanitize_html_class( 'current-menu-item' ); } ?>">
							<a href="<?php echo esc_url( bimber_get_trending_page_url() ); ?>">
								<?php echo esc_html( bimber_get_trending_page_label() ); ?>
							</a>
						</li>
					<?php endif; ?>

				<?php endif; ?>
			</ul>
		</nav>
	<?php endif; ?>
<?php endif;
