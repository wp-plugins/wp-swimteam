<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: options.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Option includes.  These includes define information used in 
 * the Option classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Options
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

/**
 * Options table enumerated fields
 */

/**
 * Define option table name
 */
define("WPST_OPTIONS_META_TABLE", WPST_DB_PREFIX . "options_meta") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_OPTIONS_META_DEFAULT_COLUMNS", "*") ;
define("WPST_OPTIONS_META_DEFAULT_TABLES", WPST_OPTIONS_META_TABLE) ;
define("WPST_OPTIONS_META_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * default constants for GUIDataListConstruction
 */
//define("WPST_OPTIONS_META_DEFAULT_COLUMNS", "*") ;
//define("WPST_OPTIONS_META_DEFAULT_TABLES", WPST_OPTIONS_META_TABLE) ;
//define("WPST_OPTIONS_META_DEFAULT_WHERE_CLAUSE", "") ;

?>
