<?php

use WPGDPRC\Integrations\AbstractIntegration;
use WPGDPRC\Integrations\Plugins\AbstractPlugin;
use WPGDPRC\Integrations\Plugins\ContactForm;
use WPGDPRC\Integrations\Plugins\GravityForms;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;

/**
 * @var AbstractIntegration | AbstractPlugin $integration
 * @var string $type
 * @var string $prefix
 * @var bool $enabled
 * @var array $values
 */

$list = false;

if ( method_exists( $integration, 'getList' ) ) {
	$list = $integration->getList();
}

$id = $controls = 'integration-item-container-' . $type;
?>

<div id="<?php echo esc_attr( $id ); ?>" class="wpgdprc-integration-item__container">

	<?php
	if ( ! is_array( $list ) ) {
		Template::render(
			'Admin/Pages/Settings/Integrations/item-form',
			[
				'prefix'      => $prefix,
				'key'         => '',
				'values'      => $values,
				'integration' => $integration,
			]
		);
	} else {
		foreach ( $list as $key => $title ) {
			Template::render(
				'Admin/Pages/Settings/Integrations/item-form',
				[
					'prefix'      => $prefix,
					'key'         => $key,
					'values'      => $values,
					'title'       => $title,
					'integration' => $integration,
				]
			);
		}
	}
	?>

	<div class="wpgdprc-integration-item__submit">
		<?php
		submit_button(
			_x( 'Disable integration', 'admin', 'wp-gdpr-compliance' ),
			'wpgdprc-button wpgdprc-button--transparent wpgdprc-button--small',
			PageSettings::SECTION_INTEGRATE . '[disable][' . $type . ']',
			'',
			[
				'class'         => 'wpgdprc-button wpgdprc-button--transparent wpgdprc-button--small',
				'data-enable'   => '0',
				'data-type'     => $type,
				'aria-expanded' => 'false',
				'aria-controls' => $controls,
			]
		);
		?>
	</div>
</div>

