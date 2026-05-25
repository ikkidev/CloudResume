<?php
/**
 * Plugin constants
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Database.
define( 'CACE_DATABASE_VERSION_OPTION_NAME', 'commentace_db_version' );
define( 'CACE_DATABASE_VERSION', '1.0' );
define( 'CACE_VOTES_TABLE_NAME', 'commentace_votes' );

// Types of comments.
define( 'CACE_COMMENT_TYPE_WORDPRESS',  'wp' );
define( 'CACE_COMMENT_TYPE_FACEBOOK',   'fb' );
define( 'CACE_COMMENT_TYPE_DISQUS',     'dsq' );

// Vote type values.
define( 'CACE_VOTE_UP', 1 );
define( 'CACE_VOTE_DOWN', -1 );

// WordPress comment form postion.
define( 'CACE_WP_COMMENT_FORM_BEFORE', 'before' );
define( 'CACE_WP_COMMENT_FORM_AFTER', 'after' );