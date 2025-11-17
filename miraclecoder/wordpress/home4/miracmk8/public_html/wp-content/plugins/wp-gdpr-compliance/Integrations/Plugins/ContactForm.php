<?php

namespace WPGDPRC\Integrations\Plugins;

use WP_Post;
use WPGDPRC\Utils\Helper;
use WPGDPRC\WordPress\Plugin;

/**
 * Class ContactForm
 * @package WPGDPRC\Integrations\Plugins
 */
class ContactForm extends AbstractPlugin {

	public function getID(): string {
		return 'contact-form-7';
	}

	public function getVersion(): string {
		return '4.6';
	}

	public function getFile(): string {
		return 'contact-form-7/wp-contact-form-7.php';
	}

	/**
	 * Inits all integration actions & filters
	 */
	public function initHooks() {
		$this->initUpdateOption();

		add_action( 'wpcf7_init', [ $this, 'addFormTagSupport' ] );
		add_filter( 'wpcf7_before_send_mail', [ $this, 'updateMailBody' ], 999 );
		add_filter( 'wpcf7_validate_wpgdprc', [ $this, 'validateField' ], 10, 2 );
		add_filter( 'wp_after_insert_post', [ $this, 'afterSaveForm' ], PHP_INT_MAX, 2 );
	}

	/**
	 * @return bool
	 */
	public function hasData(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasForms(): bool {
		return true;
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getData( string $email ): array {
		return [];
	}

	/**
	 * @param bool $front
	 * @param string $search
	 *
	 * @return array
	 */
	public function getResults( bool $front, string $search ): array {
		return [];
	}


	/**
	 * Updates forms upon plugin activation
	 */
	public function onPluginActivation() {
		if ( ! $this->isEnabled() ) {
			return;
		}

		foreach ( $this->getList() as $form_id => $title ) {
			$this->addFormTag( $form_id );
			$this->addAcceptedDate( $form_id );
		}
	}

	/**
	 * Updates forms upon plugin deactivation
	 */
	public function onPluginDeactivation() {
		if ( ! $this->isEnabled() ) {
			return;
		}

		foreach ( $this->getList() as $form_id => $title ) {
			$this->removeFormTag( $form_id );
			$this->removeAcceptedDate( $form_id );
		}
	}

	/**
	 * Returns integration icon
	 * @return string
	 */
	public function getIcon(): string {
		return 'icon-wordpress.svg'; // @TODO : Update to proper icon
	}

	/**
	 * @return bool
	 */
	public function getSelectForm(): bool {
		return true;
	}

	/**
	 * Returns integration name
	 *
	 * @param bool $front
	 *
	 * @return string
	 */
	public function getName( bool $front = false ): string {
		if ( $front ) {
			return __( 'Forms Entries', 'wp-gdpr-compliance' );
		}

		return _x( 'Contact Form 7', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * Gets the description to show at the Integration form
	 * @return string
	 */
	public function getDescription(): string {
		if ( ! $this->isInstalled() ) {
			return $this->notInstalledText();
		}
		if ( ! $this->isActivated() ) {
			return $this->notActivatedText();
		}
		if ( ! $this->isSupported() ) {
			return $this->notSupportedText();
		}

		// additional check to see if there are any forms
		if ( empty( $this->getList() ) ) {
			return $this->noFormsText();
		}

		return _x( 'When activated the GDPR checkbox will be added at the end of each <strong>activated</strong> form.', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * Returns text for Anonymize button on the front
	 *
	 * @param int $plural
	 *
	 * @return string
	 */
	public function getButtonText( int $plural = 1 ): string {
		return _nx( 'Anonymize selected entry', 'Anonymize selected entry/entries', $plural, 'amount of entries', 'wp-gdpr-compliance' );
	}

	/**
	 * Lists all Contact Form 7 forms (draft & published)
	 * Returns array with 'form ID' => 'form title (status)'
	 * @return array
	 */
	public function getList(): array {
		$list  = [];
		$posts = get_posts(
			[
				'post_type'   => 'wpcf7_contact_form',
				'numberposts' => - 1,
				'post_status' => [ 'publish', 'draft' ],
			]
		);
		if ( empty( $posts ) ) {
			return $list;
		}

		foreach ( $posts as $form ) {
			$status_text = $form->post_status !== 'publish' ? _x( 'draft', 'admin', 'wp-gdpr-compliance' ) : _x( 'published', 'admin', 'wp-gdpr-compliance' );

			$list[ $form->ID ] = sprintf( '%1s (%2s)', $form->post_title, $status_text );
		}

		ksort( $list );

		return $list;
	}

	/**
	 * Inserts consent checkbox at the end of the (enabled) forms
	 */
	public function updateFormField() {
		$enabled = $this->isEnabled();

		foreach ( $this->getList() as $form_id => $title ) {
			$this->removeFormTag( $form_id );
			$this->removeAcceptedDate( $form_id );
			if ( ! $enabled ) {
				continue;
			}

			if ( $this->isEnabledForm( $form_id ) ) {
				$this->addFormTag( $form_id );
				$this->addAcceptedDate( $form_id );
			}
		}
	}

	/**
	 * Removes [wpgdprc] string from specific form
	 *
	 * @param int $form_id
	 */
	public function removeFormTag( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}

		$meta_key = '_form';
		$value    = get_post_meta( $form_id, $meta_key, true );
		if ( empty( $value ) ) {
			return;
		}

		$result = Helper::removePattern( $value, '/(\n\n\[' . $this->getFieldTag() . '?.*\])/' );
		if ( empty( $result ) ) {
			return;
		}

		update_post_meta( $form_id, $meta_key, $result );
	}

	/**
	 * Removes [wpgdprc] string from specific form mail
	 *
	 * @param int $form_id
	 */
	public function removeAcceptedDate( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}

		$meta_key = '_mail';
		$value    = get_post_meta( $form_id, $meta_key, true );
		if ( empty( $value ) ) {
			return;
		}

		$result = Helper::removePattern( $value['body'], '/(\n\n\[' . $this->getFieldTag() . '\])/' );
		if ( empty( $result ) ) {
			return;
		}

		$value['body'] = $result;
		update_post_meta( $form_id, $meta_key, $value );
	}

	/**
	 * Adds [wpgdprc] string to specific form
	 *
	 * @param int $form_id
	 */
	public function addFormTag( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}
		if ( ! $this->isEnabledForm( $form_id ) ) {
			return;
		}

		$meta_key = '_form';
		$value    = get_post_meta( $form_id, $meta_key, true );
		if ( empty( $value ) ) {
			return;
		}

		$field_tag = '[' . $this->getFieldTag() . ' "' . $this->getCheckboxTextByForm( $form_id ) . '"]';
		$pattern   = '/(\[' . $this->getFieldTag() . '?.*\])/';
		preg_match( $pattern, $value, $matches );
		if ( ! empty( $matches ) ) {
			$value = str_replace( $matches[0], $field_tag, $value );
			update_post_meta( $form_id, $meta_key, $value );

			return;
		}

		$pattern = '/(\[submit?.*\])/';
		preg_match( $pattern, $value, $matches );
		if ( empty( $matches ) ) {
			$value .= "\n\n" . $field_tag;
		} else {
			$value = preg_replace( $pattern, $field_tag . "\n\n" . $matches[0], $value );
		}
		update_post_meta( $form_id, $meta_key, $value );
	}

	/**
	 * Adds [wpgdprc] string to specific form
	 *
	 * @param int $form_id
	 */
	public function addAcceptedDate( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}
		if ( ! $this->isEnabledForm( $form_id ) ) {
			return;
		}

		$meta_key = '_mail';
		$value    = get_post_meta( $form_id, $meta_key, true );
		if ( empty( $value ) || empty( $value['body'] ) ) {
			return;
		}

		$pattern = '/(\[' . $this->getFieldTag() . '\])/';
		preg_match( $pattern, $value['body'], $matches );
		if ( empty( $matches ) ) {
			return;
		}

		$field_tag = $this->getFieldTag( true );
		$pattern   = '/(--)/';
		preg_match( $pattern, $value['body'], $matches );
		if ( empty( $matches ) ) {
			$value['body'] .= "\n\n" . $field_tag;
		} else {
			$value['body'] = preg_replace( $pattern, $field_tag . "\n\n" . $matches[0], $value['body'] );
		}
		update_post_meta( $form_id, $meta_key, $value );
	}

	public function addFormTagSupport() {
		if ( ! function_exists( 'wpcf7_add_form_tag' ) ) {
			return;
		}
		wpcf7_add_form_tag( $this->getFieldTag(), [ $this, 'addFormTagHandler' ] );
	}

	/**
	 * @param mixed $tag (array|WPCF7_FormTag)
	 *
	 * @return string
	 */
	public function addFormTagHandler( $tag = [] ): string {
		$tag = $this->validateFormTag( $tag );
		if ( empty( $tag ) ) {
			return '';
		}

		$tag->name   = $this->getFieldTag();
		$first_label = reset( $tag->labels );
		$label       = ! empty( $first_label ) ? esc_html( $first_label ) : self::getCheckboxTextByForm();

		$class     = [ wpcf7_form_controls_class( $tag->type, 'wpcf7-validates-as-required' ) ];
		$has_error = wpcf7_get_validation_error( $tag->name );
		if ( $has_error ) {
			$class[] = 'wpcf7-not-valid';
		}

		$field_atts = [
			'type'          => 'checkbox',
			'name'          => $tag->name,
			'value'         => 1,
			'tabindex'      => $tag->get_option( 'tabindex', 'signed_int', true ),
			'aria-required' => 'true',
			'aria-invalid'  => $has_error ? 'true' : 'false',
		];

		$field = [ '<input %2$s />', '<span class="wpcf7-list-item-label">%1$s</span>' ];
		if ( $tag->has_option( 'label_first' ) ) {
			$field = array_reverse( $field );
		}
		$output = sprintf( implode( '', $field ), esc_html( $label ), wpcf7_format_atts( $field_atts ) );

		if ( $tag->has_option( 'use_label_element' ) ) {
			$output = '<label>' . $output . '</label>';
		}

		$wrapper_atts = [
			'class' => $tag->get_class_option( implode( ' ', $class ) ),
			'id'    => $tag->get_id_option(),
		];

		return sprintf(
			'<span class="wpcf7-form-control-wrap %1s"><span %2s>%3s</span>%4s</span>',
			sanitize_html_class( $tag->name ),
			wpcf7_format_atts( $wrapper_atts ),
			'<span class="wpcf7-list-item">' . $output . '</span>',
			$has_error
		);
	}

	/**
	 * @param mixed $form (WPCF7_ContactForm)
	 *
	 * @return mixed (WPCF7_ContactForm)
	 */
	public function updateMailBody( $form ) {
		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return $form;
		}
		if ( ! $form instanceof \WPCF7_ContactForm ) {
			return $form;
		}

		$mail = $form->prop( 'mail' );
		if ( empty( $mail['body'] ) ) {
			return $form;
		}
		if ( ! class_exists( 'WPCF7_Submission' ) ) {
			return $form;
		}

		$entry = \WPCF7_Submission::get_instance();
		if ( empty( $entry ) ) {
			return $form;
		}

		$data  = $entry->get_posted_data();
		$value = $this->getAcceptedDate( isset( $data[ $this->getFieldTag() ] ) && $data[ $this->getFieldTag() ] === '1' );
		/* translators: %1s: Date */
		$text   = sprintf( __( "GDPR accepted on:\n%1s", 'wp-gdpr-compliance' ), $value );
		$output = apply_filters( Plugin::PREFIX . '_cf7_mail_body_output', $text, $data, $entry );

		$mail['body'] = str_replace( $this->getFieldTag( true ), $output, $mail['body'] );
		$form->set_properties( [ 'mail' => $mail ] );

		return $form;
	}

	/**
	 * @param mixed $result
	 * @param mixed $tag (array|WPCF7_FormTag)
	 *
	 * @return mixed (WPCF7_Validation)
	 */
	public function validateField( $result, $tag = [] ) {
		if ( ! class_exists( 'WPCF7_Validation' ) ) {
			return $result;
		}
		if ( ! $result instanceof \WPCF7_Validation ) {
			return $result;
		}

		$tag = $this->validateFormTag( $tag );
		if ( empty( $tag ) ) {
			return $result;
		}

		$value = false;
		if ( ! empty( $_POST[ $this->getFieldTag() ] ) ) {
			$value = filter_var( wp_unslash( $_POST[ $this->getFieldTag() ] ), FILTER_VALIDATE_BOOLEAN );
		}
		if ( ! empty( $value ) ) {
			return $result;
		}

		$key     = '_wpcf7';
		$form_id = ! empty( $_POST[ $key ] ) && is_numeric( $_POST[ $key ] ) ? (int) $_POST[ $key ] : 0;
		if ( empty( $form_id ) ) {
			return $result;
		}

		$tag->name = $this->getFieldTag();
		$result->invalidate( $tag, self::getErrorTextByForm( $form_id ) );

		return $result;
	}

	/**
	 * Validates form tag (and checks if it is the WPGDRPC form tag)
	 *
	 * @param mixed $tag (array|WPCF7_FormTag)
	 *
	 * @return mixed (false|WPCF7_FormTag)
	 */
	public function validateFormTag( $tag = [] ) {
		if ( ! class_exists( 'WPCF7_FormTag' ) ) {
			return false;
		}

		$tag = is_array( $tag ) ? new \WPCF7_FormTag( $tag ) : $tag;
		if ( $tag->type !== $this->getFieldTag() ) {
			return false;
		}

		return $tag;
	}

	/**
	 * Allows for wrapping the field tag (like in the CF form & email)
	 *
	 * @param bool $wrapped
	 *
	 * @return string
	 */
	public function getFieldTag( bool $wrapped = false ): string {
		$tag = parent::getFieldTag();

		return $wrapped ? '[' . $tag . ']' : $tag;
	}

	/**
	 * Add form tag back when it has been removed manually
	 *
	 * @param int $id
	 * @param WP_Post $post
	 */
	public function afterSaveForm( int $id, WP_Post $post ) {
		if ( $post->post_type !== 'wpcf7_contact_form' ) {
			return;
		}
		if ( ! $this->isEnabledForm( $id ) ) {
			return;
		}

		add_action(
			'shutdown',
			function () use ( $id ) {
				$present = preg_match( '/\[wpgdprc .*]/', get_post_meta( $id, '_form', true ) );
				if ( $present ) {
					return;
				}
				$this->addFormTag( $id );
			}
		);
	}
}
