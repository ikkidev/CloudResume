<?php

namespace NewfoldLabs\WP\Module\NextSteps\DTOs;

/**
 * Track Data Transfer Object
 *
 * Represents a track that contains multiple sections
 */
class Track {

	/**
	 * Track identifier
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Track label
	 *
	 * @var string
	 */
	public $label;

	/**
	 * Track description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Track state (open or closed)
	 *
	 * @var boolean
	 */
	public $open;

	/**
	 * Track sections
	 *
	 * @var Section[]
	 */
	public $sections;

	/**
	 * Track constructor
	 *
	 * @param array $data Track data
	 */
	public function __construct( array $data = array() ) {
		$this->id          = $data['id'] ?? '';
		$this->label       = $data['label'] ?? '';
		$this->description = $data['description'] ?? '';
		$this->open        = $data['open'] ?? false;
		$this->sections    = array();

		// Convert section arrays to Section objects
		if ( isset( $data['sections'] ) && is_array( $data['sections'] ) ) {
			foreach ( $data['sections'] as $section_data ) {
				if ( $section_data instanceof Section ) {
					$this->sections[] = $section_data;
				} else {
					$this->sections[] = Section::from_array( $section_data );
				}
			}
		}
	}

	/**
	 * Convert Track to array
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'id'          => $this->id,
			'label'       => $this->label,
			'open'        => $this->open,
			'description' => $this->description,
			'sections'    => array_map(
				function ( Section $section ) {
					return $section->to_array();
				},
				$this->sections
			),
		);
	}

	/**
	 * Merge this track with saved track data
	 * Preserves: open
	 * Updates: everything else
	 *
	 * @param Track $saved_track Saved track data
	 * @return Track Merged track
	 */
	public function merge_with( Track $saved_track ): Track {
		$merged_data = $this->to_array();
		// Preserve track open state from saved data
		if ( isset( $saved_track->open ) ) {
			$merged_data['open'] = $saved_track->open;
		}
		// Merge sections recursively
		$merged_sections = array();
		foreach ( $this->sections as $section ) {
			// Find matching saved section by ID
			$saved_section = null;
			foreach ( $saved_track->sections as $saved_section_candidate ) {
				if ( $saved_section_candidate->id === $section->id ) {
					$saved_section = $saved_section_candidate;
					break;
				}
			}
			if ( $saved_section ) {
				$merged_sections[] = $section->merge_with( $saved_section );
			} else {
				$merged_sections[] = $section;
			}
		}
		$merged_data['sections'] = array_map(
			function ( Section $section ) {
				return $section->to_array();
			},
			$merged_sections
		);
		return new Track( $merged_data );
	}

	/**
	 * Create Track from array
	 *
	 * @param array $data Track data
	 * @return Track
	 */
	public static function from_array( array $data ): Track {
		return new self( $data );
	}

	/**
	 * Add section to track
	 *
	 * @param Section $section Section to add
	 * @return bool
	 */
	public function add_section( Section $section ): bool {
		// Check if section with same ID already exists
		foreach ( $this->sections as $existing_section ) {
			if ( $existing_section->id === $section->id ) {
				return false; // Section already exists
			}
		}

		$this->sections[] = $section;
		return true;
	}

	/**
	 * Remove section from track
	 *
	 * @param string $section_id Section ID to remove
	 * @return bool
	 */
	public function remove_section( string $section_id ): bool {
		foreach ( $this->sections as $index => $section ) {
			if ( $section->id === $section_id ) {
				unset( $this->sections[ $index ] );
				$this->sections = array_values( $this->sections ); // Reindex
				return true;
			}
		}
		return false;
	}

	/**
	 * Get section by ID
	 *
	 * @param string $section_id Section ID
	 * @return Section|null
	 */
	public function get_section( string $section_id ): ?Section {
		foreach ( $this->sections as $section ) {
			if ( $section->id === $section_id ) {
				return $section;
			}
		}
		return null;
	}


	/**
	 * Get all tasks from all sections
	 *
	 * @return Task[]
	 */
	public function get_all_tasks(): array {
		$tasks = array();
		foreach ( $this->sections as $section ) {
			$tasks = array_merge( $tasks, $section->tasks );
		}
		return $tasks;
	}

	/**
	 * Get track completion percentage
	 *
	 * @return int
	 */
	public function get_completion_percentage(): int {
		if ( empty( $this->sections ) ) {
			return 0;
		}

		$total_percentage = 0;
		foreach ( $this->sections as $section ) {
			$total_percentage += $section->get_completion_percentage();
		}

		return intval( $total_percentage / count( $this->sections ) );
	}

	/**
	 * Check if track is completed
	 *
	 * @return bool
	 */
	public function is_completed(): bool {
		return $this->get_completion_percentage() === 100;
	}

	/**
	 * Get count of completed tasks in track
	 *
	 * @return int
	 */
	public function get_completed_tasks_count(): int {
		$count = 0;
		foreach ( $this->sections as $section ) {
			$count += $section->get_completed_tasks_count();
		}
		return $count;
	}

	/**
	 * Get total tasks count in track
	 *
	 * @return int
	 */
	public function get_total_tasks_count(): int {
		$count = 0;
		foreach ( $this->sections as $section ) {
			$count += $section->get_total_tasks_count();
		}
		return $count;
	}

	/**
	 * Get count of completed sections
	 *
	 * @return int
	 */
	public function get_completed_sections_count(): int {
		return count(
			array_filter(
				$this->sections,
				function ( Section $section ) {
					return $section->is_completed();
				}
			)
		);
	}

	/**
	 * Get total sections count
	 *
	 * @return int
	 */
	public function get_total_sections_count(): int {
		return count( $this->sections );
	}

	/**
	 * Update task status
	 *
	 * @param string $section_id Section ID
	 * @param string $task_id Task ID
	 * @param string $status New status
	 * @return bool
	 */
	public function update_task_status( string $section_id, string $task_id, string $status ): bool {
		$section = $this->get_section( $section_id );
		if ( ! $section ) {
			return false;
		}

		return $section->update_task_status( $task_id, $status );
	}

	/**
	 * Update section open state
	 *
	 * @param string $section_id Section ID
	 * @param bool   $open Open state
	 * @return bool
	 */
	public function update_section_open_state( string $section_id, bool $open ): bool {
		$section = $this->get_section( $section_id );
		if ( ! $section ) {
			return false;
		}

		return $section->set_open( $open );
	}

	/**
	 * Update section status
	 *
	 * @param string $section_id Section ID
	 * @param string $status New status
	 * @return bool
	 */
	public function update_section_status( string $section_id, string $status ): bool {
		$section = $this->get_section( $section_id );
		if ( ! $section ) {
			return false;
		}

		return $section->set_status( $status );
	}

	/**
	 * Get task by section and task ID
	 *
	 * @param string $section_id Section ID
	 * @param string $task_id Task ID
	 * @return Task|null
	 */
	public function get_task( string $section_id, string $task_id ): ?Task {
		$section = $this->get_section( $section_id );
		if ( $section ) {
			return $section->get_task( $task_id );
		}
		return null;
	}

	/**
	 * Set track open state
	 *
	 * @param bool $open Open state
	 * @return bool
	 */
	public function set_open( bool $open ): bool {
		$this->open = $open;
		return true;
	}

	/**
	 * Check if track is open
	 *
	 * @return bool
	 */
	public function is_open(): bool {
		return $this->open;
	}

	/**
	 * Toggle track open state
	 *
	 * @return bool New open state
	 */
	public function toggle_open(): bool {
		$this->open = ! $this->open;
		return $this->open;
	}

	/**
	 * Validate track data
	 *
	 * @return bool|string True if valid, error message if not
	 */
	public function validate() {
		if ( empty( $this->id ) ) {
			return 'Track ID is required';
		}

		if ( empty( $this->label ) ) {
			return 'Track label is required';
		}

		// Validate all sections
		foreach ( $this->sections as $section ) {
			$section_validation = $section->validate();
			if ( true !== $section_validation ) {
				return "Section validation failed: {$section_validation}";
			}
		}

		return true;
	}
}
