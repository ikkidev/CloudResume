<?php

namespace NewfoldLabs\WP\Module\NextSteps\DTOs;

/**
 * Section Data Transfer Object
 *
 * Represents a section that contains multiple tasks
 */
class Section {

	/**
	 * Section identifier
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Section label
	 *
	 * @var string
	 */
	public $label;

	/**
	 * Section description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Section state (open or closed)
	 *
	 * @var boolean
	 */
	public $open;

	/**
	 * Section tasks
	 *
	 * @var Task[]
	 */
	public $tasks;

	/**
	 * Call-to-action (CTA) for the section.
	 *
	 * @var mixed|null
	 */
	public $cta;

	/**
	 * Status of the section (e.g. 'new', 'done', 'dismissed'
	 *
	 * @var string
	 */
	public $status;

	/**
	 * Date when the section was completed or dismissed.
	 *
	 * @var string|null
	 */
	public $date_completed;

	/**
	 * Icon associated with the section.
	 *
	 * @var string
	 */
	public $icon;

	/**
	 * Title for the modal related to the section.
	 *
	 * @var string
	 */
	public $modal_title;

	/**
	 * Description for the modal related to the section.
	 *
	 * @var string
	 */
	public $modal_desc;

	/**
	 * JS Event that marks the section as complete.
	 *
	 * @var string
	 */
	public $complete_on_event;

	/**
	 * Whether the section can be dismissed.
	 *
	 * @var bool
	 */
	public $mandatory;

	/**
	 * Indicates if the section was completed by a user or the system.
	 *
	 * @var string 'user' or 'system'
	 */
	public $completed_by;

	/**
	 * Section constructor
	 *
	 * @param array $data Section data
	 */
	public function __construct( array $data = array() ) {
		$this->id                = $data['id'] ?? '';
		$this->label             = $data['label'] ?? '';
		$this->description       = $data['description'] ?? '';
		$this->open              = $data['open'] ?? false;
		$this->cta               = $data['cta'] ?? null;
		$this->status            = $data['status'] ?? 'new';
		$this->date_completed    = $data['date_completed'] ?? null;
		$this->icon              = $data['icon'] ?? '';
		$this->modal_title       = $data['modal_title'] ?? '';
		$this->modal_desc        = $data['modal_desc'] ?? '';
		$this->complete_on_event = $data['complete_on_event'] ?? '';
		$this->mandatory         = $data['mandatory'] ?? false;
		$this->tasks             = array();
		$this->completed_by      = $data['completed_by'] ?? '';

		// Convert task arrays to Task objects
		if ( isset( $data['tasks'] ) && is_array( $data['tasks'] ) ) {
			foreach ( $data['tasks'] as $task_data ) {
				if ( $task_data instanceof Task ) {
					$this->tasks[] = $task_data;
				} else {
					$this->tasks[] = Task::from_array( $task_data );
				}
			}
		}
	}

	/**
	 * Convert Section to array
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'id'                => $this->id,
			'label'             => $this->label,
			'description'       => $this->description,
			'open'              => $this->open,
			'cta'               => $this->cta,
			'status'            => $this->status,
			'date_completed'    => $this->date_completed,
			'icon'              => $this->icon,
			'modal_title'       => $this->modal_title,
			'modal_desc'        => $this->modal_desc,
			'complete_on_event' => $this->complete_on_event,
			'mandatory'         => $this->mandatory,
			'completed_by'      => $this->completed_by,
			'tasks'             => array_map(
				function ( Task $task ) {
					return $task->to_array();
				},
				$this->tasks
			),
		);
	}

	/**
	 * Merge this section with saved section data
	 * Preserves: open, status, date_completed
	 * Updates: everything else
	 *
	 * @param Section $saved_section Saved section data
	 * @return Section Merged section
	 */
	public function merge_with( Section $saved_section ): Section {
		$merged_data = $this->to_array();
		// Preserve section properties from saved data
		if ( isset( $saved_section->open ) ) {
			$merged_data['open'] = $saved_section->open;
		}
		if ( ! empty( $saved_section->status ) ) {
			$merged_data['status'] = $saved_section->status;
		}
		if ( ! empty( $saved_section->date_completed ) ) {
			$merged_data['date_completed'] = $saved_section->date_completed;
		}
		// Merge tasks recursively
		$merged_tasks = array();
		foreach ( $this->tasks as $task ) {
			// Find matching saved task by ID
			$saved_task = null;
			foreach ( $saved_section->tasks as $saved_task_candidate ) {
				if ( $saved_task_candidate->id === $task->id ) {
					$saved_task = $saved_task_candidate;
					break;
				}
			}
			if ( $saved_task ) {
				$merged_tasks[] = $task->merge_with( $saved_task );
			} else {
				$merged_tasks[] = $task;
			}
		}
		$merged_data['tasks'] = array_map(
			function ( Task $task ) {
				return $task->to_array();
			},
			$merged_tasks
		);
		return new Section( $merged_data );
	}

	/**
	 * Create Section from array
	 *
	 * @param array $data Section data
	 * @return Section
	 */
	public static function from_array( array $data ): Section {
		return new self( $data );
	}

	/**
	 * Add task to section
	 *
	 * @param Task $task Task to add
	 * @return bool
	 */
	public function add_task( Task $task ): bool {
		// Check if task with same ID already exists
		foreach ( $this->tasks as $existing_task ) {
			if ( $existing_task->id === $task->id ) {
				return false; // Task already exists
			}
		}

		$this->tasks[] = $task;
		$this->sort_tasks();
		return true;
	}

	/**
	 * Remove task from section
	 *
	 * @param string $task_id Task ID to remove
	 * @return bool
	 */
	public function remove_task( string $task_id ): bool {
		foreach ( $this->tasks as $index => $task ) {
			if ( $task->id === $task_id ) {
				unset( $this->tasks[ $index ] );
				$this->tasks = array_values( $this->tasks ); // Reindex
				return true;
			}
		}
		return false;
	}

	/**
	 * Get task by ID
	 *
	 * @param string $task_id Task ID
	 * @return Task|null
	 */
	public function get_task( string $task_id ): ?Task {
		foreach ( $this->tasks as $task ) {
			if ( $task->id === $task_id ) {
				return $task;
			}
		}
		return null;
	}

	/**
	 * Update task status
	 *
	 * @param string $task_id Task ID
	 * @param string $status New status
	 * @return bool
	 */
	public function update_task_status( string $task_id, string $status ): bool {
		$task = $this->get_task( $task_id );
		if ( $task ) {
			$result = $task->update_status( $status );
			if ( $result ) {
				// Re-evaluate section status based on task states
				$this->sync_section_status_with_tasks();
			}
			return $result;
		}
		return false;
	}

	/**
	 * Update section status
	 *
	 * @param string $status New status
	 * @return bool
	 */
	public function update_status( string $status ): bool {
		if ( ! in_array( $status, array( 'new', 'dismissed', 'done' ), true ) ) {
			return false;
		}
		$this->status = $status;
		// automatically record completed/dismissed
		if ( in_array( $status, array( 'dismissed', 'done' ), true ) ) {
			$this->set_completed_now();
			// If marking section as done, mark all active tasks as done too
			if ( 'done' === $status ) {
				$this->mark_all_active_tasks_complete();
			}
			// If marking section as dismissed, mark all active tasks as dismissed too
			if ( 'dismissed' === $status ) {
				$this->mark_all_active_tasks_dismissed();
			}
		} else {
			// reset date completed if marked as new
			$this->clear_completed_date();
		}

		return true;
	}

	/**
	 * Set completed now
	 */
	public function set_completed_now(): bool {
		$now = new \DateTime( 'now', new \DateTimeZone( wp_timezone_string() ) );
		$this->set_date_completed( $now->format( 'Y-m-d H:i:s' ) );
		return true;
	}

	/**
	 * Clear completed date
	 */
	public function clear_completed_date(): bool {
		$this->set_date_completed( null );
		return true;
	}

	/**
	 * Mark all active (non-dismissed) tasks as complete
	 *
	 * Used when a section is marked as complete to ensure all tasks are also complete
	 *
	 * @return int Number of tasks that were updated
	 */
	public function mark_all_active_tasks_complete(): int {
		$updated_count = 0;
		foreach ( $this->tasks as $task ) {
			if ( ! $task->is_dismissed() && ! $task->is_completed() ) {
				$task->update_status( 'done' );
				++$updated_count;
			}
		}
		return $updated_count;
	}

	/**
	 * Mark all active (non-dismissed) tasks as dismissed
	 *
	 * Used when a section is dismissed to ensure all tasks are also dismissed
	 *
	 * @return int Number of tasks that were updated
	 */
	public function mark_all_active_tasks_dismissed(): int {
		$updated_count = 0;
		foreach ( $this->tasks as $task ) {
			if ( ! $task->is_dismissed() ) {
				$task->update_status( 'dismissed' );
				++$updated_count;
			}
		}
		return $updated_count;
	}

	/**
	 * Sync section status with task states
	 *
	 * Updates section status based on current task states:
	 * - If all active tasks are completed, section becomes 'done' with completion date
	 * - If any active task is 'new' and section is 'done', section becomes 'new' and completion date is cleared
	 * - Dismissed tasks are ignored in this logic
	 */
	private function sync_section_status_with_tasks(): void {
		if ( empty( $this->tasks ) ) {
			return;
		}

		// Use completion logic to check if all active tasks are done
		$section_should_be_completed = $this->is_completed();

		// Check if any active task is new (only matters if section is currently marked done)
		// essentially if a task was unchecked, the section should be marked as new also
		$has_new_task = false;
		if ( 'done' === $this->status ) {
			foreach ( $this->tasks as $task ) {
				if ( ! $task->is_dismissed() && 'new' === $task->status ) {
					$has_new_task = true;
					break;
				}
			}
		}

		if ( $section_should_be_completed && 'done' !== $this->status ) {
			// All active tasks done - mark section as done
			$this->update_status( 'done' );
		} elseif ( $has_new_task ) {
			// Has new tasks but section is marked done - revert to new
			$this->update_status( 'new' );
		}
	}


	/**
	 * Sort tasks by priority
	 */
	public function sort_tasks(): void {
		usort(
			$this->tasks,
			function ( Task $a, Task $b ) {
				return $a->priority <=> $b->priority;
			}
		);
	}

	/**
	 * Get section completion percentage
	 *
	 * Calculates completion based on active (non-dismissed) tasks only
	 *
	 * @return int
	 */
	public function get_completion_percentage(): int {
		if ( empty( $this->tasks ) ) {
			return 0;
		}

		// Filter out dismissed tasks
		$active_tasks = array_filter(
			$this->tasks,
			function ( Task $task ) {
				return ! $task->is_dismissed();
			}
		);

		if ( empty( $active_tasks ) ) {
			return 0;
		}

		$completed_tasks = array_filter(
			$active_tasks,
			function ( Task $task ) {
				return $task->is_completed();
			}
		);

		return intval( ( count( $completed_tasks ) / count( $active_tasks ) ) * 100 );
	}

	/**
	 * Check if section is completed
	 *
	 * @return bool
	 */
	public function is_completed(): bool {
		return $this->get_completion_percentage() === 100;
	}

	/**
	 * Get count of completed tasks
	 *
	 * @return int
	 */
	public function get_completed_tasks_count(): int {
		return count(
			array_filter(
				$this->tasks,
				function ( Task $task ) {
					return $task->is_completed();
				}
			)
		);
	}

	/**
	 * Get total tasks count
	 *
	 * @return int
	 */
	public function get_total_tasks_count(): int {
		return count( $this->tasks );
	}

	/**
	 * Set section open state
	 *
	 * @param bool $open Open state
	 * @return bool
	 */
	public function set_open( bool $open ): bool {
		$this->open = $open;
		return true;
	}

	/**
	 * Set section status state
	 *
	 * @param string $status Status state
	 * @return bool
	 */
	public function set_status( string $status ): bool {
		return $this->update_status( $status );
	}

	/**
	 * Set date completed or dismissed
	 *
	 * @param string|null $date Date string or null
	 * @return bool
	 */
	public function set_date_completed( ?string $date ): bool {
		$this->date_completed = $date;
		return true;
	}

	/**
	 * Set if the section is completed by a user or the system
	 *
	 * @param string $type 'user' or 'system'
	 * @return bool
	 */
	public function set_completed_by( string $type = 'user' ): bool {
		$this->completed_by = $type;
		return true;
	}

	/**
	 * Check if section is open
	 *
	 * @return bool
	 */
	public function is_open(): bool {
		return $this->open;
	}

	/**
	 * Toggle section open state
	 *
	 * @return bool New open state
	 */
	public function toggle_open(): bool {
		$this->open = ! $this->open;
		return $this->open;
	}

	/**
	 * Validate section data
	 *
	 * @return bool|string True if valid, error message if not
	 */
	public function validate() {
		if ( empty( $this->id ) ) {
			return 'Section ID is required';
		}

		if ( empty( $this->label ) ) {
			return 'Section label is required';
		}

		// Validate all tasks
		foreach ( $this->tasks as $task ) {
			$task_validation = $task->validate();
			if ( true !== $task_validation ) {
				return "Task validation failed: {$task_validation}";
			}
		}

		return true;
	}
}
