<?php
/**
 * Vote class
 * Object class that represents a record in the database
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
use Hybridauth\Exception\Exception;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Vote {

    private $id;
    private $comment_id;
    private $value;
    private $author_id;
    private $author_ip;
    private $author_host;
    private $date;
    private $date_gmt;

    /**
     * Get vote ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Set vote ID
     *
     * @return int
     */
    public function set_id( $id ) {
        $this->id = (int) $id;
    }

    /**
     * Get vote created data
     *
     * @return string
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * Set vote created data
     *
     * @param string $date
     */
    public function set_date( $date ) {
        $this->date = $date;
    }

    /**
     * Get vote created data GMT
     *
     * @return string
     */
    public function get_date_gmt() {
        return $this->date_gmt;
    }

    /**
     * Set vote created data GMT
     *
     * @paran $date_gmt string
     */
    public function set_date_gmt( $date_gmt ) {
        $this->date_gmt = $date_gmt;
    }

    /**
     * Get comment ID
     *
     * @return int
     */
    public function get_comment_id() {
        return $this->comment_id;
    }

    /**
     * Set comment ID
     *
     * @param int $id
     */
    public function set_comment_id( $id ) {
        $this->comment_id = (int) $id;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param int $value       Integer
     * @throws \Exception
     */
    public function set_value( $value ) {
        if ( in_array( $value, array( 'up', 'down' ) ) ) {
            $value = 'up' === $value ? 1 : -1;
        }

        $this->value = (int) $value;
    }

    /**
     * Get author ID
     *
     * @return int
     */
    public function get_author_id() {
        return $this->author_id;
    }

    /**
     * Set author ID
     *
     * @param int $id
     */
    public function set_author_id( $id ) {
        $this->author_id = (int) $id;
    }

    /**
     * Get author IP
     *
     * @return int
     */
    public function get_author_ip() {
        return $this->author_ip;
    }

    /**
     * Set author IP
     *
     * @param string $ip
     */
    public function set_author_ip( $ip ) {
        $this->author_ip = $ip;
    }

    /**
     * Get author host
     *
     * @return int
     */
    public function get_author_host() {
        return $this->author_host;
    }

    /**
     * Set author host
     *
     * @param string $host
     */
    public function set_author_host( $host ) {
        $this->author_host = $host;
    }

    /**
     * Check whether the vote is equal to the value
     *
     * @param int $value        Vote value.
     *
     * @return bool
     */
    public function is( $value ) {
        return $this->get_value() === (int) $value;
    }

    /**
     * Insert/Update into database
     *
     * @throws \Exception
     */
    public function save() {
        $db = plugin()->db();

        // Update.
        if ( $this->get_id() ) {

            $res = $db->wpdb()->update(
                $db->get_votes_table_name(),
                array(
                    'comment_id'    => $this->get_comment_id(),
                    'value'         => $this->get_value(),
                    'author_id'     => $this->get_author_id(),
                    'author_ip'     => $this->get_author_ip(),
                    'author_host'   => $this->get_author_host(),
                ),
                array( 'id' => $this->get_id() ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                ),
                array( '%d' )
            );

            if ( false === $res ) {
                throw new \Exception( 'Failed to update vote' );
            }

        // Insert.
        } else {
            $current_date  = current_time( 'mysql' );

            $res = $db->wpdb()->insert(
                $db->get_votes_table_name(),
                array(
                    'comment_id'    => $this->get_comment_id(),
                    'value'         => $this->get_value(),
                    'author_id'     => $this->get_author_id(),
                    'author_ip'     => $this->get_author_ip(),
                    'author_host'   => $this->get_author_host(),
                    'date'          => $current_date,
                    'date_gmt'      => get_gmt_from_date( $current_date ),
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );

            if ( false === $res ) {
                throw new \Exception( 'Failed to insert vote' );
            }

            $this->set_id( $db->wpdb()->insert_id );
        }

        $this->update_comment_score();
    }

    /**
     * Remove from database
     *
     * @throws \Exception
     */
    public function delete() {
        $db = plugin()->db();

        $res = $db->wpdb()->delete( $db->get_votes_table_name(), array( 'id' => $this->get_id() ), array( '%d' ) );

        if ( false === $res ) {
            throw new \Exception();
        }

        $this->update_comment_score();
    }

    /**
     * Update related comment's score on vote change
     */
    protected function update_comment_score() {
        Votes::update_comment_score( $this->get_comment_id() );
    }
}
