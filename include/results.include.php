<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: results.include.php 1071 2014-10-15 13:39:52Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2011 by Mike Walsh for Wp-SwimTeam.
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
