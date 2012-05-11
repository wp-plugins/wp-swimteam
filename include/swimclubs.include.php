<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimclubs.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Swim Club includes.  These includes define information used
 * in the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2008 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define age group table name
 */
define("WPST_SWIMCLUBS_TABLE", WPST_DB_PREFIX . "swimclubs") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_SWIMCLUBS_DEFAULT_COLUMNS", "*") ;
define("WPST_SWIMCLUBS_DEFAULT_TABLES", WPST_SWIMCLUBS_TABLE) ;
define("WPST_SWIMCLUBS_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * default constants for GUIDataListConstruction Action Buttons
 */
define("WPST_SWIMCLUBS_PROFILE_SWIMCLUB", "Profile") ;
define("WPST_SWIMCLUBS_ADD_SWIMCLUB", "Add") ;
define("WPST_SWIMCLUBS_UPDATE_SWIMCLUB", "Update") ;
define("WPST_SWIMCLUBS_DELETE_SWIMCLUB", "Delete") ;

?>
