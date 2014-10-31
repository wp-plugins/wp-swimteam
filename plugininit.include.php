<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: plugininit.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage admin
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

define('WPST_PATH', plugin_dir_path(__FILE__)) ;

//  Initialize the phpHtmlLib library.
//  To Do:  Need to check to make sure the plugin is installed.

if (!defined("PHPHTMLLIB_ABSPATH"))
    define("PHPHTMLLIB_ABSPATH", get_option('PHPHTMLLIB_ABSPATH')) ;
if (!defined("PHPHTMLLIB_RELPATH"))
    define("PHPHTMLLIB_RELPATH", get_option('PHPHTMLLIB_RELPATH')) ;

//  Load the phpHtmlLib master include file.

include_once(PHPHTMLLIB_ABSPATH . "/includes.inc") ;

//  Initialize PHP include path to reference
//  the plugin site class and include files.

$classPath = WPST_PATH . "/class" . PATH_SEPARATOR ; 
$includePath = WPST_PATH . "/include" ; 

//  Make sure it works under Windows and Unix.

//ini_set('include_path', $classPath . PATH_SEPARATOR . $includePath . PATH_SEPARATOR . ini_get('include_path')) ;

//  PHP4 introduced some new warning when assigning with references.
//  phpHtmlLib uses this construct so a fair number of warnings are
//  issued.  This is fine for development, but they need to be 
//  surpressed for production.

if (!defined('DEBUG'))
    define('DEBUG', WP_DEBUG) ;

if (!DEBUG)
    error_reporting(error_reporting() & ~E_NOTICE) ;

// Pre-2.6 compatibility
if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('url') . '/wp-content') ;
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content') ;
 
// Guess the location
define('WPST_PLUGIN_PATH',
    WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__))) ;
define('WPST_PLUGIN_URL',
    WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__))) ; 
?>
