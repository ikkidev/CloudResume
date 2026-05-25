<?php
/**
 * Taxonomy Filter Widget
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


/**
 * Class Bimber_Widget_Taxonomy_Filter
 */
class Bimber_Widget_Taxonomy_Filter extends WP_Widget {

    /**
     * The total number of displayed widgets
     *
     * @var int
     */
    static $counter = 0;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'bimber_taxonomy_filter',                     // Base ID.
			esc_html__( 'Bimber Taxonomy Filter', 'bimber' ),    // Name
			array(                                                  // Args.
				'description' => esc_html__( 'Filter posts by a taxonomy', 'bimber' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	    if ( ! $this->is_filter_allowed( $instance['taxonomy'] ) ) {
	        return;
        }

        wp_enqueue_script('bimber-taxonomy-filters' );

        $queried_object = $this->get_queried_object();

        $terms = $this->get_terms( array(
            'taxonomy'     => $instance['taxonomy'],
            'parent_term'  => $queried_object && is_a( $queried_object, 'WP_Term' ) ? $queried_object : false,
            'number'       => apply_filters( 'bimber_widget_taxonomy_filter_max_terms_to_show', 99 )
        ) );

        if ( empty( $terms ) && $instance['hide_if_empty'] ) {
            return;
        }

        $taxonomies_url_var = self::get_url_var_for_taxonomies();
        $terms_url_var = self::get_url_var_for_terms( $instance['taxonomy'] );
        $chosen_tax_terms = self::get_chosen_terms();
        $chosen_terms = ! empty( $chosen_tax_terms[ $instance['taxonomy'] ] ) ? $chosen_tax_terms[ $instance['taxonomy'] ] : array();
        $selected_filters_first = 'standard' === $instance['selected_first'];
        $visible_terms = absint( $instance['view_all_after'] );
        $all_terms = count( $terms );
        $show_view_all = $visible_terms > 0 && $visible_terms < ( $all_terms - count( $chosen_terms ) );

        // Widget body.
        echo wp_kses_post( $args['before_widget'] );

        $title = apply_filters( 'widget_title', $instance['title'] );

        if ( ! empty( $title ) ) {
            echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
        }

        // HTML id.
        $html_id = 'g1-widget-taxonomy-filter-' . self::$counter;

        // HTML class.
        $classes   = array(
            'g1-widget-taxonomy-filter'
        );

        if ( empty( $terms ) ) {
            echo esc_html_x( 'No filters available', 'Widget Taxonomy Filter', 'bimber' );
        }

        ?>
        <div id="<?php echo esc_attr( $html_id ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
	        <div class="g1-filter g1-filter-tpl-block">
	            <ul class="g1-filter-items">
                    <?php if ( $selected_filters_first ): ?>
                        <?php  foreach ( $chosen_terms as $tax_term ) : ?>
                            <li class="g1-filter-item g1-filter-item-current">
                                <?php $term = get_term( $tax_term ); ?>
                                <a class="g1-filter-checkbox" href="<?php echo esc_url( $this->remove_filter_from_url( $instance['taxonomy'], $term->term_id ) ); ?>"><?php echo esc_html( $term->name); ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

	                <?php $terms_shown = 0; foreach ( $terms as $index => $term ) : ?>
                        <?php
                        $selected_term = in_array( (int) $term->term_id, $chosen_terms, true );

                        if ( $selected_filters_first && $selected_term ) {
                            continue;
                        }

                        $terms_shown++;

                        $show_filter_count = $instance['show_counts'];

                        $filter_classes = array(
                            'g1-filter-item',
                        );

                        if ( $visible_terms > 0 && $terms_shown > $visible_terms ) {
                            $filter_classes[] = 'g1-filter-item-hidden';
                        }

                        if ( $selected_term ) {
                            $filter_classes[] = 'g1-filter-item-current';
                            $show_filter_count = false;

                            $filter_url = $this->remove_filter_from_url( $instance['taxonomy'], $term->term_id );
                        } else {
                            $filter_url = add_query_arg( array(
                                $taxonomies_url_var => $this->get_filter_value( $taxonomies_url_var , $instance['taxonomy']),
                                $terms_url_var      => $this->get_filter_value( $terms_url_var, $term->term_id ),
                            ) );
                        }
                        ?>
                        <li class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $filter_classes ) ); ?>">
                            <a class="g1-filter-checkbox" href="<?php echo esc_url( $filter_url ); ?>">
                                <?php echo esc_html( $term->name ); ?>
                                <?php if ( $show_filter_count ) : ?>
                                    <span class="count"><?php echo (int) $term->count; ?></span>
                                <?php endif; ?>
                            </a>

                        </li>
	                <?php endforeach; ?>
		        </ul>
                <?php if ( $show_view_all ): ?>
		        <button class="g1-filter-items-more g1-button g1-button-xs g1-button-subtle" type="button"><?php printf( esc_html_x( 'Show All (%d)', 'Taxonomy Filter Widget', 'bimber' ), $all_terms ); ?></button>
                <?php endif; ?>
		    </div>
        </div>
        <?php

        echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
        $instance = wp_parse_args( $instance, $this->get_default_args() );

		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'bimber' ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"  value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php echo esc_html_x( 'Filter', 'Taxonomy Filter Widget', 'bimber' ); ?>:</label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>">
                <?php
                $taxonomies = $this->get_taxonomies();

                foreach ( $taxonomies as $taxonomy ) {
                    printf( '<option value="%s"%s>%s</option>', $taxonomy->name, selected( $taxonomy->name, $instance['taxonomy'] ), $taxonomy->label );
                }
                ?>

            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_counts' ) ); ?>"><?php echo esc_html_x( 'Show filter counts', 'Taxonomy Filter Widget', 'bimber' ); ?>:</label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_counts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_counts' ) ); ?>" type="checkbox"  value="standard"<?php checked( 'standard',  $instance['show_counts'] ); ?> />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'selected_first' ) ); ?>"><?php echo esc_html_x( 'Show selected filters first', 'Taxonomy Filter Widget', 'bimber' ); ?>:</label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'selected_first' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'selected_first' ) ); ?>" type="checkbox"  value="standard"<?php checked( 'standard',  $instance['selected_first'] ); ?> />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'view_all_after' ) ); ?>"><?php echo esc_html_x( 'Show "View all" button after', 'Taxonomy Filter Widget', 'bimber' ); ?></label>&nbsp;
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'view_all_after' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_after' ) ); ?>" type="number" min="0" step="1" value="<?php echo esc_attr( $instance['view_all_after'] ); ?>" />&nbsp;
            <?php echo esc_html_x( 'items', 'Taxonomy Filter Widget', 'bimber' ); ?>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hide_if_empty' ) ); ?>"><?php echo esc_html_x( 'Hide widget if no filters', 'Taxonomy Filter Widget', 'bimber' ); ?>:</label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'hide_if_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_if_empty' ) ); ?>" type="checkbox"  value="standard"<?php checked( 'standard',  $instance['hide_if_empty'] ); ?> />
        </p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['taxonomy']       = is_string( $new_instance['taxonomy'] ) ? $new_instance['taxonomy'] : '';
        $instance['show_counts']    = 'standard' === $new_instance['show_counts'] ? 'standard' : '';
        $instance['selected_first'] = 'standard' === $new_instance['selected_first'] ? 'standard' : '';
        $instance['view_all_after'] = absint( $new_instance['view_all_after'] );
        $instance['hide_if_empty']  = 'standard' === $new_instance['hide_if_empty'] ? 'standard' : '';

		return $instance;
	}

    /**
     * Get default arguments
     *
     * @return array
     */
    protected function get_default_args() {
        $defaults = array(
            'title'          => _x( 'Filter by Tags', 'Bimber Taxonomy Filter', 'bimber' ),
            'taxonomy'       => 'post_tag',
            'show_counts'    => 'standard',
            'selected_first' => 'standard',
            'view_all_after' => 5,
            'hide_if_empty'  => '',
        );

        return apply_filters( 'bimber_widget_taxonomy_filter_defaults', $defaults );
    }

    protected function get_taxonomies() {
        $args = apply_filters( 'bimber_widget_taxonomy_filter_taxonomies_args', array(
            'public' => true,
        ) );

        return get_taxonomies( $args, 'objects' );
    }

    protected function get_terms( $args ) {
        $filtered_post_ids = $this->get_filtered_post_ids( $args );

        if ( ! $args['parent_term'] ) {
            return get_terms( array(
                'taxonomy'   => $args['taxonomy'],
                'hide_empty' => true,
                'number'     => $args['number'],
                'orderby'    => 'name',
                'order'      => 'ASC',
                'count'      => true,
                'object_ids' => $filtered_post_ids,
            ) );
        }

        $post_in_clause = '';

        if ( ! empty( $filtered_post_ids ) ) {
            $post_in_clause = sprintf( ' AND p1.ID IN (%s)', implode( ',', $filtered_post_ids ) );
        }

        global $wpdb;

        $res = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT
                    t2.term_id as term_id,
                    COUNT(t2.term_id) as count,
                    t2.name as name
                FROM
                    $wpdb->posts as p1
                    LEFT JOIN $wpdb->term_relationships as tr1 ON p1.ID = tr1.object_ID
                    LEFT JOIN $wpdb->term_taxonomy as tt1 ON tr1.term_taxonomy_id = tt1.term_taxonomy_id
                    LEFT JOIN $wpdb->terms as t1 ON tt1.term_id = t1.term_id,
                
                    $wpdb->posts as p2
                    LEFT JOIN $wpdb->term_relationships as tr2 ON p2.ID = tr2.object_ID
                    LEFT JOIN $wpdb->term_taxonomy as tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
                    LEFT JOIN $wpdb->terms as t2 ON tt2.term_id = t2.term_id
                WHERE
                    tt1.taxonomy = %s AND t1.term_id = %d AND           /* Narrow posts by the parent term */
                    tt2.taxonomy = %s AND t2.term_id != (%d) AND        /* Narrow posts by the filter term, and exclude the parent term filter */    
                    p1.post_status = 'publish' AND
                    p2.post_status = 'publish' AND
                    p1.ID = p2.ID
                    $post_in_clause
                GROUP BY
                    name
                ORDER by
                    name
                LIMIT %d
                ",
                $args['parent_term']->taxonomy,
                $args['parent_term']->term_id,
                $args['taxonomy'],
                $args['parent_term']->term_id,
                $args['number']
            )
        );

        return $res;
    }

    /**
     * Get list of IDS for posts that match the filter criteria and parent term
     *
     * @param array $args   Query args
     *
     * @return array        Array with post ids that match the filter criteria
     */
    protected function get_filtered_post_ids( $args ) {
        $ids = array();
        $filter_url_var = self::get_url_var_for_taxonomies();
        $filter_by = bimber_htmlspecialchars( filter_input( INPUT_GET, $filter_url_var ) );

        if ( empty( $filter_by ) ) {
            return $ids;
        }

        $filters = explode( ',', $filter_by );

        $tax_query = array(
            'operator' => 'AND',
        );

        // Apply filters.
        foreach ( $filters as $filter ) {
            $terms_url_var = self::get_url_var_for_terms( $filter );

            $term_ids = bimber_htmlspecialchars( filter_input( INPUT_GET, $terms_url_var ) );

            if ( empty( $term_ids ) ) {
                continue;
            }

            $term_ids = array_unique( array_map( 'absint', explode( ',', $term_ids ) ) );

            $tax_query[] = array(
                'operator' => 'AND',
                'taxonomy' => $filter,
                'field'    => 'term_id',
                'terms'    => $term_ids,
            );
        }

        // Apply parent term.
        if ( $args['parent_term'] ) {
            $tax_query[] = array(
                'taxonomy' => $args['parent_term']->taxonomy,
                'field'    => 'term_id',
                'terms'    => $args['parent_term']->term_id,
            );
        }

        $posts = get_posts( array(
            'post_type' => 'any',
            'tax_query' => $tax_query,
        ) );

        foreach ( $posts as $post ) {
            $ids[] = $post->ID;
        }

        return $ids;
    }

    /**
     * Return the taxonomy filter URL varible name
     *
     * @return string
     */
    protected static function get_url_var_for_taxonomies() {
        return apply_filters( 'bimber_widget_url_var_for_taxonomies', 'filter-by-taxonomies' );
    }

    /**
     * Return the terms filter URL varible name
     *
     * @return string
     */
    protected static function get_url_var_for_terms( $taxonomy ) {
        $url_var = sprintf( '%s-terms', $taxonomy );

        return apply_filters( 'bimber_widget_url_var_for_terms', $url_var );
    }

    /**
     * Get a filter values
     *
     * @param string $filter            Taxonomy the filter belongs to
     * @param string $value             Filter term
     *
     * @return string                   All terms for the current filter
     */
    protected function get_filter_value( $filter, $value ) {
        $filter_str = bimber_htmlspecialchars( filter_input( INPUT_GET, $filter ) );
        $filter_arr = array();

        if ( ! empty( $filter_str ) ) {
            $filter_arr = explode( ',', $filter_str );
        }

        $filter_arr[] = $value;

        return implode( ',', array_unique( $filter_arr ) );
    }

    protected function remove_filter_from_url( $filter, $value ) {
        $chosen_terms = self::get_chosen_terms();
        $taxonomies_url_var = self::get_url_var_for_taxonomies();

        // Remove all current filter related args.
        $args_to_remove = array(
            $taxonomies_url_var,
        );

        foreach ( $chosen_terms as $taxonomy => $term_ids ) {
            $terms_url_var = self::get_url_var_for_terms( $taxonomy );

            $args_to_remove[] = $terms_url_var;

            if ( $filter === $taxonomy && in_array( $value, $term_ids ) ) {
                $term_index = array_search( $value, $term_ids );

                // Remove the filter.
                unset( $chosen_terms[ $taxonomy ][ $term_index ] );

                // Remove the parent taxonomy if empty.
                if ( empty( $chosen_terms[ $taxonomy ] ) ) {
                    unset( $chosen_terms[ $taxonomy ] );
                }
            }
        }

        $url = remove_query_arg( $args_to_remove );

        // Rebuild query args.
        $args_to_add = array();

        if ( ! empty( $chosen_terms ) ) {
            $args_to_add[ $taxonomies_url_var ] = implode( ',', array_keys( $chosen_terms ) );

            foreach ( $chosen_terms as $taxonomy => $term_ids ) {
                $terms_url_var = self::get_url_var_for_terms( $taxonomy );

                $args_to_add[ $terms_url_var ] = implode( ',', $term_ids );
            }
        }

        return add_query_arg( $args_to_add, $url );
    }

    public static function get_chosen_terms() {
        $terms = array();
        $taxonomies_url_var = self::get_url_var_for_taxonomies();
        $taxonomies_str = bimber_htmlspecialchars( filter_input( INPUT_GET, $taxonomies_url_var ) );

        if ( ! empty( $taxonomies_url_var ) ) {
            $taxonomies = explode( ',', $taxonomies_str );

            foreach ( $taxonomies as $taxonomy ) {
                $terms_url_var = self::get_url_var_for_terms( $taxonomy );

                $terms_str = bimber_htmlspecialchars( filter_input( INPUT_GET, $terms_url_var ) );

                $term_ids = array_filter( array_map( 'intval', explode( ',', $terms_str ) ) );

                if ( ! empty( $term_ids ) ) {
                    $terms[ $taxonomy ] = $term_ids;
                }
            }
        }

        return $terms;
    }

    protected function  is_filter_allowed( $taxonomy ) {
        if ( ! is_main_query() ) {
            return false;
        }

        // Includes: category, tag, author, date, custom post type, and custom taxonomy based archives.
        if ( ! is_archive() ) {
            return false;
        }

        $post_types = '';

        // Custom post type archives.
        if ( is_post_type_archive() ) {
            $post_types = $this->get_queried_object()->name;
        }

        // Taxonomies.
        if ( is_category() || is_tag() || is_tax() ) {
            $queried_term = $this->get_queried_object();
            $queried_term_taxonomy = get_taxonomy( $queried_term->taxonomy );

            $post_types = $queried_term_taxonomy->object_type;
        }

        if ( ! is_array( $post_types ) ) {
            $post_types = (array) $post_types;
        }

        $allowed_taxonomies = array();

        foreach ( $post_types as $post_type ) {
            $post_type_taxonomies = get_object_taxonomies( $post_type );

            $allowed_taxonomies = array_merge( $allowed_taxonomies, $post_type_taxonomies );
        }

        $allowed_taxonomies = array_unique( $allowed_taxonomies );

        if ( ! in_array( $taxonomy, $allowed_taxonomies, true ) ) {
            return false;
        }

        return apply_filters( 'bimber_widget_taxonomy_filter_is_allowed', true, $taxonomy );
    }

    protected function get_queried_object() {
        global $wp_query;

        $queried_object = $wp_query->get( 'bimber_queried_object' );

        if ( $queried_object ) {
            return $queried_object;
        }

        return get_queried_object();
    }
}
