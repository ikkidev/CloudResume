<?php
/**
 * Class to display posts in a sidebar
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

if ( ! class_exists( 'Bimber_Widget_Posts' ) ) :

	/**
	 * Class Bimber_Widget_Posts
	 */
	class Bimber_Widget_Posts extends WP_Widget {

		/**
		 * The total number of displayed widgets
		 *
		 * @var int
		 */
		static $counter = 0;

		private $cta_buttons;

		/**
		 * Bimber_Widget_Posts constructor.
		 */
		public function __construct() {
			parent::__construct(
				'bimber_widget_posts',                          // Base ID.
				esc_html__( 'Bimber Posts', 'bimber' ),         // Name.
				array(                                          // Args.
					'description' => esc_html__( 'A list of trending posts.', 'bimber' ),
				)
			);

			$this->cta_buttons =  bimber_get_post_call_to_action_buttons();

			self::$counter ++;
		}

		/**
		 * Render widget
		 *
		 * @param array $args Arguments.
		 * @param array $instance Instance of widget.
		 */
		public function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->get_default_args() );
			$instance['template'] = $this->normalize_template_value( $instance['template'] );

			$title = apply_filters( 'widget_title', $instance['title'] );

			// HTML id.
			if ( empty( $instance['id'] ) ) {
				$instance['id'] = 'g1-widget-posts-' . self::$counter;
			}

			// HTML class.
			$classes   = explode( ' ', $instance['class'] );
			$classes[] = 'g1-widget-posts';

			echo wp_kses_post( $args['before_widget'] );

			if ( ! empty( $title ) ) {
				echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
			}

			$query = new WP_Query( $this->get_query_args( $instance ) );
			?>
			<div id="<?php echo esc_attr( $instance['id'] ); ?>"
			     class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
				<?php if ( $query->have_posts() ) : ?>
					<?php
					$settings = apply_filters( 'bimber_widget_posts_entry_settings', array(
						'elements' => array(
							'featured_media' => $instance['entry_featured_media'],
							'subtitle'       => $instance['entry_subtitle'],
							'categories'     => $instance['entry_categories'],
							'summary'        => $instance['entry_summary'],
							'author'         => $instance['entry_author'],
							'avatar'         => $instance['entry_avatar'],
							'date'           => $instance['entry_date'],
							'shares'         => $instance['entry_shares'],
							'views'          => $instance['entry_views'],
							'comments_link'  => $instance['entry_comments_link'],
							'downloads'      => $instance['entry_downloads'],
							'votes'          => $instance['entry_votes'],
							'voting_box'     => $instance['entry_voting_box'],
							'call_to_action' => $instance['entry_call_to_action'],
							'action_links'   => false,
						),
						'title_show'         => false,
						'call_to_action_hide_buttons' => $this->get_call_to_action_hide_buttons( $instance ),
					), $instance );

					$settings['query']          = $query;
					$settings['title']          = '';
					$settings['title_size']     = 'h4';
					$settings['title_align']    = '';

					switch ( $instance['template'] ) {
						case 'grid-xxs':
							$settings['columns'] = 2;
							break;
						default:
							$settings['columns'] = 1;
							break;
					}

					$settings['card_style'] = $instance['card_style'];

					bimber_set_template_part_data( $settings );
					get_template_part( 'template-parts/collection/' . $instance['template'] );
					bimber_reset_template_part_data();
					wp_reset_postdata();
					?>

				<?php else : ?>
					<h4><?php esc_html_e( 'No posts match the widget criteria', 'bimber' ); ?></h4>
                    <p><?php esc_html_e( 'Please check if there are posts that match all the below criteria. If not, remove the wrong ones in the widget settings.', 'bimber' ); ?></p>
                    <ul>
                        <?php if ( ! empty( $instance['time_range'] ) ): ?>
                        <li><?php printf( esc_html_x( 'Time range: %s', 'Posts Widget', 'bimber' ), $instance['time_range'] ); ?></li>
                        <?php endif; ?>
                        <?php if ( ! empty( $instance['category_slugs'] ) ): ?>
                            <li><?php printf( esc_html_x( 'Categories: %s', 'Posts Widget', 'bimber' ), implode( ', ', $instance['category_slugs'] ) ); ?></li>
                        <?php endif; ?>
                        <?php if ( ! empty( $instance['tag_slugs'] ) ): ?>
                            <li><?php printf( esc_html_x( 'Tags: %s', 'Posts Widget', 'bimber' ), implode( ', ', $instance['tag_slugs'] ) ); ?></li>
                        <?php endif; ?>
                    </ul>
				<?php endif; ?>
			</div>
			<?php

			echo wp_kses_post( $args['after_widget'] );
		}

		private function get_call_to_action_hide_buttons( $instance ) {
			$hide_buttons = array();

			foreach ( $instance as $setting_id => $setting_value ) {
				if ( 0 === strpos( $setting_id, 'cta_' ) && $setting_value ) {
					$hide_buttons[] = str_replace( 'cta_', '', $setting_id );
				}
			}

			return implode( ',', array_filter( $hide_buttons ) );
		}

		/**
		 * Take care of depreciated template values
		 *
		 * @param $tpl string Template
		 *
		 * @return string
		 */
		private function normalize_template_value( $tpl ) {
			$mapping = array(
				'standard'      => 'grid-standard',
				'numbered'      => 'grid-fancy',
				'listxxsmall'   => 'list-xxs',
				'numberedlist'  => 'otxtlist-xs',
			);

			if ( isset( $mapping[ $tpl ] ) ) {
				$tpl = $mapping[ $tpl ];
			}

			return $tpl;
		}

		/**
		 * Get all templates
		 *
		 * @return array
		 */
		private function get_templates() {
			$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/collection/';
			return array(
				'grid-standard'         => $uri . 'grid-standard.png',
				'grid-xxs'              => $uri . 'grid-xxs.png',
				'grid-xxs-mod01'        => $uri . 'grid-xxs-mod01.png',
				'grid-xxs-mod11'        => $uri . 'grid-xxs-mod11.png',
				'grid-fancy'            => $uri . 'grid-fancy.png',
				'list-xxs'              => $uri . 'list-xxs.png',
				'list-xxs-mod01'        => $uri . 'list-xxs-mod01.png',
				'list-xxs-mod11'        => $uri . 'list-xxs-mod11.png',
				'txtlist'               => $uri . 'txtlist.png',
				'txtlist-mod11'         => $uri . 'txtlist-mod11.png',
				'txtlist-mod01'         => $uri . 'txtlist-mod01.png',
				'otxtlist-xs'           => $uri . 'otxtlist-xs.png',
			);
		}


		/**
		 * Get category choices
		 *
		 * @return array
		 */
		public function get_category_choices() {
			$choices    = array();
			$categories = get_categories( 'hide_empty=0' );

			foreach ( $categories as $category_obj ) {
				$choices[ $category_obj->slug ] = $category_obj->name;
			}

			return $choices;
		}

		/**
		 * Get tag choices
		 *
		 * @return array
		 */
		public function get_tag_choices() {
			$choices = array();
			$tags    = get_tags( 'hide_empty=0' );

			foreach ( $tags as $tag_obj ) {
				$choices[ $tag_obj->slug ] = $tag_obj->name;
			}

			return $choices;
		}

		private function post_tags_meta_box( $value, $args = array() ) {
			$defaults = array( 'taxonomy' => 'post_tag' );
			$r = wp_parse_args( $args, $defaults );
			$tax_name = esc_attr( $r['taxonomy'] );
			$taxonomy = get_taxonomy( $r['taxonomy'] );
			$user_can_assign_terms = true;
			$comma = _x( ',', 'tag delimiter', 'bimber' );
			$terms_to_edit = $value;
			if ( is_array( $terms_to_edit ) ) {
				$terms_to_edit = implode( ',', $terms_to_edit );
			}
			?>
			<div id="tagsdiv-post_tag">
				<div class="inside">
					<div class="tagsdiv" id="<?php echo $tax_name; ?>">
						<div class="jaxtag">
						<div class="nojs-tags hide-if-js">
							<label for="tax-input-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_or_remove_items; ?></label>
							<p><textarea name="<?php echo esc_attr( $this->get_field_name( 'tag_slugs' ) ); ?>[]" rows="3" cols="20" class="the-tags" id="<?php echo esc_attr( $this->get_field_id( 'tag_slugs' ) ); ?>" <?php disabled( ! $user_can_assign_terms ); ?> aria-describedby="new-tag-<?php echo $tax_name; ?>-desc"><?php echo str_replace( ',', $comma . ' ', $terms_to_edit ); // textarea_escaped by esc_attr() ?></textarea></p>
						</div>
						<?php if ( $user_can_assign_terms ) : ?>
						<div class="ui-front ajaxtag hide-if-no-js">
							<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
							<p><input data-wp-taxonomy="<?php echo $tax_name; ?>" type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="g1-newtag newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-<?php echo $tax_name; ?>-desc" value="" />
							<input type="button" class="button tagadd" value="<?php esc_attr_e( 'Add', 'bimber' ); ?>" /></p>
						</div>
						<?php elseif ( empty( $terms_to_edit ) ): ?>
							<p><?php echo $taxonomy->labels->no_terms; ?></p>
						<?php endif; ?>
						</div>
						<div class="tagchecklist"></div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Render form
		 *
		 * @param array $instance Instance of widget.
		 *
		 * @return void
		 */
		public function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->get_default_args() );
			$instance['template'] = $this->normalize_template_value( $instance['template'] );

			?>
			<div class="g1-widget-posts">
				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'bimber' ); ?>
						:</label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
					       value="<?php echo esc_attr( $instance['title'] ); ?>">
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>">
						<?php esc_html_e( 'Template', 'bimber' ); ?> :
					</label>
				</p>

				<?php $this->render_image_radio_control( $this->get_templates(), $this->get_field_name( 'template' ), $instance['template'] );?>

				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"><?php esc_html_e( 'The max. number of entries to show', 'bimber' ); ?>
						:</label>
					<input size="5" type="text" name="<?php echo esc_attr( $this->get_field_name( 'max' ) ); ?>"
					       id="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"
					       value="<?php echo esc_attr( $instance['max'] ) ?>"/>
				</p>

				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>"><?php esc_html_e( 'Sort by', 'bimber' ); ?>
						:</label>
					<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'sort_by' ) ); ?>"
					        id="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>">
						<option
							value="date"<?php selected( 'date', $instance['sort_by'] ); ?>><?php esc_html_e( 'Publish date', 'bimber' ); ?></option>
						<option
							value="comments"<?php selected( 'comments', $instance['sort_by'] ); ?>><?php esc_html_e( 'Comments', 'bimber' ); ?></option>
						<option
							value="views"<?php selected( 'views', $instance['sort_by'] ); ?>><?php esc_html_e( 'Views', 'bimber' ); ?></option>
						<option
							value="shares"<?php selected( 'shares', $instance['sort_by'] ); ?>><?php esc_html_e( 'Shares', 'bimber' ); ?></option>
						<option
							value="votes"<?php selected( 'votes', $instance['sort_by'] ); ?>><?php esc_html_e( 'Votes', 'bimber' ); ?></option>

						<option
							value="related"<?php selected( 'related', $instance['sort_by'] ); ?>><?php esc_html_e( 'You May Also Like', 'bimber' ); ?></option>
					</select>
				</p>

				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'time_range' ) ); ?>"><?php esc_html_e( 'Time Range', 'bimber' ); ?>
						:</label>
					<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'time_range' ) ); ?>"
					        id="<?php echo esc_attr( $this->get_field_id( 'time_range' ) ); ?>">
						<option
							value="day"<?php selected( 'day', $instance['time_range'] ); ?>><?php esc_html_e( 'Last 24 hours', 'bimber' ); ?></option>
						<option
							value="week"<?php selected( 'week', $instance['time_range'] ); ?>><?php esc_html_e( 'Last 7 days', 'bimber' ); ?></option>
						<option
							value="month"<?php selected( 'month', $instance['time_range'] ); ?>><?php esc_html_e( 'Last 30 days', 'bimber' ); ?></option>
						<option
							value="all"<?php selected( 'all', $instance['time_range'] ); ?>><?php esc_html_e( 'All time', 'bimber' ); ?></option>
					</select>
				</p>

				<?php
				$bimber_categories          = $this->get_category_choices();
				$bimber_selected_categories = is_array( $instance['category_slugs'] ) ? $instance['category_slugs'] : array();

				?>
				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'category_slugs' ) ); ?>"><?php esc_html_e( 'Categories (optional)', 'bimber' ); ?>
						:</label>
					<select multiple="multiple" class="widefat"
					        name="<?php echo esc_attr( $this->get_field_name( 'category_slugs' ) ); ?>[]"
					        id="<?php echo esc_attr( $this->get_field_id( 'category_slugs' ) ); ?>">
						<option value="" <?php selected( in_array( '', $bimber_selected_categories, true ) ); ?>><?php esc_html_e( '- None -', 'bimber' ); ?></option>
						<?php foreach ( $bimber_categories as $bimber_cat_slug => $bimber_cat_name ) : ?>
							<option
								value="<?php echo esc_attr( $bimber_cat_slug ); ?>"
								<?php selected( in_array( $bimber_cat_slug, $bimber_selected_categories, true ) ); ?>>
								<?php echo esc_html( $bimber_cat_name ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>

				<?php
				$bimber_tags          = $this->get_tag_choices();
				$bimber_selected_tags = is_array( $instance['tag_slugs'] ) ? $instance['tag_slugs'] : array();

				?>
				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'tag_slugs' ) ); ?>"><?php esc_html_e( 'Tags (optional)', 'bimber' ); ?>
						:</label>
						<?php $this->post_tags_meta_box( $bimber_selected_tags );?>
				</p>

				<p>
					<label><?php esc_html_e( 'Hide Elements', 'bimber' ); ?>:</label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_featured_media'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_featured_media' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_featured_media' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_featured_media' ) ); ?>"><?php esc_html_e( 'Featured Media', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_subtitle'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_subtitle' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_subtitle' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_categories'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_categories' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_categories' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_categories' ) ); ?>"><?php esc_html_e( 'Categories', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_summary'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_summary' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_summary' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_summary' ) ); ?>"><?php esc_html_e( 'Summary', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_author'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_author' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_author' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_author' ) ); ?>"><?php esc_html_e( 'Author', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_avatar'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_avatar' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_avatar' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_avatar' ) ); ?>"><?php esc_html_e( 'Avatar', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_date'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_date' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_date' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_date' ) ); ?>"><?php esc_html_e( 'Date', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_shares'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_shares' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_shares' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_shares' ) ); ?>"><?php esc_html_e( 'Shares', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_views'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_views' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_views' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_views' ) ); ?>"><?php esc_html_e( 'Views', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_comments_link'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_comments_link' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_comments_link' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_comments_link' ) ); ?>"><?php esc_html_e( 'Comments Link', 'bimber' ); ?></label><br/>

					<input class="checkbox" type="checkbox" <?php checked( $instance['entry_call_to_action'], false ) ?>
					       id="<?php echo esc_attr( $this->get_field_id( 'entry_call_to_action' ) ); ?>"
					       name="<?php echo esc_attr( $this->get_field_name( 'entry_call_to_action' ) ); ?>"/>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'entry_call_to_action' ) ); ?>"><?php esc_html_e( 'Call to Action', 'bimber' ); ?></label><br/>

					<?php do_action( 'bimber_widget_posts_hide_elements_choices', $this, $instance ); ?>
				</p>

				<p>
					<label><?php esc_html_e( 'Call to Action - Hide Buttons', 'bimber' ); ?>:</label><br/>

					<?php foreach( $this->cta_buttons as $cta_button_id => $cta_button_label ) : ?>
						<?php $cta_button_field_id = 'cta_' . $cta_button_id; ?>
						<input class="checkbox" type="checkbox" <?php checked( $instance[ $cta_button_field_id ], true ) ?>
						       id="<?php echo esc_attr( $this->get_field_id( $cta_button_field_id ) ); ?>"
						       name="<?php echo esc_attr( $this->get_field_name( $cta_button_field_id ) ); ?>"/>
						<label
							for="<?php echo esc_attr( $this->get_field_id( $cta_button_field_id ) ); ?>"><?php echo esc_html( $cta_button_label ); ?></label><br/>

					<?php endforeach; ?>
				</p>

				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'HTML id attribute (optional)', 'bimber' ); ?>
						:</label>
					<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>"
					       id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"
					       value="<?php echo esc_attr( $instance['id'] ) ?>"/>
				</p>

				<p>
					<label
						for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'HTML class(es) attribute (optional)', 'bimber' ); ?>
						:</label>
					<input class="widefat" type="text"
					       name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
					       id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
					       value="<?php echo esc_attr( $instance['class'] ) ?>"/>
				</p>
			</div>
			<?php
		}

		/**
		 * Get query arguments
		 *
		 * @param array $instance Instance.
		 *
		 * @return array
		 */
		protected function get_query_args( $instance ) {
		    global $wp_taxonomies;

		    $post_types = array( 'post' );

		    $category_post_types = $wp_taxonomies['category']->object_type;
		    $tag_post_types = $wp_taxonomies['post_tag']->object_type;

		    $post_types += $category_post_types + $tag_post_types;

            $post_types = apply_filters( 'bimber_widget_posts_supported_post_types', $post_types );

			$query_args = array(
				'posts_per_page'      => $instance['max'],
				'ignore_sticky_posts' => true,
				'post_type'           => $post_types,
			);

			// Time range.
			$query_args = bimber_time_range_to_date_query( $instance['time_range'], $query_args );

			$bimber_categories = is_array( $instance['category_slugs'] ) ? array_filter( $instance['category_slugs'] ) : array(); // Remove empty value with array_filter.

			// Categories.
			if ( ! empty( $bimber_categories ) ) {
				$query_args['category_name'] = implode( ',', $bimber_categories );
			}
			$bimber_tags = is_array( $instance['tag_slugs'] ) ? array_filter( $instance['tag_slugs'] ) : array(); // Remove empty values with array_filter.

			// Tags.
			if ( ! empty( $bimber_tags ) ) {
				$query_args['tag_slug__in'] = $bimber_tags;
			}

			switch ( $instance['sort_by'] ) {
				case 'date':
					$query_args['orderby'] = 'date';
					break;

				case 'comments':
					$query_args['orderby'] = 'comment_count';
					break;

				case 'views':
					$query_args = bimber_get_most_viewed_query_args( $query_args, 'widget_posts' );
					break;

				case 'shares':
					$query_args = bimber_get_most_shared_query_args( $query_args );
					break;

				case 'votes':
					$query_args = bimber_get_most_voted_query_args( $query_args );
					break;

				case 'related':
					$query_args['post__in'] = bimber_get_related_entries_ids( get_the_ID(), 'post', $instance['max'], $instance['max'] );
					$query_args['orderby'] = 'post__in';
					break;
			}

			return apply_filters( 'bimber_widget_posts_query_args', $query_args );
		}

		/**
		 * Get default arguments
		 *
		 * @return array
		 */
		public function get_default_args() {
			$defaults = array(
				'title'                => esc_html__( 'Trending Now', 'bimber' ),
				'max'                  => 6,
				'template'             => 'grid-standard',
				'card_style'           => 'none',
				'sort_by'              => 'date',
				'time_range'           => 'all',
				'category_slugs'       => array(),
				'tag_slugs'            => array(),
				'entry_featured_media' => true,
				'entry_subtitle'       => true,
				'entry_avatar'         => true,
				'entry_categories'     => true,
				'entry_title'          => true,
				'entry_summary'        => true,
				'entry_author'         => true,
				'entry_date'           => true,
				'entry_shares'         => true,
				'entry_views'          => true,
				'entry_comments_link'  => true,
				'entry_call_to_action'  => true,
				'entry_downloads'      => false,
				'entry_votes'          => false,
				'entry_voting_box'     => false,
				'id'                   => '',
				'class'                => '',
			);

			// Call to Action - Hide Buttons.
			foreach( $this->cta_buttons as $cta_button_id => $cta_button_label ) {
				$defaults[ 'cta_' . $cta_button_id ] = false;
			}

			return apply_filters( 'bimber_widget_posts_defaults', $defaults );
		}

		/**
		 * Update widget
		 *
		 * @param array $new_instance New instance.
		 * @param array $old_instance Old instance.
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']                = strip_tags( $new_instance['title'] );
			$instance['max']                  = absint( $new_instance['max'] );
			$instance['template']             = array_key_exists( $new_instance['template'], $this->get_templates() ) ? $new_instance['template'] : 'grid-standard';

			$instance['sort_by']              = in_array( $new_instance['sort_by'], array(
				'date',
				'comments',
				'views',
				'shares',
				'votes',
				'related'
			), true ) ? $new_instance['sort_by'] : 'date';
			$instance['time_range']           = in_array( $new_instance['time_range'], array(
				'day',
				'week',
				'month',
				'all',
			), true ) ? $new_instance['time_range'] : 'all';
			$instance['category_slugs']       = isset( $new_instance['category_slugs'] ) && is_array( $new_instance['category_slugs'] ) ? array_filter( $new_instance['category_slugs'], 'is_string' ) : array();
			$instance['tag_slugs']            = isset( $new_instance['tag_slugs'] ) && is_array( $new_instance['tag_slugs'] ) ? array_filter( $new_instance['tag_slugs'], 'is_string' ) : array();
			$instance['tag_slugs'] 			  = explode( ',',$instance['tag_slugs'][0] );
			$instance['entry_featured_media'] = empty( $new_instance['entry_featured_media'] );
			$instance['entry_subtitle']       = empty( $new_instance['entry_subtitle'] );
			$instance['entry_avatar']         = empty( $new_instance['entry_avatar'] );
			$instance['entry_categories']     = empty( $new_instance['entry_categories'] );
			$instance['entry_title']          = empty( $new_instance['entry_title'] );
			$instance['entry_summary']        = empty( $new_instance['entry_summary'] );
			$instance['entry_author']         = empty( $new_instance['entry_author'] );
			$instance['entry_date']           = empty( $new_instance['entry_date'] );
			$instance['entry_shares']         = empty( $new_instance['entry_shares'] );
			$instance['entry_views']          = empty( $new_instance['entry_views'] );
			$instance['entry_comments_link']  = empty( $new_instance['entry_comments_link'] );
			$instance['entry_call_to_action'] = empty( $new_instance['entry_call_to_action'] );
			$instance['id']                   = sanitize_html_class( $new_instance['id'] );
			$instance['class']                = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $new_instance['class'] ) ) );

			// Call to Action - Hide Buttons.
			foreach( $this->cta_buttons as $cta_button_id => $cta_button_label ) {
				$instance[ 'cta_' . $cta_button_id ] = ! empty( $new_instance[ 'cta_' . $cta_button_id ] );
			}

			$instance = apply_filters( 'bimber_widget_posts_updated_instance', $instance, $new_instance );

			return $instance;
		}

		/**
		 * Render image radio control.
		 *
		 * @param array  $values		Values.
		 * @param string $param_name	Parameter name.
		 * @param string $current_value Current value.
		 */
		private function render_image_radio_control( $values, $param_name, $current_value ) {
			?>
			<div class="bimber-image-radio-group">
				<ul class="g1ui-img-radio-items">
					<?php foreach ( $values as $name => $path ) :  ?>
						<li class="g1ui-img-radio-item">
							<label>
								<input
									name="<?php echo esc_attr( $param_name ); ?>-radio"
									type="radio"
									value="<?php echo esc_attr( $name ); ?>" <?php checked( $name, $current_value ); ?>
									onchange="bimberImageRadio(this)"
								/>
								<?php
								if ( isset( $path ) ) {
									echo '<span><img src="' . esc_url( $path ) . '" title="' . esc_attr( $path ) . '" /></span>';
								} else {
									echo '<span>' . esc_html( $path ) . '</span>';
								}
								?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
				<input name="<?php echo esc_attr( $param_name ); ?>" type="hidden" value="<?php echo esc_attr( $current_value ); ?>" />
			</div>
			<script type="text/javascript">
				(function($) {
					window.bimberImageRadio = function(element) {
						var $control = $(element).parents('.bimber-image-radio-group');
						var $hidden = $control.find('input[type=hidden]');
						var selected = '';
						$control.find('input[type=radio]:checked').each(function() {
							selected = $(this).val();
						});
						$hidden.val(selected);
					};
				})(jQuery);
			</script>
			<style>
				.g1ui-img-radio-items-cols-3{
					display: flex;
					flex-wrap: wrap;
				}
				.g1ui-img-radio-items-cols-3  .g1ui-img-radio-item {
					width: 33.333%;
				}
				.g1ui-img-radio-item span {
					display: block;
					border-width: 1px;
					border-style: solid;
					border-color: #ccc;
					opacity: 0.666;
					filter: grayscale(100%);
				}
				.g1ui-img-radio-item img {
					display: block;
					margin: 0 auto;
				}
				.g1ui-img-radio-item input[type=radio] {
					display: none;
				}
				.g1ui-img-radio-item:hover input[type=radio] + span,
				.g1ui-img-radio-item input[type=radio]:checked + span {
					background-color: #fff;
					opacity: 1;
					filter: none;
				}
			</style>
			<?php
		}
	}

endif;
