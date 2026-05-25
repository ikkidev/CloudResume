<?php
namespace NewfoldLabs\WP\Module\Migration\Steps;

use NewfoldLabs\WP\Module\Migration\Services\Tracker;

/**
 * Abstract class representing a step in the migration process.
 *
 * This class provides a base implementation for all migration steps.
 * It should be extended by specific step classes to define the actual
 * step logic.
 *
 * @package wp-module-migration
 */
abstract class AbstractStep {
	/**
	 * The actual retry count, it will increment on each retry.
	 *
	 * @var int $retry_count
	 */
	protected $retry_count = 0;

	/**
	 * The maximum retries possible.
	 *
	 * @var int $max_retries
	 */
	protected $max_retries = 0;

	/**
	 * The possible statuses for the step.
	 *
	 * @var array $statuses
	 */
	public $statuses = array(
		'running'   => 'running',
		'completed' => 'completed',
		'failed'    => 'failed',
		'aborted'   => 'aborted',
	);

	/**
	 * Status of the current step, it could be success, running or failed
	 *
	 * @var string $status
	 */
	protected $status;

	/**
	 * The current step slug.
	 *
	 * @var string $step_slug
	 */
	protected $step_slug = '';

	/**
	 * Collect response messages.
	 *
	 * @var array $response
	 */
	protected $response = array();

	/**
	 * Collect datas for the step if any.
	 *
	 * @var array $response
	 */
	protected $datas = array();

	/**
	 * Tracker class instance.
	 *
	 * @var Tracker $tracker
	 */
	protected $tracker;

	/**
	 * Run the main code for.
	 */
	protected function run() {}

	/**
	 * Set the step status as successful & reset the retry count to 0 and print success log.
	 */
	protected function success() {
		$this->set_status( $this->statuses['completed'] );
		$this->set_retry_count( 0 );
	}
	/**
	 * Set the step status as failed & reset the retry count to 0 and print failed log.
	 */
	protected function failure() {
		$this->set_status( $this->statuses['failed'] );
	}
	/**
	 * Check if the step is completed.
	 *
	 * @return bool
	 */
	public function failed() {
		return $this->get_status() === 'failed';
	}
	/**
	 * Retry the run method.
	 *
	 * @return bool;
	 */
	protected function retry() {
		$count = $this->retry_count + 1;
		if ( $count >= $this->get_max_retries() ) {
			$this->failure();
			return $this;
		}

		sleep( 1 );

		$this->set_retry_count( $count );

		$this->run();
	}

	/**
	 * Set the current step slug
	 *
	 * @param string $slug the retry count value.
	 */
	protected function set_step_slug( string $slug ) {
		$this->step_slug = empty( $slug ) ? 'generic' : $slug;
	}

	/**
	 * Get the current step slug
	 *
	 * @return string
	 */
	public function get_step_slug() {
		return $this->step_slug;
	}

	/**
	 * Set the max retry value
	 *
	 * @param int $max the max number of retries.
	 */
	protected function set_max_retries( int $max ) {
		$this->max_retries = $max < 0 ? 0 : $max;
	}

	/**
	 * Set the retry value
	 *
	 * @param int $retry_count the retry count value.
	 */
	protected function set_retry_count( int $retry_count ) {
		$this->retry_count = $retry_count > $this->max_retries ? $this->max_retries : $retry_count;
	}
	/**
	 * Get the actual retry count
	 *
	 * @return int
	 */
	public function get_retry_count() {
		return (int) $this->retry_count;
	}
	/**
	 * Get the max retries count
	 *
	 * @return int
	 */
	public function get_max_retries() {
		return (int) $this->max_retries;
	}
	/**
	 * Get the status
	 *
	 * @return int
	 */
	public function get_status() {
		return $this->status;
	}
	/**
	 * Set the status
	 *
	 * @param string $status the status;
	 */
	public function set_status( $status ) {
		$this->status = $status;
	}

	/**
	 * Get the response
	 *
	 * @return array
	 */
	public function get_response() {
		return $this->response;
	}
	/**
	 * Set the response
	 *
	 * @param array $response the response;
	 */
	public function set_response( $response ) {
		$response       = empty( $response ) || ! is_array( $response ) ? array() : $response;
		$this->response = $response;
	}
	/**
	 * Set the tracker instance for the step
	 *
	 * @param Tracker $tracker the tracker instance.
	 */
	public function set_tracker( Tracker $tracker ) {
		$this->tracker = $tracker;
	}
	/**
	 * Get the data by data key.
	 *
	 * @param string $data_key the data key to set.
	 * @param mixed  $data_value the data value to set.
	 * @return void
	 */
	protected function set_data( $data_key, $data_value ) {
		if ( ! empty( $data_key ) && is_string( $data_key ) ) {
			$this->datas[ $data_key ] = $data_value;
		}
	}
	/**
	 * Get the data by data key, or empty string if not isset, or all datas if param is empty.
	 *
	 * @param string $data_key the data key to get.
	 * @return string
	 */
	public function get_data( $data_key = '' ) {
		if ( isset( $this->datas[ $data_key ] ) ) {
			return $this->datas[ $data_key ];
		}

		if ( empty( $data_key ) ) {
			return $this->datas;
		}
		return '';
	}
}
