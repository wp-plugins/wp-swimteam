<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimmers.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
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
 * Define swimmers table name
 */
define("WPST_SWIMMERS_TABLE", WPST_DB_PREFIX . "swimmers") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_SWIMMERS_DEFAULT_COLUMNS", "*") ;
define("WPST_SWIMMERS_DEFAULT_TABLES", WPST_SWIMMERS_TABLE) ;
define("WPST_SWIMMERS_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * swimmer columns along with age and age group calculation
 */
define("WPST_SWIMMERS_COLUMNS", "*, YEAR(CURRENT_DATE()) -
    YEAR(birthdate) - (MONTH(CURRENT_DATE()) < MONTH(birthdate)) -
    ((MONTH(CURRENT_DATE()) = MONTH(birthdate)) & (DAY(CURRENT_DATE()) <
    DAY(birthdate))) AS age, YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') =
    MONTH(birthdate)) & (DAY('%s') <= DAY(birthdate))) AS
    agegroupage") ;


/**
 * default constants for GUIDataListConstruction Action Buttons
 */
define("WPST_SWIMMERS_PROFILE_SWIMMER", "Profile") ;
define("WPST_SWIMMERS_ADD_SWIMMER", "Add") ;
define("WPST_SWIMMERS_UPDATE_SWIMMER", "Update") ;
define("WPST_SWIMMERS_DELETE_SWIMMER", "Delete") ;
define("WPST_SWIMMERS_RETIRE_SWIMMER", "Retire") ;
define("WPST_SWIMMERS_REGISTER_SWIMMER", "Register") ;
define("WPST_SWIMMERS_UNREGISTER_SWIMMER", "Unregister") ;
define("WPST_SWIMMERS_EXPORT_SD3_LSC_REGISTRATION", "Export LSC SD3 Registration") ;
?>
