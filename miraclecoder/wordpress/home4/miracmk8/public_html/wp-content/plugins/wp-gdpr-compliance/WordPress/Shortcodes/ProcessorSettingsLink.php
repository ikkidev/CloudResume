<?php
namespace WPGDPRC\WordPress\Shortcodes;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\WordPress\Plugin;

/**
 * Class ProcessorSettingsLink
 * @package WPGDPRC\WordPress\Shortcodes
 */
class ProcessorSettingsLink extends AbstractShortcode {

	const SHORTCODE = Plugin::PREFIX . '_processor_settings_link';

	/**
	 * ProcessorSettingsLink constructor
	 */
	public function __construct() {
		parent::__construct();

		// Add fallback for old shortcode
		$this->addShortcode( Plugin::PREFIX . '_consents_settings_link' );
	}

	/**
	 * Lists the default arguments
	 * @return array
	 */
	protected function defaultArgs(): array {
		return array_merge(
			parent::defaultArgs(),
			[
				'class' => '',
			]
		);
	}

	/**
	 * @param array  $args
	 * @param string $content
	 * @return string
	 */
	public function generateOutput( array $args = [], string $content = '' ): string {
		$output = '';

		if ( AdminHelper::userIsAdmin() ) {
			$output = Elements::getWarning( _x( 'You need to make sure you have added at least one (1) active data processor.', 'admin', 'wp-gdpr-compliance' ) );
		}
		if ( ! DataProcessor::isActive() ) {
			return $output;
		}

		$label      = ! empty( $content ) ? esc_html( $content ) : __( 'My settings', 'wp-gdpr-compliance' );
		$classes    = array_merge( explode( ',', $args['class'] ), [ Plugin::PREFIX . '-consents-settings-link' ] );
		$attributes = [
			'class'                   => implode( ' ', $classes ),
			'data-micromodal-trigger' => Plugin::PREFIX . '-consent-modal',
		];
		return apply_filters( Plugin::PREFIX . '_the_content', Elements::getButton( $label, $attributes, false ) );
	}

	/**
	 * Gets usage notice for shortcode (or css class)
	 * @param bool $wrap
	 * @param bool $warning
	 * @return string
	 */
	public static function getUsageNotice( bool $wrap = true, bool $warning = true ): string {
		$code       = strtolower( self::getShortcode() );
		$code_text  = sprintf( '<span class="wpgdprc-pre wpgdprc-pre--strong">[' . $code . ']<em>%1s</em>[/' . $code . ']</span>', __( 'My settings', 'wp-gdpr-compliance' ) );
		$class_text = '<span class="wpgdprc-pre wpgdprc-pre--strong">' . str_replace( '_', '-', $code ) . '</span>';

		/* translators: %1$1s: The shortcode %2$2s: The element class */
		$text = sprintf( __( 'Let your visitors re-access their settings by placing a link to the modal with the shortcode %1$1s or add the "%2$2s" class to a menu item.', 'wp-gdpr-compliance' ), $code_text, $class_text );
		return $warning ? Elements::getWarning( $text, $wrap ) : $text;
	}

}
