<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Config;

/**
 * @var string $title
 * @var string $text
 * @var array $list
 * @var array $consents
 * @var string $button
 */

?>

<div class="wpgdprc-consent-modal__body">
	<nav class="wpgdprc-consent-modal__navigation">
		<ul class="wpgdprc-consent-modal__navigation-list">
			<li class="wpgdprc-consent-modal__navigation-item">
				<button class="wpgdprc-consent-modal__navigation-button wpgdprc-consent-modal__navigation-button--active" data-target="description"><?php echo esc_html( $title ); ?></button>
			</li>
			<?php foreach ( $list as $item ) : ?>
				<?php /** @var DataProcessor $item */ ?>
				<li>
					<button class="wpgdprc-consent-modal__navigation-button" data-target="<?php echo esc_attr( $item->getId() ); ?>"><?php echo esc_html( ! empty( $item->getTitle() ) ? $item->getTitle() : __( '(no title)', 'wp-gdpr-compliance' ) ); ?></button>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="wpgdprc-consent-modal__information">
		<div class="wpgdprc-consent-modal__description wpgdprc-consent-modal__description--active" data-target="description">
			<p class="wpgdprc-consent-modal__title wpgdprc-consent-modal__title--description"><?php echo esc_html( $title ); ?></p>
			<div class="wpgdprc-content-modal__content">
				<?php echo wp_kses( $text, AdminHelper::getAllowedHTMLTags() ); ?>
			</div>
		</div>

		<?php foreach ( $list as $item ) : ?>
			<?php /** @var DataProcessor $item */ ?>
			<div class="wpgdprc-consent-modal__description" data-target="<?php echo esc_attr( $item->getId() ); ?>">
				<p class="wpgdprc-consent-modal__title wpgdprc-consent-modal__title--description"><?php echo esc_html( $item->getTitle() ); ?></p>
				<div class="wpgdprc-content-modal__content">
					<?php echo wp_kses( apply_filters( Plugin::PREFIX . '_the_content', $item->getDescription() ), AdminHelper::getAllowedHTMLTags() ); ?>
				</div>
				<?php if ( ! $item->getRequired() ) : ?>
					<div class="wpgdprc-content-modal__options">
						<?php
						Template::render(
							'Front/Consent/modal-content-option',
							[
								'item'     => $item,
								'consents' => $consents,
								'border'   => true,
							]
						);
						?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="wpgdprc-consent-modal__footer">
	<div class="wpgdprc-consent-modal__footer__information">
		<a href="<?php echo esc_url( Config::cookieInformationUrl() ); ?>" target="_blank"><?php echo esc_html__( 'Powered by Cookie Information', 'wp-gdpr-compliance' ); ?></a>
	</div>
	<button class="wpgdprc-button wpgdprc-button--secondary"><?php echo esc_html( $button ); ?></button>
</div>
