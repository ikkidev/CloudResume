<?php
/**
 * Snax plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'bimber_entry_voting_box', 'bimber_snax_render_collection_item_voting_box', 10, 1 );
add_filter( 'bimber_entry_vote_count', 'bimber_snax_entry_vote_count' );
add_filter( 'bimber_show_entry_vote_count', 'bimber_snax_show_entry_vote_count', 11 );
remove_action( 'snax_post_voting_box', 'snax_render_post_voting_box' );
add_action( 'wp_loaded',               'bimber_snax_apply_voting_box_order' );

// Post collections.
add_filter( 'bimber_post_dont_miss_hide_elements_defaults',             'bimber_snax_post_collection_hide_elements_defaults' ); // Don't Miss collection.
add_filter( 'bimber_post_related_hide_elements_defaults',               'bimber_snax_post_collection_hide_elements_defaults' ); // You May Also Like collection.
add_filter( 'bimber_post_more_from_hide_elements_defaults',             'bimber_snax_post_collection_hide_elements_defaults' ); // More From collection.

// Posts widget integration.
add_filter( 'bimber_widget_posts_defaults',                             'bimber_snax_widget_posts_defaults' );
add_filter( 'bimber_widget_posts_entry_settings',                       'bimber_snax_widget_posts_settings', 10, 2 ); // Search.
add_action( 'bimber_widget_posts_hide_elements_choices',                'bimber_snax_widget_posts_hide_elements_choices', 10, 2 );
add_filter( 'bimber_widget_posts_updated_instance',                     'bimber_snax_widget_posts_updated_instance', 10, 2 );

// Customizer.
add_filter( 'bimber_post_hide_elements_choices',                        'bimber_snax_hide_elements_choices' ); // Single post.
add_filter( 'bimber_home_featured_entries_hide_elements_choices',       'bimber_snax_featured_entries_hide_elements_choices' ); // Home > Featured Entries.
add_filter( 'bimber_home_hide_elements_choices',                        'bimber_snax_hide_elements_choices' ); // Home > Main.
add_filter( 'bimber_archive_hide_elements_choices',                     'bimber_snax_hide_elements_choices' ); // Archives > Main.
add_filter( 'bimber_archive_featured_entries_hide_elements_choices',    'bimber_snax_featured_entries_hide_elements_choices' ); // Archive > Featured.
add_filter( 'bimber_search_hide_elements_choices',                      'bimber_snax_hide_elements_choices' ); // Search.
add_filter( 'bimber_post_collection_hide_elements_choices',             'bimber_snax_hide_elements_choices' ); // Post collections (You May Also Like, More From, Dont Miss).

// VC Collection element.
add_filter( 'bimber_vc_collection_params', 'bimber_snax_vc_collection_params' );

// Bimber Posts shortcode defaults.
add_filter( 'bimber_collection_shortcode_default_atts', 'bimber_snax_collection_shortcode_default_atts' );

/**
 * Render voting box
 *
 * @param string $type          Optional. Box type.
 */
function bimber_snax_render_collection_item_voting_box( $type = '' ) {
	if ( $type ) {
		snax_render_voting_box(null, 0, 'snax-voting-' . $type );
	} else {
		snax_render_voting_box(null, 0 );
	}
}

/**
 * Return post vote count
 *
 * @return int
 */
function bimber_snax_entry_vote_count() {
	return snax_get_voting_score();
}

function bimber_snax_show_entry_vote_count( $show ) {
	$voting_enabled = snax_voting_is_enabled();
	$votes_threshold_reached = function_exists( 'snax_votes_hidden' ) && ! snax_votes_hidden();

	$show = $voting_enabled && $votes_threshold_reached;

	return $show;
}

/**
 * Set default visibility on post special collection (like More From)
 *
 * @param array $choices    Select choices.
 *
 * @return array
 */
function bimber_snax_post_collection_hide_elements_defaults( $choices ) {
	$choices['voting_box'] = true;

	return $choices;
}

/**
 * Add the "Voting box" entry to the choices list
 *
 * @param array $choices    Select choices.
 *
 * @return array
 */
function bimber_snax_hide_elements_choices( $choices ) {
	// Insert after the Comments Link.
	if ( isset( $choices['comments_link'] ) ) {
		$new_choices = array();

		// Rewrite array as there is no easy way to insert new element into assoc array at the key position.
		foreach( $choices as $choice_id => $choice_label ) {
			$new_choices[ $choice_id ] = $choice_label;

			// Insert after.
			if ( 'comments_link' === $choice_id ) {
				$new_choices['votes'] = esc_html__( 'Votes', 'bimber' );
			}
		}

		$choices = $new_choices;
		// If not exists, add at the end.
	} else {
		$choices['votes'] = esc_html__( 'Votes', 'bimber' );
	}

	// Add at the end.
	$choices['voting_box'] = esc_html__( 'Voting Box', 'bimber' );

	return $choices;
}

/**
 * Add new elements to be the choices list
 *
 * @param array $choices    Select choices.
 *
 * @return array
 */
function bimber_snax_featured_entries_hide_elements_choices( $choices ) {
	// Insert after the Comments Link.
	if ( isset( $choices['comments_link'] ) ) {
		$new_choices = array();

		// Rewrite array as there is no easy way to insert new element into assoc array at the key position.
		foreach( $choices as $choice_id => $choice_label ) {
			$new_choices[ $choice_id ] = $choice_label;

			// Insert after.
			if ( 'comments_link' === $choice_id ) {
				$new_choices['votes'] = esc_html__( 'Votes', 'bimber' );
			}
		}

		$choices = $new_choices;
		// If not exists, add at the end.
	} else {
		$choices['votes'] = esc_html__( 'Votes', 'bimber' );
	}

	return $choices;
}

/**
 * Set default settings
 *
 * @param array $settings       Settings.
 *
 * @return array
 */
function bimber_snax_widget_posts_settings( $settings, $instance ) {
	if ( isset( $instance['entry_votes'] ) ) {
		$settings['elements']['votes'] = $instance['entry_votes'];
	}

	if ( isset( $instance['entry_voting_box'] ) ) {
		$settings['elements']['voting_box'] = $instance['entry_voting_box'];
	}


	return $settings;
}

/**
 * Render choices in the "Hide Elements" section.
 *
 * @param array $instance       Widget instance.
 */
function bimber_snax_widget_posts_hide_elements_choices( $widget, $instance ) {
	?>
	<input class="checkbox" type="checkbox" <?php checked( $instance['entry_votes'], false ) ?>
	       id="<?php echo esc_attr( $widget->get_field_id( 'entry_votes' ) ); ?>"
	       name="<?php echo esc_attr( $widget->get_field_name( 'entry_votes' ) ); ?>"/>
	<label
		for="<?php echo esc_attr( $widget->get_field_id( 'entry_votes' ) ); ?>"><?php esc_html_e( 'Votes', 'bimber' ); ?></label><br/>

	<input class="checkbox" type="checkbox" <?php checked( $instance['entry_voting_box'], false ) ?>
	       id="<?php echo esc_attr( $widget->get_field_id( 'entry_voting_box' ) ); ?>"
	       name="<?php echo esc_attr( $widget->get_field_name( 'entry_voting_box' ) ); ?>"/>
	<label
		for="<?php echo esc_attr( $widget->get_field_id( 'entry_voting_box' ) ); ?>"><?php esc_html_e( 'Voting Box', 'bimber' ); ?></label><br/>
	<?php
}

/**
 * Set widget defaults
 *
 * @param array $defaults       Defaults.
 *
 * @return mixed
 */
function bimber_snax_widget_posts_defaults( $defaults ) {
	$defaults['entry_votes']       = false;
	$defaults['entry_voting_box']  = false;

	return $defaults;
}

/**
 * Save widget
 *
 * @param array $instance           Current values.
 * @param array $new_instance       New values.
 *
 * @return array
 */
function bimber_snax_widget_posts_updated_instance( $instance, $new_instance ) {
	$instance['entry_votes']       = empty( $new_instance['entry_votes'] );
	$instance['entry_voting_box']  = empty( $new_instance['entry_voting_box'] );

	return $instance;
}

/**
 * Load Snax voting box in right order on a single post page
 */
function bimber_snax_apply_voting_box_order() {
	add_action( 'bimber_after_single_content', 'bimber_snax_render_post_voting_box', bimber_get_theme_option( 'post', 'voting_box_order' ) );
}

function bimber_snax_render_post_voting_box() {
	// Single post element visible?
	$elements_to_hide_str = bimber_get_theme_option( 'post', 'hide_elements' );
	$elements_to_hide_arr = explode( ',', $elements_to_hide_str );

	if ( in_array( 'voting_box', $elements_to_hide_arr, true ) ) {
		return;
	}

	?>
	<div class="snax snax-post-container">

		<?php snax_render_post_voting_box(); ?>

	</div>
	<?php
}

function bimber_snax_vc_collection_params( $params ) {
    if ( ! function_exists( 'snax_voting_is_enabled' ) ) {
        return $params;
    }

	if ( ! snax_voting_is_enabled() ) {
		return $params;
	}

	$params[242] = array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Votes', 'bimber' ),
		'param_name' 	=> 'show_votes',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	);

	$params[302] = array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Voting Box', 'bimber' ),
		'param_name' 	=> 'show_voting_box',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	);

	return $params;
}

function bimber_snax_collection_shortcode_default_atts( $atts ) {
    if ( ! function_exists( 'snax_voting_is_enabled' ) ) {
        return $atts;
    }


    if ( snax_voting_is_enabled() ) {
		$atts['show_votes']      = 'standard';
		$atts['show_voting_box'] = 'standard';
	}

	return $atts;
}