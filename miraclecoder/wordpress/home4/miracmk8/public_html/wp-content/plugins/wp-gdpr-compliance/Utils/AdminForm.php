<?php

namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class AdminForm
 * @package WPGDPRC\Utils
 */
class AdminForm {

	/**
	 * Renders a form field row
	 *
	 * @param string $type
	 * @param string $label
	 * @param string $name
	 * @param string $value
	 * @param array $args
	 * @param bool $sr_only_label
	 */
	public static function renderField( $type = 'text', $label = '', $name = '', $value = '', $args = [], $sr_only_label = false, $info = '' ) {
		if ( $label === '' ) {
			$label = self::createLabel( $name );
		}

		if ( ! isset( $args['class'] ) ) {
			$args['class'] = 'regular-text';
		}
		if ( ! isset( $args['id'] ) ) {
			$args['id'] = sanitize_key( str_replace( '[', '_', $name ) );
		}
		if ( ! isset( $args['name'] ) ) {
			$args['name'] = $name;
		}
		if ( ! isset( $args['value'] ) ) {
			$args['value'] = $value;
		}

		$description = '';
		if ( isset( $args['description'] ) && $type !== 'message' ) {
			$description = $args['description'];
			unset( $args['description'] );
		}

		// @TODO Use for $hidden?
		$hidden = isset( $args['data-condition-target'] );

		switch ( $type ) {
			case 'checkbox':
			case 'number':
			case 'text':
			case 'url':
			case 'radio':
				$args['type'] = $type;

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderInput( $args );
                break;

            case 'hidden':
				$args['type'] = $type;

                self::renderInput( $args );
                break;

            case 'textarea':
				unset( $args['type'] );

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderTextarea($args );
                break;

            case 'select':
				unset( $args['type'] );

				self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderSelect($args );
                break;

            case 'multiselect':
				unset( $args['type'] );
				$args['multiple'] = 'multiple';

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderSelect($args );
                break;

            case 'pageselect':
				unset( $args['type'] );

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderPageSelect($args );
                break;

            case 'yesno':
				unset( $args['type'] );
				$args['choices'] = [
					'0' => _x( 'No', 'admin', 'wp-gdpr-compliance' ),
					'1' => _x( 'Yes', 'admin', 'wp-gdpr-compliance' ),
				];
				$args['class']   = 'small-text';

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderSelect($args );
                break;

            case 'enable':
				unset( $args['type'] );
				$args['choices'] = [
					'0' => _x( 'Disable', 'admin', 'wp-gdpr-compliance' ),
					'1' => _x( 'Enable', 'admin', 'wp-gdpr-compliance' ),
				];
				$args['class']   = 'small-text';

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderSelect($args );
                break;

            case 'colorpicker':
				$args['type']   = 'color';
				$args['class'] .= ' ' . Plugin::PREFIX . '-field__colorpicker';

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderInput($args );
                break;

            case 'truefalse':
				$args['type'] = 'checkbox';
				if ( empty( $args['label'] ) ) {
                    $args['label'] = $label;
				}

				if ( ! empty( $description ) ) {
					if ( isset( $args['description'] ) && is_string( $args['description'] ) ) {
						$args['description'] .= $description;
					} else {
						$args['description'] = $description;
					}
				}

                self::renderSwitch($args );
                break;

            case 'message':
				if ( ! isset( $args['description'] ) ) {
					$args['description'] = '';
				}
				if ( ! isset( $args['message'] ) ) {
					$args['message'] = $args['description'];
				}

                self::renderLabel($args['id'], $label, $sr_only_label, $info );
                self::renderDescription( $description );
                self::renderMessage($args );
                break;

            default:
				if ( is_user_logged_in() ) {
                    Template::render( 'Admin/Form/field-todo' );
				}
		}
	}

	/**
	 * Creates label (used when no label provided)
	 *
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected static function createLabel( $name = '' ) {
		return ucfirst( str_replace( [ '_', '-' ], ' ', $name ) );
	}

	/**
	 * Renders field label
	 *
	 * @param string $id
	 * @param string $text
	 * @param bool $sr_only
	 */
	protected static function renderLabel( $id = '', $text = '', $sr_only = false, $info = '' ) {
		return Template::render(
			'Admin/Form/label',
			[
				'id'      => $id,
				'text'    => $text,
				'sr_only' => $sr_only,
				'info'    => $info,
			]
		);
	}

	/**
	 * Renders field description
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	protected static function renderDescription( $text = '' ) {
		if ( empty( $text ) ) {
			return '';
		}

        Template::render(
			'Admin/Form/description',
			[
				'text' => $text,
			]
		);
	}

	/**
	 * Renders input field with attributes
	 *
	 * @param array $args
	 */
	protected static function renderInput( $args = [] ) {
		Template::render(
			'Admin/Form/field-input',
			[
				'id'    => $args['id'],
				'type'  => $args['type'],
				'name'  => $args['name'],
				'value' => $args['value'],
				'class' => $args['class'],
				'attr'  => $args,
			]
		);
	}

	/**
	 * Renders text message with attributes
	 *
	 * @param array $args
	 */
	protected static function renderMessage( $args = [] ) {
		$field = $args['message'];
		if ( ! isset( $args['value'] ) ) {
			return $field;
		}

		$args['type'] = 'hidden';
		unset( $args['description'] );
		unset( $args['message'] );

        echo esc_html( $field );
        self::renderInput($args );
	}

	/**
	 * Renders select field with attributes
	 *
	 * @param array $args
	 */
	protected static function renderSelect( $args = [] ) {
        $choices = $args['choices'] ?? [];
		if ( isset( $args['choices'] ) ) {
			unset($args['choices']);
		}

		$selected = '';
		if ( isset( $args['value'] ) ) {
			$selected = $args['value'];
			unset( $args['value'] );
		}

		if ( isset( $args['multiple'] ) && $args['multiple'] ) {
			$args['name'] .= '[]';
		}

        $name = '';
        if ( isset( $args['name'] ) ) {
            $name = $args['name'];
            unset( $args['name'] );
        }

        $id = '';
        if ( isset( $args['id'] ) ) {
            $id = $args['id'];
            unset( $args['id'] );
        }

        $class = '';
        if ( isset( $args['class'] ) ) {
            $class = $args['class'];
            unset( $args['class'] );
        }

        Template::render(
            'Admin/Form/field-select', [
                'args'     => $args,
                'choices'  => $choices,
                'selected' => $selected,
                'name'     => $name,
                'class'    => $class,
                'id'       => $id,
            ]
        );
	}

	/**
	 * @param             $selected
	 * @param bool|string $current
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function isSelected( $selected, $current = true ) {
		$result = '';
		if ( is_string( $selected ) ) {
			$result = selected( esc_attr( $selected ), esc_attr( $current ), false );

		} elseif ( is_array( $selected ) ) {
			if ( in_array( $current, $selected, true ) ) {
				$result = ' selected="selected"';
			}
		}

		return $result;
	}

	/**
	 * Renders page select field with attributes
	 *
	 * @param array $args
	 */
	protected static function renderPageSelect( $args = [] ) {
		$class = explode( ' ', $args['class'] );
		foreach ( [ 'regular-text', 'page-selector' ] as $string ) {
			if ( ! in_array( $string, $class, true ) ) {
				$class[] = $string;
			}
		}

        Template::render(
			'Admin/Form/field-pageselect',
			[
				'id'    => $args['id'],
				'name'  => $args['name'],
				'value' => $args['value'],
				'class' => implode( ' ', $class ),
				'args'  => $args,
			]
		);
	}

	/**
	 * Renders textarea field with attributes
	 *
	 * @param array $args
	 */
	protected static function renderTextarea( $args = [] ) {
        Template::render(
			'Admin/Form/field-textarea',
			[
				'id'    => $args['id'],
				'name'  => $args['name'],
				'value' => $args['value'],
				'class' => $args['class'],
				'attr'  => $args,
			]
		);
	}

	/**
	 * Renders switch field
	 *
	 * @param array $args
	 */
	protected static function renderSwitch( $args = [] ) {
        Template::render(
			'Admin/Form/field-switch',
			[
				'id'    => $args['id'],
				'type'  => $args['type'],
				'name'  => $args['name'],
				'value' => $args['value'],
				'class' => $args['class'],
				'args'  => $args,
				'data'  => self::buildAttributes(
					array_filter(
						$args,
						function ( $attr ) {
							return strpos( $attr, 'data-' ) !== false;
						},
						ARRAY_FILTER_USE_KEY
					)
				),
			]
		);
	}

	/**
	 * @param array $args
	 *
	 * @return string
	 */
	public static function buildAttributes( $args = [] ) {
		$list = [];
		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) ) {
				continue;
			}
			if ( in_array( $key, [ 'type', 'name', 'value', 'class', 'id', 'choices' ], true ) ) {
				continue;
			}
			if ( in_array( $key, [ 'checked', 'selected' ], true ) && ! boolval( $value ) ) {
				continue;
			}
			$list[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		return implode( ' ', $list );
	}

	/**
	 * Render a form setting field
	 *
	 * @param string $type
	 * @param string $label
	 * @param string $key
	 * @param array $args
	 * @param bool $sr_only
	 */
	public static function renderSettingField( $type = 'text', $label = '', $key = '', $args = [], $sr_only = false, $group = Settings::SETTINGS_GROUP ) {
        self::renderField($type, $label, Settings::getKey($key, $group ), Settings::get($key, $group ), $args, $sr_only );
	}

	/**
	 * Renders a form setting field (based on an array of data)
	 *
	 * @param array $data
	 */
	public static function renderSettingFieldFromArray( $data = [], $group = Settings::SETTINGS_GROUP ) {
		$type    = isset( $data['type'] ) ? $data['type'] : 'text';
		$label   = isset( $data['label'] ) ? $data['label'] : '';
		$key     = isset( $data['key'] ) ? $data['key'] : '';
		$args    = isset( $data['args'] ) ? $data['args'] : [];
		$sr_only = ! empty( $data['sr_only'] );

        self::renderSettingField( $type, $label, $key, $args, $sr_only );
	}

	/**
	 * Renders submit button
	 *
	 * @param string $group
	 * @param string $section
	 */
	public static function renderSubmitButton( $section = '', $group = Settings::SETTINGS_GROUP ) {
		$name = $group . '[submit]';
		if ( ! empty( $section ) ) {
			$name .= '[' . $section . ']';
		}

		submit_button( _x( 'Save settings', 'admin', 'wp-gdpr-compliance' ), 'wpgdprc-button', $name, false, [ 'class' => 'wpgdprc-button' ] );
	}

}
