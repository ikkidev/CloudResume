<?php
/**
 * Recent Comments Widget
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


/**
 * Class Widget_Recent_Comments
 */
class Widget_Recent_Comments extends \WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cace_recent_comments',                              // Base ID.
			esc_html__( '(CommentAce) Recent Comments', 'cace' ),    // Name
			array(                                                              // Args.
				'description' => esc_html__( 'Display recent comments, with animated GIFs', 'cace' ),
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
        $instance = wp_parse_args( $instance, $this->get_defaults() );

        $title = apply_filters( 'widget_title', $instance['title'] );

        // HTML class.
        $classes = array( 'cace-widget-recent-comments' );

        echo wp_kses_post( $args['before_widget'] );

        if ( ! empty( $title ) ) {
            echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
        }

		$comments_query = new \WP_Comment_Query( $this->get_comments_query_args( $instance ) );
		$comments = $comments_query->comments;
        ?>
        <div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
	        <?php
	        if ( $comments ) {
	            get_template_part( 'collections/list', null, array( 'comments' => $comments ) );

	            do_action( 'cace_widget_recent_comments_list', 'after' );
	        }
	        ?>
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
		$instance = wp_parse_args( $instance, $this->get_defaults() );
		?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html_x( 'Title', 'Widget', 'cace' ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['title'] ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"><?php echo esc_html_x( 'Number of comments to show', 'Widget', 'cace' ); ?>:</label>
            <input class="tiny-text" size="3" type="number" min="1" step="1" name="<?php echo esc_attr( $this->get_field_name( 'max' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"
                   value="<?php echo esc_attr( $instance['max'] ) ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>"><?php echo esc_html_x( 'Sort by','Widget', 'cace' ); ?>:</label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'sort_by' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>">
                <option value="recent"<?php selected( 'recent', $instance['sort_by'] ); ?>><?php echo esc_html_x( 'Recent', 'Widget', 'cace' ); ?></option>
                <option value="most_voted"<?php selected( 'most_voted', $instance['sort_by'] ); ?>><?php echo esc_html_x( 'Most Voted', 'Widget', 'cace' ); ?></option>
            </select>
        </p>

        <p>
            <input type="checkbox"
                   name="<?php echo esc_attr( $this->get_field_name( 'from_single_post' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'from_single_post' ) ); ?>"
                   value="standard"
                    <?php checked( 'standard', $instance['from_single_post'] ); ?>
            />
            <label for="<?php echo esc_attr( $this->get_field_id( 'from_single_post' ) ); ?>"><?php echo esc_html_x( 'On a single post, show only comments from that post', 'Widget', 'cace' ); ?></label>
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
        $instance['title']            = strip_tags( $new_instance['title'] );
        $instance['max']              = absint( $new_instance['max'] );
        $instance['sort_by']          = in_array( $new_instance['sort_by'], array( 'recent', 'most_voted' ), true ) ? $new_instance['sort_by'] : 'recent';
        $instance['from_single_post'] = ! empty(  $new_instance['from_single_post']) ? 'standard' : '';

		return $instance;
	}

    /**
     * Return default setup
     *
     * @return array
     */
	protected function get_defaults() {
	    return array(
            'title'             => __( 'Recent Comments', 'cace' ),
            'max'               => 5,
            'sort_by'           => 'recent',
            'from_single_post'  => 'standard',
        );
    }

    /**
     * Prepare query arguments based on current setup
     *
     * @param $instance
     *
     * @return array
     */
    protected function get_comments_query_args( $instance ) {
        $query_args = array(
	        'status' => 'approve',
        );

        $query_args['number'] = absint( $instance['max'] );

        if ( 'standard' === $instance['from_single_post'] && is_single() ) {
            $query_args['post_id'] = get_the_ID(); // Only comments from the current post.
        }

        $query_args['order'] = 'DESC';

        if ( 'most_voted' === $instance['sort_by'] ) {
            $query_args['meta_key'] = '_commentace_voting_total_votes';
            $query_args['orderby'] = 'meta_value_num';
        }

        return apply_filters( 'cace_widget_recent_comments_query_args', $query_args );
    }
}
