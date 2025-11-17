<?php

namespace WPGDPRC\Utils;

use WP_Post;
use WPGDPRC\Integrations\AbstractIntegration;
use WPGDPRC\Integrations\Plugins\AbstractPlugin;
use WPGDPRC\Integrations\Plugins\ContactForm;
use WPGDPRC\Integrations\Plugins\GravityForms;
use WPGDPRC\Integrations\Plugins\WooCommerce;
use WPGDPRC\Integrations\WPComments;
use WPGDPRC\Integrations\WPRegistration;
use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\WordPress\Cron\ProcessRequest;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Request
 * @package WPGDPRC\Utils
 */
class Request {

	const KEY_COUNT     = 'count';
	const KEY_REQUESTED = 'requested';
	const KEY_PROCESSED = 'processed';

	/**
	 * @return false|WP_Post
	 */
	public static function getAccessPage() {
		$option = Settings::get( Settings::KEY_ACCESS_PAGE );
		if ( ! empty( $option ) ) {
			return get_post( $option );
		}

		$args = [
			'post_type'   => 'page',
			'post_status' => 'publish,private,draft',
			'number'      => 1,
			'meta_key'    => '_wpgdprc_access_request',
			'meta_value'  => '1',
		];
		$page = get_pages( $args );
		if ( empty( $page ) ) {
			return false;
		}

		/** @var WP_Post $output */
		return reset( $page );
	}

	/**
	 * @return false|string
	 */
	public static function getAccessPageLinkEl() {
		$page = static::getAccessPage();
		if ( empty( $page ) ) {
			return false;
		}

		return Elements::getLink( get_permalink( $page ), get_the_title( $page ) );
	}

	/**
	 * @TODO what is this needed for??
	 * @return mixed
	 */
	public static function getDataAccessIDs() {
		return get_option( Plugin::PREFIX . '_data_access_request_ids', [] );
	}

	/**
	 * @param array $list
	 */
	public static function setDataAccessIDs( $list = [] ) {
		update_option( Plugin::PREFIX . '_data_access_request_ids', $list );
	}

	/**
	 * @return mixed
	 */
	public static function getDataAccessStatus() {
		return get_option( Plugin::PREFIX . '_data_access_request_status' );
	}

	/**
	 * @param RequestAccess $object
	 * @param false|string $type
	 * @param bool $front
	 *
	 * @return array
	 */
	public static function getIntegrationData( RequestAccess $object, $integration, $type, bool $front ) {
		if ( empty( $type ) ) {
			return [];
		}

		$email    = $object->getEmailAddress();
		$search   = '&nbsp;<strong>' . $email . '</strong>';
		$defaults = [
			'icon'              => '',
			'title'             => '',
			'content'           => '',
			/* translators: %s: email address (Beginning with a space!) */
			'notice'            => sprintf( __( 'No data found with email address%1s.', 'wp-gdpr-compliance' ), $search ),
			self::KEY_COUNT     => 0,
			self::KEY_REQUESTED => 0,
			self::KEY_PROCESSED => 0,
		];

		/**
		 * @var $integration AbstractIntegration | AbstractPlugin
		 */

		$result = $integration->getResults( $front, $search );
		$data   = $integration->getData( $email );

		if ( empty( $result ) ) {
			return $result;
		}

		$result = array_merge( $defaults, $result );
		if ( $front ) {
			if ( ! empty( $data ) ) {
				$result['content'] = function() use ($data, $type, $object) {Data::renderOutput($data, $type, $object->getId() );};
			}
		} else {
			$result['list'] = [];

			if ( ! empty( $data ) ) {
				foreach ( $data as $item ) {
					$result['list'][] = $item->getId();

					$request = RequestDelete::getByTypeAndDataIdAndAccessId( $type, $item->getId(), $object->getId() );
					if ( ! empty( $request ) ) {
						$result[ self::KEY_REQUESTED ]++;
						if ( $request->getProcessed() ) {
							$result[ self::KEY_PROCESSED ]++;
						}
					}
				}
			}

			$list = RequestDelete::getByTypeAndAccessId( $type, $object->getId() );
			foreach ( $list as $request ) {
				if ( ! in_array( $request->getDataId(), $result['list'], true ) ) {
					$result['list'][] = $request->getDataId();
					$result[ self::KEY_REQUESTED ]++;
					if ( $request->getProcessed() ) {
						$result[ self::KEY_PROCESSED ]++;
					}
				};
			}

			$result[ self::KEY_COUNT ] = count( $result['list'] );
		}

		return $result;
	}

	/**
	 * @param RequestAccess $object
	 * @param bool $front
	 *
	 * @return array
	 */
	public static function getData( RequestAccess $object, $front = false ): array {
		$list = [];
		if ( empty( $object ) ) {
			return $list;
		}

		foreach ( Integration::getList() as $type => $integration ) {
			if ( empty( $integration->hasData() && $integration->isEnabled() ) ) {
				continue;
			}

			$data = self::getIntegrationData( $object, $integration, $type, $front );
			if ( empty( $data ) ) {
				continue;
			}

			$list[ $type ] = $data;
		}

		return $list;
	}

	/**
	 * @param RequestAccess $object
	 *
	 * @return string
	 */
	public static function getProccessOffset( RequestAccess $object ) {
		$default = _x( '0 seconds', 'admin', 'wp-gdpr-compliance' );
		if ( empty( $object ) ) {
			return $default;
		}

		$created = Time::localDateTime( strtotime( $object->getDateCreated() ) );
		if ( empty( $created ) ) {
			return $default;
		}

		$next_cron = wp_next_scheduled( ProcessRequest::getHook() );
		if ( strtotime( ProcessRequest::EXPIRE ) > $next_cron ) {
			$next_cron += ProcessRequest::getFrequency();
		}

		$next_date = Time::localDateTime( $next_cron );
		if ( empty( $next_date ) ) {
			return $default;
		}

		$diff = $created->diff( $next_date );

		$offset = [];
		foreach ( [ 'y', 'm', 'd', 'h' ] as $format ) {
			$count = $diff->format( '%' . $format );
			if ( empty( $count ) ) {
				continue;
			}

			$offset[ $format ] = $count;
		}
		if ( ! empty( $offset['d'] ) ) {
			unset( $offset['h'] );
		}

		$result = [];
		if ( ! empty( $offset['y'] ) ) {
			/* translators: %1s: number of years */
			$result[] = sprintf( _nx( '%1s year', '%1s years', (int) $offset['y'], 'admin', 'wp-gdpr-compliance' ), $offset['y'] );
		}

		if ( ! empty( $offset['m'] ) ) {
			/* translators: %1s: number of months */
			$result[] = sprintf( _nx( '%1s month', '%1s months', (int) $offset['m'], 'admin', 'wp-gdpr-compliance' ), $offset['m'] );
		}

		if ( ! empty( $offset['d'] ) ) {
			/* translators: %1s: number of days */
			$result[] = sprintf( _nx( '%1s day', '%1s days', (int) $offset['d'], 'admin', 'wp-gdpr-compliance' ), $offset['d'] );
		}

		if ( ! empty( $offset['h'] ) ) {
			/* translators: %1s: number of hours */
			$result[] = sprintf( _nx( '%1s hour', '%1s hours', (int) $offset['h'], 'admin', 'wp-gdpr-compliance' ), $offset['h'] );
		}

		if ( empty( $result ) ) {
			return $default;
		}
		if ( count( $result ) == 1 ) {
			return reset( $result );
		}

		$last = end( $result );
		array_pop( $result );

		/* translators: %1$1s: results %2$2s: last */
		return sprintf( _x( '%1$1s and %2$2s', 'admin', 'wp-gdpr-compliance' ), implode( ', ', $result ), $last );
	}

}
