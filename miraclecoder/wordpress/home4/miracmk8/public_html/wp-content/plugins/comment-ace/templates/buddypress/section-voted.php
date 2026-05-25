<?php
/**
 * User Voted Comments
 */

namespace Commentace;

$filter_by = filter_input( INPUT_GET, 'filter_by', FILTER_SANITIZE_STRING );
$votes_type = '';

if ( in_array( $filter_by, array( 'up', 'down' ) ) ) {
    $votes_type = $filter_by;
}
?>

<div id="cace-comments-voted">

    <?php get_template_part( 'buddypress/filters', 'voted' ); ?>

    <?php $user_comments = bp_get_user_comments( bp_get_voted_comments_slug(), bp_displayed_user_id(), array( 'votes_type' => $votes_type ) ) ?>

    <?php if ( count( $user_comments ) ) : ?>

        <?php get_template_part( 'buddypress/pagination', 'top' ); ?>
        <?php get_template_part( 'collections/list', null, array( 'comments' => $user_comments ) ); ?>
        <?php get_template_part( 'buddypress/pagination', 'bottom' ); ?>

    <?php else : ?>

        <p><?php esc_html_e( 'There are no comments yet', 'cace' ); ?></p>

    <?php endif; ?>

</div>
