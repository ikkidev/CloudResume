<?php
/**
 * The Template for displaying categories.
 *
 * @package Bimber_Theme 6.5
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$bimber_data = bimber_get_template_part_data();
$bimber_terms = $bimber_data['terms'];
$bimber_elements = $bimber_data['elements'];
$bimber_more = $bimber_data['more'];
$bimber_more_url = $bimber_data['more_url'];

$bimber_count = count( $bimber_terms );
?>
<div class="g1-terms g1-terms-tpl-icongrid">
	<ul class="g1-terms-items">
		<?php
		$bimber_class = array(
			'g1-term',
			'g1-term-tpl-icongrid',
		);
		?>
		<?php foreach ( $bimber_terms as $bimber_i => $bimber_term ) : ?>
			<?php if ( $bimber_i === $bimber_count - 1 && strlen( $bimber_more_url ) && 0 < $bimber_more ) : ?>
				<li class="g1-terms-item g1-terms-item-more">
					<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>" href="<?php echo esc_url( $bimber_more_url ); ?>">
						<div class="g1-term-icon">
						</div>
						<div class="g1-term-body">
							<h4 class="g1-term-title"><?php esc_html_e( 'View All', 'bimber' ); ?></h4>
						</div>
					</a>
				</li>
			<?php else : ?>
				<li class="g1-terms-item">
					<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>" href="<?php echo esc_url( get_term_link( $bimber_term ) ); ?>">
						<?php if ( $bimber_elements['icon'] ) : ?>
							<?php bimber_render_term_icon( $bimber_term ); ?>
						<?php endif; ?>
						<div class="g1-term-body">
							<h4 class="g1-term-title"><?php echo $bimber_term->name; ?></h4>

							<?php if ( $bimber_elements['count'] ) : ?>
								<p class="g1-meta">
									<span class="g1-term-count"><?php
									printf(
										str_replace(
											'%d',
											'<strong>%d</strong>',
											esc_html( _n( '%d entry', '%d entries', $bimber_term->count, 'bimber' ) )
										),
										$bimber_term->count
									);
									?></span>
								</p>
							<?php endif; ?>
						</div>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
