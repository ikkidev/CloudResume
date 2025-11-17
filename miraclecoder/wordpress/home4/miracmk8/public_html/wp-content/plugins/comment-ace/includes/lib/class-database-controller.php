<?php
/**
 * Database Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

class Database_Controller {

    /**
     * Return user's database version
     *
     * @return mixed        Version number (string) or false if db not initiated
     */
    public function get_user_db_version() {
        return get_option( CACE_DATABASE_VERSION_OPTION_NAME );
    }

    /**
     * Return plugin's database current version
     *
     * @return string
     */
    public function get_plugin_db_version() {
        return CACE_DATABASE_VERSION;
    }

    /**
     * Check whether the database requires an update
     */
    public function update_required() {
        return $this->get_plugin_db_version() !== $this->get_user_db_version();
    }

    /**
     * Return name of the votes table
     *
     * @return string
     */
    public function get_votes_table_name() {
        global $wpdb;

        return $wpdb->prefix . CACE_VOTES_TABLE_NAME;
    }

    /**
     * Update database to the latest version
     */
    public function update() {
        $migrations = $this->get_migrations();
        $migrated = 0;

        foreach ( $migrations as $version => $callback ) {
            if ( $version > $this->get_user_db_version() && $version <= $this->get_plugin_db_version() ) {
                call_user_func( $callback );

                $migrated++;
            }
        }

        if ( $migrated > 0 ) {
            update_option( CACE_DATABASE_VERSION_OPTION_NAME, $version );
        }
    }

    /**
     * Return WP database abstraction layer handler
     *
     * @return wpdb|\wpdb
     */
    public function wpdb() {
        /**
         * @var wpdb $wpdb
         */
        global $wpdb;

        return $wpdb;
    }

    protected function get_migrations() {
        return array(
            '1.0' => array( $this, 'init_schema' ),
        );
    }

    /**
     * Migration 1.0 - Init schema
     */
    protected function init_schema() {
        global $wpdb;

        $table_name      = $this->get_votes_table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL auto_increment,
		comment_id bigint(20) NOT NULL ,
		value int(2) NOT NULL,
		author_id bigint(20) NOT NULL default '0',
  		author_ip varchar(100) NOT NULL default '',
		author_host varchar(200) NOT NULL,
		date datetime NOT NULL default '0000-00-00 00:00:00',
  		date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
		PRIMARY KEY (id),
		KEY comment_id (comment_id)
	    ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
