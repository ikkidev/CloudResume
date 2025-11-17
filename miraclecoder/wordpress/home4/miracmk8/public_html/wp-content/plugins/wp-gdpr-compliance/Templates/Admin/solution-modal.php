<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;

?>

<div class="wpgdprc wpgdprc-modal modal" id="wpgdprc-sign-up-modal" aria-hidden="true">
	<div class="wpgdprc-modal__overlay" tabindex="-1" data-signup-close>
		<div class="wpgdprc-modal__inner" role="dialog" aria-modal="true">
			<div class="wpgdprc-modal__header">
				<p class="wpgdprc-modal__title choose-type-title">
					<?php echo esc_html_x( 'Choose your consent solution', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>
				<p class="wpgdprc-modal__title sign-up-title" style="display: none">
					<?php echo esc_html_x( 'Start your 30-day free trial', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>
				<button class="wpgdprc-modal__close" aria-label="
				<?php
					esc_html_x( 'Close popup', 'admin', 'wp-gdpr-compliance' );
				?>
				" data-signup-close>
					<?php Template::renderSvg( 'icon-fal-times.svg' ); ?>
				</button>
				<button class="wpgdprc-modal__back" aria-label="
				<?php
					esc_html_x( 'Back', 'admin', 'wp-gdpr-compliance' );
				?>
				">
					<?php Template::renderIcon( 'arrow-left', 'fontawesome-pro-regular' ); ?>
				</button>
			</div>

			<div class="wpgdprc-sign-up-modal__step wpgdprc-sign-up-modal__choose-type">
				<div class="wpgdprc-sign-up-modal__columns">
					<div class="wpgdprc-sign-up-modal__column">
						<?php Template::renderIcon( 'user-alt', 'fontawesome-pro-regular' ); ?>
						<p class="h3">
							<?php echo esc_html_x( 'Non-business mode (personal websites)', 'admin', 'wp-gdpr-compliance' ); ?>
						</p>
						<p>
							<?php echo esc_html_x( 'Recommended to all non-business or commercial websites.', 'admin', 'wp-gdpr-compliance' ); ?>
						</p>
						<div class="wpgdprc-button__wrap">
							<button data-signup-private class="wpgdprc-button">
								<?php
								echo esc_html_x(
									'Continue with the non-business mode',
									'admin',
									'wp-gdpr-compliance'
								)
								?>
							</button>
						</div>
					</div>
					<div class="wpgdprc-sign-up-modal__column">
						<?php Template::renderIcon( 'store-alt', 'fontawesome-pro-regular' ); ?>
						<p class="h3">
							<?php echo esc_html_x( 'Global compliance (business websites)', 'admin', 'wp-gdpr-compliance' ); ?>
						</p>
						<p>
							<?php echo esc_html_x( 'Recommended for companies that want to remove all risk and stay fully compliant with all global privacy regulations (GDPR, ePrivacy, and CCPA).', 'admin', 'wp-gdpr-compliance' ); ?>
						</p>
						<div class="wpgdprc-button__wrap">
							<button data-signup-business class="wpgdprc-button">
								<?php
								echo esc_html_x(
									'Try 30 days for free',
									'admin',
									'wp-gdpr-compliance'
								)
								?>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="wpgdprc-sign-up-modal__step wpgdprc-sign-up-modal__sign-up" style="display: none">
				<iframe id="signupCookieInformation"
						title="<?php echo esc_attr_x( 'Signup for Cookie Information', 'admin', 'wp-gdpr-compliance' ); ?>"
						style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"
						src="<?php echo esc_attr( Config::addUTMParams( 'https://cookieinformation.com/only-form/' ) ); ?>" loading="lazy"
				></iframe>
			</div>
		</div>
	</div>
</div>
