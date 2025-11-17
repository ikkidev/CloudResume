<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;

?>

<div class="wpgdprc wpgdprc-modal modal" id="wpgdprc-form-modal" aria-hidden="true">
	<div class="wpgdprc-modal__overlay" tabindex="-1" data-form-close>
		<div class="wpgdprc-modal__inner" role="dialog" aria-modal="true">
			<div class="wpgdprc-modal__header">
				<p class="wpgdprc-modal__title">
					<?php echo esc_html( _x( 'Sign up and we will help you on your journey to compliance', 'admin', 'wp-gdpr-compliance' ) ); ?>
				</p>
				<button class="wpgdprc-modal__close" aria-label="<?php esc_attr_e( 'Close popup', 'wp-gdpr-compliance' ); ?>" data-form-close>
					<?php
                        Template::renderSvg( 'icon-fal-times.svg' );
					?>
				</button>
			</div>

			<div class="wpgdprc-modal__body wpgdprc-form-modal__body">
				<script>
					hbspt.forms.create({
						region: "na1",
						portalId: "5354868",
						formId: "7ead2814-6309-4c4e-9392-382a0481e09d"
					});
				</script>
			</div>
		</div>
	</div>
</div>
