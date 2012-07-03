<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimteam.include.php 947 2012-07-02 21:16:51Z mpwalsh8 $
 *
 * Swim Team includes.  These includes define constants
 * used the throughout the Wp-SwimTeam plugin.  All constants
 * defined are prefixed with 'WPST_' to ensure uniqueness.
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @package SwimTeam
 * @subpackage Admin
 * @version $Revision: 947 $
 * @lastmodified $Date: 2012-07-02 17:16:51 -0400 (Mon, 02 Jul 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('version.include.php') ;

define('WPST_DEBUG', false) ;

/**
 * Constants used across the plugin
 */
define('WPST_NO', 'no') ;
define('WPST_YES', 'yes') ;
define('WPST_NONE', 'none') ;
define('WPST_NA', 'n/a') ;
define('WPST_OPEN', 'open') ;
define('WPST_CLOSED', 'closed') ;
define('WPST_BOTH', 'both') ;
define('WPST_GENDER_MALE', 'male') ;
define('WPST_GENDER_FEMALE', 'female') ;
define('WPST_GENDER_BOTH', WPST_BOTH) ;
define('WPST_AGE_MIN', 0) ;
define('WPST_AGE_MAX', 125) ;
define('WPST_SDIF_MAX_EVENTS', 7) ;
define('WPST_ENABLED', 'enabled') ;
define('WPST_DISABLED', 'disabled') ;
define('WPST_REQUIRED', 'required') ;
define('WPST_OPTIONAL', 'optional') ;
define('WPST_YES_NO', 'yes no') ;
define('WPST_NO_YES', 'no yes') ;
define('WPST_EMAIL_REQUIRED', 'e-mail (required)') ;
define('WPST_URL_REQUIRED', 'url (required)') ;
define('WPST_EMAIL_OPTIONAL', 'e-mail (optional)') ;
define('WPST_URL_OPTIONAL', 'url (optional)') ;
define('WPST_CLOTHING_SIZE', 'clothing size') ;
define('WPST_YARDS', 'yards') ;
define('WPST_METERS', 'meters') ;
define('WPST_DEFAULT_POOL_LENGTH', 25) ;
define('WPST_DEFAULT_POOL_LANES', 6) ;
define('WPST_US_ONLY', 'united states') ;
define('WPST_EU_ONLY', 'european union') ;
define('WPST_INTERNATIONAL', 'international') ;
define('WPST_ACTIVE', 'active') ;
define('WPST_INACTIVE', 'inactive') ;
define('WPST_HIDDEN', 'hidden') ;
define('WPST_PUBLIC', 'public') ;
define('WPST_PRIVATE', 'private') ;
define('WPST_UNKNOWN', 'unknown') ;
define('WPST_USER', 'user') ;
define('WPST_ADMIN', 'admin') ;
define('WPST_NULL_ID', 0) ;
define('WPST_NULL_STRING', '') ;
define('WPST_LOCKED', 'locked') ;
define('WPST_UNLOCKED', 'unlocked') ;
define('WPST_FROZEN', 'frozen') ;
define('WPST_SIMPLE_NUMERIC', 'Simple Numeric Squence') ;
define('WPST_AGE_GROUP_PREFIX_NUMERIC', 'Age Group Prefix + Numeric') ;
define('WPST_USA_SWIMMING', 'USA-Swimming') ;
define('WPST_WPST_ID', 'Wp-SwimTeam Id') ;
define('WPST_AGE_GROUP_PREFIX_WPST_ID', 'Age Group Prefix + Wp-SwimTeam Id') ;
define('WPST_CUSTOM', 'Custom') ;
define('WPST_GENERATE_CSV', 'CSV File') ;
define('WPST_GENERATE_PDF', 'PDF File') ;
define('WPST_GENERATE_STATIC_WEB_PAGE', 'Static Web Page') ;
define('WPST_GENERATE_DYNAMIC_WEB_PAGE', 'Dynamic Web Page') ;
define('WPST_GENERATE_PRINTABLE_WEB_PAGE', 'Printable Web Page') ;
define('WPST_SORT_BY_NAME', 'Sort by Name') ;
define('WPST_SORT_BY_SWIMMER_LABEL', 'Sort by Swimmer Label') ;
define('WPST_SORT_CHRONOLOGICALLY', 'Sort Chronologically') ;
define('WPST_AWAY', 'away') ;
define('WPST_HOME', 'home') ;
define('WPST_DUAL_MEET', 'dual meet') ;
define('WPST_TIME_TRIAL', 'time trial') ;
define('WPST_INVITATIONAL', 'invitational') ;
define('WPST_RELAY_CARNIVAL', 'relay carnival') ;
define('WPST_HTML', 'html') ;
define('WPST_TEXT', 'plain text') ;
define('WPST_OPT_IN', 'opt in') ;
define('WPST_OPT_OUT', 'opt out') ;
define('WPST_FULL', 'full') ;
define('WPST_PARTIAL', 'partial') ;
define('WPST_SEASON', 'season') ;
define('WPST_EVENT', 'event') ;
define('WPST_STROKE', 'stroke') ;
define('WPST_SWIMMEET', 'Swim Meet') ;
define('WPST_DASHBOARD_PAGE', 'dashboard page') ;
define('WPST_SWIMTEAM_OVERVIEW_PAGE', 'swim team overview page') ;
define('WPST_HOME_PAGE', 'home page') ;
define('WPST_PREVIOUS_PAGE', 'previous page') ;
define('WPST_MATCH_SWIMMER_ID', 'match swimmer id') ;
define('WPST_MATCH_SWIMMER_NAME', 'match swimmer name') ;
define('WPST_MATCH_SWIMMER_NAME_AND_ID', 'match swimmer name and id') ;

/**
 * WordPress permission mapping
 */
define('WPST_ADMIN_PERMISSION', 8) ;
define('WPST_EDITOR_PERMISSION', 5) ;
define('WPST_AUTHOR_PERMISSION', 2) ;
define('WPST_CONTRIBUTOR_PERMISSION', 1) ;
define('WPST_SUBSCRIBER_PERMISSION', 0) ;

/**
 * Actions used across the plugin
 */
define('WPST_ACTION_SELECT_ACTION', 'Select Action') ;
define('WPST_ACTION_ADD', 'Add') ;
define('WPST_ACTION_UPDATE', 'Update') ;
define('WPST_ACTION_IGNORE', 'Ignore') ;
define('WPST_ACTION_REPLACE', 'Replace') ;
define('WPST_ACTION_MANAGE', 'Manage') ;
define('WPST_ACTION_REORDER', 'Reorder') ;
define('WPST_ACTION_PROFILE', 'Profile') ;
define('WPST_ACTION_DETAILS', 'Details') ;
define('WPST_ACTION_RESULTS', 'Results') ;
define('WPST_ACTION_SCRATCH', 'Scratch') ;
define('WPST_ACTION_REGISTER', 'Register') ;
define('WPST_ACTION_UNREGISTER', 'Unregister') ;
define('WPST_ACTION_SCRATCH_REPORT', 'Scratch Report') ;
define('WPST_ACTION_DELETE', 'Delete') ;
define('WPST_ACTION_OBSOLETE', 'Obsolete') ;
define('WPST_ACTION_ASSIGN_LABEL', 'Assign Label') ;
define('WPST_ACTION_ASSIGN_LABELS', 'Assign Labels') ;
define('WPST_ACTION_EXPORT_CSV', 'Export CSV') ;
define('WPST_ACTION_EXPORT_SDIF', 'Export SDIF') ;
define('WPST_ACTION_EXPORT_MMRE', 'Export MM Registration') ;
define('WPST_ACTION_EXPORT_HY3', 'Export HY3') ;
define('WPST_ACTION_EXPORT_ENTRIES', 'Export Entries') ;
define('WPST_ACTION_DIRECTORY', 'Directory') ;
define('WPST_ACTION_EXECUTE', 'Execute') ;
define('WPST_ACTION_OPT_IN', 'Opt In') ;
define('WPST_ACTION_OPT_OUT', 'Opt Out') ;
define('WPST_ACTION_EVENTS', 'Events') ;
define('WPST_ACTION_EVENTS_LOAD', 'Load Events') ;
define('WPST_ACTION_EVENTS_ADD', 'Add Event') ;
define('WPST_ACTION_EVENTS_IMPORT', 'Import Events') ;
define('WPST_ACTION_EVENTS_EXPORT', 'Export Events') ;
define('WPST_ACTION_EVENTS_PROFILE', 'Profile Event') ;
define('WPST_ACTION_EVENTS_UPDATE', 'Update Event') ;
define('WPST_ACTION_EVENTS_REORDER', 'Reorder Events') ;
define('WPST_ACTION_EVENTS_DELETE', 'Delete Event') ;
define('WPST_ACTION_EVENTS_DELETE_ALL', 'Delete All Events') ;
define('WPST_ACTION_EVENTS_MANAGE', 'Manage Events') ;
define('WPST_ACTION_EVENTS_REPORT', 'Report Events') ;
define('WPST_ACTION_OPEN_SEASON', 'Open Season') ;
define('WPST_ACTION_CLOSE_SEASON', 'Close Season') ;
define('WPST_ACTION_LOCK_IDS', 'Lock Ids') ;
define('WPST_ACTION_UNLOCK_IDS', 'Unlock Ids') ;
define('WPST_ACTION_IMPORT_EVENTS', 'Import Events') ;
define('WPST_ACTION_IMPORT_RESULTS', 'Import Results') ;
define('WPST_ACTION_EXPORT_RESULTS', 'Export Results') ;
define('WPST_ACTION_GLOBAL_UPDATE', 'Global Update') ;
define('WPST_ACTION_JOBS', 'Jobs') ;
define('WPST_ACTION_JOB_REMINDERS', 'Job Reminders') ;
define('WPST_ACTION_ASSIGN_JOBS', 'Assign Jobs') ;
define('WPST_ACTION_DEFINE_JOBS', 'Define Jobs') ;
define('WPST_ACTION_DEFINE', 'Define') ;
define('WPST_ACTION_ALLOCATE', 'Allocate') ;
define('WPST_ACTION_REALLOCATE', 'Reallocate') ;
define('WPST_ACTION_DEALLOCATE', 'Deallocate') ;
define('WPST_ACTION_ASSIGN', 'Assign') ;
define('WPST_ACTION_SIGN_UP', 'Sign Up') ;

/**
 * Default values, stored in options table
 */
define('WPST_DEFAULT_GENDER', WPST_GENDER_BOTH) ;
define('WPST_DEFAULT_MIN_AGE', WPST_AGE_MIN) ;
define('WPST_DEFAULT_MAX_AGE', 18) ;
define('WPST_DEFAULT_AGE_CUTOFF_DAY', 1) ;
define('WPST_DEFAULT_AGE_CUTOFF_MONTH', 7) ;
define('WPST_DEFAULT_GENDER_LABEL_MALE', 'boy') ;
define('WPST_DEFAULT_GENDER_LABEL_FEMALE', 'girl') ;
define('WPST_DEFAULT_MEASUREMENT_UNITS', WPST_METERS) ;
define('WPST_DEFAULT_AUTO_REGISTER', WPST_YES) ;
define('WPST_DEFAULT_REGISTRATION_SYSTEM', WPST_CLOSED) ;
define('WPST_DEFAULT_SWIMMER_LABEL_FORMAT', WPST_SIMPLE_NUMERIC) ;
define('WPST_DEFAULT_SWIMMER_LABEL_FORMAT_CODE', '%-05s') ;
define('WPST_DEFAULT_SWIMMER_LABEL_INITIAL_VALUE', 0) ;
define('WPST_DEFAULT_JOB_SIGN_UP', WPST_USER) ;
define('WPST_DEFAULT_JOB_CREDITS', 5) ;
define('WPST_DEFAULT_JOB_CREDITS_REQUIRED', 0) ;
define('WPST_DEFAULT_JOB_EMAIL_FORMAT', WPST_HTML) ;
define('WPST_DEFAULT_GEOGRAPHY', WPST_INTERNATIONAL) ;
define('WPST_DEFAULT_POSTAL_CODE_LABEL', 'Postal Code') ;
define('WPST_DEFAULT_STATE_OR_PROVINCE_LABEL', 'State or Province') ;
define('WPST_DEFAULT_PRIMARY_PHONE_LABEL', 'Home Phone') ;
define('WPST_DEFAULT_SECONDARY_PHONE_LABEL', 'Mobile Phone') ;
define('WPST_DEFAULT_OPT_IN_LABEL', 'Register') ;
define('WPST_DEFAULT_OPT_OUT_LABEL', 'Scratch') ;
define('WPST_DEFAULT_OPT_IN_OPT_OUT_EMAIL_FORMAT', WPST_HTML) ;
define('WPST_DEFAULT_OPT_IN_OPT_OUT_MODE', WPST_BOTH) ;
define('WPST_DEFAULT_OPT_IN_OPT_OUT_USAGE_MODEL', WPST_STROKE) ;
define('WPST_DEFAULT_ENABLE_VERBOSE_MESSAGES', WPST_NO) ;
define('WPST_DEFAULT_ENABLE_GOOGLE_MAPS', WPST_NO) ;
define('WPST_DEFAULT_GOOGLE_API_KEY', 'Google API Key') ;
define('WPST_DEFAULT_GDL_ROWS_TO_DISPLAY', 20) ;
define('WPST_DEFAULT_TIME_FORMAT', 'H:i') ;
define('WPST_DEFAULT_USER_OPTION_COUNT', 5) ;
define('WPST_DEFAULT_USER_OPTION', WPST_DISABLED) ;
define('WPST_DEFAULT_USER_OPTION_LABEL', 'Optional Field #') ;
define('WPST_DEFAULT_SWIMMER_OPTION_COUNT', 5) ;
define('WPST_DEFAULT_SWIMMER_OPTION', WPST_DISABLED) ;
define('WPST_DEFAULT_SWIMMER_OPTION_LABEL', 'Optional Field #') ;
define('WPST_DEFAULT_REG_PREFIX_LABEL', WPST_NULL_STRING) ;
define('WPST_DEFAULT_REG_FEE_LABEL', 'Registation Fee') ;
define('WPST_DEFAULT_REG_FEE_CURRENCY_LABEL', '$') ;
define('WPST_DEFAULT_REG_FEE_AMOUNT', '75') ;
define('WPST_DEFAULT_LOGIN_REDIRECT', WPST_NONE) ;

//  Define a prefix for the options stored in the options table
define('WPST_OPTION_PREFIX', 'st_') ;

//  Define the option fields and their default values
define('WPST_OPTION_GENDER', WPST_OPTION_PREFIX . 'gender') ;
define('WPST_OPTION_MIN_AGE', WPST_OPTION_PREFIX . 'min_age') ;
define('WPST_OPTION_MAX_AGE', WPST_OPTION_PREFIX . 'max_age') ;
define('WPST_OPTION_AGE_CUTOFF_MONTH', WPST_OPTION_PREFIX . 'cutoff_month') ;
define('WPST_OPTION_AGE_CUTOFF_DAY', WPST_OPTION_PREFIX . 'cutoff_day') ;
define('WPST_OPTION_GENDER_LABEL_MALE', WPST_OPTION_PREFIX . 'gender_label_male') ;
define('WPST_OPTION_GENDER_LABEL_FEMALE', WPST_OPTION_PREFIX . 'gender_label_female') ;
define('WPST_OPTION_MEASUREMENT_UNITS', WPST_OPTION_PREFIX . 'measurement_units') ;
define('WPST_OPTION_JOB_SIGN_UP', WPST_OPTION_PREFIX . 'job_sign_up') ;
define('WPST_OPTION_JOB_CREDITS', WPST_OPTION_PREFIX . 'job_credits') ;
define('WPST_OPTION_JOB_CREDITS_REQUIRED', WPST_OPTION_PREFIX . 'job_credits_required') ;
define('WPST_OPTION_JOB_EMAIL_ADDRESS', WPST_OPTION_PREFIX . 'job_email_address') ;
define('WPST_OPTION_JOB_EMAIL_FORMAT', WPST_OPTION_PREFIX . 'job_email_format') ;
define('WPST_OPTION_JOB_EXPECTATIONS_URL', WPST_OPTION_PREFIX . 'job_expecations_url') ;
define('WPST_OPTION_AUTO_REGISTER', WPST_OPTION_PREFIX . 'auto_register') ;
define('WPST_OPTION_REGISTRATION_SYSTEM', WPST_OPTION_PREFIX . 'registration_system') ;
define('WPST_OPTION_GEOGRAPHY', WPST_OPTION_PREFIX . 'geography') ;
define('WPST_OPTION_SWIMMER_LABEL_FORMAT', WPST_OPTION_PREFIX . 'swimmer_label_format') ;
define('WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE', WPST_OPTION_PREFIX . 'swimmer_label_format_code') ;
define('WPST_OPTION_SWIMMER_LABEL_INITIAL_VALUE', WPST_OPTION_PREFIX . 'swimmer_label_initial_value') ;
define('WPST_OPTION_ENABLE_VERBOSE_MESSAGES', WPST_OPTION_PREFIX . 'enable_verbose_messages') ;
define('WPST_OPTION_ENABLE_GOOGLE_MAPS', WPST_OPTION_PREFIX . 'enable_google_maps') ;
define('WPST_OPTION_GOOGLE_API_KEY', WPST_OPTION_PREFIX . 'google_api_key') ;
define('WPST_OPTION_GDL_ROWS_TO_DISPLAY', WPST_OPTION_PREFIX . 'gdl_rows_to_display') ;
define('WPST_OPTION_TIME_FORMAT', WPST_OPTION_PREFIX . 'time_format') ;
define('WPST_OPTION_OPT_IN_LABEL', WPST_OPTION_PREFIX . 'opt_in_label') ;
define('WPST_OPTION_OPT_OUT_LABEL', WPST_OPTION_PREFIX . 'opt_out_label') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_ADDRESS', WPST_OPTION_PREFIX . 'opt_in_opt_out_email_address') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT', WPST_OPTION_PREFIX . 'opt_in_opt_out_email_format') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_EVENTS', WPST_OPTION_PREFIX . 'opt_in_opt_out_events') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_STROKES', WPST_OPTION_PREFIX . 'opt_in_opt_out_strokes') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_MODE', WPST_OPTION_PREFIX . 'opt_in_opt_out_mode') ;
define('WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL', WPST_OPTION_PREFIX . 'opt_in_opt_out_usage_model') ;
define('WPST_OPTION_REG_PREFIX_LABEL', WPST_OPTION_PREFIX . 'reg_prefix_label') ;
define('WPST_OPTION_REG_FEE_LABEL', WPST_OPTION_PREFIX . 'reg_fee_label') ;
define('WPST_OPTION_REG_FEE_CURRENCY_LABEL', WPST_OPTION_PREFIX . 'reg_fee_currency_label') ;
define('WPST_OPTION_REG_FEE_AMOUNT', WPST_OPTION_PREFIX . 'reg_fee_amount') ;
define('WPST_OPTION_REG_EMAIL', WPST_OPTION_PREFIX . 'reg_email') ;
define('WPST_OPTION_REG_EMAIL_FORMAT', WPST_OPTION_PREFIX . 'reg_email_format') ;
define('WPST_OPTION_REG_TOU_URL', WPST_OPTION_PREFIX . 'reg_tou_url') ;
define('WPST_OPTION_REG_FEE_URL', WPST_OPTION_PREFIX . 'reg_fee_url') ;
define('WPST_OPTION_STATE_OR_PROVINCE_LABEL', WPST_OPTION_PREFIX . 'stateorprovince_label') ;
define('WPST_OPTION_POSTAL_CODE_LABEL', WPST_OPTION_PREFIX . 'postalcode_label') ;

//  Define the option fields for the extended user profile
define('WPST_OPTION_USONLY', WPST_OPTION_PREFIX . 'usonly') ;
define('WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL', WPST_OPTION_PREFIX . 'user_stateorprovince_label') ;
define('WPST_OPTION_USER_POSTAL_CODE_LABEL', WPST_OPTION_PREFIX . 'user_postalcode_label') ;
define('WPST_OPTION_USER_PRIMARY_PHONE_LABEL', WPST_OPTION_PREFIX . 'user_primary_phone_label') ;
define('WPST_OPTION_USER_SECONDARY_PHONE_LABEL', WPST_OPTION_PREFIX . 'user_secondary_phone_label') ;
define('WPST_OPTION_USER_OPTION_COUNT', WPST_OPTION_PREFIX . 'user_option_count') ;
define('WPST_OPTION_LOGIN_REDIRECT', WPST_OPTION_PREFIX . 'login_redirect') ;

//  Define the user options - how many?
//  Define constants based on default or WordPress option.

$options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

for ($oc = 1 ; $oc <= $options ; $oc++)
{
    define('WPST_OPTION_USER_OPTION' .  $oc,
        WPST_OPTION_PREFIX . 'user_option' . $oc) ;
    define('WPST_OPTION_USER_OPTION' .  $oc . '_LABEL',
        WPST_OPTION_PREFIX . 'user_option' . $oc . '_label') ;
    define('WPST_OPTION_USER_OPTION' .  $oc . '_MODE',
        WPST_OPTION_PREFIX . 'user_option' . $oc . '_mode') ;
}

//  Define the option fields for the extended swimmer profile
define('WPST_OPTION_SWIMMER_USONLY', WPST_OPTION_PREFIX . 'usonly') ;
//define('WPST_OPTION_SWIMMER_STATE_OR_PROVINCE_LABEL', WPST_OPTION_PREFIX . 'swimmer_stateorprovince_label') ;
//define('WPST_OPTION_SWIMMER_POSTAL_CODE_LABEL', WPST_OPTION_PREFIX . 'swimmer_postalcode_label') ;
define('WPST_OPTION_SWIMMER_OPTION_COUNT', WPST_OPTION_PREFIX . 'swimmer_option_count') ;

//  Define the swimmer options - how many?
//  Define constants based on default or WordPress option.

$options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

for ($oc = 1 ; $oc <= $options ; $oc++)
{
    define('WPST_OPTION_SWIMMER_OPTION' .  $oc,
        WPST_OPTION_PREFIX . 'swimmer_option' . $oc) ;
    define('WPST_OPTION_SWIMMER_OPTION' .  $oc . '_LABEL',
        WPST_OPTION_PREFIX . 'swimmer_option' . $oc . '_label') ;
    define('WPST_OPTION_SWIMMER_OPTION' .  $oc . '_MODE',
        WPST_OPTION_PREFIX . 'swimmer_option' . $oc . '_mode') ;
}

// Define Team Profile which is stored in Wordpress' options table
define('WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE', 'Unknown') ;
define('WPST_OPTION_TEAM_NAME', WPST_OPTION_PREFIX . 'team_name') ;
define('WPST_OPTION_TEAM_CLUB_OR_POOL_NAME', WPST_OPTION_PREFIX . 'team_club_or_pool_name') ;
define('WPST_OPTION_TEAM_STREET_1', WPST_OPTION_PREFIX . 'team_street_1') ;
define('WPST_OPTION_TEAM_STREET_2', WPST_OPTION_PREFIX . 'team_street_2') ;
define('WPST_OPTION_TEAM_STREET_3', WPST_OPTION_PREFIX . 'team_street_3') ;
define('WPST_OPTION_TEAM_CITY', WPST_OPTION_PREFIX . 'team_city') ;
define('WPST_OPTION_TEAM_STATE_OR_PROVINCE', WPST_OPTION_PREFIX . 'team_state_or_province') ;
define('WPST_OPTION_TEAM_POSTAL_CODE', WPST_OPTION_PREFIX . 'team_postal_code') ;
define('WPST_OPTION_TEAM_COUNTRY', WPST_OPTION_PREFIX . 'team_country') ;
define('WPST_OPTION_TEAM_PRIMARY_PHONE', WPST_OPTION_PREFIX . 'team_primary_phone') ;
define('WPST_OPTION_TEAM_SECONDARY_PHONE', WPST_OPTION_PREFIX . 'team_secondary_phone') ;
define('WPST_OPTION_TEAM_EMAIL_ADDRESS', WPST_OPTION_PREFIX . 'team_email_address') ;
define('WPST_OPTION_TEAM_WEB_SITE', WPST_OPTION_PREFIX . 'team_web_site') ;
define('WPST_OPTION_TEAM_POOL_LENGTH', WPST_OPTION_PREFIX . 'team_pool_length') ;
define('WPST_OPTION_TEAM_POOL_LANES', WPST_OPTION_PREFIX . 'team_pool_lanes') ;
define('WPST_OPTION_TEAM_POOL_MEASUREMENT_UNITS', WPST_OPTION_PREFIX . 'team_pool_measurement_units') ;
define('WPST_OPTION_TEAM_COACH_USER_ID', WPST_OPTION_PREFIX . 'team_coach_user_id') ;

// Define SDIF options stored in the Wordpress option table
define('WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID', 'Wp-SwimTeam Id') ;
define('WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL', 'Swimmer Label') ;
define('WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING', 'USA Swimming') ;
define('WPST_OPTION_SDIF_DEFAULT_VALUE', '') ;
define('WPST_OPTION_SDIF_ORG_CODE', WPST_OPTION_PREFIX . 'sdif_org_code') ;
define('WPST_OPTION_SDIF_TEAM_CODE', WPST_OPTION_PREFIX . 'sdif_team_code') ;
define('WPST_OPTION_SDIF_LSC_CODE', WPST_OPTION_PREFIX . 'sdif_lsc_code') ;
define('WPST_OPTION_SDIF_COUNTRY_CODE', WPST_OPTION_PREFIX . 'sdif_country_code') ;
define('WPST_OPTION_SDIF_REGION_CODE', WPST_OPTION_PREFIX . 'sdif_region_code') ;
define('WPST_OPTION_SDIF_SWIMMER_ID_FORMAT', WPST_OPTION_PREFIX . 'sdif_swimmer_id_format') ;
define('WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME', WPST_OPTION_PREFIX . 'sdif_swimmer_use_nickname') ;
define('WPST_OPTION_SDIF_SWIMMER_USE_AGE_GROUP_AGE', WPST_OPTION_PREFIX . 'sdif_swimmer_use_age_group_age') ;

/**
 * Define clothing sizes
 */
define('WPST_CLOTHING_SIZE_YS_LABEL', 'Youth Small') ;
define('WPST_CLOTHING_SIZE_YM_LABEL', 'Youth Medium') ;
define('WPST_CLOTHING_SIZE_YL_LABEL', 'Youth Large') ;
define('WPST_CLOTHING_SIZE_YXL_LABEL', 'Youth X-Large') ;
define('WPST_CLOTHING_SIZE_S_LABEL', 'Adult Small') ;
define('WPST_CLOTHING_SIZE_M_LABEL', 'Adult Medium') ;
define('WPST_CLOTHING_SIZE_L_LABEL', 'Adult Large') ;
define('WPST_CLOTHING_SIZE_XL_LABEL', 'Adult X-Large') ;
define('WPST_CLOTHING_SIZE_2XL_LABEL', 'Adult 2X-Large') ;
define('WPST_CLOTHING_SIZE_3XL_LABEL', 'Adult 3X-Large') ;
define('WPST_CLOTHING_SIZE_4XL_LABEL', 'Adult 4X-Large') ;
define('WPST_CLOTHING_SIZE_YS_VALUE', 'YS') ;
define('WPST_CLOTHING_SIZE_YM_VALUE', 'YM') ;
define('WPST_CLOTHING_SIZE_YL_VALUE', 'YL') ;
define('WPST_CLOTHING_SIZE_YXL_VALUE', 'YXL') ;
define('WPST_CLOTHING_SIZE_S_VALUE', 'S') ;
define('WPST_CLOTHING_SIZE_M_VALUE', 'M') ;
define('WPST_CLOTHING_SIZE_L_VALUE', 'L') ;
define('WPST_CLOTHING_SIZE_XL_VALUE', 'XL') ;
define('WPST_CLOTHING_SIZE_2XL_VALUE', '2XL') ;
define('WPST_CLOTHING_SIZE_3XL_VALUE', '3XL') ;
define('WPST_CLOTHING_SIZE_4XL_VALUE', '4XL') ;

define('WPST_FILE_FORMAT_SDIF_SD3_LABEL', 'SDIF') ;
define('WPST_FILE_FORMAT_SDIF_SD3_VALUE', 'sd3') ;
define('WPST_FILE_FORMAT_HYTEK_HY3_LABEL', 'Hy-tek HY3') ;
define('WPST_FILE_FORMAT_HYTEK_HY3_VALUE', 'hy3') ;
define('WPST_FILE_FORMAT_HYTEK_CL2_LABEL', 'Hy-tek CL2') ;
define('WPST_FILE_FORMAT_HYTEK_CL2_VALUE', 'cl2') ;


if (WPST_DEBUG) :
/**
 * Debug functions
 */
function wpst_preprint_r()
{
    $numargs = func_num_args() ;
    $arg_list = func_get_args() ;
    for ($i = 0; $i < $numargs; $i++) {
	printf('<pre style="text-align:left;">%s</pre>', print_r($arg_list[$i], true)) ;
    }
}

function wpst_whereami($f, $l)
{
    printf('<h2>%s - %s::%s</h2>', date('Y-m-d @ h:m:s'), basename($f), $l) ;
}

endif ;
?>
