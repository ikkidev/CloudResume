<?php

use WPGDPRC\Utils\AdminForm;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Template;

/**
 * @var string $type
 * @var AbstractIntegration | AbstractPlugin $integration
 * @var string $prefix
 * @var string $key
 * @var array $values
 */

// @TODO : Convert fields to 'AdminForm::renderSettingFieldFromArray([], Settings::INTEGRATIONS_GROUP);'
// @TODO : Store entered values + get stored values for field values
// @TODO : Add migration for stored values

$append = ! empty( $key ) ? '[' . $key . ']' : '';

?>

<div class="wpgdprc-integration-item__form">

	<?php if ( ! empty( $title ) ) : ?>
		<div class="flex-container align-justify align-middle">
			<p>
				<?php
					/* translators: %s: title */
					echo esc_html( sprintf( _x( 'Form: %1s', 'admin', 'wp-gdpr-compliance' ), $title ) );
				?>
			</p>
			<?php if ( ! empty( $key ) && ( isset( $values['forms'] ) && is_array( $values['forms'] ) ) ) : ?>
				<?php AdminForm::renderField( 'truefalse', _x( 'Activate for this form', 'admin', 'wp-gdpr-compliance' ), $prefix . '_forms[' . $key . ']', in_array( $key, $values['forms'], true ), [ 'data-integration' => $integration->getId() ], false ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="wpgdprc-integration-item__form-field">
		<label class="wpgdprc-integration-item__form-label wpgdprc-integration-item__form-label--large" for="<?php echo sanitize_key( $prefix . '_checkbox' ); ?>">
			<?php echo esc_html_x( 'Change the text and messages of the checkbox', 'admin', 'wp-gdpr-compliance' ); ?>
		</label>
		<div class="wpgdprc-integration-item__form-input-container wpgdprc-integration-item__form-input-container--main">
			<?php Template::renderIcon( 'check-square', 'fontawesome-pro-regular' ); ?>
			<?php
			AdminForm::renderField(
				'text',
				_x( 'Change the text of the checkbox', 'admin', 'wp-gdpr-compliance' ),
				$prefix . '_text' . $append,
				$values['text'][ $key ] ?? ( $values['text'] ?? '' ),
				[
					'class'            => 'wpgdprc-integration-item__form-input',
					'data-integration' => $integration->getId(),
				],
				true
			);
			?>
		</div>
	</div>
	<div class="wpgdprc-integration-item__form-group">
		<div class="wpgdprc-integration-item__grid">
			<div class="wpgdprc-integration-item__cell">
				<div class="wpgdprc-integration-item__form-field">
					<div class="wpgdprc-integration-item__form-input-container">
						<?php
						AdminForm::renderField(
							'text',
							_x( 'Error message', 'admin', 'wp-gdpr-compliance' ),
							$prefix . '_error_message' . $append,
							$values['error_message'][ $key ] ?? ( $values['error_message'] ?? '' ),
							[
								'class'            => 'wpgdprc-integration-item__form-input',
								'data-integration' => $integration->getId(),
							],
							false
						);
						?>
					</div>
				</div>
			</div>
			<div class="wpgdprc-integration-item__cell">
				<div class="wpgdprc-integration-item__form-field">
					<div class="wpgdprc-integration-item__form-input-container">
						<?php
						AdminForm::renderField(
							'text',
							_x( 'Required message', 'admin', 'wp-gdpr-compliance' ),
							$prefix . '_required_message' . $append,
							$values['required_message'][ $key ] ?? ( $values['required_message'] ?? '' ),
							[
								'class'            => 'wpgdprc-integration-item__form-input',
								'data-integration' => $integration->getId(),
							],
							false,
							_x( 'HTML is not allowed because of technical limitations with tooltips.', 'admin', 'wp-gdpr-compliance' )
						);
						?>
					</div>
				</div>
			</div>
		</div>
		<p class="wpgdprc-integration-item__form-description">
			<?php echo esc_html( AdminHelper::getAllowedHTMLTagsOutput() ); ?>
		</p>
	</div>
</div>
