<?php

namespace WPGDPRC\Integrations\Plugins;

use WPGDPRC\Objects\Data\GravityFormsEntry;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

/**
 * Class GravityForms
 * @package WPGDPRC\Integrations\Plugins
 */
class GravityForms extends AbstractPlugin {

	/**
	 * @return string
	 */
	public function getID(): string {
		return 'gravity-forms';
	}

	/**
	 * @return string
	 */
	public function getVersion(): string {
		return '1.9';
	}

	/**
	 * @return string
	 */
	public function getFile(): string {
		return 'gravityforms/gravityforms.php';
	}

	/**
	 * Inits all integration actions & filters
	 */
	public function initHooks() {
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$this->initUpdateOption();

		add_filter( 'gform_entries_field_value', [ $this, 'acceptedDateInOverview' ], 10, 4 );
		add_filter( 'gform_get_field_value', [ $this, 'acceptedDateInEntry' ], 10, 3 );

		// continuously check the form field and edit if needed.
		add_action( 'gform_after_save_form', [ $this, 'updateFormField' ], 10, 1 );

		foreach ( $this->getEnabledForms() as $form_id ) {
			add_filter( 'gform_entry_list_columns_' . $form_id, [ $this, 'acceptedDateColumn' ], 10, 2 );
			add_filter( 'gform_save_field_value_' . $form_id, [ $this, 'saveAcceptedDate' ], 10, 3 );
			add_action( 'gform_validation_' . $form_id, [ $this, 'updateValidationText' ] );
		}
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
	public function hasForms(): bool {
		return true;
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getData( string $email ): array {
		return GravityFormsEntry::getByEmail( $email );
	}

	/**
	 * @param bool $front
	 * @param string $search
	 *
	 * @return array
	 */
	public function getResults( bool $front, string $search ): array {
		return [
			'icon'   => $this->getIcon(),
			'title'  => $this->getName( $front ),
			/* translators: %1s: search query */
			'notice' => sprintf( __( 'No form entries found with email address%1s.', 'wp-gdpr-compliance' ), $search ),
		];
	}

	/**
	 * Updates forms upon plugin activation
	 */
	public function onPluginActivation() {
		if ( ! $this->isEnabled() ) {
			return;
		}

		$this->updateFormField();
	}

	/**
	 * Updates forms upon plugin deactivation
	 */
	public function onPluginDeactivation() {
		if ( ! $this->isEnabled() ) {
			return;
		}

		foreach ( $this->getList() as $form_id => $title ) {
			if ( ! in_array( (int) $form_id, $this->getEnabledForms(), true ) ) {
				continue;
			}

			$this->removeFormField( $form_id );
		}
	}

	/**
	 * Returns integration icon
	 * @return string
	 */
	public function getIcon(): string {
		return 'icon-gravity-forms.svg';
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

		return _x( 'Gravity Forms', 'admin', 'wp-gdpr-compliance' );
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
	 * Lists all Gravity Forms forms (active & disabled)
	 * Returns array with 'form ID' => 'form title (status)'
	 * @return array
	 */
	public function getList(): array {
		$list = [];
		if ( ! class_exists( 'GFAPI' ) ) {
			return $list;
		}

		foreach ( \GFAPI::get_forms() as $form ) {
			if ( ! empty( $form['is_trash'] ) ) {
				continue;
			}

			$status_text = empty( $form['is_active'] ) ? _x( 'disabled', 'admin', 'wp-gdpr-compliance' ) : _x( 'enabled', 'admin', 'wp-gdpr-compliance' );

			$list[ $form['id'] ] = sprintf( '%1s (%2s)', $form['title'], $status_text );
		}

		ksort( $list );

		return $list;
	}

	/**
	 * Inserts consent checkbox at the end of the (enabled) forms.
	 */
	public function updateFormField() {
		$forms = $this->getList();
		$this->updateFormFieldsIfNeeded( $forms );
	}

	/**
	 * Inserts consent checkbox at the end of the edited form.
	 *
	 * @param $form
	 */
	public function updateFormFieldOnSave( $form ) {
		$this->updateFormFieldsIfNeeded( [ $form ] );
	}

	/**
	 * Inserts consent checkbox at the end of passed forms.
	 *
	 * @param array $forms
	 */
	public function updateFormFieldsIfNeeded( array $forms = [] ) {
		foreach ( $forms as $form_id => $name ) {
			if ( ! $this->isEnabled() || ! $this->isEnabledForm( $form_id ) ) {
				$this->removeFormField( $form_id );
				continue;
			}
			$this->addFormField( $form_id );
		}
	}

	/**
	 * Removes WPGDPRC field from specific form
	 *
	 * @param int $form_id
	 */
	public function removeFormField( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$form = \GFAPI::get_form( $form_id );
		if ( empty( $form ) ) {
			return;
		}

		foreach ( $form['fields'] as $index => $field ) {
			if ( empty( $this->validateFormField( (array) $field ) ) ) {
				continue;
			}

			unset( $form['fields'][ $index ] );
			\GFAPI::update_form( $form, $form['id'] );
		}
	}

	/**
	 * Adds WPGDPRC field to specific form
	 *
	 * @param int $form_id
	 */
	public function addFormField( int $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return;
		}
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$form = \GFAPI::get_form( $form_id );
		if ( empty( $form ) ) {
			return;
		}

		$field_id  = 0;
		$field_tag = $this->getFieldTag();
		$required  = Template::get(
			'Front/Elements/required',
			[
				'message' => $this->getRequiredTextByForm( $form['id'] ),
			]
		);
		$checkbox  = [
			'text'       => implode( ' ', [ $this->getCheckboxTextByForm( $form['id'] ), $required ] ),
			'value'      => 'true',
			'isSelected' => false,
		];

		foreach ( $form['fields'] as &$field ) {
			// collect latest field ID
			if ( $field->id >= $field_id ) {
				$field_id = (int) $field->id + 1;
			}

			// make sure choices are set if this is a WP GDPRC field
			if ( empty( $this->validateFormField( (array) $field ) ) ) {
				continue;
			}

			$field['choices'] = [ $checkbox ];
			\GFAPI::update_form( $form, $form['id'] );

			return;
		}

		if ( empty( $field_id ) ) {
			$field_id = array_reduce(
				$form['fields'],
				function( $carry, $item ) {
					return $item->id > $carry ? $item->id : $carry;
				},
				1
			);
		}
		$input = [
			'id'    => $field_id . '.1',
			'label' => $this->getCheckboxTextByForm( $form['id'] ),
			'name'  => $field_tag,
		];

		$args = [
			'id'                => $field_id,
			$field_tag          => true,
			'type'              => 'checkbox',
			'label'             => $this->getPrivacyLabel(),
			'labelPlacement'    => 'hidden_label',
			'isRequired'        => true,
			'enableChoiceValue' => true,
			'choices'           => [ $checkbox ],
			'inputs'            => [ $input ],
		];

		$form['fields'][] = apply_filters( Plugin::PREFIX . '_gforms_field_args', $args, $form );
		\GFAPI::update_form( $form, $form['id'] );
	}

	/**
	 * Validates form field (and checks if it is the WPGDRPC form field)
	 *
	 * @param array $field
	 *
	 * @return false|array
	 */
	public function validateFormField( $field = [] ) {
		$field_tag = $this->getFieldTag();

		if ( is_object( $field ) ) {
			if ( ! class_exists( 'GF_Field' ) ) {
				return false;
			}
			if ( ! $field instanceof \GF_Field ) {
				return false;
			}
			if ( ! property_exists( $field, $field_tag ) ) {
				return false;
			}
			if ( $field->{$field_tag} !== true ) {
				return false;
			}

			return $field;
		}

		if ( empty( $field[ $field_tag ] ) ) {
			return false;
		}
		if ( $field[ $field_tag ] !== true ) {
			return false;
		}

		return $field;
	}

	/**
	 * Searches the WPGDPRC field ID inside a specific form
	 *
	 * @param int $form_id
	 *
	 * @return int
	 */
	public function getFieldIdByFormId( $form_id = 0 ): string {
		if ( ! class_exists( 'GFFormsModel' ) ) {
			return 0;
		}

		$form = \GFFormsModel::get_form_meta( $form_id );
		if ( empty( $form ) || empty( $form['fields'] ) ) {
			return 0;
		}

		foreach ( $form['fields'] as $field ) {
			if ( empty( $this->validateFormField( $field ) ) ) {
				continue;
			}
			if ( ! isset( $field['inputs'][0]['id'] ) ) {
				continue;
			}

			return $field['inputs'][0]['id'];
		}

		return 0;
	}

	/**
	 * Returns the 'accepted date' value in the entry single view
	 *
	 * @param mixed $value
	 * @param array $entry
	 *
	 * @return string
	 */
	public function acceptedDateInEntry( $value, $entry, $field ) {
		$field_id = $this->getFieldIdByFormId( $entry['form_id'] );
		if ( empty( $field_id ) ) {
			return $value;
		}

		if ( ! property_exists( $field_id, $this->getFieldTag() ) ) {
			return $value;
		}

		if ( empty( $value[ $field_id ] ) ) {
			$value[ $field_id ] = $this->getAcceptedDate( false );
		}

		return apply_filters( Plugin::PREFIX . '_gforms_accepted_date_in_entry', $value, $field_id, $entry );
	}

	/**
	 * Returns the 'accepted date' value in the entries overview
	 *
	 * @param string $value
	 * @param int $form_id
	 * @param int $field_id
	 * @param array $entry
	 *
	 * @return string
	 */
	public function acceptedDateInOverview( $value = '', $form_id = 0, $field_id = 0, $entry = [] ) {
		if ( ! empty( $value ) ) {
			return $value;
		}

		$id = $this->getFieldIdByFormId( $form_id );
		if ( empty( $id ) ) {
			return $value;
		}

		if ( (int) $field_id != floor( $id ) ) {
			return $value;
		}

		$value = ! empty( $entry[ $id ] ) ? $entry[ $id ] : $this->getAcceptedDate( false );

		return apply_filters( Plugin::PREFIX . '_gforms_accepted_date_in_entry_overview', $value, $field_id, $form_id, $entry );
	}

	/**
	 * Returns the 'accepted date' column name in the entries overview
	 *
	 * @param array $columns
	 * @param int $form_id
	 *
	 * @return array
	 */
	public function acceptedDateColumn( $columns = [], $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return $columns;
		}

		$field_id = $this->getFieldIdByFormId( $form_id );
		$key      = "field_id-$field_id";
		if ( ! isset( $columns[ $key ] ) ) {
			return $columns;
		}

		$text            = $this->getPrivacyLabel();
		$columns[ $key ] = apply_filters( Plugin::PREFIX . '_gforms_accepted_date_column_in_entry_overview', $text, $columns[ $key ], $form_id );

		return $columns;
	}

	/**
	 * Saves the 'accepted date' value to the entry
	 *
	 * @param string $value
	 * @param array $lead
	 * @param mixed $field
	 *
	 * @return string
	 */
	public function saveAcceptedDate( $value = '', $lead = [], $field = [] ) {
		if ( empty( $this->validateFormField( $field ) ) ) {
			return $value;
		}

		$value = $this->getAcceptedDate( $value );

		return apply_filters( Plugin::PREFIX . '_gforms_accepted_date_to_entry', $value, $field, $lead );
	}

	/**
	 * Updates the validation text for the WPGDPRC field
	 *
	 * @param array $validation_result
	 *
	 * @return array
	 */
	public function updateValidationText( array $validation_result = [] ): array {
		$form = $validation_result['form'];

		foreach ( $form['fields'] as &$field ) {
			if ( empty( $this->validateFormField( $field ) ) ) {
				continue;
			}
			if ( ! isset( $field['failed_validation'] ) ) {
				continue;
			}
			if ( $field['failed_validation'] !== true ) {
				continue;
			}

			$field['validation_message'] = apply_filters( Plugin::PREFIX . '_gforms_validation_message', $this->getErrorTextByForm( $form['id'] ), $field, $form );
		}
		$validation_result['form'] = $form;

		return $validation_result;
	}
}
