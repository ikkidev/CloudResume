<?php
namespace WPGDPRC\WordPress;

use WPGDPRC\WordPress\Ajax\ConsentCookie;
use WPGDPRC\WordPress\Ajax\DeleteRequest;
use WPGDPRC\WordPress\Ajax\FormSubmitted;
use WPGDPRC\WordPress\Ajax\HideWelcome;
use WPGDPRC\WordPress\Ajax\PostEditLink;
use WPGDPRC\WordPress\Ajax\ProcessAction;
use WPGDPRC\WordPress\Ajax\ProcessRequest;
use WPGDPRC\WordPress\Ajax\ResetConsent;
use WPGDPRC\WordPress\Ajax\UpdateIntegration;
use WPGDPRC\WordPress\Ajax\UpdatePremiumMode;
use WPGDPRC\WordPress\Ajax\UpdateProcessorMode;

/**
 * Class Ajax
 * @package WPGDPRC\WordPress
 */
class Ajax {

	/**
	 * Ajax constructor
	 */
	public static function init() {
		ConsentCookie::init();
		DeleteRequest::init();
		HideWelcome::init();
		PostEditLink::init();
		ProcessAction::init();
		ProcessRequest::init();
		ResetConsent::init();
		UpdateProcessorMode::init();
		UpdateIntegration::init();
		UpdatePremiumMode::init();
        FormSubmitted::init();
	}

}
