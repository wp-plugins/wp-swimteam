<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimclubs.include.php 1071 2014-10-15 13:39:52Z mpwalsh8 $
 *
 * Swim Club includes.  These includes define information used
 * in the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2008 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision: 1071 $
 * @lastmodified $Date: 2014-10-15 09:39:52 -0400 (Wed, 15 Oct 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'include/swimteam.include.php') ;

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
