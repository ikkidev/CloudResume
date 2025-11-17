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

<ul class="sub-menu">
	<?php foreach ( $bimber_terms as $bimber_i => $bimber_term ) : ?>
		<?php if ( $bimber_i === $bimber_count - 1 && strlen( $bimber_more_url ) && 0 < $bimber_more ) : ?>
			<li class="menu-item">
				<a href="<?php echo esc_url( $bimber_more_url ); ?>">
					<div class="g1-term-icon">
					</div>
					<div class="g1-term-body">
						<h4 class="g1-term-title"><?php esc_html_e( 'View All', 'bimber' ); ?></h4>
					</div>
				</a>
			</li>
		<?php else : ?>
			<li class="menu-item">
				<a href="<?php echo esc_url( get_term_link( $bimber_term ) ); ?>">
					<?php if ( $bimber_elements['icon'] ) : ?>
						<?php bimber_render_term_icon( $bimber_term ); ?>
					<?php endif; ?>

					<?php echo $bimber_term->name; ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
