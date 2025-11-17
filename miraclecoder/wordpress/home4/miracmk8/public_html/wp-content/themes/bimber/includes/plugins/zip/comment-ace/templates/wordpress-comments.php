<?php
/**
 * WordPress comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! get_comments_number() && ! comments_open() ) {
    return;
}

/**
 * @var WordPress_Comments $wp_comments    Comment type object.
 * @var array             $args             Template arguments.
 */
$wp_comments = $args['comment_type'];

$cace_classes = array(
    'cace-comment-type',
    'cace-comment-type-' . $wp_comments->get_id(),
);
$cace_classes = array_merge( $cace_classes, $wp_comments->get_classes() );

$cace_order = $wp_comments->get_order();

$cace_comment_count = get_comments_number();

// Pingbacks & Trackbacks.
$cace_ping_count = 0;

if ( ! empty( $comments ) ) {
    $comments_by_type = separate_comments( $comments );

    $cace_ping_count = count( $comments_by_type['pings'] );
}
?>

<div class="<?php echo implode( ' ', array_filter( array_map( 'sanitize_html_class', $cace_classes ) ) ); ?>" data-comment-type="<?php echo esc_attr( $wp_comments->get_id() ); ?>">
    <section id="comments-wp" class="g1-comment-type g1-comment-type-wp comments-area">
        <?php $wp_comments->render_comment_form( CACE_WP_COMMENT_FORM_BEFORE ); ?>

        <?php if ( $cace_comment_count ) : ?>
            <?php if ( 1 === count( plugin()->comments()->get_types() ) ) : ?>
                <?php get_template_part( 'wp-comments/headline-comments', null, array( 'comment_count' => $cace_comment_count ) ); ?>
            <?php endif ?>

            <div class="cace-comments-tools">
                <?php get_template_part('wp-comments/tools-collapse-replies'); ?>

                <?php
                get_template_part( 'wp-comments/tools-sort', null, array(
                    'cace_order' => $cace_order,
                ) );
                ?>
            </div>
        <?php endif; ?>

        <ol class="comment-list">
            <?php
            if ( $wp_comments->have_featured_comments() && $wp_comments->show_featured_comments_on_page() ) {
                render_comment_items( $wp_comments->get_featured_comment_ids(), array( 'cace_type' => 'featured-comment' ) );
            }

            // Regular comments.
            wp_list_comments( array(
                'type'     => 'comment',
            ) );
            ?>
        </ol>
        <span class="cace-spinner"></span>

        <?php get_template_part( 'wp-comments/view-replies' ); ?>
        <?php get_template_part( 'wp-comments/report' ); ?>
        <?php $wp_comments->render_load_more_link(); ?>

        <?php if ( $cace_ping_count ) : ?>
            <?php get_template_part( 'wp-comments/headline-pings', null, array( 'ping_count' => $cace_ping_count ) ); ?>

            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'type'     => 'pings',
                    'page'     => 1,
                    'per_page' => $cace_ping_count,
                ) );
                ?>
            </ol>
        <?php endif; ?>

        <?php $wp_comments->render_comment_form( CACE_WP_COMMENT_FORM_AFTER ); ?>
    </section><!-- #comments-wp -->
</div>
