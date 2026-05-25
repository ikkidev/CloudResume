<?php
/**
 * Template for displaying single item title
 *
 * @package snax
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$snax_item_permalink = false;

if ( function_exists( 'snax_get_item_permalink' ) ) {
	$snax_item_permalink = snax_get_item_permalink();
}
?>
<h3 class="g1-beta g1-beta-1st snax-item-title">
<?php if ( $snax_item_permalink && snax_list_link_to_single() ) : ?>
	<?php $snax_link_target = isset( $snax_item_permalink['target'] ) ? ' target="' . $snax_item_permalink['target'] . '"' : ''; ?>
	<?php $snax_link_rel    = isset( $snax_item_permalink['rel'] ) ? $snax_item_permalink['rel'] : 'bookmark'; ?>

	<a href="<?php echo esc_url( $snax_item_permalink['url'] ); ?>"<?php echo esc_attr( $snax_link_target ); ?> id="snax-itemli-<?php echo (int) get_the_ID(); ?>" rel="<?php echo esc_attr( $snax_link_rel ); ?>">
<?php endif; ?>
	<?php
		snax_render_item_position();
		the_title( '', '' );
	?>
<?php if ( $snax_item_permalink && snax_list_link_to_single() ) : ?>
	</a>
<?php endif; ?>
</h3>
