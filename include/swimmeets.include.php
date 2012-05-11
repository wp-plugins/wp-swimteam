<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimmeets.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Swim Club includes.  These includes define information used
 * in the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2008 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

include_once("swimteam.include.php") ;

/**
 * Define swim meets table names
 */
define("WPST_SWIMMEETS_TABLE", WPST_DB_PREFIX . "swimmeets") ;
define("WPST_SWIMMEETS_META_TABLE", WPST_DB_PREFIX . "swimmeets_meta") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_SWIMMEETS_DEFAULT_COLUMNS", "*") ;
define("WPST_SWIMMEETS_DEFAULT_TABLES", WPST_SWIMMEETS_TABLE) ;
define("WPST_SWIMMEETS_DEFAULT_WHERE_CLAUSE", "") ;
define("WPST_SWIMMEETS_META_DEFAULT_COLUMNS", "*") ;
define("WPST_SWIMMEETS_META_DEFAULT_TABLES", WPST_SWIMMEETS_META_TABLE) ;
define("WPST_SWIMMEETS_META_DEFAULT_WHERE_CLAUSE", "") ;

?>
