<?php

use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Settings;
use WPGDPRC\Utils\Elements;

?>
<div class="wpgdprc-welcome">
	<?php if ( ! Settings::isPremium() ) : ?>
		<p>
			<?php echo esc_html_x( 'You’re currently using our free GDPR consent solution, so we’ve got you covered in the EU.', 'admin', 'wp-gdpr-compliance' ); ?>
		</p>
		<p>
			<?php echo esc_html_x( 'Do you have a business website that needs to comply with all global privacy regulations  (GDPR, ePrivacy, and CCPA)? Then you should try our global consent solutions (and steer clear of huge fines!).', 'admin', 'wp-gdpr-compliance' ); ?>
		</p>
		<p>
			<?php
			Elements::link(
				Config::premiumUrl(),
				_x( 'Sign up for a 30-day free trial', 'admin', 'wp-gdpr-compliance' ),
				[
					'target' => '_blank',
					'class'  => 'wpgdprc-sign-up-button',
				]
			);
			?>
		</p>
	<?php else : ?>
		<p>
			<?php echo esc_html_x( 'You’re currently using our the fully featured consent solution, so we’ve got you covered.', 'admin', 'wp-gdpr-compliance' ); ?>
		</p>
	<?php endif; ?>
</div>
