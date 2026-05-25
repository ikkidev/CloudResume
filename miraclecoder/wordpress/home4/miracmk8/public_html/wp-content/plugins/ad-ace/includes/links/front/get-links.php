<?php
/**
 * Front Links Functions
 *
 * @package AdAce.
 * @subpackage Links.
 */

 /**
  * Get links list.
  *
  * @param string $category Category slug or empty.
  * @param bool   $simple If use featured image.
  * @param int    $highlighted Number of list elements to hightlight.
  * @param bool   $transparent If use semitransparent.
  */
function adace_get_links_list( $category = '', $simple = false, $highlighted = 2, $transparent = false ) {
	// Prepare args for loop.
	$links_args = array(
		'post_type'           => 'adace_link',
		'posts_per_page'      => -1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	);
	// Add category if we have one.
	if ( ! empty( $category ) ) {
		$links_args['tax_query'] = array(
			array(
				'taxonomy' => 'adace_link_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	// Use this filter to alter query args.
	$links_args = apply_filters( 'adace_links_widget_query_args', $links_args );
	// Build query.
	$links_query = new WP_Query( $links_args );

	ob_start();

	set_query_var( 'links_query', $links_query );
	set_query_var( 'highlighted', $highlighted );
	set_query_var( 'transparent', $transparent );
	adace_get_template_part( 'linklist', $simple ? '' : 'images' );

	$output = ob_get_clean();

	return $output;
}
