<?php
/**
 * Injections API
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

/*
 * TERMINOLOGY
 * -----------
 *
 * Position - number of current item (slot or post, starts with 1) on a list.
 *
 * Post number - number, in order, of a post (starts with 1) on a list.
 *
 * Example
 * -------
 * We are on page 1, posts per page = 5, we injected 2 slots on positions 1 and 3.
 * In result, we have list of 5 items:
 * 1 item - injected slot,  position = 1,
 * 2 item - post,           position = 2, post number = 1,
 * 3 item - injected slot,  position = 3,
 * 4 item - post,           position = 4, post number = 2,
 * 5 item - post,           position = 5, post number = 3,
 */

global $bimber_registered_injection_slots;

/**
 * Stores the slots.
 *
 * @global array $bimber_registered_injection_slots
 * @since 4.6
 */
$bimber_registered_injection_slots = array();

/**
 * Register a slot
 *
 * @since 4.6
 *
 * @param array $args           Arguments.
 *
 * @return string               Slot id on success.
 */
function bimber_register_injection_slot( $args = array() ) {
	global $bimber_registered_injection_slots;

	$i = count( $bimber_registered_injection_slots ) + 1;

	$id_is_empty                = empty( $args['id'] );
	$render_callback_is_empty   = empty( $args['render_callback'] );

	$defaults = array(
		/* translators: 1: the slot index */
		'name'              => sprintf( __( 'Injection slot %d', 'bimber' ), $i ),
		'position'          => 0,
		'repeat'            => 0,           // Repeat after each X positions.
		'description'       => '',
		'before_slot'       => '',
		'after_slot'        => '',
		'active_callback'   => '__return_true',
	);

	$slot = wp_parse_args( $args, $defaults );

	if ( $id_is_empty ) {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No "id" was set in the arguments array for the slot.', 'bimber' ), '4.6' );
	}

	if ( $render_callback_is_empty ) {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No "render_callback" was set in the arguments array for the slot.', 'bimber' ), '4.6' );
	}

	$bimber_registered_injection_slots[ $slot['id'] ] = $slot;

	/**
	 * Fires once a slot has been registered.
	 *
	 * @param array $slot Parsed arguments for the registered slot.
	 */
	do_action( 'bimber_register_injection_slot', $slot );

	return $slot['id'];
}

/**
 * Removes a slot from the list
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots         Stores the new slot in this array by slot ID.
 *
 * @param string $id                                        The ID of the slot when it was added.
 */
function bimber_unregister_injection_slot( $id ) {
	global $bimber_registered_injection_slots;

	unset( $bimber_registered_injection_slots[ $id ] );
}

/**
 * Checks if a slot is registered
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param string $id                                    The ID of the slot when it was registered.
 *
 * @return bool                                         True if the slot is registered, false otherwise.
 */
function bimber_is_registered_injection_slot( $id ) {
	global $bimber_registered_injection_slots;

	return isset( $bimber_registered_injection_slots[ $id ] );
}

/**
 * Checks if a slot is active
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param string $id                                    The ID of the slot when it was registered.
 *
 * @return bool                                         True if the slot is registered, false otherwise.
 */
function bimber_is_active_injection_slot( $id ) {
	global $bimber_registered_injection_slots;

	if ( ! bimber_is_registered_injection_slot( $id ) ) {
		return false;
	}

	return call_user_func( $bimber_registered_injection_slots[ $id ]['active_callback'] );
}

/**
 * Return a registered slot
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param string $id                                    The ID of the slot when it was registered.
 *
 * @return mixed                                        Slot data if the slot is registered, false otherwise.
 */
function bimber_get_injection_slot( $id ) {
	global $bimber_registered_injection_slots;

	if ( ! bimber_is_registered_injection_slot( $id ) ) {
		return false;
	}

	return $bimber_registered_injection_slots[ $id ];
}

/**
 * Return all registered slots
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @return array
 */
function bimber_get_injection_slots() {
	global $bimber_registered_injection_slots;

	return $bimber_registered_injection_slots;
}

/**
 * Render slot in a place of post
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param int   $post_number                            Index of a post where slot should be rendered.
 * @param array $params                                 Optional. Extra params for renderer.
 */
function bimber_render_post_injection_slots( $post_number, $current_page, $posts_per_page, $params = array() ) {
	$firt_post_on_page      = $posts_per_page * ( $current_page - 1 ) + 1;
	$injected_on_prev_pages = bimber_count_injections_before_position( $firt_post_on_page );
	$injected_on_curr_page  = bimber_slots_injected_on_page();
	$count_by_id            = array();

	// Sum slots from prev pages and current page.
	foreach( $injected_on_prev_pages['count_by_id'] as $slot_id => $injection_count ) {
		if ( ! isset( $count_by_id[ $slot_id ] ) ) {
			$count_by_id[ $slot_id ] = 0;
		}

		$count_by_id[ $slot_id ] += $injection_count;
	}

	foreach( $injected_on_curr_page['count_by_id'] as $slot_id => $injection_count ) {
		if ( ! isset( $count_by_id[ $slot_id ] ) ) {
			$count_by_id[ $slot_id ] = 0;
		}

		$count_by_id[ $slot_id ] += $injection_count;
	}

	// On each page we start with $post_number = position number (eg. if posts per page = 12 then on page = 2, first $post_number = 13 as its real position)
	// regardless of number of injected slots on previous pages.
	// We must, in such case, count position offset only based on slots injected on that current page, not on previous.
	$slots_rendered_on_page = $injected_on_curr_page['count'];

	// Final slot position is a sum of current post number and injected slots offset.
	$position = $post_number + $slots_rendered_on_page;

	// Having injected slots grouped by id, we can check which repetition of slot it is (necessary for dynamic slots like products).
	$params['count_by_id'] = $count_by_id;

	// Render slots at position.
	bimber_render_injection_slot_at( $position, $params );

	// New slots might be injected, update counters.
	$injected_on_curr_page  = bimber_slots_injected_on_page();
	$position = $post_number + $injected_on_curr_page['count'];

	// As we place slots before posts, there is no way to add slot at the last position on a page (this would require a first post from next page),
	// so for this special case we need to place slot after a post.
	$next_position = $position + 1;
	$last_position = $current_page * $posts_per_page;

	if ( $next_position === $last_position ) {
		bimber_inject_slot_after_current_post( $next_position, $params );
	}
}

function bimber_inject_slot_after_current_post( $position, $params ) {
	global $bimber_injection_slot_after;

	$bimber_injection_slot_after = array(
		'position'  => $position,
		'params'    => $params
	);
}

/**
 * Render a slot at a position
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param int   $position                               Position of the slot when it was registered.
 * @param array $params                                 Optional. Extra params for renderer.
 * @param bool  $before_post                            Slot injection location. True for "before" post, false for "after" post.
 */
function bimber_render_injection_slot_at( $position, $params = array(), $before_post = true ) {
	// Check if an active slot has been registered at the target $position.
	$slot = bimber_get_injection_slot_at( $position );

	if ( $slot ) {
		$slot = array_merge( $slot, $params );

		bimber_render_injection_slot( $slot );

		if ( $before_post ) {
			// Render all slots that should be placed before current $post_number post.
			bimber_render_injection_slot_at( $position + 1, $params );
		}
	}
}

/**
 * Render a slot
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param array $slot                                   Slot config.
 */
function bimber_render_injection_slot( $slot ) {
	ob_start();
	echo $slot['before_slot'];

	call_user_func( $slot['render_callback'], $slot );

	echo $slot['after_slot'];
	$html = ob_get_clean();
	global $bimber_skip_adace_slot_inside_collection;
	if ( true === $bimber_skip_adace_slot_inside_collection ) {
		$bimber_skip_adace_slot_inside_collection = false;
		return;
	} else {
		echo $html;
		bimber_register_page_slot_injection( $slot );
	}

}

/**
 * Register slot injection on the current page
 *
 * @param array $slot           Slot data.
 */
function bimber_register_page_slot_injection( $slot ) {
	bimber_init_slots_injected_on_page();
	global $bimber_slots_injected_on_page;

	$bimber_slots_injected_on_page['count']++;

	if ( ! isset( $bimber_slots_injected_on_page['count_by_id'][ $slot['id'] ] ) ) {
		$bimber_slots_injected_on_page['count_by_id'][ $slot['id'] ] = 0;
	}

	$bimber_slots_injected_on_page['count_by_id'][ $slot['id'] ]++;
}

/**
 * Return number of slots injected on current page
 *
 * @return int
 */
function bimber_slots_injected_on_page() {
	bimber_init_slots_injected_on_page();
	global $bimber_slots_injected_on_page;

	return $bimber_slots_injected_on_page;
}

function bimber_init_slots_injected_on_page() {
	global $bimber_slots_injected_on_page;

	if ( ! isset( $bimber_slots_injected_on_page ) ) {
		$bimber_slots_injected_on_page = array(
			'count'         => 0,
			'count_by_id'   => array(),
		);
	}
}

/**
 * Get first active slot registered for a position
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param int $position                                 Position number.
 *
 * @return mixed                                        Slot id if registered. False otherwise.
 */
function bimber_get_injection_slot_at( $position ) {
	global $bimber_registered_injection_slots;

	foreach( $bimber_registered_injection_slots as $slot_id => $slot ) {
		// Find first, active slot at the position and return it.
		$slot_at_position = $position === $slot['position'];
		$slot_at_repeated_position = false;

		// Repeat after each X positions?
		if ( $slot['repeat'] > 0 ) {
			$slot_at_repeated_position =
				$position > $slot['position'] &&                                        // Position higher than slot position.
				( 0 === ( $position - $slot['position'] ) % ( $slot['repeat'] + 1 ) );  // Modulo is equal to 0. Repeat +1 to put after position not on the position).
		}

		if ( ( $slot_at_position || $slot_at_repeated_position ) && bimber_is_active_injection_slot( $slot['id'] ) ) {
			return $slot;
		}
	}

	return false;
}

/**
 * Return number of slots injected between $start_position and $end_position collection items
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param int $start_position                           Collection item start position.
 * @param int $end_position                             Collection item end position.
 *
 * @return mixed                                        Slot id if registered. False otherwise.
 */
function bimber_count_injections_between_positions( $start_position, $end_position ) {
	$count              = 0;
	$count_by_id        = array();

	for ( $curr_pos = $start_position; $curr_pos <= $end_position; $curr_pos++ ) {
		if ( $slot = bimber_get_injection_slot_at( $curr_pos ) ) {
			$count++;

			if ( ! isset( $count_by_id[ $slot['id'] ] ) ) {
				$count_by_id[ $slot['id'] ] = 0;
			}

			$count_by_id[ $slot['id'] ]++;
		}
	}

	return array(
		'count'         => $count,
		'count_by_id'   => $count_by_id,
	);
}

/**
 * Return number of slots injected before a position
 *
 * @since 4.6
 *
 * @global array $bimber_registered_injection_slots     Registered slots.
 *
 * @param int    $position                              Slot position.
 *
 * @return array
 */
function bimber_count_injections_before_position( $position ) {
	return bimber_count_injections_between_positions( 1, $position - 1 );
}

/**
 * @param $post_number
 *
 * @return int
 */
function bimber_count_injections_before_post( $post_number ) {
	$count              = 0;
	$position           = 1;
	$curr_post_number   = 1;

	// Iterate over all posts till $post_number post.
	while ( $curr_post_number < $post_number ) {
		// For each post, check how many slots are injected before it.
		// If position doesn't hold slot, that means we iterate over all slots before $curr_post_number post
		// and we can go to next post.
		while ( $slot = bimber_get_injection_slot_at( $position ) ) {
			$count++;
			$position++;
		}

 		$curr_post_number++;
 		$position++;
  	}

  	return $count;
}
