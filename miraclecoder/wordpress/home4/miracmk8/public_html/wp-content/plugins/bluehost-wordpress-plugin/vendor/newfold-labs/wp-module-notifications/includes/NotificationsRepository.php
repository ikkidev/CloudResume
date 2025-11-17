<?php

namespace NewFoldLabs\WP\Module\Notifications;

use NewfoldLabs\WP\Module\Data\HiiveConnection;
use WP_Forge\Helpers\Arr;
use WP_Forge\Collection\Collection;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class NotificationsRepository
 */
class NotificationsRepository {

	/**
	 * Transient name where notifications are stored.
	 */
	const TRANSIENT = 'newfold_notifications';

	/**
	 * Collection of notifications.
	 *
	 * @var Notification[]
	 */
	protected $notifications = array();

	/**
	 * NotificationsRepository constructor.
	 *
	 * @param boolean $fetch_notices When true (default), requests notices immediately. When false, loads script to prime transient client-side.
	 */
	public function __construct( $fetch_notices = true ) {

		// If there is no Hiive connection, bail.
		if(! HiiveConnection::is_connected()) {
			return;
		}

		$notifications = get_transient( self::TRANSIENT );
		
		// load test data TODO REMOVE
		// $notifications = self::get_test_notification_data(); 

		if ( false === $notifications && true === $fetch_notices ) {
			$response = wp_remote_get(
				NFD_HIIVE_URL . '/notifications',
				array(
					'headers' => array(
						'Content-Type'  => 'application/json',
						'Accept'        => 'application/json',
						'Authorization' => 'Bearer ' . HiiveConnection::get_auth_token(),
					),
				)
			);
			if ( ! is_wp_error( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				$data = json_decode( $body, true );
				if ( $data && is_array( $data ) ) {
					$notifications = Arr::get( $data, 'data' );
					set_transient( self::TRANSIENT, $notifications, 5 * MINUTE_IN_SECONDS );
				}
			}
		} elseif ( false === $notifications && false === $fetch_notices ) {
			wp_enqueue_script(
				'newfold-notices-primer',
				plugins_url( 'vendor/newfold-labs/wp-module-notifications/assets/js/prime-notices.js', container()->plugin()->file ),
				array( 'wp-dom-ready', 'wp-api-fetch', 'nfd-runtime' ),
				container()->plugin()->version,
				true
			);
		}

		$notifications = is_array( $notifications ) ? $notifications : array();

		// Index by ID and convert all data to Notification objects before storing.
		$this->notifications = Collection::make( $notifications )->indexBy( 'id' )->map(
			function ( $notification ) {
				return new Notification( $notification );
			}
		)->all();
	}

	public static function get_test_notification_data() {
		$results[] = [
			'id'         => 'test-plugin',
			'locations'  => [
				[
					'context' => container()->plugin()->id . '-plugin',
					'pages'   => '#/home',
				]
			],
			'expiration' => 1648863456503,
			'content'    => '<div class="notice notice-error"><p>Expired notice should not display in app app notice</p></div>',
		];
		$results[] = [
			'id'         => 'test-admin',
			'locations'  => [
				[
					'context' => container()->plugin()->id . '-plugin',
					'pages'   => '#/home',
				],
				[
					'context' => container()->plugin()->id . '-plugin',
					'pages'   => '#/help',
				]
			],
			'expiration' => 1749860279240,
			'content'    => '<div class="notice notice-warning"><p>Here is a plugin notice it should display on home and help screen only! <a data-action="close">x</a></p></div>',
		];
		$results[] = [
			'id'         => 'test-admin-prime',
			'locations'  => [
				[
					'context' => 'wp-admin-prime',
					'pages'   => 'all',
				]
			],
			'expiration' => 1649860279240,
			'content'    => '<div class="notice notice-error"><p>HELLOW THERE `wp-admin-prime` notice!</p></div>',
		];
		$results[] = [
			'id'         => 'test-search-realtime',
			'locations'  => [
				[
					'context' => 'wp-plugin-search',
					'pages'   => 'all',
				]
			],
			'expiration' => 1649860279240,
			'content'    => '<div class="notice notice-success"><p>HELLOW THERE plugin search notice!</p></div>',
		];
		$results[] = [
			'id' => "36832018-3d86-4fc1-80d2-fc36973f6b2f",
			'locations' => [
				[
					'pages'   => "all",
					'context' => 'wp-admin-notice'
				]
			],
			'expiration' => 1649860279240,
			'content' => "<style>@media screen and (max-width:1160px) {.bf2020 {background-image:none !important;padding-left:26px !important;}}@media screen and (max-width:600px) {.bf2020 {padding-left:0 !important;height:auto !important;}.bf2020 .body{flex-direction:column}.bf2020-button{display:block !important;width:100%!important;margin-bottom:15px;margin-top:15px;}}.bf2020-button:hover{background:#2C6CC9 !important;color:white !important;}</style><div style=\"padding: 10px 20px 0 0;clear: both;\"><div style=\"background: #3575D3;display: flex;box-shadow: 0 1px 3px rgba(100,100,100,.2);position:relative;color: #fff;font-family: 'Open Sans', sans-serif;justify-content: space-between;\" class=\"bf2020\"> <div style=\"display:flex;justify-content: left;padding-top: 15px;padding-bottom: 15px;flex-grow: 1;\"><svg viewBox=\"0 0 116.8 19.3\" title=\"Bluehost Logo\" style=\"width: 136px;padding: 0 30px;flex-grow: 0;\"><g fill=\"#ffffff\"><path d=\"M0 0h5.3v5.3H0zm6.8 0h5.3v5.3H6.8zm6.9 0H19v5.3h-5.3zM0 6.8h5.3v5.3H0zm6.8 0h5.3v5.3H6.8zm6.9 0H19v5.3h-5.3zM0 13.7h5.3V19H0zm6.8 0h5.3V19H6.8zM13.7 13.7H19V19h-5.3zM29.8 8.2c1.1-1 2.5-1.5 4-1.5 2.7 0 5.3 1.8 5.3 6.3s-2.9 6.3-6.1 6.3c-1.6 0-3.2-.4-4.6-1.3V0h1.4zm0 9.1c1 .5 2.1.8 3.2.8 2.5 0 4.8-1.5 4.8-5.1 0-3.2-1.8-5.1-4.1-5.1-1.5.1-2.9.8-3.9 1.9zM41.5 19V0h1.3v19zm5.8-4.7c0 2.9 1.4 3.7 2.8 3.7 1.8-.1 3.3-1.1 4.2-2.7V6.9h1.4v12.2h-1.4v-2.4c-1 1.5-2.8 2.5-4.6 2.5-1.9 0-3.8-1.1-3.8-4.8V6.9h1.4zm21.4 3.9c-1.3.7-2.8 1.1-4.3 1-4.1-.1-6.2-3.4-6.1-6.8 0-3.2 2.5-5.8 5.6-5.8h.4c3.3.1 5.5 2.7 5.1 6.6h-9.8c0 2.6 2.1 4.7 4.7 4.8h.1c1.3 0 2.6-.3 3.8-.9zm-.5-6.1c.1-2.2-1.6-4.1-3.8-4.2h-.2c-2.4-.1-4.4 1.8-4.5 4.2zm5.6-2.9c1-1.6 2.6-2.5 4.5-2.6 2.4 0 3.9 1.8 3.9 4.6V19h-1.3v-7.6c0-2.6-1.4-3.6-2.8-3.6-1.8.2-3.3 1.2-4.2 2.7V19h-1.3V0H74c-.2 0-.2 9.2-.2 9.2zm22.3 3.7c0 4-2.7 6.3-5.9 6.3-3.5 0-5.9-2.8-5.9-6.3-.2-3.3 2.3-6.1 5.6-6.3h.3c3.2.1 5.9 2.4 5.9 6.3zm-10.3 0c0 2.7 1.6 5 4.5 5s4.5-2.4 4.5-5-1.7-5-4.5-5-4.5 2.3-4.5 5zm13.4 4c1 .6 2.1 1 3.2 1 1.3 0 2.9-.5 2.8-1.8 0-1.1-1.2-2-3-2.7-2.1-.8-3.9-1.6-3.9-3.5s1.8-3.3 4.2-3.3c1.2 0 2.3.3 3.4.9l-.5 1.1c-.9-.5-1.8-.7-2.8-.7-2 0-2.9 1-2.9 2 0 1.3 1.5 1.8 3.4 2.6 2.9 1.1 3.6 2.5 3.6 3.6 0 1.9-1.8 3.1-4.2 3.1-1.4 0-2.7-.4-3.9-1.1zm16.4-10V8h-4v6.7c0 2 .8 3.2 2.6 3.3.8 0 1.6-.1 2.3-.5l.4 1.2c-.9.3-1.8.5-2.7.5-2.2 0-3.9-1.3-3.9-4.5V8H108V6.9h2.2V2.8h1.4v4.1z\"></path></g></svg><div style=\"margin: 0;line-height:24px;font-size: 16px;font-family: 'Open Sans', sans-serif;display: flex;align-items: center;justify-content: space-between;flex-grow: 2;\" class=\"body\"><span style=\"max-width: 600px;flex-grow: 2;\">Jetpack Backup provides redundant backups and an activity log of all your changes, which means you can choose the right backup and restore that specific version of your site.</span> <a href=\"https://my.bluehost.com/hosting/app#/marketplace/product/i/jetpack-backup?utm_content=learn_more&utm_term=backup&utm_source=banner_search&utm_medium=bluehost_plugin\" style=\"border: 1px solid #2C6CC9;display: inline-flex;padding: 0.5rem 1rem;text-decoration: none;font-weight: bold;font-family: 'Open Sans', sans-serif;font-size: 16px;margin: 0 18px;color: #2C6CC9;outline: 1px solid transparent;border-radius: 3px;flex-shrink: 0;text-align: center;background: #fff;flex-grow: 0;\" class=\"bf2020-button\">Learn More</a></div></div> <a href=\"\" data-action=\"close\" style=\"margin: 8px 10px 0 0;flex-grow: 0;\" class=\"\"> <svg width=\"16px\" height=\"16px\" viewBox=\"0 0 20 20\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" style=\"\"><title>Close</title><g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g id=\"Black-Friday\" transform=\"translate(-1357.000000, -226.000000)\"><g id=\"Group\" transform=\"translate(1355.000000, 224.000000)\"> <rect id=\"Background\" x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect><path d=\"M14.12125,12 L20.53125,5.59 L21.853125,4.268125 C22.048125,4.073125 22.048125,3.75625 21.853125,3.56125 L20.43875,2.146875 C20.24375,1.951875 19.926875,1.951875 19.731875,2.146875 L12,9.87875 L4.268125,2.14625 C4.073125,1.95125 3.75625,1.95125 3.56125,2.14625 L2.14625,3.560625 C1.95125,3.755625 1.95125,4.0725 2.14625,4.2675 L9.87875,12 L2.14625,19.731875 C1.95125,19.926875 1.95125,20.24375 2.14625,20.43875 L3.560625,21.853125 C3.755625,22.048125 4.0725,22.048125 4.2675,21.853125 L12,14.12125 L18.41,20.53125 L19.731875,21.853125 C19.926875,22.048125 20.24375,22.048125 20.43875,21.853125 L21.853125,20.43875 C22.048125,20.24375 22.048125,19.926875 21.853125,19.731875 L14.12125,12 Z\" id=\"close\" fill=\"#fff\"></path></g></g></g></svg></a></div></div><img src='https://hiive.cloud/i.png?notification_id=36832018-3d86-4fc1-80d2-fc36973f6b2f' alt='' width='1' height='1' style='position: absolute;' />"
		];
		$results[] = [
			'id' => 'b33d6d88-a8c0-4d75-b9ff-f75d339babb3',
			'locations' => [
				[
					'pages' => ["#/marketplace/services"],
					'context' => container()->plugin()->id . "-plugin"
				],
				[
					'pages' => 'index.php',
					'context' => "wp-admin-notice"
				],
			],
			'expiration' => 1649860279240,
			'content' => "<style>@media screen and (max-width:1160px) {.cta {background-image:none !important;padding-left:26px !important;}}@media screen and (max-width:600px) {.cta {padding-left:0 !important;height:auto !important;}.cta .body{flex-direction:column}.cta-button{display:block !important;width:100%!important;margin-bottom:15px;margin-top:15px;}}.cta-button:hover{background:#2C6CC9 !important;color:white !important;}</style><link rel=\"preconnect\" href=\"https://fonts.gstatic.com\"><link href=\"https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&amp;display=swap\" rel=\"stylesheet\"><div style=\"padding: 10px 20px 0 0;clear: both;\"><div style=\"background: #3575D3;display: flex;box-shadow: 0 1px 3px rgba(100,100,100,.2);position:relative;color: #fff;font-family: 'Open Sans', sans-serif;justify-content: space-between;\" class=\"cta\"> <div style=\"display:flex;justify-content: left;padding-top: 15px;padding-bottom: 15px;flex-grow: 1;\"><svg viewBox=\"0 0 116.8 19.3\" title=\"Bluehost Logo\" style=\"width: 136px;padding: 0 30px;flex-grow: 0;\"><g fill=\"#ffffff\"><path d=\"M0 0h5.3v5.3H0zm6.8 0h5.3v5.3H6.8zm6.9 0H19v5.3h-5.3zM0 6.8h5.3v5.3H0zm6.8 0h5.3v5.3H6.8zm6.9 0H19v5.3h-5.3zM0 13.7h5.3V19H0zm6.8 0h5.3V19H6.8zM13.7 13.7H19V19h-5.3zM29.8 8.2c1.1-1 2.5-1.5 4-1.5 2.7 0 5.3 1.8 5.3 6.3s-2.9 6.3-6.1 6.3c-1.6 0-3.2-.4-4.6-1.3V0h1.4zm0 9.1c1 .5 2.1.8 3.2.8 2.5 0 4.8-1.5 4.8-5.1 0-3.2-1.8-5.1-4.1-5.1-1.5.1-2.9.8-3.9 1.9zM41.5 19V0h1.3v19zm5.8-4.7c0 2.9 1.4 3.7 2.8 3.7 1.8-.1 3.3-1.1 4.2-2.7V6.9h1.4v12.2h-1.4v-2.4c-1 1.5-2.8 2.5-4.6 2.5-1.9 0-3.8-1.1-3.8-4.8V6.9h1.4zm21.4 3.9c-1.3.7-2.8 1.1-4.3 1-4.1-.1-6.2-3.4-6.1-6.8 0-3.2 2.5-5.8 5.6-5.8h.4c3.3.1 5.5 2.7 5.1 6.6h-9.8c0 2.6 2.1 4.7 4.7 4.8h.1c1.3 0 2.6-.3 3.8-.9zm-.5-6.1c.1-2.2-1.6-4.1-3.8-4.2h-.2c-2.4-.1-4.4 1.8-4.5 4.2zm5.6-2.9c1-1.6 2.6-2.5 4.5-2.6 2.4 0 3.9 1.8 3.9 4.6V19h-1.3v-7.6c0-2.6-1.4-3.6-2.8-3.6-1.8.2-3.3 1.2-4.2 2.7V19h-1.3V0H74c-.2 0-.2 9.2-.2 9.2zm22.3 3.7c0 4-2.7 6.3-5.9 6.3-3.5 0-5.9-2.8-5.9-6.3-.2-3.3 2.3-6.1 5.6-6.3h.3c3.2.1 5.9 2.4 5.9 6.3zm-10.3 0c0 2.7 1.6 5 4.5 5s4.5-2.4 4.5-5-1.7-5-4.5-5-4.5 2.3-4.5 5zm13.4 4c1 .6 2.1 1 3.2 1 1.3 0 2.9-.5 2.8-1.8 0-1.1-1.2-2-3-2.7-2.1-.8-3.9-1.6-3.9-3.5s1.8-3.3 4.2-3.3c1.2 0 2.3.3 3.4.9l-.5 1.1c-.9-.5-1.8-.7-2.8-.7-2 0-2.9 1-2.9 2 0 1.3 1.5 1.8 3.4 2.6 2.9 1.1 3.6 2.5 3.6 3.6 0 1.9-1.8 3.1-4.2 3.1-1.4 0-2.7-.4-3.9-1.1zm16.4-10V8h-4v6.7c0 2 .8 3.2 2.6 3.3.8 0 1.6-.1 2.3-.5l.4 1.2c-.9.3-1.8.5-2.7.5-2.2 0-3.9-1.3-3.9-4.5V8H108V6.9h2.2V2.8h1.4v4.1z\"></path></g></svg><div style=\"margin: 0;line-height:24px;font-size: 16px;font-family:'Open Sans',sans-serif;display: flex;align-items: center;justify-content: space-between;flex-grow: 2;\" class=\"body\"><span style=\"max-width: 600px;flex-grow: 2;\">For over 30 years, .org has given a voice to millions. Start your mission with a .org domain name, now only $9.99/first year.</span> <a href=\"https://my.bluehost.com/hosting/app/#/domains?utm_content=learn_more&amp;utm_term=domains&amp;utm_source=plugin&amp;utm_medium=bluehost_plugin\" style=\"border: 1px solid #2C6CC9;display: inline-flex;padding: 0.5rem 1rem;text-decoration: none;font-weight: bold;font-family: 'Open Sans', sans-serif;font-size: 16px;margin: 0 18px;color: #2C6CC9;outline: 1px solid transparent;border-radius: 3px;flex-shrink: 0;text-align: center;background: #fff;flex-grow: 0;\" class=\"cta-button\">Learn More</a></div></div> <a href=\"\" data-action=\"close\" style=\"margin: 8px 10px 0 0;flex-grow: 0;\" class=\"\"> <svg width=\"16px\" height=\"16px\" viewBox=\"0 0 20 20\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" style=\"\"><title>Close</title><g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g transform=\"translate(-1357.000000, -226.000000)\"><g transform=\"translate(1355.000000, 224.000000)\"> <rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"></rect><path d=\"M14.12125,12 L20.53125,5.59 L21.853125,4.268125 C22.048125,4.073125 22.048125,3.75625 21.853125,3.56125 L20.43875,2.146875 C20.24375,1.951875 19.926875,1.951875 19.731875,2.146875 L12,9.87875 L4.268125,2.14625 C4.073125,1.95125 3.75625,1.95125 3.56125,2.14625 L2.14625,3.560625 C1.95125,3.755625 1.95125,4.0725 2.14625,4.2675 L9.87875,12 L2.14625,19.731875 C1.95125,19.926875 1.95125,20.24375 2.14625,20.43875 L3.560625,21.853125 C3.755625,22.048125 4.0725,22.048125 4.2675,21.853125 L12,14.12125 L18.41,20.53125 L19.731875,21.853125 C19.926875,22.048125 20.24375,22.048125 20.43875,21.853125 L21.853125,20.43875 C22.048125,20.24375 22.048125,19.926875 21.853125,19.731875 L14.12125,12 Z\" fill=\"#fff\"></path></g></g></g></svg></a></div></div><img src='https://hiive.cloud/i.png?notification_id=b33d6d88-a8c0-4d75-b9ff-f75d339babb3' alt='' width='1' height='1' style='position: absolute;' />"
		];
		return $results;
	}

	/**
	 * Get all of the notifications as objects.
	 *
	 * @return Notification[]
	 */
	public function all() {
		return $this->notifications;
	}

	/**
	 * Get the notifications as a collection.
	 *
	 * @return Collection
	 */
	public function collection() {
		return Collection::make( $this->all() );
	}

	/**
	 * Get a notification by ID.
	 *
	 * @param string $id Notification ID.
	 *
	 * @return Notification
	 */
	public function get( $id ) {
		return $this->notifications[ $id ];
	}

	/**
	 * Check if a notification exists.
	 *
	 * @param string $id Notification ID.
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return isset( $this->notifications[ $id ] );
	}

	/**
	 * Remove a notification from the collection.
	 *
	 * @param string $id Notification ID.
	 */
	public function remove( $id ) {
		$notification = $this->notifications[ $id ];
		$notification->dismiss();
		unset( $this->notifications[ $id ] );
		self::setTransient( $this->asArray() );
	}

	/**
	 * Set the transient where notifications are stored.
	 *
	 * @param array     $notifications Array of notifications.
	 * @param float|int $expiration    Transient expiration.
	 */
	public static function setTransient( array $notifications, $expiration = 5 * MINUTE_IN_SECONDS ) {
		set_transient( self::TRANSIENT, array_values( $notifications ), $expiration );
	}

	/**
	 * Delete the transient where notifications are stored.
	 */
	public static function deleteTransient() {
		delete_transient( self::TRANSIENT );
	}

	/**
	 * Get all notifications as an array.
	 *
	 * @return array
	 */
	public function asArray() {
		return array_map(
			function ( Notification $notification ) {
				return $notification->asArray();
			},
			$this->notifications
		);
	}

	/**
	 * Get all notifications as JSON.
	 *
	 * @return string
	 */
	public function asJson() {
		return (string) wp_json_encode( $this->asArray(), JSON_PRETTY_PRINT );
	}

	/**
	 * Convert object to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->asJson();
	}

}
