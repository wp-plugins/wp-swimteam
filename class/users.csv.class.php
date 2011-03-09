<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * CSV classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Users
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("users.class.php") ;

/**
 * Class definition of the seasons
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamUserProfile
 */
class SwimTeamUsersCSV extends SwimTeamUserProfile
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
     * Get the CSV Header
     *
     * @param optional boolean - add the line ending, defaults to true
     * @return string
     */
    function getCSVHeader($prefix = "", $eol = false)
    {
        $csv = "" ;
 
        $csv .= sprintf("\"%s%s\",", $prefix, "First Name") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "Last Name") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "Username") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "E-mail Address") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "Street Address 1") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "Street Address 2") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "Street Address 3") ;
        $csv .= sprintf("\"%s%s\",", $prefix, "City") ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $csv .= sprintf("\"%s%s\",", $prefix, $label) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $csv .= sprintf("\"%s%s\",", $prefix, $label) ;

        $csv .= sprintf("\"%s%s\",", $prefix, "Country") ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $csv .= sprintf("\"%s%s\",", $prefix, $label) ;

        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $csv .= sprintf("\"%s%s\",", $prefix, $label) ;

        $csv .= sprintf("\"%s%s\",", $prefix, "Contact Information") ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        $ometa = new SwimTeamOptionMeta() ;
        $ometa->setUserId($this->getId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant("WPST_OPTION_USER_OPTION" . $oc) ;
            $lconst = constant("WPST_OPTION_USER_OPTION" . $oc . "_LABEL") ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $label = get_option($lconst) ;
                $csv .= sprintf(",\"%s%s\"", $prefix, $label) ;
            }
        }

        //  Terminate the string?

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
    }

    /**
     * Get the CSV Record
     *
     * @param mixed - user profile record, passed by reference
     * @param optional boolean - add the line ending, defaults to true
     * @return string - CSV record with optional EOL
     */
    function getCSVRecord(&$u, &$om, $eol = false)
    {
        $csv = "" ;

        $csv .= sprintf("\"%s\",", $u->getFirstName()) ;
        $csv .= sprintf("\"%s\",", $u->getLastName()) ;
        $csv .= sprintf("\"%s\",", $u->getUserName()) ;
        $csv .= sprintf("\"%s\",", $u->getEmailAddress()) ;
        $csv .= sprintf("\"%s\",", $u->getStreet1()) ;
        $csv .= sprintf("\"%s\",", $u->getStreet2()) ;
        $csv .= sprintf("\"%s\",", $u->getStreet3()) ;
        $csv .= sprintf("\"%s\",", $u->getCity()) ;
        $csv .= sprintf("\"%s\",", $u->getStateOrProvince()) ;
        $csv .= sprintf("\"%s\",", $u->getPostalCode()) ;
        $csv .= sprintf("\"%s\",", $u->getCountry()) ;
        $csv .= sprintf("\"%s\",", $u->getPrimaryPhone()) ;
        $csv .= sprintf("\"%s\",", $u->getSecondaryPhone()) ;
        $csv .= sprintf("\"%s\",", $u->getContactInfo()) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //$ometa = new SwimTeamOptionMeta() ;
        $om->setUserId($u->getUserId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant("WPST_OPTION_USER_OPTION" . $oc) ;
        
            if (get_option($oconst) != WPST_DISABLED)
            {
                $om->loadOptionMetaByUserIdAndKey($u->getUserId(), $oconst) ;
                $csv .= sprintf(",\"%s\"", $om->getOptionMetaValue()) ;
            }
        }

        //  Terminate the string?

        if ($eol) $csv .= "\r\n" ;

        return $csv ;
}

    /**
     * Build the CSV stream
     *
     * @param - boolean - optional to export query to active swimmers only
     */
    function generateCSV($active_only = true)
    {
        $csv = &$this->__csvData ;

        //  Get the CSV header ...

        $csv = $this->getCSVHeader("", true) ;

        $user = new SwimTeamUserProfile() ;
        $ometa = new SwimTeamOptionMeta() ;
        $userIds = $this->getUserIds(false, true) ;

        //  Loop through the users

        $this->__csvCount = 0 ;

        foreach ($userIds as $userId)
        {
            $this->__csvCount++ ;

            $user->loadUserProfileByUserId($userId["userid"]) ;

            $csv .= $this->getCSVRecord($user, $ometa, true) ;
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
            "/" . get_option('upload_path'), "CSV")) ;

        $this->setCSVFile(tempnam('', "CSV")) ;

        //  Write the CSV data to the file

        $f = fopen($this->getCSVFile(), "w") ;
        fwrite($f, $this->__csvData) ;
        fclose($f) ;
    }
}
?>
