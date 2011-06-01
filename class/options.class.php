<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Options classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Options
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

//error_reporting(E_ALL) ;

require_once("db.class.php") ;
require_once("swimteam.include.php") ;

/**
 * Class definition of the agegroups
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamOptions extends SwimTeamDBI
{
    /**
     * usonly property - international or US only
     */
    var $__usonly ;

    /**
     * genders property - genders supported
     */
    var $__genders ;

    /**
     * minAge property - the minimum age for the group
     */
    var $__minAge ;

    /**
     * maxAge property - the maximum age for the group
     */
    var $__maxAge ;

    /**
     * age cutoff day property
     */
    var $__ageCutoffDay ;

    /**
     * age cutoff month property
     */
    var $__ageCutoffMonth ;

    /**
     * gender label male property
     */
    var $__genderLabelMale ;

    /**
     * gender label female property
     */
    var $__genderLabelFemale ;

    /**
     * measurment units property
     */
    var $__measurement_units ;

    /**
     * swimmer label format property
     */
    var $__swimmer_label_format ;

    /**
     * swimmer label format code property
     */
    var $__swimmer_label_format_code ;

    /**
     * job sign up property
     */
    var $__job_sign_up ;

    /**
     * job credits property
     */
    var $__job_credits ;

    /**
     * job credits required property
     */
    var $__job_credits_required  ;

    /**
     * job email address property
     */
    var $__job_email_address ;

    /**
     * job email format property
     */
    var $__job_email_format ;

    /**
     * job expectations url property
     */
    var $__job_expectations_url ;

    /**
     * auto-register property
     */
    var $__auto_register ;

    /**
     * registration system property
     */
    var $__registration_system ;

    /**
     * GDL rows to display property
     */
    var $__gdl_rows_to_display ;

    /**
     * google maps api key property
     */
    var $__google_api_key ;

    /**
     * login redirect action property
     */
    var $__login_redirect_action  ;

    /**
     * geography property
     */
    var $__geography ;

    /**
     * user state or province label property
     */
    var $__user_stateorprovince_label ;

    /**
     * user postal code label property
     */
    var $__user_postalcode_label ;

    /**
     * primary phone label property
     */
    var $__user_primaryphone_label ;

    /**
     * secondary phone label property
     */
    var $__user_secondaryphone_label ;

    /**
     * swim team option field property
     */
    var $__swim_team_option = array() ;

    /**
     * opt in label property
     */
    var $__opt_in_label ;

    /**
     * opt out label property
     */
    var $__opt_out_label ;

    /**
     * opt in opt out email address property
     */
    var $__opt_in_opt_out_email_address ;

    /**
     * opt in opt out email format property
     */
    var $__opt_in_opt_out_email_format ;

    /**
     * opt in opt out usage model property
     */
    var $__opt_in_opt_out_usage_model ;

    /**
     * opt in opt out mode property
     */
    var $__opt_in_opt_out_mode ;

    /**
     * opt in opt out events property
     */
    var $__opt_in_opt_out_events ;

    /**
     * registration prefix label property
     */
    var $__registration_prefix_label ;

    /**
     * registration fee label property
     */
    var $__registration_fee_label ;

    /**
     * registration fee currency label property
     */
    var $__registration_fee_currency_label ;

    /**
     * registration fee property
     */
    var $__registration_fee ;

    /**
     * registration email property
     */
    var $__registration_email ;

    /**
     * registration email format property
     */
    var $__registration_email_format ;

    /**
     * registration terms of use URL property
     */
    var $__registration_tou_url ;

    /**
     * registration fee policy URL property
     */
    var $__registration_fee_policy_url ;

    /**
     * user optional fields
     */
    var $__user_optional_fields ;

    /**
     * swimmer optional fields
     */
    var $__swimmer_optional_fields ;

    /**
     * Set the usonly property - true or false
     *
     * @param - boolean - true for US only configuration
     */
    function setUSOnly($usonly = true)
    {
        $this->__usonly = $usonly ;
    }

    /**
     * Get the usonly property - true or false
     *
     * @return - string - true for US only configuration
     */
    function getUSOnly()
    {
        return ($this->__usonly) ;
    }

    /**
     * Set the genders - male, female, both
     *
     * @param - string - gender(s)
     */
    function setGender($genders)
    {
        $this->__genders = $genders ;
    }

    /**
     * Get the genders - male, female, both
     *
     * @return - string - gender(s)
     */
    function getGender()
    {
        return ($this->__genders) ;
    }

    /**
     * Set the minAge of the age group
     *
     * @param - int - minimum age of group
     */
    function setMinAge($minAge)
    {
        $this->__minAge = $minAge ;
    }

    /**
     * Get the minAge of the age group
     *
     * @return - int - minimum age of the group
     */
    function getMinAge()
    {
        return ($this->__minAge) ;
    }

    /**
     * Set the maxAge of the age group
     *
     * @param - int - maximum age of group
     */
    function setMaxAge($maxAge)
    {
        $this->__maxAge = $maxAge ;
    }

    /**
     * Get the maxAge of the age group
     *
     * @return - int - maximum age of the group
     */
    function getMaxAge()
    {
        return ($this->__maxAge) ;
    }

    /**
     * Set the age cutoff day
     *
     * @param - int - age cutoff day
     */
    function setAgeCutoffDay($ageCutoffDay)
    {
        $this->__ageCutoffDay = $ageCutoffDay ;
    }

    /**
     * Get the age cutoff day
     *
     * @return - int - age cutoff day
     */
    function getAgeCutoffDay()
    {
        return ($this->__ageCutoffDay) ;
    }

    /**
     * Set the age cutoff month
     *
     * @param - int - age cutoff month
     */
    function setAgeCutoffMonth($ageCutoffMonth)
    {
        $this->__ageCutoffMonth = $ageCutoffMonth ;
    }

    /**
     * Get the age cutoff month
     *
     * @return - int - age cutoff month
     */
    function getAgeCutoffMonth()
    {
        return ($this->__ageCutoffMonth) ;
    }

    /**
     * Set the male gender label
     *
     * @param - string - male gender label
     */
    function setGenderLabelMale($genderLabelMale)
    {
        $this->__genderLabelMale = $genderLabelMale ;
    }

    /**
     * Get the male gender label
     *
     * @return - string - male gender label
     */
    function getGenderLabelMale()
    {
        return ($this->__genderLabelMale) ;
    }

    /**
     * Set the female gender label
     *
     * @param - string - female gender label
     */
    function setGenderLabelFemale($genderLabelFemale)
    {
        $this->__genderLabelFemale = $genderLabelFemale ;
    }

    /**
     * Get the female gender label
     *
     * @return - string - female gender label
     */
    function getGenderLabelFemale()
    {
        return ($this->__genderLabelFemale) ;
    }

    /**
     * Set the state or province label
     *
     * @param - string - state or province label
     */
    function setStateOrProvinceLabel($label)
    {
        $this->__user_stateorprovince_label = $label ;
    }

    /**
     * Get the state or province label
     *
     * @return - string - state or province label
     */
    function getStateOrProvinceLabel()
    {
        return ($this->__user_stateorprovince_label) ;
    }

    /**
     * Set the postal code label
     *
     * @param - string - postal code label
     */
    function setPostalCodeLabel($label)
    {
        $this->__user_postalcode_label = $label ;
    }

    /**
     * Get the postal code label
     *
     * @return - string - postal code label
     */
    function getPostalCodeLabel()
    {
        return ($this->__user_postalcode_label) ;
    }

    /**
     * Set the primary phone label
     *
     * @param - string - primary phone label
     */
    function setPrimaryPhoneLabel($label)
    {
        $this->__user_primaryphone_label = $label ;
    }

    /**
     * Get the primary phone label
     *
     * @return - string - primary phone label
     */
    function getPrimaryPhoneLabel()
    {
        return ($this->__user_primaryphone_label) ;
    }

    /**
     * Set the secondary phone label
     *
     * @param - string - secondary phone label
     */
    function setSecondaryPhoneLabel($label)
    {
        $this->__user_secondaryphone_label = $label ;
    }

    /**
     * Get the secondary phone label
     *
     * @return - string - secondary phone label
     */
    function getSecondaryPhoneLabel()
    {
        return ($this->__user_secondaryphone_label) ;
    }

    /**
     * Set the measurement units
     *
     * @param - string - measurement units
     */
    function setMeasurementUnits($units)
    {
        $this->__measurement_units = $units ;
    }

    /**
     * Get the swimmer label format
     *
     * @return - string - swimmer label format
     */
    function getSwimmerLabelFormat()
    {
        return ($this->__swimmer_label_format) ;
    }

    /**
     * Set the swimmer label format
     *
     * @param - string - swimmer label format
     */
    function setSwimmerLabelFormat($format)
    {
        $this->__swimmer_label_format = $format ;
    }

    /**
     * Set the swimmer label format code
     *
     * @param - string - swimmer label format code
     */
    function setSwimmerLabelFormatCode($code)
    {
        $this->__swimmer_label_format_code = $code ;
    }

    /**
     * Get the swimmer label format code
     *
     * @return - string - swimmer label format code
     */
    function getSwimmerLabelFormatCode()
    {
        return ($this->__swimmer_label_format_code) ;
    }

    /**
     * Get the job sign up state
     *
     * @return - string - job sign up state
     */
    function getJobSignUp()
    {
        return ($this->__job_sign_up) ;
    }

    /**
     * Set the job sign up state
     *
     * @param - string - job sign up state
     */
    function setJobSignUp($state)
    {
        $this->__job_sign_up = $state ;
    }

    /**
     * Get the job credits
     *
     * @return - integer - job credits
     */
    function getJobCredits()
    {
        return ($this->__job_credits) ;
    }

    /**
     * Set the job credits
     *
     * @param - integer - job credits
     */
    function setJobCredits($credits)
    {
        $this->__job_credits = $credits ;
    }

    /**
     * Get the job credits required
     *
     * @return - integer - job credits required
     */
    function getJobCreditsRequired()
    {
        return ($this->__job_credits_required) ;
    }

    /**
     * Set the job credits required
     *
     * @param - integer - job credits required
     */
    function setJobCreditsRequired($required)
    {
        $this->__job_credits_required = $required ;
    }

    /**
     * Set the job email address
     *
     * @param - string - job email address
     */
    function setJobEmailAddress($email_address)
    {
        $this->__job_email_address = $email_address ;
    }

    /**
     * Get the job email address
     *
     * @return - string - job email address
     */
    function getJobEmailAddress()
    {
        return ($this->__job_email_address) ;
    }

    /**
     * Set the job email format
     *
     * @param - string - job email format
     */
    function setJobEmailFormat($email_format)
    {
        $this->__job_email_format = $email_format ;
    }

    /**
     * Get the job email format
     *
     * @return - string - job email format
     */
    function getJobEmailFormat()
    {
        return ($this->__job_email_format) ;
    }

    /**
     * Set the job expecations url
     *
     * @param - string - job expecations url
     */
    function setJobExpectationsURL($url)
    {
        $this->__job_expectations_url = $url ;
    }

    /**
     * Get the job expecations url
     *
     * @return - string - job expecations url
     */
    function getJobExpectationsURL()
    {
        return ($this->__job_expectations_url) ;
    }

    /**
     * Get the auto-register state
     *
     * @return - string - auto-register state
     */
    function getAutoRegister()
    {
        return ($this->__auto_register) ;
    }

    /**
     * Set the auto-register state
     *
     * @param - string - auto-register state
     */
    function setAutoRegister($state)
    {
        $this->__auto_register = $state ;
    }

    /**
     * Get the registration system state
     *
     * @return - string - registration system state
     */
    function getRegistrationSystem()
    {
        return ($this->__registration_system) ;
    }

    /**
     * Set the registration system state
     *
     * @param - string - registration system state
     */
    function setRegistrationSystem($state)
    {
        $this->__registration_system = $state ;
    }

    /**
     * Get the GDL rows to display
     *
     * @return - string - GDL rows to display
     */
    function getGDLRowsToDisplay()
    {
        return ($this->__gdl_rows_to_display) ;
    }

    /**
     * Set the GDL rows to display
     *
     * @param - string - GDL rows to display
     */
    function setGDLRowsToDisplay($rows)
    {
        $this->__gdl_rows_to_display = $rows ;
    }

    /**
     * Get the google maps api key
     *
     * @return - string - google maps api key
     */
    function getGoogleAPIKey()
    {
        return ($this->__google_api_key) ;
    }

    /**
     * Set the google maps api key
     *
     * @param - string - google maps api key
     */
    function setGoogleAPIKey($key)
    {
        $this->__google_api_key = $key ;
    }

    /**
     * Get the login redirect action
     *
     * @return - string - login redirect action
     */
    function getLoginRedirectAction()
    {
        return ($this->__login_redirect_action) ;
    }

    /**
     * Set the login redirect action
     *
     * @param - string - login redirect action
     */
    function setLoginRedirectAction($action)
    {
        $this->__login_redirect_action = $action ;
    }

    /**
     * Get the measurement units
     *
     * @return - string - measurement units
     */
    function getMeasurementUnits()
    {
        return ($this->__measurement_units) ;
    }

    /**
     * Set the geography
     *
     * @param - string - geography
     */
    function setGeography($geography)
    {
        $this->__geography = $geography ;
    }

    /**
     * Get the geography units
     *
     * @return - string - geography units
     */
    function getGeography()
    {
        return ($this->__geography) ;
    }

    /**
     * Set the swim team option
     *
     * @param - string - swim team option
     * @param - string - $value - value of option
     */
    function setSwimTeamOption($option, $value)
    {
        $this->__swim_team_option[$option] = $value ;
    }

    /**
     * Get the user option
     *
     * @param - string - user option
     * @return - boolean - state of user option
     */
    function getSwimTeamOption($option)
    {
        return (array_key_exists($option, $this->__swim_team_option)) ?
            ($this->__swim_team_option[$option]) : WPST_NULL_STRING ;
    }

    /**
     * Set the opt in label
     *
     * @param - string - opt in label
     */
    function setOptInLabel($label)
    {
        $this->__opt_in_label = $label ;
    }

    /**
     * Get the opt in label
     *
     * @return - string - opt in label
     */
    function getOptInLabel()
    {
        return ($this->__opt_in_label) ;
    }

    /**
     * Set the opt out label
     *
     * @param - string - opt out label
     */
    function setOptOutLabel($label)
    {
        $this->__opt_out_label = $label ;
    }

    /**
     * Get the opt out label
     *
     * @return - string - opt out label
     */
    function getOptOutLabel()
    {
        return ($this->__opt_out_label) ;
    }

    /**
     * Set the opt in opt out email address
     *
     * @param - string - opt in opt out email address
     */
    function setOptInOptOutEmailAddress($email_address)
    {
        $this->__opt_in_opt_out_email_address = $email_address ;
    }

    /**
     * Get the opt in opt out email address
     *
     * @return - string - opt in opt out email address
     */
    function getOptInOptOutEmailAddress()
    {
        return ($this->__opt_in_opt_out_email_address) ;
    }

    /**
     * Set the opt in opt out email format
     *
     * @param - string - opt in opt out email format
     */
    function setOptInOptOutEmailFormat($email_format)
    {
        $this->__opt_in_opt_out_email_format = $email_format ;
    }

    /**
     * Get the opt in opt out email format
     *
     * @return - string - opt in opt out email format
     */
    function getOptInOptOutEmailFormat()
    {
        return ($this->__opt_in_opt_out_email_format) ;
    }

    /**
     * Set the opt in opt out usage model
     *
     * @param - string - opt in opt out usage model
     */
    function setOptInOptOutUsageModel($model)
    {
        $this->__opt_in_opt_out_usage_model = $model ;
    }

    /**
     * Get the opt in opt out usage model
     *
     * @return - string - opt in opt out usage model
     */
    function getOptInOptOutUsageModel()
    {
        return ($this->__opt_in_opt_out_usage_model) ;
    }

    /**
     * Set the opt in opt out mode
     *
     * @param - string - opt in opt out mode
     */
    function setOptInOptOutMode($mode)
    {
        $this->__opt_in_opt_out_mode = $mode ;
    }

    /**
     * Get the opt in opt out mode
     *
     * @return - string - opt in opt out mode
     */
    function getOptInOptOutMode()
    {
        return ($this->__opt_in_opt_out_mode) ;
    }

    /**
     * Set the opt in opt out events
     *
     * @param - array - opt in opt out strokes in SDIF numbering
     */
    function setOptInOptOutStrokes($strokes)
    {
        $this->__opt_in_opt_out_strokes = $strokes ;
    }

    /**
     * Get the opt in opt out events
     *
     * @return - array - opt in opt out strokes in SDIF numbering
     */
    function getOptInOptOutStrokes()
    {
        return ($this->__opt_in_opt_out_strokes) ;
    }

    /**
     * Set the registration prefix label
     *
     * @param - string - registration prefix label
     */
    function setRegistrationPrefixLabel($label)
    {
        $this->__registration_prefix_label = $label ;
    }

    /**
     * Get the registration prefix out label
     *
     * @return - string - registration prefix label
     */
    function getRegistrationPrefixLabel()
    {
        return ($this->__registration_prefix_label) ;
    }

    /**
     * Set the registration fee label
     *
     * @param - string - registration fee label
     */
    function setRegistrationFeeLabel($label)
    {
        $this->__registration_fee_label = $label ;
    }

    /**
     * Get the registration fee out label
     *
     * @return - string - registration fee label
     */
    function getRegistrationFeeLabel()
    {
        return ($this->__registration_fee_label) ;
    }

    /**
     * Set the registration fee currency label
     *
     * @param - string - registration fee currency label
     */
    function setRegistrationFeeCurrencyLabel($label)
    {
        $this->__registration_fee_currency_label = $label ;
    }

    /**
     * Get the registration fee currency label
     *
     * @return - string - registration fee currency label
     */
    function getRegistrationFeeCurrencyLabel()
    {
        return ($this->__registration_fee_currency_label) ;
    }

    /**
     * Set the registration fee
     *
     * @param - string - registration fee
     */
    function setRegistrationFee($fee)
    {
        $this->__registration_fee = $fee ;
    }

    /**
     * Get the registration fee
     *
     * @return - string - registration fee
     */
    function getRegistrationFee()
    {
        return ($this->__registration_fee) ;
    }

    /**
     * Set the registration email
     *
     * @param - string - registration email
     */
    function setRegistrationEmail($email)
    {
        $this->__registration_email = $email ;
    }

    /**
     * Get the registration email
     *
     * @return - string - registration email
     */
    function getRegistrationEmail()
    {
        return ($this->__registration_email) ;
    }

    /**
     * Set the registration email format
     *
     * @param - string - registration email format
     */
    function setRegistrationEmailFormat($email_format)
    {
        $this->__registration_email_format = $email_format ;
    }

    /**
     * Get the registration email format
     *
     * @return - string - registration email format
     */
    function getRegistrationEmailFormat()
    {
        return ($this->__registration_email_format) ;
    }

    /**
     * Set the registration tou url
     *
     * @param - string - registration tou url
     */
    function setRegistrationTermsOfUseURL($url)
    {
        $this->__registration_tou_url = $url ;
    }

    /**
     * Get the registration email
     *
     * @return - string - registration email
     */
    function getRegistrationTermsOfUseURL()
    {
        return ($this->__registration_tou_url) ;
    }

    /**
     * Set the registration fee policy url
     *
     * @param - string - registration fee policy url
     */
    function setRegistrationFeePolicyURL($url)
    {
        $this->__registration_fee_policy_url = $url ;
    }

    /**
     * Get the registration fee policy url
     *
     * @return - string - registration fee policy url
     */
    function getRegistrationFeePolicyURL()
    {
        return ($this->__registration_fee_policy_url) ;
    }

    /**
     * Set the number of user optional fields
     *
     * @param - int - number of user optional fields
     */
    function setUserOptionalFields($num)
    {
        $this->__user_optional_fields = $num ;
    }

    /**
     * Get the number of user optional fields
     *
     * @return - int - number of user optional fields
     */
    function getUserOptionalFields()
    {
        return ($this->__user_optional_fields) ;
    }

    /**
     * Set the number of swimmer optional fields
     *
     * @param - int - number of swimmer optional fields
     */
    function setSwimmerOptionalFields($num)
    {
        $this->__swimmer_optional_fields = $num ;
    }

    /**
     * Get the number of swimmer optional fields
     *
     * @return - int - number of swimmer optional fields
     */
    function getSwimmerOptionalFields()
    {
        return ($this->__swimmer_optional_fields) ;
    }

    /**
     * load Options
     *
     * Load the option values from the WordPress database.
     * If for some reason, the option doesn't exist, use the
     * default value.
     *
     */
    function loadOptions()
    {
        //  gender
        $option = get_option(WPST_OPTION_GENDER) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGender($option) ;
        }
        else
        {
            $this->setGender(WPST_DEFAULT_GENDER) ;
            update_option(WPST_OPTION_GENDER, WPST_DEFAULT_GENDER) ;
        }

        //  min age
        $option = get_option(WPST_OPTION_MIN_AGE) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setMinAge($option) ;
        }
        else
        {
            $this->setMinAge(WPST_DEFAULT_MIN_AGE) ;
            update_option(WPST_OPTION_MIN_AGE, WPST_DEFAULT_MIN_AGE) ;
        }


        //  max age
        $option = get_option(WPST_OPTION_MAX_AGE) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setMaxAge($option) ;
        }
        else
        {
            $this->setMaxAge(WPST_DEFAULT_MAX_AGE) ;
            update_option(WPST_OPTION_MAX_AGE, WPST_DEFAULT_MAX_AGE) ;
        }

        //  age cutoff day
        $option = get_option(WPST_OPTION_AGE_CUTOFF_DAY) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setAgeCutoffDay($option) ;
        }
        else
        {
            $this->setAgeCutoffDay(WPST_DEFAULT_AGE_CUTOFF_DAY) ;
            update_option(WPST_OPTION_AGE_CUTOFF_DAY, WPST_DEFAULT_AGE_CUTOFF_DAY) ;
        }

        //  age cutoff month
        $option = get_option(WPST_OPTION_AGE_CUTOFF_MONTH) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setAgeCutoffMonth($option) ;
        }
        else
        {
            $this->setAgeCutoffMonth(WPST_DEFAULT_AGE_CUTOFF_MONTH) ;
            update_option(WPST_OPTION_AGE_CUTOFF_MONTH, WPST_DEFAULT_AGE_CUTOFF_MONTH) ;
        }

        //  gender label male
        $option = get_option(WPST_OPTION_GENDER_LABEL_MALE) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGenderLabelMale($option) ;
        }
        else
        {
            $this->setGenderLabelMale(WPST_DEFAULT_GENDER_LABEL_MALE) ;
            update_option(WPST_OPTION_GENDER_LABEL_MALE, WPST_DEFAULT_GENDER_LABEL_MALE) ;
        }

        //  gender label female
        $option = get_option(WPST_OPTION_GENDER_LABEL_FEMALE) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGenderLabelFemale($option) ;
        }
        else
        {
            $this->setGenderLabelFemale(WPST_DEFAULT_GENDER_LABEL_FEMALE) ;
            update_option(WPST_OPTION_GENDER_LABEL_FEMALE, WPST_DEFAULT_GENDER_LABEL_FEMALE) ;
        }

        //  swimmer label format
        $option = get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setSwimmerLabelFormat($option) ;
        }
        else
        {
            $this->setSwimmerLabelFormat(WPST_DEFAULT_SWIMMER_LABEL_FORMAT) ;
            update_option(WPST_OPTION_SWIMMER_LABEL_FORMAT, WPST_DEFAULT_SWIMMER_LABEL_FORMAT) ;
        }

        //  swimmer label format code
        $option = get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setSwimmerLabelFormatCode($option) ;
        }
        else
        {
            $this->setSwimmerLabelFormatCode(WPST_DEFAULT_SWIMMER_LABEL_FORMAT_CODE) ;
            update_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE, WPST_DEFAULT_SWIMMER_LABEL_FORMAT_CODE) ;
        }

        //  job sign up
        $option = get_option(WPST_OPTION_JOB_SIGN_UP) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setJobSignUp($option) ;
        }
        else
        {
            $this->setJobSignUp(WPST_DEFAULT_JOB_SIGN_UP) ;
            update_option(WPST_OPTION_JOB_SIGN_UP, WPST_DEFAULT_JOB_SIGN_UP) ;
        }

        //  job credits
        $option = get_option(WPST_OPTION_JOB_CREDITS) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setJobCredits($option) ;
        }
        else
        {
            $this->setJobCredits(WPST_DEFAULT_JOB_CREDITS) ;
            update_option(WPST_OPTION_JOB_CREDITS, WPST_DEFAULT_JOB_CREDITS) ;
        }

        //  job credits required
        $option = get_option(WPST_OPTION_JOB_CREDITS_REQUIRED) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setJobCreditsRequired($option) ;
        }
        else
        {
            $this->setJobCreditsRequired(WPST_DEFAULT_JOB_CREDITS_REQUIRED) ;
            update_option(WPST_OPTION_JOB_CREDITS_REQUIRED, WPST_DEFAULT_JOB_CREDITS_REQUIRED) ;
        }

        //  job email address
        $option = get_option(WPST_OPTION_JOB_EMAIL_ADDRESS) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setJobEmailAddress($option) ;
        }
        else
        {
            $email = get_bloginfo('admin_email') ;
            $this->setJobEmailAddress($email) ;
            update_option(WPST_OPTION_JOB_EMAIL_ADDRESS, $email) ;
        }
 
        //  job email format
        $option = get_option(WPST_OPTION_JOB_EMAIL_FORMAT) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setJobEmailFormat($option) ;
        }
        else
        {
            $this->setJobEmailFormat(WPST_DEFAULT_JOB_EMAIL_FORMAT) ;
            update_option(WPST_OPTION_JOB_EMAIL_FORMAT, WPST_DEFAULT_JOB_EMAIL_FORMAT) ;
        }
 
        //  job expectations url
        $option = get_option(WPST_OPTION_JOB_EXPECTATIONS_URL) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setJobExpectationsURL($option) ;
        }
        else
        {
            $this->setJobExpectationsURL(WPST_NULL_STRING) ;
            update_option(WPST_OPTION_JOB_EXPECTATIONS_URL, WPST_NULL_STRING) ;
        }
 
        //  auto-register
        $option = get_option(WPST_OPTION_AUTO_REGISTER) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setAutoRegister($option) ;
        }
        else
        {
            $this->setAutoRegister(WPST_DEFAULT_AUTO_REGISTER) ;
            update_option(WPST_OPTION_AUTO_REGISTER, WPST_DEFAULT_AUTO_REGISTER) ;
        }

        //  registration system
        $option = get_option(WPST_OPTION_REGISTRATION_SYSTEM) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationSystem($option) ;
        }
        else
        {
            $this->setRegistrationSystem(WPST_DEFAULT_REGISTRATION_SYSTEM) ;
            update_option(WPST_OPTION_REGISTRATION_SYSTEM, WPST_DEFAULT_REGISTRATION_SYSTEM) ;
        }

        //  GDL Rows to Display
        $option = get_option(WPST_OPTION_GDL_ROWS_TO_DISPLAY) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGDLRowsToDisplay($option) ;
        }
        else
        {
            $this->setGoogleAPIKey(WPST_DEFAULT_GDL_ROWS_TO_DISPLAY) ;
            update_option(WPST_OPTION_GDL_ROWS_TO_DISPLAY, WPST_DEFAULT_GDL_ROWS_TO_DISPLAY) ;
        }

        //  Google API Key
        $option = get_option(WPST_OPTION_GOOGLE_API_KEY) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGoogleAPIKey($option) ;
        }
        else
        {
            $this->setGoogleAPIKey(WPST_DEFAULT_GOOGLE_API_KEY) ;
            update_option(WPST_OPTION_GOOGLE_API_KEY, WPST_DEFAULT_GOOGLE_API_KEY) ;
        }

        //  Login Redirect Action
        $option = get_option(WPST_OPTION_LOGIN_REDIRECT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setLoginRedirectAction($option) ;
        }
        else
        {
            $this->setLoginRedirectAction(WPST_DEFAULT_LOGIN_REDIRECT) ;
            update_option(WPST_OPTION_LOGIN_REDIRECT, WPST_DEFAULT_LOGIN_REDIRECT) ;
        }

        //  geography
        $option = get_option(WPST_OPTION_GEOGRAPHY) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setGeography($option) ;
        }
        else
        {
            $this->setGeography(WPST_DEFAULT_GEOGRAPHY) ;
            update_option(WPST_OPTION_GEOGRAPHY, WPST_DEFAULT_GEOGRAPHY) ;
        }

        //  postal code label
        $option = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setPostalCodeLabel($option) ;
        }
        else
        {
            $this->setPostalCodeLabel(WPST_DEFAULT_POSTAL_CODE_LABEL) ;
            update_option(WPST_OPTION_POSTAL_CODE_LABEL, WPST_DEFAULT_POSTAL_CODE_LABEL) ;
        }

        //  primary phone label
        $option = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setPrimaryPhoneLabel($option) ;
        }
        else
        {
            $this->setPrimaryPhoneLabel(WPST_DEFAULT_PRIMARY_PHONE_LABEL) ;
            update_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL, WPST_DEFAULT_PRIMARY_PHONE_LABEL) ;
        }

        //  secondary phone label
        $option = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setSecondaryPhoneLabel($option) ;
        }
        else
        {
            $this->setSecondaryPhoneLabel(WPST_DEFAULT_SECONDARY_PHONE_LABEL) ;
            update_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL, WPST_DEFAULT_SECONDARY_PHONE_LABEL) ;
        }

        //  state or province label
        $option = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setStateOrProvinceLabel($option) ;
        }
        else
        {
            $this->setStateOrProvinceLabel(WPST_DEFAULT_STATE_OR_PROVINCE_LABEL) ;
            update_option(WPST_OPTION_STATE_OR_PROVINCE_LABEL, WPST_DEFAULT_STATE_OR_PROVINCE_LABEL) ;
        }

        //  Registration Prefix Label
        $option = get_option(WPST_OPTION_REG_PREFIX_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationPrefixLabel($option) ;
        }
        else
        {
            $this->setRegistrationPrefixLabel(WPST_DEFAULT_REG_PREFIX_LABEL) ;
            update_option(WPST_OPTION_REG_PREFIX_LABEL, WPST_DEFAULT_REG_PREFIX_LABEL) ;
        }

        //  Registration Fee Label
        $option = get_option(WPST_OPTION_REG_FEE_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationFeeLabel($option) ;
        }
        else
        {
            $this->setRegistrationFeeLabel(WPST_DEFAULT_REG_FEE_LABEL) ;
            update_option(WPST_OPTION_REG_FEE_LABEL, WPST_DEFAULT_REG_FEE_LABEL) ;
        }

        //  Registration Fee Currency Label
        $option = get_option(WPST_OPTION_REG_FEE_CURRENCY_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationFeeCurrencyLabel($option) ;
        }
        else
        {
            $this->setRegistrationFeeCurrencyLabel(WPST_DEFAULT_REG_FEE_CURRENCY_LABEL) ;
            update_option(WPST_OPTION_REG_FEE_CURRENCY_LABEL, WPST_DEFAULT_REG_FEE_CURRENCY_LABEL) ;
        }

        //  Registration Fee
        $option = get_option(WPST_OPTION_REG_FEE_AMOUNT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationFee($option) ;
        }
        else
        {
            $this->setRegistrationFee(WPST_DEFAULT_REG_FEE_AMOUNT) ;
            update_option(WPST_OPTION_REG_FEE_AMOUNT, WPST_DEFAULT_REG_FEE_AMOUNT) ;
        }

        //  Registration E=mail
        $option = get_option(WPST_OPTION_REG_EMAIL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationEmail($option) ;
        }
        else
        {
            $email = get_bloginfo('admin_email') ;
            $this->setRegistrationEmail($email) ;
            update_option(WPST_OPTION_REG_EMAIL, $email) ;
        }

        //  Registration E=mail Format
        $option = get_option(WPST_OPTION_REG_EMAIL_FORMAT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationEmailFormat($option) ;
        }
        else
        {
            $this->setRegistrationEmailFormat(WPST_HTML) ;
            update_option(WPST_OPTION_REG_EMAIL_FORMAT, WPST_HTML) ;
        }

        //  Registration Terms of Use URL
        $option = get_option(WPST_OPTION_REG_TOU_URL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationTermsOfUseURL($option) ;
        }
        else
        {
            $this->setRegistrationTermsOfUseURL(WPST_NULL_STRING) ;
            update_option(WPST_OPTION_REG_TOU_URL, WPST_NULL_STRING) ;
        }

        //  Registration Fee Policy URL
        $option = get_option(WPST_OPTION_REG_FEE_URL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setRegistrationFeePolicyURL($option) ;
        }
        else
        {
            $this->setRegistrationFeePolicyURL(WPST_NULL_STRING) ;
            update_option(WPST_OPTION_REG_FEE_URL, WPST_NULL_STRING) ;
        }

        //  Number of User Optional Fields
        $option = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setUserOptionalFields($option) ;
        }
        else
        {
            $this->setUserOptionalFields(WPST_DEFAULT_USER_OPTION_COUNT) ;
            update_option(WPST_OPTION_USER_OPTION_COUNT, WPST_DEFAULT_USER_OPTION_COUNT) ;
        }

        //  Number of Swimmer Optional Fields
        $option = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setSwimmerOptionalFields($option) ;
        }
        else
        {
            $this->setSwimmerOptionalFields(WPST_DEFAULT_SWIMMER_OPTION_COUNT) ;
            update_option(WPST_OPTION_SWIMMER_OPTION_COUNT, WPST_DEFAULT_SWIMMER_OPTION_COUNT) ;
        }

        //  How many user options does this configuration support?

        $options = $this->getUserOptionalFields() ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            //  user option
            $option = get_option(constant("WPST_OPTION_USER_OPTION" . $oc)) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" . $oc), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" .
                    $oc), WPST_DEFAULT_USER_OPTION) ;
                update_option(constant("WPST_OPTION_USER_OPTION" .
                    $oc), WPST_DEFAULT_USER_OPTION) ;
            }
 
            //  user option label
            $option = get_option(constant("WPST_OPTION_USER_OPTION" . $oc . "_LABEL")) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" .
                    $oc . "_LABEL"), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" .
                    $oc . "_LABEL"), WPST_DEFAULT_USER_OPTION_LABEL . $oc) ;
                update_option(constant("WPST_OPTION_USER_OPTION" .
                    $oc .  "_LABEL"), WPST_DEFAULT_USER_OPTION_LABEL . $oc) ;
            }
 
            //  user option mode
            $option = get_option(constant("WPST_OPTION_USER_OPTION" . $oc . "_MODE")) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" .
                    $oc . "_MODE"), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_USER_OPTION" .
                    $oc . "_MODE"), WPST_USER) ;
                update_option(constant("WPST_OPTION_USER_OPTION" .
                    $oc .  "_MODE"), WPST_USER) ;
            }
        }

        //  How many swimmer options does this configuration support?

        $options = $this->getSwimmerOptionalFields() ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            //  swimmer option
            $option = get_option(constant("WPST_OPTION_SWIMMER_OPTION" . $oc)) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" . $oc), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc), WPST_DEFAULT_SWIMMER_OPTION) ;
                update_option(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc), WPST_DEFAULT_SWIMMER_OPTION) ;
            }
 
            //  swimmer option label
            $option = get_option(constant("WPST_OPTION_SWIMMER_OPTION" . $oc . "_LABEL")) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc . "_LABEL"), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc . "_LABEL"), WPST_DEFAULT_SWIMMER_OPTION . $oc) ;
                update_option(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc .  "_LABEL"), WPST_DEFAULT_SWIMMER_OPTION_LABEL . $oc) ;
            }
 
            //  swimmer option mode
            $option = get_option(constant("WPST_OPTION_SWIMMER_OPTION" . $oc . "_MODE")) ;

            //  If option isn't stored in the database, use the default
            if ($option !== false)
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc . "_MODE"), $option) ;
            }
            else
            {
                $this->setSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc . "_MODE"), WPST_USER) ;
                update_option(constant("WPST_OPTION_SWIMMER_OPTION" .
                    $oc .  "_MODE"), WPST_USER) ;
            } 
        }

        //  opt in label
        $option = get_option(WPST_OPTION_OPT_IN_LABEL) ;

        //  If option isn't stored in the database, use the default
        if ($option !== false)
        {
            $this->setOptInLabel($option) ;
        }
        else
        {
            $this->setOptInLabel(WPST_DEFAULT_OPT_IN_LABEL) ;
            update_option(WPST_OPTION_OPT_IN_LABEL, WPST_DEFAULT_OPT_IN_LABEL) ;
        }
 
        //  opt out label
        $option = get_option(WPST_OPTION_OPT_OUT_LABEL) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptOutLabel($option) ;
        }
        else
        {
            $this->setOptOutLabel(WPST_DEFAULT_OPT_OUT_LABEL) ;
            update_option(WPST_OPTION_OPT_OUT_LABEL, WPST_DEFAULT_OPT_OUT_LABEL) ;
        }
 
        //  opt in opt out email address
        $option = get_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_ADDRESS) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptInOptOutEmailAddress($option) ;
        }
        else
        {
            $email = get_bloginfo('admin_email') ;
            $this->setOptInOptOutEmailAddress($email) ;
            update_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_ADDRESS, $email) ;
        }
 
        //  opt in opt out email format
        $option = get_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptInOptOutEmailFormat($option) ;
        }
        else
        {
            $this->setOptInOptOutEmailFormat(WPST_DEFAULT_OPT_IN_OPT_OUT_EMAIL_FORMAT) ;
            update_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT, WPST_DEFAULT_OPT_IN_OPT_OUT_EMAIL_FORMAT) ;
        }
 
        //  opt in opt out usage model
        $option = get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptInOptOutUsageModel($option) ;
        }
        else
        {
            $this->setOptInOptOutUsageModel(WPST_STROKE) ;
            update_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL, STROKE) ;
        }
 
        //  opt in opt out mode
        $option = get_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptInOptOutMode($option) ;
        }
        else
        {
            $this->setOptInOptOutMode(WPST_DEFAULT_OPT_IN_OPT_OUT_MODE) ;
            update_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE, WPST_DEFAULT_OPT_IN_OPT_OUT_MODE) ;
        }
 
        //  opt-in opt-out strokes
        $option = get_option(WPST_OPTION_OPT_IN_OPT_OUT_STROKES) ;

        //  If option isn't stored out the database, use the default
        if ($option !== false)
        {
            $this->setOptInOptOutStrokes($option) ;
        }
        else
        {
            $optinoptoutstrokes = array(
                WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE
               ,WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE
            ) ;
            $this->setOptInOptOutStrokes($optinoptoutstrokes) ;
            update_option(WPST_OPTION_OPT_IN_OPT_OUT_STROKES, $optinoptoutstrokes) ;
        }
    }

    /**
     * update (save) Options
     *
     * Write the options to the WordPress database
     */
    function updateOptions()
    {
        update_option(WPST_OPTION_GENDER, $this->getGender()) ;
        update_option(WPST_OPTION_MIN_AGE, $this->getMinAge()) ;
        update_option(WPST_OPTION_MAX_AGE, $this->getMaxAge()) ;
        update_option(WPST_OPTION_AGE_CUTOFF_DAY, $this->getAgeCutoffDay()) ;
        update_option(WPST_OPTION_AGE_CUTOFF_MONTH, $this->getAgeCutoffMonth()) ;
        update_option(WPST_OPTION_GENDER_LABEL_MALE, $this->getGenderLabelMale()) ;
        update_option(WPST_OPTION_GENDER_LABEL_FEMALE, $this->getGenderLabelFemale()) ;
        update_option(WPST_OPTION_OPT_IN_LABEL, $this->getOptInLabel()) ;
        update_option(WPST_OPTION_OPT_OUT_LABEL, $this->getOptOutLabel()) ;
        update_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_ADDRESS, $this->getOptInOptOutEmailAddress()) ;
        update_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT, $this->getOptInOptOutEmailFormat()) ;
        update_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE, $this->getOptInOptOutMode()) ;
        update_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL, $this->getOptInOptOutUsageModel()) ;
        update_option(WPST_OPTION_OPT_IN_OPT_OUT_STROKES, $this->getOptInOptOutStrokes()) ;
        update_option(WPST_OPTION_GEOGRAPHY, $this->getGeography()) ;
        //update_option(WPST_OPTION_MEASUREMENT_UNITS, $this->getMeasurementUnits()) ;
        update_option(WPST_OPTION_JOB_SIGN_UP, $this->getJobSignUp()) ;
        update_option(WPST_OPTION_JOB_CREDITS, $this->getJobCredits()) ;
        update_option(WPST_OPTION_JOB_CREDITS_REQUIRED, $this->getJobCreditsRequired()) ;
        update_option(WPST_OPTION_JOB_EMAIL_ADDRESS, $this->getJobEmailAddress()) ;
        update_option(WPST_OPTION_JOB_EMAIL_FORMAT, $this->getJobEmailFormat()) ;
        update_option(WPST_OPTION_JOB_EXPECTATIONS_URL, $this->getJobExpectationsURL()) ;
        update_option(WPST_OPTION_SWIMMER_LABEL_FORMAT, $this->getSwimmerLabelFormat()) ;
        update_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE, $this->getSwimmerLabelFormatCode()) ;
        update_option(WPST_OPTION_AUTO_REGISTER, $this->getAutoRegister()) ;
        update_option(WPST_OPTION_REGISTRATION_SYSTEM, $this->getRegistrationSystem()) ;
        update_option(WPST_OPTION_GOOGLE_API_KEY, $this->getGoogleAPIKey()) ;
        update_option(WPST_OPTION_GDL_ROWS_TO_DISPLAY, $this->getGDLRowsToDisplay()) ;
        update_option(WPST_OPTION_LOGIN_REDIRECT, $this->getLoginRedirectAction()) ;
        update_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL, $this->getStateOrProvinceLabel()) ;
        update_option(WPST_OPTION_USER_POSTAL_CODE_LABEL, $this->getPostalCodeLabel()) ;
        update_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL, $this->getPrimaryPhoneLabel()) ;
        update_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL, $this->getSecondaryPhoneLabel()) ;
        update_option(WPST_OPTION_REG_PREFIX_LABEL, $this->getRegistrationPrefixLabel()) ;
        update_option(WPST_OPTION_REG_FEE_LABEL, $this->getRegistrationFeeLabel()) ;
        update_option(WPST_OPTION_REG_FEE_CURRENCY_LABEL, $this->getRegistrationFeeCurrencyLabel()) ;
        update_option(WPST_OPTION_REG_FEE_AMOUNT, $this->getRegistrationFee()) ;
        update_option(WPST_OPTION_REG_EMAIL, $this->getRegistrationEmail()) ;
        update_option(WPST_OPTION_REG_EMAIL_FORMAT, $this->getRegistrationEmailFormat()) ;
        update_option(WPST_OPTION_REG_TOU_URL, $this->getRegistrationTermsOfUseURL()) ;
        update_option(WPST_OPTION_REG_FEE_URL, $this->getRegistrationFeePolicyURL()) ;
        update_option(WPST_OPTION_USER_OPTION_COUNT, $this->getUserOptionalFields()) ;
        update_option(WPST_OPTION_SWIMMER_OPTION_COUNT, $this->getSwimmerOptionalFields()) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Make sure all of the User Option constants are defined

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            if (!defined("WPST_OPTION_USER_OPTION" .  $oc))
                define("WPST_OPTION_USER_OPTION" .  $oc,
                    WPST_OPTION_PREFIX . "user_option" . $oc) ;

            if (!defined("WPST_OPTION_USER_OPTION" .  $oc . "_LABEL"))
                define("WPST_OPTION_USER_OPTION" .  $oc . "_LABEL",
                    WPST_OPTION_PREFIX . "user_option" . $oc . "_label") ;

            if (!defined("WPST_OPTION_USER_OPTION" .  $oc . "_MODE"))
                define("WPST_OPTION_USER_OPTION" .  $oc . "_MODE",
                    WPST_OPTION_PREFIX . "user_option" . $oc . "_mode") ;
        }
 
        //  Store the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            //  user option
            update_option(constant("WPST_OPTION_USER_OPTION" . $oc),
               $this->getSwimTeamOption(constant("WPST_OPTION_USER_OPTION" . $oc))) ;
 
            update_option(constant("WPST_OPTION_USER_OPTION" .
                $oc .  "_LABEL"), $this->getSwimTeamOption(constant("WPST_OPTION_USER_OPTION" . $oc . "_LABEL"))) ;

            update_option(constant("WPST_OPTION_USER_OPTION" .
                $oc .  "_MODE"), $this->getSwimTeamOption(constant("WPST_OPTION_USER_OPTION" . $oc . "_MODE"))) ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Make sure all of the Swimmer Option constants are defined

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            if (!defined("WPST_OPTION_SWIMMER_OPTION" .  $oc))
                define("WPST_OPTION_SWIMMER_OPTION" .  $oc,
                    WPST_OPTION_PREFIX . "swimmer_option" . $oc) ;

            if (!defined("WPST_OPTION_SWIMMER_OPTION" .  $oc . "_LABEL"))
                define("WPST_OPTION_SWIMMER_OPTION" .  $oc . "_LABEL",
                    WPST_OPTION_PREFIX . "swimmer_option" . $oc . "_label") ;

            if (!defined("WPST_OPTION_SWIMMER_OPTION" .  $oc . "_MODE"))
                define("WPST_OPTION_SWIMMER_OPTION" .  $oc . "_MODE",
                    WPST_OPTION_PREFIX . "swimmer_option" . $oc . "_mode") ;
        }
 
        //  Store the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            //  swimmer option
            update_option(constant("WPST_OPTION_SWIMMER_OPTION" . $oc),
               $this->getSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" . $oc))) ;
 
            update_option(constant("WPST_OPTION_SWIMMER_OPTION" .
                $oc .  "_LABEL"), $this->getSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" . $oc . "_LABEL"))) ;
 
            update_option(constant("WPST_OPTION_SWIMMER_OPTION" .
                $oc .  "_MODE"), $this->getSwimTeamOption(constant("WPST_OPTION_SWIMMER_OPTION" . $oc . "_MODE"))) ;
        }
    }
}

/**
 * Class definition of the Swim Team Option Meta
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamOptionMeta extends SwimTeamDBI
{
    /**
     * option meta id
     */
    var $__ometa_id ;

    /**
     * option user id
     */
    var $__user_id ;

    /**
     * option swimmer id
     */
    var $__swimmer_id ;

    /**
     * option meta key
     */
    var $__ometa_key ;

    /**
     * option meta value
     */
    var $__ometa_value ;

    /**
     * option meta record
     */
    var $__ometa_record ;

    /**
     * Set Option Meta Id
     *
     * @param int - $id - Id of the meta option
     */
    function setOptionMetaId($id)
    {
        $this->__ometa_id = $id ;
    }

    /**
     * Get Option Meta Id
     *
     * @return int - Id of the meta option
     */
    function getOptionMetaId()
    {
        return $this->__ometa_id ;
    }

    /**
     * Set Option User Id
     *
     * @param int - $id - Id of the user option
     */
    function setUserId($id)
    {
        $this->__user_id = $id ;
    }

    /**
     * Get Option User Id
     *
     * @return int - Id of the user option
     */
    function getUserId()
    {
        return $this->__user_id ;
    }

    /**
     * Set Option Swimmer Id
     *
     * @param int - $id - Id of the swimmer option
     */
    function setSwimmerId($id)
    {
        $this->__swimmer_id = $id ;
    }

    /**
     * Get Option Swimmer Id
     *
     * @return int - Id of the swimmer option
     */
    function getSwimmerId()
    {
        return $this->__swimmer_id ;
    }

    /**
     * Set Option Meta Key
     *
     * @param int - $key - Key of the meta option
     */
    function setOptionMetaKey($key)
    {
        $this->__ometa_key = $key ;
    }

    /**
     * Get Option Meta Key
     *
     * @return int - Key of the meta option
     */
    function getOptionMetaKey()
    {
        return $this->__ometa_key ;
    }

    /**
     * Set Option Meta Value
     *
     * @param int - $value - Value of the meta option
     */
    function setOptionMetaValue($value)
    {
        $this->__ometa_value = $value ;
    }

    /**
     * Get Option Meta Value
     *
     * @return int - Value of the meta option
     */
    function getOptionMetaValue()
    {
        return $this->__ometa_value ;
    }

    /**
     * Load Option Meta
     *
     * @param - string - $query - SQL query string
     */
    function loadOptionMeta($query = null)
    {
        if (is_null($query))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Query")) ;
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        // Make sure only one result is returned ...

        if ($this->getQueryCount() == 1)
        {
            $this->__ometa_record = $this->getQueryResult() ;

            //  Short cut to save typing ... 

            $om = &$this->__ometa_record ;

            $this->setOptionMetaId($om['ometaid']) ;
            $this->setUserId($om['userid']) ;
            $this->setSwimmerId($om['swimmerid']) ;
            $this->setOptionMetaKey($om['ometakey']) ;
            $this->setOptionMetaValue($om['ometavalue']) ;
        }
        else
        {
            $this->setOptionMetaId(null) ;
            $this->setUserId(null) ;
            $this->setSwimmerId(null) ;
            $this->setOptionMetaKey(null) ;
            $this->setOptionMetaValue(null) ;
            $this->__ometa_record = null ;
        }

        return ($this->getQueryCount() == 1) ;
    }

    /**
     * Load Option Meta by Meta Id
     *
     * @param - int - $id - option meta id
     */
    function loadOptionMetaByOMetaId($ometaid = null)
    {
        if (is_null($ometaid)) $ometaid = $this->getOptionMetaId() ;

        if (is_null($ometaid))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Id")) ;
        $query = sprintf("SELECT * FROM %s WHERE ometaid='%s'",
            WPST_OPTIONS_META_TABLE, $ometaid) ;

        return $this->loadOptionMeta($query) ;
    }

    /**
     * Load Option Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     */
    function loadOptionMetaByUserIdAndKey($userid, $key)
    {
        $query = sprintf("SELECT * FROM %s WHERE userid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $userid, $key) ;

        return $this->loadOptionMeta($query) ;
    }

    /**
     * Load Option Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     */
    function loadOptionMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf("SELECT * FROM %s WHERE swimmerid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $swimmerid, $key) ;

        return $this->loadOptionMeta($query) ;
    }

    /**
     * check if a record already exists
     * by unique id in the user profile table
     *
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existOptionMeta($query = null)
    {
        if (is_null($query))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Query")) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * check if a record already exists
     * by unique id in the user profile table
     *
     * @param - string - $query - SQL query string
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existMetaOptionByUserIdAndKey($query = null)
    {
        if (is_null($query))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Query")) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * Exist Option Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     */
    function existOptionMetaByUserIdAndKey($userid, $key)
    {
        $query = sprintf("SELECT ometaid FROM %s
            WHERE userid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $userid, $key) ;

        return $this->existOptionMeta($query) ;
    }

    /**
     * Exist Option Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     */
    function existOptionMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf("SELECT ometaid FROM %s
            WHERE swimmerid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $swimmerid, $key) ;

        return $this->existOptionMeta($query) ;
    }

    /**
     * save a user option meta record
     *
     * @return - integer - insert id
     */
    function saveUserOptionMeta()
    {
        $success = false ;

        if (is_null($this->getUserId()))
			wp_die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Id")) ;
        if (is_null($this->getOptionMetaKey()))
			wp_die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Key")) ;
        //  Update or new save?
 
        $update = $this->existOptionMetaByUserIdAndKey($this->getUserId(), $this->getOptionMetaKey()) ;

        if ($update)
            $query = sprintf("UPDATE %s ", WPST_OPTIONS_META_TABLE) ;
        else
            $query = sprintf("INSERT INTO %s ", WPST_OPTIONS_META_TABLE) ;

        $query .= sprintf("SET 
            userid=\"%s\",
            swimmerid=\"%s\",
            ometakey=\"%s\",
            ometavalue=\"%s\"",
            $this->getUserId(),
            WPST_NULL_ID,
            $this->getOptionMetaKey(),
            $this->getOptionMetaValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(" WHERE userid=\"%s\" AND ometakey=\"%s\"",
                $this->getUserId(), $this->getOptionMetaKey()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->setOptionMetaId($this->getInsertId()) ;
        }

        return $success ;
    }

    /**
     * save a swimmer option meta record
     *
     * @return - integer - insert id
     */
    function saveSwimmerOptionMeta()
    {
        $success = false ;

        if (is_null($this->getSwimmerId()))
			wp_die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Id")) ;
        if (is_null($this->getOptionMetaKey()))
			wp_die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Key")) ;
        //  Update or new save?
 
        $update = $this->existOptionMetaBySwimmerIdAndKey($this->getSwimmerId(), $this->getOptionMetaKey()) ;

        if ($update)
            $query = sprintf("UPDATE %s ", WPST_OPTIONS_META_TABLE) ;
        else
            $query = sprintf("INSERT INTO %s ", WPST_OPTIONS_META_TABLE) ;

        $query .= sprintf("SET 
            userid=\"%s\",
            swimmerid=\"%s\",
            ometakey=\"%s\",
            ometavalue=\"%s\"",
            WPST_NULL_ID,
            $this->getSwimmerId(),
            $this->getOptionMetaKey(),
            $this->getOptionMetaValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(" WHERE swimmerid=\"%s\" AND ometakey=\"%s\"",
                $this->getSwimmerId(), $this->getOptionMetaKey()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $this->setOptionMetaId($this->getInsertId()) ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Delete Option Meta data based on a query string
     *
     * @param - string - $query - SQL query string
     * @return - int - number of affected rows
     */
    function deleteOptionMeta($query = null)
    {
        if (is_null($query))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null Query")) ;
        $this->setQuery($query) ;
        $status = $this->runDeleteQuery() ;

        return ($status) ;
    }

    /**
     * Delete Option Meta by User Id - deletes
     * all Option Meta data associated with a User Id.
     *
     * @param - int - $userid - user id
     * @return - int - number of affected rows
     */
    function deleteOptionMetaByUserId($userid)
    {
        $query = sprintf("DELETE FROM %s WHERE userid='%s'",
            WPST_OPTIONS_META_TABLE, $userid) ;

        return $this->deleteOptionMeta($query) ;
    }

    /**
     * Delete Option Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     * @return - int - number of affected rows
     */
    function deleteOptionMetaByUserIdAndKey($userid, $key)
    {
        $query = sprintf("DELETE FROM %s
            WHERE userid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $userid, $key) ;

        return $this->deleteOptionMeta($query) ;
    }

    /**
     * Delete Option Meta by Swimmer Id - deletes
     * all Option Meta data associated with a Swimmer Id.
     *
     * @param - int - $swimmerid - swimmer id
     * @return - int - number of affected rows
     */
    function deleteOptionMetaBySwimmerId($swimmerid)
    {
        $query = sprintf("DELETE FROM %s WHERE swimmerid='%s'",
            WPST_OPTIONS_META_TABLE, $swimmerid) ;

        return $this->deleteOptionMeta($query) ;
    }

    /**
     * Delete Option Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     * @return - int - number of affected rows
     */
    function deleteOptionMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf("DELETE FROM %s
            WHERE swimmerid='%s' AND ometakey='%s'",
            WPST_OPTIONS_META_TABLE, $swimmerid, $key) ;

        return $this->deleteOptionMeta($query) ;
    }

    /**
     * Update Option Meta by Key
     *
     * @param - string - $key - option meta key
     * @param - string - $value - option meta value
     * @return - int - number of affected rows
     */
    function globalUpdateOptionMetaByKey($key = null, $value = null)
    {
        if (is_null($key)) $key = $this->getOptionMetaKey() ;

        if (is_null($key))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null option key.")) ;

        if (is_null($value)) $value = $this->getOptionMetaValue() ;

        if (is_null($value))
			die(sprintf("%s(%s):  %s", basename(__FILE__), __LINE__, "Null option value.")) ;

        $query .= sprintf("UPDATE %s SET ometavalue=\"%s\"
            WHERE ometakey=\"%s\"", WPST_OPTIONS_META_TABLE, $value, $key) ;

        $this->setQuery($query) ;
        return $this->runUpdateQuery() ;
    }
}
?>
