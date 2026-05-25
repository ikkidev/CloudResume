<?php

namespace WPGDPRC\Objects\Data;

use WPGDPRC\Integrations\WPComments;
use WPGDPRC\Utils\Anonymous;
use WPGDPRC\WordPress\Plugin;

/**
 * Class Comment
 * @package WPGDPRC\Objects\Data
 */
class Comment {

	/** @var int */
	protected $id = 0;
	/** @var int */
	protected $postId = 0;
	/** @var string */
	protected $name = '';
	/** @var string */
	protected $emailAddress = '';
	/** @var string */
	protected $content = '';
	/** @var string */
	protected $ipAddress = '';
	/** @var string */
	protected $date = '';

	/**
	 * Comment constructor
	 *
	 * @param int $id
	 */
	public function __construct( int $id = 0 ) {
		if ( empty( $id ) ) {
			return;
		}

		$this->setId( (int) $id );
		$this->load();
	}

	/**
	 * @return string
	 */
	public static function getDataSlug(): string {
		return WPComments::getInstance()->getID();
	}

	/**
	 * @return string
	 */
	public static function getDataName(): string {
		return __( 'Comment', 'wp-gdpr-compliance' );
	}

	/**
	 * Loads Comment attributes
	 */
	public function load() {
		global $wpdb;
		$query = 'SELECT * FROM `' . $wpdb->users . '` WHERE `ID` = %d';
		$row   = $wpdb->get_row( $wpdb->prepare( $query, $this->getId() ) );
		if ( empty( $row ) ) {
			return;
		}

		$this->loadByRow( $row );
	}

	/**
	 * Sets Comment attributes based on DB row
	 *
	 * @param \stdClass $row
	 */
	public function loadByRow( \stdClass $row ) {
		$this->setId( $row->comment_ID );
		$this->setPostId( $row->comment_post_ID );
		$this->setName( $row->comment_author );
		$this->setEmailAddress( $row->comment_author_email );
		$this->setIpAddress( $row->comment_author_IP );
		$this->setContent( $row->comment_content );
		$this->setDate( $row->comment_date );
	}

	/**
	 * Lists Comments with specific email address
	 *
	 * @param string $email
	 *
	 * @return array
	 */
	public static function getByEmail( string $email = '' ): array {
		$output = [];
		if ( empty( $email ) ) {
			return $output;
		}

		global $wpdb;
		$query   = 'SELECT * FROM ' . $wpdb->comments . ' WHERE `comment_author_email` = %s';
		$results = $wpdb->get_results( $wpdb->prepare( $query, $email ) );
		if ( empty( $results ) ) {
			return $output;
		}

		foreach ( $results as $row ) {
			$object = new self();
			$object->loadByRow( $row );
			$output[] = $object;
		}

		return $output;
	}

	/**
	 * @param int $comment_id
	 *
	 * @return int
	 */
	public static function anonymize( int $comment_id = 0 ): int {
		$data = [
			'comment_ID'           => $comment_id,
			'comment_author'       => Anonymous::getName(),
			'comment_author_email' => Anonymous::getEmailAddress( $comment_id ),
			'comment_author_IP'    => Anonymous::getIpAddress(),
			'comment_author_url'   => Anonymous::getSiteUrl(),
			'user_id'              => 0,
			'comment_agent'        => '',
		];

		return wp_update_comment( $data );
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return (int) $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( int $id = 0 ) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getPostId(): int {
		return (int) $this->postId;
	}

	/**
	 * @param int $post_id
	 */
	public function setPostId( int $post_id = 0 ) {
		$this->postId = $post_id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name = '' ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getEmailAddress(): string {
		return $this->emailAddress;
	}

	/**
	 * @param string $email_address
	 */
	public function setEmailAddress( string $email_address = '' ) {
		$this->emailAddress = $email_address;
	}

	/**
	 * @return string
	 */
	public function getIpAddress(): string {
		return $this->ipAddress;
	}

	/**
	 * @param string $ip_address
	 */
	public function setIpAddress( string $ip_address = '' ) {
		$this->ipAddress = $ip_address;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent( string $content = '' ) {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getDate(): string {
		return $this->date;
	}

	/**
	 * @param string $date
	 */
	public function setDate( string $date = '' ) {
		$this->date = $date;
	}

}
