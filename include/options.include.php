<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: options.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Option includes.  These includes define information used in 
 * the Option classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Options
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
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
