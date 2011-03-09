<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TeamProfile classes.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage TeamProfile
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("db.class.php") ;
require_once("swimteam.include.php") ;
require_once("sdif.include.php") ;
require_once("users.class.php") ;
require_once("team.class.php") ;
require_once("seasons.class.php") ;
require_once("roster.class.php") ;

/**
 * Class definition of the SDIF team profile
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SDIFProfile
{
    /**
     * org code property - the code the org is known by
     */
    var $__orgcode ;

    /**
     * team code property - the code the team is known by
     */
    var $__teamcode ;

    /**
     * lsc code property - the lsc code of the team's club or pool
     */
    var $__lsccode ;

    /**
     * country code property - country code
     */
    var $__countrycode ;

    /**
     * region code property - region code
     */
    var $__regioncode ;

    /**
     * swimmer id format property - swimmer id format code
     */
    var $__swimmeridformat = WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING ;

    /**
     * use nickname property - override first name with nickname?
     */
    var $__usenickname = WPST_NO ;

    /**
     * use age group age property - override age with age based on age cutoff
     */
    var $__useagegroupage = WPST_NO ;

    /**
     * Set the org code
     *
     * @param - string - org code
     */
    function setOrgCode($code)
    {
        $this->__orgcode = $code ;
    }

    /**
     * Get the org code
     *
     * @return - string - org code
     */
    function getOrgCode()
    {
        return ($this->__orgcode) ;
    }

    /**
     * Set the team code
     *
     * @param - string - team code
     */
    function setTeamCode($code)
    {
        $this->__teamcode = $code ;
    }

    /**
     * Get the team code
     *
     * @return - string - team code
     */
    function getTeamCode()
    {
        return ($this->__teamcode) ;
    }

    /**
     * Set the LSC code property
     *
     * @param - string - lsc code
     */
    function setLSCCode($code)
    {
        $this->__lsccode = $code ;
    }

    /**
     * Get the LSC code property
     *
     * @return - string - team club or pool name
     */
    function getLSCCode()
    {
        return ($this->__lsccode) ;
    }

    /**
     * Set the country code property
     *
     * @param - string - country code property
     */
    function setCountryCode($code)
    {
        $this->__countrycode = $code ;
    }

    /**
     * Get the country code property
     *
     * @return - string - country code property
     */
    function getCountryCode()
    {
        return ($this->__countrycode) ;
    }

    /**
     * Set the region code property
     *
     * @param - string - region code
     */
    function setRegionCode($code)
    {
        $this->__regioncode = $code ;
    }

    /**
     * Get the region code property
     *
     * @return - string - region code
     */
    function getRegionCode()
    {
        return ($this->__regioncode) ;
    }

    /**
     * Set the swimmer id format property
     *
     * @param - string - swimmer id format
     */
    function setSwimmerIdFormat($format)
    {
        $this->__swimmeridformat = $format ;
    }

    /**
     * Get the swimmer id format property
     *
     * @return - string - swimmer id format
     */
    function getSwimmerIdFormat()
    {
        return ($this->__swimmeridformat) ;
    }

    /**
     * Set the use nick name property
     *
     * @param - boolean - use nickname property
     */
    function setUseNickName($usenickname = WPST_YES)
    {
        $this->__usenickname = $usenickname ;
    }

    /**
     * Get the use nick name property
     *
     * @return - boolean - use nickname property
     */
    function getUseNickName()
    {
        return ($this->__usenickname) ;
    }

    /**
     * Set the use age group age property
     *
     * @param - boolean - use age group age property
     */
    function setUseAgeGroupAge($useagegroupage = WPST_YES)
    {
        $this->__useagegroupage = $useagegroupage ;
    }

    /**
     * Get the use age group age property
     *
     * @return - boolean - use age group age property
     */
    function getUseAgeGroupAge()
    {
        return ($this->__useagegroupage) ;
    }

    /**
     * load SDIF Profile
     *
     * Load the option values from the Wordpress database.
     * If for some reason, the SDIF profile doesn't exist,
     * use the default values where every possible.
     *
     */
    function loadSDIFProfile()
    {
        //  org code
        $option = get_option(WPST_OPTION_SDIF_ORG_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setOrgCode($option) ;
        }
        else
        {
            $this->setOrgCode(WPST_OPTION_SDIF_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_SDIF_ORG_CODE, WPST_OPTION_SDIF_DEFAULT_VALUE) ;
        }

        //  team code
        $option = get_option(WPST_OPTION_SDIF_TEAM_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setTeamCode($option) ;
        }
        else
        {
            $this->setTeamCode(WPST_OPTION_SDIF_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_SDIF_TEAM_CODE, WPST_OPTION_SDIF_DEFAULT_VALUE) ;
        }

        //  LSC code
        $option = get_option(WPST_OPTION_SDIF_LSC_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setLSCCode($option) ;
        }
        else
        {
            $this->setLSCCode(WPST_OPTION_SDIF_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_SDIF_LSC_CODE, WPST_OPTION_SDIF_DEFAULT_VALUE) ;
        }

        //  Country code
        $option = get_option(WPST_OPTION_SDIF_COUNTRY_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setCountryCode($option) ;
        }
        else
        {
            $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

            if ($geography == WPST_US_ONLY)
            {
                $this->setCountryCode(WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE) ;
                update_option(WPST_OPTION_SDIF_COUNTRY_CODE, WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE) ;
            }
            else
            {
                $this->setCountryCode(WPST_OPTION_SDIF_DEFAULT_VALUE) ;
                update_option(WPST_OPTION_SDIF_COUNTRY_CODE, WPST_OPTION_SDIF_DEFAULT_VALUE) ;
            }
        }

        //  Region code
        $option = get_option(WPST_OPTION_SDIF_REGION_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setRegionCode($option) ;
        }
        else
        {
            $this->setRegionCode(WPST_OPTION_SDIF_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_SDIF_REGION_CODE, WPST_OPTION_SDIF_DEFAULT_VALUE) ;
        }

        //  swimmer id format
        $option = get_option(WPST_OPTION_SDIF_SWIMMER_ID_FORMAT) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setSwimmerIdFormat($option) ;
        }
        else
        {
            $this->setSwimmerIdFormat(WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING) ;
            update_option(WPST_OPTION_SDIF_SWIMMER_ID_FORMAT, WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING) ;
        }
 
        //  use nick name?
        $option = get_option(WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setUseNickName($option) ;
        }
        else
        {
            $this->setUseNickName(WPST_NO) ;
            update_option(WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME, WPST_NO) ;
        }
 
        //  use age group age?
        $option = get_option(WPST_OPTION_SDIF_SWIMMER_USE_AGE_GROUP_AGE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setUseAgeGroupAge($option) ;
        }
        else
        {
            $this->setUseAgeGroupAge(WPST_NO) ;
            update_option(WPST_OPTION_SDIF_SWIMMER_USE_AGE_GROUP_AGE, WPST_NO) ;
        }
    }

    /**
     * update (save) SDIF Profile
     *
     * Write the options to the Worpress database
     */
    function updateSDIFProfile()
    {
        update_option(WPST_OPTION_SDIF_ORG_CODE, $this->getOrgCode()) ;
        update_option(WPST_OPTION_SDIF_TEAM_CODE, $this->getTeamCode()) ;
        update_option(WPST_OPTION_SDIF_LSC_CODE, $this->getLSCCode()) ;
        update_option(WPST_OPTION_SDIF_COUNTRY_CODE, $this->getCountryCode()) ;
        update_option(WPST_OPTION_SDIF_REGION_CODE, $this->getRegionCode()) ;
        update_option(WPST_OPTION_SDIF_SWIMMER_ID_FORMAT, $this->getSwimmerIdFormat()) ;
        update_option(WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME, $this->getUseNickName()) ;
        update_option(WPST_OPTION_SDIF_SWIMMER_USE_AGE_GROUP_AGE, $this->getUseAgeGroupAge()) ;
    }
}

/**
 * Class definition of the SDIF LSC Registration export
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SDIFProfile
 */
class SDIFLSCRegistration extends SDIFProfile
{
    /**
     * sdif data
     */
    var $__sdifData ;

    /**
     * sdif File
     */
    var $__sdifFile ;

    /**
     * sdif record count
     */
    var $__sdifCount ;

    /**
     * sdif debug flag
     */
    var $__sdifDebugFlag = false ;

    /**
     * Get SDIF Debug Flag
     *
     * @return boolean - state of SDIF debug flag
     */
    function getSDIFDebugFlag()
    {
        return $this->__sdifDebugFlag ;
    }

    /**
     * Set SDIF Debug Flag
     *
     * @return boolean - state of SDIF debug flag
     */
    function setSDIFDebugFlag($flag = true)
    {
        $this->__sdifDebugFlag = $flag ;
    }

    /**
     * Get SDIF record count
     *
     * @return int - count of SDIF records
     */
    function getSDIFCount()
    {
        return $this->__sdifCount ;
    }

    /**
     * Get SDIF file name
     *
     * @return string - SDIF file name
     */
    function getSDIFFile()
    {
        return $this->__sdifFile ;
    }

    /**
     * Set SDIF file name
     *
     * @param string - SDIF file name
     */
    function setSDIFFile($f)
    {
        $this->__sdifFile = $f ;
    }

    /**
     * Consturctor
     *
     */
    function SDIFLSCRegistration()
    {
        parent::loadSDIFProfile() ;
    }

    /**
     * GenerateSDIF - generate the SDIF content for the team roster.
     *
     * @return string - SDIF content
     */
    function generateSDIF($swimmerid = null)
    {
        $sdif = &$this->__sdifData ;

        $sdif = "" ;

        //  Add debug stuff?  The debug stuff invalidates the SDIF
        //  but is useful for ensuring all of the information is in
        //  the proper column.
 
        if ($this->getSDIFDebugFlag())
        {
            $sdif .= WPST_SDIF_COLUMN_DEBUG1 ;
            $sdif .= "\r\n";

            $sdif .= WPST_SDIF_COLUMN_DEBUG2 ;
            $sdif .= "\r\n";
        }

        $sdif .= $this->constructA0Record() ;
        $sdif .= "\r\n";

        $sdif .= $this->constructC1Record() ;
        $sdif .= "\r\n";

        //  Need to get the active roster
 
        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;
        $roster->setSeasonId($season->getActiveSeasonId()) ;

        if (is_null($swimmerid))
            $swimmerIds = $roster->getSwimmerIds() ;
        else
            $swimmerIds = array(array("swimmerid" => $swimmerid)) ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        $this->__sdifCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            if ($swimmer->loadSwimmerById($swimmerId["swimmerid"]))
            {
                $this->__sdifCount++ ;

                $roster->setSwimmerId($swimmerId["swimmerid"]) ;
                $roster->loadRosterBySeasonIdAndSwimmerId() ;

                $label = $roster->getSwimmerLabel() ;

                //  Phone number fields are provided by the contact

                //  If for some reason the swimmer doesn't have a
                //  parent/guardian contact record, use the Admin's.

                if ($contact->userProfileExistsByUserId($swimmer->getContact1Id()))
                {
                    //printf("Contact 1:  %s %s %s %s<br>", $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                }
                else if ($contact->userProfileExistsByUserId($swimmer->getContact2Id()))
                {
                    //printf("Contact 2:  %s %s %s %s<br>", $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact2Id()) ;
                }
                else
                {
                    //printf("Admin Contact:  %s %s %s %s<br>", $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId(1) ;
                }

                $sdif .= $this->constructD1Record($swimmer, $contact, $label) ;
                $sdif .= "\r\n" ;

                $sdif .= $this->constructD2Record($swimmer, $contact) ;
                $sdif .= "\r\n" ;
            }
            else  //  Should never get here, if we do, something is wrong ...
            {
                $d1 = sprintf("%2s", WPST_SDIF_RECORD_TERMINATOR) ;
            }
        }

        $sdif .= $this->constructZ0Record(count($swimmerIds)) ;
    }

    /**
     * Write the SDIF data to a file which can be sent to the browser
     *
     */
    function generateSDIFFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setSDIFFile(tempnam('', "SD3")) ;

        //  Write the SDIF data to the file

        $f = fopen($this->getSDIFFile(), "w") ;
        fwrite($f, $this->__sdifData) ;
        fclose($f) ;
    }

    /**
     * constructA0Record() - construct the A0 record.
     *
     * @return string - A0 record
     */
    function constructA0Record()
    {
        global $current_user ;

        //  Need some information from the current user

        $user = new SwimTeamUserProfile() ;
        $user->loadUserProfileByUserId($current_user->ID) ;
        $user_info = get_userdata($current_user->ID) ;

        //  Construct the A0 record
 
        $a0 = sprintf(WPST_SDIF_A0_RECORD, $this->getOrgCode(),
            WPST_SDIF_VERSION, WPST_SDIF_FTT_CODE_USS_REGISTRATION_VALUE,
            WPST_SDIF_FUTURE_USE, WPST_SDIF_SOFTWARE_NAME,
            WPST_SDIF_SOFTWARE_VERSION, $user_info->first_name . " " .
            $user_info->last_name, $user->getPrimaryPhone(),
            date("mdY"), WPST_SDIF_FUTURE_USE, $this->getLSCCOde(),
            WPST_SDIF_FUTURE_USE, WPST_SDIF_RECORD_TERMINATOR) ;

        return $a0 ;
    }

    /**
     * constructC1Record() - construct the C1 record.
     *
     * @return string - C1 record
     */
    function constructC1Record()
    {
        //  Need Team Profile information from database
 
        $team = new SwimTeamProfile() ;
        $team->loadTeamProfile() ;

        $c1 = sprintf(WPST_SDIF_C1_RECORD, $this->getOrgCode(),
            WPST_SDIF_FUTURE_USE, $this->getTeamCode(),
            $team->getClubOrPoolName(), $team->getTeamName(),
            $team->getStreet1(), $team->getStreet2(), $team->getCity(),
            $team->getStateOrProvince(), $team->getPostalCode(),
            WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE,
            $this->getRegionCode(), WPST_SDIF_FUTURE_USE,
            WPST_SDIF_FUTURE_USE, WPST_SDIF_FUTURE_USE,
            WPST_SDIF_RECORD_TERMINATOR) ;

        return $c1 ;
    }

    /**
     * constructD1Record() - construct the D1 record.
     *
     * @return string - D1 record
     * @todo - fix Hard Coded value for United States
     */
    function constructD1Record($swimmer, $contact, $label)
    {
        //  Assumptions:
        //
        //  Phone number fields are provided by the primary contact.
        //
        //  Some of the data is unknown or can't be determined so
        //  we'll make our best guess in order to have legal SDIF.
        //
        //  Use today date('mdY') for USS registration date
        //  Use 'C' (change) for the Member Code
        //
        //  The specification only allows 12 characters for the USS#
        //  however the USS# should be 14 characters.  The last two
        //  characters will be trimmed - I suspect this field could
        //  be left blank.

        //  Use computed age or real age?

        if (get_option(WPST_OPTION_SDIF_SWIMMER_USE_AGE_GROUP_AGE) == WPST_YES)
            $age = $swimmer->getAgeGroupAge() ;
        else
            $age = $swimmer->getAge();

        //  Build the D1 record

        $d1 = sprintf(WPST_SDIF_D1_RECORD, $this->getOrgCode(),
            WPST_SDIF_FUTURE_USE, $this->getTeamCode(),
            WPST_SDIF_FUTURE_USE, $swimmer->getLastCommaFirstNames(get_option(WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME)),
            WPST_SDIF_FUTURE_USE, /*$swimmer->getUSSNumber(),*/ $label,
            WPST_SDIF_ATTACHED_CODE_ATTACHED_VALUE,
            WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE,
            $swimmer->getDateOfBirthAsMMDDYYYY(), $age,
            strtoupper(substr($swimmer->getGender(), 0, 1)),
            WPST_SDIF_NO_VALUE,
            WPST_SDIF_NO_VALUE, $contact->getPrimaryPhone(),
            $contact->getSecondaryPhone(), date('mdY'),
            WPST_SDIF_MEMBERSHIP_CODE_NEW_VALUE, WPST_SDIF_FUTURE_USE,
            WPST_SDIF_RECORD_TERMINATOR) ;

        return $d1 ;
    }

    /**
     * constructD2Record() - construct the D2 record.
     *
     * @return string - D2 record
     */
    function constructD2Record($swimmer, $contact)
    {
        //  Assumptions:
        //
        //  Phone number fields are provided by the primary contact.
        //
        //  Some of the data is unknown or can't be determined so
        //  we'll make our best guess in order to have legal SDIF.
        //
        //  Use today date('mdY') for USS registration date
        //  Use 'C' (change) for the Member Code
        //
        //  The specification only allows 12 characters for the USS#
        //  however the USS# should be 14 characters.  The last two
        //  characters will be trimmed - I suspect this field could
        //  be left blank.

        $d2 = sprintf(WPST_SDIF_D2_RECORD, $this->getOrgCode(),
            WPST_SDIF_FUTURE_USE, $this->getTeamCode(),
            WPST_SDIF_FUTURE_USE, $swimmer->getLastCommaFirstNames(get_option(WPST_OPTION_SDIF_SWIMMER_USE_NICKNAME)),
            $contact->getFullName(), $contact->getFullStreetAddress(30),
            $contact->getCity(), $contact->getStateOrProvince(),
            $contact->getCountry(), $contact->getPostalCode(),
            WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE,
            $this->getRegionCode(), WPST_SDIF_ANSWER_CODE_NO_VALUE,
            WPST_SDIF_SEASON_CODE_SEASON_1_VALUE, WPST_SDIF_FUTURE_USE,
            WPST_SDIF_RECORD_TERMINATOR) ;

        return $d2 ;
    }

    /**
     * constructZ0Record() - construct the Z0 record.
     *
     * @return string - Z0 record
     */
    function constructZ0Record($swimmerCount)
    {
        //  Construct the Z0 record
 
        $z0 = sprintf(WPST_SDIF_Z0_RECORD, $this->getOrgCode(),
            WPST_SDIF_FUTURE_USE, WPST_SDIF_FTT_CODE_USS_REGISTRATION_VALUE,
            WPST_SDIF_NO_VALUE, 0, 0, 0, 1, $swimmerCount * 2,
            $swimmerCount, 0, 0, 0, 1, $swimmerCount, 0, 0, 0,
            WPST_SDIF_FUTURE_USE, WPST_SDIF_RECORD_TERMINATOR) ;

        return $z0 ;
    }
}
?>
