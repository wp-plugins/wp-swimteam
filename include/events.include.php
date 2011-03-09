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
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define age group table name
 */
define("WPST_EVENTS_TABLE", WPST_DB_PREFIX . "events") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_EVENTS_DEFAULT_COLUMNS", "*") ;
define("WPST_EVENTS_DEFAULT_TABLES", WPST_EVENTS_TABLE) ;
define("WPST_EVENTS_DEFAULT_WHERE_CLAUSE", "") ;

?>
