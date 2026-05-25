<?php

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;
use NewfoldLabs\WP\Module\NextSteps\DTOs\Track;
use NewfoldLabs\WP\Module\NextSteps\DTOs\Section;
use NewfoldLabs\WP\Module\NextSteps\DTOs\Task;

/**
 * PlanRepository
 *
 * Handles plan persistence, data management, and CRUD operations.
 * Responsible for storing, retrieving, and managing plan data.
 *
 * @package WPModuleNextSteps
 */
class PlanRepository {

	/**
	 * Option name where the current plan is stored
	 */
	const OPTION = 'nfd_next_steps';

	/**
	 * Static cache for the current plan
	 *
	 * @var Plan|null
	 */
	private static $cached_plan = null;

	/**
	 * Static cache for the raw option data
	 *
	 * @var array|null
	 */
	private static $cached_option_data = null;

	/**
	 * Flag to track if the cache is valid
	 *
	 * @var bool
	 */
	private static $cache_valid = false;

	/**
	 * Check if the cache is valid and contains a plan
	 *
	 * @return bool True if cache is valid and has a plan
	 */
	private static function is_cache_valid(): bool {
		return self::$cache_valid && null !== self::$cached_plan;
	}

	/**
	 * Cache a plan and its data
	 *
	 * @param Plan  $plan The plan to cache
	 * @param array $plan_data The raw plan data to cache
	 * @return void
	 */
	private static function cache_plan( Plan $plan, array $plan_data ): void {
		self::$cached_plan        = $plan;
		self::$cached_option_data = $plan_data;
		self::$cache_valid        = true;
	}

	/**
	 * Invalidate the static cache
	 *
	 * @return void
	 */
	public static function invalidate_cache(): void {
		self::$cached_plan        = null;
		self::$cached_option_data = null;
		self::$cache_valid        = false;
	}

	/**
	 * Reset Next Steps Data
	 *
	 * @return void
	 */
	private static function reset_next_steps_data(): void {
		self::invalidate_cache();
		delete_option( self::OPTION );
	}

	/**
	 * Get plan data from cache or database
	 *
	 * @return array The plan data array
	 */
	private static function get_plan_data(): array {
		// self::reset_next_steps_data(); // manual reset - for debugging
		// Return cached data if available
		if ( null !== self::$cached_option_data ) {
			return self::$cached_option_data;
		}
		// Get from database and cache it
		$plan_data                = get_option( self::OPTION, array() );
		$plan_data                = is_array( $plan_data ) ? $plan_data : array();
		self::$cached_option_data = $plan_data;
		return $plan_data;
	}

	/**
	 * Get the current plan, loading from database if needed
	 *
	 * This method implements three optimized code paths for maximum performance:
	 *
	 * 1. **Cache Hit Path** (most common):
	 *    - Returns cached plan immediately if cache is valid
	 *    - No database queries or expensive operations
	 *    - Zero overhead for repeated calls
	 *
	 * 2. **No Plan Path**:
	 *    - Called when no plan exists in database
	 *    - Determines current site type once
	 *    - Creates and saves appropriate plan based on site type
	 *    - Caches result for future calls
	 *
	 * 3. **Existing Plan Path**:
	 *    - Handles existing plans with different scenarios:
	 *      - **Site Type Mismatch**: Replaces non-custom plans when site type changes
	 *      - **Custom Plans**: Preserves custom plans regardless of site type
	 *      - **Version Updates**: Merges outdated plans with latest version
	 *    - Only calls determine_site_type() when actually needed
	 *    - Preserves user progress during merges
	 *
	 * @return Plan|null The current plan or null if none exists
	 */
	public static function get_current_plan(): ?Plan {
		// Return cached plan if available
		if ( self::is_cache_valid() ) {
			return self::$cached_plan;
		}
		// Get plan data (from cache or database)
		$plan_data = self::get_plan_data();
		$plan      = ! empty( $plan_data ) ? Plan::from_array( $plan_data ) : null;

		// No plan exists
		if ( ! $plan ) {
			// Create new plan based on current site type
			$site_type = PlanFactory::determine_site_type();
			$new_plan  = PlanFactory::create_plan( $site_type );
			if ( $new_plan ) {
				// Save the new plan for future use
				self::save_plan( $new_plan );
				$plan = $new_plan;
			}
		} elseif ( 'custom' !== $plan->type ) {
			// Plan exists but check if site type has changed (only for non-custom plans)
			$site_type = PlanFactory::determine_site_type();
			if ( $plan->type !== $site_type ) {
				// Site type has changed, replace with new plan
				$new_plan = PlanFactory::create_plan( $site_type );
				// Note: No merge needed since this is a new plan
				// Next Steps are unique to the site type and data across plans is not relevant
				if ( $new_plan ) {
					// Save the new plan for future use
					self::save_plan( $new_plan );
					$plan = $new_plan;
				}
			} elseif ( $plan->is_version_outdated() ) {
				// Site type matches but version is outdated, need to merge with latest plan data
				$new_plan = PlanFactory::create_plan( $plan->type );
				// Merge the saved data with the new plan (version will be updated automatically)
				$merged_plan = $new_plan->merge_with( $plan );
				// Save the merged plan with updated version
				self::save_plan( $merged_plan );
				// Check for any auto-complete validations after merge
				self::check_new_plan( $merged_plan );
				$plan = $merged_plan;
			}
		} elseif ( $plan->is_version_outdated() ) {
			// Custom plan exists and version is outdated, need to merge with latest plan data
			// For custom plans, create a new plan with the same structure but without the old version
			$plan_data = $plan->to_array();
			unset( $plan_data['version'] ); // Remove old version so new plan gets current version
			$new_plan = PlanFactory::create_plan( $plan->type, $plan_data );
			// Merge the saved data with the new plan (version will be updated automatically)
			$merged_plan = $new_plan->merge_with( $plan );
			// Save the merged plan with updated version
			self::save_plan( $merged_plan );
			// Check for any auto-complete validations after merge
			self::check_new_plan( $merged_plan );
			$plan = $merged_plan;
		}
		// Cache the result
		if ( null !== $plan ) {
			// Generate fresh plan data to ensure it matches the current plan object
			self::cache_plan( $plan, $plan->to_array() );
		}
		return $plan;
	}

	/**
	 * Save the current plan
	 *
	 * @param Plan $plan Plan to save
	 * @param bool $force_check Whether to force check for existing conditions
	 * @return bool Whether the plan was saved
	 */
	public static function save_plan( Plan $plan, bool $force_check = false ): bool {
		// Check if this is a new plan before saving
		$is_new_plan = self::is_new_plan();

		$plan_data = $plan->to_array();
		$result    = update_option( self::OPTION, $plan_data );

		// Update cache with the saved plan and data after successful save
		if ( $result ) {
			self::cache_plan( $plan, $plan_data );

			// If this was a new plan, check for existing conditions that should auto-complete tasks
			if ( $is_new_plan || $force_check ) {
				self::check_new_plan( $plan );
			}
		}
		return $result;
	}

	/**
	 * Check if a newly created plan has existing conditions that should auto-complete tasks
	 *
	 * This method validates existing site state against task completion criteria
	 * and marks applicable tasks as complete for sites that already meet the requirements.
	 *
	 * @param Plan $plan The plan to check for auto-completable tasks
	 * @return array Array of task paths that were marked complete
	 */
	public static function check_new_plan( Plan $plan ): array {
		// Validate existing state and mark applicable tasks as complete
		$completed_tasks = TaskStateValidator::validate_existing_state( $plan );

		// If tasks were completed, save the updated plan
		if ( ! empty( $completed_tasks ) ) {
			$plan_data = $plan->to_array();
			update_option( self::OPTION, $plan_data );
			self::cache_plan( $plan, $plan_data );

			// Log completed tasks for debugging
			// error_log( 'TaskStateValidator auto-completed tasks: ' . implode( ', ', $completed_tasks ) );
		}

		return $completed_tasks;
	}

	/**
	 * Check if no plan currently exists (indicating this would be a new plan)
	 *
	 * @return bool True if no plan exists, false if a plan already exists
	 */
	private static function is_new_plan(): bool {
		$plan_data = self::get_plan_data();
		return empty( $plan_data );
	}


	/**
	 * Switch to a different plan type
	 *
	 * @param string $plan_type Plan type to switch to
	 * @return Plan|false
	 */
	public static function switch_plan( string $plan_type ) {
		if (
			! in_array( $plan_type, array_values( PlanFactory::PLAN_TYPES ), true ) &&
			! in_array( $plan_type, array_keys( PlanFactory::PLAN_TYPES ), true )
		) {
			return false;
		}
		// If we received an onboarding site_type, convert it to internal plan type
		if ( array_key_exists( $plan_type, PlanFactory::PLAN_TYPES ) ) {
			$plan_type = PlanFactory::PLAN_TYPES[ $plan_type ];
		}
		// Load the appropriate plan directly
		$plan = PlanFactory::create_plan( $plan_type );

		// Save the loaded plan (this will automatically update cache and check for existing smart taskconditions)
		self::save_plan( $plan, true );
		return $plan;
	}

	/**
	 * Update task status
	 *
	 * @param string $track_id   Track ID
	 * @param string $section_id Section ID
	 * @param string $task_id    Task ID
	 * @param string $status     New status
	 * @return bool
	 */
	public static function update_task_status( string $track_id, string $section_id, string $task_id, string $status ): bool {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return false;
		}
		$updated = $plan->update_task_status( $track_id, $section_id, $task_id, $status );
		if ( $updated ) {
			return self::save_plan( $plan );
		}
		return false;
	}

	/**
	 * Get a specific task
	 *
	 * @param string $track_id   Track ID
	 * @param string $section_id Section ID
	 * @param string $task_id    Task ID
	 * @return Task|null
	 */
	public static function get_task( string $track_id, string $section_id, string $task_id ): ?Task {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return null;
		}
		return $plan->get_task( $track_id, $section_id, $task_id );
	}

	/**
	 * Add a task to a section
	 *
	 * @param string $track_id   Track ID
	 * @param string $section_id Section ID
	 * @param Task   $task       Task to add
	 * @return bool
	 */
	public static function add_task( string $track_id, string $section_id, Task $task ): bool {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return false;
		}
		$added = $plan->add_task( $track_id, $section_id, $task );
		if ( $added ) {
			return self::save_plan( $plan );
		}
		return false;
	}

	/**
	 * Reset plan to default
	 *
	 * @return Plan
	 */
	public static function reset_plan(): Plan {
		$default_plan = PlanFactory::load_default_plan();
		// save_plan will automatically update cache
		self::save_plan( $default_plan );
		return $default_plan;
	}

	/**
	 * Get plan statistics
	 *
	 * @return array The plan statistics
	 */
	public static function get_plan_stats(): array {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return array();
		}
		return array(
			'completion_percentage' => $plan->get_completion_percentage(),
			'total_tasks'           => $plan->get_total_tasks_count(),
			'completed_tasks'       => $plan->get_completed_tasks_count(),
			'total_sections'        => $plan->get_total_sections_count(),
			'completed_sections'    => $plan->get_completed_sections_count(),
			'total_tracks'          => $plan->get_total_tracks_count(),
			'completed_tracks'      => $plan->get_completed_tracks_count(),
		);
	}

	/**
	 * Update section state
	 *
	 * @param string $track_id   Track ID
	 * @param string $section_id Section ID
	 * @param string $type       Type of update ('open' or 'status')
	 * @param mixed  $value      New value
	 * @return bool Whether the section state was updated
	 */
	public static function update_section_state( string $track_id, string $section_id, string $type, $value ): bool {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return false;
		}
		$updated = false;
		if ( 'open' === $type ) {
			$updated = $plan->update_section_open( $track_id, $section_id, boolval( $value ) );
		} elseif ( 'status' === $type ) {
			$updated = $plan->update_section_status( $track_id, $section_id, $value );
		}
		if ( $updated ) {
			return self::save_plan( $plan );
		}
		return false;
	}

	/**
	 * Update track status
	 *
	 * @param string $track_id Track ID
	 * @param bool   $open     Whether track should be open/expanded
	 * @return bool Whether the track status was updated
	 */
	public static function update_track_status( string $track_id, bool $open ): bool {
		$plan = self::get_current_plan();
		if ( ! $plan ) {
			return false;
		}
		$updated = $plan->update_track_open_state( $track_id, $open );
		if ( $updated ) {
			return self::save_plan( $plan );
		}
		return false;
	}
}
