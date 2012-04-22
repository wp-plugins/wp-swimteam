<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TeamProfile classes.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage TeamProfile
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('db.class.php') ;
require_once('swimteam.include.php') ;
require_once('sdif.include.php') ;
require_once('users.class.php') ;
require_once('team.class.php') ;
require_once('seasons.class.php') ;
require_once('roster.class.php') ;

/**
 * Class definition of the SDIF team profile
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
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
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFProfile
 */
class SDIFBasePyramid extends SDIFProfile
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
}

/**
 * Class definition of the SDIF LSC Registration export
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFBasePyramid
 */
class SDIFLSCRegistrationPyramid extends SDIFBasePyramid
{
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

        $sdif = '' ;

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
            $swimmerIds = array(array('swimmerid' => $swimmerid)) ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        $this->__sdifCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            if ($swimmer->loadSwimmerById($swimmerId['swimmerid']))
            {
                $this->__sdifCount++ ;

                $roster->setSwimmerId($swimmerId['swimmerid']) ;
                $roster->loadRosterBySeasonIdAndSwimmerId() ;

                $label = $roster->getSwimmerLabel() ;

                //  Phone number fields are provided by the contact

                //  If for some reason the swimmer doesn't have a
                //  parent/guardian contact record, use the Admin's.

                if ($contact->userProfileExistsByUserId($swimmer->getContact1Id()))
                {
                    //printf('Contact 1:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                }
                else if ($contact->userProfileExistsByUserId($swimmer->getContact2Id()))
                {
                    //printf('Contact 2:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact2Id()) ;
                }
                else
                {
                    //printf('Admin Contact:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId(1) ;
                }

                $sdif .= $this->constructD1Record($swimmer, $contact, $label) ;
                $sdif .= "\r\n" ;

                $sdif .= $this->constructD2Record($swimmer, $contact) ;
                $sdif .= "\r\n" ;
            }
            else  //  Should never get here, if we do, something is wrong ...
            {
                $d1 = sprintf('%2s', WPST_SDIF_RECORD_TERMINATOR) ;
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
 
        $this->setSDIFFile(tempnam('', 'SD3')) ;

        //  Write the SDIF data to the file

        $f = fopen($this->getSDIFFile(), 'w') ;
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
            WPST_SDIF_SOFTWARE_VERSION, $user_info->first_name . ' ' .
            $user_info->last_name, $user->getPrimaryPhone(),
            date('mdY'), WPST_SDIF_FUTURE_USE, $this->getLSCCOde(),
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

/**
 * Class definition of the SDIF Meet Entries Pyramid export
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFBasePyramid
 */
class SDIFMeetEntriesPyramid extends SDIFBasePyramid
{
    /**
     * Consturctor
     *
     */
    function SDIFMeetEntriesPyramid()
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

        $sdif = array() ;

        //  Add debug stuff?  The debug stuff invalidates the SDIF
        //  but is useful for ensuring all of the information is in
        //  the proper column.
 
        if ($this->getSDIFDebugFlag())
        {
            $sdif[] = WPST_SDIF_COLUMN_DEBUG1 ;
            $sdif[] = WPST_SDIF_COLUMN_DEBUG2 ;
        }

        //$a0 = new 
        $sdif[] = $this->constructA0Record() ;

        $sdif[] = $this->constructC1Record() ;

        //  Need to get the active roster
 
        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;
        $roster->setSeasonId($season->getActiveSeasonId()) ;

        if (is_null($swimmerid))
            $swimmerIds = $roster->getSwimmerIds() ;
        else
            $swimmerIds = array(array('swimmerid' => $swimmerid)) ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        $this->__sdifCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            if ($swimmer->loadSwimmerById($swimmerId['swimmerid']))
            {
                $this->__sdifCount++ ;

                $roster->setSwimmerId($swimmerId['swimmerid']) ;
                $roster->loadRosterBySeasonIdAndSwimmerId() ;

                $label = $roster->getSwimmerLabel() ;

                //  Phone number fields are provided by the contact

                //  If for some reason the swimmer doesn't have a
                //  parent/guardian contact record, use the Admin's.

                if ($contact->userProfileExistsByUserId($swimmer->getContact1Id()))
                {
                    //printf('Contact 1:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                }
                else if ($contact->userProfileExistsByUserId($swimmer->getContact2Id()))
                {
                    //printf('Contact 2:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId($swimmer->getContact2Id()) ;
                }
                else
                {
                    //printf('Admin Contact:  %s %s %s %s<br>', $swimmer->getFirstName(), $swimmer->getLastName(), $swimmer->getContact1Id(), $swimmer->getContact2Id()) ;
                    $contact->loadUserProfileByUserId(1) ;
                }

                $sdif[] = $this->constructD1Record($swimmer, $contact, $label) ;

                $sdif[] = $this->constructD2Record($swimmer, $contact) ;
            }
            else  //  Should never get here, if we do, something is wrong ...
            {
                $d1 = sprintf('%2s', WPST_SDIF_RECORD_TERMINATOR) ;
            }
        }

        $sdif[] = $this->constructZ0Record(count($swimmerIds)) ;
    }

    /**
     * Write the SDIF data to a file which can be sent to the browser
     *
     */
    function generateSDIFFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setSDIFFile(tempnam('', 'SD3')) ;

        //  Write the SDIF data to the file

        $f = fopen($this->getSDIFFile(), 'w') ;
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
            WPST_SDIF_SOFTWARE_VERSION, $user_info->first_name . ' ' .
            $user_info->last_name, $user->getPrimaryPhone(),
            date('mdY'), WPST_SDIF_FUTURE_USE, $this->getLSCCOde(),
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

/**
 * SDIF record base class
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SDIFRecord extends SwimTeamDBI
{
    /**
     * SDIF record storage
     */
    var $_sdif_record ;

    /**
     * SDIF record type
     */
    var $_sdif_record_type ;

    /**
     * Org Code
     */
    var $_org_code ;

    /**
     * Course Code
     */
    var $_course_code ;

    /**
     * Team Code
     */
    var $_team_code ;

    /**
     * Team Code 5th Character
     */
    var $_team_code_5 ;

    /**
     * Region Code
     */
    var $_region_code ;

    /**
     * Swimmer Name property
     */
    var $_swimmer_name ;

    /**
     * USS property
     */
    var $_uss ;

    /**
     * USS new property
     */
    var $_uss_new ;

    /**
     * USS old property
     */
    var $_uss_old ;

    /**
     * Attach Code property
     */
    var $_attach_code ;

    /**
     * Citizen Code property
     */
    var $_citizen_code ;

    /**
     * Birth Date property
     */
    var $_birth_date ;

    /**
     * Birth Date Database property
     */
    var $_birth_date_db ;

    /**
     * Age Or Class property
     */
    var $_age_or_class ;

    /**
     * Gender property
     */
    var $_gender ;

    /**
     * Phone Number property
     */
    var $_phone_number ;

    /**
     * Future Use #1
     */
    var $_future_use_1 ;

    /**
     * Future Use #2
     */
    var $_future_use_2 ;

    /**
     * Future Use #3
     */
    var $_future_use_3 ;

    /**
     * Timestamp property
     */
    var $_timestamp ;

    /**
     * Set Org Code
     *
     * @param int org code
     */
    function setOrgCode($code)
    {
        $this->_org_code = $code ;
    }

    /**
     * Get Org Code
     *
     * @return int org code
     */
    function getOrgCode()
    {
        return $this->_org_code ;
    }

    /**
     * Set Team Code
     *
     * @param string team code
     */
    function setTeamCode($txt)
    {
        $this->_team_code = $txt ;
    }

    /**
     * Get Team Code
     *
     * @return string team code
     */
    function getTeamCode()
    {
        return $this->_team_code ;
    }

    /**
     * Set Team Code 5th character
     *
     * @param string team code 5th character
     */
    function setTeamCode5($txt)
    {
        $this->_team_code_5 = $txt ;
    }

    /**
     * Get Team Code 5th character
     *
     * @return string team code 5th character
     */
    function getTeamCode5()
    {
        return $this->_team_code_5 ;
    }

    /**
     * Set Course Code
     *
     * @param string course code
     */
    function setCourseCode($txt)
    {
        $this->_course_code = $txt ;
    }

    /**
     * Get Course Code
     *
     * @return string course code
     */
    function getCourseCode()
    {
        return $this->_course_code ;
    }

    /**
     * Set Region Code
     *
     * @param string region code
     */
    function setRegionCode($txt)
    {
        $this->_region_code = $txt ;
    }

    /**
     * Get Region Code
     *
     * @return string region code
     */
    function getRegionCode()
    {
        return $this->_region_code ;
    }

    /**
     * Set Stroke Code
     *
     * @param string stroke code
     */
    function setStrokeCode($txt)
    {
        $this->_stroke_code = $txt ;
    }

    /**
     * Get Stroke Code
     *
     * @return string stroke code
     */
    function getStrokeCode()
    {
        return $this->_stroke_code ;
    }

    /**
     * Set Age Or Class
     *
     * @param string age or class
     */
    function setAgeOrClass($txt)
    {
        $this->_age_or_class = $txt ;
    }

    /**
     * Get Age Or Class
     *
     * @return string age or class
     */
    function getAgeOrClass()
    {
        return $this->_age_or_class ;
    }

    /**
     * Set Birth Date
     *
     * @param string birth date
     * @param boolean date provided in database format
     */
    function setBirthDate($txt, $db = false)
    {
        if ($db)
        {
            $this->_birth_date_db = $txt ;
 
            //  The birth date date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_birth_date_db ;

            $this->_birth_date = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_birth_date = $txt ;
 
            //  The birth date date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_birth_date ;

            $this->_birth_date_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Birth Date
     *
     * @param boolean date returned in database format
     * @return string birth date
     */
    function getBirthDate($db = true)
    {
        if ($db)
            return $this->_birth_date_db ;
        else
            return $this->_birth_date ;
    }

    /**
     * Set Gender
     *
     * @param string gender
     */
    function setGender($txt)
    {
        $this->_gender = $txt ;
    }

    /**
     * Get Gender
     *
     * @return string gender
     */
    function getGender()
    {
        return $this->_gender ;
    }

    /**
     * Set Swimmer Name
     *
     * @param string swimmer name
     */
    function setSwimmerName($txt)
    {
        $this->_swimmer_name = $txt ;
    }

    /**
     * Get Swimmer Name
     *
     * @return string swimmer name
     */
    function getSwimmerName()
    {
        return $this->_swimmer_name ;
    }

    /**
     * Set USS
     *
     * @param string uss
     */
    function setUSS($txt)
    {
        $this->_uss = $txt ;
    }

    /**
     * Get USS
     *
     * @return string uss
     */
    function getUSS()
    {
        return $this->_uss ;
    }

    /**
     * Set USS New
     *
     * Construct the new format of the USS swimmer number
     * from the name and birth date fields.
     */
    function setUSSNew($uss = null)
    {
        if (is_null($uss))
        {
            $dob = split('-', $this->getBirthDate()) ;
            $name = split(',', $this->getSwimmerName()) ;

            //  Make sure there are 3 elements in the $name array
            for ($i = 0 ; $i <= 2 ; $i++)
                if (!array_key_exists($i, $name)) $name[$i] = '' ;

            $first = strtoupper(trim($name[1])) ;
            $last = strtoupper(trim($name[0])) ;
            $middle = empty($name[2]) ? '*' : $name[2] ;

            $this->_uss_new = sprintf('%02s%02s%02s%3s%1s%4s',
                $dob[1], $dob[2], substr($dob[0], 2, 2), substr($first, 0, 3),
                substr($middle, 0, 1), substr($last, 0, 4)) ;
        }
        else
            $this->_uss_new = $uss ;
    }

    /**
     * Get USS New
     *
     * @return string ft uss new
     */
    function getUSSNew()
    {
        return $this->_uss_new ;
    }

    /**
     * Set USS Old
     *
     */
    function setUSSOld($uss = null)
    {
        if (is_null($uss))
            $this->_uss_old = substr($this->getUSSNew(), 0, 12) ;
        else
            $this->_uss_old = $uss ;
    }

    /**
     * Get USS Old
     *
     * @return string ft uss old
     */
    function getUSSOld()
    {
        return $this->_uss_old ;
    }

    /**
     * Set Attach Code
     *
     * @param string attach code
     */
    function setAttachCode($txt)
    {
        $this->_attach_code = $txt ;
    }

    /**
     * Get Attach Code
     *
     * @return string attach code
     */
    function getAttachCode()
    {
        return $this->_attach_code ;
    }

    /**
     * Set Citizen Code
     *
     * @param string citizen code
     */
    function setCitizenCode($txt)
    {
        $this->_citizen_code = $txt ;
    }

    /**
     * Get Citizen Code
     *
     * @return string citizen code
     */
    function getCitizenCode()
    {
        return $this->_citizen_code ;
    }

    /**
     * Set Phone Number
     *
     * @param string phone number
     */
    function setPhoneNumber($txt)
    {
        $this->_phone_number = $txt ;
    }

    /**
     * Get Phone Number
     *
     * @return string phone number
     */
    function getPhoneNumber()
    {
        return $this->_phone_number ;
    }

    /**
     * Set Future Use 1
     *
     * @param string future use 1
     */
    function setFutureUse1($txt)
    {
        $this->_future_use_1 = $txt ;
    }

    /**
     * Get Future Use 1
     *
     * @return string future use 1
     */
    function getFutureUse1()
    {
        return $this->_future_use_1 ;
    }

    /**
     * Set Future Use 2
     *
     * @param string future use 2
     */
    function setFutureUse2($txt)
    {
        $this->_future_use_2 = $txt ;
    }

    /**
     * Get Future Use 2
     *
     * @return string future use 2
     */
    function getFutureUse2()
    {
        return $this->_future_use_2 ;
    }

    /**
     * Set Future Use 3
     *
     * @param string future use 3
     */
    function setFutureUse3($txt)
    {
        $this->_future_use_3 = $txt ;
    }

    /**
     * Get Future Use 3
     *
     * @return string future use 3
     */
    function getFutureUse3()
    {
        return $this->_future_use_3 ;
    }

    /**
     * Set SDIF record
     *
     * @param string SDIF record
     */
    function setSDIFRecord($rec)
    {
        $this->_sdif_record = $rec ;
    }

    /**
     * Set Timestamp
     *
     * @param string timestamp
     */
    function setTimestamp($txt)
    {
        $this->_timestamp = $txt ;
    }

    /**
     * Get Timestamp
     *
     * @return string timestamp
     */
    function getTimestamp()
    {
        return $this->_timestamp ;
    }
}

/**
 * SDIF A0 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFA0Record extends SDIFRecord
{
    /**
     * SDIF Version Number
     */
    var $_sdif_version_number ;

    /**
     * File Code
     */
    var $_file_code ;

    /**
     * Software Name
     */
    var $_software_name ;

    /**
     * Software Version
     */
    var $_software_version ;

    /**
     * Contact Name
     */
    var $_contact_name ;

    /**
     * Contact Phone
     */
    var $_contact_phone ;

    /**
     * File Creation or Update
     */
    var $_file_creation_or_update ;

    /**
     * Submitted By LSC
     */
    var $_submitted_by_lsc ;

    /**
     * Set Meet Name
     *
     * @param string meet name
     */
    function setMeetName($txt)
    {
        $this->_meet_name = $txt ;
    }

    /**
     * Get Meet Name
     *
     * @return string meet name
     */
    function getMeetName()
    {
        return $this->_meet_name ;
    }

    /**
     * Set Meet Address 1
     *
     * @param string meet address 1
     */
    function setMeetAddress1($txt)
    {
        $this->_meet_address_1 = $txt ;
    }

    /**
     * Get Meet Address 1
     *
     * @return string meet address 1
     */
    function getMeetAddress1()
    {
        return $this->_meet_address_1 ;
    }

    /**
     * Set Meet Address 2
     *
     * @param string meet address 2
     */
    function setMeetAddress2($txt)
    {
        $this->_meet_address_2 = $txt ;
    }

    /**
     * Get Meet Address 2
     *
     * @return string meet address 2
     */
    function getMeetAddress2()
    {
        return $this->_meet_address_2 ;
    }

    /**
     * Set Meet City
     *
     * @param string meet city
     */
    function setMeetCity($txt)
    {
        $this->_meet_city = $txt ;
    }

    /**
     * Get Meet City
     *
     * @return string meet city
     */
    function getMeetCity()
    {
        return $this->_meet_city ;
    }

    /**
     * Set Meet State
     *
     * @param string meet state
     */
    function setMeetState($txt)
    {
        $this->_meet_state = $txt ;
    }

    /**
     * Get Meet State
     *
     * @return string meet state
     */
    function getMeetState()
    {
        return $this->_meet_state ;
    }

    /**
     * Set Meet Postal Code
     *
     * @param string meet postal code
     */
    function setMeetPostalCode($txt)
    {
        $this->_meet_postal_code = $txt ;
    }

    /**
     * Get Meet Postal Code
     *
     * @return string meet postal code
     */
    function getMeetPostalCode()
    {
        return $this->_meet_postal_code ;
    }

    /**
     * Set Meet Country Code
     *
     * @param string meet country code
     */
    function setMeetCountryCode($txt)
    {
        $this->_meet_country_code = $txt ;
    }

    /**
     * Get Meet Country Code
     *
     * @return string meet country code
     */
    function getMeetCountryCode()
    {
        return $this->_meet_country_code ;
    }

    /**
     * Set Meet Code
     *
     * @param string meet code
     */
    function setMeetCode($txt)
    {
        $this->_meet_code = $txt ;
    }

    /**
     * Get Meet Code
     *
     * @return string meet code
     */
    function getMeetCode()
    {
        return $this->_meet_code ;
    }

    /**
     * Set Meet Start
     *
     * @param string meet start
     * @param boolean date provided in database format
     */
    function setMeetStart($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_start_db = $txt ;
 
            //  The meet start date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_start_db ;

            $this->_meet_start = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_start = $txt ;
 
            //  The meet start date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_start ;

            $this->_meet_start_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet Start
     *
     * @param boolean date returned in database format
     * @return string meet start
     */
    function getMeetStart($db = true)
    {
        if ($db)
            return $this->_meet_start_db ;
        else
            return $this->_meet_start ;
    }

    /**
     * Set Meet End
     *
     * @param string meet end
     * @param boolean date provided in database format
     */
    function setMeetEnd($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_end_db = $txt ;
 
            //  The meet end date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_end_db ;

            $this->_meet_end = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_end = $txt ;
 
            //  The meet end date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_end ;

            $this->_meet_end_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet End
     *
     * @param boolean date returned in database format
     * @return string meet end
     */
    function getMeetEnd($db = true)
    {
        if ($db)
            return $this->_meet_end_db ;
        else
            return $this->_meet_end ;
    }

    /**
     * Set Pool Altitude
     *
     * @param string pool altitude
     */
    function setPoolAltitude($txt)
    {
        $this->_pool_altitude = $txt ;
    }

    /**
     * Get Pool Altitude
     *
     * @return string pool altitude
     */
    function getPoolAltitude()
    {
        return $this->_pool_altitude ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        $c = container() ;
        if (WPST_DEBUG)
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
        //print $c->render() ;

        //  This doesn't work right and I am not sure why ...
        //  it ends reading data from the wrong character position.

        //$success = sscanf($this->_sdif_record, WPST_SDIF_B1_RECORD,
        //    $this->_org_code,
        //    $this->_future_use_1,
        //    $this->_meet_name,
        //    $this->_meet_address_1,
        //    $this->_meet_address_2,
        //    $this->_meet_city,
        //    $this->_meet_state,
        //    $this->_meet_postal_code,
        //    $this->_meet_country_code,
        //    $this->_meet_code,
        //    $this->_meet_start,
        //    $this->_meet_end,
        //    $this->_pool_altitude,
        //    $this->_future_use_2,
        //    $this->_course_code,
        //    $this->_future_use_3) ;

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setMeetName(trim(substr($this->_sdif_record, 11, 30))) ;
        $this->setMeetAddress1(trim(substr($this->_sdif_record, 41, 22))) ;
        $this->setMeetAddress2(trim(substr($this->_sdif_record, 63, 22))) ;
        $this->setMeetCity(trim(substr($this->_sdif_record, 85, 20))) ;
        $this->setMeetState(trim(substr($this->_sdif_record, 105, 2))) ;
        $this->setMeetPostalCode(trim(substr($this->_sdif_record, 107, 10))) ;
        $this->setMeetCountryCode(trim(substr($this->_sdif_record, 117, 3))) ;
        $this->setMeetCode(trim(substr($this->_sdif_record, 120, 1))) ;
        $this->setMeetStart(trim(substr($this->_sdif_record, 121, 8))) ;
        $this->setMeetEnd(trim(substr($this->_sdif_record, 129, 8))) ;
        $this->setPoolAltitude(trim(substr($this->_sdif_record, 137, 4))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 141, 8))) ;
        $this->setCourseCode(trim(substr($this->_sdif_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 150, 10))) ;
    }
}

/**
 * SDIF Bx record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFBxRecord extends SDIFRecord
{
    /**
     * Meet Name
     */
    var $_meet_name ;

    /**
     * Meet Address Line 1
     */
    var $_meet_address_1 ;

    /**
     * Meet Address Line 2
     */
    var $_meet_address_2 ;

    /**
     * Meet City
     */
    var $_meet_city ;

    /**
     * Meet State
     */
    var $_meet_state ;

    /**
     * Meet Postal Code
     */
    var $_meet_postal_code ;

    /**
     * Meet Country Code
     */
    var $_meet_country_code ;

    /**
     * Meet Code
     */
    var $_meet_code ;

    /**
     * Meet Start
     */
    var $_meet_start ;

    /**
     * Meet Start for database
     */
    var $_meet_start_db ;

    /**
     * Meet End
     */
    var $_meet_end ;

    /**
     * Meet End for database
     */
    var $_meet_end_db ;

    /**
     * Pool Altitude
     */
    var $_pool_altitude ;

    /**
     * Set Meet Name
     *
     * @param string meet name
     */
    function setMeetName($txt)
    {
        $this->_meet_name = $txt ;
    }

    /**
     * Get Meet Name
     *
     * @return string meet name
     */
    function getMeetName()
    {
        return $this->_meet_name ;
    }

    /**
     * Set Meet Address 1
     *
     * @param string meet address 1
     */
    function setMeetAddress1($txt)
    {
        $this->_meet_address_1 = $txt ;
    }

    /**
     * Get Meet Address 1
     *
     * @return string meet address 1
     */
    function getMeetAddress1()
    {
        return $this->_meet_address_1 ;
    }

    /**
     * Set Meet Address 2
     *
     * @param string meet address 2
     */
    function setMeetAddress2($txt)
    {
        $this->_meet_address_2 = $txt ;
    }

    /**
     * Get Meet Address 2
     *
     * @return string meet address 2
     */
    function getMeetAddress2()
    {
        return $this->_meet_address_2 ;
    }

    /**
     * Set Meet City
     *
     * @param string meet city
     */
    function setMeetCity($txt)
    {
        $this->_meet_city = $txt ;
    }

    /**
     * Get Meet City
     *
     * @return string meet city
     */
    function getMeetCity()
    {
        return $this->_meet_city ;
    }

    /**
     * Set Meet State
     *
     * @param string meet state
     */
    function setMeetState($txt)
    {
        $this->_meet_state = $txt ;
    }

    /**
     * Get Meet State
     *
     * @return string meet state
     */
    function getMeetState()
    {
        return $this->_meet_state ;
    }

    /**
     * Set Meet Postal Code
     *
     * @param string meet postal code
     */
    function setMeetPostalCode($txt)
    {
        $this->_meet_postal_code = $txt ;
    }

    /**
     * Get Meet Postal Code
     *
     * @return string meet postal code
     */
    function getMeetPostalCode()
    {
        return $this->_meet_postal_code ;
    }

    /**
     * Set Meet Country Code
     *
     * @param string meet country code
     */
    function setMeetCountryCode($txt)
    {
        $this->_meet_country_code = $txt ;
    }

    /**
     * Get Meet Country Code
     *
     * @return string meet country code
     */
    function getMeetCountryCode()
    {
        return $this->_meet_country_code ;
    }

    /**
     * Set Meet Code
     *
     * @param string meet code
     */
    function setMeetCode($txt)
    {
        $this->_meet_code = $txt ;
    }

    /**
     * Get Meet Code
     *
     * @return string meet code
     */
    function getMeetCode()
    {
        return $this->_meet_code ;
    }

    /**
     * Set Meet Start
     *
     * @param string meet start
     * @param boolean date provided in database format
     */
    function setMeetStart($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_start_db = $txt ;
 
            //  The meet start date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_start_db ;

            $this->_meet_start = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_start = $txt ;
 
            //  The meet start date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_start ;

            $this->_meet_start_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet Start
     *
     * @param boolean date returned in database format
     * @return string meet start
     */
    function getMeetStart($db = true)
    {
        if ($db)
            return $this->_meet_start_db ;
        else
            return $this->_meet_start ;
    }

    /**
     * Set Meet End
     *
     * @param string meet end
     * @param boolean date provided in database format
     */
    function setMeetEnd($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_end_db = $txt ;
 
            //  The meet end date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_end_db ;

            $this->_meet_end = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_end = $txt ;
 
            //  The meet end date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_end ;

            $this->_meet_end_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet End
     *
     * @param boolean date returned in database format
     * @return string meet end
     */
    function getMeetEnd($db = true)
    {
        if ($db)
            return $this->_meet_end_db ;
        else
            return $this->_meet_end ;
    }

    /**
     * Set Pool Altitude
     *
     * @param string pool altitude
     */
    function setPoolAltitude($txt)
    {
        $this->_pool_altitude = $txt ;
    }

    /**
     * Get Pool Altitude
     *
     * @return string pool altitude
     */
    function getPoolAltitude()
    {
        return $this->_pool_altitude ;
    }
}

/**
 * SDIF B1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFBxRecord
 */
class SDIFB1Record extends SDIFBxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        $c = container() ;
        if (WPST_DEBUG)
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
        //print $c->render() ;

        //  This doesn't work right and I am not sure why ...
        //  it ends reading data from the wrong character position.

        //$success = sscanf($this->_sdif_record, WPST_SDIF_B1_RECORD,
        //    $this->_org_code,
        //    $this->_future_use_1,
        //    $this->_meet_name,
        //    $this->_meet_address_1,
        //    $this->_meet_address_2,
        //    $this->_meet_city,
        //    $this->_meet_state,
        //    $this->_meet_postal_code,
        //    $this->_meet_country_code,
        //    $this->_meet_code,
        //    $this->_meet_start,
        //    $this->_meet_end,
        //    $this->_pool_altitude,
        //    $this->_future_use_2,
        //    $this->_course_code,
        //    $this->_future_use_3) ;

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setMeetName(trim(substr($this->_sdif_record, 11, 30))) ;
        $this->setMeetAddress1(trim(substr($this->_sdif_record, 41, 22))) ;
        $this->setMeetAddress2(trim(substr($this->_sdif_record, 63, 22))) ;
        $this->setMeetCity(trim(substr($this->_sdif_record, 85, 20))) ;
        $this->setMeetState(trim(substr($this->_sdif_record, 105, 2))) ;
        $this->setMeetPostalCode(trim(substr($this->_sdif_record, 107, 10))) ;
        $this->setMeetCountryCode(trim(substr($this->_sdif_record, 117, 3))) ;
        $this->setMeetCode(trim(substr($this->_sdif_record, 120, 1))) ;
        $this->setMeetStart(trim(substr($this->_sdif_record, 121, 8))) ;
        $this->setMeetEnd(trim(substr($this->_sdif_record, 129, 8))) ;
        $this->setPoolAltitude(trim(substr($this->_sdif_record, 137, 4))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 141, 8))) ;
        $this->setCourseCode(trim(substr($this->_sdif_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 150, 10))) ;
    }
}

/**
 * SDIF Cx record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFCxRecord extends SDIFRecord
{
    /**
     * Team Name
     */
    var $_team_name ;

    /**
     * Team Name Abbreviation
     */
    var $_team_name_abrv ;

    /**
     * Team Address Line 1
     */
    var $_team_address_1 ;

    /**
     * Team Address Line 2
     */
    var $_team_address_2 ;

    /**
     * Team City
     */
    var $_team_city ;

    /**
     * Team State
     */
    var $_team_state ;

    /**
     * Team Postal Code
     */
    var $_team_postal_code ;

    /**
     * Team Country Code
     */
    var $_team_country_code ;

    /**
     * Set Team Name
     *
     * @param string team name
     */
    function setTeamName($txt)
    {
        $this->_team_name = $txt ;
    }

    /**
     * Get Team Name
     *
     * @return string team name
     */
    function getTeamName()
    {
        return $this->_team_name ;
    }

    /**
     * Set Team Name Abbreviation
     *
     * @param string team name abreviation
     */
    function setTeamNameAbrv($txt)
    {
        $this->_team_name_abrv = $txt ;
    }

    /**
     * Get Team Name Abreviation
     *
     * @return string team name abreviation
     */
    function getTeamNameAbrv()
    {
        return $this->_team_name_abrv ;
    }

    /**
     * Set Team Address 1
     *
     * @param string team address 1
     */
    function setTeamAddress1($txt)
    {
        $this->_team_address_1 = $txt ;
    }

    /**
     * Get Team Address 1
     *
     * @return string team address 1
     */
    function getTeamAddress1()
    {
        return $this->_team_address_1 ;
    }

    /**
     * Set Team Address 2
     *
     * @param string team address 2
     */
    function setTeamAddress2($txt)
    {
        $this->_team_address_2 = $txt ;
    }

    /**
     * Get Team Address 2
     *
     * @return string team address 2
     */
    function getTeamAddress2()
    {
        return $this->_team_address_2 ;
    }

    /**
     * Set Team City
     *
     * @param string team city
     */
    function setTeamCity($txt)
    {
        $this->_team_city = $txt ;
    }

    /**
     * Get Team City
     *
     * @return string team city
     */
    function getTeamCity()
    {
        return $this->_team_city ;
    }

    /**
     * Set Team State
     *
     * @param string team state
     */
    function setTeamState($txt)
    {
        $this->_team_state = $txt ;
    }

    /**
     * Get Team State
     *
     * @return string team state
     */
    function getTeamState()
    {
        return $this->_team_state ;
    }

    /**
     * Set Team Postal Code
     *
     * @param string team postal code
     */
    function setTeamPostalCode($txt)
    {
        $this->_team_postal_code = $txt ;
    }

    /**
     * Get Team Postal Code
     *
     * @return string team postal code
     */
    function getTeamPostalCode()
    {
        return $this->_team_postal_code ;
    }

    /**
     * Set Team Country Code
     *
     * @param string team country code
     */
    function setTeamCountryCode($txt)
    {
        $this->_team_country_code = $txt ;
    }

    /**
     * Get Team Country Code
     *
     * @return string team country code
     */
    function getTeamCountryCode()
    {
        return $this->_team_country_code ;
    }
}

/**
 * SDIF C1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFC1Record extends SDIFCxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
            //print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 11, 6))) ;
        $this->setTeamName(trim(substr($this->_sdif_record, 17, 30))) ;
        $this->setTeamNameAbrv(trim(substr($this->_sdif_record, 47, 16))) ;
        $this->setTeamAddress1(trim(substr($this->_sdif_record, 63, 22))) ;
        $this->setTeamAddress2(trim(substr($this->_sdif_record, 85, 22))) ;
        $this->setTeamCity(trim(substr($this->_sdif_record, 107, 20))) ;
        $this->setTeamState(trim(substr($this->_sdif_record, 127, 2))) ;
        $this->setTeamPostalCode(trim(substr($this->_sdif_record, 129, 10))) ;
        $this->setTeamCountryCode(trim(substr($this->_sdif_record, 139, 3))) ;
        $this->setRegionCode(trim(substr($this->_sdif_record, 142, 1))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 143, 8))) ;
        $this->setTeamCode5(trim(substr($this->_sdif_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 150, 10))) ;
    }
}

/**
 * SDIF Dx record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFDxRecord extends SDIFRecord
{
    /**
     * Event Gender property
     */
    var $_event_gender ;

    /**
     * Event Distance property
     */
    var $_event_distance ;

    /**
     * Stroke Code property
     */
    var $_stroke_code ;

    /**
     * Event Number property
     */
    var $_event_number ;

    /**
     * Event Age Code property
     */
    var $_event_age_code ;

    /**
     * Swim Date property
     */
    var $_swim_date ;

    /**
     * Swim Date Database property
     */
    var $_swim_date_db ;

    /**
     * Seed Time property
     */
    var $_seed_time ;

    /**
     * Seed Course Code property
     */
    var $_seed_course_code ;

    /**
     * Prelim Time property
     */
    var $_prelim_time ;

    /**
     * Prelim Course Code property
     */
    var $_prelim_course_code ;

    /**
     * Swim Off Time property
     */
    var $_swim_off_time ;

    /**
     * Swim Off Course Code property
     */
    var $_swim_off_course_code ;

    /**
     * Finals Time property
     */
    var $_finals_time ;

    /**
     * Finals Time internal property
     */
    var $_finals_time_ft ;

    /**
     * Finals Course Code property
     */
    var $_finals_course_code ;

    /**
     * Prelim Heat Number property
     */
    var $_prelim_heat_number ;

    /**
     * Prelim Lane Number property
     */
    var $_prelim_lane_number ;

    /**
     * Finals Heat Number property
     */
    var $_finals_heat_number ;

    /**
     * Finals Lane Number property
     */
    var $_finals_lane_number ;

    /**
     * Prelim Place Ranking property
     */
    var $_prelim_place_ranking ;

    /**
     * Finals Place Ranking property
     */
    var $_finals_place_ranking ;

    /**
     * Finals Points property
     */
    var $_finals_points ;

    /**
     * Event Time Class Code property
     */
    var $_event_time_class_code ;

    /**
     * Swimmer Flight Status property
     */
    var $_swimmer_flight_status ;

    /**
     * Set Result Id
     *
     * @param string result id
     */
    function setResultId($txt)
    {
        $this->_resultid = $txt ;
    }

    /**
     * Get Result Id
     *
     * @return string result id
     */
    function getResultId()
    {
        return $this->_resultid ;
    }

    /**
     * Set Swimmer Id
     *
     * @param string swimmer id
     */
    function setSwimmerId($txt)
    {
        $this->_swimmerid = $txt ;
    }

    /**
     * Get Swimmer Id
     *
     * @return string swimmer id
     */
    function getSwimmerId()
    {
        return $this->_swimmerid ;
    }

    /**
     * Set Event Gender
     *
     * @param string event gender
     */
    function setEventGender($txt)
    {
        $this->_event_gender = $txt ;
    }

    /**
     * Get Event Gender
     *
     * @return string event gender
     */
    function getEventGender()
    {
        return $this->_event_gender ;
    }

    /**
     * Set Event Distance
     *
     * @param string event distance
     */
    function setEventDistance($txt)
    {
        $this->_event_distance = $txt ;
    }

    /**
     * Get Event Distance
     *
     * @return string event distance
     */
    function getEventDistance()
    {
        return $this->_event_distance ;
    }

    /**
     * Set Event Number
     *
     * @param string event number
     */
    function setEventNumber($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_event_number = $txt ;
    }

    /**
     * Get Event Number
     *
     * @return string event number
     */
    function getEventNumber()
    {
        return $this->_event_number ;
    }

    /**
     * Set Event Age Code
     *
     * @param string event age code
     */
    function setEventAgeCode($txt)
    {
        $this->_event_age_code = $txt ;
    }

    /**
     * Get Event Age Code
     *
     * @return string event age code
     */
    function getEventAgeCode()
    {
        return $this->_event_age_code ;
    }

    /**
     * Set Swim Date
     *
     * @param string swim date
     * @param boolean date provided in database format
     */
    function setSwimDate($txt, $db = false)
    {
        if ($db)
        {
            $this->_swim_date_db = $txt ;
 
            //  The swim date date is stored in YYYY-MM-DD in the database but
            //  SDIF B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_swim_date_db ;

            $this->_swim_date = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_swim_date = $txt ;
 
            //  The swim date date is stored in MMDDYYYY format in the SDIF B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_swim_date ;

            $this->_swim_date_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Swim Date
     *
     * @param boolean date returned in database format
     * @return string swim date
     */
    function getSwimDate($db = true)
    {
        if ($db)
            return $this->_swim_date_db ;
        else
            return $this->_swim_date ;
    }

    /**
     * Set Seed Time
     *
     * @param string seed time
     */
    function setSeedTime($txt)
    {
        if (empty($txt)) $txt = 0.0 ;

        $this->_seed_time = $txt ;
    }

    /**
     * Get Seed Time
     *
     * @return string seed time
     */
    function getSeedTime()
    {
        return $this->_seed_time ;
    }

    /**
     * Set Seed Course Code
     *
     * @param string seed course code
     */
    function setSeedCourseCode($txt)
    {
        $this->_seed_course_code = $txt ;
    }

    /**
     * Get Seed Course Code
     *
     * @return string seed course code
     */
    function getSeedCourseCode()
    {
        return $this->_seed_course_code ;
    }

    /**
     * Set Prelim Time
     *
     * @param string prelim time
     */
    function setPrelimTime($txt)
    {
        if (empty($txt)) $txt = 0.0 ;

        $this->_prelim_time = $txt ;
    }

    /**
     * Get Prelim Time
     *
     * @return string prelim time
     */
    function getPrelimTime()
    {
        return $this->_prelim_time ;
    }

    /**
     * Set Prelim Course Code
     *
     * @param string prelim course code
     */
    function setPrelimCourseCode($txt)
    {
        $this->_prelim_course_code = $txt ;
    }

    /**
     * Get Prelim Course Code
     *
     * @return string prelim course code
     */
    function getPrelimCourseCode()
    {
        return $this->_prelim_course_code ;
    }

    /**
     * Set Swim Off Time
     *
     * @param string swim off time
     */
    function setSwimOffTime($txt)
    {
        if (empty($txt)) $txt = 0.0 ;

        $this->_swim_off_time = $txt ;
    }

    /**
     * Get Swim Off Time
     *
     * @return string swim off time
     */
    function getSwimOffTime()
    {
        return $this->_swim_off_time ;
    }

    /**
     * Set Swim Off Course Code
     *
     * @param string swim off course code
     */
    function setSwimOffCourseCode($txt)
    {
        $this->_swim_off_course_code = $txt ;
    }

    /**
     * Get Swim Off Course Code
     *
     * @return string swim off course code
     */
    function getSwimOffCourseCode()
    {
        return $this->_swim_off_course_code ;
    }

    /**
     * Set Finals Time
     *
     * @param string finals time
     */
    function setFinalsTime($txt, $db = false)
    {
        //  A time can be format can be formatted several way.
        //
        //  1)  All blanks
        //  2)  mm:ss.ss - the mm: portion of the time is optional
        //  3)  Time Code value - DQ, NS, etc. - from the Time Code table.
        //
        //  Internally Flip=Turn will store times as a floating point number
        //  representing the total number of seconds for the time.  This means
        //  a time such as 1:01.22 will be stored as 61.22.  Storing times in
        //  this manner makes them much easier to compare for fastest and/or
        //  slowest times.

        $this->_finals_time = $txt ;

        //  Time in mm:ss.ss?
        if (preg_match('/[0-9][0-9]:[0-9][0-9]\.[0-9][0-9]/', $txt))
        {
            //printf('<h3>mm:ss.ss - %s</h3>', $txt) ;
            $time = explode($txt, ':') ;
            $this->_finals_time_ft = $time[0] * 60 + $time[1] ;

        }
        //  Time in ss.ss?
        else if (preg_match('/[0-9][0-9]\.[0-9][0-9]/', $txt))
        {
            //printf('<h3>ss.ss - %s</h3>', $txt) ;
            $this->_finals_time_ft = (float)$txt ;
        }
        else
        {
            //printf('<h3>????? - %s</h3>', $txt) ;
            $this->_finals_time_ft = 0.0 ;
        }

    }

    /**
     * Get Finals Time
     *
     * @return string finals time
     */
    function getFinalsTime($ft = false)
    {
        if ($ft)
            return $this->_finals_time_ft ;
        else
            return $this->_finals_time ;
    }

    /**
     * Set Finals Course Code
     *
     * @param string finals course code
     */
    function setFinalsCourseCode($txt)
    {
        $this->_finals_course_code = $txt ;
    }

    /**
     * Get Finals Course Code
     *
     * @return string finals course code
     */
    function getFinalsCourseCode()
    {
        return $this->_finals_course_code ;
    }

    /**
     * Set Prelim Heat Number
     *
     * @param string prelim heat number
     */
    function setPrelimHeatNumber($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_prelim_heat_number = $txt ;
    }

    /**
     * Get Prelim Heat Number
     *
     * @return string prelim heat number
     */
    function getPrelimHeatNumber()
    {
        return $this->_prelim_heat_number ;
    }

    /**
     * Set Prelim Lane Number
     *
     * @param string prelim lane number
     */
    function setPrelimLaneNumber($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_prelim_lane_number = $txt ;
    }

    /**
     * Get Prelim Lane Number
     *
     * @return string prelim lane number
     */
    function getPrelimLaneNumber()
    {
        return $this->_prelim_lane_number ;
    }

    /**
     * Set Finals Heat Number
     *
     * @param string finals heat number
     */
    function setFinalsHeatNumber($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_finals_heat_number = $txt ;
    }

    /**
     * Get Finals Heat Number
     *
     * @return string finals heat number
     */
    function getFinalsHeatNumber()
    {
        return $this->_finals_heat_number ;
    }

    /**
     * Set Finals Lane Number
     *
     * @param string finals lane number
     */
    function setFinalsLaneNumber($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_finals_lane_number = $txt ;
    }

    /**
     * Get Finals Lane Number
     *
     * @return string finals lane number
     */
    function getFinalsLaneNumber()
    {
        return $this->_finals_lane_number ;
    }

    /**
     * Set Prelim Place Ranking
     *
     * @param string prelim place ranking
     */
    function setPrelimPlaceRanking($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_prelim_place_ranking = $txt ;
    }

    /**
     * Get Prelim Place Ranking
     *
     * @return string prelim place ranking
     */
    function getPrelimPlaceRanking()
    {
        return $this->_prelim_place_ranking ;
    }

    /**
     * Set Finals Place Ranking
     *
     * @param string finals place ranking
     */
    function setFinalsPlaceRanking($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_finals_place_ranking = $txt ;
    }

    /**
     * Get Finals Place Ranking
     *
     * @return string finals place ranking
     */
    function getFinalsPlaceRanking()
    {
        return $this->_finals_place_ranking ;
    }

    /**
     * Set Finals Points
     *
     * @param string finals points
     */
    function setFinalsPoints($txt)
    {
        if (empty($txt)) $txt = 0 ;

        $this->_finals_points = $txt ;
    }

    /**
     * Get Finals Points
     *
     * @return string finals points
     */
    function getFinalsPoints()
    {
        return $this->_finals_points ;
    }

    /**
     * Set Event Time Class Code
     *
     * @param string event time class code
     */
    function setEventTimeClassCode($txt)
    {
        $this->_event_time_class_code = $txt ;
    }

    /**
     * Get Event Time Class Code
     *
     * @return string event time class code
     */
    function getEventTimeClassCode()
    {
        return $this->_event_time_class_code ;
    }

    /**
     * Set Swimmer Flight Status
     *
     * @param string swimmer flight status
     */
    function setSwimmerFlightStatus($txt)
    {
        $this->_swimmer_flight_status = $txt ;
    }

    /**
     * Get Swimmer Flight Status
     *
     * @return string swimmer flight status
     */
    function getSwimmerFlightStatus()
    {
        return $this->_swimmer_flight_status ;
    }
}

/**
 * SDIF D0 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFDxRecord
 */
class SDIFD0Record extends SDIFDxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
            print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setSwimmerName(trim(substr($this->_sdif_record, 11, 28))) ;
        $this->setUSS(trim(substr($this->_sdif_record, 39, 12))) ;
        $this->setAttachCode(trim(substr($this->_sdif_record, 51, 1))) ;
        $this->setCitizenCode(trim(substr($this->_sdif_record, 52, 3))) ;
        $this->setBirthDate(trim(substr($this->_sdif_record, 55, 8))) ;
        $this->setAgeOrClass(trim(substr($this->_sdif_record, 63, 2))) ;
        $this->setGender(trim(substr($this->_sdif_record, 65, 1))) ;
        $this->setEventGender(trim(substr($this->_sdif_record, 66, 1))) ;
        $this->setEventDistance(trim(substr($this->_sdif_record, 67, 4))) ;
        $this->setStrokeCode(trim(substr($this->_sdif_record, 71, 1))) ;
        $this->setEventNumber(trim(substr($this->_sdif_record, 72, 4))) ;
        $this->setEventAgeCode(trim(substr($this->_sdif_record, 76, 4))) ;
        $this->setSwimDate(trim(substr($this->_sdif_record, 80, 8))) ;
        $this->setSeedTime(trim(substr($this->_sdif_record, 88, 8))) ;
        $this->setSeedCourseCode(trim(substr($this->_sdif_record, 96, 1))) ;
        $this->setPrelimTime(trim(substr($this->_sdif_record, 97, 8))) ;
        $this->setPrelimCourseCode(trim(substr($this->_sdif_record, 105, 1))) ;
        $this->setSwimOffTime(trim(substr($this->_sdif_record, 106, 8))) ;
        $this->setSwimOffCourseCode(trim(substr($this->_sdif_record, 114, 1))) ;
        $this->setFinalsTime(trim(substr($this->_sdif_record, 115, 8))) ;
        $this->setFinalsCourseCode(trim(substr($this->_sdif_record, 123, 1))) ;
        $this->setPrelimHeatNumber(trim(substr($this->_sdif_record, 124, 2))) ;
        $this->setPrelimLaneNumber(trim(substr($this->_sdif_record, 126, 2))) ;
        $this->setFinalsHeatNumber(trim(substr($this->_sdif_record, 128, 2))) ;
        $this->setFinalsLaneNumber(trim(substr($this->_sdif_record, 130, 2))) ;
        $this->setPrelimPlaceRanking(trim(substr($this->_sdif_record, 132, 3))) ;
        $this->setFinalsPlaceRanking(trim(substr($this->_sdif_record, 135, 3))) ;
        $this->setFinalsPoints(trim(substr($this->_sdif_record, 138, 4))) ;
        $this->setEventTimeClassCode(trim(substr($this->_sdif_record, 142, 2))) ;
        $this->setSwimmerFlightStatus(trim(substr($this->_sdif_record, 144, 1))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 145, 15))) ;

        //  Construct 'new' and 'old' formats of the USS number
        //  from the name and birthdate fields.

        $this->setUSSNew() ;
        $this->setUSSOld() ;
    }
}

/**
 * SDIF D1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFDxRecord
 */
class SDIFD1Record extends SDIFDxRecord
{
    /**
     * First Admin property
     */
    var $_admin_info_1 ;

    /**
     * Fourth Admin property
     */
    var $_admin_info_4 ;

    /**
     * Set Admin Info 1
     *
     * @param string admin info
     */
    function setAdminInfo1($txt)
    {
        $this->_admin_info_1 = $txt ;
    }

    /**
     * Get Admin Info
     *
     * @return string admin info
     */
    function getAdminInfo1()
    {
        return $this->_admin_info_1 ;
    }

    /**
     * Set Admin Info 4
     *
     * @param string admin info
     */
    function setAdminInfo4($txt)
    {
        $this->_admin_info_4 = $txt ;
    }

    /**
     * Get Admin Info
     *
     * @return string admin info
     */
    function getAdminInfo4()
    {
        return $this->_admin_info_4 ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
            print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 11, 6))) ;
        $this->setTeamCode5(trim(substr($this->_sdif_record, 17, 1))) ;
        $this->setSwimmerName(trim(substr($this->_sdif_record, 18, 28))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 46, 1))) ;
        $this->setUSS(trim(substr($this->_sdif_record, 47, 12))) ;
        $this->setAttachCode(trim(substr($this->_sdif_record, 59, 1))) ;
        $this->setCitizenCode(trim(substr($this->_sdif_record, 60, 3))) ;
        $this->setBirthDate(trim(substr($this->_sdif_record, 63, 8))) ;
        $this->setAgeOrClass(trim(substr($this->_sdif_record, 71, 2))) ;
        $this->setGender(trim(substr($this->_sdif_record, 73, 1))) ;
        $this->setAdminInfo1(trim(substr($this->_sdif_record, 74, 30))) ;
        $this->setAdminInfo4(trim(substr($this->_sdif_record, 104, 20))) ;
        $this->setPhone(trim(substr($this->_sdif_record, 124, 12))) ;

        //  Construct 'new' and 'old' formats of the USS number
        //  from the name and birthdate fields.

        $this->setUSSNew() ;
        $this->setUSSOld() ;
    }
}

/**
 * SDIF Z0 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFZ0Record extends SDIFRecord
{
    /**
     * Org Code
     */
    var $_org_code ;

    /**
     * Future Use #1
     */
    var $_future_use_1 ;

    /**
     * File Code
     */
    var $_file_code ;

    /**
     * Notes
     */
    var $_notes ;

    /**
     * B record count
     */
    var $_b_record_count ;

    /**
     * Meet Count
     */
    var $_meet_count ;

    /**
     * C record count
     */
    var $_c_record_count ;

    /**
     * Team Count
     */
    var $_team_count ;

    /**
     * D record count
     */
    var $_d_record_count ;

    /**
     * Swimmer Count
     */
    var $_swimmer_count ;

    /**
     * E record count
     */
    var $_e_record_count ;

    /**
     * F record count
     */
    var $_f_record_count ;

    /**
     * G record count
     */
    var $_g_record_count ;

    /**
     * Batch Number
     */
    var $_batch_number ;

    /**
     * New Member Count
     */
    var $_new_member_count ;

    /**
     * Renew Member Count
     */
    var $_renew_member_count ;

    /**
     * Change Member Count
     */
    var $_change_member_count ;

    /**
     * Delete Member Count
     */
    var $_delete_member_count ;

    /**
     * Future Use #2
     */
    var $_future_use_2 ;

    /**
     * Set Org Code
     *
     * @param int org code
     */
    function setOrgCode($code)
    {
        $this->_org_code = $code ;
    }

    /**
     * Get Org Code
     *
     * @return int org code
     */
    function getOrgCode()
    {
        return $this->_org_code ;
    }

    /**
     * Set Future Use 1
     *
     * @param string future use 1
     */
    function setFutureUse1($txt)
    {
        $this->_future_use_1 = $txt ;
    }

    /**
     * Get Future Use 1
     *
     * @return string future use 1
     */
    function getFutureUse1()
    {
        return $this->_future_use_1 ;
    }

    /**
     * Set File Code
     *
     * @param string file code
     */
    function setFileCode($txt)
    {
        $this->_file_code = $txt ;
    }

    /**
     * Get File Code
     *
     * @return string file code
     */
    function getFileCode()
    {
        return $this->_file_code ;
    }

    /**
     * Set Notes
     *
     * @param string notes
     */
    function setNotes($txt)
    {
        $this->_notes = $txt ;
    }

    /**
     * Get Notes
     *
     * @return string notes
     */
    function getNotes()
    {
        return $this->_notes ;
    }

    /**
     * Set B record count
     *
     * @param int number of b records
     */
    function setBRecordCount($cnt)
    {
        $this->_b_record_count = $cnt ;
    }

    /**
     * Get B record count
     *
     * @param int number of b records
     */
    function getBRecordCount()
    {
        return $this->_b_record_count ;
    }

    /**
     * Set Meet Count
     *
     * @param int number of meets
     */
    function setMeetCount($cnt)
    {
        $this->_meet_count = $cnt ;
    }

    /**
     * Get Meet Count 
     *
     * @return int number of meets
     */
    function getMeetCount()
    {
        return $this->_meet_count ;
    }

    /**
     * Set C record count
     *
     * @param int number of c records
     */
    function setCRecordCount($cnt)
    {
        $this->_c_record_count = $cnt ;
    }

    /**
     * Get C record count
     *
     * @param int number of c records
     */
    function getCRecordCount()
    {
        return $this->_c_record_count ;
    }

    /**
     * Set Team Count
     *
     * @param int number of teams
     */
    function setTeamCount($cnt)
    {
        $this->_team_count = $cnt ;
    }

    /**
     * Get Team Count 
     *
     * @return int number of teams
     */
    function getTeamCount()
    {
        return $this->_team_count ;
    }

    /**
     * Set D record count
     *
     * @param int number of d records
     */
    function setDRecordCount($cnt)
    {
        $this->_d_record_count = $cnt ;
    }

    /**
     * Get D record count
     *
     * @param int number of d records
     */
    function getDRecordCount()
    {
        return $this->_d_record_count ;
    }

    /**
     * Set Swimmer Count
     *
     * @param int number of swimmers
     */
    function setSwimmerCount($cnt)
    {
        $this->_swimmer_count = $cnt ;
    }

    /**
     * Get Swimmer Count 
     *
     * @return int number of swimmers
     */
    function getSwimmerCount()
    {
        return $this->_swimmer_count ;
    }

    /**
     * Set E record count
     *
     * @param int number of e records
     */
    function setERecordCount($cnt)
    {
        $this->_e_record_count = $cnt ;
    }

    /**
     * Get E record count
     *
     * @param int number of e records
     */
    function getERecordCount()
    {
        return $this->_e_record_count ;
    }

    /**
     * Set F record count
     *
     * @param int number of f records
     */
    function setFRecordCount($cnt)
    {
        $this->_f_record_count = $cnt ;
    }

    /**
     * Get F record count
     *
     * @param int number of f records
     */
    function getFRecordCount()
    {
        return $this->_f_record_count ;
    }

    /**
     * Set G record count
     *
     * @param int number of g records
     */
    function setGRecordCount($cnt)
    {
        $this->_g_record_count = $cnt ;
    }

    /**
     * Get G record count
     *
     * @param int number of g records
     */
    function getGRecordCount()
    {
        return $this->_g_record_count ;
    }

    /**
     * Set Batch Number
     *
     * @param int number of batches
     */
    function setBatchNumber($cnt)
    {
        $this->_batch_number = $cnt ;
    }

    /**
     * Get Batch Number 
     *
     * @return int number of batches
     */
    function getBatchNumber()
    {
        return $this->_batch_number ;
    }

    /**
     * Set Future Use 2
     *
     * @param string future use 2
     */
    function setFutureUse2($txt)
    {
        $this->_future_use_2 = $txt ;
    }

    /**
     * Set New Member Count
     *
     * @param int number of new members
     */
    function setNewMemberCount($cnt)
    {
        $this->_new_member_count = $cnt ;
    }

    /**
     * Get New Member Count 
     *
     * @return int number of new members
     */
    function getNewMemberCount()
    {
        return $this->_new_member_count ;
    }

    /**
     * Set Renew Member Count
     *
     * @param int number of renew members
     */
    function setRenewMemberCount($cnt)
    {
        $this->_renew_member_count = $cnt ;
    }

    /**
     * Get Renew Member Count 
     *
     * @return int number of renew members
     */
    function getRenewMemberCount()
    {
        return $this->_renew_member_count ;
    }

    /**
     * Set Change Member Count
     *
     * @param int number of change members
     */
    function setChangeMemberCount($cnt)
    {
        $this->_change_member_count = $cnt ;
    }

    /**
     * Get Change Member Count 
     *
     * @return int number of change members
     */
    function getChangeMemberCount()
    {
        return $this->_change_member_count ;
    }

    /**
     * Set Delete Member Count
     *
     * @param int number of delete members
     */
    function setDeleteMemberCount($cnt)
    {
        $this->_delete_member_count = $cnt ;
    }

    /**
     * Get Delete Member Count 
     *
     * @return int number of delete members
     */
    function getDeleteMemberCount()
    {
        return $this->_delete_member_count ;
    }

    /**
     * Get Future Use 2
     *
     * @return string future use 2
     */
    function getFutureUse2()
    {
        return $this->_future_use_2 ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_SDIF_COLUMN_DEBUG1,
                WPST_SDIF_COLUMN_DEBUG2, $this->_sdif_record)) ;
            print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setFileCode(trim(substr($this->_sdif_record, 11, 2))) ;
        $this->setNotes(trim(substr($this->_sdif_record, 13, 30))) ;
        $this->setBRecordCount(trim(substr($this->_sdif_record, 43, 3))) ;
        $this->setMeetCount(trim(substr($this->_sdif_record, 46, 3))) ;
        $this->setCRecordCount(trim(substr($this->_sdif_record, 49, 4))) ;
        $this->setTeamCount(trim(substr($this->_sdif_record, 53, 4))) ;
        $this->setDRecordCount(trim(substr($this->_sdif_record, 57, 6))) ;
        $this->setSwimmerCount(trim(substr($this->_sdif_record, 63, 6))) ;
        $this->setERecordCount(trim(substr($this->_sdif_record, 69, 5))) ;
        $this->setFRecordCount(trim(substr($this->_sdif_record, 74, 6))) ;
        $this->setGRecordCount(trim(substr($this->_sdif_record, 80, 6))) ;
        $this->setBatchNumber(trim(substr($this->_sdif_record, 86, 5))) ;
        $this->setNewMemberCount(trim(substr($this->_sdif_record, 91, 3))) ;
        $this->setRenewMemberCount(trim(substr($this->_sdif_record, 94, 3))) ;
        $this->setChangeMemberCount(trim(substr($this->_sdif_record, 97, 3))) ;
        $this->setDeleteMemberCount(trim(substr($this->_sdif_record, 100, 3))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 103, 57))) ;
    }
}

/**
 * SDIF Code Tables
 *
 * The SDIF specification defines 26 tables that map code
 * values into some sort of textual reprsentation.  Some of
 * the mappings are very simple, for example, gender, others
 * are more complex, for example, country codes.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 */
class SDIFCodeTables
{
    /**
     * Return the Gender Code text based on the supplied
     * gender code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string gender code
     * @param boolean optional invalid mapping
     * @return string gender code description
     */
    function GetGenderCode($code, $invalid = true)
    {
        $WPST_SDIF_GENDER_CODES = array(
            WPST_SDIF_SWIMMER_SEX_CODE_MALE_VALUE => WPST_SDIF_SWIMMER_SEX_CODE_MALE_LABEL
           ,WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_VALUE => WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_GENDER_CODES))
            return $WPST_SDIF_GENDER_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Event Gender Code text based on the supplied
     * event gender code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string event gender code
     * @param boolean optional invalid mapping
     * @return string event gender code description
     */
    function GetEventGenderCode($code, $invalid = true)
    {
        $WPST_SDIF_EVENT_GENDER_CODES = array(
            WPST_SDIF_EVENT_SEX_CODE_MALE_VALUE => WPST_SDIF_EVENT_SEX_CODE_MALE_LABEL
           ,WPST_SDIF_EVENT_SEX_CODE_FEMALE_VALUE => WPST_SDIF_EVENT_SEX_CODE_FEMALE_LABEL
           ,WPST_SDIF_EVENT_SEX_CODE_MIXED_VALUE => WPST_SDIF_EVENT_SEX_CODE_MIXED_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_EVENT_GENDER_CODES))
            return $WPST_SDIF_EVENT_GENDER_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Attach Code text based on the supplied
     * attached code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string attached code
     * @param boolean optional invalid mapping
     * @return string attached code description
     */
    function GetAttachedCode($code, $invalid = true)
    {
        $WPST_SDIF_ATTACHED_CODES = array(
            WPST_SDIF_ATTACHED_CODE_ATTACHED_VALUE => WPST_SDIF_ATTACHED_CODE_ATTACHED_LABEL
           ,WPST_SDIF_ATTACHED_CODE_UNATTACHED_VALUE => WPST_SDIF_ATTACHED_CODE_UNATTACHED_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_ATTACHED_CODES))
            return $WPST_SDIF_ATTACHED_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Citizen Code text based on the supplied
     * citizen code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string citizen code
     * @param boolean optional invalid mapping
     * @return string citizen code description
     */
    function GetCitizenCode($code, $invalid = true)
    {
        $WPST_SDIF_CITIZEN_CODES = array(
            WPST_SDIF_CITIZENSHIP_CODE_DUAL_VALUE => WPST_SDIF_CITIZENSHIP_CODE_DUAL_LABEL
           ,WPST_SDIF_CITIZENSHIP_CODE_FOREIGN_VALUE => WPST_SDIF_CITIZENSHIP_CODE_FOREIGN_LABEL
        ) ;

        //  The citizen code can also come from the list of
        //  Country codes so look there first!

        $cc = SDIFCodeTables::GetCountryCode($code) ;

        if ($cc != '')
            return $cc ;
        else if (array_key_exists($code, $WPST_SDIF_CITIZEN_CODES))
            return $WPST_SDIF_CITIZEN_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Org Code text based on the supplied
     * org code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string org code
     * @param boolean optional invalid mapping
     * @return string org code description
     */
    function GetOrgCode($code, $invalid = true)
    {
        $WPST_SDIF_ORG_CODES = array(
            WPST_SDIF_ORG_CODE_USS_VALUE => WPST_SDIF_ORG_CODE_USS_LABEL
           ,WPST_SDIF_ORG_CODE_MASTERS_VALUE => WPST_SDIF_ORG_CODE_MASTERS_LABEL
           ,WPST_SDIF_ORG_CODE_NCAA_VALUE => WPST_SDIF_ORG_CODE_NCAA_LABEL
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_I_VALUE => WPST_SDIF_ORG_CODE_NCAA_DIV_I_LABEL
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_II_VALUE => WPST_SDIF_ORG_CODE_NCAA_DIV_II_LABEL
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_III_VALUE => WPST_SDIF_ORG_CODE_NCAA_DIV_III_LABEL
           ,WPST_SDIF_ORG_CODE_YMCA_VALUE => WPST_SDIF_ORG_CODE_YMCA_LABEL
           ,WPST_SDIF_ORG_CODE_FINA_VALUE => WPST_SDIF_ORG_CODE_FINA_LABEL
           ,WPST_SDIF_ORG_CODE_HIGH_SCHOOL_VALUE => WPST_SDIF_ORG_CODE_HIGH_SCHOOL_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_ORG_CODES))
            return $WPST_SDIF_ORG_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Course Code text based on the supplied
     * course code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string course code
     * @param boolean optional invalid mapping
     * @return string course code description
     */
    function GetCourseCode($code, $alt = false, $invalid = true)
    {
        if ($alt)
            $WPST_SDIF_COURSE_CODES = array(
                WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE => WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE => WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_LABEL
            ) ;

        else
            $WPST_SDIF_COURSE_CODES = array(
                WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE => WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE => WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL
               ,WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_VALUE => WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL
            ) ;

        if (array_key_exists($code, $WPST_SDIF_COURSE_CODES))
            return $WPST_SDIF_COURSE_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Stroke Code text based on the supplied
     * stroke code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string stroke code
     * @param boolean optional invalid mapping
     * @return string stroke code description
     */
    function GetStrokeCode($code, $invalid = true)
    {
        $WPST_SDIF_EVENT_STROKE_CODES = array(
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE => WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE => WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE => WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE => WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE => WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE => WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL
           ,WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE => WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_EVENT_STROKE_CODES))
            return $WPST_SDIF_EVENT_STROKE_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Region Code text based on the supplied
     * region code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string region code
     * @param boolean optional invalid mapping
     * @return string region code description
     */
    function GetRegionCode($code, $invalid = true)
    {
        $WPST_SDIF_REGION_CODES = array(
            WPST_SDIF_REGION_CODE_REGION_1_VALUE => WPST_SDIF_REGION_CODE_REGION_1_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_2_VALUE => WPST_SDIF_REGION_CODE_REGION_2_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_3_VALUE => WPST_SDIF_REGION_CODE_REGION_3_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_4_VALUE => WPST_SDIF_REGION_CODE_REGION_4_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_5_VALUE => WPST_SDIF_REGION_CODE_REGION_5_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_6_VALUE => WPST_SDIF_REGION_CODE_REGION_6_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_7_VALUE => WPST_SDIF_REGION_CODE_REGION_7_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_8_VALUE => WPST_SDIF_REGION_CODE_REGION_8_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_9_VALUE => WPST_SDIF_REGION_CODE_REGION_9_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_10_VALUE => WPST_SDIF_REGION_CODE_REGION_10_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_11_VALUE => WPST_SDIF_REGION_CODE_REGION_11_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_12_VALUE => WPST_SDIF_REGION_CODE_REGION_12_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_13_VALUE => WPST_SDIF_REGION_CODE_REGION_13_LABEL
           ,WPST_SDIF_REGION_CODE_REGION_14_VALUE => WPST_SDIF_REGION_CODE_REGION_14_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_REGION_CODES))
            return $WPST_SDIF_REGION_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Meet Code text based on the supplied
     * meet code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string meet code
     * @param boolean optional invalid mapping
     * @return string meet code description
     */
    function GetMeetCode($code, $invalid = true)
    {
        $WPST_SDIF_MEET_CODES = array(
            WPST_SDIF_MEET_TYPE_INVITATIONAL_VALUE => WPST_SDIF_MEET_TYPE_INVITATIONAL_LABEL
           ,WPST_SDIF_MEET_TYPE_REGIONAL_VALUE => WPST_SDIF_MEET_TYPE_REGIONAL_LABEL
           ,WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE => WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL
           ,WPST_SDIF_MEET_TYPE_ZONE_VALUE => WPST_SDIF_MEET_TYPE_ZONE_LABEL
           ,WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE => WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL
           ,WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE => WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL
           ,WPST_SDIF_MEET_TYPE_JUNIORS_VALUE => WPST_SDIF_MEET_TYPE_JUNIORS_LABEL
           ,WPST_SDIF_MEET_TYPE_SENIORS_VALUE => WPST_SDIF_MEET_TYPE_SENIORS_LABEL
           ,WPST_SDIF_MEET_TYPE_DUAL_VALUE => WPST_SDIF_MEET_TYPE_DUAL_LABEL
           ,WPST_SDIF_MEET_TYPE_TIME_TRIALS_VALUE => WPST_SDIF_MEET_TYPE_TIME_TRIALS_LABEL
           ,WPST_SDIF_MEET_TYPE_INTERNATIONAL_VALUE => WPST_SDIF_MEET_TYPE_INTERNATIONAL_LABEL
           ,WPST_SDIF_MEET_TYPE_OPEN_VALUE => WPST_SDIF_MEET_TYPE_OPEN_LABEL
           ,WPST_SDIF_MEET_TYPE_LEAGUE_VALUE => WPST_SDIF_MEET_TYPE_LEAGUE_LABEL
        ) ;

        if (array_key_exists($code, $WPST_SDIF_MEET_CODES))
            return $WPST_SDIF_MEET_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Country Code text based on the supplied
     * country code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string country code
     * @param boolean optional invalid mapping
     * @return string country code description
     */
    function GetCountryCode($code, $invalid = true)
    {
		$WPST_SDIF_COUNTRY_CODES = array(
		    WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_VALUE => WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ALBANIA_VALUE => WPST_SDIF_COUNTRY_CODE_ALBANIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ALGERIA_VALUE => WPST_SDIF_COUNTRY_CODE_ALGERIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_VALUE => WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ANDORRA_VALUE => WPST_SDIF_COUNTRY_CODE_ANDORRA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ANGOLA_VALUE => WPST_SDIF_COUNTRY_CODE_ANGOLA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ANTIGUA_VALUE => WPST_SDIF_COUNTRY_CODE_ANTIGUA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_VALUE => WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_VALUE => WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ARGENTINA_VALUE => WPST_SDIF_COUNTRY_CODE_ARGENTINA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ARMENIA_VALUE => WPST_SDIF_COUNTRY_CODE_ARMENIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ARUBA_VALUE => WPST_SDIF_COUNTRY_CODE_ARUBA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_AUSTRALIA_VALUE => WPST_SDIF_COUNTRY_CODE_AUSTRALIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_AUSTRIA_VALUE => WPST_SDIF_COUNTRY_CODE_AUSTRIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_VALUE => WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BAHAMAS_VALUE => WPST_SDIF_COUNTRY_CODE_BAHAMAS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BAHRAIN_VALUE => WPST_SDIF_COUNTRY_CODE_BAHRAIN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BANGLADESH_VALUE => WPST_SDIF_COUNTRY_CODE_BANGLADESH_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BARBADOS_VALUE => WPST_SDIF_COUNTRY_CODE_BARBADOS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BELARUS_VALUE => WPST_SDIF_COUNTRY_CODE_BELARUS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BELGIUM_VALUE => WPST_SDIF_COUNTRY_CODE_BELGIUM_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BELIZE_VALUE => WPST_SDIF_COUNTRY_CODE_BELIZE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BENIN_VALUE => WPST_SDIF_COUNTRY_CODE_BENIN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BERMUDA_VALUE => WPST_SDIF_COUNTRY_CODE_BERMUDA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BHUTAN_VALUE => WPST_SDIF_COUNTRY_CODE_BHUTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BOLIVIA_VALUE => WPST_SDIF_COUNTRY_CODE_BOLIVIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BOTSWANA_VALUE => WPST_SDIF_COUNTRY_CODE_BOTSWANA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BRAZIL_VALUE => WPST_SDIF_COUNTRY_CODE_BRAZIL_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BRUNEI_VALUE => WPST_SDIF_COUNTRY_CODE_BRUNEI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BULGARIA_VALUE => WPST_SDIF_COUNTRY_CODE_BULGARIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_VALUE => WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CAMEROON_VALUE => WPST_SDIF_COUNTRY_CODE_CAMEROON_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CANADA_VALUE => WPST_SDIF_COUNTRY_CODE_CANADA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_VALUE => WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CHAD_VALUE => WPST_SDIF_COUNTRY_CODE_CHAD_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CHILE_VALUE => WPST_SDIF_COUNTRY_CODE_CHILE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_VALUE => WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_COLUMBIA_VALUE => WPST_SDIF_COUNTRY_CODE_COLUMBIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_COSTA_RICA_VALUE => WPST_SDIF_COUNTRY_CODE_COSTA_RICA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CROATIA_VALUE => WPST_SDIF_COUNTRY_CODE_CROATIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CUBA_VALUE => WPST_SDIF_COUNTRY_CODE_CUBA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CYPRUS_VALUE => WPST_SDIF_COUNTRY_CODE_CYPRUS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_VALUE => WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_VALUE => WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_DENMARK_VALUE => WPST_SDIF_COUNTRY_CODE_DENMARK_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_DJIBOUTI_VALUE => WPST_SDIF_COUNTRY_CODE_DJIBOUTI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_VALUE => WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ECUADOR_VALUE => WPST_SDIF_COUNTRY_CODE_ECUADOR_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_VALUE => WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_VALUE => WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ESTONIA_VALUE => WPST_SDIF_COUNTRY_CODE_ESTONIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ETHIOPIA_VALUE => WPST_SDIF_COUNTRY_CODE_ETHIOPIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_FIJI_VALUE => WPST_SDIF_COUNTRY_CODE_FIJI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_FINLAND_VALUE => WPST_SDIF_COUNTRY_CODE_FINLAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_FRANCE_VALUE => WPST_SDIF_COUNTRY_CODE_FRANCE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GABON_VALUE => WPST_SDIF_COUNTRY_CODE_GABON_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GAMBIA_VALUE => WPST_SDIF_COUNTRY_CODE_GAMBIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GEORGIA_VALUE => WPST_SDIF_COUNTRY_CODE_GEORGIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GERMANY_VALUE => WPST_SDIF_COUNTRY_CODE_GERMANY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GHANA_VALUE => WPST_SDIF_COUNTRY_CODE_GHANA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_VALUE => WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GREECE_VALUE => WPST_SDIF_COUNTRY_CODE_GREECE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GRENADA_VALUE => WPST_SDIF_COUNTRY_CODE_GRENADA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GUAM_VALUE => WPST_SDIF_COUNTRY_CODE_GUAM_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GUATEMALA_VALUE => WPST_SDIF_COUNTRY_CODE_GUATEMALA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GUINEA_VALUE => WPST_SDIF_COUNTRY_CODE_GUINEA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_GUYANA_VALUE => WPST_SDIF_COUNTRY_CODE_GUYANA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_HAITI_VALUE => WPST_SDIF_COUNTRY_CODE_HAITI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_HONDURAS_VALUE => WPST_SDIF_COUNTRY_CODE_HONDURAS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_HONG_KONG_VALUE => WPST_SDIF_COUNTRY_CODE_HONG_KONG_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_HUNGARY_VALUE => WPST_SDIF_COUNTRY_CODE_HUNGARY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ICELAND_VALUE => WPST_SDIF_COUNTRY_CODE_ICELAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_INDIA_VALUE => WPST_SDIF_COUNTRY_CODE_INDIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_INDONESIA_VALUE => WPST_SDIF_COUNTRY_CODE_INDONESIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_IRAQ_VALUE => WPST_SDIF_COUNTRY_CODE_IRAQ_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_IRELAND_VALUE => WPST_SDIF_COUNTRY_CODE_IRELAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_VALUE => WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ISRAEL_VALUE => WPST_SDIF_COUNTRY_CODE_ISRAEL_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ITALY_VALUE => WPST_SDIF_COUNTRY_CODE_ITALY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_IVORY_COAST_VALUE => WPST_SDIF_COUNTRY_CODE_IVORY_COAST_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_JAMAICA_VALUE => WPST_SDIF_COUNTRY_CODE_JAMAICA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_JAPAN_VALUE => WPST_SDIF_COUNTRY_CODE_JAPAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_JORDAN_VALUE => WPST_SDIF_COUNTRY_CODE_JORDAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_VALUE => WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_KENYA_VALUE => WPST_SDIF_COUNTRY_CODE_KENYA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_VALUE => WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_KUWAIT_VALUE => WPST_SDIF_COUNTRY_CODE_KUWAIT_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_VALUE => WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LAOS_VALUE => WPST_SDIF_COUNTRY_CODE_LAOS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LATVIA_VALUE => WPST_SDIF_COUNTRY_CODE_LATVIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LEBANON_VALUE => WPST_SDIF_COUNTRY_CODE_LEBANON_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LESOTHO_VALUE => WPST_SDIF_COUNTRY_CODE_LESOTHO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LIBERIA_VALUE => WPST_SDIF_COUNTRY_CODE_LIBERIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LIBYA_VALUE => WPST_SDIF_COUNTRY_CODE_LIBYA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_VALUE => WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LITHUANIA_VALUE => WPST_SDIF_COUNTRY_CODE_LITHUANIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_VALUE => WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MADAGASCAR_VALUE => WPST_SDIF_COUNTRY_CODE_MADAGASCAR_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MALAWI_VALUE => WPST_SDIF_COUNTRY_CODE_MALAWI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MALAYSIA_VALUE => WPST_SDIF_COUNTRY_CODE_MALAYSIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MALDIVES_VALUE => WPST_SDIF_COUNTRY_CODE_MALDIVES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MALI_VALUE => WPST_SDIF_COUNTRY_CODE_MALI_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MALTA_VALUE => WPST_SDIF_COUNTRY_CODE_MALTA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MAURITANIA_VALUE => WPST_SDIF_COUNTRY_CODE_MAURITANIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MAURITIUS_VALUE => WPST_SDIF_COUNTRY_CODE_MAURITIUS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MEXICO_VALUE => WPST_SDIF_COUNTRY_CODE_MEXICO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MOLDOVA_VALUE => WPST_SDIF_COUNTRY_CODE_MOLDOVA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MONACO_VALUE => WPST_SDIF_COUNTRY_CODE_MONACO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MONGOLIA_VALUE => WPST_SDIF_COUNTRY_CODE_MONGOLIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MOROCCO_VALUE => WPST_SDIF_COUNTRY_CODE_MOROCCO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_VALUE => WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NAMIBIA_VALUE => WPST_SDIF_COUNTRY_CODE_NAMIBIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NEPAL_VALUE => WPST_SDIF_COUNTRY_CODE_NEPAL_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_VALUE => WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NICARAGUA_VALUE => WPST_SDIF_COUNTRY_CODE_NICARAGUA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NIGER_VALUE => WPST_SDIF_COUNTRY_CODE_NIGER_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NIGERIA_VALUE => WPST_SDIF_COUNTRY_CODE_NIGERIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_NORWAY_VALUE => WPST_SDIF_COUNTRY_CODE_NORWAY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_OMAN_VALUE => WPST_SDIF_COUNTRY_CODE_OMAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PAKISTAN_VALUE => WPST_SDIF_COUNTRY_CODE_PAKISTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PANAMA_VALUE => WPST_SDIF_COUNTRY_CODE_PANAMA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_VALUE => WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PARAGUAY_VALUE => WPST_SDIF_COUNTRY_CODE_PARAGUAY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_VALUE => WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_VALUE => WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PERU_VALUE => WPST_SDIF_COUNTRY_CODE_PERU_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PHILIPPINES_VALUE => WPST_SDIF_COUNTRY_CODE_PHILIPPINES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_POLAND_VALUE => WPST_SDIF_COUNTRY_CODE_POLAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PORTUGAL_VALUE => WPST_SDIF_COUNTRY_CODE_PORTUGAL_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_VALUE => WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_QATAR_VALUE => WPST_SDIF_COUNTRY_CODE_QATAR_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ROMANIA_VALUE => WPST_SDIF_COUNTRY_CODE_ROMANIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_RUSSIA_VALUE => WPST_SDIF_COUNTRY_CODE_RUSSIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_RWANDA_VALUE => WPST_SDIF_COUNTRY_CODE_RWANDA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SAN_MARINO_VALUE => WPST_SDIF_COUNTRY_CODE_SAN_MARINO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_VALUE => WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SENEGAL_VALUE => WPST_SDIF_COUNTRY_CODE_SENEGAL_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SEYCHELLES_VALUE => WPST_SDIF_COUNTRY_CODE_SEYCHELLES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_VALUE => WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SINGAPORE_VALUE => WPST_SDIF_COUNTRY_CODE_SINGAPORE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SLOVENIA_VALUE => WPST_SDIF_COUNTRY_CODE_SLOVENIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SOMALIA_VALUE => WPST_SDIF_COUNTRY_CODE_SOMALIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_VALUE => WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SPAIN_VALUE => WPST_SDIF_COUNTRY_CODE_SPAIN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SRI_LANKA_VALUE => WPST_SDIF_COUNTRY_CODE_SRI_LANKA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_VALUE => WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SUDAN_VALUE => WPST_SDIF_COUNTRY_CODE_SUDAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SURINAM_VALUE => WPST_SDIF_COUNTRY_CODE_SURINAM_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SWAZILAND_VALUE => WPST_SDIF_COUNTRY_CODE_SWAZILAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SWEDEN_VALUE => WPST_SDIF_COUNTRY_CODE_SWEDEN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SWITZERLAND_VALUE => WPST_SDIF_COUNTRY_CODE_SWITZERLAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_SYRIA_VALUE => WPST_SDIF_COUNTRY_CODE_SYRIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_VALUE => WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TANZANIA_VALUE => WPST_SDIF_COUNTRY_CODE_TANZANIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_THAILAND_VALUE => WPST_SDIF_COUNTRY_CODE_THAILAND_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TOGO_VALUE => WPST_SDIF_COUNTRY_CODE_TOGO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TONGA_VALUE => WPST_SDIF_COUNTRY_CODE_TONGA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_VALUE => WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TUNISIA_VALUE => WPST_SDIF_COUNTRY_CODE_TUNISIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_TURKEY_VALUE => WPST_SDIF_COUNTRY_CODE_TURKEY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_UGANDA_VALUE => WPST_SDIF_COUNTRY_CODE_UGANDA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_UKRAINE_VALUE => WPST_SDIF_COUNTRY_CODE_UKRAINE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_VALUE => WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_VALUE => WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE => WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_URUGUAY_VALUE => WPST_SDIF_COUNTRY_CODE_URUGUAY_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_VANUATU_VALUE => WPST_SDIF_COUNTRY_CODE_VANUATU_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_VENEZUELA_VALUE => WPST_SDIF_COUNTRY_CODE_VENEZUELA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_VIETNAM_VALUE => WPST_SDIF_COUNTRY_CODE_VIETNAM_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_VALUE => WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_VALUE => WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_YEMEN_VALUE => WPST_SDIF_COUNTRY_CODE_YEMEN_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_VALUE => WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ZAIRE_VALUE => WPST_SDIF_COUNTRY_CODE_ZAIRE_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ZAMBIA_VALUE => WPST_SDIF_COUNTRY_CODE_ZAMBIA_LABEL
		   ,WPST_SDIF_COUNTRY_CODE_ZIMBABWE_VALUE => WPST_SDIF_COUNTRY_CODE_ZIMBABWE_LABEL
		) ;

        if (array_key_exists($code, $WPST_SDIF_COUNTRY_CODES))
            return $WPST_SDIF_COUNTRY_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }
}

/**
 * SDIF Code Tables
 *
 * The SDIF specification defines 26 tables that map code
 * values into some sort of textual reprsentation.  Some of
 * the mappings are very simple, for example, gender, others
 * are more complex, for example, country codes.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 */
class SDIFCodeTableMappings
{
    /**
     * Return an array of course codes and their mappings
     *
     * @return array org code mappings
     */
    function GetOrgCodes()
    {
        $WPST_SDIF_ORG_CODES = array(
            'Select Organization' => WPST_NULL_STRING
           ,WPST_SDIF_ORG_CODE_USS_LABEL => WPST_SDIF_ORG_CODE_USS_VALUE
           ,WPST_SDIF_ORG_CODE_MASTERS_LABEL => WPST_SDIF_ORG_CODE_MASTERS_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_LABEL => WPST_SDIF_ORG_CODE_NCAA_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_I_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_I_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_II_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_II_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_III_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_III_VALUE
           ,WPST_SDIF_ORG_CODE_YMCA_LABEL => WPST_SDIF_ORG_CODE_YMCA_VALUE
           ,WPST_SDIF_ORG_CODE_FINA_LABEL => WPST_SDIF_ORG_CODE_FINA_VALUE
           ,WPST_SDIF_ORG_CODE_HIGH_SCHOOL_LABEL => WPST_SDIF_ORG_CODE_HIGH_SCHOOL_VALUE
        ) ;

        return $WPST_SDIF_ORG_CODES ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCourseCodes($dq = false)
    {
        $WPST_SDIF_COURSE_CODES = array(
            'Select Course' => WPST_NULL_STRING
           ,WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL => WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE
           ,WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL => WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE
           ,WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL => WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE
        ) ;

        //  Include the DQ option?  Not included by default.

        if ($dq)
            $WPST_SDIF_COURSE_CODES[
                WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL] = WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE ;
 
        return $WPST_SDIF_COURSE_CODES ;
    }

    /**
     * Return an array of region codes and their mappings
     *
     * @return string region code description
     */
    function GetRegionCodes()
    {
        $WPST_SDIF_REGION_CODES = array(
            'Select Region' => WPST_NULL_STRING
           ,WPST_SDIF_REGION_CODE_REGION_1_LABEL => WPST_SDIF_REGION_CODE_REGION_1_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_2_LABEL => WPST_SDIF_REGION_CODE_REGION_2_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_3_LABEL => WPST_SDIF_REGION_CODE_REGION_3_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_4_LABEL => WPST_SDIF_REGION_CODE_REGION_4_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_5_LABEL => WPST_SDIF_REGION_CODE_REGION_5_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_6_LABEL => WPST_SDIF_REGION_CODE_REGION_6_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_7_LABEL => WPST_SDIF_REGION_CODE_REGION_7_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_8_LABEL => WPST_SDIF_REGION_CODE_REGION_8_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_9_LABEL => WPST_SDIF_REGION_CODE_REGION_9_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_10_LABEL => WPST_SDIF_REGION_CODE_REGION_10_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_11_LABEL => WPST_SDIF_REGION_CODE_REGION_11_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_12_LABEL => WPST_SDIF_REGION_CODE_REGION_12_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_13_LABEL => WPST_SDIF_REGION_CODE_REGION_13_VALUE
           ,WPST_SDIF_REGION_CODE_REGION_14_LABEL => WPST_SDIF_REGION_CODE_REGION_14_VALUE
        ) ;

        return $WPST_SDIF_REGION_CODES ;
    }

    /**
     * Return an array of meet codes and their mappings
     *
     * @return string meet code description
     */
    function GetMeetCodes()
    {
        $WPST_SDIF_MEET_CODES = array(
            'Select Meet' => WPST_NULL_STRING
           ,WPST_SDIF_MEET_TYPE_INVITATIONAL_LABEL => WPST_SDIF_MEET_TYPE_INVITATIONAL_VALUE
           ,WPST_SDIF_MEET_TYPE_REGIONAL_LABEL => WPST_SDIF_MEET_TYPE_REGIONAL_VALUE
           ,WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL => WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE
           ,WPST_SDIF_MEET_TYPE_ZONE_LABEL => WPST_SDIF_MEET_TYPE_ZONE_VALUE
           ,WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL => WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE
           ,WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL => WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE
           ,WPST_SDIF_MEET_TYPE_JUNIORS_LABEL => WPST_SDIF_MEET_TYPE_JUNIORS_VALUE
           ,WPST_SDIF_MEET_TYPE_SENIORS_LABEL => WPST_SDIF_MEET_TYPE_SENIORS_VALUE
           ,WPST_SDIF_MEET_TYPE_DUAL_LABEL => WPST_SDIF_MEET_TYPE_DUAL_VALUE
           ,WPST_SDIF_MEET_TYPE_TIME_TRIALS_LABEL => WPST_SDIF_MEET_TYPE_TIME_TRIALS_VALUE
           ,WPST_SDIF_MEET_TYPE_INTERNATIONAL_LABEL => WPST_SDIF_MEET_TYPE_INTERNATIONAL_VALUE
           ,WPST_SDIF_MEET_TYPE_OPEN_LABEL => WPST_SDIF_MEET_TYPE_OPEN_VALUE
           ,WPST_SDIF_MEET_TYPE_LEAGUE_LABEL => WPST_SDIF_MEET_TYPE_LEAGUE_VALUE
        ) ;

        return $WPST_SDIF_MEET_CODES ;
    }

    /**
     * Return an array of country codes and their mappings
     *
     * @return array country code description mappings
     */
    function GetCountryCodes()
    {
		$WPST_SDIF_COUNTRY_CODES = array(
            'Select Country' => WPST_NULL_STRING
		   ,WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_LABEL => WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ALBANIA_LABEL => WPST_SDIF_COUNTRY_CODE_ALBANIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ALGERIA_LABEL => WPST_SDIF_COUNTRY_CODE_ALGERIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_LABEL => WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ANDORRA_LABEL => WPST_SDIF_COUNTRY_CODE_ANDORRA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ANGOLA_LABEL => WPST_SDIF_COUNTRY_CODE_ANGOLA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ANTIGUA_LABEL => WPST_SDIF_COUNTRY_CODE_ANTIGUA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_LABEL => WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_LABEL => WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ARGENTINA_LABEL => WPST_SDIF_COUNTRY_CODE_ARGENTINA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ARMENIA_LABEL => WPST_SDIF_COUNTRY_CODE_ARMENIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ARUBA_LABEL => WPST_SDIF_COUNTRY_CODE_ARUBA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_AUSTRALIA_LABEL => WPST_SDIF_COUNTRY_CODE_AUSTRALIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_AUSTRIA_LABEL => WPST_SDIF_COUNTRY_CODE_AUSTRIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_LABEL => WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BAHAMAS_LABEL => WPST_SDIF_COUNTRY_CODE_BAHAMAS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BAHRAIN_LABEL => WPST_SDIF_COUNTRY_CODE_BAHRAIN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BANGLADESH_LABEL => WPST_SDIF_COUNTRY_CODE_BANGLADESH_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BARBADOS_LABEL => WPST_SDIF_COUNTRY_CODE_BARBADOS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BELARUS_LABEL => WPST_SDIF_COUNTRY_CODE_BELARUS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BELGIUM_LABEL => WPST_SDIF_COUNTRY_CODE_BELGIUM_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BELIZE_LABEL => WPST_SDIF_COUNTRY_CODE_BELIZE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BENIN_LABEL => WPST_SDIF_COUNTRY_CODE_BENIN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BERMUDA_LABEL => WPST_SDIF_COUNTRY_CODE_BERMUDA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BHUTAN_LABEL => WPST_SDIF_COUNTRY_CODE_BHUTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BOLIVIA_LABEL => WPST_SDIF_COUNTRY_CODE_BOLIVIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BOTSWANA_LABEL => WPST_SDIF_COUNTRY_CODE_BOTSWANA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BRAZIL_LABEL => WPST_SDIF_COUNTRY_CODE_BRAZIL_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BRUNEI_LABEL => WPST_SDIF_COUNTRY_CODE_BRUNEI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BULGARIA_LABEL => WPST_SDIF_COUNTRY_CODE_BULGARIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_LABEL => WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CAMEROON_LABEL => WPST_SDIF_COUNTRY_CODE_CAMEROON_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CANADA_LABEL => WPST_SDIF_COUNTRY_CODE_CANADA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_LABEL => WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CHAD_LABEL => WPST_SDIF_COUNTRY_CODE_CHAD_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CHILE_LABEL => WPST_SDIF_COUNTRY_CODE_CHILE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_LABEL => WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_COLUMBIA_LABEL => WPST_SDIF_COUNTRY_CODE_COLUMBIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_COSTA_RICA_LABEL => WPST_SDIF_COUNTRY_CODE_COSTA_RICA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CROATIA_LABEL => WPST_SDIF_COUNTRY_CODE_CROATIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CUBA_LABEL => WPST_SDIF_COUNTRY_CODE_CUBA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CYPRUS_LABEL => WPST_SDIF_COUNTRY_CODE_CYPRUS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_LABEL => WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_LABEL => WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_DENMARK_LABEL => WPST_SDIF_COUNTRY_CODE_DENMARK_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_DJIBOUTI_LABEL => WPST_SDIF_COUNTRY_CODE_DJIBOUTI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_LABEL => WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ECUADOR_LABEL => WPST_SDIF_COUNTRY_CODE_ECUADOR_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_LABEL => WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_LABEL => WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ESTONIA_LABEL => WPST_SDIF_COUNTRY_CODE_ESTONIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ETHIOPIA_LABEL => WPST_SDIF_COUNTRY_CODE_ETHIOPIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_FIJI_LABEL => WPST_SDIF_COUNTRY_CODE_FIJI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_FINLAND_LABEL => WPST_SDIF_COUNTRY_CODE_FINLAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_FRANCE_LABEL => WPST_SDIF_COUNTRY_CODE_FRANCE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GABON_LABEL => WPST_SDIF_COUNTRY_CODE_GABON_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GAMBIA_LABEL => WPST_SDIF_COUNTRY_CODE_GAMBIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GEORGIA_LABEL => WPST_SDIF_COUNTRY_CODE_GEORGIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GERMANY_LABEL => WPST_SDIF_COUNTRY_CODE_GERMANY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GHANA_LABEL => WPST_SDIF_COUNTRY_CODE_GHANA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_LABEL => WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GREECE_LABEL => WPST_SDIF_COUNTRY_CODE_GREECE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GRENADA_LABEL => WPST_SDIF_COUNTRY_CODE_GRENADA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GUAM_LABEL => WPST_SDIF_COUNTRY_CODE_GUAM_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GUATEMALA_LABEL => WPST_SDIF_COUNTRY_CODE_GUATEMALA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GUINEA_LABEL => WPST_SDIF_COUNTRY_CODE_GUINEA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_GUYANA_LABEL => WPST_SDIF_COUNTRY_CODE_GUYANA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_HAITI_LABEL => WPST_SDIF_COUNTRY_CODE_HAITI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_HONDURAS_LABEL => WPST_SDIF_COUNTRY_CODE_HONDURAS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_HONG_KONG_LABEL => WPST_SDIF_COUNTRY_CODE_HONG_KONG_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_HUNGARY_LABEL => WPST_SDIF_COUNTRY_CODE_HUNGARY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ICELAND_LABEL => WPST_SDIF_COUNTRY_CODE_ICELAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_INDIA_LABEL => WPST_SDIF_COUNTRY_CODE_INDIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_INDONESIA_LABEL => WPST_SDIF_COUNTRY_CODE_INDONESIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_IRAQ_LABEL => WPST_SDIF_COUNTRY_CODE_IRAQ_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_IRELAND_LABEL => WPST_SDIF_COUNTRY_CODE_IRELAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_LABEL => WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ISRAEL_LABEL => WPST_SDIF_COUNTRY_CODE_ISRAEL_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ITALY_LABEL => WPST_SDIF_COUNTRY_CODE_ITALY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_IVORY_COAST_LABEL => WPST_SDIF_COUNTRY_CODE_IVORY_COAST_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_JAMAICA_LABEL => WPST_SDIF_COUNTRY_CODE_JAMAICA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_JAPAN_LABEL => WPST_SDIF_COUNTRY_CODE_JAPAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_JORDAN_LABEL => WPST_SDIF_COUNTRY_CODE_JORDAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_LABEL => WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_KENYA_LABEL => WPST_SDIF_COUNTRY_CODE_KENYA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_LABEL => WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_KUWAIT_LABEL => WPST_SDIF_COUNTRY_CODE_KUWAIT_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_LABEL => WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LAOS_LABEL => WPST_SDIF_COUNTRY_CODE_LAOS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LATVIA_LABEL => WPST_SDIF_COUNTRY_CODE_LATVIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LEBANON_LABEL => WPST_SDIF_COUNTRY_CODE_LEBANON_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LESOTHO_LABEL => WPST_SDIF_COUNTRY_CODE_LESOTHO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LIBERIA_LABEL => WPST_SDIF_COUNTRY_CODE_LIBERIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LIBYA_LABEL => WPST_SDIF_COUNTRY_CODE_LIBYA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_LABEL => WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LITHUANIA_LABEL => WPST_SDIF_COUNTRY_CODE_LITHUANIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_LABEL => WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MADAGASCAR_LABEL => WPST_SDIF_COUNTRY_CODE_MADAGASCAR_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MALAWI_LABEL => WPST_SDIF_COUNTRY_CODE_MALAWI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MALAYSIA_LABEL => WPST_SDIF_COUNTRY_CODE_MALAYSIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MALDIVES_LABEL => WPST_SDIF_COUNTRY_CODE_MALDIVES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MALI_LABEL => WPST_SDIF_COUNTRY_CODE_MALI_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MALTA_LABEL => WPST_SDIF_COUNTRY_CODE_MALTA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MAURITANIA_LABEL => WPST_SDIF_COUNTRY_CODE_MAURITANIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MAURITIUS_LABEL => WPST_SDIF_COUNTRY_CODE_MAURITIUS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MEXICO_LABEL => WPST_SDIF_COUNTRY_CODE_MEXICO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MOLDOVA_LABEL => WPST_SDIF_COUNTRY_CODE_MOLDOVA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MONACO_LABEL => WPST_SDIF_COUNTRY_CODE_MONACO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MONGOLIA_LABEL => WPST_SDIF_COUNTRY_CODE_MONGOLIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MOROCCO_LABEL => WPST_SDIF_COUNTRY_CODE_MOROCCO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_LABEL => WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NAMIBIA_LABEL => WPST_SDIF_COUNTRY_CODE_NAMIBIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NEPAL_LABEL => WPST_SDIF_COUNTRY_CODE_NEPAL_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_LABEL => WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NICARAGUA_LABEL => WPST_SDIF_COUNTRY_CODE_NICARAGUA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NIGER_LABEL => WPST_SDIF_COUNTRY_CODE_NIGER_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NIGERIA_LABEL => WPST_SDIF_COUNTRY_CODE_NIGERIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_NORWAY_LABEL => WPST_SDIF_COUNTRY_CODE_NORWAY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_OMAN_LABEL => WPST_SDIF_COUNTRY_CODE_OMAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PAKISTAN_LABEL => WPST_SDIF_COUNTRY_CODE_PAKISTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PANAMA_LABEL => WPST_SDIF_COUNTRY_CODE_PANAMA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_LABEL => WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PARAGUAY_LABEL => WPST_SDIF_COUNTRY_CODE_PARAGUAY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_LABEL => WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_LABEL => WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PERU_LABEL => WPST_SDIF_COUNTRY_CODE_PERU_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PHILIPPINES_LABEL => WPST_SDIF_COUNTRY_CODE_PHILIPPINES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_POLAND_LABEL => WPST_SDIF_COUNTRY_CODE_POLAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PORTUGAL_LABEL => WPST_SDIF_COUNTRY_CODE_PORTUGAL_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_LABEL => WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_QATAR_LABEL => WPST_SDIF_COUNTRY_CODE_QATAR_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ROMANIA_LABEL => WPST_SDIF_COUNTRY_CODE_ROMANIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_RUSSIA_LABEL => WPST_SDIF_COUNTRY_CODE_RUSSIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_RWANDA_LABEL => WPST_SDIF_COUNTRY_CODE_RWANDA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SAN_MARINO_LABEL => WPST_SDIF_COUNTRY_CODE_SAN_MARINO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_LABEL => WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SENEGAL_LABEL => WPST_SDIF_COUNTRY_CODE_SENEGAL_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SEYCHELLES_LABEL => WPST_SDIF_COUNTRY_CODE_SEYCHELLES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_LABEL => WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SINGAPORE_LABEL => WPST_SDIF_COUNTRY_CODE_SINGAPORE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SLOVENIA_LABEL => WPST_SDIF_COUNTRY_CODE_SLOVENIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SOMALIA_LABEL => WPST_SDIF_COUNTRY_CODE_SOMALIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_LABEL => WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SPAIN_LABEL => WPST_SDIF_COUNTRY_CODE_SPAIN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SRI_LANKA_LABEL => WPST_SDIF_COUNTRY_CODE_SRI_LANKA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_LABEL => WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SUDAN_LABEL => WPST_SDIF_COUNTRY_CODE_SUDAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SURINAM_LABEL => WPST_SDIF_COUNTRY_CODE_SURINAM_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SWAZILAND_LABEL => WPST_SDIF_COUNTRY_CODE_SWAZILAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SWEDEN_LABEL => WPST_SDIF_COUNTRY_CODE_SWEDEN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SWITZERLAND_LABEL => WPST_SDIF_COUNTRY_CODE_SWITZERLAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_SYRIA_LABEL => WPST_SDIF_COUNTRY_CODE_SYRIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_LABEL => WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TANZANIA_LABEL => WPST_SDIF_COUNTRY_CODE_TANZANIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_THAILAND_LABEL => WPST_SDIF_COUNTRY_CODE_THAILAND_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TOGO_LABEL => WPST_SDIF_COUNTRY_CODE_TOGO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TONGA_LABEL => WPST_SDIF_COUNTRY_CODE_TONGA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_LABEL => WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TUNISIA_LABEL => WPST_SDIF_COUNTRY_CODE_TUNISIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_TURKEY_LABEL => WPST_SDIF_COUNTRY_CODE_TURKEY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_UGANDA_LABEL => WPST_SDIF_COUNTRY_CODE_UGANDA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_UKRAINE_LABEL => WPST_SDIF_COUNTRY_CODE_UKRAINE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_LABEL => WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_LABEL => WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_LABEL => WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_URUGUAY_LABEL => WPST_SDIF_COUNTRY_CODE_URUGUAY_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_VANUATU_LABEL => WPST_SDIF_COUNTRY_CODE_VANUATU_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_VENEZUELA_LABEL => WPST_SDIF_COUNTRY_CODE_VENEZUELA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_VIETNAM_LABEL => WPST_SDIF_COUNTRY_CODE_VIETNAM_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_LABEL => WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_LABEL => WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_YEMEN_LABEL => WPST_SDIF_COUNTRY_CODE_YEMEN_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_LABEL => WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ZAIRE_LABEL => WPST_SDIF_COUNTRY_CODE_ZAIRE_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ZAMBIA_LABEL => WPST_SDIF_COUNTRY_CODE_ZAMBIA_VALUE
		   ,WPST_SDIF_COUNTRY_CODE_ZIMBABWE_LABEL => WPST_SDIF_COUNTRY_CODE_ZIMBABWE_VALUE
		) ;

        return $WPST_SDIF_COUNTRY_CODES ;
    }
}
?>
