<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Users includes.  These includes define information used in 
 * the Users classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Users
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

/**
 * Define users table name
 */
define("WPST_USERS_TABLE", WPST_DB_PREFIX . "users") ;

/**
 * default constants for GUIDataList construction
 */
define("WPST_USERS_DEFAULT_COLUMNS", "*") ;
define("WPST_USERS_DEFAULT_TABLES", WPST_USERS_TABLE) ;
define("WPST_USERS_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * complex constants for GUIDataList to show names from
 * WP tables and other information from plugin tables.
 */
define("WPST_USERS_COLUMNS", "ID AS userid, user_login, firstname, lastname, swimmers") ;
define("WPST_USERS_TABLES", sprintf("`%susers`
            LEFT JOIN (
	            SELECT user_id AS uid, meta_value AS firstname
	            FROM `%susermeta` 
	            WHERE meta_key = 'first_name'
            ) AS metaF ON %susers.ID = metaF.uid
            LEFT JOIN (
	            SELECT user_id AS uid, meta_value AS lastname
	            FROM `%susermeta` 
	            WHERE meta_key = 'last_name'
            ) AS metaL ON %susers.ID = metaL.uid
            LEFT JOIN (
                SELECT %sswimmers.contact1id as swimmers
                FROM `%sswimmers`
                UNION
                SELECT %sswimmers.contact2id
                FROM `%sswimmers`) AS registered
                ON %susers.ID = registered.swimmers",
                WP_DB_PREFIX,
                WP_DB_PREFIX,
                WP_DB_PREFIX,
                WP_DB_PREFIX,
                WP_DB_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX,
                WP_DB_PREFIX
            )) ;
define("WPST_USERS_WHERE_CLAUSE", WPST_USERS_DEFAULT_WHERE_CLAUSE) ;

/**
 * default constants for GUIDataListConstruction Action Buttons
 */
define("WPST_USERS_ADD_USER", "Add") ;
define("WPST_USERS_UPDATE_USER", "Update") ;
define("WPST_USERS_DELETE_USER", "Delete") ;
define("WPST_USERS_RETIRE_USER", "Retire") ;
define("WPST_USERS_REGISTER_USER", "Register") ;
define("WPST_USERS_PROFILE_USER", "Profile") ;
define("WPST_USERS_EXPORT_CSV", "Export CSV") ;

?>
