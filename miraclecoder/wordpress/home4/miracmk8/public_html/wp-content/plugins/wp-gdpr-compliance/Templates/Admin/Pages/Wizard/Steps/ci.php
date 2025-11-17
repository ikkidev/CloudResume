<?php

use WPGDPRC\WordPress\Config;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\AdminForm;

?>

<div data-action="disable" data-title="<?php echo esc_attr_x( 'Select website type', 'admin', 'wp-gdpr-compliance' ); ?>" class="step">
	<h1 class="h2 step__title">
		<?php echo esc_html_x( 'Select website type', 'admin', 'wp-gdpr-compliance' ); ?>
	</h1>

	<div class="questionnaire margin-top-2">
		<p class="margin-bottom-0"><?php echo esc_html_x( 'What kind of website do you need a cookie pop-up for?', 'admin', 'wp-gdpr-compliance' ); ?></p>
		<div class="flex-container flex-dir-row-reverse align-right align-middle">
			<?php
			AdminForm::renderField(
				'radio',
				_x( 'A business website', 'admin', 'wp-gdpr-compliance' ),
				'user_type',
				'business',
				[
					'required' => 'required',
					'id'       => 'business',
				],
				false
			);
			?>
		</div>
		<div class="flex-container flex-dir-row-reverse align-right align-middle">
			<?php
				AdminForm::renderField(
					'radio',
					_x( 'A personal website', 'admin', 'wp-gdpr-compliance' ),
					'user_type',
					'personal',
					[
						'required' => 'required',
						'id'       => 'personal',
					],
					false
				);
				?>
		</div>


		<div id="signup" class="margin-top-2 hide">
			<div data-for="business">
				<p>
					<?php echo esc_html_x( 'Business websites often have to comply <strong>with more</strong> than just GDPR.', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>

				<p>
					<strong><?php echo esc_html_x( 'Is that you?', 'admin', 'wp-gdpr-compliance' ); ?></strong>
				</p>

				<p>
					<?php echo esc_html_x( 'If so, we recommend trying our free 30-day trial so you can:', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>

				<ul>
					<li><?php echo wp_kses( _x( '<strong>Remove all risk</strong> by complying with all global privacy regulations  (GDPR, ePrivacy, and CCPA)', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
					<li><?php echo wp_kses( _x( '<strong>Unlock our powerful</strong> scanner that notifies you whenever one of your webpages is illegally tracking and saving private data.', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
					<li><?php echo wp_kses( _x( '<strong>Customize your content</strong> and so much more!', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
				</ul>
			</div>
			<div data-for="personal">
				<p>
					<?php echo esc_html_x( 'For personal websites in the EU, this plugin’s got you covered. ', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>

				<p>
					<?php echo esc_html_x( 'But, if you’re concerned about being 100% compliant globally, we recommend turning your compliance up a notch. ', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>

				<p>
					<?php echo esc_html_x( 'Try our free 30-day trial so you can: ', 'admin', 'wp-gdpr-compliance' ); ?>
				</p>

				<ul>
					<li><?php echo wp_kses( _x( '<strong>Remove all risk</strong> by complying with all global privacy regulations  (GDPR, ePrivacy, and CCPA)', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
					<li><?php echo wp_kses( _x( '<strong>Unlock our powerful</strong> scanner that notifies you whenever one of your webpages is illegally tracking and saving private data.', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
					<li><?php echo wp_kses( _x( '<strong>Customize your content</strong> and so much more!', 'admin', 'wp-gdpr-compliance' ), [ 'strong' => [] ] ); ?></li>
				</ul>
			</div>
			<?php
			Elements::link(
				Config::premiumUrl(),
				_x( 'Start your 30-day free trial', 'admin', 'wp-gdpr-compliance' ),
				[
					'target' => '_blank',
					'class'  => 'wpgdprc-button button',
					'id'     => 'signuplink',
				]
			);
			?>
		</div>
	</div>
</div>
