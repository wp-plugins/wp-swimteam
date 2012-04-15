<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * DB includes.  These includes define information used in 
 * the DB classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage DB
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

global $wpdb ;

/**
 * Username, password, and hostname for the Swim Team database
 * but, no reason not to use what is already defined for Wordpress.
 * This will fail if Wordpress' config file isn't present but so
 * will Wordpress so it is pretty much of a non-issue.
 *
 */


/**
 * The username to use
 */
define('WPST_DB_USERNAME', DB_USER) ;

/**
 * The password to use
 */
define('WPST_DB_PASSWORD', DB_PASSWORD) ;

/**
 * The database server to use
 */
define('WPST_DB_HOSTNAME', DB_HOST);

/**
 * The database name to use
 */
define('WPST_DB_NAME', DB_NAME) ;

/**
 * build the DSN which is used by phpHtmlLib
 */
define('WPST_DB_DSN', 'mysql://' . WPST_DB_USERNAME . ':' . WPST_DB_PASSWORD . '@' . WPST_DB_HOSTNAME . '/' . WPST_DB_NAME) ;


/**
 * Define table prefix
 */
define('WP_DB_PREFIX', $wpdb->prefix) ;
define('WPST_DB_PREFIX', WP_DB_PREFIX . 'st_') ;

/**
 * Database version - stored as a WP option
 */
define('WPST_DB_VERSION', '0.86') ;
define('WPST_DB_OPTION', 'swimteam_db_version') ;

?>
