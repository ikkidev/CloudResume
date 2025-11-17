<?php
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
/**
 * This template renders a coming soon page when the coming soon feature is active.
 */

$svg_kses = array(
	'svg'  => array(
		'class'        => true,
		'fill'         => true,
		'height'       => true,
		'stroke'       => true,
		'stroke-width' => true,
		'viewbox'      => true,
		'width'        => true,
		'xmlns'        => true,
	),
	'g'    => array(
		'fill'              => true,
		'stroke'            => true,
		'stroke-miterlimit' => true,
		'stroke-width'      => true,
	),
	'rect' => array(
		'fill'      => true,
		'height'    => true,
		'rx'        => true,
		'transform' => true,
		'width'     => true,
		'x'         => true,
		'y'         => true,
	),
	'text' => array(
		'fill'        => true,
		'font-family' => true,
		'font-size'   => true,
		'font-weight' => true,
		'title'       => true,
		'transform'   => true,
	),
	'path' => array(
		'd'               => true,
		'fill'            => true,
		'opacity'         => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'transform'       => true,

	),
);

$kses_post_inc_svg = array_merge( wp_kses_allowed_html( 'post' ), $svg_kses );
$login_icon        = '<svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"></path></svg>';
$email_icon        = '<svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"></path></svg>';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<meta name="robots" content="index, follow" />
		<title><?php echo esc_html( $args['template_page_title'] ); ?></title>
		<?php if ( ! empty( $args['template_p'] ) ) : ?>
			<meta name="description" content="<?php echo esc_attr( $args['template_p'] ); ?>" />
		<?php endif; ?>
		<script src="<?php echo esc_url( includes_url( 'js/jquery/jquery.js' ) ); //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>"></script>
		<style type="text/css"><?php echo file_get_contents( 'default.css', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
		<link rel="canonical" href="<?php echo esc_url( site_url() ); ?>">
		<?php if ( isset( $args['template_styles'] ) ) : ?>
			<link rel="stylesheet" href="<?php echo esc_url( $args['template_styles'] ); //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>" />
		<?php endif; ?>

		<!-- Structured data JSON-LD -->
		<script type="application/ld+json">
			{
				"@context": "https://schema.org",
				"@type": "WebSite",
				"url": "<?php echo esc_url( site_url() ); ?>",
				"name": "<?php echo esc_html( $args['template_page_title'] ); ?>",
				"description": "<?php echo esc_html( $args['template_p'] ); ?>"
			}
		</script>

		<!-- Open Graph -->
		<meta property="og:title" content="<?php echo esc_html( $args['template_page_title'] ); ?>">
		<meta property="og:description" content="<?php echo esc_html( $args['template_p'] ); ?>">
		<meta property="og:url" content="<?php echo esc_url( site_url() ); ?>">
		<meta property="og:type" content="website">

		<!-- Twitter Card -->
		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="<?php echo esc_html( $args['template_page_title'] ); ?>">
		<meta name="twitter:description" content="<?php echo esc_html( $args['template_p'] ); ?>">

	</head>
	<body>
	<header>
		<div class="login-prompt">
			<span class="login-label">
				<?php esc_html_e( 'Is this your website?', 'wp-module-coming-soon' ); ?>
			</span>
			<a href="<?php echo esc_url( wp_login_url() ); ?>" class="login-link" aria-label="<?php esc_html_e( 'Login to WordPress', 'wp-module-coming-soon' ); ?>>">
				<span class="nfd-login-icon">
					<?php echo wp_kses( $login_icon, $svg_kses ); ?>
				</span>
				<?php esc_html_e( 'Login to WordPress', 'wp-module-coming-soon' ); ?>
			</a>
		</div>
	</header>
		<div id="wrap">
			<main class="content">
				<?php if ( isset( $args['template_content'] ) && is_readable( $args['template_content'] ) ) : // check for custom template_constant. ?>
					<?php load_template( $args['template_content'], true, $args ); ?>
				<?php else : // no template provided use default. ?>
				<div class="subscription_widget">
					<span><?php echo wp_kses( $args['template_site_logo'], $kses_post_inc_svg ); ?></span>
					<h1><?php echo wp_kses_post( $args['template_h1'] ); ?></h1>
					<?php if ( ! empty( $args['template_desc'] ) ) : ?>
						<p class="comingsoon-description"><?php echo wp_kses_post( $args['template_desc'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $args['template_coming_soon_links'] ) ) : ?>
						<p class="comingsoon_links"><?php echo wp_kses_post( $args['template_coming_soon_links'] ); ?></p>
					<?php endif; ?>
					<?php if ( isset( $args['template_login_btn'] ) && true === $args['template_login_btn'] ) : // admin login button. ?>
						<a class="btn" href="<?php echo esc_url( wp_login_url() ); ?>">
							<?php esc_html_e( 'Admin Login', 'wp-module-coming-soon' ); ?>
						</a>
					<?php endif; // end admin login button. ?>
					<?php if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'subscriptions' ) ) : ?>
						<div id="subscribe-text">
							<p><?php echo wp_kses_post( $args['template_p'] ); ?></p>
						</div>
						<form action="" method="post" accept-charset="utf-8" id="subscribe">
							<input type="hidden" name="action" value="newfold_coming_soon_subscribe">
							<?php wp_nonce_field( 'newfold_coming_soon_subscribe_nonce', 'newfold-nonce-coming-soon-subscribe' ); ?>
							<?php if ( ! empty( $args['template_email_label'] ) ) : ?>
								<label id="subscribe-label" for="subscribe-field">
									<?php echo esc_html( $args['template_email_label'] ); ?>
								</label>
							<?php endif; ?>
							<span class="inputs email" id="subscribe-email">
								<span class="subscription-field-wrapper">
									<?php echo wp_kses( $email_icon, $svg_kses ); ?>
									<input
										type="email"
										name="email"
										required="required"
										value=""
										id="subscribe-field"
										placeholder="<?php echo esc_attr( $args['template_email_ph'] ); ?>"
									>
								</span>
							<span class="inputs submit" id="subscribe-submit">
								<input class="btn" type="submit" value="<?php echo esc_attr( $args['template_subscribe'] ); ?>" name="subscriptions_widget">
							</span>
							</span>
							<div id="success" class="status-message">
								<?php echo wp_kses_post( $args['template_msg_success'] ); ?>
							</div>
							<div id="error-active" class="status-message">
								<?php echo wp_kses_post( $args['template_msg_active'] ); ?>
							</div>
							<div id="error-invalid" class="status-message">
								<?php echo wp_kses_post( $args['template_msg_invalid'] ); ?>
							</div>
						</form>
						<script>
							var ajaxscript = {
								ajax_url: '<?php echo esc_url( admin_url() ); ?>admin-ajax.php'
							};
							<?php echo file_get_contents( 'script.js', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</script>
					<?php endif; // end subscribe form. ?>
				</div>
				<?php endif; // end template. ?>
			</main>
		</div>
		<footer>
			<?php if ( ! empty( $args['template_coming_soon_backlinks'] ) ) : ?>
				<p class="comingsoon-backlinks"><?php echo wp_kses( $args['template_coming_soon_backlinks'], $kses_post_inc_svg ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $args['template_footer_t'] ) ) : ?>
			<p class="text-center coming-soon-footer-banner">
				<?php echo wp_kses_post( $args['template_footer_t'] ); ?>
			</p>
			<?php endif; ?>
		</footer>
	</body>
</html>
