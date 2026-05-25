<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Settings;
use WPGDPRC\Utils\Wizard;

?>

<div class="wrap wpgdprc <?php echo esc_attr( sanitize_key( $_GET['page'] ?? '' ) ); ?>" data-mode="<?php echo Settings::isPremium() ? 'premium' : 'free'; ?>">
	<h1 class="wp-heading-inline screen-reader-text"><?php echo esc_html( Config::pluginName() ); ?></h1>

	<header class="wpgdprc-header">
		<?php Template::render( 'Admin/header' ); ?>
	</header>

	<hr class="wp-header-end">

	<?php if ( ! Wizard::isCompleted() ) : ?>
		<?php Wizard::renderNotice(); ?>
    <?php endif; ?>

	<main id="wpgdprc-main" class="wpgdprc-main">
