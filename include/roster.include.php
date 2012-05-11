<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: roster.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Roster includes.  These includes define information used in 
 * the Roster classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

include_once('swimteam.include.php') ;
include_once('swimmers.include.php') ;
include_once('agegroups.include.php') ;

/**
 * Define age group table name
 */
define('WPST_ROSTER_TABLE', WPST_DB_PREFIX . 'roster') ;

/**
 * Seasons table enumerated fields
 */
define('WPST_ROSTER_SWIMMER_ACTIVE', WPST_ACTIVE) ;
define('WPST_ROSTER_SWIMMER_INACTIVE', WPST_INACTIVE) ;
define('WPST_ROSTER_SWIMMER_HIDDEN', WPST_HIDDEN) ;

/**
 * default constants for GUIDataListConstruction
 */

define('WPST_ROSTER_DEFAULT_COLUMNS', '*') ;
define('WPST_ROSTER_DEFAULT_TABLES', WPST_ROSTER_TABLE) ;
define('WPST_ROSTER_DEFAULT_WHERE_CLAUSE', '') ;

/**
 * complex constants for GUIDataListConstruction
 */

define('WPST_ROSTER_COLUMNS', "DISTINCT " . WPST_ROSTER_TABLE .
    ".swimmerid AS swimmerid, " . WPST_SWIMMERS_TABLE .
    ".firstname, " . WPST_SWIMMERS_TABLE . ".lastname, " . WPST_SWIMMERS_TABLE .
    ".nickname, " . WPST_SWIMMERS_TABLE .  ".birthdate, YEAR(CURRENT_DATE()) -
    YEAR(birthdate) - (MONTH(CURRENT_DATE()) < MONTH(birthdate)) -
    ((MONTH(CURRENT_DATE()) = MONTH(birthdate)) & (DAY(CURRENT_DATE()) <
    DAY(birthdate))) AS age, YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate))) AS agegroupage, CONCAT(" .
    WPST_AGE_GROUP_TABLE .  ".gender, ' '," . WPST_AGE_GROUP_TABLE .
    ".minage, ' - '," .  WPST_AGE_GROUP_TABLE . ".maxage) AS agegroup," .
    WPST_ROSTER_TABLE . ".swimmerlabel," . WPST_ROSTER_TABLE .
    ".registered") ;
define('WPST_ROSTER_TABLES', WPST_ROSTER_TABLE . ", " .
    WPST_SWIMMERS_TABLE . ", " . WPST_AGE_GROUP_TABLE) ;
define("WPST_ROSTER_WHERE_CLAUSE", WPST_SWIMMERS_TABLE . ".id = " .
    WPST_ROSTER_TABLE . ".swimmerid AND " . WPST_ROSTER_TABLE .
    ".status = '" .  WPST_ACTIVE . "' AND " . WPST_ROSTER_TABLE .
    ".seasonid='%s' AND " . WPST_AGE_GROUP_TABLE . ".gender = ".
    WPST_SWIMMERS_TABLE . ".gender AND (YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate))) >= " . WPST_AGE_GROUP_TABLE .
    ".minage && YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate))) <= " . WPST_AGE_GROUP_TABLE . ".maxage)") ;

define('WPST_ROSTER_COUNT_COLUMNS', " COUNT(YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate)))) AS agegroupcount, CONCAT("
    . WPST_AGE_GROUP_TABLE .  ".gender, ' '," . WPST_AGE_GROUP_TABLE .
    ".minage, ' - '," .  WPST_AGE_GROUP_TABLE . ".maxage) AS agegroup") ;
define('WPST_ROSTER_COUNT_TABLES', WPST_ROSTER_TABLE . ", " .
    WPST_SWIMMERS_TABLE . ", " . WPST_AGE_GROUP_TABLE) ;
define('WPST_ROSTER_COUNT_WHERE_CLAUSE', WPST_SWIMMERS_TABLE . ".id = " .
    WPST_ROSTER_TABLE . ".swimmerid AND " . WPST_ROSTER_TABLE .
    ".status = '" .  WPST_ACTIVE . "' AND " . WPST_ROSTER_TABLE .
    ".seasonid='%s' AND " . WPST_AGE_GROUP_TABLE . ".gender = ".
    WPST_SWIMMERS_TABLE . ".gender AND ( YEAR(CURRENT_DATE()) -
    YEAR(birthdate) - (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') =
    MONTH(birthdate)) & (DAY('%s') <= DAY(birthdate))) >= " .
    WPST_AGE_GROUP_TABLE .  ".minage && YEAR(CURRENT_DATE()) -
    YEAR(birthdate) - (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') =
    MONTH(birthdate)) & (DAY('%s') <= DAY(birthdate))) <= " .
    WPST_AGE_GROUP_TABLE . ".maxage) GROUP BY agegroup
    ORDER BY " . WPST_AGE_GROUP_TABLE . ".minage") ;
?>
