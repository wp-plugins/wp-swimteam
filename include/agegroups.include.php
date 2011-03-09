<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define age group table name
 */
define("WPST_AGE_GROUP_TABLE", WPST_DB_PREFIX . "agegroups") ;

define("WPST_AGT_MIN_AGE", 0) ;
define("WPST_AGT_MAX_AGE", 18) ;


/**
 * Jobs table enumerated fields
 */
define("WPST_AGT_GENDER_MALE", WPST_GENDER_MALE) ;
define("WPST_AGT_GENDER_FEMALE", WPST_GENDER_FEMALE) ;
define("WPST_AGT_GENDER_BOTH", WPST_GENDER_BOTH) ;


/**
 * default constants for GUIDataListConstruction
 */
define("WPST_AGT_DEFAULT_COLUMNS", "*") ;
define("WPST_AGT_DEFAULT_TABLES", WPST_AGE_GROUP_TABLE) ;
define("WPST_AGT_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * default constants for GUIDataListConstruction Action Buttons
 */
define("WPST_AGT_ADD_AGE_GROUP", "Add") ;
define("WPST_AGT_UPDATE_AGE_GROUP", "Update") ;
define("WPST_AGT_DELETE_AGE_GROUP", "Delete") ;

?>
