<?php
/**
 * Collection List Template
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="cace-comments-with-avatars">
    <ul class="comment-list">
        <?php foreach ( $args['comments'] as $comment ) : ?>
	        <li class="comment">
		        <article class="comment-body cace-card cace-card-solid">
			        <footer class="comment-meta">
						<?php
						$post_title_text = get_the_title( $comment->comment_post_ID );
						// Trim words to keep it shorter.
						if ( true ) {
							$post_title_text = wp_trim_words( $post_title_text, 5 );
						}

                        $comment_author = '';

                        if ( $comment->user_id ) {
                            $comment_author_url = get_author_posts_url( $comment->user_id );
                        } else {
                            $comment_author_url = get_comment_author_url( $comment );
                        }

                        $comment_author .= '<div class="comment-author">';

                        if ( ! empty( $comment_author_url ) ) {
                            $comment_author .= '<a class="url" href="' . esc_url( $comment_author_url ) . '" rel="external nofollow">';
                        }

                        $comment_author .= get_avatar( $comment, 36 );
                        $comment_author .= '<span class="fn g1-epsilon g1-epsilon-1st">';
                        $comment_author .= esc_html( get_comment_author( $comment ) );
                        $comment_author .= '</span>';

                        if ( ! empty( $comment_author_url ) ) {
                            $comment_author .= '</a>';
                        }
                        $comment_author .= '</div><!-- .comment-author -->';

                        $comment_post_title = '';
                        $comment_post_title .= '<strong class="comment-post-title g1-epsilon g1-epsilon-1st">';
                        $comment_post_title .= '<a href="' . esc_url( get_permalink( $comment->comment_post_ID ) ) . '">' . esc_html( $post_title_text ) . '</a>';
                        $comment_post_title .= '</strong>';

                        echo wp_kses_post( sprintf(
														__( '%1$s on %2$s <span class="screen-reader-text says">says:</span>', 'cace'),
                            $comment_author,
                            $comment_post_title
                        ) );

						// The  get_comment_time function doesn't have the comment_id attribute :/
						// That's why we must do this workaround.
						$comment_time = apply_filters( 'get_comment_time',
							mysql2date( get_option('time_format'), $comment->comment_date, true ),
							$comment->comment_date,
							get_option('time_format'),
							false,
							true,
							$comment
						);

						$comment_time_attr = apply_filters( 'get_comment_time',
							mysql2date( 'H:i:s', $comment->comment_date, true ),
							$comment->comment_date,
							'H:i:s',
							false,
							true,
							$comment
						);
						?>

				        <div class="g1-meta comment-metadata">
					        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						        <time datetime="<?php echo esc_attr( get_comment_date( 'Y-m-d', $comment ) . 'T' . $comment_time_attr . get_iso_8601_utc_offset() ); ?>">
							        <?php printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'cace' ), get_comment_date('', $comment), $comment_time ); ?>
						        </time>
					        </a>
				        </div><!-- .comment-metadata -->
			        </footer><!-- .comment-meta -->

			        <div class="comment-content">
			            <?php comment_text( $comment ); ?>
			        </div><!-- .comment-content -->
		        </article>
	        </li>
        <?php endforeach; ?>
    </ul>
</div>
