<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\WordPress\Admin\Pages\PageWizard;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;

?>

<p><?php echo esc_html_x( 'We will significantly extend the features of this plugin to protect our community against the increasing pressure from Data Protection Agencies, giving hefty fines for non-compliant cookie consents. To enable business users to comply with GDPR, we will offer a business solution that easily allows you to become compliant and avoid fines.', 'admin', 'wp-gdpr-compliance' ); ?></p>
<br>
<p><?php echo esc_html_x( 'If you run a business within the EU, we strongly recommend changing to the business version to secure 100% compliance. Features include customizable consent pop-ups, automated cookie policies, integration to Google Products, and much more.', 'admin', 'wp-gdpr-compliance' ); ?></p>
<br>
<p><?php Elements::link( Config::premiumUrl(), _x( 'If you want to get a taste of what you can expect, you can try the full suite of Cookie Information for 30 days free', 'admin', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ] ); ?></p>
<br>
<p><?php echo esc_html( _x( 'It appears you have not yet completed the first time setup.', 'admin', 'wp-gdpr-compliance' ) ); ?></p>
<?php Elements::link( PageWizard::getPageUrl(), _x( "Let's get you all set up!", 'admin', 'wp-gdpr-compliance' ), [ 'class' => 'wpgdprc-button primary' ] ); ?>
