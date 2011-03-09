<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2011 by Mike Walsh for Wp-SwimTeam.
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
