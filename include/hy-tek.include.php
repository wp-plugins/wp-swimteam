<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Hy-tek includes.  These includes define information used in 
 * the Hy-tek classes and child classes in the Wp-SwimTeam plugin.
 *
 * This information is based on information posted by Troy Delano in
 * the HY3 Forum (http://groups.google.com/group/sdif-forum?hl=en).
 *
 * See this document for more details:
 *   https://docs.google.com/open?id=0B48BXDxt74TQeDJEQjdlZTdRME9WZUJLY1dVcU45UQ
 *
 * (c) 2012 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Hy-tek
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

include_once('swimteam.include.php') ;

define('WPST_HY3_VERSION', '3.0') ;
define('WPST_HY3_UNUSED', '') ;
define('WPST_HY3_NO_VALUE', '') ;
define('WPST_HY3_SOFTWARE_NAME', 'Hy-Tek, Ltd') ;
//define('WPST_HY3_SOFTWARE_VERSION', WPST_VERSION) ;
define('WPST_HY3_SOFTWARE_VERSION', 'WPST vX.Y.Z') ;

/**
 * Hy-tek Team Manager (TM) and Meet Manager (MM) handle zero
 * times (0.00) differently.  To support as many as possible,
 * a mode is defined in the HY3 options page to control how
 * zero times are handled during HY3 generation.
 *
 */
define('WPST_HY_TEK_USE_BLANKS_LABEL', 'Use Blanks') ;
define('WPST_HY_TEK_USE_ZEROS_LABEL', 'Use Zeros') ;
define('WPST_HY_TEK_USE_NT_LABEL', 'Use "NT" Notation') ;
define('WPST_HY_TEK_USE_BLANKS_VALUE', 1) ;
define('WPST_HY_TEK_USE_ZEROS_VALUE', 2) ;
define('WPST_HY_TEK_USE_NT_VALUE', 3) ;

//  Define the Hy-tek file format labels and extensions
define('WPST_HY_TEK_FILE_FORMAT_HY3_LABEL', 'Hy-tek HY3') ;
define('WPST_HY_TEK_FILE_FORMAT_HYV_LABEL', 'Hy-tek HYV') ;
define('WPST_HY_TEK_FILE_FORMAT_CL2_LABEL', 'Hy-tek CL2') ;
define('WPST_HY_TEK_FILE_FORMAT_RE1_LABEL', 'Hy-tek RE1') ;
define('WPST_HY_TEK_FILE_FORMAT_HY3_VALUE', 'hy3') ;
define('WPST_HY_TEK_FILE_FORMAT_HYV_VALUE', 'hyv') ;
define('WPST_HY_TEK_FILE_FORMAT_RE1_VALUE', 're1') ;
define('WPST_HY_TEK_FILE_FORMAT_CL2_VALUE', 'cl2') ;

/**
 *  File Type Code
 *
 *       02   Meet Entries
 *       03   Team Roster
 *       07   Meet Results (MM to TM)
 */

//  Define the labels used in the GUI
define('WPST_HY3_FTC_MEET_TEAM_ROSTER_LABEL', 'Team Roster') ;
define('WPST_HY3_FTC_MEET_ENTRIES_LABEL', 'Meet Entries') ;
define('WPST_HY3_FTC_MEET_RESULTS_MM_TO_TM_LABEL', 'Meet Results (MM to TM)') ;

//  Define the values used in the records
define('WPST_HY3_FTC_MEET_ENTRIES_VALUE', '02') ;
define('WPST_HY3_FTC_MEET_TEAM_ROSTER_VALUE', '03') ;
define('WPST_HY3_FTC_MEET_RESULTS_MM_TO_TM_VALUE', '07') ;

/**
 *  Team Type Code
 *
 *       AGE  Age Group
 *       HS   High School
 *       COL  College
 *       MAS  Masters
 *       OTH  Other
 *       REC  Recreaction
 */

//  Define the labels used in the GUI
define('WPST_HY3_TTC_AGE_GROUP_LABEL', 'Age Group') ;
define('WPST_HY3_TTC_HIGH_SCHOOL_LABEL', 'High School') ;
define('WPST_HY3_TTC_COLLEGE_LABEL', 'College') ;
define('WPST_HY3_TTC_MASTERS_LABEL', 'Masters') ;
define('WPST_HY3_TTC_OTHERS_LABEL', 'Others') ;
define('WPST_HY3_TTC_RECREATION_LABEL', 'Recreation') ;

//  Define the values used in the records
define('WPST_HY3_TTC_AGE_GROUP_VALUE', 'AGE') ;
define('WPST_HY3_TTC_HIGH_SCHOOL_VALUE', 'HS') ;
define('WPST_HY3_TTC_COLLEGE_VALUE', 'COL') ;
define('WPST_HY3_TTC_MASTERS_VALUE', 'MAS') ;
define('WPST_HY3_TTC_OTHERS_VALUE', 'OTH') ;
define('WPST_HY3_TTC_RECREATION_VALUE', 'REC') ;

/**
 *  Team Registration Code
 *
 *       AUST Australia
 *       BCSS Canada (BCSSA)
 *       NSZF New Zealand
 *       OTH  Other
 *       SSA  South Africa
 *       UK   United Kingdom
 *       USS  USA Swimming
 */

//  Define the labels used in the GUI
define('WPST_HY3_TRC_AUSTRALIA_LABEL', 'Australia') ;
define('WPST_HY3_TRC_CANADA_BCSSA_LABEL', 'Canada (BCSSA)') ;
define('WPST_HY3_TRC_NEW_ZEALAND_LABEL', 'New Zealand') ;
define('WPST_HY3_TRC_OTHER_LABEL', 'Other') ;
define('WPST_HY3_TRC_SOUTH_AFRICA_LABEL', 'South Africa') ;
define('WPST_HY3_TRC_UNITED_KINGDOM_LABEL', 'United Kingdom') ;
define('WPST_HY3_TRC_USA_SWIMMING_LABEL', 'USA Swimming') ;

//  Define the values used in the records
define('WPST_HY3_TRC_AUSTRALIA_VALUE', 'AUST') ;
define('WPST_HY3_TRC_CANADA_BCSSA_VALUE', 'BCSS') ;
define('WPST_HY3_TRC_NEW_ZEALAND_VALUE', 'NZSF') ;
define('WPST_HY3_TRC_OTHER_VALUE', 'OTH') ;
define('WPST_HY3_TRC_SOUTH_AFRICA_VALUE', 'SSA') ;
define('WPST_HY3_TRC_UNITED_KINGDOM_VALUE', 'UK') ;
define('WPST_HY3_TRC_USA_SWIMMING_VALUE', 'USS') ;

//  Define Debug Column Record - used to make sure things are
//  in the correct column - kind of like the old FORTRAN days!
//  Hy-tek HY3 records are always 130 columns with the record 
//  type in colunms 1-2 and a checksm in columsn 129-130.

define('WPST_HY3_COLUMN_DEBUG1', '                                                                                                   1111111111111111111111111111111') ;
define('WPST_HY3_COLUMN_DEBUG2', '         1111111111222222222233333333334444444444555555555566666666667777777777888888888899999999990000000000111111111122222222223') ;
define('WPST_HY3_COLUMN_DEBUG3', '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890') ;

//  HY3 record terminator
define('WPST_HY3_RECORD_TERMINATOR', chr(13) . chr(10)) ;

//  HY3 checksum record
define('WPST_HY3_CHECKSUM_RECORD', '%-128.128s%01.1d%01.1d') ;

//  Define A1 record
define('WPST_HY3_A1_RECORD', 'A1%2.2s%-25.25s%-15.15s%-14.14s%-8.8s%-1.1s%8.8s%-52.52s%-2.2s') ;

//  Define B1 record
define('WPST_HY3_B1_RECORD', 'B1%1.1s%8.8s%-30.30s%-22.22s%-22.22s%-20.20s%2.2s%-10.10s%3.3s%1.1s%8.8s%8.8s%4.4s%8.8s%1.1s%10.10s') ;

//  Define B2 record
define('WPST_HY3_B2_RECORD', 'B2%1.1s%8.8s%-30.30s%-22.22s%-22.22s%-20.20s%2.2s%-10.10s%3.3s%12.12s%28.28s') ;

//  Define C1 record
define('WPST_HY3_C1_RECORD', 'C1%-5.5s%-30.30s%-16.16s%-63.63s%-3.3s%-6.6s%-2.2s') ;

//  Define C2 record
define('WPST_HY3_C2_RECORD', 'C2%-30.30s%-30.30s%-30.30s%-2.2s%-10.10s%-3.3s%-1.1s%-4.4s%-16.16s%-2.2s') ;

//  Define C3 record
define('WPST_HY3_C3_RECORD', 'C3%-29.29s%-20.20s%-20.20s%-20.20s%-36.36s%-2.2s') ;

//  Define D1 record
define('WPST_HY3_D1_RECORD', 'D1%1.1s%5.5s%-20.20s%-20.20s%-20.20s%1.1s%-14.14s%5.5s%8.8s%1.1s%2.2s%5.5s%1.1s%-23.23s%-2.2s') ;

//  Define HY3 D1x record
define('WPST_HY3_D1x_RECORD', 'D1%1.1s%5.5s%-20.20s%-20.20s%-20.20s%1.1s%-14.14s%5.5s%8.8s%1.1s%2.2s%5.5s%1.1s%23.23s%-2.2s') ;

//  Define HY3 D2 record
define('WPST_HY3_D2_RECORD', 'D2%-30.30s%-30.30s%-20.20s          %-2.2s%-10.10s%3.3s') ;

//  Define HY3 D3 record
define('WPST_HY3_D3_RECORD', 'D3%-30.30s%-20.20s%-20.20s%-20.20s%-36.36s') ;

//  Define HY3 D4 record
define('WPST_HY3_D4_RECORD', 'D4%-40.40s%-30.30s%-20.20s          %-2.2s%-10.10s%3.3s') ;

//  Define HY3 D5 record
define('WPST_HY3_D5_RECORD', 'D5%-40.40s%-30.30sX FFFFFFFFFFFFF%-20.20s%-10.10s%-10.10s') ;

//  Define HY3 D6 record
define('WPST_HY3_D6_RECORD', 'D6%-30.30s%-20.20s%-30.30s%-20.20s') ;

//  Define HY3 D7 record
define('WPST_HY3_D7_RECORD', 'D7%-20.20s%-20.20s%-20.20s%-50.50s%-14.14s') ;

//  Define HY3 D8 record
define('WPST_HY3_D8_RECORD', 'D8%-120.120s') ;

//  Define HY3 D9 record
define('WPST_HY3_D9_RECORD', 'D9%-120.120s') ;

//  Define HY3 DA record
define('WPST_HY3_DA_RECORD', 'DA%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s') ;

//  Define HY3 DB record
define('WPST_HY3_DB_RECORD', 'DB%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s') ;

//  Define HY3 DC record
define('WPST_HY3_DC_RECORD', 'DC%128s') ;

//  Define HY3 DD record
define('WPST_HY3_DD_RECORD', 'DD%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s') ;

//  Define HY3 DE record
define('WPST_HY3_DE_RECORD', 'DE%-36.36s%-50.50s%-14.14s%-20.20s') ;

//  Define HY3 DF record
define('WPST_HY3_DF_RECORD', 'DF%-20.20s%-20.20s%-50.50s') ;

?>
