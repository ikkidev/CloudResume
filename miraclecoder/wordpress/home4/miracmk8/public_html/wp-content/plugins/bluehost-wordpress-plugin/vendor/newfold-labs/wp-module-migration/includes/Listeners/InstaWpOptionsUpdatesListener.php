<?php
namespace NewfoldLabs\WP\Module\Migration\Listeners;

use NewfoldLabs\WP\Module\Migration\Data\Events;
use NewfoldLabs\WP\Module\Migration\Services\EventService;
use NewfoldLabs\WP\Module\Migration\Services\UtilityService;
use NewfoldLabs\WP\Module\Migration\Services\Tracker;
use NewfoldLabs\WP\Module\Migration\Steps\Push;
use NewfoldLabs\WP\Module\Migration\Steps\PageSpeed;
use NewfoldLabs\WP\Module\Migration\Steps\LastStep;
use NewfoldLabs\WP\Module\Migration\Steps\SourceHostingInfo;

/**
 * Monitors InstaWp options update
 */
class InstaWpOptionsUpdatesListener {
	/**
	 * Tracker class instance.
	 *
	 * @var Tracker $tracker
	 */
	public $tracker;

	/**
	 * InstaWpOptionsUpdatesListener constructor.
	 */
	public function __construct() {
		$this->register_hooks();
	}
	/**
	 * Register the hooks for the listener
	 *
	 * @return void
	 */
	public function register_hooks() {
		$this->tracker = new Tracker();
		add_filter( 'pre_update_option_instawp_last_migration_details', array( $this, 'on_update_instawp_last_migration_details' ), 10, 2 );
		add_filter( 'pre_update_option_instawp_migration_details', array( $this, 'on_update_instawp_migration_details' ), 10, 2 );
		add_action( 'nfd_migration_page_speed_source', array( $this, 'page_speed_source' ), 10 );
		add_action( 'nfd_migration_page_speed_destination', array( $this, 'page_speed_destination' ), 10, 3 );
		add_action( 'nfd_migration_source_hosting_info', array( $this, 'source_hosting_info' ), 10 );
	}

	/**
	 * Triggers events
	 *
	 * @param array $new_value status of migration
	 * @param array $old_value previous status of migration
	 */
	public function on_update_instawp_last_migration_details( $new_value, $old_value ) {
		if ( $old_value !== $new_value && ! get_option( 'nfd_migration_status_sent', false ) ) {
			$migrate_group_uuid = isset( $new_value['migrate_group_uuid'] ) ? $new_value['migrate_group_uuid'] : '';
			if ( ! empty( $migrate_group_uuid ) ) {
				$response = UtilityService::get_migration_data( $migrate_group_uuid );

				if ( $response && is_array( $response ) ) {
					// Use new_value for migration_status instead of API response
					$migration_status = isset( $new_value['status'] ) ? $new_value['status'] : '';

					if ( 'completed' === $migration_status || 'failed' === $migration_status || 'aborted' === $migration_status ) {
						$push = new Push();
						$push->set_status( $push->statuses[ $migration_status ] );
						$this->tracker->update_track( $push );

						if ( isset( $response['data']['source_site_url'] ) ) {
							$source_site_url = $response['data']['source_site_url'];
							if ( ! wp_next_scheduled( 'nfd_migration_source_hosting_info' ) ) {
								wp_schedule_single_event( time() + 60, 'nfd_migration_source_hosting_info', array( 'source_site_url' => $source_site_url ) );
							}
							if ( 'completed' === $migration_status ) {
								if ( ! wp_next_scheduled( 'nfd_migration_page_speed_source' ) ) {
									wp_schedule_single_event( time() + 90, 'nfd_migration_page_speed_source', array( 'source_site_url' => $source_site_url ) );
								}
								if ( ! wp_next_scheduled( 'nfd_migration_page_speed_destination' ) ) {
									wp_schedule_single_event(
										time() + 120,
										'nfd_migration_page_speed_destination',
										array(
											'source_site_url' => $source_site_url,
											'migrate_group_uuid' => $migrate_group_uuid,
											'status'          => $migration_status,
										),
									);
								}
							}
						}
					}

					if ( 'completed' === $migration_status ) {
						$migration_complete = new LastStep();
						$migration_complete->set_status( $migration_complete->statuses['completed'] );
						$this->tracker->update_track( $migration_complete );
					} elseif ( 'failed' === $migration_status ) {
						$migration_complete = new LastStep();
						$migration_complete->set_status( $migration_complete->statuses['failed'] );
						$this->tracker->update_track( $migration_complete );
						EventService::send_application_event(
							'migration_failed',
							$this->tracker->get_track_content()
						);
					} elseif ( 'aborted' === $migration_status ) {
						$migration_complete = new LastStep();
						$migration_complete->set_status( $migration_complete->statuses['aborted'] );
						$this->tracker->update_track( $migration_complete );
						EventService::send_application_event(
							'migration_aborted',
							$this->tracker->get_track_content()
						);
					}
				}
			}
		}

		return $new_value;
	}

	/**
	 * Listen instaWp option update to intercept the Push step and track it
	 *
	 * @param array $new_value status of migration
	 * @param array $old_value previous status of migration
	 * @return array
	 */
	public function on_update_instawp_migration_details( $new_value, $old_value ) {
		if ( $old_value !== $new_value ) {
			$mode   = isset( $new_value['mode'] ) ? $new_value['mode'] : '';
			$status = isset( $new_value['status'] ) ? $new_value['status'] : '';
			if ( 'push' === $mode && 'initiated' === $status ) {
				$push = new Push();
				$this->tracker->update_track( $push );
			}
		}
		return $new_value;
	}
	/**
	 * Get source site hosting informations.
	 *
	 * @param string $source_site_url source site url.
	 * @return void
	 */
	public function source_hosting_info( $source_site_url ) {
		$source_hosting_info = new SourceHostingInfo( $source_site_url );
		$this->tracker->update_track( $source_hosting_info );

		if ( ! $source_hosting_info->failed() ) {
			$source_hosting_info->set_status( $source_hosting_info->statuses['completed'] );
		}

		$this->tracker->update_track( $source_hosting_info );
	}
	/**
	 * Track page speed for source site.
	 *
	 * @param string $source_site_url source site url.
	 * @return void
	 */
	public function page_speed_source( $source_site_url ) {
		$source_url_pagespeed = new PageSpeed( $source_site_url, 'source' );
		if ( ! $source_url_pagespeed->failed() ) {
			$source_url_pagespeed->set_status( $source_url_pagespeed->statuses['completed'] );
		}

		$this->tracker->update_track( $source_url_pagespeed );
	}
	/**
	 * Track page speed for source site.
	 *
	 * @param string $source_site_url    source site url.
	 * @param string $migrate_group_uuid migrate group uuid.
	 * @param string $status             status of migration.
	 * @return void
	 */
	public function page_speed_destination( $source_site_url, $migrate_group_uuid, $status ) {
		try {
			$source_url_pagespeed = new PageSpeed( site_url(), 'destination' );
			if ( ! $source_url_pagespeed->failed() ) {
				$source_url_pagespeed->set_status( $source_url_pagespeed->statuses['completed'] );
			}

			$this->tracker->update_track( $source_url_pagespeed );
		} finally {
			if ( ! get_option( 'nfd_migration_status_sent', false ) ) {
				EventService::send_application_event(
					'migration_completed',
					array_merge(
						array(
							'migration_uuid' => $migrate_group_uuid,
						),
						$this->tracker->get_track_content()
					),
				);

				// send specific data to the Migration Table Event
				$tracked_datas           = $this->tracker->get_track_content();
				$isp                     = $tracked_datas['SourceHostingInfo']['data']['SourceHostingData']['isp'] ?? 'N/A';
				$as                      = $tracked_datas['SourceHostingInfo']['data']['SourceHostingData']['as'] ?? 'N/A';
				$source_speed_index      = $tracked_datas['PageSpeed_source']['data']['speedIndex'] ?? '0';
				$source_speed_index      = str_replace( ' s', '', $source_speed_index );
				$destination_speed_index = $tracked_datas['PageSpeed_destination']['data']['speedIndex'] ?? 0;
				$destination_speed_index = str_replace( ' s', '', $destination_speed_index );
				$status                  = 'completed' === $status ? 'successful' : $status;
				$migration_infos         = array(
					'migration_uuid'         => $migrate_group_uuid,
					'status'                 => $status,
					'origin_url'             => $source_site_url,
					'origin_isp'             => $isp,
					'origin_as'              => $as,
					'origin_page_speed'      => $source_speed_index,
					'destination_page_speed' => $destination_speed_index,
				);

				EventService::send_application_event( "migration_$status", $migration_infos );
				update_option( 'nfd_migration_status_sent', true );
			}
		}
	}
}
