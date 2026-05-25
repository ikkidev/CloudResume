<?php
/**
 * Facebook comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * @var Facebook_Comments $comment_type     Comment type object.
 * @var array             $args             Template arguments.
 */
$comment_type = $args['comment_type'];

$commentace_classes = array(
    'cace-comment-type',
    'cace-comment-type-' . $comment_type->get_id(),
);

$commentace_classes = array_merge( $commentace_classes, $comment_type->get_classes() );
?>

<div class="<?php echo implode( ' ', array_filter( array_map( 'sanitize_html_class', $commentace_classes ) ) ); ?>"  data-comment-type="<?php echo esc_attr( $comment_type->get_id() ); ?>">

    <?php if ( $comment_type->get_app_id() ): ?>

        <!--<span class="fb-comments-count" data-href="<?php echo esc_url( get_permalink() ); ?>"></span>-->

        <div class="fb-comments"
         data-href="<?php echo esc_url( get_permalink() ); ?>"
         data-numposts="<?php echo absint( get_fb_comments_number() ); ?>"
         data-width="100%"
         data-order-by="<?php echo esc_attr( get_fb_comments_order() ); ?>"
         data-colorscheme="<?php echo esc_attr( get_fb_color_scheme() ); ?>"></div>

        <span class="cace-spinner"></span>

    <?php else: ?>

        <p>
            <?php printf(
                esc_html_x( 'Facebook App ID not set. Please check %s', 'Notice', 'cace' ),
                '<a href="'. esc_url( admin_url( 'admin.php?page=cace-settings-fb' ) ) .'" target="_blank">'. esc_html__( 'settings', 'cace' ) .'</a>' ); ?>
        </p>


    <?php endif; ?>

</div>

