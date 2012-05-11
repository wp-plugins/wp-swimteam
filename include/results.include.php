<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: results.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2011 by Mike Walsh for Wp-SwimTeam.
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
define("WPST_RESULTS_TABLE", WPST_DB_PREFIX . "results") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_RESULTS_DEFAULT_COLUMNS", "*") ;
define("WPST_RESULTS_DEFAULT_TABLES", WPST_RESULTS_TABLE) ;
define("WPST_RESULTS_DEFAULT_WHERE_CLAUSE", "") ;

?>
