<?php
/**
 * Admin resources loader
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

require_once BIMBER_ADMIN_DIR . 'default-filters.php';
require_once BIMBER_ADMIN_DIR . 'common.php';
require_once BIMBER_ADMIN_DIR . 'tgm-config.php';
require_once BIMBER_ADMIN_DIR . 'theme-activation.php';
require_once BIMBER_ADMIN_DIR . 'demo-content/functions.php';
require_once BIMBER_ADMIN_DIR . 'theme-options/theme-options.php';
require_once BIMBER_ADMIN_DIR . 'metaboxes/post-single-options.php';
require_once BIMBER_ADMIN_DIR . 'metaboxes/page-header-options.php';
require_once BIMBER_ADMIN_DIR . 'metaboxes/fake-views-metabox.php';
require_once BIMBER_ADMIN_DIR . 'metaboxes/menu-endpoints-metabox.php';
require_once BIMBER_ADMIN_DIR . 'metaboxes/video-post-format-metabox.php';
require_once BIMBER_ADMIN_DIR . 'category.php';
require_once BIMBER_ADMIN_DIR . 'tag.php';
