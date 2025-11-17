<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminForm;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Settings;
use WPGDPRC\WordPress\Config;

$section               = PageSettings::SECTION_CONSENT;
$fields                = PageSettings::getSectionFields( $section );
$notice                = PageSettings::getShortcodeNotice( false, false );
$premium               = Settings::isPremium();
$premium_dashboard_url = Config::premiumDashboardUrl();

?>

<h3 class="screen-reader-text">
	<?php echo esc_html_x( 'Consent bar', 'admin', 'wp-gdpr-compliance' ); ?>
</h3>

<div class="wpgdprc-form wpgdprc-form--consent-bar">
	<div class="grid-x grid-margin-x grid-margin-y">
		<div class="cell large-12">
			<div class="wpgdprc-form__intro">
				<div class="wpgdprc-form__title-container">
					<h4 class="wpgdprc-form__title h3">
						<?php esc_html( _x( 'Consent bar & pop-up settings', 'admin', 'wp-gdpr-compliance' ) ); ?>
					</h4>
				</div>
				<p class="wpgdprc-form__text
				<?php
				if ( $premium ) {
					echo esc_html('hidden');
                }
				?>
				">
					<?php echo wp_kses_post( $notice ); ?>
				</p>
				<p class="wpgdprc-form__text
				<?php
				if ( ! $premium ) {
					echo esc_html('hidden');
                }
				?>
				">
					<?php
						/* translators: %1s: URL to the premium dashboard */
						printf( esc_html_x( 'Style your Consent Bar and Consent Pop-up via the Cookie Information dashboard %1s.', 'admin', 'wp-gdpr-compliance' ), "<a href=\"" . esc_url($premium_dashboard_url) . "\">" . esc_html_x( 'here', 'admin', 'wp-gdpr-compliance' ) . '</a>' );
					?>
				</p>
			</div>
		</div>
		<div class="cell wpgdprc-form--fields
		<?php
		if ( $premium ) {
			echo 'hidden';}
		?>
		">
			<fieldset>
				<div class="grid-x grid-margin-x grid-margin-y">

					<div class="cell wpgdprc-form--fields large-7">
							<div class="cell">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_FONT ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--font">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_FONT ] ); ?>
									</div>
								<?php endif; ?>
							</div>
					</div>

					<div class="cell">
						<legend class="wpgdprc-form__legend h3"><?php echo esc_html_x( 'Consent bar settings', 'admin', 'wp-gdpr-compliance' ); ?></legend>
						<div class="wpgdprc-form__field">
							<span class="wpgdprc-form__label"><?php echo esc_html_x( 'Preview', 'admin', 'wp-gdpr-compliance' ); ?></span>
							<?php DataProcessor::renderBar(); ?>
						</div>
					</div>
					<div class="cell large-7">
						<div class="grid-x grid-margin-x grid-margin-y">

							<div class="cell">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_EXPLAIN_TEXT ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--explaintext">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_EXPLAIN_TEXT ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell large-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_POSITION ] ) ) : ?>
									<div class="wpgdprc-form__field">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_POSITION ] ); ?>
									</div>
								<?php endif; ?>
							</div>

						</div>
					</div>
					<div class="cell large-5">
						<div class="grid-x grid-margin-x grid-margin-y">

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_BTN_TEXT ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--buttontext">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_BTN_TEXT ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_INFO_TEXT ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--moretext">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_INFO_TEXT ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_BG_COLOR ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--colorpicker">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_BG_COLOR ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_TEXT_COLOR ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--colorpicker">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_TEXT_COLOR ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_BTN_PRIMARY ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--colorpicker">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_BTN_PRIMARY ] ); ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="cell xxlarge-6">
								<?php if ( isset( $fields[ Settings::KEY_CONSENT_BTN_SECONDARY ] ) ) : ?>
									<div class="wpgdprc-form__field wpgdprc-form__field--colorpicker">
										<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_BTN_SECONDARY ] ); ?>
									</div>
								<?php endif; ?>
							</div>

						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="cell wpgdprc-form--fields large-7
		<?php
		if ( $premium ) {
			echo 'hidden';}
		?>
		">
			<fieldset class="wpgdprc-form__block wpgdprc-form__block--modal-settings">
				<legend class="wpgdprc-form__legend h3"><?php echo esc_html_x( 'Consent pop-up settings', 'admin', 'wp-gdpr-compliance' ); ?></legend>

				<?php if ( isset( $fields[ Settings::KEY_CONSENT_MODAL_TITLE ] ) ) : ?>
					<div class="wpgdprc-form__field wpgdprc-form__field--modaltitle">
						<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_MODAL_TITLE ] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( isset( $fields[ Settings::KEY_CONSENT_MODAL_TEXT ] ) ) : ?>
					<div class="wpgdprc-form__field wpgdprc-form__field--modaltext">
						<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_CONSENT_MODAL_TEXT ] ); ?>
					</div>
				<?php endif; ?>

			</fieldset>
		</div>
		<div class="cell wpgdprc-form--fields
		<?php
		if ( $premium ) {
			echo 'hidden';}
		?>
		">
			<div class="wpgdprc-form__field wpgdprc-form__field--submit">
				<?php AdminForm::renderSubmitButton( $section ); ?>
			</div>
		</div>
	</div>
</div>
