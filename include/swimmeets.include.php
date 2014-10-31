<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimmeets.include.php 1071 2014-10-15 13:39:52Z mpwalsh8 $
 *
 * Swim Club includes.  These includes define information used
 * in the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2008 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision: 1071 $
 * @lastmodified $Date: 2014-10-15 09:39:52 -0400 (Wed, 15 Oct 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'include/swimteam.include.php') ;

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
