<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: agegroups.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . '/include/swimteam.include.php') ;

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
