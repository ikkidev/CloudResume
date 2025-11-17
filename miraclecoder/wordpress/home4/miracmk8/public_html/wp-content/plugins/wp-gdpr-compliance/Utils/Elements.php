<?php
namespace WPGDPRC\Utils;

/**
 * Class Elements
 * @package WPGDPRC\Utils
 */
class Elements {

    /**
     * Builds a button element (with attributes)
     * @param string $text
     * @param array $attr
     * @param bool $admin
     * @return string|void
     */
	public static function button( string $text = '', array $attr = [], bool $admin = true ) {
		if ( empty( $text ) || ! is_string( $text ) ) {
			return;
		}

        Template::render(
			$admin ? 'Admin/Elements/button' : 'Front/Elements/button',
			[
				'text' => $text,
				'attr' => $attr,
			]
		);
	}

    /**
     * Prints a button element (with attributes)
     * @param string $text
     * @param array $attr
     * @param bool $admin
     * @return false|string
     */
	public static function getButton( string $text = '', array $attr = [], bool $admin = true ) {
        ob_start();
        self::button( $text, $attr, $admin );
        return ob_get_clean();
	}

	/**
	 * Builds a warning
	 * @param string $error
	 * @param bool   $wrap
	 */
	public static function error( string $error = '', bool $wrap = true ) {
		if ( empty( $error ) ) {
			return;
		}

		 ?>
            <?php if($wrap) : ?>
                <p>
            <?php endif; ?>

                <?php /* translators: %s: error message */ ?>
                <span class="wpgdprc-text--error"><?php echo wp_kses_post(sprintf( _x( '<strong>ERROR</strong>: %1$s', 'admin', 'wp-gdpr-compliance' ), $error ) ) ?> </span>

            <?php if($wrap) : ?>
                </p>
            <?php endif; ?>
        <?php
	}

	/**
	 * Renders a post edit link element (with attributes)
	 * @param int    $id
	 * @param string $text
	 * @param array  $attr
	 * @return string
	 */
	public static function editLink( int $id = 0, string $text = '', array $attr = [] ): string {
		$url = get_edit_post_link( (int) $id );
		if ( empty( $url ) ) {
			return '';
		}

		return self::getLink( htmlspecialchars_decode( $url ), $text, $attr );
	}

	/**
	 * Renders a heading element (with attributes)
	 * @param string $text
	 * @param int    $level
	 * @param array  $attr
	 */
	public static function heading( string $text = '', int $level = 2, array $attr = [] ) {
		if ( empty( $text ) || ! is_string( $text ) ) {
			return;
		}

        Template::render(
            'Admin/Elements/heading',
            [
                'text' => $text,
                'level' => $level,
                'attr' => $attr,
            ]
        );
	}

    /**
     * Builds a link element (with attributes)
     * @param string $url
     * @param string $text
     * @param array $attr
     * @param bool $noIcon
     * @return string
     */
	public static function getLink( string $url = '', string $text = '', array $attr = [], bool $noIcon = false ): string {
		if ( empty( $url ) || ! is_string( $url ) ) {
			return '';
		}
		if ( empty( $text ) || ! is_string( $text ) ) {
			$text = Helper::stripUrl( $url );
		}

		// trim removes the space after links which gets added because php storm always forces a linebreak at the end of an file.
		return trim(
			Template::get(
				'Admin/Elements/link',
				[
					'url'  => $url,
					'text' => $text,
					'attr' => $attr,
					'icon' => ! $noIcon,
				]
			)
		);
	}

	/**
	 * Prints a link element (with attributes)
	 * @param string $url
	 * @param string $text
	 * @param array  $attr
	 */
	public static function link( string $url = '', string $text = '', array $attr = [], $noIcon = false ) {
        echo wp_kses( self::getLink( $url, $text, $attr, $noIcon ), AdminHelper::getAllAllowedSvgTags());
	}

	/**
	 * Builds a warning
	 * @param string $warning
	 * @param bool   $wrap
	 * @return string
	 */
	public static function getWarning( string $warning = '', bool $wrap = true ): string {
		if ( empty( $warning ) ) {
			return '';
		}

		/* translators: %s: warning message */
		$html = '<span class="wpgdprc-text--warning">' . sprintf( _x( '<strong>NOTE:</strong> %1$s', 'admin', 'wp-gdpr-compliance' ), $warning ) . '</span>';
		return $wrap ? '<p>' . $html . '</p>' : $html;
	}

	/**
	 * Prints a warning
	 * @param string $warning
	 * @param bool   $wrap
	 */
	public static function warning( string $warning = '', bool $wrap = true ) {
        echo wp_kses( self::getWarning( $warning, $wrap ), AdminHelper::getAllAllowedSvgTags());
	}

	/**
	 * Builds a notice
	 * @param string $title
	 * @param string $text
	 * @param string $button
	 * @param string $type
	 * @return string
	 */
	public static function notice( string $title = '', string $text = '', string $button = '', string $type = 'notice' ) {
		$icon = 'icon-info-circle.svg';
		switch ( $type ) {
			case 'error':
				$icon = 'icon-times-circle.svg';
				break;

			case 'warning':
				$icon = 'icon-exclamation-triangle.svg';
				break;

			case 'wizard':
				$icon = 'icon-wave.svg';
				break;

            case 'upgrade':
                $icon = 'icon-sparkles.svg';
		}

        Template::render(
			'Admin/Elements/notice-fancy',
			[
				'type'   => $type,
				'icon'   => Template::getSvg( $icon, true ),
				'title'  => $title,
				'text'   => $text,
				'button' => $button,
			]
		);
	}
}
