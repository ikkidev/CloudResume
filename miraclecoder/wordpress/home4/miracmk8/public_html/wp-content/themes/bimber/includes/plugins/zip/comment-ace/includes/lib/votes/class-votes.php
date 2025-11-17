<?php
/**
 * Votes class
 * Peer class that contain static methods to operate on the Votes table
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Votes {

    public static function count_user_votes( $user_id, $type = '' ) {
        $db = plugin()->db();

        if ( ! empty( $type ) ) {
            $type_value = ( 'up' === $type ) ? 1 : -1;

            $query_str = "SELECT COUNT(*) count FROM {$db->get_votes_table_name()} WHERE author_id = $user_id AND value = $type_value";
        } else {
            $query_str = "SELECT COUNT(*) count FROM {$db->get_votes_table_name()} WHERE author_id = $user_id";
        }


        $res = $db->wpdb()->get_row( $query_str, OBJECT, 0 );

        return (int) $res->count;
    }

    /**
     * Find user votes
     *
     * @param int $user_id      User id.
     * @param int $max          Number of votst to get.
     * @param int $offset       Votes offset.
     *
     * @return array            Array of vote IDs.
     */
    public static function find_by_user( $user_id, $type = '', $max = 10, $offset = 0 ) {
        $db = plugin()->db();

        if ( ! empty( $type ) ) {
            $type_value = ( 'up' === $type ) ? 1 : -1;

            $query_str = "SELECT comment_id, value FROM {$db->get_votes_table_name()} WHERE author_id = $user_id AND value = $type_value ORDER BY date DESC LIMIT $max OFFSET $offset";
        } else {
            $query_str = "SELECT comment_id, value FROM {$db->get_votes_table_name()} WHERE author_id = $user_id ORDER BY date DESC LIMIT $max OFFSET $offset";
        }


        return $db->wpdb()->get_results( $query_str, ARRAY_A, 0 );
    }

    /**
     * Find a vote (unique)
     *
     * @param int $user_id          User id.
     * @param int $comment_id       Comment id.
     * @param bool|int $value       Optional. Vote value.
     *
     * @throws \Exception
     *
     * @return bool|Vote           Vote object or false if not found.
     */
    public static function find_by_user_comment($user_id, $comment_id, $value = false ) {
        $db = plugin()->db();
        $votes = array();

        if ($value) {
            if ( is_string( $value ) ) {
                $value = 'up' === $value ? 1 : -1;
            }

            $query_str = "SELECT * FROM {$db->get_votes_table_name()} WHERE comment_id = $comment_id AND author_id = $user_id AND value = $value";
        } else {
            $query_str = "SELECT * FROM {$db->get_votes_table_name()} WHERE comment_id = $comment_id AND author_id = $user_id";
        }

        $result = $db->wpdb()->get_row( $query_str, OBJECT, 0 );

        if ( $result ) {
            $vote = new Vote();
            $vote->set_id( $result->id );
            $vote->set_comment_id( $result->comment_id );
            $vote->set_value( $result->value );
            $vote->set_author_id( $result->author_id );
            $vote->set_author_ip( $result->author_ip );
            $vote->set_author_host( $result->author_host );
            $vote->set_date( $result->date );
            $vote->set_date_gmt( $result->date_gmt );

            return $vote;
        }

        return false;
    }


    /**
     * Delete all votes attached to the comment
     *
     * @param int $comment_id       Comment id.
     *
     * @return bool|int             Number of deleted rows or false on failure.
     */
    public static function delete_comment_votes( $comment_id ) {
        $db = plugin()->db();

        return $db->wpdb()->delete( $db->get_votes_table_name(), array( 'comment_id' => $comment_id ), array( '%d' ) );
    }

    /**
     * Return votes summary (score, up votes, down votes)
     *
     * @param int $comment_id       Comment ID.
     *
     * @return array
     */
    public static function get_comment_votes( $comment_id ) {
        return array(
            'score'      => self::get_comment_score( $comment_id ),
            'up_votes'   => self::get_comment_up_votes( $comment_id ),
            'down_votes' => self::get_comment_down_votes( $comment_id ),
        );
    }

    /**
     * Return total score
     *
     * @param int $comment_id       Comment ID.
     *
     * @return int
     */
    public static function get_comment_score( $comment_id ) {
        return (int) get_comment_meta( $comment_id, '_commentace_voting_score', true );
    }

    /**
     * Return number of up votes
     *
     * @param int $comment_id       Comment ID.
     *
     * @return int
     */
    public static function get_comment_up_votes( $comment_id ) {
        return (int) get_comment_meta( $comment_id, '_commentace_voting_up_votes', true );
    }

    /**
     * Return number of down votes
     *
     * @param int $comment_id       Comment ID.
     *
     * @return int
     */
    public static function get_comment_down_votes( $comment_id ) {
        return (int) get_comment_meta( $comment_id, '_commentace_voting_down_votes', true );
    }

    /**
     * Update comment's score
     *
     * @param int $comment_id       Comment ID.
     *
     * @return int
     */
    public static function update_comment_score( $comment_id ) {
        $score = self::calculate_comment_score( $comment_id );

        update_comment_meta( $comment_id, '_commentace_voting_score', $score['total'] );
        update_comment_meta( $comment_id, '_commentace_voting_up_votes', $score['up_votes'] );
        update_comment_meta( $comment_id, '_commentace_voting_down_votes', $score['down_votes'] );
        update_comment_meta( $comment_id, '_commentace_voting_total_votes', $score['up_votes'] + $score['down_votes'] );

        return $score;
    }

    /**
     * Calculate comments' score
     *
     * @param int $comment_id       Comment ID.
     *
     * @return int
     */
    protected static function calculate_comment_score( $comment_id ) {
        $db = plugin()->db();

        $score = $db->wpdb()->get_row( "SELECT 
                SUM(value) as total, 
                SUM(if(value>0,1,0)) as up_votes,
                SUM(if(value<0,1,0)) as down_votes
            FROM 
                {$db->get_votes_table_name()} 
            WHERE comment_id = $comment_id;", ARRAY_A );

        return $score;
    }
}
