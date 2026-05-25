<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\FormHandler;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;

/**
 * Class UpdateWizardConsent
 * @package WPGDPRC\WordPress\Ajax
 */
class UpdateWizardConsent extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Wizard::AJAX_SAVE_CONSENT;
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * Determines if AJAX call is sending a data attribute
	 * @return bool
	 */
	public static function hasData() {
		return true;
	}

    /**
     * Sanitizes
     * @param array $data
     * @return array
     */
    public static function sanitizeData($data) {
        return [
            'active' => (int) filter_var($data['active'] ?? 0, FILTER_SANITIZE_NUMBER_INT),
            'required' => (int) filter_var($data['required'] ?? 0, FILTER_SANITIZE_NUMBER_INT),
            'description' => sanitize_text_field( wp_unslash($data['description'] ?? '') ),
            'id' => (int) filter_var($data['id'] ?? '', FILTER_SANITIZE_NUMBER_INT),
            'placement' => DataProcessor::validatePlace( $data['placement'] ?? '' ),
            'snippet' => $data['snippet'] ?? '', // should not be sanitized because it is a code snippet.
            'title' => sanitize_text_field(wp_unslash($data['title'] ?? '')),
            'wrap' => key_exists($data['wrap'] ?? '', DataProcessor::listWrapChoices()) ? $data['wrap'] : '',
        ];
    }

    /**
     * @return array|false|void
     */
    public static function validateData() {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- some data is a code snippet individual values will be escaped int the function above.
        return !empty( $_POST['processors'] ) ? $_POST['processors'] : [];
    }

    /**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		$args = FormHandler::consentEditForm( $data );

		ob_start();

		Template::render( 'Admin/Pages/Wizard/Steps/Parts/consent-form', [ 'id' => $args['edit'] ] );

		$response = [
			'success' => true,
			'form'    => ob_get_clean(),
		];

		static::returnResponse( $response );
	}

}
