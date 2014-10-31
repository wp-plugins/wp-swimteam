<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: db.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * DB includes.  These includes define information used in 
 * the DB classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage DB
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
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
 * Define table prefixes, need to account for multi-site!
 */
define('WP_DB_PREFIX', $wpdb->prefix) ;
define('WP_DB_BASE_PREFIX', $wpdb->base_prefix) ;
define('WPST_DB_PREFIX', WP_DB_PREFIX . 'st_') ;
define('WPST_DB_BASE_PREFIX', WP_DB_BASE_PREFIX . 'st_') ;

/**
 * Database version - stored as a WP option
 */
define('WPST_DB_VERSION', '0.89') ;
define('WPST_DB_OPTION', 'swimteam_db_version') ;

?>
