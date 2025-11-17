<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminForm;
use WPGDPRC\Utils\PrivacyPolicy;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Plugin;

/**
 * @var string        $current
 * @var int           $id
 * @var DataProcessor $object
 */

$prefix = ! empty( $current ) ? $current : PageDashboard::TAB_PROCESSORS;
$title  = ! empty( $id ) ? _x( 'Edit processor', 'admin', 'wp-gdpr-compliance' ) : _x( 'New processor', 'admin', 'wp-gdpr-compliance' );

?>

<form method="post" action="">
	<header class="wpgdprc-content__header wpgdprc-content__header--justify">
		<h2 class="wpgdprc-content__title"><?php echo esc_html( $title ); ?></h2>
		<ul class="wpgdprc-content__actions">
			<li>
				<?php
				AdminForm::renderField( 'truefalse', _x( 'Required', 'admin', 'wp-gdpr-compliance' ), $prefix . '[required]', $object->getRequired(), [ 'border' => true ], false );
				?>
			</li>
			<li>
				<?php
				AdminForm::renderField( 'truefalse', _x( 'Active processor?', 'admin', 'wp-gdpr-compliance' ), $prefix . '[active]', $object->getActive(), [ 'border' => true ], false );
				?>
			</li>
		</ul>
		<?php AdminForm::renderField( 'hidden', _x( 'ID', 'admin', 'wp-gdpr-compliance' ), $prefix . '[id]', $id ); ?>
	</header>

	<section class="wpgdprc-form wpgdprc-form--edit-processor">
		<div class="grid-x grid-margin-x grid-margin-y">
			<div class="cell cell--main large-4">
				<div class="wpgdprc-form__field">
					<?php
					AdminForm::renderField(
						'text',
						_x( 'Title of processor', 'admin', 'wp-gdpr-compliance' ),
						$prefix . '[title]',
						$object->getTitle(),
						[
							'class'       => 'regular-text',
							'required'    => 'required',
							'placeholder' => _x( 'Title', 'admin', 'wp-gdpr-compliance' ),
							'description' => _x( 'Name your processor wisely, the name of the processor is shown in the popup of the processorbar. For example name it "Google Analytics" or "Advertisement"', 'admin', 'wp-gdpr-compliance' ),
						],
						false
					);
					?>
				</div>

				<div class="wpgdprc-form__field">
					<?php
					AdminForm::renderField(
						'textarea',
						_x( 'Description', 'admin', 'wp-gdpr-compliance' ),
						$prefix . '[description]',
						$object->getDescription(),
						[
							'class'       => 'regular-text',
							'placeholder' => _x( 'A short description of the processor', 'admin', 'wp-gdpr-compliance' ),
							/* translators: %1s: placeholder value */
							'description' => sprintf( _x( 'Describe your processor script as thoroughly as possible. %1s will not work here.', 'admin', 'wp-gdpr-compliance' ), PrivacyPolicy::REPLACER ),
						],
						false
					);
					?>
				</div>

				<div class="wpgdprc-form__field">
					<?php
					AdminForm::renderField(
						'select',
						_x( 'Code wrap', 'admin', 'wp-gdpr-compliance' ),
						$prefix . '[wrap]',
						$object->getWrap(),
						[
							'class'       => 'regular-text',
							'data-target' => $prefix . '_snippet',
							'choices'     => DataProcessor::listWrapChoices(),
						],
						false
					);
					?>
				</div>

				<div class="wpgdprc-form__field">
					<?php
					AdminForm::renderField(
						'select',
						_x( 'Placement', 'admin', 'wp-gdpr-compliance' ),
						$prefix . '[placement]',
						$object->getPlacement(),
						[
							'class'   => 'regular-text',
							'choices' => DataProcessor::listPlaceChoices(),
						],
						false
					);
					?>
				</div>
			</div>

			<div class="cell cell--aside large-8">
				<div class="wpgdprc-form__field wpgdprc-form__field--code">
					<?php
					AdminForm::renderField(
						'textarea',
						_x( 'Code snippet', 'admin', 'wp-gdpr-compliance' ),
						$prefix . '[snippet]',
						htmlspecialchars( $object->getSnippet(), ENT_QUOTES, get_option( 'blog_charset' ) ),
						[
							'class'        => 'wpgdprc-codemirror',
							'data-type'    => $object->getWrap() ? 'js' : 'html',
							'autocomplete' => 'false',
							'spellcheck'   => 'false',
						],
						false
					);
					?>
				</div>
				<div class="wpgdprc-form__field wpgdprc-form__field--submit">
					<?php submit_button( _x( 'Save processor', 'admin', 'wp-gdpr-compliance' ), 'wpgdprc-button', $prefix . '[submit][edit]', true, [ 'class' => 'wpgdprc-button' ] ); ?>
				</div>
			</div>
		</div>
	</section>
</form>
