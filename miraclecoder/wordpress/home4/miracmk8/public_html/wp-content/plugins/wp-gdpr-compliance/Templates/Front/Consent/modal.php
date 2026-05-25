<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Cookie;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Front\Consent\Modal;

/**
 * @var string $content
 */

?>

<div class="wpgdprc wpgdprc-consent-modal" id="wpgdprc-consent-modal" aria-hidden="true">
	<div class="wpgdprc-consent-modal__overlay" tabindex="-1" data-micromodal-close>
		<div class="wpgdprc-consent-modal__inner" role="dialog" aria-modal="true">
			<div class="wpgdprc-consent-modal__header">
				<p class="wpgdprc-consent-modal__title"><?php esc_html_e( 'Privacy settings', 'wp-gdpr-compliance' ); ?></p>
				<button class="wpgdprc-consent-modal__close" aria-label="<?php esc_attr_e( 'Close popup', 'wp-gdpr-compliance' ); ?>" data-micromodal-close>
					<?php Template::renderSvg( 'icon-fal-times.svg' ); ?>
				</button>
			</div>
            <?php

                $list = DataProcessor::getListByType( 'active' );

                if ( !empty( $list ) ) {

                    Template::render(
                    'Front/Consent/modal-content',
                        [
                            'title'    => Modal::getTitle(),
                            'text'     => Modal::getText() . Modal::getNote(),
                            'list'     => $list,
                            'consents' => Cookie::getConsentIDs(),
                            'button'   => Modal::getButton(),
                        ]
                    );
                }

            ?>
		</div>
	</div>
</div>
