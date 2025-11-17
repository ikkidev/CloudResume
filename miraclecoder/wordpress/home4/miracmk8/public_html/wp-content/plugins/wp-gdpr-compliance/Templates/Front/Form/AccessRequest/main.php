<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

if ( empty( $email ) ) {
	$email = esc_attr__( 'Your Email Address', 'wp-gdpr-compliance' );
}
if ( empty( $consent ) ) {
	$consent = Settings::getAccessRequestFormCheckboxText();
}
if ( empty( $submit ) ) {
	$submit = esc_attr__( 'Send', 'wp-gdpr-compliance' );
}

?>

<form class="wpgdprc-form wpgdprc-form--access-request" name="wpgdprc_form" method="POST">
	<ul class="wpgdprc-form__fields">
		<?php
			Template::render(
				'Front/Form/AccessRequest/field-email',
				[
					'label'       => apply_filters( Plugin::PREFIX . '_request_form_email_label', $email ),
					'placeholder' => apply_filters( Plugin::PREFIX . '_request_form_email_placeholder', $email ),
				]
			);
		?>
		<?php
			Template::render(
				'Front/Form/AccessRequest/field-consent',
				[
					'label' => $consent,
				]
		    );
		?>
	</ul>
	<div class="wpgdprc-form__footer">
		<?php
			Template::render(
				'Front/Form/AccessRequest/field-submit',
				[
					'label' => apply_filters( Plugin::PREFIX . '_request_form_submit_label', $submit ),
				]
		    );
		?>
	</div>
	<div class="wpgdprc-message" style="display:none;"></div>
</form>
