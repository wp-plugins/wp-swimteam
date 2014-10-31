<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: users.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Users includes.  These includes define information used in 
 * the Users classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Users
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
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

define('WPST_USERS_COLUMNS', 'DISTINCT u.ID AS userid, user_login,
            m1.meta_value firstname, m2.meta_value lastname,
            CASE
		        WHEN s1.contact1id IS NOT NULL OR s2.contact2id IS NOT NULL THEN \'yes\'
		        ELSE \'no\'
	        END AS swimmers') ;
define('WPST_USERS_TABLES', sprintf('%susers u
            LEFT JOIN %susermeta m1 ON
            (m1.user_id = u.ID AND m1.meta_key = \'first_name\')
            LEFT JOIN %susermeta m2 ON
            (m2.user_id = u.ID AND m2.meta_key = \'last_name\')
            LEFT JOIN %sswimmers s1 ON (s1.contact1id = u.ID)
            LEFT JOIN %sswimmers s2 ON (s2.contact2id = u.ID)',
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WPST_DB_PREFIX,
                WPST_DB_PREFIX
            )) ;
/*
define('WPST_USERS_COLUMNS', 'DISTINCT u.ID AS userid, user_login,
            m1.meta_value firstname, m2.meta_value lastname,
            (NOT ISNULL(m3.contact1id) OR NOT ISNULL(m3.contact2id)) AS swimmers') ;
define('WPST_USERS_TABLES', sprintf('%susers u
            LEFT JOIN %susermeta m1 ON
            (m1.user_id = u.ID AND m1.meta_key = \'first_name\')
            LEFT JOIN %susermeta m2 ON
            (m2.user_id = u.ID AND m2.meta_key = \'last_name\')
            LEFT JOIN %sswimmers m3 ON
            (m3.contact1id = u.ID OR m3.contact2id)',
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX,
                WPST_DB_PREFIX
            )) ;
define('WPST_USERS_COLUMNS2', 'id AS userid, user_login, firstname, lastname, swimmers') ;
define('WPST_USERS_TABLES2', sprintf('%susers
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
*/
define('WPST_USERS_WHERE_CLAUSE', WPST_USERS_DEFAULT_WHERE_CLAUSE) ;

?>
