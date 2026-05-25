<?php
/**
 * The Template Part for displaying post navigation in AMP.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.5
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

?>
<nav class="g1-nav-single">
	<div class="g1-nav-single-inner">
		<p class="g1-single-nav-label"><?php esc_html_e( 'See more', 'bimber' ); ?></p>
		<?php
		$previous_post = get_previous_post();
		$next_post = get_next_post();
		?>
		<ul class="g1-nav-single-links">
			<li class="g1-nav-single-prev">
				<?php if ( $previous_post ) :?>
					<a href=<?php echo esc_url_raw( amp_get_permalink( $previous_post->ID ) );?>>
					<strong><?php echo esc_html__( 'Previous article', 'bimber' )?></strong> 
					<span class="g1-gamma g1-gamma-1st"><?php echo esc_html( $previous_post->post_title );?></span>
					</a>
				<?php endif;?>
			</li>
			<li class="g1-nav-single-next">
				<?php if ( $next_post ) :?>
					<a href=<?php echo esc_url_raw( amp_get_permalink( $next_post->ID ) );?>>
					<strong><?php echo esc_html__( 'Next article', 'bimber' )?></strong> 
					<span class="g1-gamma g1-gamma-1st"><?php echo esc_html( $next_post->post_title );?></span>
					</a>
				<?php endif;?>
			</li>
		</ul>
	</div>
</nav>
