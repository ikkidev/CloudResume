<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Plugin;

?>

<div data-title="<?php echo esc_attr_x( 'Add privacy policy', 'admin', 'wp-gdpr-compliance' ); ?>" class="step">
	<h2 class="h2 step__title">
		<?php echo esc_html_x( 'Add privacy policy', 'admin', 'wp-gdpr-compliance' ); ?>
	</h2>
	<p><?php echo esc_html_x( 'You are required to have a privacy policy on your website to comply with GDPR guidelines. Here you can add a link from your consent pop to your privacy policy.', 'admin', 'wp-gdpr-compliance' ); ?></p>
	<p> <i> <?php echo esc_html_x( 'Your privacy policy lets your customers know what type of data you’re collecting, and what you’re doing with that data.', 'admin', 'wp-gdpr-compliance' ); ?> </i> </p>

	<div class="step__form-wrapper margin-top-1" data-action="<?php echo esc_attr( Wizard::AJAX_SAVE_SETTINGS ); ?>">
		<?php Template::render( 'Admin/Pages/Wizard/Steps/Parts/policy-form' ); ?>
	</div>
</div>
