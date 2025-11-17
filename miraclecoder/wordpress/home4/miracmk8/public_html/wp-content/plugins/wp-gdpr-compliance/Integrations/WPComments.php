<?php

namespace WPGDPRC\Integrations;

use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

/**
 * Class WPComments
 * @package WPGDPRC\Integrations
 */
class WPComments extends AbstractIntegration {

	/**
	 * @return string
	 */
	public function getID(): string {
		return 'WordPress';
	}

	/**
	 * @return string
	 */
	public function getVersion(): string {
		return 'core WordPress';
	}

	/**
	 * Inits all integration actions & filters
	 */
	public function initHooks() {
		add_action( 'init', [ $this, 'initFormFilters' ] );
		add_action( 'pre_comment_on_post', [ $this, 'checkPost' ] );
		add_action( 'comment_post', [ $this, 'addAcceptedDateToMeta' ] );
		add_action( 'manage_comments_custom_column', [ $this, 'acceptedDateInCommentOverview' ], 10, 2 );

		add_filter( 'manage_edit-comments_columns', [ $this, 'acceptedDateColumnInCommentOverview' ] );
	}

	/**
	 * Inits additional integration filters (that are not ready upon first call)
	 */
	public function initFormFilters() {
		if ( AdminHelper::userIsAdmin() ) {
			add_filter( 'comment_form_submit_field', [ $this, 'addFieldForAdmin' ], 999 );

			return;
		}

		add_filter( 'comment_form_submit_field', [ $this, 'addField' ], 999 );
	}

	/**
	 * @return bool
	 */
	public function hasData(): bool {
		return true;
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool {
		return true;
	}

	/**
	 * @return bool
	 */
	public function hasForms(): bool {
		return false;
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getData( string $email ): array {
		return Data::getComments( $email );
	}

	/**
	 * @param bool $front
	 * @param string $search
	 *
	 * @return array
	 */
	public function getResults( bool $front, string $search ): array {
		return [
			'icon'   => self::getIcon(),
			'title'  => self::getName( $front ),
			/* translators: %1s: search query */
			'notice' => sprintf( __( 'No comments found with email address%1s.', 'wp-gdpr-compliance' ), $search ),
		];
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
			return __( 'Comments', 'wp-gdpr-compliance' );
		}

		return _x( 'WordPress Comments', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return _x( 'When activated the GDPR checkbox will be added automatically, just above the submit button.', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * Returns text for Anonymize button on the front
	 *
	 * @param int $plural
	 *
	 * @return string
	 */
	public function getButtonText( int $plural = 1 ): string {
		return _nx( 'Anonymize selected comment', 'Anonymize selected comment(s)', $plural, 'amount of comments', 'wp-gdpr-compliance' );
	}

	/**
	 * Returns notice text for possible problems
	 * In this case for JetPack comments
	 * @return false|string
	 */
	public function getNotice() {
		if ( ! AdminHelper::pluginEnabled( 'jetpack/jetpack.php' ) ) {
			return false;
		}

		$modules = (array) get_option( 'jetpack_active_modules', [] );
		if ( ! in_array( 'comments', $modules, true ) ) {
			return false;
		}

		$notice = _x( 'Please disable the custom comments form in Jetpack to make your WordPress Comments GDPR compliant.', 'admin', 'wp-gdpr-compliance' );

		return Elements::getWarning( $notice, false );
	}

	/**
	 * @param string $submit_field
	 *
	 * @return string
	 */
	public function addField( string $submit_field = '' ): string {
		$field = apply_filters( Plugin::PREFIX . '_wordpress_field', $this->getCheckbox( false ), $submit_field );

		return implode( '', [ $field, $submit_field ] );
	}

	/**
	 * @param string $submit_field
	 *
	 * @return string
	 */
	public function addFieldForAdmin( string $submit_field = '' ): string {
		$label = '<p style="font-size: 14px;"><i>' . __( 'This checkbox is checked because you are an admin', 'wp-gdpr-compliance' ) . '</i></p>';
		$field = apply_filters( Plugin::PREFIX . '_wordpress_field', $label . $this->getCheckbox( true ), $submit_field );

		return implode( '', [ $field, $submit_field ] );
	}

	/**
	 * @param false $checked
	 *
	 * @return string
	 */
	public function getCheckbox( bool $checked = false ): string {
		$required = Template::get( 'Front/Elements/required', [ 'message' => $this->getRequiredText() ] );

		return Template::get(
			'Front/Comment/checkbox',
			[
				'name'    => $this->getFieldTag(),
				'label'   => implode( ' ', [ $this->getCheckboxText(), $required ] ),
				'checked' => $checked,
				'class'   => 'comment-form-' . Plugin::PREFIX,
			]
		);
	}

	public function checkPost() {
		if ( isset( $_POST[ $this->getFieldTag() ] ) ) {
			return;
		}

		$message = Template::get( 'Front/Elements/error', [ 'message' => $this->getErrorText() ] );
		wp_die( wp_kses( $message, \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() ), esc_html( __( 'Comment Submission Failure', 'wp-gdpr-compliance' ) ), [ 'back_link' => true ] );
	}

	/**
	 * @param int $comment_id
	 */
	public function addAcceptedDateToMeta( int $comment_id = 0 ) {
		if ( empty( $_POST[ $this->getFieldTag() ] ) ) {
			return;
		}
		if ( empty( $comment_id ) ) {
			return;
		}

		add_comment_meta( $comment_id, '_' . $this->getFieldTag(), time() );
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public function acceptedDateColumnInCommentOverview( array $columns = [] ): array {
		$columns[ $this->getFieldTag() . '-date' ] = esc_html( apply_filters( Plugin::PREFIX . '_accepted_date_column_in_comment_overview', _x( 'GDPR accepted on', 'admin', 'wp-gdpr-compliance' ) ) );

		return $columns;
	}

	/**
	 * @param string $column
	 * @param int $comment_id
	 *
	 * @return string
	 */
	public function acceptedDateInCommentOverview( string $column = '', int $comment_id = 0 ): string {
		if ( $column !== $this->getFieldTag() . '-date' ) {
			return $column;
		}

		$date  = get_comment_meta( $comment_id, '_' . $this->getFieldTag(), true );
		$value = $this->getAcceptedDate( $date );
		echo esc_html( apply_filters( Plugin::PREFIX . '_accepted_date_in_comment_overview', $value, $comment_id ) );

		return $column;
	}
}
