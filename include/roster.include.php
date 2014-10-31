<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: roster.include.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Roster includes.  These includes define information used in 
 * the Roster classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . '/include/swimteam.include.php') ;
require_once(WPST_PATH . '/include/swimmers.include.php') ;
require_once(WPST_PATH . '/include/agegroups.include.php') ;

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
    ".seasonid='%s' AND (" . WPST_AGE_GROUP_TABLE . ".gender = ".
    WPST_SWIMMERS_TABLE . ".gender OR " . WPST_AGE_GROUP_TABLE . ".gender='" .
    WPST_MIXED . "') AND (YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate))) >= " . WPST_AGE_GROUP_TABLE .
    ".minage && YEAR(CURRENT_DATE()) - YEAR(birthdate) -
    (MONTH('%s') < MONTH(birthdate)) - ((MONTH('%s') = MONTH(birthdate)) &
    (DAY('%s') <= DAY(birthdate))) <= " . WPST_AGE_GROUP_TABLE . ".maxage)
    AND " . WPST_AGE_GROUP_TABLE . ".type LIKE '%s'") ;

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
    WPST_AGE_GROUP_TABLE . ".maxage) AND " . WPST_AGE_GROUP_TABLE . ".type = '%s'
    GROUP BY agegroup ORDER BY " . WPST_AGE_GROUP_TABLE . ".minage") ;
?>
