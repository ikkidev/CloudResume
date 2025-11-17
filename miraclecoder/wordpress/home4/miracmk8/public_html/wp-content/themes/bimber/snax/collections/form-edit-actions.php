<?php
/**
 * Snax Collection Edit Form Actions Template Part
 *
 * @package snax 1.11
 * @subpackage Theme
 */
$snax_collection = snax_get_collection_by_id( get_the_ID() );
?>
<div class="snax-edit-collection-actions">
	<a class="g1-button g1-button-m g1-button-subtle" href="<?php echo esc_url( $snax_collection->get_url() ); ?>"><?php esc_html_e( 'Cancel', 'snax' ); ?></a>
	<input type="submit" value="<?php esc_attr_e( 'Save Changes', 'snax' ); ?>" />
</div>