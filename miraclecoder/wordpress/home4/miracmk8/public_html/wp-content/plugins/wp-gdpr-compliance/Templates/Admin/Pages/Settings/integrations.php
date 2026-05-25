<?php

use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

Integration::handleForm();

?>

<header class="wpgdprc-content__header">
	<h2 class="wpgdprc-content__title"><?php echo esc_html_x( 'Integrations', 'admin', 'wp-gdpr-compliance' ); ?></h2>
	<p class="wpgdprc-content__text"><?php echo esc_html_x( 'Integrate GDPR Consent Compliance into forms that you use on your website. By enabling the integrations a checkbox will be added. You can manage what text to display per integration.', 'admin', 'wp-gdpr-compliance' ); ?></p>
</header>

<section class="wpgdprc-integrations">
	<?php
	$list = Integration::getList();
	foreach ( $list as $key => $integration ) {
		Template::render(
			'Admin/Pages/Settings/Integrations/item',
			[
				'type'        => $key,
				'integration' => $integration,
			]
		);
	}
	?>
</section>
