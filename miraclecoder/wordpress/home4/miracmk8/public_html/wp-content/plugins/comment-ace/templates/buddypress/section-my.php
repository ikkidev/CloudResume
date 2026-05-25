<?php
/**
 * User Comments
 */

namespace Commentace;

$is_owner = is_user_logged_in() && bp_displayed_user_id() && get_current_user_id() === bp_displayed_user_id();
$filter_by = filter_input( INPUT_GET, 'filter_by', FILTER_SANITIZE_STRING );

if ( $is_owner ) {
    $comments_status = 'any';

    switch ( $filter_by ) {
        case 'published':
            $comments_status = 'approve';
            break;
        case 'pending':
            $comments_status = 'hold';
            break;
    }
} else {
    $comments_status = 'approve';
}
?>

<div id="cace-comments-my">

    <?php
    if ( $is_owner ) {
        get_template_part( 'buddypress/filters', 'my' );
    }
    ?>

    <?php $user_comments = bp_get_user_comments( bp_get_my_comments_slug(), bp_displayed_user_id(), array( 'status' => $comments_status ) ) ?>

    <?php if ( count( $user_comments ) ) : ?>

        <?php get_template_part( 'buddypress/pagination', 'top' ); ?>
        <?php get_template_part( 'collections/list', null, array( 'comments' => $user_comments ) ); ?>
        <?php get_template_part( 'buddypress/pagination', 'bottom' ); ?>

    <?php else : ?>

        <p><?php esc_html_e( 'There are no comments yet', 'cace' ); ?></p>

    <?php endif; ?>

</div>
