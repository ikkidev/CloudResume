<?php

namespace WPGDPRC\Utils;

use WPGDPRC\Integrations\AbstractIntegration;
use WPGDPRC\Integrations\Plugins\AbstractPlugin;
use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Plugin;

/**
 * Class FormHandler
 * @package WPGDPRC\Utils
 */
class FormHandler {

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public static function consentDeleteForm( $id = 0 ) {
		if ( empty( $id ) ) {
			return false;
		}

		if ( ! DataProcessor::exists( $id ) ) {
            AdminHelper::wrapNotice( _x( 'Consent could not be found.', 'admin', 'wp-gdpr-compliance' ), 'warning' );

			return false;
		}

		$success = DataProcessor::deleteById( $id );
		if ( ! $success ) {
            AdminHelper::wrapNotice( _x( 'Something went wrong.', 'admin', 'wp-gdpr-compliance' ), 'warning' );

			return false;
		}

        AdminHelper::wrapNotice( _x( 'Consent deleted.', 'admin', 'wp-gdpr-compliance' ) );

		return true;
	}

	/**
	 * @param array $data
	 *
	 * @return array|false
	 */
	public static function consentEditForm( $data = [] ) {

		$update = date_i18n( 'Y-m-d H:i:s' );
		$object = new DataProcessor( $data['id'] );
		$object->setTitle( ! empty( $data['title'] ) ? stripslashes( esc_html( $data['title'] ) ) : '' );
		$object->setDescription( ! empty( $data['description'] ) ? stripslashes( wp_kses( $data['description'], AdminHelper::getAllowedHTMLTags() ) ) : '' );
		$object->setSnippet( ! empty( $data['snippet'] ) ? $data['snippet'] : '' );
		$object->setWrap( ! empty( $data['wrap'] ) ? 1 : 0 );
		$object->setPlacement( DataProcessor::validatePlace( ! empty( $data['placement'] ) ? $data['placement'] : null ) );
		$object->setPlugins( '' );
		$object->setRequired( ! empty( $data['required'] ) ? 1 : 0 );
		$object->setActive( ! empty( $data['active'] ) ? 1 : 0 );
		$object->setSiteId( get_current_blog_id() );
		if ( empty( $object->getDateCreated() ) ) {
			$object->setDateCreated( $update );
		}

		$changed = $object->updated( new DataProcessor( $data['id'] ) );
		if ( $changed ) {
			$object->setDateModified( $update );
		}
		$id = $object->save();

		$args = [ 'edit' => $id ];
		if ( $changed ) {
			$args['updated'] = true;
			do_action( Plugin::PREFIX . '_consent_updated' );
		}

		return $args;
	}

	/**
	 * Redirect after form save
	 */
	public static function consentEditFormRedirect( $args = [] ) {
        ?>
            <script>
                    location.href = "<?php echo esc_js(filter_var( add_query_arg( $args, PageDashboard::getTabUrl( PageDashboard::TAB_PROCESSORS ) ), FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED )); ?>"
            </script>
        <?php
		die();
	}
}
