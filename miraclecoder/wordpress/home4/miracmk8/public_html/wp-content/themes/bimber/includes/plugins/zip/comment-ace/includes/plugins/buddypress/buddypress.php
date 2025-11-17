<?php
/**
 * BuddyPress plugin integration
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'bp_loaded',                                'Commentace\bp_setup');
add_filter( 'bp_core_admin_get_components',	            'Commentace\bp_register_custom_components', 10, 2 );
add_filter( 'cace_widget_recent_comments_query_args',   'Commentace\bp_widget_recent_comments_query_args', 10, 1 );
add_action( 'cace_widget_recent_comments_list',         'Commentace\bp_add_view_all_comments_link', 10, 1 );

/**
 * Return Comments component unique id
 *
 * @return string
 */
function bp_comments_component_id() {
    return apply_filters( 'cace_comments_bp_component_id', 'comments' );
}

/**
 * Return the My comments page slug
 *
 * @param string $default Default value.
 *
 * @return string
 */
function bp_get_my_comments_slug() {
    return apply_filters( 'cace_bp_my_comments_slug', 'my' );
}

/**
 * Return the Voted comments page slug
 *
 * @param string $default Default value.
 *
 * @return string
 */
function bp_get_voted_comments_slug() {
    return apply_filters( 'cace_bp_voted_comments_slug', 'voted' );
}

/**
 * Set up BuddyPress integration
 */
function bp_setup() {
    if ( ! function_exists( 'buddypress' ) ) {
        /**
         * Create helper for BuddyPress 1.6 and earlier.
         *
         * @return bool
         */
        function buddypress() {
            return isset( $GLOBALS['bp'] ) ? $GLOBALS['bp'] : false;
        }
    }

    // Bail if in maintenance mode.
    if ( ! buddypress() || buddypress()->maintenance_mode ) {
        return;
    }

    /* Activate our custom components */
    bp_activate_components();

    // Comments.
    $component_id = bp_comments_component_id();

    if ( bp_is_active( $component_id ) ) {
        require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/plugins/buddypress/class-comments-bp-component.php';

        $component = new Comments_BP_Component();
        buddypress()->$component_id = $component;
    }
}

/**
 * Register custom components
 *
 * @param array  $components        Registered components.
 * @param string $type              Component type.
 *
 * @return array
 */
function bp_register_custom_components( $components, $type ) {
    if ( in_array( $type, array( 'all', 'optional' ), true ) ) {

        // Comments.
        $comments_id = bp_comments_component_id();

        $components[ $comments_id ] = array(
            'title'       => _x( 'Comments', 'BuddyPress component name', 'cace' ),
            'description' => _x( 'Allow your users to manage their comments directly from within their profiles.', 'BuddyPress component description', 'cace' ),
        );
    }

    return $components;
}

/**
 * Init our custom components states
 *
 */
function bp_activate_components() {
    $bp_components = get_option( 'cace_bp_components' );

    if ( 'loaded' !== $bp_components ) {
        $bp_active_components = bp_get_option( 'bp-active-components', array() );

        $bp_active_components[ bp_comments_component_id() ] = 1;

        bp_update_option( 'bp-active-components', $bp_active_components );
        add_option( 'cace_bp_components', 'loaded' );
    }
}

/**
 * Hook "My Comments" template into plugins template
 */
function bp_member_screen_my_comments() {
    add_action( 'bp_template_content', 'Commentace\bp_member_my_comments_content' );
    bp_core_load_template( apply_filters( 'cace_bp_member_screen_my_comments', 'members/single/plugins' ) );
}


/**
 * Comments template part
 */
function bp_member_my_comments_content() {
    get_template_part( 'buddypress/section-my' );
}

/**
 * Hook "Voted Comments" template into plugins template
 */
function bp_member_screen_voted_comments() {
    add_action( 'bp_template_content', 'Commentace\bp_member_voted_comments_content' );
    bp_core_load_template( apply_filters( 'cace_bp_member_screen_voted_comments', 'members/single/plugins' ) );
}


/**
 * Comments template part
 */
function bp_member_voted_comments_content() {
    get_template_part( 'buddypress/section-voted' );
}

/**
 * Return user comments
 *
 * @param string $type              Type of comments (my | voted).
 * @param int    $user_id           Optional. User id.
 * @param array  $extra_args        Optional. Extra arguments to filter comments by.
 *
 * @return array
 */
function bp_get_user_comments( $type, $user_id = 0, $extra_args = array() ) {
    $user_id = (int) $user_id;

    // If not set, try to get current.
    if ( 0 === $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( empty( $user_id ) ) {
        return array();
    }

    $args = wp_parse_args( array(
        'user_id'       => $user_id,
        'comments_type' => $type,
    ), $extra_args );

    $query = bp_get_comments_query( $args );

    return $query->get_comments();
}

/**
 * Set up posts query
 *
 * @param array $args           WP Query args.
 *
 * @return \WP_Comment_Query
 */
function bp_get_comments_query( $args = array() ) {
    global $wp_rewrite;

    $comments_per_page = bp_get_comments_per_page();
    $current_page      = get_paged();

    $r = array();

    if ( bp_get_voted_comments_slug() === $args[ 'comments_type' ] ) {
        $votes_type = isset( $args['votes_type'] ) ? $args['votes_type'] : '';

        $all_comments = Votes::count_user_votes( $args['user_id'], $votes_type );

        $offset = ( $current_page - 1 ) * $comments_per_page;

        $votes_on_page = Votes::find_by_user( $args['user_id'], $votes_type, $comments_per_page, $offset );

        $comment_ids = array();

        foreach ( $votes_on_page as $vote ) {
            $comment_ids[] = $vote['comment_id'];
        }

        $query = new \WP_Comment_Query( array(
            'comment__in' => ! empty( $comment_ids ) ? $comment_ids : array( -1 )
        ) );

        $comments_on_page = count( $comment_ids );
    } else {
        // Posts query args.
        $r = array(
            'number'        => $comments_per_page,
            'paged'         => $current_page,
        );

        $r = wp_parse_args( $args, $r );

        $query = new \WP_Comment_Query( $r );

        $comments_on_page = count( $query->get_comments() ); // The number of comments being displayed.

        $args['count'] = true;
        $count_query = new \WP_Comment_Query( $args );
        $all_comments  = $count_query->get_comments(); // All comments.
    }

    // Add pagination values to query object.
    $query->number      = $comments_per_page;
    $query->paged       = $current_page;
    $query->post_count  = $comments_on_page;
    $query->found_posts = $all_comments;

    // Only add pagination if query returned results.
    if ( ( (int) $query->post_count || (int) $query->found_posts ) && (int) $query->number ) {
        $base = add_query_arg( 'paged', '%#%' );

        $base = apply_filters( 'cace_comments_pagination_base', $base, $r );

        // Pagination settings with filter.
        $pagination = apply_filters( 'cace_comments_pagination', array(
            'base'      => $base,
            'format'    => '',
            'total'     => $comments_per_page === $query->found_posts ? 1 : ceil( (int) $query->found_posts / (int) $comments_per_page ),
            'current'   => (int) $query->paged,
            'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
            'next_text' => is_rtl() ? '&larr;' : '&rarr;',
            'mid_size'  => 1,
        ) );

        // Add pagination to query object.
        $query->pagination_links = paginate_links( $pagination );

        // Remove first page from pagination.
        $query->pagination_links = str_replace( $wp_rewrite->pagination_base . "/1/'", "'", $query->pagination_links );
    }

    plugin()->external_plugins()->buddypress()->comments_query = $query;

    return $query;
}

/**
 * Return number of posts to display on a single page
 *
 * @param int $default              Optional. Default value.
 *
 * @return int
 */
function bp_get_comments_per_page() {
    return apply_filters( 'cace_bp_comments_per_page', 10 );
}

/**
 * Return the pagination count
 *
 * @return string
 */
function bp_get_comments_pagination_count() {
    $query = plugin()->external_plugins()->buddypress()->comments_query;

    if ( empty( $query ) ) {
        return false;
    }

    // Set pagination values.
    $start_num = intval( ( $query->paged - 1 ) * $query->number ) + 1;
    $from_num  = number_format( $start_num );
    $to_num    = number_format( ( $start_num + ( $query->number - 1 ) > $query->found_posts ) ? $query->found_posts : $start_num + ( $query->number - 1 ) );
    $total_int = (int) ! empty( $query->found_posts ) ? $query->found_posts : $query->post_count;
    $total     = number_format( $total_int );

    // Several topics in a forum with a single page.
    if ( empty( $to_num ) ) {
        $retstr = sprintf( _n( 'Viewing %1$s comment', 'Viewing %1$s comments', $total_int, 'cace' ), $total );

        // Several topics in a forum with several pages.
    } else {
        $retstr = sprintf( _n( 'Viewing comment %2$s (of %4$s total)', 'Viewing %1$s comments - %2$s through %3$s (of %4$s total)', $total_int, 'cace' ), $query->post_count, $from_num, $to_num, $total );
    }

    // Filter and return.
    return apply_filters( 'cace_get_comments_pagination_count', esc_html( $retstr ) );
}

/**
 * Output the pagination count
 */
function bp_comments_pagination_count() {
    echo esc_html( bp_get_comments_pagination_count() );
}

/**
 * Return pagination links
 *
 * @return string
 */
function bp_get_comments_pagination_links() {
    $query = plugin()->external_plugins()->buddypress()->comments_query;

    if ( empty( $query ) ) {
        return false;
    }

    return apply_filters( 'cace_get_comments_pagination_links', $query->pagination_links );
}

/**
 * Output pagination links
 */
function bp_comments_pagination_links() {
    echo filter_var( bp_get_comments_pagination_links() );
}

function bp_widget_recent_comments_query_args( $query_args ) {
    // We are on BP profile page.
    if ( function_exists( 'bp_get_displayed_user' ) && $user = bp_get_displayed_user() ) {
        // Make sure there is no post filter applied.
        if ( isset( $query_args['post_id'] ) ) {
            unset( $query_args['post_id'] );
        }

        $query_args['author__in'] = array( $user->id );
    }

    return $query_args;
}

function bp_add_view_all_comments_link( $where ) {
    if ( 'after' !== $where ) {
        return;
    }

    $on_bp_profile_page = function_exists( 'bp_get_displayed_user' ) && $user = bp_get_displayed_user();

    if ( ! $on_bp_profile_page ) {
        return;
    }

    $url = bp_core_get_user_domain( $user->id ) . bp_comments_component_id();

    if ( ! empty( $url ) ) {
        ?>
        <p class="cace-more-results">
            <a class="g1-link g1-link-s g1-link-right" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'View all comments', 'cace' ); ?></a>
        </p>
        <?php
    }
}
