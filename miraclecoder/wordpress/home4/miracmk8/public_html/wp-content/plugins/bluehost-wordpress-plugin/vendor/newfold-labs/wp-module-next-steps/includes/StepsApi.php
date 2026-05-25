<?php

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Task;
use NewfoldLabs\WP\Module\NextSteps\PlanRepository;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * StepsApi - REST API controller for Next Steps functionality
 *
 * This class provides a comprehensive REST API for managing next steps plans,
 * tasks, sections, and tracks. It handles all CRUD operations for the next steps
 * system including plan management, task status updates, and progress tracking.
 *
 * All endpoints require 'manage_options' capability for security.
 * The API uses WordPress REST API standards with proper validation,
 * error handling, and response formatting.
 *
 * @package NewfoldLabs\WP\Module\NextSteps
 * @since 1.0.0
 * @author Newfold Labs
 */
class StepsApi {

	/**
	 * Transient name where data is stored.
	 */
	const OPTION = 'nfd_next_steps';


	/**
	 * REST namespace
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * REST base
	 *
	 * @var string
	 */
	private $rest_base;

	/**
	 * StepsApi constructor.
	 *
	 * Initializes the API with the namespace and base route for all endpoints.
	 * Sets up the REST API namespace as 'newfold-next-steps/v2' and base route as '/plans'.
	 */
	public function __construct() {
		$this->namespace = 'newfold-next-steps/v2';
		$this->rest_base = '/plans';
	}

	/**
	 * Register all REST API routes for the Next Steps functionality.
	 *
	 * Registers the following endpoints:
	 * - GET  /plans - Retrieve current plan and steps
	 * - POST /plans/add - Add new tasks to current plan
	 * - PUT  /plans/tasks/{task_id} - Update task status
	 * - PUT  /plans/sections/{section_id} - Update section state (open/status)
	 * - PUT  /plans/tracks/{track_id} - Update track open state
	 * - GET  /plans/stats - Get plan statistics
	 * - PUT  /plans/switch - Switch to different plan type
	 * - PUT  /plans/reset - Reset plan to defaults
	 * - POST /plans/tasks - Add task to specific section
	 *
	 * All routes require 'manage_options' capability and include proper
	 * parameter validation and sanitization.
	 */
	public function register_routes() {

		// Add route for fetching steps
		// newfold-next-steps/v2/plans
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_steps' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// Add route for adding steps
		// newfold-next-steps/v2/plans/add
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/add',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'add_steps' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// Add route for updating a step status
		// newfold-next-steps/v2/plans/tasks/{task_id}
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/tasks/(?P<task_id>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_task_status' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'plan_id'    => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'track_id'   => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'section_id' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'task_id'    => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'status'     => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
				),
			)
		);

		// Add route for plan statistics
		// newfold-next-steps/v2/plans/stats
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/stats',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_plan_stats' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// Add route for switching plans
		// newfold-next-steps/v2/plans/switch
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/switch',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'switch_plan' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'plan_type' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value ) && in_array( $value, array( 'ecommerce', 'blog', 'corporate' ), true );
						},
					),
				),
			)
		);

		// Add route for resetting plan
		// newfold-next-steps/v2/plans/reset
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/reset',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'reset_plan' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// Add route for updating track open state
		// newfold-next-steps/v2/plans/tracks/{track_id}
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/tracks/(?P<track_id>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_track_status' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'plan_id'  => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'track_id' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'open'     => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_bool( $value );
						},
					),
				),
			)
		);

		// Add route for updating section state (unified for both open and status)
		// newfold-next-steps/v2/plans/sections/{section_id}
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/sections/(?P<section_id>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_section_state' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'plan_id'    => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'track_id'   => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'section_id' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'type'       => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value ) && in_array( $value, array( 'status', 'open' ), true );
						},
					),
					'value'      => array(
						'required'          => true,
						'validate_callback' => function ( $value, $request ) {
							$type = $request->get_param( 'type' );
							if ( 'open' === $type ) {
								return is_bool( $value );
							} elseif ( 'status' === $type ) {
								return is_string( $value ) && in_array( $value, array( 'new', 'dismissed', 'done' ), true );
							}
							return false;
						},
					),
				),
			)
		);

		// Add route for adding tasks to specific sections
		// newfold-next-steps/v2/plans/tasks
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/tasks',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'add_task_to_section' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'track_id'   => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'section_id' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_string( $value );
						},
					),
					'task'       => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return is_array( $value ) && isset( $value['id'], $value['title'] );
						},
					),
				),
			)
		);
	}

	/**
	 * Set the option where steps are stored.
	 *
	 * Helper method to store plan data in WordPress options table.
	 * This method is used internally by the PlanRepository to persist
	 * plan state changes.
	 *
	 * @param array $steps Data to be stored in the options table
	 *
	 * @return void
	 */
	public static function set_data( $steps ) {
		update_option( self::OPTION, $steps );
	}

	/**
	 * GET /newfold-next-steps/v2/plans - Retrieve current plan and steps
	 *
	 * Retrieves the current active plan with all tracks, sections, and tasks.
	 * This endpoint is used by the frontend to display the complete next steps
	 * structure and current progress.
	 *
	 * @api {get} /newfold-next-steps/v2/plans Get Current Plan
	 * @apiName GetPlan
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 *
	 * @apiError (404) no_plan No plan found
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function get_steps() {
		$plan = PlanRepository::get_current_plan();

		if ( ! $plan ) {
			return new \WP_Error( 'no_plan', __( 'No plan found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// TODO
		// check each steps callback to determine if completed - smart next steps autocomplete
		// each step can define a callback that will be called to determine if the step is completed
		// for example add post can check if a post exists in the site or add media can check if media has been uploaded

		return new WP_REST_Response( $plan->to_array(), 200 );
	}

	/**
	 * POST /newfold-next-steps/v2/plans/add - Add new tasks to the current plan
	 *
	 * Adds new tasks to the first available section of the current plan.
	 * If a task with the same ID already exists, it will be updated with new values.
	 * This endpoint is typically used to dynamically add tasks based on user actions
	 * or plugin installations.
	 *
	 * @api {post} /newfold-next-steps/v2/plans/add Add Tasks to Plan
	 * @apiName AddTasksToPlan
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param array $new_tasks Array of task objects to add or update. Task objects with:
	 *  - string id Required. Unique task identifier
	 *  - string title Required. Task title
	 *  - string description Optional. Task description
	 *  - string href Optional. Task URL or action
	 *  - string status Optional. Task status ('new', 'done', 'dismissed')
	 *  - number priority Optional. Task priority
	 *  - string source Optional. Task source
	 *  - Object data_attributes Optional. Additional data attributes
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 *
	 * @apiSuccess {Object} plan The updated plan object (same structure as GET /steps)
	 *
	 * @apiError (404) no_plan No plan found
	 * @apiError (404) no_tracks No tracks found in plan
	 * @apiError (404) no_sections No sections found in track
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function add_steps( $new_tasks ) {
		// Get the current plan
		$plan = PlanRepository::get_current_plan();

		if ( ! $plan ) {
			return new \WP_Error( 'no_plan', __( 'No plan found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Add tasks to the first available section
		$tracks = $plan->tracks;
		if ( empty( $tracks ) ) {
			return new \WP_Error( 'no_tracks', __( 'No tracks found in plan.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$first_track = $tracks[0];
		$sections    = $first_track->sections;
		if ( empty( $sections ) ) {
			return new \WP_Error( 'no_sections', __( 'No sections found in track.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$first_section = $sections[0];

		// Add each task to the first section
		foreach ( $new_tasks as $task_data ) {
			if ( ! isset( $task_data['id'] ) ) {
				continue;
			}

			$task = Task::from_array( $task_data );

			// Check if task already exists and update it
			$existing_task = $first_section->get_task( $task->id );
			if ( $existing_task ) {
				// Update allowed fields
				$sync_fields = array( 'title', 'description', 'href', 'priority' );
				foreach ( $sync_fields as $field ) {
					if ( isset( $task_data[ $field ] ) && $existing_task->$field !== $task_data[ $field ] ) {
						$existing_task->$field = $task_data[ $field ];
					}
				}
			} else {
				// Add new task
				$first_section->add_task( $task );
			}
		}

		// Save the updated plan
		PlanRepository::save_plan( $plan );

		return new \WP_REST_Response( $plan->to_array(), 200 );
	}

	/**
	 * PUT /newfold-next-steps/v2/plans/tasks/{task_id} - Update task status
	 *
	 * Updates the status of a specific task within a plan. This endpoint is used
	 * when users mark tasks as completed, dismissed, or reset them to new status.
	 * The endpoint validates all required parameters and ensures the task exists
	 * before updating.
	 *
	 * @api {put} /newfold-next-steps/v2/plans/tasks/{task_id} Update Task Status
	 * @apiName UpdateTaskStatus
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param WP_REST_Request $request The REST request object containing:
	 * $request->get_param('plan_id') Required. Plan identifier
	 * $request->get_param('track_id') Required. Track identifier
	 * $request->get_param('section_id') Required. Section identifier
	 * $request->get_param('task_id') Required. Task identifier (from URL parameter)
	 * $request->get_param('status') Required. New status ('new', 'done', 'dismissed')
	 *
	 * @return WP_REST_Response|WP_Error The response object on success, or WP_Error on failure.
	 *
	 * @apiSuccess {Object} response Minimal task update data containing:
	 * @apiSuccess {string} response.id Task ID
	 * @apiSuccess {string} response.status Updated task status
	 *
	 * @apiError (400) invalid_params Invalid parameters provided
	 * @apiError (400) invalid_status Invalid status value provided
	 * @apiError (404) step_not_found Task not found in the specified location
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function update_task_status( WP_REST_Request $request ) {
		$plan_id    = $request->get_param( 'plan_id' );
		$track_id   = $request->get_param( 'track_id' );
		$section_id = $request->get_param( 'section_id' );
		$task_id    = $request->get_param( 'task_id' ); // From URL parameter
		$status     = $request->get_param( 'status' );

		// validate parameters
		if ( empty( $track_id ) || empty( $section_id ) || empty( $task_id ) || empty( $status ) ) {
			return new WP_Error( 'invalid_params', __( 'Invalid parameters provided.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}
		if ( ! in_array( $status, array( 'new', 'done', 'dismissed' ), true ) ) {
			return new WP_Error( 'invalid_status', __( 'Invalid status provided.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}

		// Check if the state is actually changing to avoid unnecessary updates
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$task = $plan->get_task( $track_id, $section_id, $task_id );
		if ( ! $task ) {
			return new WP_Error( 'step_not_found', __( 'Task not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// If the status hasn't changed, return success without updating
		if ( $task->status === $status ) {
			// Return the current task data
			$response_data = array(
				'id'     => $task->id,
				'status' => $task->status,
			);

			return new WP_REST_Response( $response_data, 200 );
		}

		// Use PlanRepository to update the task status
		$success = PlanRepository::update_task_status( $track_id, $section_id, $task_id, $status );

		if ( ! $success ) {
			return new WP_Error( 'step_not_found', __( 'Step not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Get the updated task data to return minimal changed properties
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$task = $plan->get_task( $track_id, $section_id, $task_id );
		if ( ! $task ) {
			return new WP_Error( 'step_not_found', __( 'Updated task not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Return only the essential changed properties
		$response_data = array(
			'id'     => $task->id,
			'status' => $task->status,
		);

		return new WP_REST_Response( $response_data, 200 );
	}

	/**
	 * PUT /newfold-next-steps/v2/plans/sections/{section_id} - Update section state
	 *
	 * Updates the state of a specific section within a plan. This unified endpoint
	 * can update both the 'open' state (expanded/collapsed) and the 'status' state
	 * (new/done/dismissed) of a section. The type parameter determines which property
	 * to update, and the value parameter must match the expected type.
	 *
	 * @api {put} /newfold-next-steps/v2/plans/sections/{section_id} Update Section State
	 * @apiName UpdateSectionState
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param WP_REST_Request $request The REST request object containing:
	 * $request->get_param('plan_id') Required. Plan identifier
	 * $request->get_param('track_id') Required. Track identifier
	 * $request->get_param('section_id') Required. Section identifier (from URL parameter)
	 * $request->get_param('type') Required. Update type ('open' or 'status')
	 * $request->get_param('value') Required. New value:
	 *  - For 'open' type: boolean (true/false)
	 *  - For 'status' type: string ('new', 'done', 'dismissed')
	 *
	 * @return WP_REST_Response|WP_Error The response object on success, or WP_Error on failure.
	 *
	 * @apiSuccess {Object} response Minimal section update data containing:
	 * @apiSuccess {string} response.id Section ID
	 * @apiSuccess {string} response.status Updated section status
	 * @apiSuccess {string} [response.date_completed] Completion timestamp (if status changed to done/dismissed)
	 * @apiSuccess {boolean} [response.open] Open state (if type was 'open')
	 *
	 * @apiError (400) invalid_params Invalid parameters provided
	 * @apiError (404) section_not_found Section not found in the specified location
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function update_section_state( WP_REST_Request $request ) {
		$plan_id    = $request->get_param( 'plan_id' );
		$track_id   = $request->get_param( 'track_id' );
		$section_id = $request->get_param( 'section_id' ); // From URL parameter
		$type       = $request->get_param( 'type' );
		$value      = $request->get_param( 'value' );

		// validate parameters
		if ( empty( $track_id ) || empty( $section_id ) || empty( $type ) ) {
			return new WP_Error( 'invalid_params', __( 'Invalid parameters provided.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}

		// Check if the state is actually changing to avoid unnecessary updates
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$section = $plan->get_section( $track_id, $section_id );
		if ( ! $section ) {
			return new WP_Error( 'section_not_found', __( 'Section not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Check if the value is actually different from current state
		$current_value = null;
		if ( 'open' === $type ) {
			$current_value = $section->open;
		} elseif ( 'status' === $type ) {
			$current_value = $section->status;
		}

		// If the value hasn't changed, return success without updating
		if ( $current_value === $value ) {
			// Return the current section data
			$response_data = array(
				'id' => $section->id,
			);

			if ( 'status' === $type ) {
				$response_data['status'] = $section->status;

				// Also include date_completed if it exists
				if ( ! empty( $section->date_completed ) ) {
					$response_data['date_completed'] = $section->date_completed;
				}
			}

			// Include open state if it was requested
			if ( 'open' === $type ) {
				$response_data['open'] = $section->open;
			}

			return new WP_REST_Response( $response_data, 200 );
		}

		// Use PlanRepository to update the section state
		$success = PlanRepository::update_section_state( $track_id, $section_id, $type, $value );

		if ( ! $success ) {
			return new WP_Error( 'section_not_found', __( 'Section not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Get the updated section data to return minimal changed properties
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$section = $plan->get_section( $track_id, $section_id );
		if ( ! $section ) {
			return new WP_Error( 'section_not_found', __( 'Updated section not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Return only the essential changed properties
		$response_data = array(
			'id'     => $section->id,
			'status' => $section->status,
		);

		// Include date_completed if it exists (for status changes)
		if ( ! empty( $section->date_completed ) ) {
			$response_data['date_completed'] = $section->date_completed;
		}

		// Include open state if it was changed
		if ( 'open' === $type ) {
			$response_data['open'] = $section->open;
		}

		return new WP_REST_Response( $response_data, 200 );
	}

	/**
	 * PUT /newfold-next-steps/v2/plans/tracks/{track_id} - Update track open state
	 *
	 * Updates the open/expanded state of a specific track within a plan.
	 * This endpoint is used to expand or collapse tracks in the UI.
	 *
	 * @api {put} /newfold-next-steps/v2/plans/tracks/{track_id} Update Track State
	 * @apiName UpdateTrackStatus
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param WP_REST_Request $request The REST request object containing:
	 * $request->get_param('plan_id') Required. Plan identifier
	 * $request->get_param('track_id') Required. Track identifier (from URL parameter)
	 * $request->get_param('open') Required. Whether track should be open/expanded
	 *
	 * @return WP_REST_Response|WP_Error The response object on success, or WP_Error on failure.
	 *
	 * @apiSuccess {Object} response Minimal track update data containing:
	 * @apiSuccess {string} response.id Track ID
	 * @apiSuccess {boolean} response.open Updated track open state
	 *
	 * @apiError (400) invalid_params Invalid parameters provided
	 * @apiError (404) track_not_found Track not found in the specified location
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function update_track_status( WP_REST_Request $request ) {
		$plan_id  = $request->get_param( 'plan_id' );
		$track_id = $request->get_param( 'track_id' ); // From URL parameter
		$open     = $request->get_param( 'open' ) ?? false;

		// validate parameters
		if ( empty( $track_id ) ) {
			return new WP_Error( 'invalid_params', __( 'Invalid parameters provided.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}

		// Check if the state is actually changing to avoid unnecessary updates
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$track = $plan->get_track( $track_id );
		if ( ! $track ) {
			return new WP_Error( 'track_not_found', __( 'Track not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// If the open state hasn't changed, return success without updating
		if ( $track->open === $open ) {
			// Return the current track data
			$response_data = array(
				'id'   => $track->id,
				'open' => $track->open,
			);

			return new WP_REST_Response( $response_data, 200 );
		}

		// Use PlanRepository to update the track status
		$success = PlanRepository::update_track_status( $track_id, $open );

		if ( ! $success ) {
			return new WP_Error( 'track_not_found', __( 'Track not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Get the updated track data to return minimal changed properties
		$plan = PlanRepository::get_current_plan();
		if ( ! $plan ) {
			return new WP_Error( 'plan_not_found', __( 'Plan not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		$track = $plan->get_track( $track_id );
		if ( ! $track ) {
			return new WP_Error( 'track_not_found', __( 'Track not found.', 'wp-module-next-steps' ), array( 'status' => 404 ) );
		}

		// Return only the essential changed properties
		$response_data = array(
			'id'   => $track->id,
			'open' => $track->open,
		);

		return new WP_REST_Response( $response_data, 200 );
	}


	/**
	 * GET /newfold-next-steps/v2/plans/stats - Get plan statistics
	 *
	 * Retrieves statistical information about the current plan including
	 * task completion counts, progress percentages, and other metrics.
	 * This endpoint is used for analytics and progress tracking.
	 *
	 * @api {get} /newfold-next-steps/v2/plans/stats Get Plan Statistics
	 * @apiName GetPlanStats
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @return WP_REST_Response Response object containing plan statistics.
	 *
	 * @apiSuccess {Object} stats Plan statistics object
	 * @apiSuccess {number} stats.total_tasks Total number of tasks in plan
	 * @apiSuccess {number} stats.completed_tasks Number of completed tasks
	 * @apiSuccess {number} stats.dismissed_tasks Number of dismissed tasks
	 * @apiSuccess {number} stats.new_tasks Number of new tasks
	 * @apiSuccess {number} stats.completion_percentage Overall completion percentage
	 * @apiSuccess {Object} stats.track_stats Statistics per track
	 *
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function get_plan_stats() {
		$stats = PlanRepository::get_plan_stats();
		return new WP_REST_Response( $stats, 200 );
	}

	/**
	 * PUT /newfold-next-steps/v2/plans/switch - Switch to different plan type
	 *
	 * Switches the current plan to a different plan type (blog, store, or corporate).
	 * This endpoint loads a new plan structure and replaces the current one.
	 * All existing progress is lost when switching plans.
	 *
	 * @api {put} /newfold-next-steps/v2/plans/switch Switch Plan Type
	 * @apiName SwitchPlan
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param WP_REST_Request $request The REST request object containing:
	 * $request->get_param('plan_type') Required. Plan type ('ecommerce', 'blog', 'corporate')
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 *
	 * @apiSuccess {Object} plan The new plan object (same structure as GET /steps)
	 *
	 * @apiError (400) invalid_plan_type Invalid plan type provided
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function switch_plan( WP_REST_Request $request ) {
		$plan_type = $request->get_param( 'plan_type' );

		$plan = PlanRepository::switch_plan( $plan_type );

		if ( ! $plan ) {
			return new WP_Error( 'invalid_plan_type', __( 'Invalid plan type provided.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}

		return new WP_REST_Response( $plan->to_array(), 200 );
	}

	/**
	 * PUT /newfold-next-steps/v2/plans/reset - Reset plan to defaults
	 *
	 * Resets the current plan to its default state, clearing all progress
	 * and returning all tasks to their initial 'new' status. This endpoint
	 * is useful for testing or when users want to start over.
	 *
	 * @api {put} /newfold-next-steps/v2/plans/reset Reset Plan
	 * @apiName ResetPlan
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @return WP_REST_Response Response object containing the reset plan.
	 *
	 * @apiSuccess {Object} plan The reset plan object (same structure as GET /steps)
	 *
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function reset_plan() {
		$plan = PlanRepository::reset_plan();
		return new WP_REST_Response( $plan->to_array(), 200 );
	}

	/**
	 * POST /newfold-next-steps/v2/plans/tasks - Add task to specific section
	 *
	 * Adds a new task to a specific section within a track. This endpoint
	 * allows for more precise task placement compared to the general add_steps
	 * endpoint which adds to the first available section.
	 *
	 * @api {post} /newfold-next-steps/v2/plans/tasks Add Task to Section
	 * @apiName AddTaskToSection
	 * @apiGroup NextSteps
	 * @apiPermission manage_options
	 *
	 * @param WP_REST_Request $request The REST request object containing:
	 * $request->get_param('track_id') Required. Track identifier
	 * $request->get_param('section_id') Required. Section identifier
	 * $request->get_param('task') Required. Task object with:
	 *  - string id Required. Unique task identifier
	 *  - string title Required. Task title
	 *  - string description Optional. Task description
	 *  - string href Optional. Task URL or action
	 *  - string status Optional. Task status ('new', 'done', 'dismissed')
	 *  - number priority Optional. Task priority
	 *  - string source Optional. Task source
	 *  - Object data_attributes Optional. Additional data attributes
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
	 *
	 * @apiSuccess {Object} plan The updated plan object (same structure as GET /steps)
	 *
	 * @apiError (400) add_task_failed Failed to add task to section
	 * @apiError (403) forbidden Insufficient permissions
	 */
	public static function add_task_to_section( WP_REST_Request $request ) {
		$track_id   = $request->get_param( 'track_id' );
		$section_id = $request->get_param( 'section_id' );
		$task_data  = $request->get_param( 'task' );

		$task = Task::from_array( $task_data );

		$success = PlanRepository::add_task( $track_id, $section_id, $task );

		if ( ! $success ) {
			return new WP_Error( 'add_task_failed', __( 'Failed to add task to section.', 'wp-module-next-steps' ), array( 'status' => 400 ) );
		}

		$plan = PlanRepository::get_current_plan();
		return new WP_REST_Response( $plan->to_array(), 200 );
	}
}
