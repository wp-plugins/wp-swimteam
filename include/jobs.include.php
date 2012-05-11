<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: jobs.include.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * Job includes.  These includes define information used in 
 * the Job classes and child classes in the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Jobs
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

/**
 * Jobs table enumerated fields
 */
define("WPST_JOB_DURATION_EVENT", "event") ;
define("WPST_JOB_DURATION_FULL_MEET", "full meet") ;
define("WPST_JOB_DURATION_PARTIAL_MEET", "partial meet") ;
define("WPST_JOB_DURATION_FULL_SEASON", "full season") ;
define("WPST_JOB_DURATION_PARTIAL_SEASON", "partial season") ;

define("WPST_JOB_TYPE_PAID", "paid") ;
define("WPST_JOB_TYPE_VOLUNTEER", "volunteer") ;

/**
 * Define job table name
 */
define("WPST_JOBS_TABLE", WPST_DB_PREFIX . "jobs") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_JOBS_DEFAULT_COLUMNS", "*") ;
define("WPST_JOBS_DEFAULT_TABLES", WPST_JOBS_TABLE) ;
define("WPST_JOBS_DEFAULT_WHERE_CLAUSE", "") ;

/**
 * Define job allocation table name
 */
define("WPST_JOB_ALLOCATIONS_TABLE", WPST_DB_PREFIX . "joballocations") ;

/**
 * Define job assignment table name
 */
define("WPST_JOB_ASSIGNMENTS_TABLE", WPST_DB_PREFIX . "jobassignments") ;

/**
 * default constants for GUIDataListConstruction
 */
define("WPST_JOB_ASSIGNMENTS_DEFAULT_COLUMNS", "*") ;
define("WPST_JOB_ASSIGNMENTS_DEFAULT_TABLES", WPST_JOB_ASSIGNMENTS_TABLE) ;
define("WPST_JOB_ASSIGNMENTS_DEFAULT_WHERE_CLAUSE", "") ;

?>
