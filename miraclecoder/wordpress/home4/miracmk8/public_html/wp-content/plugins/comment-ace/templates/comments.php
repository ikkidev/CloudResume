<?php
/**
 * Comments wrapper template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$in_debug_mode = plugin()->in_debug_mode();

if ( post_password_required() ) {
    if ( $in_debug_mode ) {
        echo esc_html_x( 'Password protected post. Comments hidden.', 'Debug Mode', 'cace' );
    }

    return;
}



/**
 * @var $comment_types Comment_Type[]
 */
$comment_types = plugin()->comments()->get_types();
$cace_show_avatars = get_option('show_avatars');

$commentace_classes = array(
    'cace-comments',
    $cace_show_avatars ? 'cace-comments-with-avatars' : 'cace-comments-without-avatars',
    $in_debug_mode ? 'cace-debug-mode' : '',
);
?>

<div class="<?php echo implode( ' ', array_filter( array_map( 'sanitize_html_class', $commentace_classes ) ) ); ?>" id="comments">

    <?php do_action( 'commentace_before_comments' ); ?>

    <?php if ( 1 < count( $comment_types ) ) : ?>
        <?php get_template_part( 'wp-comments/headline' ); ?>

        <?php get_template_part( 'comments-tabs' ); ?>
    <?php endif; ?>

    <?php
    $tab_index = 0;
    foreach ($comment_types as $comment_type ) {
        if ( 0 === $tab_index++ ) {
            $comment_type->set_classes( array( 'cace-comment-type-current' ) );
        }

        $comment_type->render();
    }
    ?>

    <?php do_action( 'commentace_after_comments' ); ?>
</div>


