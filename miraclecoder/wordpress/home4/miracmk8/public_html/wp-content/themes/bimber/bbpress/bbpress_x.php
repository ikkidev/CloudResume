<?php
/**
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php
    $g1_bbpress_template = g1_get_theme_option( 'bbpress', 'forum_template', 'sidebar_right' );

    // Add proper body classes
    switch ( $g1_bbpress_template ) {
        case 'sidebar_right':
            add_filter( 'body_class', array(G1_Theme(), 'secondary_after_body_class') );
            add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
            break;
        case 'sidebar_left':
            add_filter( 'body_class', array(G1_Theme(), 'secondary_before_body_class') );
            add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
            break;
        default:
            add_filter( 'body_class', array(G1_Theme(), 'secondary_none_body_class') );
            break;
    }
?>
<?php get_header( 'bbpress' ); ?>
    <div id="primary">
        <div id="content" role="main">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php
                        global $post;
                        $title = the_title( '', '', false );
                        $subtitle = wp_kses_data( get_post_meta( $post->ID, '_g1_subtitle', true ) );
                    ?>

                    <?php if( strlen( $title ) || strlen( $subtitle ) ): ?>
                        <header class="entry-header g1-move-to-precontent">
                            <div class="g1-hgroup">
                                <?php if ( strlen( $title ) ): ?>
                                    <h1 class="entry-title"><?php echo $title; ?></h1>
                                <?php endif; ?>
                                <?php if ( strlen( $subtitle ) ): ?>
                                    <h3 class="entry-subtitle"><?php echo $subtitle; ?></h3>
                                <?php endif; ?>
                            </div>
                        </header><!-- .entry-header -->
                    <?php endif; ?>

                    <!-- BEGIN .entry-content -->
                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php g1_wp_link_pages(); ?>
                    </div>
                    <!-- END .entry-content -->
                    <footer class="entry-meta">
                        <?php edit_post_link( __( 'Edit', 'g1_theme' ), '<span class="edit-link">', '</span>' ); ?>
                    </footer>

                    <?php comments_template( '', true ); ?>
                </article><!-- #post-<?php the_ID(); ?> -->
            <?php endwhile; ?>

        </div><!-- #content -->
    </div><!-- #primary -->
<?php
    if ( in_array( $g1_bbpress_template, array( 'sidebar_left', 'sidebar_right' ) ) ) {
        get_sidebar( 'bbpress' );
    }
    get_footer( 'bbpress' );
