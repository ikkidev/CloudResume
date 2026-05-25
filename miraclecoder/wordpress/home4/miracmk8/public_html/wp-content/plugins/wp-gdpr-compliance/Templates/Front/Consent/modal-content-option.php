<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Template;

/**
 * @var DataProcessor $item
 * @var array         $consents
 * @var bool          $border
 */

$id    = $item->getId();
$class = $border ? 'wpgdprc-switch--border' : '';

?>

<div class="wpgdprc-checkbox">
	<label class="wpgdprc-switch wpgdprc-switch--column <?php echo esc_attr( $class ); ?>" for="<?php echo esc_attr( $id ); ?>">
		<span class="wpgdprc-switch__text"><?php esc_html_e( 'Enable?', 'wp-gdpr-compliance' ); ?></span>
		<span class="wpgdprc-switch__switch">
			<input class="wpgdprc-switch__input" type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $id ); ?>" <?php checked( true, in_array( $id, $consents, true ) ); ?> />
			<span class="wpgdprc-switch__slider round">
				<?php Template::renderIcon( 'check', 'fontawesome-pro-regular' ); ?>
				<?php Template::renderIcon( 'times', 'fontawesome-pro-regular' ); ?>
			</span>
		</span>
	</label>
</div>
