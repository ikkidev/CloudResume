<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\Objects\DataProcessor;

?>

	<!DOCTYPE html>
	<!--[if IE 9]>
	<html class="ie9" <?php language_attributes(); ?> >
	<![endif]-->
	<!--[if !(IE 9) ]><!-->
	<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo esc_attr_x( 'First time setup wizard - Cookie Information | Free WP GDPR Consent Plugin', 'admin', 'wp-gdpr-compliance' ); ?></title>
		<!-- Stop the flash of un styled content by hiding the body until the main style is loaded in (which also shows the body again styled and all.) -->
		<style>body {
				opacity: 0;
				visibility: hidden;
				background: #F1F1F1;
				transition: opacity .4s ease-in;
			}</style>
		<?php wp_print_head_scripts(); ?>
	</head>
	<body class="wp-admin wp-core-ui wpgdprc fts">
	<div id="wp-gdpr-fts">
		<main class="wpgdprc-main grid-x">
			<div class="wpgdprc-container cell small-12">
				<div id="step-container">

					<?php
						Template::render( 'Admin/Pages/Wizard/Steps/welcome' );
						Template::render( 'Admin/Pages/Wizard/Steps/ci' );
						DataProcessor::getTotal() < 1 ? Template::render( 'Admin/Pages/Wizard/Steps/consent' ) : '';
						Template::render( 'Admin/Pages/Wizard/Steps/privacy' );
						Template::render( 'Admin/Pages/Wizard/Steps/done' );
					?>

					<div class="step-container__footer flex-container align-justify">
						<button class="button wpgdprc-button wpgdprc-button--white" data-step="prev">
							<?php Template::renderIcon( 'arrow-left', 'fontawesome-pro-regular' ); ?>
							<?php echo esc_html_x( 'Back', 'admin', 'wp-gdpr-compliance' ); ?>
						</button>

						<button class="button wpgdprc-button" data-step="next">
							<span class="spinner hide">
								<span class="spinner__spin" role="status" aria-hidden="true">
									<span class="show-for-sr">Loading...</span>
								</span>
							</span>
							<?php echo esc_html_x( 'Next', 'admin', 'wp-gdpr-compliance' ); ?>
						</button>

						<a data-step="done" href="<?php echo esc_url( Wizard::getFinishLink() ); ?>" class="button primary wpgdprc-button hide">
							<?php echo esc_html_x( 'Finish', 'admin', 'wp-gdpr-compliance' ); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="small-12 margin-right-2">
				<?php Template::render( 'Admin/Elements/logo' ); ?>

				<div class="wizard--bar">
					<div class="wizard--bar--done"></div>
				</div>

				<div class="flex-container align-spaced" id="step-to-buttons">
					<!-- gets filled by js. -->
				</div>
			</div>

		</main>

		<footer class="wp-gdpr-fts__footer">
			<a href="<?php echo esc_url( Wizard::getFinishLink() ); ?>">
				<?php echo esc_html( _x( 'Skip for now', 'admin', 'wp-gdpr-compliance' ) ); ?>
			</a>
		</footer>

	</div>
	<?php wp_print_footer_scripts(); ?>
	</body>
	</html>
<?php
