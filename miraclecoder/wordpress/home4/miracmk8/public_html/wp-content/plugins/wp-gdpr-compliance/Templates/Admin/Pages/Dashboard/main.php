<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageRequests;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * @var int    $checkboxes
 * @var int    $forms
 * @var int    $consents
 * @var int    $requests
 * @var string $last_request
 */

$disabled = ( $forms - $checkboxes );
$welcome  = ! version_compare( Plugin::VERSION, get_option( Plugin::PREFIX . '_hide_welcome', '1.0' ), '<' );

$list = [
	'checkboxes' => [
		/* translators: %1s: number of checkboxes */
		'title'  => sprintf( _nx( '%1s Active checkbox', '%1s Active checkboxes', $checkboxes, 'admin', 'wp-gdpr-compliance' ), '<span class="h1">' . $checkboxes . '</span>' ),
		/* translators: %1s: number of forms */
		'text'   => $disabled > 0 ? sprintf( _nx( '%1s form doesn’t seem to have a checkbox enabled', '%1s forms don’t seem to have a checkbox enabled', $disabled, 'admin', 'wp-gdpr-compliance' ), '<strong>' . $disabled . '</strong>' ) : '',
		'link'   => [
			'title' => _x( 'Manage checkboxes', 'admin', 'wp-gdpr-compliance' ),
			'url'   => PageSettings::getSectionUrl( PageSettings::SECTION_INTEGRATE ),
		],
		'class'  => 'wpgdprc-tile--manage',
		'manage' => true,
	],
	'processors' => [
		/* translators: %1s: number of active data processor */
		'title'  => sprintf( _nx( '%1s Active data processor', '%1s Active data processors', $consents, 'admin', 'wp-gdpr-compliance' ), '<span class="h1">' . $consents . '</span>' ),
		/* translators: %1s: consent bar status */
		'text'   => sprintf( _x( 'The consent bar is %1s', 'admin', 'wp-gdpr-compliance' ), DataProcessor::isActive() ? _x( 'showing', 'admin', 'wp-gdpr-compliance' ) : _x( 'not showing', 'admin', 'wp-gdpr-compliance' ) ),
		'link'   => [
			'title' => _x( 'Manage data processors', 'admin', 'wp-gdpr-compliance' ),
			'url'   => PageDashboard::getTabUrl( PageDashboard::TAB_PROCESSORS ),
		],
		'class'  => 'wpgdprc-tile--manage',
		'manage' => true,
	],
	'requests'   => [
		/* translators: %1s: number of requests */
		'title'  => sprintf( _nx( '%1s Data request', '%1s Data requests', $requests, 'admin', 'wp-gdpr-compliance' ), '<span class="h1">' . $requests . '</span>' ),
		/* translators: %1s: last request */
		'text'   => $requests > 0 ? sprintf( _x( 'Last request is from %1s', 'admin', 'wp-gdpr-compliance' ), $last_request ) : '',
		'link'   => [
			'title' => _x( 'Manage data requests', 'admin', 'wp-gdpr-compliance' ),
			'url'   => PageRequests::getPageUrl(),
		],
		'class'  => 'wpgdprc-tile--manage',
		'manage' => true,
	],
];

if ( ! Settings::isPremium() ) {
	$list['premium'] = [
		'title'  => _x( 'Own a business website?', 'admin', 'wp-gdpr-compliance' ),
		'text'   => _x( 'Try our premium plan for free for 30 days, and make sure your website is globally compliant.', 'admin', 'wp-gdpr-compliance' ),
		'link'   => [
			'title'      => _x( 'Try it out now', 'admin', 'wp-gdpr-compliance' ),
			'url'        => Config::premiumUrl(),
			'attributes' => [
				'target' => '_blank',
				'class'  => 'wpgdprc-sign-up-button',
			],
		],
		'class'  => 'wpgdprc-tile--primary',
		'manage' => false,
	];
}

?>
<header class="wpgdprc-content__header">
	<h2 class="wpgdprc-content__title"><?php echo esc_html_x( 'Dashboard', 'admin', 'wp-gdpr-compliance' ); ?></h2>
</header>

<?php
if ( $welcome ) {
	Template::render( 'Admin/Pages/Dashboard/welcome', [] );}
?>

<section class="wpgdprc-tiles">
	<div class="grid-x grid-margin-x grid-margin-y">
		<?php foreach ( $list as $key => $item ) : ?>
			<div class="cell large-6 xxlarge-3">
				<?php
				Template::render(
					'Admin/Pages/Dashboard/tile',
					[
						'class'           => implode( ' ', [ 'wpgdprc-tile--' . $key, $item['class'] ] ),
						'title_class'     => ! empty( $item['manange'] ) ? ' h3' : '',
						'title'           => ! empty( $item['title'] ) ? $item['title'] : '',
						'text'            => ! empty( $item['text'] ) ? $item['text'] : '',
						'link_title'      => ! empty( $item['link']['title'] ) ? $item['link']['title'] : '',
						'link_url'        => ! empty( $item['link']['url'] ) ? $item['link']['url'] : '',
						'link_attributes' => ! empty( $item['link']['attributes'] ) ? $item['link']['attributes'] : [],
					]
				);
				?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
