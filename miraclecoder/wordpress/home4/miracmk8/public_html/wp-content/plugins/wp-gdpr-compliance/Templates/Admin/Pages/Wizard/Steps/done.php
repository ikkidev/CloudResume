<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;

?>

<div data-title="<?php echo esc_attr_x( 'Finish', 'admin', 'wp-gdpr-compliance' ); ?>" class="step">
	<h2 class="h2 step__title">
		<?php echo esc_html_x( 'Finish', 'admin', 'wp-gdpr-compliance' ); ?>
	</h2>
	<p><?php echo esc_html_x( 'Good job! Your cookie pop-up is now live. 🎉', 'admin', 'wp-gdpr-compliance' ); ?></p>
	<p><?php echo esc_html_x( 'Did you know that you can enrich your pop-up even more with your free plugin?', 'admin', 'wp-gdpr-compliance' ); ?></p>

	<ul class="no-bullet margin-0 margin-top-1">
		<li class="margin-bottom-1">
			<?php Elements::link( Wizard::getFinishLink( PageSettings::getSectionUrl( PageSettings::SECTION_CONSENT ) ), _x( 'Customize your pop-up', 'admin', 'wp-gdpr-compliance' ) ); ?>
		</li>
		<li class="margin-bottom-1">
			<?php Elements::link( Wizard::getFinishLink( PageSettings::getSectionUrl( PageSettings::SECTION_PRIVACY ) ), _x( 'Manage your Privacy Policy page', 'admin', 'wp-gdpr-compliance' ) ); ?>
		</li>
		<li>
			<?php Elements::link( Wizard::getFinishLink( PageDashboard::getTabUrl( PageDashboard::TAB_PROCESSORS ) ), _x( 'Manually add data processors (e.g. Google Analytics, Facebook, etc.)', 'admin', 'wp-gdpr-compliance' ) ); ?>
		</li>
	</ul>
</div>
