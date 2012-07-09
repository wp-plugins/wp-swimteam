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
define('WPST_HY3_FTC_MERGE_MEET_ENTRIES_VALUE', '01') ;
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

//  Define the values used in the records
define('WPST_HY3_STROKE_CODE_FREESTYLE_LABEL', 'Freestyle') ;
define('WPST_HY3_STROKE_CODE_BACKSTROKE_LABEL', 'Backstroke') ;
define('WPST_HY3_STROKE_CODE_BREASTSTROKE_LABEL', 'Breaststroke') ;
define('WPST_HY3_STROKE_CODE_BUTTERFLY_LABEL', 'Butterfly') ;
define('WPST_HY3_STROKE_CODE_MEDLEY_LABEL', 'Medley') ;
define('WPST_HY3_STROKE_CODE_FREESTYLE_VALUE', 'A') ;
define('WPST_HY3_STROKE_CODE_BACKSTROKE_VALUE', 'B') ;
define('WPST_HY3_STROKE_CODE_BREASTSTROKE_VALUE', 'C') ;
define('WPST_HY3_STROKE_CODE_BUTTERFLY_VALUE', 'D') ;
define('WPST_HY3_STROKE_CODE_MEDLEY_VALUE', 'E') ;

//  Define the labels used in the GUI
define('WPST_HY3_MEET_TYPE_INVITATIONAL_LABEL', 'Invitational') ;
define('WPST_HY3_MEET_TYPE_REGIONAL_LABEL', 'Regional') ;
define('WPST_HY3_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL', 'LSC Championship') ;
define('WPST_HY3_MEET_TYPE_ZONE_LABEL', 'Zone') ;
define('WPST_HY3_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL', 'Zone Championship') ;
define('WPST_HY3_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL', 'National Championship') ;
define('WPST_HY3_MEET_TYPE_JUNIORS_LABEL', 'Juniors') ;
define('WPST_HY3_MEET_TYPE_SENIORS_LABEL', 'Seniors') ;
define('WPST_HY3_MEET_TYPE_DUAL_LABEL', 'Dual') ;
define('WPST_HY3_MEET_TYPE_TIME_TRIALS_LABEL', 'Time Trials') ;
define('WPST_HY3_MEET_TYPE_INTERNATIONAL_LABEL', 'International') ;
define('WPST_HY3_MEET_TYPE_OPEN_LABEL', 'Open') ;
define('WPST_HY3_MEET_TYPE_LEAGUE_LABEL', 'League') ;

//  Define the values used in the records
define('WPST_HY3_MEET_TYPE_INVITATIONAL_VALUE', '1') ;
define('WPST_HY3_MEET_TYPE_REGIONAL_VALUE', '2') ;
define('WPST_HY3_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE', '3') ;
define('WPST_HY3_MEET_TYPE_ZONE_VALUE', '4') ;
define('WPST_HY3_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE', '5') ;
define('WPST_HY3_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE', '6') ;
define('WPST_HY3_MEET_TYPE_JUNIORS_VALUE', '7') ;
define('WPST_HY3_MEET_TYPE_SENIORS_VALUE', '8') ;
define('WPST_HY3_MEET_TYPE_DUAL_VALUE', '9') ;
define('WPST_HY3_MEET_TYPE_TIME_TRIALS_VALUE', '0') ;
define('WPST_HY3_MEET_TYPE_INTERNATIONAL_VALUE', 'A') ;
define('WPST_HY3_MEET_TYPE_OPEN_VALUE', 'B') ;
define('WPST_HY3_MEET_TYPE_LEAGUE_VALUE', 'C') ;

/**
 * Course/Status Code
 *
 * COURSE Code 013   Course/Status code
 *      Please note that there are alternatives for the three types
 *      of pools.  The alpha characters make the file more readable.
 *      Either may be used.
 *      1 or S   Short Course Meters
 *      2 or Y   Short Course Yards
 *      3 or L   Long Course Meters
 *      X        Disqualified
 *
 * NOTE:  This implementation only uses alpha characters for output
 *        but can accomodate the numeric values for input.
 */

//  Define the labels used in the GUI
define('WPST_HY3_COURSE_STATUS_CODE_SCM_LABEL', 'Short Course Meters') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCY_LABEL', 'Short Course Yards') ;
define('WPST_HY3_COURSE_STATUS_CODE_LCM_LABEL', 'Long Course Meters') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCM_ALT_LABEL', 'SC Meters') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCY_ALT_LABEL', 'SC Yards') ;
define('WPST_HY3_COURSE_STATUS_CODE_LCM_ALT_LABEL', 'LC Meters') ;
define('WPST_HY3_COURSE_STATUS_CODE_DQ_LABEL', 'Disqualified') ;

//  Define the values used in the records
define('WPST_HY3_COURSE_STATUS_CODE_SCM_VALUE', 'S') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCY_VALUE', 'Y') ;
define('WPST_HY3_COURSE_STATUS_CODE_LCM_VALUE', 'L') ;
define('WPST_HY3_COURSE_STATUS_CODE_DQ_VALUE', 'X') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCM_ALT_VALUE', '1') ;
define('WPST_HY3_COURSE_STATUS_CODE_SCY_ALT_VALUE', '2') ;
define('WPST_HY3_COURSE_STATUS_CODE_LCM_ALT_VALUE', '3') ;

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
define('WPST_HY3_B1_RECORD', 'B1%-45.45s%-45.45s%8.8s%8.8s%8.8s%4.4s%8.8s%-2.2s') ;

//  Define B2 record
define('WPST_HY3_B2_RECORD', 'B2%92.92s%2.2s%2.2s%1.1s%8.8s%1.1s%22.22s%-2.2s') ;

//  Define C1 record
define('WPST_HY3_C1_RECORD', 'C1%-5.5s%-30.30s%-16.16s%-63.63s%-3.3s%-6.6s%-2.2s') ;

//  Define C2 record
define('WPST_HY3_C2_RECORD', 'C2%-30.30s%-30.30s%-30.30s%-2.2s%-10.10s%-3.3s%-1.1s%-4.4s%-16.16s%-2.2s') ;

//  Define C3 record
define('WPST_HY3_C3_RECORD', 'C3%-30.30s%-20.20s%-20.20s%-20.20s%-36.36s%-2.2s') ;

//  Define D1 record
define('WPST_HY3_D1_RECORD', 'D1%1.1s%5.5s%-20.20s%-20.20s%-20.20s%1.1s%-14.14s%-5.5s%8.8s%1.1s%2.2s%1.1s%4.4s%3.3s%3.3s%1.1s%3.3s%1.1s%3.3s%3.3s%6.6s%-2.2s') ;

//  Define HY3 D2 record
define('WPST_HY3_D2_RECORD', 'D2%-30.30s%-30.30s%-20.20s%10.10s%-2.2s%-10.10s%3.3s%21.21s%-2.2s') ;

//  Define HY3 D3 record
define('WPST_HY3_D3_RECORD', 'D3%-30.30s%-20.20s%-20.20s%-20.20s%-36.36s') ;

//  Define HY3 D4 record
define('WPST_HY3_D4_RECORD', 'D4%-40.40s%-30.30s%-20.20s%10.10s%-2.2s%-10.10s%3.3s%11.11s%-2.2s') ;

//  Define HY3 D5 record
define('WPST_HY3_D5_RECORD', 'D5%-40.40s%-30.30sX%1.1sFFFFFFFFFFFFF%4.4s%-8.8s%8.8s%-10.10s%-10.10s%1.1s%-2.2s') ;

//  Define HY3 D6 record
define('WPST_HY3_D6_RECORD', 'D6%-30.30s%-20.20s%-30.30s%-20.20s%26.26s%-2.2s') ;

//  Define HY3 D7 record
define('WPST_HY3_D7_RECORD', 'D7%-20.20s%-20.20s%-20.20s%-50.50s%-14.14s%2.2s%-2.2s') ;

//  Define HY3 D8 record
define('WPST_HY3_D8_RECORD', 'D8%-120.120s%6.6s%-2.2s') ;

//  Define HY3 D9 record
define('WPST_HY3_D9_RECORD', 'D9%-120.120s%6.6s%-2.2s') ;

//  Define HY3 DA record
define('WPST_HY3_DA_RECORD', 'DA%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%6.6s%-2.2s') ;

//  Define HY3 DB record
define('WPST_HY3_DB_RECORD', 'DB%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%6.6s%-2.2s') ;

//  Define HY3 DC record
define('WPST_HY3_DC_RECORD', 'DC%120s%6.6s%-2.2s') ;

//  Define HY3 DD record
define('WPST_HY3_DD_RECORD', 'DD%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%-20.20s%6.6s%-2.2s') ;

//  Define HY3 DE record
define('WPST_HY3_DE_RECORD', 'DE%-36.36s%-50.50s%-14.14s%-20.20s%6.6s%-2.2s') ;

//  Define HY3 DF record
define('WPST_HY3_DF_RECORD', 'DF%-20.20s%-20.20s%-50.50s%6.6s%-2.2s') ;

//  Define HY3 E1 record
define('WPST_HY3_E1_RECORD', 'E1%1.1s%5.5s%-5.5s%1.1s%1.1s%6.6s%1.1s%3.3s%3.3s%4.4s%6.2f%4.4s%8.8s%1.1s%8.8s%1.1s%8.8s%1.1s%7.7s%1.1s%4.4s%15.15s%1.1s%31.31s%-2.2s') ;
define('WPST_HY3_E1_UNKNOWN_1', '01') ;
define('WPST_HY3_E1_UNKNOWN_2', '0NN') ;
define('WPST_HY3_E1_UNKNOWN_3', 'N') ;

define('WPST_HY3_E1_DEBUG_RECORD', 'E1F    1DEBUGFG    50A 11 12  01  0.00  18    0.00     0.00     0.00    0.00  0NN               N                               08') ;

define('WPST_HY3_F1_RECORD', 'F1%5.5s%1.1s%4.4s%1.1s%1.1s%1.1s%6.6s%1.1s%3.3s%3.3s%4.4s%6.6s%3.3s%1.1s%8.8s%1.1s%8.8s%1.1s%49.49s%-2.2s') ;
define('WPST_HY3_F1_DEBUG_RECORD', 'F1DEBUGB   0FFF   200A 15 18      7.00 64        0Y    0.00Y                                                                    26') ;

define('WPST_HY3_F3_RECORD', 'F3%1.1s%5.5s%-5.5s%1.1s%1.1s%1.1s%5.5s%-5.5s%1.1s%1.1s%1.1s%5.5s%-5.5s%1.1s%1.1s%1.1s%5.5s%-5.5s%1.1s%1.1s%76.76s%-2.2s') ;

define('WPST_HY3_F3_DEBUG_RECORD', 'F3F   64Self F1F   60AdamsF2F   63ArensF3F   65VoltzF4                                                                          63') ;


?>
