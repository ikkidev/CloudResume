<?php
/**
 * BBPress Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Set global to count topics in loop.
global $adace_count_bbpress_topics;
global $adace_count_bbpress_replies;
$adace_count_bbpress_topics = 0;
$adace_count_bbpress_replies = 0;

add_filter( 'adace_get_supported_post_types', 'adace_add_bbpress_post_type_support' );
/**
 * Add BBPress post types to supported post types
 *
 * @param array $supported_post_types Supported post types.
 */
function adace_add_bbpress_post_type_support( $supported_post_types ) {
	$supported_post_types['forum'] = esc_html__( 'Forum', 'adace' );
	$supported_post_types['topic'] = esc_html__( 'Topic', 'adace' );
	$supported_post_types['reply'] = esc_html__( 'Reply', 'adace' );
	return $supported_post_types;
}

/**
 * Get BBPress topics slot id.
 *
 * @return string Id for bbpress needs.
 */
function adace_get_before_bbpress_topics_slot_id() {
	return 'adace-after-x-bbpress-topics';
}

add_action( 'after_setup_theme', 'adace_add_bbpress_section', 20 );
/**
 * Add snax section
 */
function adace_add_bbpress_section() {
	adace_register_ad_section( 'bbpress', __( 'bbPress', 'adace' ) );
}


/**
* Register after X Topics
*/
adace_register_ad_slot(
	array(
		'id'      => adace_get_before_bbpress_topics_slot_id(),
		'name'    => esc_html__( 'Before X Topics', 'adace' ),
		'section' => 'bbpress',
		'options' => array(
			'is_singular'          => array( 'forum' ),
			'is_singular_editable' => false,
		),
		'custom_options' => array(
			'before_x_bbpress_topics'          => 2,
			'before_x_bbpress_topics_editable' => true,
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_before_bbpress_topics_slot_option', 10, 2 );
/**
 * Add option for before topic slot.
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot ad.
 */
function adace_before_bbpress_topics_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_before_bbpress_topics_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['before_x_bbpress_topics'] = esc_html__( 'Show before X topics', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_before_bbpress_topics_slot_option_renderer', 10, 2 );
/**
 * Add renderer for before bbpress topics slot option.
 *
 * @param array $args Slot args.
 * @param array $slot_options Slot options.
 */
function adace_before_bbpress_topics_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_before_bbpress_topics_slot_id() !== $args['slot']['id'] ) {
		return;
	}
	$before_x_bbpress_topics_editable = $args['slot']['custom_options']['before_x_bbpress_topics_editable'];
	if ( $before_x_bbpress_topics_editable ) {
		$before_x_bbpress_topics_current = isset( $slot_options['before_x_bbpress_topics'] ) ? $slot_options['before_x_bbpress_topics'] : $args['slot']['custom_options']['before_x_bbpress_topics'];
	} else {
		$before_x_bbpress_topics_current = $args['slot']['custom_options']['before_x_bbpress_topics'];
	}
	if ( 'before_x_bbpress_topics' === $args['field_for'] ) :
	?>
	<input
		class="small-text"
		type="number"
		id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_topics]"
		name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_topics]"
		min="1"
		max="10000"
		step="1"
		value="<?php echo( esc_html( $before_x_bbpress_topics_current ) ); ?>"
		<?php echo( esc_html( $before_x_bbpress_topics_editable ? '' : ' disabled' ) );  ?>
	/>
	<label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_topics]"><?php esc_html_e( 'Type here before how many bbpress topics display slot.', 'adace' ); ?></label>
	<?php
	endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_before_bbpress_topics_slot_option_save_validator', 10, 2 );
/**
 * Add option saving validator for before topics slot.
 *
 * @param array $input_sanitized Sanitized.
 * @param array $input Original.
 */
function adace_before_bbpress_topics_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['before_x_bbpress_topics'] ) ) {
		$input_sanitized['before_x_bbpress_topics'] = intval( filter_var( $input['before_x_bbpress_topics'], FILTER_SANITIZE_NUMBER_INT ) );
	}
	return $input_sanitized;
}

add_action( 'the_post', 'adace_before_bbpress_topics_slot_inject' );
/**
 * Add option saving validator for before paragraph slot
 *
 * @param object $post Post object.
 */
function adace_before_bbpress_topics_slot_inject( $post ) {
	// Check if topic query is set.
	if ( ! isset( bbpress() -> topic_query -> query ) ) { return; }
	// Make sure that $post is topic.
	if ( 'topic' !== $post -> post_type ) { return; }
	// Check if ad slot is displayable.
	if ( false === adace_is_ad_slot( adace_get_before_bbpress_topics_slot_id() ) ) { return; }

	$adace_ad_slots = adace_access_ad_slots();
	// Get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_before_bbpress_topics_slot_id() ];
	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_before_bbpress_topics_slot_id() . '_options' );

	$inject_before = $slot_register['custom_options']['before_x_bbpress_topics_editable'] ? $slot_options['before_x_bbpress_topics'] : $slot_register['custom_options']['before_x_bbpress_topics'];

	// Get loop counter.
	global $adace_count_bbpress_topics;
	$adace_count_bbpress_topics++;

	// Calculate current topic by getting posts_per_page * current page + loop counter.
	$topics_per_page = bbpress() -> topic_query -> query['posts_per_page'];
	$topics_page = isset( bbpress() -> topic_query -> query['paged'] ) ? ( bbpress() -> topic_query -> query['paged'] - 1 ) : 0;
	$current_topic_number = ( $topics_per_page * $topics_page ) + $adace_count_bbpress_topics;

	if ( 0 === $current_topic_number % $inject_before ) {
		echo adace_get_ad_slot( adace_get_before_bbpress_topics_slot_id() );
	}
}

/**
 * Get BBPress replies slot id.
 *
 * @return string Id for bbpress needs.
 */
function adace_get_before_bbpress_replies_slot_id() {
	return 'adace-after-x-bbpress-replies';
}

/**
* Register after X Replies
*/
adace_register_ad_slot(
	array(
		'id' => adace_get_before_bbpress_replies_slot_id(),
		'name' => esc_html__( 'Before X Replies', 'adace' ),
		'section' => 'bbpress',
		'options' => array(
			'is_singular' => array( 'topic' ),
			'is_singular_editable' => false,
		),
		'custom_options' => array(
			'before_x_bbpress_replies' => 2,
			'before_x_bbpress_replies_editable' => true,
		),
	)
);

add_filter( 'adace_options_slot_fields_filter', 'adace_before_bbpress_replies_slot_option', 10, 2 );
/**
 * Add option for before replies slot.
 *
 * @param array $slot_fields Slot fields.
 * @param array $adace_ad_slot Slot ad.
 */
function adace_before_bbpress_replies_slot_option( $slot_fields, $adace_ad_slot ) {
	if ( adace_get_before_bbpress_replies_slot_id() !== $adace_ad_slot['id'] ) {
		return $slot_fields;
	}
	$slot_fields['before_x_bbpress_replies'] = esc_html__( 'Show before X replies', 'adace' );
	return $slot_fields;
}

add_action( 'adace_options_slots_field_renderer_action', 'adace_before_bbpress_replies_slot_option_renderer', 10, 2 );
/**
 * Add renderer for before bbpress replies slot option.
 *
 * @param array $args Slot args.
 * @param array $slot_options Slot options.
 */
function adace_before_bbpress_replies_slot_option_renderer( $args, $slot_options ) {
	if ( adace_get_before_bbpress_replies_slot_id() !== $args['slot']['id'] ) {
		return;
	}

	$before_x_bbpress_replies_editable = $args['slot']['custom_options']['before_x_bbpress_replies_editable'];
	if ( $before_x_bbpress_replies_editable ) {
		$before_x_bbpress_replies_current = isset( $slot_options['before_x_bbpress_replies'] ) ? $slot_options['before_x_bbpress_replies'] : $args['slot']['custom_options']['before_x_bbpress_replies'];
	} else {
		$before_x_bbpress_replies_current = $args['slot']['custom_options']['before_x_bbpress_replies'];
	}

	if ( 'before_x_bbpress_replies' === $args['field_for'] ) :
	?>
	<input
		class="small-text"
		type="number"
		id="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_replies]"
		name="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_replies]"
		min="1"
		max="10000"
		step="1"
		value="<?php echo( esc_html( $before_x_bbpress_replies_current ) ); ?>"
		<?php echo( $before_x_bbpress_replies_editable ? '' : ' disabled' );  ?>
	/>
	<label for="<?php echo( 'adace_slot_' . esc_html( $args['slot']['id'] ) . '_options' ); ?>[before_x_bbpress_replies]"><?php esc_html_e( 'Type here before how many bbpress replies display slot.', 'adace' ); ?></label>
	<?php
	endif;
}

add_filter( 'adace_slots_options_save_validator_filter', 'adace_before_bbpress_replies_slot_option_save_validator', 10, 2 );
/**
 * Add option saving validator for before topics slot.
 *
 * @param array $input_sanitized Sanitized.
 * @param array $input Original.
 */
function adace_before_bbpress_replies_slot_option_save_validator( $input_sanitized, $input ) {
	if ( isset( $input['before_x_bbpress_replies'] ) ) {
		$input_sanitized['before_x_bbpress_replies'] = intval( filter_var( $input['before_x_bbpress_replies'], FILTER_SANITIZE_NUMBER_INT ) );
	}

	return $input_sanitized;
}

add_action( 'the_post', 'adace_before_bbpress_replies_slot_inject' );
/**
 * Add option saving validator for before paragraph slot.
 *
 * @param object $post Post object.
 */
function adace_before_bbpress_replies_slot_inject( $post ) {
	// Check if topic query is set.
	if ( ! isset( bbpress() -> reply_query -> query ) ) { return; }
	// Make sure that $post is topic.
	if ( 'reply' !== $post -> post_type ) { return; }
	// Check if ad slot is displayable.
	if ( false === adace_is_ad_slot( adace_get_before_bbpress_replies_slot_id() ) ) { return; }

	$adace_ad_slots = adace_access_ad_slots();

	// Get slot register data.
	$slot_register = $adace_ad_slots[ adace_get_before_bbpress_replies_slot_id() ];
	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . adace_get_before_bbpress_replies_slot_id() . '_options' );
	$inject_before = $slot_register['custom_options']['before_x_bbpress_replies_editable'] ? $slot_options['before_x_bbpress_replies'] : $slot_register['custom_options']['before_x_bbpress_replies'];

	// Get loop counter.
	global $adace_count_bbpress_replies;
	$adace_count_bbpress_replies++;

	// Calculate current topic by getting posts_per_page * current page + loop counter.
	$replies_per_page = bbpress() -> reply_query -> query['posts_per_page'];
	$replies_page = isset( bbpress() -> reply_query -> query['paged'] ) ? ( bbpress() -> reply_query -> query['paged'] - 1 ) : 0;
	$current_replies_number = ( $replies_per_page * $replies_page ) + $adace_count_bbpress_replies;

	if ( 0 === $current_replies_number % $inject_before ) {
		echo adace_get_ad_slot( adace_get_before_bbpress_replies_slot_id() );
	}
}

add_filter( 'adace_disable_ads_per_post', 'adace_bbpress_disable_ads_on_index', 10, 3 );

/**
 * Disable ads via settings in "Forums" page
 *
 * @param  bool   $disable  Whether to disable ad.
 * @param  string $slot_id  Slot id.
 * @return bool
 * */
function adace_bbpress_disable_ads_on_index( $disable, $slot_id ) {
	if ( bbp_is_forum_archive() ) {
		global $post;
		$post_id = $post->ID;

		$disable_array = get_post_meta( $post_id, 'adace_disable', true );
		if ( is_array( $disable_array ) ) {
			$disable_ad_all_slots	= $disable_array['adace_disable_all_slots'];
			$disable_ad_slots  		= $disable_array['adace_disable_slots'];
			$disable_ad_widgets 	= $disable_array['adace_disable_widgets'];
			$disable_ad_shortcodes 	= $disable_array['adace_disable_shortcodes'];

			if ( strpos( $slot_id, 'shortcode' ) !== false ) {
				$disable = $disable_ad_shortcodes;
			} elseif (  strpos( $slot_id, 'widget' ) !== false ) {
				$disable = $disable_ad_widgets;
			} else {
				if ( isset( $disable_ad_slots[ $slot_id ] ) ) {
					$disable = $disable_ad_slots[ $slot_id ];
				}
				if ( $disable_ad_all_slots ) {
					$disable = true;
				}
			}
		}
	}
	return $disable;
}
