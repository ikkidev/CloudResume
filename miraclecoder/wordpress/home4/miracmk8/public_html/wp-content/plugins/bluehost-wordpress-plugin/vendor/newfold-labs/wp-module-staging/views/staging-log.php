<?php
/**
 * View: layout for the log page on admin side.
 *
 * @package wp-module-staging
 */

$staging_config = $instance->getConfig();
?>
<div class="wrap">
	<h1><?php echo esc_html__( 'Log Staging', 'wp-module-staging' ); ?></h1>
	<div style="margin-top: 1em; margin-bottom: 2em; padding: 1em; background: #fff; border-left: 4px solid #2271b1; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
		<ul style="list-style: none; padding: 0; margin: 0;">
			<li><strong><?php echo esc_html__( 'Production DIR:', 'wp-module-staging' ); ?></strong> <span class="prod-dir"><?php echo esc_html( $instance->getProductionDir() ); ?></span></li>
			<li><strong><?php echo esc_html__( 'Production URL:', 'wp-module-staging' ); ?></strong> <span class="prod-url"><?php echo esc_html( $instance->getProductionUrl() ); ?></span></li>
			<li><strong><?php echo esc_html__( 'Staging DIR:', 'wp-module-staging' ); ?></strong> <span class="staging-dir"><?php echo esc_html( $instance->getStagingDir() ); ?></span></li>
			<li><strong><?php echo esc_html__( 'Staging URL:', 'wp-module-staging' ); ?></strong> <span class="staging-url"><?php echo esc_html( $instance->getStagingUrl() ); ?></span></li>
			<li><strong><?php echo esc_html__( 'Staging environment:', 'wp-module-staging' ); ?></strong> <span class="staging-env"><?php echo esc_html( $instance->getEnvironment() ); ?></span></li>
		</ul>
	</div>
	<form method="get" style="margin-bottom: 1em;">
		<?php wp_nonce_field( 'nfd_staging_log_filter', 'nfd_staging_log_nonce' ); ?>
		<input type="hidden" name="page" value="nfd-staging-log" />
		<label for="log_date"><?php echo esc_html__( 'Filter by date (YYYY-MM-DD):', 'wp-module-staging' ); ?></label>
		<input type="date" id="log_date" name="log_date" value="<?php echo esc_attr( $filter_date ); ?>" /> 
		<label for="per_page" style="margin-left:1em;"><?php echo esc_html__( 'Items per page:', 'wp-module-staging' ); ?></label>
		<select id="per_page" name="per_page">
			<?php foreach ( array( 10, 20, 30, 50, 100 ) as $opt ) : ?>
				<option value="<?php echo esc_html( $opt ); ?>"<?php selected( $per_page, $opt ); ?>><?php echo esc_html( $opt ); ?></option>
			<?php endforeach; ?>
		</select> 
		<input type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Save', 'wp-module-staging' ); ?>" />
		<button type="button" class="button" style="margin-left:8px;" onclick="window.location.href='admin.php?page=nfd-staging-log'">
			<?php echo esc_html__( 'Reset', 'wp-module-staging' ); ?>
		</button>
	</form>

	<?php if ( ! empty( $logs_to_show ) ) : ?>
		<table class="widefat fixed striped"><thead><tr><th><?php echo esc_html__( 'Log', 'wp-module-staging' ); ?></th></tr></thead><tbody>
		<?php foreach ( $logs_to_show as $log ) : ?>
			<tr><td style="font-family:monospace;"><?php echo esc_html( $log ); ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>

		<?php
		if ( $total_pages > 1 ) :
			?>
			<div class="tablenav">
				<div class="tablenav-pages" style="margin: 16px 0; font-size: 1.15em;">
					<?php
					$base_url = remove_query_arg( array( 'paged' ) );

					if ( $page > 1 ) :
						$prev_url = add_query_arg(
							array(
								'paged'    => $page - 1,
								'per_page' => $per_page,
								'log_date' => $filter_date,
							),
							$base_url
						);
						?>
						<a href="<?php echo esc_url( $prev_url ); ?>" style="margin-right:8px;padding:6px 14px;border-radius:4px;background:#f1f1f1;text-decoration:none;">&laquo; <?php echo esc_html__( 'Prev', 'wp-module-staging' ); ?></a>
					<?php else : ?>
						<span style="margin-right:8px;padding:6px 14px;border-radius:4px;background:#e2e2e2;color:#aaa;">&laquo; <?php echo esc_html__( 'Prev', 'wp-module-staging' ); ?></span>
					<?php endif; ?>

					<?php
					$pages_to_show = array();

					if ( $total_pages <= 5 ) {
						$pages_to_show = range( 1, $total_pages );
					} else {
						$pages_to_show = array( 1, 2 );

						if ( $page > 3 ) {
							$pages_to_show[] = $page - 1;
						}
						if ( $page > 2 && $page < $total_pages - 1 ) {
							$pages_to_show[] = $page;
						}
						if ( $page < $total_pages - 2 ) {
							$pages_to_show[] = $page + 1;
						}

						$pages_to_show[] = $total_pages - 1;
						$pages_to_show[] = $total_pages;

						$pages_to_show = array_unique( $pages_to_show );
						sort( $pages_to_show );
					}

					$last_page = 0;
					foreach ( $pages_to_show as $p ) :
						if ( $p > $last_page + 1 ) {
							// Aggiungi puntini di sospensione
							echo '<span style="margin:0 4px;">...</span>';
						}

						$url = add_query_arg(
							array(
								'paged'    => $p,
								'per_page' => $per_page,
								'log_date' => $filter_date,
							),
							$base_url
						);

						if ( $p === $page ) :
							?>
							<span style="font-weight:bold;margin:0 4px;padding:6px 14px;border-radius:4px;background:#2271b1;color:#fff;"><?php echo esc_html( $p ); ?></span>
						<?php else : ?>
							<a href="<?php echo esc_url( $url ); ?>" style="margin:0 4px;padding:6px 14px;border-radius:4px;background:#f1f1f1;text-decoration:none;"><?php echo esc_html( $p ); ?></a>
							<?php
						endif;

						$last_page = $p;
					endforeach;
					?>

					<?php
					if ( $page < $total_pages ) :
						$next_url = add_query_arg(
							array(
								'paged'    => $page + 1,
								'per_page' => $per_page,
								'log_date' => $filter_date,
							),
							$base_url
						);
						?>
						<a href="<?php echo esc_url( $next_url ); ?>" style="margin-left:8px;padding:6px 14px;border-radius:4px;background:#f1f1f1;text-decoration:none;"><?php echo esc_html__( 'Next', 'wp-module-staging' ); ?> &raquo;</a>
					<?php else : ?>
						<span style="margin-left:8px;padding:6px 14px;border-radius:4px;background:#e2e2e2;color:#aaa;"><?php echo esc_html__( 'Next', 'wp-module-staging' ); ?> &raquo;</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<p><?php echo esc_html__( 'No log found for selected date.', 'wp-module-staging' ); ?></p>
	<?php endif; ?>
</div> 