<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Reports classes.
 *
 * $Id: reportgen.class.php 1032 2013-10-25 16:09:03Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Reports
 * @version $Revision: 1032 $
 * @lastmodified $Date: 2013-10-25 12:09:03 -0400 (Fri, 25 Oct 2013) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('db.class.php') ;
require_once('jobs.class.php') ;
require_once('table.class.php') ;
require_once('seasons.class.php') ;
require_once('swimmers.class.php') ;
require_once('roster.class.php') ;
require_once('users.csv.class.php') ;
require_once('swimteam.include.php') ;

/**
 * Class definition of the base report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamReportGenerator extends SwimTeamDBI
{
    /**
     * first name field
     */
    var $__reporttitle = 'Swim Team Report' ;

    /**
     * first name field
     */
    var $__firstname = false ;

    /**
     * last name field
     */
    var $__lastname = false ;

    /**
     * internal id
     */
    var $__internalid = false ;

    /**
     * optional fields
     */
    var $__optionalfields = array() ;

    /**
     * optional field filters
     */
    var $__optionalfieldfilters = array() ;

    /**
     * optional field filter values
     */
    var $__optionalfieldfiltervalues = array() ;

    /**
     * record count
     */
    var $__recordcount = 0 ;

    /**
     * report table
     */
    var $__reporttable = null ;

    /**
     * set report title
     *
     * @param string - title of report
     */
    function setReportTitle($title)
    {
        $this->__reporttitle = $title ;
    }

    /**
     * get report title
     *
     * @return string - title of report
     */
    function getReportTitle()
    {
        return $this->__reporttitle ;
    }

    /**
     * set first name field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setFirstName($flag = true)
    {
        $this->__firstname = $flag ;
    }

    /**
     * get first name field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getFirstName()
    {
        return $this->__firstname ;
    }

    /**
     * set last name field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setLastName($flag = true)
    {
        $this->__lastname = $flag ;
    }

    /**
     * get last name field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getLastName()
    {
        return $this->__lastname ;
    }

    /**
     * set internal id field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setInternalId($flag = true)
    {
        $this->__internalid = $flag ;
    }

    /**
     * get internal id field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getInternalId()
    {
        return $this->__internalid ;
    }

    /**
     * set a optional field for inclusion
     *
     * @param string - optional field key
     * @param boolean - flag to turn field inclusion on or off
     */
    function setOptionalField($option, $flag = true)
    {
        $this->__optionalfields[$option] = $flag ;
    }

    /**
     * get a optional field for inclusion
     *
     * @param string - optional field key
     * @return boolean - flag to indicate field inclusion on or off
     */
    function getOptionalField($option)
    {
        return (array_key_exists($option, $this->__optionalfields) ?
            $this->__optionalfields[$option] : WPST_NULL_STRING) ;
    }

    /**
     * set optional field filter field inclusion
     *
     * @param string - optional field key
     * @param boolean - flag to turn field inclusion on or off
     */
    function setOptionalFieldFilter($option, $flag = true)
    {
        $this->__optionalfieldfilters[$option] = $flag ;
    }

    /**
     * get optional field filter field inclusion
     *
     * @param string - optional field key
     * @return boolean - flag to turn field inclusion on or off
     */
    function getOptionalFieldFilter($option)
    {
        return (array_key_exists($option, $this->__optionalfieldfilters) ?
            $this->__optionalfieldfilters[$option] : WPST_NULL_STRING) ;
    }

    /**
     * set optional field filter field value
     *
     * @param string - optional field key
     * @param string - value to use for field filter
     */
    function setOptionalFieldFilterValue($option, $value = WPST_NULL_STRING)
    {
        $this->__optionalfieldfiltervalues[$option] = $value ;
    }

    /**
     * get optional field filter field value
     *
     * @param string - optional field key
     * @return string - value to use for field filter
     */
    function getOptionalFieldFilterValue($option)
    {
        return (array_key_exists($option, $this->__optionalfieldfiltervalues) ?
            $this->__optionalfieldfiltervalues[$option] : WPST_NULL_STRING) ;
    }

    /**
     * Get report record count
     *
     * @return int - count of report records
     */
    function getRecordCount()
    {
        return $this->__recordcount ;
    }

    /**
     * Get report
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return $this->__reporttable ;
    }
}

/**
 * Class definition of the report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamReportGenerator
 */
class SwimTeamUsersReportGenerator extends SwimTeamReportGenerator
{
    /**
     * username field
     */
    var $__username = false ;

    /**
     * e-mail address field
     */
    var $__emailaddress = false ;

    /**
     * birth date field
     */
    var $__birthdate = false ;

    /**
     * street address 1 field
     */
    var $__streetaddress1 = false ;

    /**
     * street address 2 field
     */
    var $__streetaddress2 = false ;

    /**
     * street address 3 field
     */
    var $__streetaddress3 = false ;

    /**
     * city field
     */
    var $__city = false ;

    /**
     * state or province field
     */
    var $__stateorprovince = false ;

    /**
     * postal code field
     */
    var $__postalcode = false ;

    /**
     * country field
     */
    var $__country = false ;

    /**
     * primary phone field
     */
    var $__primaryphone = false ;

    /**
     * secondary phone field
     */
    var $__secondaryphone = false ;

    /**
     * contact information field
     */
    var $__contactinfo = false ;

    /**
     * secondary contact detail
     */
    var $__secondarycontactdetail = false ;

    /**
     * contact information filter
     */
    var $__contactinfofilter = false ;

    /**
     * set username field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setUsername($flag = true)
    {
        $this->__username = $flag ;
    }

    /**
     * get username field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getUsername()
    {
        return $this->__username ;
    }

    /**
     * set e-mail address field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setEmailAddress($flag = true)
    {
        $this->__emailaddress = $flag ;
    }

    /**
     * get e-mail address field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getEmailAddress()
    {
        return $this->__emailaddress ;
    }

    /**
     * set street address 1 field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStreetAddress1($flag = true)
    {
        $this->__streetaddress1 = $flag ;
    }

    /**
     * get street address 1 field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStreetAddress1()
    {
        return $this->__streetaddress1 ;
    }

    /**
     * set street address 2 field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStreetAddress2($flag = true)
    {
        $this->__streetaddress2 = $flag ;
    }

    /**
     * get street address 2 field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStreetAddress2()
    {
        return $this->__streetaddress2 ;
    }

    /**
     * set street address 3 field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStreetAddress3($flag = true)
    {
        $this->__streetaddress3 = $flag ;
    }

    /**
     * get street address 3 field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStreetAddress3()
    {
        return $this->__streetaddress3 ;
    }

    /**
     * set city field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setCity($flag = true)
    {
        $this->__city = $flag ;
    }

    /**
     * get city field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getCity()
    {
        return $this->__city ;
    }

    /**
     * set state or province field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStateOrProvince($flag = true)
    {
        $this->__stateorprovince = $flag ;
    }

    /**
     * get state or province field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStateOrProvince()
    {
        return $this->__stateorprovince ;
    }

    /**
     * set postal code field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setPostalCode($flag = true)
    {
        $this->__postalcode = $flag ;
    }

    /**
     * get postal code field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getPostalCode()
    {
        return $this->__postalcode ;
    }

    /**
     * set country field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setCountry($flag = true)
    {
        $this->__country = $flag ;
    }

    /**
     * get country field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getCountry()
    {
        return $this->__country ;
    }

    /**
     * set primary phone field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setPrimaryPhone($flag = true)
    {
        $this->__primaryphone = $flag ;
    }

    /**
     * get primary phone field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getPrimaryPhone()
    {
        return $this->__primaryphone ;
    }

    /**
     * set secondary phone field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setSecondaryPhone($flag = true)
    {
        $this->__secondaryphone = $flag ;
    }

    /**
     * get secondary phone field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getSecondaryPhone()
    {
        return $this->__secondaryphone ;
    }

    /**
     * set contact information field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setContactInformation($flag = true)
    {
        $this->__contactinfo = $flag ;
    }

    /**
     * get contact information field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getContactInformation()
    {
        return $this->__contactinfo ;
    }

    /**
     * set contact information filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setContactInformationFilter($flag = true)
    {
        $this->__contactinfofilter = $flag ;
    }

    /**
     * get contact information filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getContactInformationFilter()
    {
        return $this->__contactinfofilter ;
    }

    /**
     * get userids
     *
     * @return mixed - array of user ids
     */
    function getUserIds()
    {
        $u = array() ;
        $user = new SwimTeamUserProfile() ;
        $userIds = $user->getUserIds(false, true, $this->getFilter()) ;

        //  Strip the extra level of array off the values

        foreach ($userIds as $userId)
            $u[] = $userId['userid'] ;

        return $u ;
    }

    /**
     * Create the filter used to during the report generation.
     *
     * @return string - filter - filter string used with SQL WHERE clause.
     */
    function getFilter()
    {
        //  Construct filters

        $filter = '' ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Loop through the options and define the filter accordingly

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalFieldFilter($oconst))
                {
                    $filter .=
                        sprintf('%s%s.ometakey="%s" AND %s.ometavalue="%s"',
                        ($filter == '' ? '' : ' AND '), WPST_OPTIONS_META_TABLE,
                        $oconst, WPST_OPTIONS_META_TABLE,
                        $this->getOptionalFieldFilterValue($oconst)) ;
                }
            }
        }

        //  If any optional field filters were added then
        //  need to add another term to make sure only the 
        //  proper rows are selected.

        if (!empty($filter))
        {
            $filter .= sprintf(' AND %s.userid = %susers.ID',
                WPST_OPTIONS_META_TABLE, WP_DB_PREFIX) ;
        }

        //  Contact filter?

        if ($this->getContactInformationFilter())
            $filter .= sprintf('%scontactinfo="%s"',
                ($filter == '' ? '' : ' AND '), $this->getContactInformationFilterValue()) ;

        return $filter ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableHeader(&$tr)
    {
        if ($this->getInternalId()) $tr[] = 'Internal Id' ;
        if ($this->getFirstName()) $tr[] = 'First Name' ;
        if ($this->getLastName()) $tr[] = 'Last Name' ;
        if ($this->getUsername()) $tr[] = 'Username' ;
        if ($this->getEmailAddress()) $tr[] = 'E-mail Address' ;
        if ($this->getStreetAddress1()) $tr[] = 'Street Address 1' ;
        if ($this->getStreetAddress2()) $tr[] = 'Street Address 2' ;
        if ($this->getStreetAddress3()) $tr[] = 'Street Address 3' ;
        if ($this->getCity()) $tr[] = 'City' ;
        if ($this->getStateOrProvince()) $tr[] = 
            get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        if ($this->getPostalCode()) $tr[] = 
            get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        if ($this->getCountry()) $tr[] = 'Country' ;
        if ($this->getPrimaryPhone())
            $tr[] = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        if ($this->getSecondaryPhone())
            $tr[] = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        if ($this->getContactInformation()) $tr[] = 'Contact Information' ;
        //if ($this->getTShirtSize()) $tr[] = 'T-Shirt Size' ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $tr[] = get_option($lconst) ;
                }
            }
        }

        return $tr ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableRow(&$u, &$om, &$tr)
    {
        //  Internal Id

        if ($this->getInternalId())
            $tr[] = $u->getId() ;

        if ($this->getFirstName())
            $tr[] = $u->getFirstName() ;

        if ($this->getLastName())
            $tr[] = $u->getLastName() ;

        if ($this->getUsername())
            $tr[] = $u->getUsername() ;

        if ($this->getEmailAddress())
            $tr[] = $u->getEmailAddress() ;

        if ($this->getStreetAddress1())
            $tr[] = $u->getStreet1() ;

        if ($this->getStreetAddress2())
            $tr[] = $u->getStreet2() ;

        if ($this->getStreetAddress3())
            $tr[] = $u->getStreet3() ;

        if ($this->getCity())
            $tr[] = $u->getCity() ;

        if ($this->getStateOrProvince())
            $tr[] = $u->getStateOrProvince() ;

        if ($this->getPostalCode())
            $tr[] = $u->getPostalCode() ;

        if ($this->getCountry())
            $tr[] = $u->getCountry() ;

        if ($this->getPrimaryPhone())
            $tr[] = $u->getPrimaryPhone() ;

        if ($this->getSecondaryPhone())
            $tr[] = $u->getSecondaryPhone() ;

        if ($this->getContactInformation())
            $tr[] = $u->getContactInfo() ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        $om->setUserId($u->getUserId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
        
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $om->loadOptionMetaByUserIdAndKey($u->getUserId(), $oconst) ;
                    $tr[] = $om->getOptionMetaValue() ;
                }
            }
        }

        return $tr ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateHTMLReport() ;
    }

    /**
     * Generate the HTML Report
     *
     */
    function generateHTMLReport()
    {
        $this->__reporttable = new SwimTeamInfoTable($this->getReportTitle(), '100%') ;
        $table = &$this->__reporttable ;
        $table->set_alt_color_flag(true) ;

        $season = new SwimTeamSeason() ;

        $swimmer = new SwimTeamSwimmer() ;

        $tr = array() ;

        $tr = $this->getHTMLTableHeader($tr) ;

        //  Generate the column headers
 
        for ($i = 0 ; $i < count($tr) ; $i++)
            $table->set_column_header($i, $tr[$i], null, 'left') ;

        //  Get all the user ids using the appropriate filter

        $user = new SwimTeamUserProfile() ;
        $ometa = new SwimTeamOptionMeta() ;
        //$userIds = $user->getUserIds(false, true, $this->getFilter()) ;
        $userIds = $this->getUserIds() ;

        //  Loop through the users

        $this->__recordcount = 0 ;

        foreach ($userIds as $userId)
        {
            $this->__recordcount++ ;

            //$valid = $user->loadUserProfileByUserId($userId['userid']) ;
            $valid = $user->loadUserProfileByUserId($userId) ;

            //  The query will be invalid if the user exists in the standard
            //  WordPress user table but doesn't exist in the wp-SwimTeam user
            //  table.  Force the user id so the report will emit something useful.

            //if (!$valid) $user->setId($userId['userid']) ;
            if (!$valid) $user->setId($userId) ;

            $tr = array() ;
            $tr = $this->getHTMLTableRow($user, $ometa, $tr) ;

            //  Can't simply add a row to the table because we
            //  don't know how many cells the table has.  Use this
            //  PHP trick to pass an undetermined number of arguments
            //  to a method.

            call_user_func_array(array($table, 'add_row'), $tr);
        }
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getHTMLReport()
    {
        return $this->__reporttable ;
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return $this->getHTMLReport() ;
    }
}

/**
 * Class definition of the CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamUsersReportGenerator
 */
class SwimTeamUsersReportGeneratorCSV extends SwimTeamUsersReportGenerator
{
    /**
     * csv data
     */
    var $__csvData ;

    /**
     * csv File
     */
    var $__csvFile ;

    /**
     * csv record count
     */
    var $__csvCount ;

    /**
     * Get CSV record count
     *
     * @return int - count of CSV records
     */
    function getCSVCount()
    {
        return $this->__csvCount ;
    }

    /**
     * Get CSV file name
     *
     * @return string - CSV file name
     */
    function getCSVFile()
    {
        return $this->__csvFile ;
    }

    /**
     * Set CSV file name
     *
     * @param string - CSV file name
     */
    function setCSVFile($f)
    {
        $this->__csvFile = $f ;
    }

   /**
     * Get CSV report
     *
     * @return mixed - report CSV
     */
    function getCSVReport()
    {
        return new Container(html_pre($this->__csvData)) ;
    }

    /**
     * Get report
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return parent::getHTMLReport() ;
    }

    /**
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getCSVHeader($eol = false)
    {
        $csv = '' ;
        //  Generate the column headers
 
        if ($this->getFirstName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"First Name"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Last Name"' ;

        if ($this->getUsername())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Userame"' ;

        if ($this->getEmailAddress())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"E-mail Address"' ;

        if ($this->getStreetAddress1())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Street Address 1"' ;

        if ($this->getStreetAddress2())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Street Address 2"' ;

        if ($this->getStreetAddress3())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Street Address 3"' ;

        if ($this->getCity())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"City"' ;

        
        if ($this->getStateOrProvince())
        {
            $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' . $label . '"' ;
        }

        if ($this->getPostalCode())
        {
            $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' . $label . '"' ;
        }

        if ($this->getCountry())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Country"' ;

        if ($this->getPrimaryPhone())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) . '"' ;

        if ($this->getSecondaryPhone())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
            get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL). '"' ;

        if ($this->getContactInformation())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Contact Information"' ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;
            
        //  Handle the optional fields
        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                        get_option($lconst) . '"' ;
                }
            }
        }

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - user profile record, passed by reference
     * @param mixed - user meta record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$u, &$om, $eol = false)
    {
        $csv = '' ;

        if ($this->getInternalId())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getId() . '"' ;

        if ($this->getFirstName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getFirstName() . '"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getLastName() . '"' ;

        if ($this->getUsername())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getUsername() . '"' ;

        if ($this->getEmailAddress())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getEmailAddress() . '"' ;

        if ($this->getStreetAddress1())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getStreet1() . '"' ;

        if ($this->getStreetAddress2())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getStreet2() . '"' ;

        if ($this->getStreetAddress3())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getStreet3() . '"' ;

        if ($this->getCity())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getCity() . '"' ;

        if ($this->getStateOrProvince())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getStateOrProvince() . '"' ;

        if ($this->getPostalCode())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getPostalCode() . '"' ;

        if ($this->getCountry())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getCountry() . '"' ;

        if ($this->getPrimaryPhone())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getPrimaryPhone() . '"' ;

        if ($this->getSecondaryPhone())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getSecondaryPhone() . '"' ;

        if ($this->getContactInformation())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $u->getContactInfo() . '"' ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        $om->setUserId($u->getUserId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
        
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $om->loadOptionMetaByUserIdAndKey($u->getUserId(), $oconst) ;
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                        '"' . ucfirst($om->getOptionMetaValue()) . '"' ;

                }
            }
        }

        //  Terminate the string?

        if ($eol) $csv .= $csvRow . "\r\n" ;

        return $csv ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateCSVReport() ;

        //  Generate the HTML representation too!

        parent::generateHTMLReport() ;
    }

    /**
     * Generate the Report
     *
     */
    function generateCSVReport()
    {
        $this->__csvData = '' ;

        $csv = &$this->__csvData ;

        $season = new SwimTeamSeason() ;
        $swimmer = new SwimTeamSwimmer() ;
        $user = new SwimTeamUsersCSV() ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . $this->getCSVHeader() ;
        $csv .= "\r\n" ;

        $this->__recordcount = 0 ;

        //  Get all the user ids using the appropriate filter

        $user = new SwimTeamUserProfile() ;
        $ometa = new SwimTeamOptionMeta() ;
        $userIds = $this->getUserIds() ;

        //  Loop through the users

        $this->__recordcount = 0 ;

        foreach ($userIds as $userId)
        {
            $this->__recordcount++ ;

            $valid = $user->loadUserProfileByUserId($userId) ;

            //  The query will be invalid if the user exists in the standard
            //  WordPress user table but doesn't exist in the wp-SwimTeam user
            //  table.  Force the user id so the report will emit something useful.

            if (!$valid) $user->setId($userId) ;

            $csv .= $this->getCSVRecord($user, $ometa) ;

            $csv .= "\r\n" ;
        }
    }

    /**
     * Write the CSV data to a file which can be sent to the browser
     *
     */
    function generateCSVFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setCSVFile(tempnam(ABSPATH .
            '/' . get_option('upload_path'), 'CSV')) ;

        $this->setCSVFile(tempnam('', 'CSV')) ;

        //  Write the CSV data to the file

        $f = fopen($this->getCSVFile(), 'w') ;
        fwrite($f, $this->__csvData) ;
        fclose($f) ;
    }
}

/**
 * Class definition of the Job Assignments report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamUsersReportGeneratorCSV
 */
class SwimTeamJobAssignmentsReportGenerator extends SwimTeamUsersReportGeneratorCSV
{
    /**
     * job position field
     */
    var $__jobposition = false ;

    /**
     * job description field
     */
    var $__jobdescription = false ;

    /**
     * job duration field
     */
    var $__jobduration = false ;

    /**
     * job type field
     */
    var $__jobtype = false ;

    /**
     * job credits field
     */
    var $__jobcredits = false ;

    /**
     * job notes field
     */
    var $__jobnotes = false ;

    /**
     * job duration filter
     */
    var $__jobdurationfilter = false ;

    /**
     * job duration filter
     */
    var $__jobtypefilter = false ;

    /**
     * swim meet ids
     */
    var $__swimmeetids = array() ;

    /**
     * set job position field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobPosition($flag = true)
    {
        $this->__jobposition = $flag ;
    }

    /**
     * get job position field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobPosition()
    {
        return $this->__jobposition ;
    }

    /**
     * set job description field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobDescription($flag = true)
    {
        $this->__jobdescription = $flag ;
    }

    /**
     * get job description field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobDescription()
    {
        return $this->__jobdescription ;
    }

    /**
     * set job duration field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobDuration($flag = true)
    {
        $this->__jobduration = $flag ;
    }

    /**
     * get job duration field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobDuration()
    {
        return $this->__jobduration ;
    }

    /**
     * set job type field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobType($flag = true)
    {
        $this->__jobtype = $flag ;
    }

    /**
     * get job type field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobType()
    {
        return $this->__jobtype ;
    }

    /**
     * set job credits field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobCredits($flag = true)
    {
        $this->__jobcredits = $flag ;
    }

    /**
     * get job credits field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobCredits()
    {
        return $this->__jobcredits ;
    }

    /**
     * set job notes field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobNotes($flag = true)
    {
        $this->__jobnotes = $flag ;
    }

    /**
     * get job notes field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobNotes()
    {
        return $this->__jobnotes ;
    }

    /**
     * set job duration filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobDurationFilter($flag = true)
    {
        $this->__jobdurationfilter = $flag ;
    }

    /**
     * get job duration filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobDurationFilter()
    {
        return $this->__jobdurationfilter ;
    }

    /**
     * set job type filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setJobTypeFilter($flag = true)
    {
        $this->__jobtypefilter = $flag ;
    }

    /**
     * get job type filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getJobTypeFilter()
    {
        return $this->__jobtypefilter ;
    }

    /**
     * set swim meet ids
     *
     * @param mixed - array of swim meet ids
     */
    function setSwimMeetIds($ids)
    {
        $this->__swimmeetids = $ids ;
    }

    /**
     * get swim meet ids
     *
     * @return mixed - array of swim meet ids
     */
    function getSwimMeetIds()
    {
        return $this->__swimmeetids ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableHeader(&$tr)
    {
        $tr = array('Date', 'Opponent', 'Location') ;
        if ($this->getJobPosition()) $tr[] = 'Position' ;
        if ($this->getJobDescription()) $tr[] = 'Description' ;
        if ($this->getJobDuration()) $tr[] = 'Duration' ;
        if ($this->getJobType()) $tr[] = 'Type' ;
        if ($this->getJobCredits()) $tr[] = 'Credits' ;
        if ($this->getJobNotes()) $tr[] = 'Notes' ;

        $tr = parent::getHTMLTableHeader($tr) ;

        return $tr ;
    }

    /**
     * Get HTML table row
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableRow(&$ja, &$job, &$u, &$om, &$tr)
    {
        if ($this->getJobPosition())
            $tr[] = $job->getJobPosition() ;

        if ($this->getJobDescription())
            $tr[] = $job->getJobDescription() ;

        if ($this->getJobDuration())
            $tr[] = ucwords($job->getJobDuration()) ;

        if ($this->getJobType())
            $tr[] = ucwords($job->getJobType()) ;

        if ($this->getJobCredits())
            $tr[] = $job->getJobCredits() ;

        if ($this->getJobNotes())
            $tr[] = $job->getJobNotes() ;

        $tr = parent::getHTMLTableRow($u, $om, $tr) ;

        return $tr ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateHTMLReport() ;
    }

    /**
     * Generate the HTML Report
     *
     */
    function generateHTMLReport()
    {
        $this->__reporttable = new SwimTeamInfoTable($this->getReportTitle(), '100%') ;
        $table = &$this->__reporttable ;
        $table->set_alt_color_flag(true) ;

        $swimmeet = new SwimMeet() ;
        $season = new SwimTeamSeason() ;
        $swimmer = new SwimTeamSwimmer() ;
        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $user = new SwimTeamUserProfile() ;
        $ometa = new SwimTeamOptionMeta() ;

        $tr = array() ;

        $tr = $this->getHTMLTableHeader($tr) ;

        //  Generate the column headers
 
        for ($i = 0 ; $i < count($tr) ; $i++)
            $table->set_column_header($i, $tr[$i], null, 'left') ;

        //  Loop through the swim meet Ids

        $swimmeetids = $this->getSwimMeetIds() ;

        foreach ($swimmeetids as $swimmeetid)
        {
            $ja->setMeetId($swimmeetid) ;

            $swimmeet->loadSwimMeetByMeetId($swimmeetid) ;

            //  Get season long job ids for all users (admin)
            //  or just the current user (non-admin users)

            if (current_user_can('edit_posts'))
            {
                $jaids = $ja->getJobAssignmentIdsBySeasonId($swimmeet->getSeasonId()) ;
            }
            else
            {
                global $current_user ;
                get_currentuserinfo() ;
                $jaids = $ja->getJobAssignmentIdsBySeasonIdAndUserId($swimmeet->getSeasonId(), $current_user->ID) ;
            }

            if (is_null($jaids)) $jaids = array() ;

            //  Get meet job ids for all users (admin)
            //  or just the current user (non-admin users)
            //
            //  Merge with meet job ids

            if (current_user_can('edit_posts'))
            {
                $jaids = array_merge($jaids, $ja->getJobAssignmentIdsByMeetId(null, true)) ;
            }
            else
            {
                global $current_user ;
                get_currentuserinfo() ;
                $jaids = array_merge($jaids, $ja->getJobAssignmentIdsByMeetIdAndUserId(null, $current_user->ID, true)) ;
            }

            if (!empty($jaids))
            {
                //  Add job assignments

                foreach ($jaids as $jaid)
                {
                    $this->__recordcount++ ;

                    $row = array() ;
                    $key = &$jaid['jobassignmentid'] ;

                    $ja->loadJobAssignmentByJobAssignmentId($key) ;
                    $job->loadJobByJobId($ja->getJobId()) ;

                    $user->loadUserProfileByUserId($ja->getUserId()) ;
        
                    $tr = SwimTeamTextMap::__mapMeetIdToText($swimmeetid) ;
                    $tr = $this->getHTMLTableRow($ja, $job, $user, $ometa, $tr) ;
        
                    //  Can't simply add a row to the table because we
                    //  don't know how many cells the table has.  Use this
                    //  PHP trick to pass an undetermined number of arguments
                    //  to a method.
        
                    call_user_func_array(array($table, 'add_row'), $tr);
                }
            }
        }
    }

    /**
     * Get report
     *
     * @return html_table - report table
     */
    function getReport()
    {
            return parent::getHTMLReport() ;
    }
}

/**
 * Class definition of the CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamJobAssignmentsReportGenerator
 */
class SwimTeamJobAssignmentsReportGeneratorCSV extends SwimTeamJobAssignmentsReportGenerator
{
    /**
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getCSVHeader($eol = false)
    {
        //  Generate the column headers

        $csv = '"Date","Opponent","Location"' ;

        if ($this->getJobPosition() )
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Position"' ;

        if ($this->getJobDescription())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Description"' ;

        if ($this->getJobDuration())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Duration"' ;

        if ($this->getJobType())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Type"' ;

        if ($this->getJobCredits())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Credits"' ;

        if ($this->getJobNotes())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Notes"' ;

        //  Call the parent CSV Header method to get the rest of the header fields

        $csv .= ',' . parent::getCSVHeader(false) ;

        //  Handle line endings

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - user profile record, passed by reference
     * @param mixed - user meta record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$ja, &$job, &$u, &$om, $eol = false)
    {
        if ($this->getJobPosition())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $job->getJobPosition() . '"' ;

        if ($this->getJobDescription())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $job->getJobDescription() . '"' ;

        if ($this->getJobDuration())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucwords($job->getJobDuration()) . '"' ;

        if ($this->getJobType())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucwords($job->getJobType()) . '"' ;

        if ($this->getJobCredits())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $job->getJobCredits() . '"' ;

        if ($this->getJobNotes())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $job->getJobNotes() . '"' ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
            parent::getCSVRecord($u, $om, false) ;

        //  Terminate the string?

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateCSVReport() ;

        //  Generate the HTML representation too!

        parent::generateHTMLReport() ;
    }

    /**
     * Generate the CSV Report
     *
     */
    function generateCSVReport()
    {
        $this->__csvData = '' ;
        $this->__recordcount = 0 ;

        $csv = &$this->__csvData ;

        $swimmeet = new SwimMeet() ;
        $season = new SwimTeamSeason() ;
        $swimmer = new SwimTeamSwimmer() ;
        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $user = new SwimTeamUserProfile() ;
        $ometa = new SwimTeamOptionMeta() ;

        //  Generate the column headers
        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . $this->getCSVHeader() ;
        $csv .= "\r\n" ;

        //  Loop through the swim meet Ids

        $swimmeetids = $this->getSwimMeetIds() ;

        foreach ($swimmeetids as $swimmeetid)
        {
            $ja->setMeetId($swimmeetid) ;

            $swimmeet->loadSwimMeetByMeetId($swimmeetid) ;

            //  Get season long job ids for all users (admin)
            //  or just the current user (non-admin users)

            if (current_user_can('edit_posts'))
            {
                $jaids = $ja->getJobAssignmentIdsBySeasonId($swimmeet->getSeasonId()) ;
            }
            else
            {
                global $current_user ;
                get_currentuserinfo() ;
                $jaids = $ja->getJobAssignmentIdsBySeasonIdAndUserId($swimmeet->getSeasonId(), $current_user->ID) ;
            }

            if (is_null($jaids)) $jaids = array() ;

            //  Get meet job ids for all users (admin)
            //  or just the current user (non-admin users)
            //
            //  Merge with meet job ids

            if (current_user_can('edit_posts'))
            {
                $jaids = array_merge($jaids, $ja->getJobAssignmentIdsByMeetId(null, true)) ;
            }
            else
            {
                global $current_user ;
                get_currentuserinfo() ;
                $jaids = array_merge($jaids, $ja->getJobAssignmentIdsByMeetIdAndUserId(null, $current_user->ID, true)) ;
            }

            if (!empty($jaids))
            {
                //  Add job assignments

                foreach ($jaids as $jaid)
                {
                    $this->__recordcount++ ;

                    $row = array() ;
                    $key = &$jaid['jobassignmentid'] ;

                    $ja->loadJobAssignmentByJobAssignmentId($key) ;
                    $job->loadJobByJobId($ja->getJobId()) ;

                    $user->loadUserProfileByUserId($ja->getUserId()) ;
        
                    $detail = SwimTeamTextMap::__mapMeetIdToText($swimmeetid) ;
                    $csv .= '"' . $detail['date'] . '",' ;
                    $csv .= '"' . $detail['opponent'] . '",' ;
                    $csv .= '"' . $detail['location'] . '",' ;
                    $csv .= $this->getCSVRecord($ja, $job, $user, $ometa, true) ;
                }
            }
        }
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return parent::getHTMLReport() ;
    }
}

/**
 * Class definition of the Job Commitments report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamUsersReportGeneratorCSV
 */
class SwimTeamJobCommitmentsReportGenerator extends SwimTeamUsersReportGeneratorCSV
{
    /**
     * Credits property
     */
    var $__credits = array() ;

    /**
     * Season Id property
     */
    var $__seasonid ;

    /**
     * Set the credits
     *
     * @param - int - credits
     */
    function setCredits($userid, $credits)
    {
        $this->__credits[$userid] = $credits ;
    }

    /**
     * Get the credits
     *
     * @return - int - credits
     */
    function getCredits($userid)
    {
        return ($this->__credits[$userid]) ;
    }

    /**
     * Set the season id
     *
     * @param - int - id of the season
     */
    function setSeasonId($id)
    {
        $this->__seasonid = $id ;
    }

    /**
     * Get the season id
     *
     * @return - int - id of the season
     */
    function getSeasonId()
    {
        return ($this->__seasonid) ;
    }

    /**
     * Get the user ids
     *
     * @return - mixed - array of user ids
     */
    function getUserIds()
    {
        return (array_keys($this->__credits)) ;
    }

    /**
     *  Calcualte Credits - determine how many credits each user is committed to
     *
     *  @param - int $seasonid - the season id to base the calculation on
     */
    function CalculateCredits($seasonid = null)
    {
        $swimmer = new SwimTeamSwimmer() ;
        $user = new SwimTeamUserProfile() ;
        $ja = new SwimTeamJobAssignment() ;
        $roster = new SwimTeamRoster() ;
        $roster->setSeasonId($seasonid) ;

        $userids = $user->getUserIds() ;

        //  Loop through the users

        foreach ($userids as $userid)
        {
            $activeswimmers = false ;

            //  Select the swimmers connected to the user

            //$filter = sprintf('(%s.contact1id="%s" OR %s.contact2id="%s")',
            //    WPST_SWIMMERS_TABLE, $userid['userid'], WPST_SWIMMERS_TABLE, $userid['userid']) ;
            $filter = sprintf('(s.contact1id="%s" OR s.contact2id="%s")', $userid['userid'], $userid['userid']) ;

            $swimmerids = $swimmer->getAllSwimmerIds($filter) ;

            //  Loop through the swimmers, determine if any are active
 
            foreach ($swimmerids as $swimmerid)
            {
                $roster->setSwimmerId($swimmerid['swimmerid']) ;
                $activeswimmers |= $roster->isSwimmerRegistered() ;
            }

            //  If user has active swimmers then include them in the report

            if ($activeswimmers)
            {
                $credits = 0 ;

                $ja->setUserId($userid['userid']) ;
                $ja->setSeasonId($seasonid) ;
                $jaids = $ja->getJobAssignmentIdsBySeasonIdAndUserId(null, null, false) ;

                //  Loop through the Job assignment ids and calculate credits

                foreach ($jaids as $jaid)
                {
                    $ja->loadJobAssignmentByJobAssignmentId($jaid['jobassignmentid']) ;
                    $ja->loadJobByJobId($ja->getJobId()) ;

                    $credits += $ja->getJobCredits() ;
                }

                $this->setCredits($userid['userid'], $credits) ;
            }
        }
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableHeader(&$tr)
    {
        $tr = parent::getHTMLTableHeader($tr) ;
        $tr[] = 'Credits' ;

        return $tr ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableRow(&$u, &$om, &$tr)
    {
        $tr = parent::getHTMLTableRow($u, $om, $tr) ;
        $credits = $this->getCredits($u->getUserId()) ;

        $credits = ($credits != null) ? $credits : 0 ;

        //  If the credits is below the threshhold, show it in red!

        if ($credits < get_option(WPST_OPTION_JOB_CREDITS_REQUIRED))
        {
            $span = html_span(null, ($credits != null) ? $credits : '0') ;
            $span->set_style('font-weight: bold; color: red;') ;
            $tr[] = $span ;
        }
        else
        {
            $tr[] = ($credits != null) ? $credits : '0' ;
        }

        return $tr ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateHTMLReport() ;
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return parent::getHTMLReport() ;
    }
}

/**
 * Class definition of the Job Commitments CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamJobCommitmentsReportGenerator
 */
class SwimTeamJobCommitmentsReportGeneratorCSV extends SwimTeamJobCommitmentsReportGenerator
{
    /**
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getCSVHeader($eol = false)
    {
        $csv = parent::getCSVHeader() ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Credits"' ;

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - user profile record, passed by reference
     * @param mixed - user meta record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$u, &$om, $eol = false)
    {
        $csv = parent::getCSVRecord($u, $om) ;

        $credits = $this->getCredits($u->getUserId()) ;

        $credits = ($credits != null) ? $credits : 0 ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' . $credits. '"' ;

        if ($eol) $csv .= "\r\n" ;

        return $csv ;

    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateCSVReport() ;

        //  Generate the HTML representation too!

        parent::generateHTMLReport() ;
    }

    /**
     * Get CSV report table
     *
     * @return mixed - report table array
     */
    function getReport()
    {
        return parent::getHTMLReport() ;
    }
}

/**
 * Class definition of the report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamReportGenerator
 */
class SwimTeamSwimmersReportGenerator extends SwimTeamReportGenerator
{
    /**
     * middle name field
     */
    var $__middlename = false ;

    /**
     * nick name field
     */
    var $__nickname = false ;

    /**
     * nick name override
     */
    var $__nickname_override = false ;

    /**
     * birth date field
     */
    var $__birthdate = false ;

    /**
     * gender field
     */
    var $__gender = false ;

    /**
     * status field
     */
    var $__status = false ;

    /**
     * age field
     */
    var $__age = false ;

    /**
     * age group field
     */
    var $__agegroup = false ;

    /**
     * primary contact
     */
    var $__primarycontact = false ;

    /**
     * primary contact detail
     */
    var $__primarycontactdetail = false ;

    /**
     * secondary contact
     */
    var $__secondarycontact = false ;

    /**
     * secondary contact detail
     */
    var $__secondarycontactdetail = false ;

    /**
     * results
     */
    var $__results = false ;

    /**
     * swimmer label
     */
    var $__swimmerlabel = false ;

    /**
     * web site id
     */
    var $__websiteid = false ;

    /**
     * gender filter
     */
    var $__genderfilter = false ;

    /**
     * gender filter value
     */
    var $__genderfiltervalue = WPST_NULL_STRING ;

    /**
     * status filter
     */
    var $__statusfilter = false ;

    /**
     * status filter value
     */
    var $__statusfiltervalue = WPST_NULL_STRING ;

    /**
     * results filter
     */
    var $__resultsfilter = false ;

    /**
     * results filter value
     */
    var $__resultsfiltervalue = WPST_PUBLIC ;

    /**
     * current roster
     */
    var $__currentroster = null ;

    /**
     * Get current swimmer label if it exists
     *
     * Label is a challenge because it is tied to
     * the current roster and not to the swimmer.

     * @return string - current swimmer label
     */
    function getCurrentSwimmerLabel($swimmerid)
    {
        if (is_null($this->__currentroster))
        {
            $this->__currentroster = new SwimTeamRoster() ;

            $season = new SwimTeamSeason() ;
            //$season->loadActiveSeason() ;

            $this->__currentroster->setSeasonId($season->getActiveSeasonId()) ;
        }

        $this->__currentroster->setSwimmerId($swimmerid) ;
        $this->__currentroster->loadRosterBySeasonIdAndSwimmerId() ;

        $label = $this->__currentroster->getSwimmerLabel() ;

        if (is_null($label) || ($label == WPST_NULL_STRING)) $label = strtoupper(WPST_NA) ;

        return $label ;
    }

    /**
     * set middle name field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setMiddleName($flag = true)
    {
        $this->__middlename = $flag ;
    }

    /**
     * get middle name field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getMiddleName()
    {
        return $this->__middlename ;
    }

    /**
     * set nick name field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setNickName($flag = true)
    {
        $this->__nickname = $flag ;
    }

    /**
     * get middle name field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getNickName()
    {
        return $this->__nickname ;
    }

    /**
     * set nick name override
     *
     * @param boolean - flag to turn override on or off
     */
    function setNickNameOverride($flag = true)
    {
        $this->__nickname_override = $flag ;
    }

    /**
     * get nick name override
     *
     * @return boolean - flag to turn override on or off
     */
    function getNickNameOverride()
    {
        return $this->__nickname_override ;
    }

    /**
     * set birth date field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setBirthDate($flag = true)
    {
        $this->__birthdate = $flag ;
    }

    /**
     * get birth date field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getBirthDate()
    {
        return $this->__birthdate ;
    }

    /**
     * set gender field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setGender($flag = true)
    {
        $this->__gender = $flag ;
    }

    /**
     * get gender field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getGender()
    {
        return $this->__gender ;
    }

    /**
     * set age field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setAge($flag = true)
    {
        $this->__age = $flag ;
    }

    /**
     * get age field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getAge()
    {
        return $this->__age ;
    }

    /**
     * set age group field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setAgeGroup($flag = true)
    {
        $this->__agegroup = $flag ;
    }

    /**
     * get age group field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getAgeGroup()
    {
        return $this->__agegroup ;
    }

    /**
     * set primary contact field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setPrimaryContact($flag = true)
    {
        $this->__primarycontact = $flag ;
    }

    /**
     * get primary contact field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getPrimaryContact()
    {
        return $this->__primarycontact ;
    }

    /**
     * set primary contact detail field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setPrimaryContactDetail($flag = true)
    {
        $this->__primarycontactdetail = $flag ;
    }

    /**
     * get primary contact detail field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getPrimaryContactDetail()
    {
        return $this->__primarycontactdetail ;
    }

    /**
     * set secondary contact field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setSecondaryContact($flag = true)
    {
        $this->__secondarycontact = $flag ;
    }

    /**
     * get secondary contact field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getSecondaryContact()
    {
        return $this->__secondarycontact ;
    }

    /**
     * set secondary contact detail field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setSecondaryContactDetail($flag = true)
    {
        $this->__secondarycontactdetail = $flag ;
    }

    /**
     * get secondary contact detail field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getSecondaryContactDetail()
    {
        return $this->__secondarycontactdetail ;
    }

    /**
     * set results field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setResults($flag = true)
    {
        $this->__results = $flag ;
    }

    /**
     * get results field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getResults()
    {
        return $this->__results ;
    }

    /**
     * set status field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStatus($flag = true)
    {
        $this->__status = $flag ;
    }

    /**
     * get status field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStatus()
    {
        return $this->__status ;
    }

    /**
     * set swimmer label field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setSwimmerLabel($flag = true)
    {
        $this->__swimmerlabel = $flag ;
    }

    /**
     * get swimmer label field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getSwimmerLabel()
    {
        return $this->__swimmerlabel ;
    }

    /**
     * set web site id field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setWebSiteId($flag = true)
    {
        $this->__websiteid = $flag ;
    }

    /**
     * get web site id field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getWebSiteId()
    {
        return $this->__websiteid ;
    }

    /**
     * set gender filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setGenderFilter($flag = true)
    {
        $this->__genderfilter = $flag ;
    }

    /**
     * get gender filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getGenderFilter()
    {
        return $this->__genderfilter ;
    }

    /**
     * set gender filter field value
     *
     * @param string - value to use to filter report
     */
    function setGenderFilterValue($filter = '')
    {
        $this->__genderfiltervalue = $filter ;
    }

    /**
     * get gender filter field value
     *
     * @return string - value to use to filter report
     */
    function getGenderFilterValue()
    {
        return $this->__genderfiltervalue ;
    }

    /**
     * set status filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setStatusFilter($flag = true)
    {
        $this->__statusfilter = $flag ;
    }

    /**
     * get status filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getStatusFilter()
    {
        return $this->__statusfilter ;
    }

    /**
     * set status filter field value
     *
     * @param string - value to use to filter report
     */
    function setStatusFilterValue($filter = '')
    {
        $this->__statusfiltervalue = $filter ;
    }

    /**
     * get status filter field value
     *
     * @return string - value to use to filter report
     */
    function getStatusFilterValue()
    {
        return $this->__statusfiltervalue ;
    }

    /**
     * set results filter field inclusion
     *
     * @param boolean - flag to turn field inclusion on or off
     */
    function setResultsFilter($flag = true)
    {
        $this->__resultsfilter = $flag ;
    }

    /**
     * get results filter field inclusion
     *
     * @return boolean - flag to turn field inclusion on or off
     */
    function getResultsFilter()
    {
        return $this->__resultsfilter ;
    }

    /**
     * set results filter field value
     *
     * @param string - value to use to filter report
     */
    function setResultsFilterValue($filter = '')
    {
        $this->__resultsfiltervalue = $filter ;
    }

    /**
     * get results filter field value
     *
     * @return string - value to use to filter report
     */
    function getResultsFilterValue()
    {
        return $this->__resultsfiltervalue ;
    }

    /**
     * Create the filter used to during the report generation.
     *
     * @return string - filter - filter string used with SQL WHERE clause.
     */
    function getFilter()
    {
        //  Construct filters

        $filter = '' ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Loop through the options and define the filter accordingly

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalFieldFilter($oconst))
                {
                    $filter .= sprintf('%sm.ometakey="%s" AND m.ometavalue="%s"',
                        ($filter == '' ? '' : ' AND '), $oconst, 
                        $this->getOptionalFieldFilterValue($oconst)) ;
                }
            }
        }

        //  If any optional field filters were added then
        //  need to add another term to make sure only the 
        //  proper rows are selected.

        if (!empty($filter))
        {
            //$filter .= sprintf(' AND %s.swimmerid = %s.id',
                //WPST_OPTIONS_META_TABLE, WPST_SWIMMERS_TABLE) ;
            $filter .= ' AND m.swimmerid=s.id' ;
        }

        //  Construct filters

        if ($this->getGenderFilter())
            $filter .= sprintf('%sgender="%s"',
                ($filter == '' ? '' : ' AND '), $this->getGenderFilterValue()) ;

        if ($this->getStatusFilter())
            $filter .= sprintf('%ss2.season_status = "%s" AND r.status ="%s"',
                ($filter == '' ? '' : ' AND '), WPST_ACTIVE, $this->getStatusFilterValue()) ;

        if ($this->getResultsFilter())
            $filter .= sprintf('%sresults="%s"',
                ($filter == '' ? '' : ' AND '), $this->getResultsFilterValue()) ;

        return $filter ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableHeader(&$tr)
    {
        if ($this->getInternalId()) $tr[] = 'Internal Id' ;
        if ($this->getFirstName()) $tr[] = 'First Name' ;
        if ($this->getMiddleName()) $tr[] = 'Middle Name' ;
        if ($this->getNickName()) $tr[] = 'Nick Name' ;
        if ($this->getLastName()) $tr[] = 'Last Name' ;
        if ($this->getBirthDate()) $tr[] = 'Birth Date' ;
        if ($this->getAge()) $tr[] = 'Age' ;
        if ($this->getAgeGroup()) $tr[] = 'Age Group' ;
        if ($this->getGender()) $tr[] = 'Gender' ;
        if ($this->getStatus()) $tr[] = 'Status' ;
        if ($this->getSwimmerLabel()) $tr[] = 'Swimmer Label' ;
        if ($this->getResults()) $tr[] = 'Results' ;
        if ($this->getWebSiteId()) $tr[] = 'Web Site Id' ;
        if ($this->getPrimaryContact()) $tr[] = 'Primary Contact' ;
        if ($this->getSecondaryContact()) $tr[] = 'Secondary Contact' ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $tr[] = get_option($lconst) ;
                }
            }
        }

        return $tr ;
    }

    /**
     * Get HTML table header
     *
     * @param mixed - array to populate
     * @return mixed - array of table headers
     */
    function getHTMLTableRow(&$s, &$om, $label, &$tr)
    {
        //  Internal Id

        if ($this->getInternalId()) $tr[] = $s->getSwimmerId() ;

        if ($this->getFirstName())
        {
            if ($this->getNickNameOverride() && $s->getNickName() != '')
                $tr[] = $s->getNickName() ;
            else
                $tr[] = $s->getFirstName() ;
        }

        if ($this->getMiddleName()) $tr[] = $s->getMiddleName() ;
        if ($this->getNickName()) $tr[] = $s->getNickName() ;
        if ($this->getLastName()) $tr[] = $s->getLastName() ;
        if ($this->getBirthDate()) $tr[] = $s->getDateOfBirthAsDate() ;
        if ($this->getAge())
            $tr[] = $s->getAge() . ' (' .  $s->getAgeGroupAge() . ')' ;
        if ($this->getAgeGroup()) $tr[] = $s->getAgeGroupText() ;
        if ($this->getGender()) $tr[] = ucfirst($s->getGender()) ;
        if ($this->getStatus()) $tr[] = ucfirst($s->getStatus()) ;
        if ($this->getSwimmerLabel()) $tr[] = $label ;
        if ($this->getResults()) $tr[] = ucfirst($s->getResults()) ;
        if ($this->getWebSiteId())
        {
            if ($s->getWPUserId() == WPST_NONE)
            {
                $tr[] = _HTML_SPACE ;
            }
            else
            {
                $u = get_userdata($s->getWPUserId()) ;
                $tr[] = $u->user_login ;
            }
        }

        //  Primary Contact

        if ($this->getPrimaryContact())
        {
            $u = get_userdata($s->getContact1Id()) ;
            $tr[] = ($u !== false) ? $u->first_name . ' ' . $u->last_name : _HTML_SPACE ;
        }

        //  Secondary Contact

        if ($this->getSecondaryContact())
        {
            $u = get_userdata($s->getContact2Id()) ;
            $tr[] = ($u !== false) ? $u->first_name . ' ' . $u->last_name : _HTML_SPACE ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        $om->setSwimmerId($s->getSwimmerId()) ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
    
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $om->loadOptionMetaBySwimmerIdAndKey($s->getSwimmerId(), $oconst) ;
                    $tr[] = $om->getOptionMetaValue() ;
                }
            }
        }

        return $tr ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport()
    {
        $this->generateHTMLReport() ;
    }

    /**
     * Generate the HMTL Report
     *
     */
    function generateHTMLReport($swimmerid = null)
    {
        $this->__reporttable = new SwimTeamInfoTable($this->getReportTitle(), '100%') ;
        $table = &$this->__reporttable ;
        $table->set_alt_color_flag(true) ;

        $season = new SwimTeamSeason() ;

        $swimmer = new SwimTeamSwimmer() ;

        $tr = array() ;

        $tr = $this->getHTMLTableHeader($tr) ;

        //  Generate the column headers
 
        for ($i = 0 ; $i < count($tr) ; $i++)
            $table->set_column_header($i, $tr[$i], null, 'left') ;

        //  Get all the swimmer ids using the appropriate filter

        $swimmer = new SwimTeamSwimmer() ;
        $ometa = new SwimTeamOptionMeta() ;

        $joins = sprintf('LEFT JOIN %s r ON (r.swimmerid=s.id)
            LEFT JOIN %s s2 ON (s2.id=r.seasonid)', WPST_ROSTER_TABLE, WPST_SEASONS_TABLE) ;

        if (is_null($swimmerid))
            $swimmerIds = $swimmer->getAllSwimmerIds($this->getFilter(), 'lastname', $joins) ;
        else
            $swimmerIds = array(array('swimmerid' => $swimmerid)) ;

        //  Loop through the swimmers

        $this->__recordcount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            $this->__recordcount++ ;

            $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
            $label = $this->getCurrentSwimmerLabel($swimmerId['swimmerid']) ;

            $tr = array() ;
            $tr = $this->getHTMLTableRow($swimmer, $ometa, $label, $tr) ;

            //  Can't simply add a row to the table because we
            //  don't know how many cells the table has.  Use this
            //  PHP trick to pass an undetermined number of arguments
            //  to a method.

            call_user_func_array(array($table, 'add_row'), $tr);
        }
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getHTMLReport()
    {
        return $this->__reporttable ;
    }

    /**
     * Get HTML report table
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return $this->getHTMLReport() ;
    }
}

/**
 * Class definition of the CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamSwimmersReportGenerator
 */
class SwimTeamSwimmersReportGeneratorExport extends SwimTeamSwimmersReportGenerator
{
    /**
     * export data
     */
    var $__exportData ;

    /**
     * export File
     */
    var $__exportFile ;

    /**
     * export record count
     */
    var $__exportCount ;

    /**
     * Get Export record count
     *
     * @return int - count of Export records
     */
    function getExportCount()
    {
        return $this->__exportCount ;
    }

    /**
     * Get Export file name
     *
     * @return string - Export file name
     */
    function getExportFile()
    {
        return $this->__exportFile ;
    }

    /**
     * Set Export file name
     *
     * @param string - Export file name
     */
    function setExportFile($f)
    {
        $this->__exportFile = $f ;
    }

    /**
     * Get report
     *
     * @return html_table - report table
     */
    function getReport()
    {
        return parent::getHTMLReport() ;
    }

    /**
     * Write the Export data to a file which can be sent to the browser
     *
     */
    function generateExportFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setExportFile(tempnam(ABSPATH .
            '/' . get_option('upload_path'), 'Export')) ;

        $this->setExportFile(tempnam('', 'Export')) ;

        //  Write the Export data to the file

        $f = fopen($this->getExportFile(), 'w') ;
        fwrite($f, $this->__exportData) ;
        fclose($f) ;
    }
}

/**
 * Class definition of the CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamSwimmersReportGenerator
 */
class SwimTeamSwimmersReportGeneratorCSV extends SwimTeamSwimmersReportGeneratorExport
{
    /**
     * Get Export file name
     *
     * @return string - Export file name
     */
    function getCSVFile()
    {
        return $this->getExportFile() ;
    }

    /**
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getCSVHeader($eol = false)
    {
        $csv = '' ;
        //  Generate the column headers
 
        if ($this->getInternalId())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Internal Id"' ;

        if ($this->getFirstName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"First Name"' ;

        if ($this->getMiddleName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Middle Name"' ;

        if ($this->getNickName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Nick Name"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Last Name"' ;

        if ($this->getBirthDate())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Birth Date"' ;

        if ($this->getAge())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Age"' ;

        if ($this->getAgeGroup())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Age Group"' ;

        if ($this->getGender())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Gender"' ;

        if ($this->getStatus())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Status"' ;

        if ($this->getSwimmerLabel())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Swimmer Label"' ;

        if ($this->getResults())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Results"' ;

        if ($this->getWebSiteId())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Web Site Id"' ;

        //  Primary Contact

        if ($this->getPrimaryContact())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                '"Primary Contact"' ;
        }

        //  Secondary Contact

        if ($this->getSecondaryContact())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                '"Secondary Contact"' ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant("WPST_OPTION_SWIMMER_OPTION" . $oc) ;
            $lconst = constant("WPST_OPTION_SWIMMER_OPTION" . $oc . "_LABEL") ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                        get_option($lconst) . '"' ;
                }
            }
        }

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - swimmer profile record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$s, &$om, $sid, $eol = false)
    {
        $csv = '' ;

        if ($this->getInternalId())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getSwimmerId() . '"' ;

        if ($this->getFirstName())
        {
            if ($this->getNickNameOverride() && $s->getNickName() != '')
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $s->getNickName() . '"' ;
            else
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $s->getFirstName() . '"' ;
        }

        if ($this->getMiddleName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getMiddleName() . '"' ;

        if ($this->getNickName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getNickName() . '"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getLastName() . '"' ;

        if ($this->getBirthDate())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getDateOfBirthAsDate() . '"' ;

        if ($this->getAge())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getAge() . ' (' .
                $s->getAgeGroupAge() . ')' . '"' ;

        if ($this->getAgeGroup())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getAgeGroupText() . '"' ;

        if ($this->getGender())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getGender()) . '"' ;

        if ($this->getStatus())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getStatus()) . '"' ;

        if ($this->getSwimmerLabel())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .  '"' .
            $this->getCurrentSwimmerLabel($sid) . '"' ;

        if ($this->getResults())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getResults()) . '"' ;

        if ($this->getWebSiteId())
        {
            if ($s->getWPUserId() == WPST_NONE)
            {
                 $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '""' ;
            }
            else
            {
                $u = get_userdata($s->getWPUserId()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $u->user_login . '"' ;
            }
        }

        //  Primary Contact

        if ($this->getPrimaryContact())
        {
            $u = get_userdata($s->getContact1Id()) ;
            $name = ($u !== false) ? $u->first_name . ' ' . $u->last_name : '' ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' . $name . '"' ;
        }

        //  Secondary Contact

        if ($this->getSecondaryContact())
        {
            $u = get_userdata($s->getContact2Id()) ;

            $name = ($u !== false) ? $u->first_name . ' ' . $u->last_name : '' ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' . $name . '"' ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $om->loadOptionMetaBySwimmerIdAndKey($s->getSwimmerId(), $oconst) ;
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                        '"' . ucfirst($om->getOptionMetaValue()) . '"' ;
                }
            }
        }
        //  Terminate the string?

        if ($eol) $csv .= $csvRow . "\r\n" ;

        return $csv ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport($swimmerid = null)
    {
        $this->generateCSVReport($swimmerid) ;

        //  Generate the HTML representation too!

        parent::generateHTMLReport($swimmerid) ;
    }

    /**
     * Generate the CSV Report
     *
     */
    function generateCSVReport($swimmerid = null)
    {
        $csv = &$this->__exportData ;

        $csv = '' ;

        $season = new SwimTeamSeason() ;
        $ometa = new SwimTeamOptionMeta() ;
        $swimmer = new SwimTeamSwimmer() ;
        $user = new SwimTeamUsersCSV() ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . $this->getCSVHeader() ;
        //  Show Primary Contact Detail?

        if ($this->getPrimaryContactDetail())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                $user->getCSVHeader('Primary ') ;
        }

        //  Show Secondary Contact Detail?

        if ($this->getSecondaryContactDetail())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                $user->getCSVHeader('Secondary ') ;
        }

        $csv .= "\r\n" ;

        //  Get all the swimmer ids using the appropriate filter

        $joins = sprintf('LEFT JOIN %s r ON (r.swimmerid=s.id)
            LEFT JOIN %s s2 ON (s2.id=r.seasonid)', WPST_ROSTER_TABLE, WPST_SEASONS_TABLE) ;

        if (is_null($swimmerid))
            $swimmerIds = $swimmer->getAllSwimmerIds($this->getFilter(), 'lastname', $joins) ;
        else
            $swimmerIds = array(array('swimmerid' => $swimmerid)) ;

        //  Loop through the swimmers

        $this->__exportCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            $this->__exportCount++ ;

            $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;

            $csv .= $this->getCSVRecord($swimmer, $ometa, $swimmerId['swimmerid']) ;
            //  Show Primary Contact Detail?

            if ($this->getPrimaryContactDetail())
            {
                $user->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                    $user->getCSVRecord($user, $ometa) ;
            }

            //  Show Secondary Contact Detail?

            if ($this->getSecondaryContactDetail())
            {
                $user->loadUserProfileByUserId($swimmer->getContact2Id()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                    $user->getCSVRecord($user, $ometa) ;
            }

            $csv .= "\r\n" ;
        }
    }

    /**
     * Write the CSV data to a file which can be sent to the browser
     *
     */
    function generateCSVFile()
    {
        $this->generateExportFile() ;
    }
}

/**
 * Class definition of the Meet Manager RE1 report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamSwimmersReportGenerator
 */
class SwimTeamSwimmersReportGeneratorRE1 extends SwimTeamSwimmersReportGeneratorExport
{
    /**
     * club name
     */
    var $__clubname ;

    /**
     * club abbreviation
     */
    var $__clubabbrevation ;

    /**
     * set club name
     *
     * @param - string - string for club name
     */
    function setClubName($name)
    {
        $this->__clubname = $name ;
    }

    /**
     * get club name
     *
     * @return - string - club name
     */
    function getClubName()
    {
        return $this->__clubname ;
    }

    /**
     * set club abbreviation
     *
     * @param - string - string for club abbreviation
     */
    function setClubAbbreviation($name)
    {
        $this->__clubabbreviation = $name ;
    }

    /**
     * get club abbreviation
     *
     * @return - string - club abbreviation
     */
    function getClubAbbreviation()
    {
        return $this->__clubabbreviation ;
    }

    /**
     * Get Export file name
     *
     * @return string - Export file name
     */
    function getRE1File()
    {
        return $this->getExportFile() ;
    }

    /**
     * Get the RE1 Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getRE1Header($eol = false)
    {
        $re1 = '' ;
        $this->setClubName(get_option(WPST_OPTION_TEAM_NAME)) ;
        $this->setClubAbbreviation(get_option(WPST_OPTION_SDIF_TEAM_CODE)) ;

        //  Generate the file header
 
        $re1 .= sprintf('"%s Registration"', $this->getClubAbbreviation()) ;
        $re1 .= sprintf(';"%s"', date('m/d/y')) ;
        $re1 .= ';"http://www.wp-SwimTeam.org"' ;
        $re1 .= sprintf(';"%s"', $this->getClubAbbreviation()) ;

        if ($eol) $re1 .= "\r\n" ;

        return $re1 ;
    }

    /**
     * Get the RE1 Record
     *
     * @param mixed - swimmer profile record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - RE1 record with optional EOL
     */
    function getRE1Record(&$s, $sid, $eol = false)
    {
        $re1 = '' ;

        //  Use the swimmer label if it is defined.
        //  If it isn't, use the USS Number.
        $regnum = $this->getCurrentSwimmerLabel($sid) ;

        if (empty($regnum) || ($regnum == strtoupper(WPST_NA)))
            $regnum = $s->getUSSNumber() ;

        //  Use the nickname as the preferred name if it exists.

        $nickname = $s->getNickName() ;

        if (empty($nickname))
            $nickname = $s->getFirstName() ;

        //  Construct the record.  The Meet Manager Registration Entry
        //  format is an ASCII file with quoted fields delimited with the
        //  semicolon character.
        //
        //  --Registration number
        //  --Last name
        //  --First name
        //  --Middle initial
        //  --Sex (M or F)
        //  --Birthdate (MM/DD/YY)
        //  --Club abbreviation
        //  --Club name
        //  --Preferred first name
        //  --?? (Always 'N' in all provided examples)
        //  

        $re1 .= sprintf('"%s";"%s";"%s";"%s";"%s";"%s";"%s";"%s";"%s";"N"',
            $regnum,
            $s->getLastName(),
            $s->getFirstName(),
            $s->getMiddleInitial(),
            strtoupper(substr($s->getGender(), 0, 1)),
            $s->getDateOfBirthAsMMDDYY("/"),
            $this->getClubAbbreviation(),
            $this->getClubName(),
            $nickname) ;


        //  Terminate the string?

        if ($eol) $re1 .= $re1Row . "\r\n" ;

        return $re1 ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport($swimmerid = null)
    {
        $this->generateRE1Report($swimmerid) ;

        //  Generate the HTML representation too!

        parent::generateHTMLReport($swimmerid) ;
    }

    /**
     * Generate the RE1 Report
     *
     */
    function generateRE1Report($swimmerid = null)
    {
        $this->__exportData = '' ;

        $re1 = &$this->__exportData ;

        $season = new SwimTeamSeason() ;
        $swimmer = new SwimTeamSwimmer() ;

        $re1 .= $this->getRE1Header(true) ;

        //  Get all the swimmer ids using the appropriate filter

        $joins = sprintf('LEFT JOIN %s r ON (r.swimmerid=s.id)
            LEFT JOIN %s s2 ON (s2.id=r.seasonid)', WPST_ROSTER_TABLE, WPST_SEASONS_TABLE) ;

        //  Get all the swimmer ids using the appropriate filter

        if (is_null($swimmerid))
            $swimmerIds = $swimmer->getAllSwimmerIds($this->getFilter(), 'lastname', $joins) ;
        else
            $swimmerIds = array(array('swimmerid' => $swimmerid)) ;

        //  Loop through the swimmers

        $this->__exportCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            $this->__exportCount++ ;

            $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;

            $re1 .= $this->getRE1Record($swimmer, $swimmerId['swimmerid']) ;
            $re1 .= "\r\n" ;
        }
    }

    /**
     * Write the RE1 data to a file which can be sent to the browser
     *
     */
    function generateRE1File()
    {
        $this->generateExportFile() ;
    }
}

/**
 * Class definition of the CSV report generator
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamSwimmersReportGenerator
 */
class SwimTeamSwimmersReportGeneratorCSV2 extends SwimTeamSwimmersReportGenerator
{
    /**
     * csv data
     */
    var $__csvData ;

    /**
     * csv File
     */
    var $__csvFile ;

    /**
     * csv record count
     */
    var $__csvCount ;

    /**
     * Get CSV record count
     *
     * @return int - count of CSV records
     */
    function getCSVCount()
    {
        return $this->__csvCount ;
    }

    /**
     * Get CSV file name
     *
     * @return string - CSV file name
     */
    function getCSVFile()
    {
        return $this->__csvFile ;
    }

    /**
     * Set CSV file name
     *
     * @param string - CSV file name
     */
    function setCSVFile($f)
    {
        $this->__csvFile = $f ;
    }

    /**
     * Get report
     *
     * @return html_table - report table
     */
    function getReport($html = false)
    {
        if ($html)
            return parent::getReport() ;
        else
            return new Container(html_pre($this->__csvData)) ;
    }

    /**
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to false
     * @return string
     */
    function getCSVHeader($eol = false)
    {
        $csv = '' ;
        //  Generate the column headers
 
        if ($this->getFirstName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"First Name"' ;

        if ($this->getMiddleName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Middle Name"' ;

        if ($this->getNickName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : ''). '"Nick Name"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Last Name"' ;

        if ($this->getBirthDate())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Birth Date"' ;

        if ($this->getAge())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Age"' ;

        if ($this->getAgeGroup())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Age Group"' ;

        if ($this->getGender())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Gender"' ;

        if ($this->getStatus())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Status"' ;

        if ($this->getSwimmerLabel())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Swimmer Label"' ;

        if ($this->getResults())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Results"' ;

        if ($this->getWebSiteId())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"Web Site Id"' ;
        //  Primary Contact

        if ($this->getPrimaryContact())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                '"Primary Contact"' ;
        }

        //  Secondary Contact

        if ($this->getSecondaryContact())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                '"Secondary Contact"' ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                        get_option($lconst) . '"' ;
                }
            }
        }

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - swimmer profile record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$s, &$om, $sid, $eol = false)
    {
        $csv = '' ;

        if ($this->getFirstName())
        {
            if ($this->getNickNameOverride() && $s->getNickName() != '')
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $s->getNickName() . '"' ;
            else
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $s->getFirstName() . '"' ;
        }

        if ($this->getMiddleName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getMiddleName() . '"' ;

        if ($this->getNickName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getNickName() . '"' ;

        if ($this->getLastName())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getLastName() . '"' ;

        if ($this->getBirthDate())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getDateOfBirthAsDate() . '"' ;

        if ($this->getAge())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getAge() . ' (' .
                $s->getAgeGroupAge() . ')' . '"' ;

        if ($this->getAgeGroup())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . $s->getAgeGroupText() . '"' ;

        if ($this->getGender())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getGender()) . '"' ;

        if ($this->getStatus())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getStatus()) . '"' ;

        if ($this->getSwimmerLabel())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .  '"' .
            $this->getCurrentSwimmerLabel($sid) . '"' ;

        if ($this->getResults())
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                '"' . ucfirst($s->getResults()) . '"' ;

        if ($this->getWebSiteId())
        {
            if ($s->getWPUserId() == WPST_NONE)
            {
                 $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '""' ;
            }
            else
            {
                $u = get_userdata($s->getWPUserId()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . 
                    '"' . $u->user_login . '"' ;
            }
        }

        //  Primary Contact

        if ($this->getPrimaryContact())
        {
            $u = get_userdata($s->getContact1Id()) ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                $u->first_name . ' ' . $u->last_name .  '"' ;
        }

        //  Secondary Contact

        if ($this->getSecondaryContact())
        {
            $u = get_userdata($s->getContact2Id()) ;
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . '"' .
                $u->first_name . ' ' . $u->last_name .  '"' ;
        }

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if ($this->getOptionalField($oconst))
                {
                    $om->loadOptionMetaBySwimmerIdAndKey($s->getSwimmerId(), $oconst) ;
                    $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                        '"' . ucfirst($om->getOptionMetaValue()) . '"' ;
                }
            }
        }
        //  Terminate the string?

        if ($eol) $csv .= $csvRow . "\r\n" ;

        return $csv ;
    }

    /**
     * Generate the Report
     *
     */
    function generateReport($html = false)
    {
        $this->__csvData = '' ;

        $csv = &$this->__csvData ;

        $season = new SwimTeamSeason() ;
        $ometa = new SwimTeamOptionMeta() ;
        $swimmer = new SwimTeamSwimmer() ;
        $user = new SwimTeamUsersCSV() ;

        $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') . $this->getCSVHeader() ;
        //  Show Primary Contact Detail?

        if ($this->getPrimaryContactDetail())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                $user->getCSVHeader('Primary ') ;
        }

        //  Show Secondary Contact Detail?

        if ($this->getSecondaryContactDetail())
        {
            $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                $user->getCSVHeader('Secondary ') ;
        }

        $csv .= "\r\n" ;

        //  Get all the swimmer ids using the appropriate filter

        $swimmerIds = $swimmer->getAllSwimmerIds($this->getFilter()) ;

        //  Loop through the swimmers

        $this->__recordcount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            $this->__recordcount++ ;

            $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;

            $csv .= $this->getCSVRecord($swimmer, $ometa, $swimmerId['swimmerid']) ;
            //  Show Primary Contact Detail?

            if ($this->getPrimaryContactDetail())
            {
                $user->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                    $user->getCSVRecord($user, $ometa) ;
            }

            //  Show Secondary Contact Detail?

            if ($this->getSecondaryContactDetail())
            {
                $user->loadUserProfileByUserId($swimmer->getContact2Id()) ;
                $csv .= (($csv != WPST_NULL_STRING) ? ',' : '') .
                    $user->getCSVRecord($user, $ometa) ;
            }

            $csv .= "\r\n" ;
        }

        //  Generate the HTML representation too?

        if ($html) parent::generateReport($html) ;
    }

    /**
     * Write the CSV data to a file which can be sent to the browser
     *
     */
    function generateCSVFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setCSVFile(tempnam(ABSPATH .
            '/' . get_option('upload_path'), 'CSV')) ;

        $this->setCSVFile(tempnam('', 'CSV')) ;

        //  Write the CSV data to the file

        $f = fopen($this->getCSVFile(), 'w') ;
        fwrite($f, $this->__csvData) ;
        fclose($f) ;
    }
}
?>
