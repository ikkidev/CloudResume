<?php

namespace NewfoldLabs\WP\Module\InstallChecker;

class WooCommerce {

	/**
	 * Check if WooCommerce is installed and active.
	 *
	 * @return bool
	 */
	public static function isWooCommerce() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Get all WooCommerce page IDs.
	 *
	 * @return int[]
	 */
	public static function getAllPageIds() {
		
		$pages = array(
			'shop',
			'cart',
			'checkout',
			'myaccount',
			'refund_returns'
		);
		
		array_walk( $pages, function ( &$page ) {
			$page =  wc_get_page_id( $page );
		});
		
		return array_filter( $pages, function ( $page ) {
			return $page >= 1;
		} );
	}
}