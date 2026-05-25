<?php

use WPGDPRC\Utils\AdminForm;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

// @TODO : Check if values are passed to the front of the site
$section  = PageSettings::SECTION_PRIVACY;
$fields   = PageSettings::getSectionFields( $section );
$external = Settings::isEnabled( Settings::KEY_POLICY_EXTERN );

?>

<h3 class="screen-reader-text">
	<?php echo esc_html_x( 'Privacy policy', 'admin', 'wp-gdpr-compliance' ); ?>
</h3>

<section class="wpgdprc-form wpgdprc-form--privacy-policy">
	<h4 class="screen-reader-text">
		<?php echo esc_html_x( 'Privacy policy link settings', 'admin', 'wp-gdpr-compliance' ); ?>
	</h4>
	<div class="grid-x grid-margin-x grid-margin-y">
		<div class="cell large-8">
			<?php if ( isset( $fields[ Settings::KEY_POLICY_TEXT ] ) ) : ?>
				<fieldset class="wpgdprc-form__field">
					<legend class="wpgdprc-form__legend"><?php echo esc_html_x( 'What do you call your Privacy Policy (the link text)?', 'admin', 'wp-gdpr-compliance' ); ?></legend>
					<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_POLICY_TEXT ] ); ?>
				</fieldset>
			<?php endif; ?>
		</div>
		<div class="cell large-8">
			<?php if ( isset( $fields[ Settings::KEY_POLICY_PAGE ] ) ) : ?>
				<fieldset id="external_no" class="wpgdprc-form__field 
				<?php
				if ( $external ) {
					echo 'hidden';}
				?>
				">
					<legend class="wpgdprc-form__legend"><?php echo esc_html_x( 'Privacy policy page', 'admin', 'wp-gdpr-compliance' ); ?></legend>
					<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_POLICY_PAGE ] ); ?>
				</fieldset>
			<?php endif; ?>
			<?php if ( isset( $fields[ Settings::KEY_POLICY_LINK ] ) ) : ?>
				<fieldset id="external_yes" class="wpgdprc-form__field 
				<?php
				if ( ! $external ) {
					echo 'hidden';}
				?>
				">
					<legend class="wpgdprc-form__legend"><?php echo esc_html_x( 'Where can people find your Privacy Policy? (URL)', 'admin', 'wp-gdpr-compliance' ); ?></legend>
					<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_POLICY_LINK ] ); ?>
				</fieldset>
			<?php endif; ?>
		</div>
		<div class="cell large-8">
			<?php if ( isset( $fields[ Settings::KEY_POLICY_EXTERN ] ) ) : ?>
				<fieldset class="wpgdprc-form__field wpgdprc-form__field--switch wpgdprc-form__field--switch-no-text">
					<legend class="wpgdprc-form__legend"><?php echo esc_html_x( 'External privacy policy?', 'admin', 'wp-gdpr-compliance' ); ?></legend>
					<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_POLICY_EXTERN ] ); ?>
				</fieldset>
			<?php endif; ?>
		</div>
		<div class="cell">
			<div class="wpgdprc-form__field wpgdprc-form__field--submit">
				<?php AdminForm::renderSubmitButton( $section ); ?>
			</div>
		</div>
	</div>
</section>
