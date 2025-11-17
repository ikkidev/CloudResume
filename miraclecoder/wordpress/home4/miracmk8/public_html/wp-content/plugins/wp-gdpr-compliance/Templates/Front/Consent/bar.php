<?php

/**
 * @var string $explanation
 * @var string $settings_button
 * @var string $accept_button
 */

use WPGDPRC\WordPress\Front\Consent\Bar;

$position = ! is_admin() ? Bar::getPosition() : '';
$class    = is_admin() ? 'wpgdprc-consent-bar--admin' : '';
$class   .= $position ? "wpgdprc-consent-bar--position-{$position}" : '';

// always start with the bar hidden
?>
<div class="wpgdprc-consent-bar <?php echo esc_attr( $class ); ?>" style="display: none;">
	<div class="wpgdprc-consent-bar__inner">
		<div class="wpgdprc-consent-bar__container">
			<div class="wpgdprc-consent-bar__content">
				<div class="wpgdprc-consent-bar__column wpgdprc-consent-bar__column--notice">
					<div class="wpgdprc-consent-bar__notice"><?php echo wp_kses( $explanation, \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() ); ?></div>
				</div>
				<div class="wpgdprc-consent-bar__column wpgdprc-consent-bar__column--settings">
					<button type="button" class="wpgdprc-button wpgdprc-button--settings"
							data-micromodal-trigger="wpgdprc-consent-modal"
							aria-expanded="false"
							aria-haspopup="true"
					>
						<?php echo esc_html( $settings_button ); ?>
					</button>
				</div>
				<div class="wpgdprc-consent-bar__column wpgdprc-consent-bar__column--accept">
					<button type="button" class="wpgdprc-button wpgdprc-button--accept">
						<?php echo esc_html( $accept_button ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
