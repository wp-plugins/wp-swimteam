<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: users.include.php 847 2012-05-09 16:00:20Z mpwalsh8 $
 *
 * Users includes.  These includes define information used in 
 * the Users classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Users
 * @version $Revision: 847 $
 * @lastmodified $Date: 2012-05-09 12:00:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

/**
 * Define users table name
 */
define('WPST_USERS_TABLE', WPST_DB_PREFIX . 'users') ;

/**
 * default constants for GUIDataList construction
 */
define('WPST_USERS_DEFAULT_COLUMNS', '*') ;
define('WPST_USERS_DEFAULT_TABLES', WPST_USERS_TABLE) ;
define('WPST_USERS_DEFAULT_WHERE_CLAUSE', '') ;

/**
 * complex constants for GUIDataList to show names from
 * WP tables and other information from plugin tables.
 */
define('WPST_USERS_COLUMNS', 'id AS userid, user_login, firstname, lastname, swimmers') ;
define('WPST_USERS_TABLES', sprintf('%susers
            LEFT JOIN (
	            SELECT user_id AS uid, meta_value AS firstname
	            FROM %susermeta 
	            WHERE meta_key = \'first_name\'
            ) AS metaF ON %susers.ID = metaF.uid
            LEFT JOIN (
	            SELECT user_id AS uid, meta_value AS lastname
	            FROM %susermeta 
	            WHERE meta_key = \'last_name\'
            ) AS metaL ON %susers.ID = metaL.uid
            LEFT JOIN (
                SELECT %sswimmers.contact1id as swimmers
                FROM %sswimmers
                UNION
                SELECT %sswimmers.contact2id
                FROM %sswimmers) AS registered
                ON %susers.ID = registered.swimmers',
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WP_DB_BASE_PREFIX
            )) ;
define('WPST_USERS_WHERE_CLAUSE', WPST_USERS_DEFAULT_WHERE_CLAUSE) ;

?>
