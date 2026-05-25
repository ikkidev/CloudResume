<?php
/**
 * BP Component for Comments
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

if ( ! class_exists( 'Comments_BP_Component' ) ) :
    /**
     * Loads Component for BuddyPress
     */
    class Comments_BP_Component extends \BP_Component {

        /**
         * Start the BP component creation process
         */
        public function __construct() {
            parent::start(
                bp_comments_component_id(),
                __( 'Comments', 'cace' )
            );

            $this->fully_loaded();
        }

        /**
         * Setup hooks
         */
        public function setup_actions() {

            add_filter( 'cace_comments_pagination_base',       array( $this, 'user_comments_pagination_base' ), 10, 2 );

            parent::setup_actions();
        }

        /**
         * Change pagination base url
         *
         * @param string $base          Current base url.
         * @param array  $args          WP Query args.
         *
         * @return string
         */
        public function user_comments_pagination_base( $base, $args ) {
            global $wp_rewrite;

            if ( $wp_rewrite->using_permalinks() && isset( $args['user_id'] ) ) {
                $user_id = $args['user_id'];
                $component_slug = $this->slug;

                $sub_component_slug = '';

                if ( isset( $args['comments_type'] ) ) {
                    switch ( $args['comments_type'] ) {
                        case bp_get_my_comments_slug():
                            $sub_component_slug = bp_get_my_comments_slug() . '/';
                            break;

                        case bp_get_voted_comments_slug():
                            $sub_component_slug = bp_get_voted_comments_slug() . '/';
                            break;
                    }
                }

                $base = bp_core_get_user_domain( $user_id ) . $component_slug . '/'. $sub_component_slug;

                // Use pagination base.
                $base = trailingslashit( $base ) . user_trailingslashit( $wp_rewrite->pagination_base . '/%#%/' );
            }

            return $base;
        }

        /**
         * Allow the variables, actions, and filters to be modified by third party
         * plugins and themes.
         */
        private function fully_loaded() {
            do_action_ref_array( 'cace_comments_bp_component_loaded', array( $this ) );
        }

        /**
         * Setup BuddyBar navigation
         *
         * @param array $main_nav               Component main navigation.
         * @param array $sub_nav                Component sub navigation.
         */
        public function setup_nav( $main_nav = array(), $sub_nav = array() ) {

            // Stop if there is no user displayed or logged in.
            if ( ! is_user_logged_in() && ! bp_displayed_user_id() ) {
                return;
            }

            // Comments.
            $main_nav = array(
                'name'                => __( 'Comments', 'cace' ),
                'slug'                => $this->slug,
                'position'            => 5,
                'screen_function'     => 'Commentace\bp_member_screen_my_comments',
                'default_subnav_slug' => bp_get_my_comments_slug(),
                'item_css_id'         => $this->id,
            );

            // Determine user to use.
            if ( bp_displayed_user_id() ) {
                $user_domain = bp_displayed_user_domain();
            } elseif ( bp_loggedin_user_domain() ) {
                $user_domain = bp_loggedin_user_domain();
            } else {
                return;
            }

            $component_link = trailingslashit( $user_domain . $this->slug );

            // Comments > My.
            $sub_nav[] = array(
                'name'            => _x( 'My', 'BuddyPress > Comments > Sub Nav', 'cace' ),
                'slug'            => bp_get_my_comments_slug(),
                'parent_url'      => $component_link,
                'parent_slug'     => $this->slug,
                'screen_function' => 'Commentace\bp_member_screen_my_comments',
                'position'        => 40,
                'item_css_id'     => 'my-comments',
            );

            // Comments > Voted (only for logged in user).
            $sub_nav[] = array(
                'name'            => _x( 'Voted', 'BuddyPress > Comments > Sub Nav', 'cace' ),
                'slug'            => bp_get_voted_comments_slug(),
                'parent_url'      => $component_link,
                'parent_slug'     => $this->slug,
                'screen_function' => 'Commentace\bp_member_screen_voted_comments',
                'position'        => 60,
                'item_css_id'     => 'voted-comments',
            );

            $main_nav = apply_filters( 'cace_bp_component_main_nav', $main_nav, $this->id );
            $sub_nav  = apply_filters( 'cace_bp_component_sub_nav', $sub_nav, $this->id );

            parent::setup_nav( $main_nav, $sub_nav );
        }

        /**
         * Set up the admin bar
         *
         * @param array $wp_admin_nav       Component entries in the WordPress Admin Bar.
         */
        public function setup_admin_bar( $wp_admin_nav = array() ) {

            // Menus for logged in user.
            if ( is_user_logged_in() ) {

                // Setup the logged in user variables.
                $user_domain = bp_loggedin_user_domain();
                $component_link = trailingslashit( $user_domain . $this->slug );

                // Posts.
                $wp_admin_nav[] = array(
                    'parent' => buddypress()->my_account_menu_id,
                    'id'     => 'my-account-' . $this->id,
                    'title'  => __( 'Comments', 'case' ),
                    'href'   => trailingslashit( $component_link ),
                );

                // Comments > My.
                $wp_admin_nav[] = array(
                    'parent' => 'my-account-' . $this->id,
                    'id'     => 'my-account-' . $this->id . '-my-comments',
                    'title'  => _x( 'My', 'BuddyPress > Comments > Sub Nav', 'cace' ),
                    'href'   => trailingslashit( $component_link . bp_get_my_comments_slug() ),
                );

                // Comments > Voted.
                $wp_admin_nav[] = array(
                    'parent' => 'my-account-' . $this->id,
                    'id'     => 'my-account-' . $this->id . '-voted-comments',
                    'title'  => _x( 'Voted', 'BuddyPress > Comments > Sub Nav', 'cace' ),
                    'href'   => trailingslashit( $component_link . bp_get_voted_comments_slug() ),
                );
            }

            parent::setup_admin_bar( $wp_admin_nav );
        }

        /**
         * Sets up the title for pages and <title>
         */
        public function setup_title() {
            $bp = buddypress();

            // Adjust title based on view.
            $is_component = (bool) bp_is_current_component( $this->id );

            if ( $is_component ) {
                if ( bp_is_my_profile() ) {
                    $bp->bp_options_title = __( 'Comments', 'cace' );
                } elseif ( bp_is_user() ) {
                    $bp->bp_options_avatar = bp_core_fetch_avatar( array(
                        'item_id' => bp_displayed_user_id(),
                        'type'    => 'thumb',
                    ) );

                    $bp->bp_options_title = bp_get_displayed_user_fullname();
                }
            }

            parent::setup_title();
        }
    }
endif;