<?php


namespace WPGDPRC\Objects\Data;

use WPGDPRC\Integrations\Plugins\GravityForms as GravityFormsIntegration;
use WPGDPRC\Utils\Anonymous;

class GravityFormsEntry {

	protected $entry;

	const TYPE_NAME    = 'name';
	const TYPE_PHONE   = 'phone';
	const TYPE_EMAIL   = 'email';
	const TYPE_ADDRESS = 'address';

	public function __construct( array $entry ) {
		$this->entry = $entry;
	}

	public function anonymize() {
		$map = [
			static::TYPE_NAME    => 'NAME',
			static::TYPE_EMAIL   => Anonymous::anonymizeEmail( $this->getEmail() ),
			static::TYPE_PHONE   => 'PHONE',
			static::TYPE_ADDRESS => 'ADDRESS',
		];

		$form = static::getForm();

		foreach ( $form['fields'] as $field ) {
			if ( ! in_array( $field['type'], array_keys( $map ), true ) ) {
				continue;
			}

			if ( is_array( $field['inputs'] ) ) {
				foreach ( $field['inputs'] as $input ) {
					gform_update_meta( $this->getId(), $input['id'], $map[ $field['type'] ] . '_' . $input['id'] );
				}
			} else {
				gform_update_meta( $this->getId(), $field['id'], $map[ $field['type'] ] );
			}
		}
	}

	public function getId(): int {
		return $this->entry['id'];
	}

	public function getEmail(): string {
		$fields = static::getEmailFields( $this->entry['form_id'] );

		return implode(
			', ',
			array_map(
				function ( $field ) {
					return $this->entry[ $field['id'] ];
				},
				$fields
			)
		);
	}

	public function getIp(): string {
		return $this->entry['ip'];
	}

	public function getDate(): string {
		return $this->entry['date_created'];
	}

	public function getForm(): array {
		if ( ! class_exists( 'GFAPI' ) ) {
			return [];
		}

		return \GFAPI::get_form( $this->entry['form_id'] );
	}

	public function getFormName(): string {
		return $this->getForm()['title'] ?? '';
	}

	public static function getDataSlug(): string {
		return GravityFormsIntegration::getInstance()->getID();
	}

	/**
	 * @param string $email
	 * @return GravityFormsEntry[]
	 */
	public static function getByEmail( string $email ): array {
		$entries = static::getEntriesByEmail( $email );

		if ( $user = get_user_by( 'email', $email ) ) {
			$entries = array_merge(
				$entries,
				static::getEntriesByUser( $user->ID )
			);
		}

		return static::hydrate( array_unique( $entries, SORT_REGULAR ) );
	}

	public static function getByDataId( int $id ): ?GravityFormsEntry {
		if ( ! class_exists( 'GFAPI' ) ) {
			return null;
		}

		return new static(
			\GFAPI::get_entry( $id )
		);
	}

	/**
	 * @param array $entries
	 * @return GravityFormsEntry[]
	 */
	public static function hydrate( array $entries ): array {
		return array_map(
			function ( $entry ) {
				return new static( $entry );
			},
			$entries
		);
	}

	protected static function getEntriesByEmail( string $email ): array {
		if ( ! class_exists( 'GFAPI' ) ) {
			return [];
		}

		$forms = \GFAPI::get_forms();

		$entries = [];
		foreach ( $forms as $form ) {
			$fields = static::getEmailFields( $form['id'] );

			$filters = array_map(
				function ( $field ) use ( $email ) {
					return [
						'key'   => $field['id'],
						'value' => $email,
					];
				},
				$fields
			);

			$entries = array_merge(
				$entries,
				\GFAPI::get_entries(
					$form['id'],
					[ 'field_filters' => $filters ]
				)
			);
		}

		return $entries;
	}

	protected static function getEntriesByUser( int $userId ): array {
		if ( ! class_exists( 'GFAPI' ) ) {
			return [];
		}

		return \GFAPI::get_entries(
			null,
			[
				'field_filters' => [
					[
						'key'   => 'created_by',
						'value' => $userId,
					],
				],
			]
		);
	}

	protected static function getEmailFields( int $formId ): array {
		if ( ! class_exists( 'GFAPI' ) ) {
			return [];
		}

		$form = \GFAPI::get_form( $formId );

		return array_filter(
			$form['fields'],
			function ( $field ) {
				return $field['type'] === 'email';
			}
		);
	}
}
