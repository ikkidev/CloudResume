<?php

use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;

/**
 * @var bool $enabled
 * @var bool $supported
 * @var bool $plugin
 * @var string $type
 */

if ( ! $plugin ) {
	return;
}

$class = 'wpgdprc-button wpgdprc-button--white-primary wpgdprc-button--small';

$enable_class = $class . ( empty( $enabled ) && ! empty( $supported ) ? '' : ' is-hidden' );
$manage_class = $class . ( empty( $enabled ) ? ' is-hidden' : '' );
$controls     = 'integration-item-container-' . $type;

?>

<?php
submit_button(
	_x( 'Activate', 'admin', 'wp-gdpr-compliance' ),
	$enable_class,
	PageSettings::SECTION_INTEGRATE . '[enable][' . $type . ']',
	false,
	[
		'data-enable' => '1',
		'data-type'   => $type,
	]
);
?>
<button class="<?php echo esc_attr( $manage_class ); ?>" data-action="manage" aria-expanded="false" aria-controls="<?php echo esc_attr( $controls ); ?>">
	<?php echo esc_html_x( 'Manage', 'admin', 'wp-gdpr-compliance' ); ?>
</button>
