<?php
/**
 * Votes template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$commentace_comment_votes = $args['comment_votes'];

/**
 * @var Vote $commentace_vote
 */
$commentace_vote = $args['user_vote'];

$commentace_vote_icon = get_voting_icon();
$commentace_show_number_of_votes = show_number_of_votes();

$commentace_vote_classes = apply_filters( 'cace_vote_classes', array( 'cace-comment-vote' ) );

$commentace_vote_up_classes = $commentace_vote_classes;
$commentace_vote_down_classes = $commentace_vote_classes;

$commentace_vote_up_classes[] = 'cace-comment-vote-up';

if ( $commentace_vote && $commentace_vote->is( CACE_VOTE_UP ) ) {
    $commentace_vote_up_classes[] = 'cace-comment-vote-selected';
}

$commentace_vote_down_classes[] = 'cace-comment-vote-down';

if ( $commentace_vote && $commentace_vote->is( CACE_VOTE_DOWN ) ) {
    $commentace_vote_down_classes[] = 'cace-comment-vote-selected';
}
?>

<div class="cace-comment-votes">
    <button class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $commentace_vote_up_classes ) ); ?>" aria-label="<?php esc_attr_e( 'Vote Up', 'cace' ); ?>">
        <span class="cace-vote-icon cace-vote-icon-<?php echo sanitize_html_class( $commentace_vote_icon ); ?>"></span>
        <?php esc_html_e( 'Vote Up', 'cace' ); ?>

        <?php if ( $commentace_show_number_of_votes ) : ?>
            <?php
                $score_class = array( 'cace-comment-score' );
                if ( ! $commentace_comment_votes['up_votes'] ) {
                    $score_class[] = 'cace-comment-score-0';
                }
            ?>
            <span class="<?php echo implode(' ', array_map('sanitize_html_class', $score_class ) ); ?>" data-raw-value="<?php echo absint( $commentace_comment_votes['up_votes'] ); ?>">
                <?php echo number_format_i18n( $commentace_comment_votes['up_votes'] ); ?>
            </span>
        <?php endif; ?>
    </button>

    <?php if ( show_vote_score() ) : ?>
        <?php
        $score_class = array( 'cace-comment-score', 'cace-comment-score-total' );
        if ( 0 < $commentace_comment_votes['score'] ) {
            $score_class[] = 'cace-comment-score-positive';
        } else if ( 0 > $commentace_comment_votes['score'] ) {
            $score_class[] = 'cace-comment-score-negative';
        } else {
            $score_class[] = 'cace-comment-score-0';
        }
        ?>
        <span class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $score_class ) ); ?>" data-raw-value="<?php echo absint( $commentace_comment_votes['score'] ); ?>"><?php echo number_format_i18n( $commentace_comment_votes['score'] ); ?></span>
    <?php endif; ?>

    <button class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $commentace_vote_down_classes ) ); ?>">
        <span class="cace-vote-icon cace-vote-icon-<?php echo sanitize_html_class( $commentace_vote_icon ); ?>"></span>
        <?php esc_html_e( 'Vote Down', 'cace' ); ?>

        <?php if ( $commentace_show_number_of_votes ) : ?>
            <?php
            $score_class = array( 'cace-comment-score' );
            if ( ! $commentace_comment_votes['down_votes'] ) {
                $score_class[] = 'cace-comment-score-0';
            }
            ?>
            <span class="<?php echo implode(' ', array_map('sanitize_html_class', $score_class ) ); ?>" data-raw-value="<?php echo absint( $commentace_comment_votes['down_votes'] ); ?>">
                <?php echo number_format_i18n( $commentace_comment_votes['down_votes'] ); ?>
            </span>
        <?php endif; ?>
    </button>
</div>

