<?php
namespace WPGDPRC\Objects;

/**
 * Class AbstractRequest
 * @package WPGDPRC\Objects
 */
abstract class AbstractRequest extends AbstractObject {

	/**
	 * Lists the data for saving to the database
	 * @param bool $new
	 * @return array
	 */
	public function getData( bool $new = false ): array {
		$list = [];
		if ( ! $new ) {
			return $list;
		}

		$list[ static::KEY_SITE_ID ] = $this->getSiteId();
		$list[ static::KEY_CREATED ] = date_i18n( 'Y-m-d H:i:s' );
		return $list;
	}

}
