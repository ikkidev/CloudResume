<?php

namespace NewfoldLabs\WP\Module\NextSteps\DTOs;

/**
 * Task Data Transfer Object
 *
 * Represents an individual task within a section
 */
class Task {

	/**
	 * Task identifier
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Task title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Task description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Task URL/href
	 *
	 * @var string
	 */
	public $href;

	/**
	 * Task status: 'new', 'done', 'dismissed'
	 *
	 * @var string
	 */
	public $status;

	/**
	 * Task priority (for ordering)
	 *
	 * @var int
	 */
	public $priority;

	/**
	 * Task source (module identifier)
	 *
	 * @var string
	 */
	public $source;

	/**
	 * Task data attributes for HTML elements
	 *
	 * @var array
	 */
	public $data_attributes;

	/**
	 * Task constructor
	 *
	 * @param array $data Task data
	 */
	public function __construct( array $data = array() ) {
		$this->id              = $data['id'] ?? '';
		$this->title           = $data['title'] ?? '';
		$this->description     = $data['description'] ?? '';
		$this->href            = $data['href'] ?? '';
		$this->status          = $data['status'] ?? 'new';
		$this->priority        = $data['priority'] ?? 0;
		$this->source          = $data['source'] ?? 'wp-module-next-steps';
		$this->data_attributes = $data['data_attributes'] ?? array();
	}

	/**
	 * Convert Task to array
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'id'              => $this->id,
			'title'           => $this->title,
			'description'     => $this->description,
			'href'            => $this->href,
			'status'          => $this->status,
			'priority'        => $this->priority,
			'source'          => $this->source,
			'data_attributes' => $this->data_attributes,
		);
	}

	/**
	 * Merge this task with saved task data
	 * Preserves: status
	 * Updates: everything else
	 *
	 * @param Task $saved_task Saved task data
	 * @return Task Merged task
	 */
	public function merge_with( Task $saved_task ): Task {
		$merged_data = $this->to_array();
		// Preserve status from saved task
		if ( ! empty( $saved_task->status ) ) {
			$merged_data['status'] = $saved_task->status;
		}
		return new Task( $merged_data );
	}

	/**
	 * Create Task from array
	 *
	 * @param array $data Task data
	 * @return Task
	 */
	public static function from_array( array $data ): Task {
		return new self( $data );
	}

	/**
	 * Validate task data
	 *
	 * @return bool|string True if valid, error message if not
	 */
	public function validate() {
		if ( empty( $this->id ) ) {
			return 'Task ID is required';
		}

		if ( empty( $this->title ) ) {
			return 'Task title is required';
		}

		if ( ! in_array( $this->status, array( 'new', 'done', 'dismissed' ), true ) ) {
			return 'Task status must be new, done, or dismissed';
		}

		if ( ! is_int( $this->priority ) ) {
			return 'Task priority must be an integer';
		}

		return true;
	}

	/**
	 * Update task status
	 *
	 * @param string $status New status
	 * @return bool
	 */
	public function update_status( string $status ): bool {
		if ( ! in_array( $status, array( 'new', 'done', 'dismissed' ), true ) ) {
			return false;
		}

		$this->status = $status;
		return true;
	}

	/**
	 * Set data attributes for the task
	 *
	 * @param array $data_attributes Associative array of data attributes
	 * @return void
	 */
	public function set_data_attributes( array $data_attributes ): void {
		$this->data_attributes = $data_attributes;
	}

	/**
	 * Add a single data attribute
	 *
	 * @param string $key Data attribute key (without 'data-' prefix)
	 * @param string $value Data attribute value
	 * @return void
	 */
	public function add_data_attribute( string $key, string $value ): void {
		$this->data_attributes[ $key ] = $value;
	}

	/**
	 * Remove a data attribute
	 *
	 * @param string $key Data attribute key to remove
	 * @return void
	 */
	public function remove_data_attribute( string $key ): void {
		unset( $this->data_attributes[ $key ] );
	}

	/**
	 * Get formatted data attributes for HTML output
	 *
	 * @return array Formatted data attributes with 'data-' prefix
	 */
	public function get_formatted_data_attributes(): array {
		$formatted = array();
		foreach ( $this->data_attributes as $key => $value ) {
			// Ensure key doesn't already have 'data-' prefix
			$formatted_key               = strpos( $key, 'data-' ) === 0 ? $key : 'data-' . $key;
			$formatted[ $formatted_key ] = $value;
		}
		return $formatted;
	}

	/**
	 * Check if task is completed
	 *
	 * @return bool
	 */
	public function is_completed(): bool {
		return 'done' === $this->status;
	}

	/**
	 * Check if task is dismissed
	 *
	 * @return bool
	 */
	public function is_dismissed(): bool {
		return 'dismissed' === $this->status;
	}

	/**
	 * Get task completion percentage (0 or 100)
	 *
	 * @return int
	 */
	public function get_completion_percentage(): int {
		return $this->is_completed() ? 100 : 0;
	}
}
