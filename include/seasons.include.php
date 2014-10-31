<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: seasons.include.php 1071 2014-10-15 13:39:52Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 1071 $
 * @lastmodified $Date: 2014-10-15 09:39:52 -0400 (Wed, 15 Oct 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'include/swimteam.include.php') ;

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
