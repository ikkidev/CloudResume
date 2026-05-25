<?php

use WPGDPRC\Utils\AdminForm;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

$section = PageSettings::SECTION_REQUEST;
$fields  = PageSettings::getSectionFields( $section );

?>

<h3 class="screen-reader-text">
	<?php echo esc_html_x( 'Request User', 'admin', 'wp-gdpr-compliance' ); ?>
</h3>

<div class="wpgdprc-form wpgdprc-form--request-user">
	<div class="grid-x grid-margin-x grid-margin-y">
		<div class="cell">
			<div class="wpgdprc-form__intro">
				<h4 class="wpgdprc-form__title h3">
					<?php echo esc_html( _x( 'Request user data form', 'admin', 'wp-gdpr-compliance' ) ); ?>
				</h4>
				<p class="wpgdprc-form__text"><?php echo esc_html_x( "Allow your site's visitors to request their data stored in the WordPress database (comments, WooCommerce orders etc.). Data found is send to their email address and allows them to put in an additional request to have the data anonymized.", 'admin', 'wp-gdpr-compliance' ); ?></p>
			</div>
		</div>
		<div class="cell">
			<fieldset class="">
				<legend class="screen-reader-text"><?php echo esc_html_x( 'Request User data', 'admin', 'wp-gdpr-compliance' ); ?></legend>
				<div class="grid-x grid-margin-x grid-margin-y">

					<div class="cell large-6">
						<?php if ( isset( $fields[ Settings::KEY_ACCESS_ENABLE ] ) ) : ?>
							<div class="wpgdprc-form__field">
								<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_ACCESS_ENABLE ] ); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="cell large-6 activate_yes">
						<?php if ( isset( $fields[ Settings::KEY_ACCESS_TEXT ] ) ) : ?>
							<div class="wpgdprc-form__field">
								<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_ACCESS_TEXT ] ); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="cell large-6 activate_yes">
						<?php if ( isset( $fields[ Settings::KEY_ACCESS_PAGE ] ) ) : ?>
							<div class="wpgdprc-form__field">
								<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_ACCESS_PAGE ] ); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="cell large-6 activate_yes">
						<?php if ( isset( $fields[ Settings::KEY_ACCESS_DELETE_TEXT ] ) ) : ?>
							<div class="wpgdprc-form__field">
								<?php AdminForm::renderSettingFieldFromArray( $fields[ Settings::KEY_ACCESS_DELETE_TEXT ] ); ?>
							</div>
						<?php endif; ?>
					</div>

				</div>
			</fieldset>
		</div>
		<div class="cell">
			<div class="wpgdprc-form__field wpgdprc-form__field--submit">
				<?php AdminForm::renderSubmitButton( $section ); ?>
			</div>
		</div>
	</div>
</div>
