<?php
/**
 * Common resources loader
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

require_once BIMBER_INCLUDES_DIR . 'default-filters.php';
require_once BIMBER_INCLUDES_DIR . 'post.php';
require_once BIMBER_INCLUDES_DIR . 'archive.php';
require_once BIMBER_INCLUDES_DIR . 'archive-template.php';
require_once BIMBER_INCLUDES_DIR . 'home.php';
require_once BIMBER_INCLUDES_DIR . 'options.php';
require_once BIMBER_INCLUDES_DIR . 'ajax.php';
require_once BIMBER_INCLUDES_DIR . 'theme-setup.php';
require_once BIMBER_INCLUDES_DIR . 'theme-migration.php';
require_once BIMBER_INCLUDES_DIR . 'theme-migration-legacy.php';
require_once BIMBER_INCLUDES_DIR . 'theme.php';
require_once BIMBER_INCLUDES_DIR . 'embed.php';
require_once BIMBER_INCLUDES_DIR . 'plugins/functions.php';
require_once BIMBER_INCLUDES_DIR . 'shortcodes/functions.php';
require_once BIMBER_INCLUDES_DIR . 'widgets/functions.php';
require_once BIMBER_INCLUDES_DIR . 'fake-counters.php';
require_once BIMBER_INCLUDES_DIR . 'injections-api.php';
require_once BIMBER_INCLUDES_DIR . 'bending-cat.php';
require_once BIMBER_INCLUDES_DIR . 'header-builder.php';
require_once BIMBER_INCLUDES_DIR . 'quick-nav-links.php';
require_once BIMBER_INCLUDES_DIR . 'post-template.php';
require_once BIMBER_INCLUDES_DIR . 'comment.php';
require_once BIMBER_INCLUDES_DIR . 'gutenberg.php';
require_once BIMBER_INCLUDES_DIR . 'shares/loader.php';


require_once BIMBER_INCLUDES_DIR . 'stack/' . bimber_get_current_stack() . '.php';

/**
 * Below files need to be loaded for both contexts.
 */

// Uses backend panel and frontend preview.
require_once BIMBER_ADMIN_DIR . 'customizer/customizer.php';

// Customizer (backend) decides if cache is stale or still valid, frontend uses cached version.
require_once BIMBER_FRONT_DIR . 'dynamic-style-cache.php';

// bending cat needs this for live preview.
require_once BIMBER_FRONT_DIR . 'common.php';
