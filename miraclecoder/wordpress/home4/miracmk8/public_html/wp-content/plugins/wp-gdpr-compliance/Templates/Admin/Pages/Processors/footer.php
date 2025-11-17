<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminForm;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;

/**
 * @var string $current
 */

$prefix  = ! empty( $current ) ? $current : PageDashboard::TAB_PROCESSORS;
$active  = DataProcessor::isActive();
$status  = $active ? _x( 'active', 'admin', 'wp-gdpr-compliance' ) : _x( 'not active', 'admin', 'wp-gdpr-compliance' );
$default = [
	'class'       => 'wpgdprc-tile--text',
	'heading'     => 4,
	'title_class' => 'h6',
];
$tiles   = [
	[
		'class'  => 'wpgdprc-tile--text wpgdprc-tile--consent-bar ' . ( $active ? 'wpgdprc-tile--green-light' : '' ),
		'title'  => _x( 'Cookie pop-up', 'admin', 'wp-gdpr-compliance' ),
		/* translators: %1s: Consent bar status */
		'text'   => sprintf( _x( 'The consent bar is %1s', 'admin', 'wp-gdpr-compliance' ), '<strong>' . $status . '</strong>' ),
		'extra'  => function() use ($active, $prefix) {
            echo wp_kses_post( '<div class="wpgdprc-tile__check ' . ( $active ? '' : 'hide' ) . '">' );
            AdminForm::renderField(
                'truefalse',
                _x( 'Toggle consent bar', 'admin', 'wp-gdpr-compliance' ),
                $prefix . '[active]',
                $active,
                [
                    'border'          => true,
                    'data-enable'     => $active ? '1' : '0',
                ],
                true
            );
            echo wp_kses_post( '</div>');
        },
		'footer' => '<p class="wpgdprc-tile__message">' . ( $active ? _x( '<strong>(!)</strong> By deactivating the pop-up, you’ll no longer be asking people for consent, and you’re at risk of being non-compliant.', 'admin', 'wp-gdpr-compliance' ) : _x( 'To enable the Cookie pop-up, activate one (or more) data processors.', 'admin', 'wp-gdpr-compliance' ) ) . '</p>',
	],
	[
		'title'  => _x( 'Reset Cookie pop-up', 'admin', 'wp-gdpr-compliance' ),
		'text'   => _x( 'Want to reset the Cookie pop-up? This means that the consent bar will appear again for all users.', 'admin', 'wp-gdpr-compliance' ),
		'footer' => function() {
            Elements::button(
                _x( 'Reset', 'admin', 'wp-gdpr-compliance' ) . Template::getIcon( 'sync' ),
                [
                    'class'       => 'wpgdprc-button wpgdprc-button--icon wpgdprc-button--white wpgdprc-button--small',
                    'data-action' => 'reset-consent',
                ]
            );
        },
	],
	[
		'title'  => _x( 'Change the look', 'admin', 'wp-gdpr-compliance' ),
		'text'   => _x( 'Change the colors of the consent bar and the settings.', 'admin', 'wp-gdpr-compliance' ),
		'footer' => function() { Elements::link(
			PageSettings::getSectionUrl( PageSettings::SECTION_CONSENT ),
                _x( 'Change settings', 'admin', 'wp-gdpr-compliance' ),
                [ 'class' => 'wpgdprc-button wpgdprc-button--white wpgdprc-button--small' ]
            );
        },
	],
];

?>

<section class="wpgdprc-tiles">
	<div class="wpgdprc-tiles__header">
		<h3 class="wpgdprc-tiles__title h3"><?php echo esc_html_x( 'Settings', 'admin', 'wp-gdpr-compliance' ); ?></h3>
	</div>
	<div class="wpgdprc-tiles__container">
		<div class="grid-x grid-margin-x grid-margin-y">
			<?php foreach ( $tiles as $tile ) : ?>
				<div class="cell large-6 xlarge-4">
					<?php Template::render( 'Admin/tile', array_merge( $default, $tile ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
