<?php

use WPGDPRC\Integrations\AbstractIntegration;
use WPGDPRC\Integrations\Plugins\AbstractPlugin;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Settings;

/**
 * @var string $type
 * @var AbstractIntegration|AbstractPlugin $integration
 */

$prefix      = Settings::getKey( $type, Settings::INTEGRATIONS_GROUP );
$class       = 'wpgdprc-integration-item--' . $type;
$class_icon  = 'wpgdprc-integration-item__icon--' . $type;
$controls_id = 'integration-item-container-' . $type;

?>

<div class="wpgdprc-integration-item <?php echo esc_attr( $class ); ?>" data-expand>
	<div class="wpgdprc-integration-item__header">
		<div class="wpgdprc-integration-item__header-inner">
			<div class="wpgdprc-integration-item__content">
				<h3 class="wpgdprc-integration-item__title">
					<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>" value="<?php echo ! empty( $integration->isEnabled() ) ? 1 : 0; ?>"/>
					<?php echo esc_html( $integration->getName() ); ?>
					<?php
					Template::render(
						'Admin/Pages/Settings/Integrations/item-active',
						[
							'enabled' => $integration->isEnabled(),
						]
					);
					?>
				</h3>
				<p class="wpgdprc-integration-item__text"><?php echo wp_kses( $integration->getDescription(), [ 'strong' => [] ] ); ?></p>
			</div>
			<div class="wpgdprc-integration-item__icon <?php echo esc_attr( $class_icon ); ?>">
				<?php Template::renderSvg( $integration->getIcon() ); ?>
			</div>
		</div>
		<div class="wpgdprc-integration-item__action">
			<?php
			Template::render(
				'Admin/Pages/Settings/Integrations/item-manage',
				[
					'enabled'     => $integration->isEnabled(),
					'supported'   => $integration->isSupported(),
					'plugin'      => $integration->isActivated(),
					'type'        => $type,
					'controls_id' => $controls_id,
				]
			);
			?>
		</div>
	</div>
	<?php
	if ( ! empty( $integration->getNotice() ) ) {
		echo wp_kses_post( $integration->getNotice() );
	} else {
		Template::render(
			'Admin/Pages/Settings/Integrations/item-content',
			[
				'integration' => $integration,
				'type'        => $type,
				'prefix'      => $prefix,
				'enabled'     => $integration->isEnabled(),
				'values'      => $integration->getValues(),
				'controls_id' => $controls_id,
			]
		);
	}
	?>
</div>
