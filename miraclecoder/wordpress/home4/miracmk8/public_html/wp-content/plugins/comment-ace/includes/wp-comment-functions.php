<?php
/**
 * WordPress Comment Functions
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Retrieves the comment time of the current comment.
 *
 * @since 1.5.0
 *
 * @param int    $comment_id Optional. Comment object or ID.
 * @param string $format     Optional. PHP date format. Defaults to the 'time_format' option.
 * @param bool   $gmt        Optional. Whether to use the GMT date. Default false.
 * @param bool   $translate  Optional. Whether to translate the time (for use in feeds).
 *                           Default true.
 * @return string The formatted time.
 */
function get_comment_time( $comment_id = null, $format = '', $gmt = false, $translate = true ) {
    $comment = get_comment( $comment_id );

    $comment_date = $gmt ? $comment->comment_date_gmt : $comment->comment_date;

    $_format = ! empty( $format ) ? $format : get_option( 'time_format' );

    $date = mysql2date( $_format, $comment_date, $translate );

    /**
     * Filters the returned comment time.
     *
     * @since 1.5.0
     *
     * @param string|int $date      The comment time, formatted as a date string or Unix timestamp.
     * @param string     $format    PHP date format.
     * @param bool       $gmt       Whether the GMT date is in use.
     * @param bool       $translate Whether the time is translated.
     * @param WP_Comment $comment   The comment object.
     */
    return apply_filters( 'get_comment_time', $date, $format, $gmt, $translate, $comment );
}

/**
 * Displays the edit comment link with formatting.
 *
 * @since 1.0.0
 *
 * @param int    $comment_id    Optional. Comment object or ID.
 * @param string $text          Optional. Anchor text. If null, default is 'Edit This'. Default null.
 * @param string $before        Optional. Display before edit link. Default empty.
 * @param string $after         Optional. Display after edit link. Default empty.
 */
function edit_comment_link( $comment_id = null, $text = null, $before = '', $after = '' ) {
    $comment = get_comment( $comment_id );

    if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) ) {
        return;
    }

    if ( null === $text ) {
        $text = __( 'Edit This' );
    }

    $link = '<a class="comment-edit-link" href="' . esc_url( get_edit_comment_link( $comment ) ) . '">' . $text . '</a>';

    /**
     * Filters the comment edit link anchor tag.
     *
     * @since 2.3.0
     *
     * @param string $link       Anchor tag for the edit link.
     * @param int    $comment_id Comment ID.
     * @param string $text       Anchor text.
     */
    echo $before . apply_filters( 'edit_comment_link', $link, $comment->comment_ID, $text ) . $after;
}

/**
 * Custom function for displaying comments
 *
 * @param \WP_Comment $comment Comment object.
 * @param array  $args Arguments.
 * @param int    $depth Depth.
 */
function render_comment_item_callback($comment, $args, $depth ) {
    static $comment_index = 0;

    $avatar_size = ( 1 === $depth ) ? 36 : 30;

    $comment_ID = $comment->comment_ID;
    $comment_post = get_post( $comment->comment_post_ID );

    $is_new = isset( $args['cace_is_new'] ) && true === $args['cace_is_new'];
    $is_reported = (isset( $args['cace_reported'] ) && true === $args['cace_reported']) || Report\is_comment_reported( $comment_ID );
    $is_featured = isset( $args['cace_type'] ) && 'featured-comment' === $args['cace_type'] && 1 === $depth;
    $is_author = (int)$comment->user_id === (int)$comment_post->post_author;

    $actions = array(
        'copy_link' => is_copy_link_enabled(),
        'reply'     => true,
        'report'    => is_reporting_enabled(),
        'vote'      => is_voting_enabled(),
    );

    if ( ! $comment->comment_approved || $is_new || $is_reported ) {
        $actions['copy_link'] = false;
        $actions['reply']     = false;
        $actions['report']    = false;
        $actions['vote']      = false;
    }
    switch ( $comment->comment_type ) :
        // @since WordPress 5.5 (empty value was used before for default comment type)
        case '' :
        case 'comment' :
            if ( 1 === $depth ) {
                // We call the action before the <li> so let's decrease the index to make it correct.
                do_action( 'cace_after_comment', $comment, $comment_index );
            }
            ?>
            <li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent', $comment ); ?> id="li-comment-<?php echo absint( $comment_ID ); ?>">

            <?php
            $class = array( 'comment-body' );
            $class[] = 'cace-card';

            if ( $is_new ) {
                $class[] = 'cace-card-solid';
                $class[] = 'cace-card-new';
            }

            if ( $is_reported ) {
                $class[] = 'cace-card-solid';
                $class[] = 'cace-card-reported';
            }

            if ( $is_featured ) {
                $class[] = 'cace-card-solid';
                $class[] = 'cace-card-featured';
            } else {
                switch ( get_option( 'cace_design_cards' ) ) {
                    case 'standard':
                        $class[] = 'cace-card-solid';
                        break;

                    case 'simple':
                        $class[] = 'cace-card-simple';
                        break;

                    default:
                        break;
                }
            }

            $class = array_unique( $class );
            ?>

            <article class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $class ) ); ?>" id="comment-<?php echo absint( $comment_ID ); ?>" itemscope itemtype="http://schema.org/Comment">
                <footer class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        $comment_author_url = get_comment_author_url( $comment );
                        $comment_author     = get_comment_author( $comment );
                        $avatar             = get_avatar( $comment, $avatar_size );
                        ?>

                        <?php if ( ! empty( $comment_author_url ) ) : ?>
                        <a class="url" href="<?php echo esc_url( $comment_author_url ); ?>" rel="external nofollow">
                            <?php endif; ?>

                            <?php echo wp_kses_post( $avatar ); ?>

                            <span class="fn g1-epsilon g1-epsilon-1st"><?php echo esc_html( $comment_author ); ?></span>

                            <?php if ( $is_author && show_author_badge() ) : ?>
                                <span class="cace-badge cace-badge-author"><?php esc_html_e( 'Author', 'cace' ); ?></span>
                            <?php endif; ?>
                            <span class="says"><?php esc_html_e( 'says:', 'cace' ); ?></span>

                            <?php if ( ! empty( $comment_author_url ) ) : ?>
                        </a>
                    <?php endif; ?>
                    </div><!-- .comment-author -->

                    <div class="g1-meta comment-metadata">
                        <a class="comment-date" href="<?php echo esc_url( get_comment_link( $comment_ID ) ); ?>">
                            <time datetime="<?php echo esc_attr( get_comment_date( 'Y-m-d', $comment_ID ) . 'T' . get_comment_time( $comment_ID, 'H:i:s' ) . get_iso_8601_utc_offset() ); ?>">
                                <?php printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'cace' ), get_comment_date( '', $comment_ID ), get_comment_time( $comment_ID ) ); ?>
                            </time>
                        </a>
                        <?php edit_comment_link( $comment_ID, __( 'Edit', 'cace' ) ); ?>

                        <?php if ( $actions['copy_link'] ) : ?>
                            <a class="cace-comment-link cace-comment-link-not-copied" title="<?php esc_html_e( 'Copy Link of a Comment', 'cace' ); ?>" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php esc_html_e( 'Copy Link of a Comment', 'cace' ); ?></a>
                        <?php endif; ?>
                    </div><!-- .comment-metadata -->

                </footer><!-- .comment-meta -->



                <div class="comment-content">
                    <?php if ( '0' === $comment->comment_approved && ! $is_reported ) : ?>
                        <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'cace' ); ?></p>
                    <?php endif; ?>

                    <?php if ( $is_reported ) : ?>
                        <p class="comment-awaiting-moderation"><?php esc_html_e( 'This comment was reported.', 'cace' ); ?></p>

                        <?php do_action( 'cace_reported_comment_info', 'after' ); ?>
                    <?php else : ?>
                        <?php comment_text( $comment_ID ); ?>
                    <?php endif; ?>

                </div><!-- .comment-content -->

                <div class="comment-footer">
                    <?php if ( $actions['vote'] ) : ?>
                        <?php
                        // Comment voting UI.
                        $comment_votes = Votes::get_comment_votes( $comment_ID );

                        $user_vote = false;
                        if ( is_user_logged_in() ) {
                            $user_vote = Votes::find_by_user_comment( get_current_user_id(), $comment_ID );
                        }

                        set_query_var( 'commentace_data', array(
                            'comment_votes' => $comment_votes,
                            'user_vote'     => $user_vote,
                        ) );
                        get_template_part( 'votes/wordpress-comment-votes', '', array(
                            'comment_votes' => $comment_votes,
                            'user_vote'     => $user_vote,
                        ) );
                        ?>
                    <?php endif; ?>

                    <?php if ( $actions['reply'] ) : ?>
                        <div class="g1-meta reply">
                            <?php comment_reply_link( array_merge( $args, array(
                                'depth'     => $depth,
                                'max_depth' => isset( $args['max_depth'] ) ? $args['max_depth'] : get_option( 'thread_comments_depth' ),
                            ) ), $comment ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $actions['report'] ) : ?>
                        <button type="button" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', apply_filters( 'cace_report_classes', array( 'cace-button-reset','cace-comment-report', 'cace-login-required' ) ) ) ); ?>"><?php echo esc_html_e( 'Report', 'cace' ); ?></button>
                    <?php endif; ?>
                </div>

            </article><!-- .comment-body -->
            <?php
            if ( 1 === $depth ) {
                $comment_index++;
            }
            break;
        case 'pingback'  :
        case 'trackback' :
            ?>
        <li <?php comment_class( '', $comment_ID ); ?> id="comment-<?php echo absint( $comment_ID ); ?>">
            <p>
                <?php esc_html_e( 'Pingback:', 'cace' ); ?>

                <?php comment_author_link( $comment_ID ); ?>

                <?php edit_comment_link( $comment_ID, esc_html__( 'Edit', 'cace' ), '<span class="edit-link">', '</span>' ); ?>
            </p>
            <?php
            break;
    endswitch;
}

/**
 *
 *
 * @param array $comment_ids        List of comment ids.
 * @param array $args               Optional. Extra arguments.
 * @param int   $post_id            Optional. Post ID.
 * @param int   $depth              Optional. Comment depth.
 */
function render_comment_items( $comment_ids, $args = array(), $post_id = null, $depth = 1 ) {
    $post = get_post( $post_id );

    add_filter( 'cace_do_not_exclude_featured_comments', '__return_true' );

    $comments = get_comments( array(
        'comment__in' => $comment_ids,
        'orderby'     => 'comment__in',
    ) );

    foreach ( $comments as $comment ) {
        $children = get_comments( array(
            'parent' => $comment->comment_ID,
            'hierarchical' => true,
            'status' => 'approve',
            'include_unapproved' => array( get_current_user_id() ),
        ) );

        foreach ( $children as $child ) {
            $comments[] = $child;
        }


    }

    /**
     * Filters the comments array.
     *
     * @param array $comments Array of comments supplied to the comments template.
     * @param int   $post_ID  Post ID.
     */
    $comments = apply_filters( 'comments_array', $comments, $post->ID );

    $defaults = array(
        'walker'            => null,
        'max_depth'         => get_option( 'thread_comments_depth' ),
        'style'             => 'ul',
        'callback'          => null,
        'end-callback'      => null,
        'type'              => 'all',
        'page'              => '',
        'per_page'          => '',
        'avatar_size'       => 32,
        'reverse_top_level' => null,
        'reverse_children'  => '',
        'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
        'short_ping'        => false,
        'echo'              => true,
    );

    $parsed_args = wp_parse_args( $args, $defaults );

    $parsed_args = apply_filters( 'wp_list_comments_args', $parsed_args );

    $walker = new \Walker_Comment;
    $output = $walker->paged_walk( $comments, $parsed_args['max_depth'], $parsed_args['page'], $parsed_args['per_page'], $parsed_args );

    echo $output;

    add_filter( 'cace_do_not_exclude_featured_comments', '__return_false' );
}

/**
 * Process embeds inside a comment
 *
 * @param string          $comment_text     Text of the current comment.
 *
 * @return string
 */
function parse_comment_embeds( $comment_text ) {
    if ( preg_match_all( '/<a href="https:\/\/giphy\.com\/embed\/([^"]+)"[^>]+>[^<]+<\/a>/', $comment_text, $matches ) ) {
        $links = $matches[0];
        $ids  = $matches[1];
        $src = plugin()->get_url() . 'assets/images/powered-by-giphy';

        $tpl = '';
        $tpl .= '<figure class="cace-gif">';
        $tpl .= '<video preload="none" autoplay muted loop playsinline>';
        $tpl .= '<source src="https://media1.giphy.com/media/%s/giphy.mp4" type="video/mp4">';
        $tpl .= '</video>';
        $tpl .= '<figcaption>';
        $tpl .= '<span class="cace-gif-src">' . esc_html__( 'Powered by GIPHY', 'cace') . '</span>';
        $tpl .= '</figcaption>';
        $tpl .= '</figure>';

        foreach ($links as $index => $link) {
            $video = sprintf( $tpl, $ids[ $index ] );
            $comment_text = str_replace( $link, $video, $comment_text );
        }
    }

    return $comment_text;
}

/**
 * Trim the comment text based on defined length.
 *
 * @param array $commentdata        Comment data.
 *
 * @return array
 */
function trim_comment( $commentdata ) {
    $max_length = (int) get_comment_maxlength();

    if ( $max_length > 0 && mb_strlen( $commentdata['comment_content'] ) > $max_length ) {
        $commentdata['comment_content'] = mb_strimwidth( $commentdata['comment_content'], 0, $max_length );
    }

    return $commentdata;
}
