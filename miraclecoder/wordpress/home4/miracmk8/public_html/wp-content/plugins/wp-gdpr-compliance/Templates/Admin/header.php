<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;
use WPGDPRC\Utils\Banner;

?>

<div class="wpgdprc-header__left">
	<div class="wpgdprc-header__title">
		<?php Template::render( 'Admin/Elements/logo' ); ?>
	</div>
	<p class="wpgdprc-header__message">
		<?php echo wp_kses_post( Banner::getStatusAndPremiumBanner() );  ?>
		<?php
		Elements::link(
			Config::premiumUrl(),
			_x( 'Sign up for a 30-day free trial', 'admin', 'wp-gdpr-compliance' ),
			[
				'target' => '_blank',
				'class'  => 'wpgdprc-header__link--premium wpgdprc-sign-up-button',
			]
		);
		?>
	</p>
</div>
<div class="wpgdprc-header__right">
	<nav class="wpgdprc-header__nav" aria-labelledby="header-nav">
		<h2 id="header-nav"
			class="screen-reader-text"><?php echo esc_html( _x( 'Plugin help', 'admin', 'wp-gdpr-compliance' ) ); ?></h2>
		<ul>
			<li>
				<?php Elements::link( Config::aboutUrl(), _x( 'About', 'admin', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ] ); ?>
			</li>
			<li>
				<?php Elements::link( Config::supportUrl(), _x( 'Support', 'admin', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ] ); ?>
			</li>
		</ul>
	</nav>
</div>
