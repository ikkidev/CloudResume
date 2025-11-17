<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\Utils\Integration;

/**
 * @var string $current
 * @var array $tabs
 * @var array $setting_tabs
 */

$title = isset( $tabs[ $current ]['title'] ) ? $tabs[ $current ]['title'] : '';
$intro = isset( $tabs[ $current ]['intro'] ) ? $tabs[ $current ]['intro'] : '';

?>

<article class="wpgdprc-container">
	<div class="wpgdprc-content">
		<?php
		switch ( $current ) {
			case PageDashboard::TAB_PROCESSORS:
				Template::render(
					'Admin/Pages/Processors/main',
					[
						'current' => PageDashboard::TAB_PROCESSORS,
						'title'   => $title,
						'intro'   => $intro,
					]
				);
				break;

			case PageDashboard::TAB_SETTINGS:
				Template::render(
					'Admin/Pages/Settings/main',
					[
						'current'  => PageDashboard::TAB_SETTINGS,
						'title'    => $title,
						'intro'    => $intro,
						'sections' => $setting_tabs,
					]
				);
				break;

			case PageDashboard::TAB_PREMIUM:
				Template::render(
					'Admin/Pages/Premium/main',
					[
						'current' => PageDashboard::TAB_PREMIUM,
						'title'   => $title,
						'intro'   => $intro,
					]
				);
				break;

			default:
				Template::render(
					'Admin/Pages/Dashboard/main',
					[
						'title'        => $title,
						'checkboxes'   => Integration::getActiveFormCount(),
						'forms'        => Integration::getValidFormCount(),
						'consents'     => DataProcessor::getTotal( [ 'active' => [ 'value' => 1 ] ] ),
						'requests'     => RequestAccess::getTotal(),
						'last_request' => date( _x( 'F jS, Y', 'admin', 'wp-gdpr-compliance' ), strtotime( RequestAccess::getLastCreated() ) ),
					]
				);
		}
		?>
	</div>
</article>

<?php Template::render(
	'Admin/page-tablist',
	[
		'class' => 'alignleft',
		'title' => _x( 'Dashboard navigation', 'admin', 'wp-gdpr-compliance' ),
		'tabs'  => $tabs,
	]
); ?>
