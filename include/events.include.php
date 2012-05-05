<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Swim Club includes.  These includes define information used
 * in the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2008 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

include_once('swimteam.include.php') ;

/**
 * Define events table name
 */
define('WPST_EVENTS_TABLE', WPST_DB_PREFIX . 'events') ;

/**
 * default constants for GUIDataListConstruction
 */
define('WPST_EVENTS_DEFAULT_COLUMNS', '*') ;
define('WPST_EVENTS_DEFAULT_TABLES', WPST_EVENTS_TABLE) ;
define('WPST_EVENTS_DEFAULT_WHERE_CLAUSE', '') ;

/**
 * Define events group table name
 */
define('WPST_EVENT_GROUPS_TABLE', WPST_DB_PREFIX . 'eventgroups') ;

/**
 * default constants for GUIDataListConstruction
 */
define('WPST_EVENT_GROUPS_DEFAULT_COLUMNS', '*') ;
define('WPST_EVENT_GROUPS_DEFAULT_TABLES', WPST_EVENT_GROUPS_TABLE) ;
define('WPST_EVENT_GROUPS_DEFAULT_WHERE_CLAUSE', '') ;

define('WPST_EVENT_GROUPS_WITH_EVENT_COUNT_COLUMNS', 
    WPST_EVENT_GROUPS_TABLE . '.*,' .
    '(SELECT COUNT(' . WPST_EVENTS_TABLE . '.eventid) FROM ' .
    WPST_EVENTS_TABLE . ' WHERE ' . WPST_EVENT_GROUPS_TABLE . '.eventgroupid = ' .
    WPST_EVENTS_TABLE . '.eventgroupid) AS eventcount') ;

/**
 * Extended Event definitions
 */
define('WPST_EXTENDED_EVENTS_COLUMNS', WPST_EVENTS_TABLE . '.*, ' . WPST_AGE_GROUP_TABLE . '.*') ;
define('WPST_EXTENDED_EVENTS_TABLES', WPST_EVENTS_TABLE . ', ' . WPST_AGE_GROUP_TABLE) ;
define('WPST_EXTENDED_EVENTS_WHERE_CLAUSE', WPST_EVENTS_TABLE . '.agegroupid = ' . WPST_AGE_GROUP_TABLE . '.id') ;

?>
