<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Swim Team includes.  These includes define constants
 * used the throughout the Wp-SwimTeam plugin.  All constants
 * defined are prefixed with "WPST_" to ensure uniqueness.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 519 $
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

/**
 * Define constants used for the plugin version number
 */
define("WPST_MAJOR_VERSION", '1') ;
define("WPST_MINOR_VERSION", '1') ;
define("WPST_BUILD_NUMBER", '$WCREV$') ;
define("WPST_VERSION", WPST_MAJOR_VERSION .
    "." . WPST_MINOR_VERSION . "." . WPST_BUILD_NUMBER) ;
define("WPST_BUILD_TIME", '$WCNOW$') ;
?>
