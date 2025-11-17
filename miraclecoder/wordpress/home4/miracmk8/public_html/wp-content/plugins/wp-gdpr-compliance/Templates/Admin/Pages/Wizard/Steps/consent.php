<?php

use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\Utils\Template;

?>

<div data-title="<?php echo esc_attr_x( 'Setup first consent', 'admin', 'wp-gdpr-compliance' ); ?>" class="step">
	<h2 class="h2 step__title">
		<?php echo esc_html_x( 'Setup your first consent', 'admin', 'wp-gdpr-compliance' ); ?>
	</h2>
	<p><?php echo esc_html_x( "Most websites use services and plugins for statistical and marketing that require the user's consent to comply with GDPR. Here you can add the first of the services you use. You can always change this later.", 'admin', 'wp-gdpr-compliance' ); ?></p>
	<div class="step__form-wrapper margin-top-1" data-action="<?php echo esc_attr( Wizard::AJAX_SAVE_CONSENT ); ?>">
		<?php Template::render( 'Admin/Pages/Wizard/Steps/Parts/consent-form' ); ?>
	</div>
</div>
