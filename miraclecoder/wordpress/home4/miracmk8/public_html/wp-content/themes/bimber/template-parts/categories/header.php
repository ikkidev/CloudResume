<?php
/**
 * The Template for displaying categories header.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_data = bimber_get_template_part_data();
$bimber_title = $bimber_data['title'];
$bimber_title_size = $bimber_data['title_size'];
$bimber_title_align = $bimber_data['title_align'];
$bimber_title_show = $bimber_data['title_show'];
?>
<?php if ( strlen( $bimber_title ) && 'none' !== $bimber_title_show ) : ?>
	<div class="g1-terms-header">
		<?php echo do_shortcode( '[bimber_title size="' . $bimber_title_size . '" align="' . $bimber_title_align . '" class="g1-cats-title"]' . $bimber_title . '[/bimber_title]' ); ?>
	</div>
<?php endif;
