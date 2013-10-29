<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: agegroups.include.php 992 2013-06-25 14:10:09Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 992 $
 * @lastmodified $Date: 2013-06-25 10:10:09 -0400 (Tue, 25 Jun 2013) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define age group table name
 */
define("WPST_AGE_GROUP_TABLE", WPST_DB_PREFIX . "agegroups") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_AGT_DEFAULT_COLUMNS", "*") ;
define("WPST_AGT_DEFAULT_TABLES", WPST_AGE_GROUP_TABLE) ;
define("WPST_AGT_DEFAULT_WHERE_CLAUSE", "") ;

?>
