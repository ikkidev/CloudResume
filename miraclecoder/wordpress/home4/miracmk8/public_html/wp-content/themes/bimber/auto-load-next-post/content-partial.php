<?php
/**
 * This file loads the content partially.
 */

// Fetch plugin settings.
$alnp_remove_comments = get_option( 'auto_load_next_post_remove_comments' );

// Load content before the loop.
do_action( 'alnp_load_before_loop' );
?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	// Load content before the post content.
	do_action( 'alnp_load_before_content' );

	// Load content before the post content for a specific post format.
	do_action( 'alnp_load_before_content_type_' . get_post_format() );

	$bimber_post_settings = bimber_get_post_settings();

	// We need to be sure the single navigation is available,
	// because ALNP plugin uses it to get post URLs.
	$bimber_post_settings['navigation'] = true;

	// Hide comments if required.
	if ( $alnp_remove_comments === 'yes' ) {
		$bimber_post_settings['comments'] = false;
	}

	bimber_set_template_part_data( $bimber_post_settings );

	/*
	 * Include the post format-specific template for the content. If you want to
	 * use this in a child theme, then include a file called called content-single-classic-___.php
	 * (where ___ is the post format) and that will be used instead.
	 */
	get_template_part( 'template-parts/content-single-classic', get_post_format() );

	bimber_reset_template_part_data();

	// Load content after the post content for a specific post format.
	do_action( 'alnp_load_after_content_type_' . get_post_format() );

	// Load content after the post content.
	do_action( 'alnp_load_after_content' );
	?>
<?php endwhile; ?>
<?php
// Load content after the loop.
do_action( 'alnp_load_after_loop' );
