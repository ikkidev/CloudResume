<?php
/**
 * Task State Validator for Next Steps Module.
 *
 * @package WPPluginBluehost
 */

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;

/**
 * TaskStateValidator
 *
 * Validates existing site state against task completion criteria.
 * Uses a registry pattern to automatically discover and run validation checks
 * for tasks that may already be completed on existing sites when next steps initializes.
 */
class TaskStateValidator {

	/**
	 * Registry of task validators
	 *
	 * Format: ['plan_id.track_id.section_id.task_id' => callable]
	 *
	 * @var array
	 */
	private static $validators = array();

	/**
	 * Register a task state validator
	 *
	 * @param string   $task_path Task path in format 'plan_id.track_id.section_id.task_id'
	 * @param callable $validation_callback Callback that returns bool indicating if task should be complete
	 * @return void
	 */
	public static function register_validator( string $task_path, callable $validation_callback ): void {
		self::$validators[ $task_path ] = $validation_callback;
	}

	/**
	 * Validate existing state for all registered tasks
	 *
	 * Checks current site state against all registered validators and marks
	 * tasks as complete if their conditions are already met.
	 *
	 * @param Plan $plan The plan to validate tasks for
	 * @return array Array of task paths that were marked complete
	 */
	public static function validate_existing_state( Plan $plan ): array {
		$completed_tasks = array();

		foreach ( self::$validators as $task_path => $validation_callback ) {
			// Parse the task path
			$path_parts = explode( '.', $task_path );
			if ( count( $path_parts ) !== 4 ) {
				continue; // Invalid path format - should be plan_id.track_id.section_id.task_id
			}

			list( $plan_id, $track_id, $section_id, $task_id ) = $path_parts;

			// Only validate for the current plan ID
			if ( $plan_id !== $plan->id ) {
				continue;
			}

			// Check if task exists and is not already complete
			if ( ! $plan->has_exact_task( $track_id, $section_id, $task_id ) ) {
				continue;
			}

			$task = $plan->get_task( $track_id, $section_id, $task_id );
			if ( $task && $task->is_completed() ) {
				continue; // Already complete
			}

			// Run the validation callback
			try {
				$should_be_complete = call_user_func( $validation_callback );

				if ( $should_be_complete ) {
					// Mark task as complete
					$success = $plan->update_task_status( $track_id, $section_id, $task_id, 'done' );

					if ( $success ) {
						$track   = $plan->get_track( $track_id );
						$section = $track->get_section( $section_id );
						if ( 'done' === $section->status ) {
							$section->set_completed_by( 'system' );
						}

						$completed_tasks[] = $task_path;
					}
				}
			} catch ( \Exception $e ) {
				// Log error but continue with other validators
				// error_log( "TaskStateValidator error for {$task_path}: " . $e->getMessage() );
			}
		}

		return $completed_tasks;
	}


	/**
	 * Get all registered validators
	 *
	 * @return array Array of registered validators
	 */
	public static function get_registered_validators(): array {
		return self::$validators;
	}

	/**
	 * Clear all registered validators (useful for testing)
	 *
	 * @return void
	 */
	public static function clear_validators(): void {
		self::$validators = array();
	}

	/**
	 * Get count of registered validators
	 *
	 * @return int Number of registered validators
	 */
	public static function get_validator_count(): int {
		return count( self::$validators );
	}
}
