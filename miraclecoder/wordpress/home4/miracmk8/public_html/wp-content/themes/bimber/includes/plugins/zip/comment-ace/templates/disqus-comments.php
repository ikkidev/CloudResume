<?php
/**
 * Disqus comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * @var Disqus_Comments $comment_type     Comment type object.
 * @var array           $args             Template arguments.
 */
$comment_type = $args['comment_type'];

$commentace_classes = array(
    'cace-comment-type',
    'cace-comment-type-' . $comment_type->get_id(),
);

$commentace_classes = array_merge( $commentace_classes, $comment_type->get_classes() );
?>

<div class="<?php echo implode( ' ', array_filter( array_map( 'sanitize_html_class', $commentace_classes ) ) ); ?>"  data-comment-type="<?php echo esc_attr( $comment_type->get_id() ); ?>">

    <?php if ( $comment_type->get_disqus_shortname() ): ?>

        <span class="disqus-comment-count" data-disqus-url="<?php echo get_permalink(); ?>"></span>

        <div id="disqus_thread"></div>

        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

        <span class="cace-spinner"></span>

    <?php else: ?>

        <p>
            <?php printf(
                esc_html_x( 'Disqus Shortname not set. Please check %s', 'Notice', 'cace' ),
                '<a href="'. esc_url( admin_url( 'admin.php?page=cace-settings-dsq' ) ) .'" target="_blank">'. esc_html__( 'settings', 'cace' ) .'</a>' ); ?>
        </p>

    <?php endif; ?>

</div>

