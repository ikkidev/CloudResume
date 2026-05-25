<?php
/**
 * The template part for displaying user navigation
 *
 * @package Bimber_Theme 5.3
 */

?>
<?php if ( bimber_can_use_plugin( 'snax/snax.php' ) ) : ?>
	<?php if ( snax_show_create_button() ): ?>
		<?php
		$snax_class = array(
			'g1-button',
			'g1-button-m',
			'g1-button-solid',
			'snax-button',
			'snax-button-create',
		);
		$snax_url_prefix = snax_get_url_var_prefix();
		if ( $snax_url_prefix ) {
			$snax_url_prefix .= '_';
		}
		$url = snax_get_frontend_submission_page_url();
		$url_type = bimber_get_theme_option( 'snax', 'header_create_button_type' );
		if ( 'ranked_list' === $url_type ) {
			$url_type = 'list&type=ranked';
		}
		if ( 'list' === $url_type ) {
			$url_type = 'list';
		}
		if ( 'classic_list' === $url_type ) {
			$url_type = 'list&type=classic';
		}
		if ( 'all' !== $url_type && 'all_dropdown' !== $url_type ) {
			if ( strpos( $url, '?' ) !== false ) {
				$url .= '&' . $snax_url_prefix . 'format=' . $url_type;
			} else {
				$url .= '?' . $snax_url_prefix . 'format=' . $url_type;
			}
		}
		if ( 'all_dropdown' === $url_type ) {
			$snax_class[] = 'snax-button-create-dropdown';
		}

		if ( 'all_dropdown' !== $url_type ) :
		?>
		<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>"
		   href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( bimber_get_theme_option( 'snax', 'header_create_button_label' ) ); ?></a>
		<?php else :
			$snax_class[] = 'g1-drop-toggle';
			$snax_format_limit = apply_filters( 'bimber_snax_dropdown_formats_limit', 3 );
			$count = 0;
			?>
			<div class="g1-drop g1-drop-before g1-drop-create">
			<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>"
		   		href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( bimber_get_theme_option( 'snax', 'header_create_button_label' ) ); ?>
					<span class="g1-drop-toggle-arrow"></span>
					</a>
				<div class="g1-drop-content snax">
					<?php foreach ( snax_get_active_formats() as $snax_format_id => $snax_format_args ) :
						$url_type = $snax_format_id;
						if ( 'ranked_list' === $snax_format_id ) {
							$url_type = 'list&type=ranked';
						}
						if ( 'list' === $snax_format_id ) {
							$url_type = 'list';
						}
						if ( 'classic_list' === $snax_format_id ) {
							$url_type = 'list&type=classic';
						}
						if ( 'all' !== $url_type && 'all_dropdown' !== $url_type ) {
							if ( strpos( $url, '?' ) !== false ) {
								$suffix = '&' . $snax_url_prefix . 'format=' . $url_type;
							} else {
								$suffix = '?' . $snax_url_prefix . 'format=' . $url_type;
							}
						}
						?>
						<a href="<?php echo esc_url( $url . $suffix ); ?>" class="snax-format-<?php echo sanitize_html_class( $snax_format_id ); ?>">
							<i class="snax-format-icon"></i>
							<span class="g1-epsilon g1-epsilon-2nd"><?php echo esc_html( $snax_format_args['labels']['name'] ); ?></span>
						</a>
					<?php
					$count += 1;
					if ( $count >= $snax_format_limit ) {
						break;
					}
					endforeach; ?>
					<?php if ( $snax_format_limit < snax_get_format_count() ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="bimber-snax-dropdown-view-all g1-link g1-link-right">
							<?php echo esc_html__( 'View all formats', 'bimber' ); ?>
						</a>
					<?php endif;?>
				</div>
			</div>
		<?php endif; ?>

	<?php endif; ?>
<?php endif; ?>
