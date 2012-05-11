<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: seasons.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define age group table name
 */
define("WPST_SEASONS_TABLE", WPST_DB_PREFIX . "seasons") ;

/**
 * Seasons table enumerated fields
 */
define("WPST_SEASONS_SEASON_ACTIVE", WPST_ACTIVE) ;
define("WPST_SEASONS_SEASON_INACTIVE", WPST_INACTIVE) ;
define("WPST_SEASONS_SEASON_HIDDEN", WPST_HIDDEN) ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_SEASONS_DEFAULT_COLUMNS", "*") ;
define("WPST_SEASONS_DEFAULT_TABLES", WPST_SEASONS_TABLE) ;
define("WPST_SEASONS_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * default constants for GUIDataListConstruction Action Buttons
 */
define("WPST_SEASONS_ADD_SEASON", "Add") ;
define("WPST_SEASONS_UPDATE_SEASON", "Update") ;
define("WPST_SEASONS_DELETE_SEASON", "Delete") ;
define("WPST_SEASONS_OPEN_SEASON", "Open Season") ;
define("WPST_SEASONS_CLOSE_SEASON", "Close Season") ;
define("WPST_SEASONS_LOCK_IDS", "Lock Ids") ;
define("WPST_SEASONS_UNLOCK_IDS", "Unlock Ids") ;

?>
