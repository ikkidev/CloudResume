<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;

$plugin_data = Helper::getPluginData();

?>

<div data-title="<?php echo esc_attr_x( 'Start', 'admin', 'wp-gdpr-compliance' ); ?>" class="step">
	<h1 class="h2 step__title">
		<?php echo esc_html_x( 'Get your GDPR foundation in place', 'admin', 'wp-gdpr-compliance' ); ?>
		<span><?php esc_html( sprintf( 'v%1s', $plugin_data['Version'] ) ); ?></span>
	</h1>
	<p>
		<?php echo esc_html_x( 'You’re just a few steps away from being GDPR compliant.', 'admin', 'wp-gdpr-compliance' ); ?>
	</p>
	<p>
		<strong><?php echo esc_html_x( 'We’re just missing these details:', 'admin', 'wp-gdpr-compliance' ); ?></strong>
		<ul>
			<li><?php echo esc_html_x( 'What kind of website do you want to add the pop-up to?', 'admin', 'wp-gdpr-compliance' ); ?></li>
			<li><?php echo esc_html_x( 'Where is your website’s privacy policy? (the pop-up needs to link to it)', 'admin', 'wp-gdpr-compliance' ); ?></li>
		</ul>

		<?php echo esc_html_x( 'Let’s get you set up!', 'admin', 'wp-gdpr-compliance' ); ?>
	</p>
</div>
