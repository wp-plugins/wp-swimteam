<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TeamProfile classes.
 *
 * $Id: sdif.class.php 957 2012-07-07 04:39:17Z mpwalsh8 $
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage TeamProfile
 * @version $Revision: 957 $
 * @lastmodified $Date: 2012-07-07 00:39:17 -0400 (Sat, 07 Jul 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('db.class.php') ;
require_once('swimteam.include.php') ;
require_once('sdif.include.php') ;
require_once('users.class.php') ;
require_once('team.class.php') ;
require_once('seasons.class.php') ;
require_once('roster.class.php') ;
require_once('swimclubs.class.php') ;

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
     * Set SDIF record count
     *
     * @return int - count of SDIF records
     */
    function setSDIFCount($count)
    {
        $this->__sdifCount  = $count ;
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
     * Write the SDIF data to a file which can be sent to the browser
     *
     */
    function generateSDIFFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setSDIFFile(tempnam('', 'SD3')) ;

        //  Write the SDIF data to the file

        $f = fopen($this->getSDIFFile(), 'w') ;

        foreach ($this->__sdifData as $sdif)
            fwrite($f, $sdif . WPST_SDIF_RECORD_TERMINATOR) ;

        fclose($f) ;
    }
}

/**
 * Class definition of the SDIF LSC Registration Pyramid export
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
    function SDIFLSCRegistrationPyramid()
    {
        parent::loadSDIFProfile() ;
    }

    /**
     * GenerateSDIF - generate the SDIF content for the team roster.
     *
     * @return string - SDIF content
     */
    function generateSDIF($swimmerIds = array())
    {
        global $current_user ;

        //  Need some information from the current user

        $user = new SwimTeamUserProfile() ;
        $user->loadUserProfileByUserId($current_user->ID) ;

        // Shortcut to access property
        $sdif = &$this->__sdifData ;

        $sdif = array() ;

        //  Need to keep track of unique swimmers and various
        //  SDIF record counts in order to terminate SDIF file.

        $unique_swimmers = array() ;
        $sdif_counters = array('b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0) ;

        //  Add debug stuff?  The debug stuff invalidates the SDIF
        //  but is useful for ensuring all of the information is in
        //  the proper column.
 
        if ($this->getSDIFDebugFlag())
        {
            $sdif[] = WPST_SDIF_COLUMN_DEBUG1 ;
            $sdif[] = WPST_SDIF_COLUMN_DEBUG2 ;
        }

        /**
         * Build the A0 record
         */
        $a0 = new SDIFA0Record() ;
        $a0->setOrgCode($this->getOrgCode()) ;
        $a0->setSDIFVersionNumber(WPST_SDIF_VERSION) ;
        $a0->setFileCode(WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $a0->setSoftwareName(WPST_SDIF_SOFTWARE_NAME) ;
        $a0->setSoftwareVersion(WPST_SDIF_SOFTWARE_VERSION) ;
        $a0->setContactName($user->GetFullName()) ;
        $a0->setContactPhone($user->GetPrimaryPhone()) ;
        $a0->setFileCreationOrUpdate(date('mdY')) ;
        $a0->setSubmittedByLSC($this->getLSCCOde()) ;
        
        $sdif[] = $a0->GenerateRecord() ;

        //  Need Team Profile information from database
 
        $swimteam = new SwimTeamProfile() ;
        $swimteam->loadTeamProfile() ;

        /**
         * Build the C1 record
         */
        $c1 = new SDIFC1Record() ;
        $c1->setOrgCode($this->getOrgCode()) ;
        $c1->setTeamCode($this->getTeamCode()) ;
        $c1->setTeamName($swimteam->getClubOrPoolName()) ;
        $c1->setTeamNameAbrv($swimteam->getTeamName()) ;
        $c1->setTeamAddress1($swimteam->getStreet1()) ;
        $c1->setTeamAddress2($swimteam->getStreet2()) ;
        $c1->setTeamCity($swimteam->getCity()) ;
        $c1->setTeamState($swimteam->getStateOrProvince()) ;
        $c1->setTeamPostalCode($swimteam->getPostalCode()) ;
        $c1->setTeamCountryCode($this->getCountryCode()) ;
        $c1->setRegionCode($this->getRegionCode()) ;

        $sdif[] = $c1->GenerateRecord() ;
        $sdif_counters['c']++ ;

        //  Need a bunch of data!

        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;

        //  Load information from the database
        //  to get the list of potential swimmers

        $roster->setSeasonId($season->getActiveSeasonId()) ;

        //  If no swimmers are specified, pull the whole roster
        //  and clean up the array so it as expected.

        if (empty($swimmerIds))
        {
            $swimmerIds = array() ;
            $allSwimmerIds = $roster->getSwimmerIds() ;

            foreach ($allSwimmerIds as $swimmerId)
                $swimmerIds[] = $swimmerId['swimmerid'] ;
        }
        else if (!is_array($swimmerIds))
        {
            $swimmerIds = array($swimmerIds) ;
        }

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        //  If no events are specified, use all events in the swim meet

        $d1 = new SDIFD1Record() ;

        $d1->setOrgCode($this->getOrgCode()) ;
        $d1->setAttachCode(WPST_SDIF_ATTACHED_CODE_ATTACHED_VALUE) ;
        $d1->setMemberCode(WPST_SDIF_MEMBERSHIP_CODE_CHANGE_VALUE) ;

        $d2 = new SDIFD2Record() ;
        $d2->setOrgCode($this->getOrgCode()) ;
        $d2->setTeamCode($this->getTeamCode()) ;
        $d2->setRegionCode($this->getRegionCode()) ;
        $d2->setSwimmerCountryCode($this->getCountryCode()) ;
        $d2->setAnswerCode(WPST_SDIF_ANSWER_CODE_NO_VALUE) ;
        $d2->setSeasonCode(WPST_SDIF_SEASON_CODE_SEASON_1_VALUE) ;

        //  May want to add a citizenship field for each swimmer but
        //  for now, we'll use the country code for the swim team itself
        $d1->setCitizenCode($this->getCountryCode()) ;

        foreach ($swimmerIds as $key => &$swimmerId)
        {
            $roster->setSwimmerId($swimmerId) ;
            $roster->loadRosterBySeasonIdAndSwimmerId() ;
            $swimmer->loadSwimmerById($swimmerId) ;
            $contact->loadUserProfileByUserId($swimmer->getContact1Id()) ;
                    
            //  Initialize D1 record fields which are swimmer based
            $d1->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
            $d1->setBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), true) ;

            //  How should the Swimmer Id appear in the SDIF file?
            if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID)
                $d1->setUSS($swimmer->getId()) ;
            if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                $d1->setUSS($roster->getSwimmerLabel()) ;
            else
                $d1->setUSS($swimmer->getUSSNumber()) ;

            $d1->setPhoneNumber($contact->getPrimaryPhone()) ;
            $d1->setSecondaryPhoneNumber($contact->getSecondaryPhone()) ;
    
            if ($this->getUseAgeGroupAge() == WPST_NO)
                $d1->setAgeOrClass($swimmer->getAge()) ;
            else
                $d1->setAgeOrClass($swimmer->getAgeGroupAge()) ;
            $d1->setGender($swimmer->getGender()) ;
    
            if ($this->getSDIFDebugFlag())
            {
                $sdif[] = WPST_SDIF_COLUMN_DEBUG1 ;
                $sdif[] = WPST_SDIF_COLUMN_DEBUG2 ;
            }
    
            $sdif[] = $d1->GenerateRecord() ;
            $sdif_counters['d']++ ;

            $d2->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
            $d2->setSwimmerAddress1($contact->getFullStreetAddress()) ;
            $d2->setSwimmerCity($contact->getCity()) ;
            $d2->setSwimmerState($contact->getStateOrProvince()) ;
            $d2->setSwimmerPostalCode($contact->getPostalCode()) ;
            $sdif[] = $d2->GenerateRecord() ;
            $sdif_counters['d']++ ;
    
            //  Update the various counters
    
            //  Track uninque swimmers
            if (!in_array($swimmer->getId(), $unique_swimmers))
                $unique_swimmers[] = $swimmer->getId() ;
        }

        //  Construct the Z0 file termination record
 
        $z0 = new SDIFZ0Record() ;
        $z0->setOrgCode($this->getOrgCode()) ;
        $z0->setFileCode(WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $z0->setNotes(sprintf('Created:  %s', date('Y-m-d @ H:i'))) ;
        $z0->setBRecordCount($sdif_counters['b']) ;
        $z0->setMeetCount(1) ;
        $z0->setCRecordCount($sdif_counters['c']) ;
        $z0->setTeamCount(1) ;
        $z0->setDRecordCount($sdif_counters['d']) ;
        $z0->setSwimmerCount(count($unique_swimmers)) ;
        $z0->setERecordCount($sdif_counters['e']) ;
        $z0->setFRecordCount($sdif_counters['f']) ;
        $z0->setGRecordCount($sdif_counters['g']) ;
        $z0->setBatchNumber(1) ;
        $z0->setNewMemberCount(0) ;
        $z0->setRenewMemberCount(0) ;
        $z0->setChangeMemberCount(0) ;
        $z0->setDeleteMemberCount(0) ;

        $sdif[] = $z0->GenerateRecord() ;

        //  Record the count of entries created

        //$this->setSDIFCount($sdif_counters['d'] + $sdif_counters['e'] + $sdif_counters['f']) ;
        $this->setSDIFCount(count($unique_swimmers)) ;
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
     * Swim Meet Id
     */
    var $_swimmeetid ;

    /**
     * Zero Time Mode property
     */
    var $_zerotimemode ;

    /**
     * Set the Swim Meet Id
     *
     * @param int $id swim meet id
     */
    function setSwimMeetId($id)
    {
        $this->_swimmeetid = $id ;
    }

    /**
     * Get the Swim Meet Id
     *
     * @return int swim meet id
     */
    function getSwimMeetId()
    {
        return $this->_swimmeetid ;
    }

    /**
     * Set the Zero Time Mode to specify
     * how zero times (0.00) should be handled
     *
     * @param string $mode mode setting
     */
    function setZeroTimeMode($mode = WPST_SDIF_USE_BLANKS)
    {
        $this->_zerotimemode = $mode ;
    }

    /**
     * Get the Zero Time Mode to specify
     * how zero times (0.00) should be handled
     *
     * @return string $mode mode setting
     */
    function getZeroTimeMode()
    {
        return $this->_zerotimemode ;
    }

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
    function generateSDIF($eventIds = array())
    {
        global $current_user ;

        //  Need some information from the current user

        $user = new SwimTeamUserProfile() ;
        $user->loadUserProfileByUserId($current_user->ID) ;

        // Shortcut to access property
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

        //  Need to keep track of unique swimmers and various
        //  SDIF record counts in order to terminate SDIF file.

        $unique_swimmers = array() ;
        $sdif_counters = array('b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0) ;

        //  We'll need a bunch of data from the database ...

        $swimmeet = new SwimMeet() ;
        $meta = new SwimMeetMeta() ;
        $event = new SwimMeetEvent() ;
        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;
        $swimclub = new SwimClubProfile() ;
        $swimteam = new SwimTeamProfile() ;

        //  Need Team Profile information from database
        //  along with swim meet and opponent club details.
 
        $swimteam->loadTeamProfile() ;
        $swimmeet->loadSwimMeetByMeetId($this->getSwimMeetId()) ;
        $swimclub->loadSwimClubBySwimClubId($swimmeet->getOpponentSwimClubId()) ;

        /**
         * Build the A0 record
         */
        $a0 = new SDIFA0Record() ;
        $a0->setOrgCode($this->getOrgCode()) ;
        $a0->setSDIFVersionNumber(WPST_SDIF_VERSION) ;
        $a0->setFileCode(WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $a0->setSoftwareName(WPST_SDIF_SOFTWARE_NAME) ;
        $a0->setSoftwareVersion(WPST_SDIF_SOFTWARE_VERSION) ;
        $a0->setContactName($user->GetFullName()) ;
        $a0->setContactPhone($user->GetPrimaryPhone()) ;
        $a0->setFileCreationOrUpdate(date('mdY')) ;
        $a0->setSubmittedByLSC($this->getLSCCOde()) ;
        
        $sdif[] = $a0->GenerateRecord() ;

        /**
         * Build the B1 record
         */
        $b1 = new SDIFB1Record() ;
        $b1->setOrgCode($this->getOrgCode()) ;
        $b1->setTeamCode($this->getTeamCode()) ;
        $b1->setMeetName(sprintf('%s vs %s', $swimteam->getTeamName(), $swimclub->getTeamName())) ;

        //  Meet address depends on meet location, 'Home' or 'Away'

        if ($swimmeet->getLocation() == WPST_HOME)
            $st = &$swimteam ;
        else if ($swimmeet->getLocation() == WPST_AWAY)
            $st = &$swimclub ;
        else
            $st = &$swimteam ;

        $b1->setMeetAddress1($st->getStreet1()) ;
        $b1->setMeetAddress2($st->getStreet2()) ;
        $b1->setMeetCity($st->getCity()) ;
        $b1->setMeetState($st->getStateOrProvince()) ;
        $b1->setMeetPostalCode($st->getPostalCode()) ;
        $b1->setMeetCountryCode($this->getCountryCode()) ;

        switch ($swimmeet->getMeetType())
        {
            case WPST_DUAL_MEET:
                $b1->setMeetCode(WPST_SDIF_MEET_TYPE_DUAL_VALUE) ;
                break ;

            case WPST_INVITATIONAL:
                $b1->setMeetCode(WPST_SDIF_MEET_TYPE_INVITATIONAL_VALUE) ;
                break ;

            case WPST_TIME_TRIAL:
                $b1->setMeetCode(WPST_SDIF_MEET_TYPE_TIME_TRIALS_VALUE) ;
                break ;

            default:
                $b1->setMeetCode(WPST_SDIF_MEET_TYPE_OPEN_VALUE) ;
                break ;
        }

        $b1->setMeetStart($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $b1->setMeetEnd($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $b1->setPoolAltitude(0) ;
        $b1->setCourseCode(SDIFCodeTableMappings::GetCourseCode(
            $st->getPoolMeasurementUnits(), $st->getPoolLength())) ;

        $sdif[] = $b1->GenerateRecord() ;
        $sdif_counters['b']++ ;

        /**
         * Build the B2 record
         */
        $b2 = new SDIFB2Record() ;
        $b2->setOrgCode($this->getOrgCode()) ;
        $b2->setTeamCode($this->getTeamCode()) ;
        $b2->setMeetName($user->getFullName()) ;
        $b2->setMeetAddress1($st->getStreet1()) ;
        $b2->setMeetAddress2($st->getStreet2()) ;
        $b2->setMeetCity($st->getCity()) ;
        $b2->setMeetState($st->getStateOrProvince()) ;
        $b2->setMeetPostalCode($st->getPostalCode()) ;
        $b2->setMeetCountryCode($this->getCountryCode()) ;
        $b2->setMeetHostPhone($user->getPrimaryPhone()) ;

        $sdif[] = $b2->GenerateRecord() ;
        $sdif_counters['b']++ ;

        /**
         * Build the C1 record
         */
        $c1 = new SDIFC1Record() ;
        $c1->setOrgCode($this->getOrgCode()) ;
        $c1->setTeamCode($this->getTeamCode()) ;
        $c1->setTeamName($swimteam->getClubOrPoolName()) ;
        $c1->setTeamNameAbrv($swimteam->getTeamName()) ;
        $c1->setTeamAddress1($swimteam->getStreet1()) ;
        $c1->setTeamAddress2($swimteam->getStreet2()) ;
        $c1->setTeamCity($swimteam->getCity()) ;
        $c1->setTeamState($swimteam->getStateOrProvince()) ;
        $c1->setTeamPostalCode($swimteam->getPostalCode()) ;
        $c1->setTeamCountryCode($this->getCountryCode()) ;
        $c1->setRegionCode($this->getRegionCode()) ;

        $sdif[] = $c1->GenerateRecord() ;
        $sdif_counters['c']++ ;

        /**
         * Build the C2 record.  We'll actually build this twice, initially
         * with placeholder values in the count fields and then it will be
         * rebuilt later once all of the counts are known.
         */
        $c2 = new SDIFC2Record() ;
        $c2->setOrgCode($this->getOrgCode()) ;
        $c2->setTeamCode($this->getTeamCode()) ;
        if ($swimteam->getCoachUserId() !== WPST_NULL_ID)
        {
            $coach = new SwimTeamUserProfile() ;
            $coach->loadUserProfileByUserId($swimteam->getCoachUserId()) ;
            $c2->setCoachName($coach->getFullName()) ;
            $c2->setCoachPhone($coach->getPrimaryPhone()) ;
        }
        else
        {
            $c2->setCoachName(WPST_NULL_STRING) ;
            $c2->setCoachPhone(WPST_NULL_STRING) ;
        }

        $c2->setNumberOfIndividualEntries(0) ;
        $c2->setNumberOfAthletes(0) ;
        $c2->setNumberOfRelayEntries(0) ;
        $c2->setNumberOfRelaySwimmers(0) ;
        $c2->setNumberOfSplitRecords(0) ;
        $c2->setTeamNameAbrv($swimteam->getTeamName()) ;
        $c2->setTeamCode5(WPST_NULL_STRING) ;

        $sdif[] = $c2->GenerateRecord() ;
        $sdif_counters['c']++ ;

        //  Load information from the database
        //  to get the list of potential swimmers

        $roster->setSeasonId($swimmeet->getSeasonId()) ;
        $swimmerIds = $roster->getSwimmerIds() ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        //  If no events are specified, use all events in the swim meet

        if (empty($eventIds))
        {
            $meetEventIds = $event->getAllEventIdsByMeetId($this->getSwimMeetId(), 'eventid') ;
            //  Clean up the array of Meet Event Ids

            foreach ($meetEventIds as $key => $value)
                $eventIds[] = $value['eventid'] ;

            unset($meetEventIds) ;
        }

        //  Initialize the D0 and D3 records, some of it is static for all entries
        $d0 = new SDIFD0Record() ;
        $d3 = new SDIFD3Record() ;

        $d0->setOrgCode($this->getOrgCode()) ;
        $d0->setAttachCode(WPST_SDIF_ATTACHED_CODE_ATTACHED_VALUE) ;

        //  May want to add a citizenship field for each swimmer but
        //  for now, we'll use the country code for the swim team itself
        $d0->setCitizenCode($this->getCountryCode()) ;


        //  The rest of the fields are left empty
        $d0->setSwimDate($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $d0->setSeedTime(0.0) ;
        $d0->setSeedCourseCode(WPST_NULL_STRING) ;
        $d0->setPrelimTime(0.0) ;
        $d0->setPrelimCourseCode(WPST_NULL_STRING) ;
        $d0->setSwimOffTime(0.0) ;
        $d0->setSwimOffCourseCode(WPST_NULL_STRING) ;
        $d0->setFinalsTime(0.0) ;
        $d0->setFinalsCourseCode(WPST_NULL_STRING) ;
        $d0->setPrelimHeatNumber(0) ;
        $d0->setPrelimLaneNumber(0) ;
        $d0->setFinalsHeatNumber(0) ;
        $d0->setFinalsLaneNumber(0) ;
        $d0->setPrelimPlaceRanking(WPST_NULL_STRING) ;
        $d0->setFinalsPlaceRanking(WPST_NULL_STRING) ;
        $d0->setFinalsPoints(WPST_NULL_STRING) ;
        $d0->setEventTimeClassCode(WPST_NULL_STRING) ;
        $d0->setSwimmerFlightStatus(WPST_NULL_STRING) ;

        //  Initialize the E0 and F0 records, some of it is static for all entries
        $e0 = new SDIFE0Record() ;
        $f0 = new SDIFF0Record() ;

        $e0->setOrgCode($this->getOrgCode()) ;
        $e0->setTeamCode($this->getTeamCode()) ;
        $e0->setRelayTeamName('A') ;

        $f0->setOrgCode($this->getOrgCode()) ;
        $f0->setTeamCode($this->getTeamCode()) ;
        $f0->setRelayTeamName('A') ;
        $f0->setPrelimLegOrderCode(WPST_SDIF_RELAY_CODE_NOT_SWIMMING_VALUE) ;
        $f0->setSwimOffLegOrderCode(WPST_SDIF_RELAY_CODE_NOT_SWIMMING_VALUE) ;
        $f0->setFinalsLegOrderCode(WPST_SDIF_RELAY_CODE_ALTERNAME_VALUE) ;

        //  The rest of the fields are left empty
        $e0->setSwimDate($swimmeet->getMeetDateAsMMDDYYYY(), false) ;
        $e0->setSeedTime(0.0) ;
        $e0->setSeedCourseCode(WPST_NULL_STRING) ;
        $e0->setPrelimTime(0.0) ;
        $e0->setPrelimCourseCode(WPST_NULL_STRING) ;
        $e0->setSwimOffTime(0.0) ;
        $e0->setSwimOffCourseCode(WPST_NULL_STRING) ;
        $e0->setFinalsTime(0.0) ;
        $e0->setFinalsCourseCode(WPST_NULL_STRING) ;
        $e0->setPrelimHeatNumber(0) ;
        $e0->setPrelimLaneNumber(0) ;
        $e0->setFinalsHeatNumber(0) ;
        $e0->setFinalsLaneNumber(0) ;
        $e0->setPrelimPlaceRanking(WPST_NULL_STRING) ;
        $e0->setFinalsPlaceRanking(WPST_NULL_STRING) ;
        $e0->setFinalsPoints(WPST_NULL_STRING) ;
        $e0->setEventTimeClassCode(WPST_NULL_STRING) ;

        //  Loop through events

        foreach ($eventIds as $eventId)
        {
            //  Debug code, take this out!!!
            //if ($eventId['eventid'] != 1411) continue ;
//            if ((($eventId['eventid'] < 1462) || ($eventId['eventid'] > 1465)) &&
//                (($eventId['eventid'] < 1474) || ($eventId['eventid'] > 1476))) continue ;

            //$event->loadSwimMeetEventByEventId($eventId['eventid']) ;
            $event->loadSwimMeetEventByEventId($eventId) ;

            // Get all swimmers eligible for the age group

            $swimmerIds = $roster->getAllSwimmerIdsByAgeGroupId($event->getAgeGroupId()) ;

            //  Need to tidy up the list of swimmers based on meet participation
            //  Is the meet set up as opt-in or opt-out?  Adjust the list accordingly.

            foreach ($swimmerIds as $key => &$swimmerId)
            {
                //  Has swimmer entered or scratched this event?
                //  Which mode is the data in Stroke or Event?

                if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
                {
                    $strokecodes = $meta->getStrokeCodesBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                        $swimmeet->getMeetId(), $swimmeet->getParticipation()) ;

                    //  Clean up array so it is easier to work with

                    $optinoptout = array() ;
                    if (!empty($strokecodes))
                    {
                        foreach ($strokecodes as $strokecode)
                            $optinoptout[] = $strokecode['strokecode'] ;
                    }

                    //  Determine if swimmer is swimming in this event

                    $swimming = ($swimmeet->getParticipation() == WPST_OPT_IN) ?
                        in_array($event->getStroke(), $optinoptout) :
                        !in_array($event->getStroke(), $optinoptout) ;
                }
                else
                {
                    $eventids = $meta->getEventIdsBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                        $swimmeet->getMeetId(), $swimmeet->getParticipation()) ;

                    //  Clean up array so it is easier to work with

                    $optinoptout = array() ;
                    if (!empty($eventids))
                    {
                        foreach ($eventids as $eventid)
                            $optinoptout[] = $eventid['eventid'] ;
                    }

                    //  Determine if swimmer is swimming in this event

                    $swimming = ($swimmeet->getParticipation() == WPST_OPT_IN) ?
                        in_array($event->getEventId(), $optinoptout) :
                        !in_array($event->getEventId(), $optinoptout) ;
                }

                //  If swimmer isn't swimming, drop them from the list!

                if (!$swimming) unset($swimmerIds[$key]) ;
            }

            //  Now we know the swimmers and the events, time to generate records!

            //  Individual or Relay event?

            if (($event->getStroke() == WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE) ||
                ($event->getStroke() == WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE))
            {
                //  Intialize the E0 record fields that are event based
                $e0->setEventGender($event->getGender()) ;
                $e0->setEventDistance($event->getDistance()) ;
                $e0->setCourseCode($event->getCourse()) ;
                $e0->setStrokeCode($event->getStroke()) ;
                $e0->setEventNumber($event->getEventNumber()) ;
                $e0->setEventAgeCode($event->getMinAge(), $event->getMaxAge()) ;

                $f0count = 0 ;
                $totalage = 0 ;
                $f0sdif = array() ;

                foreach ($swimmerIds as $key => &$swimmerId)
                {
                    $roster->setSwimmerId($swimmerId['swimmerid']) ;
                    $roster->loadRosterBySeasonIdAndSwimmerId() ;
                    $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
                    
                    //  Initialize F0 record fields which are swimmer based
                    $f0->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
                    $f0->setPreferredFirstName($swimmer->getFirstName($this->getUseNickName())) ;
                    $f0->setBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), false) ;

                    //  How should the Swimmer Id appear in the SDIF file?
                    if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID)
                        $f0->setUSS($swimmer->getId()) ;
                    if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                        $f0->setUSS($roster->getSwimmerLabel()) ;
                    else
                        $f0->setUSS($swimmer->getUSSNumber()) ;

                    $f0->setUSSNew() ;
    
                    if ($this->getUseAgeGroupAge() == WPST_NO)
                    {
                        $totalage += $swimmer->getAge() ;
                        $f0->setAgeOrClass($swimmer->getAge()) ;
                    }
                    else
                    {
                        $totalage += $swimmer->getAgeGroupAge() ;
                        $f0->setAgeOrClass($swimmer->getAgeGroupAge()) ;
                    }

                    $f0->setGender($swimmer->getGender()) ;
    
                    if ($this->getSDIFDebugFlag())
                    {
                        $sdif[] = WPST_SDIF_COLUMN_DEBUG1 ;
                        $sdif[] = WPST_SDIF_COLUMN_DEBUG2 ;
                    }
    
                    $f0sdif[] = $f0->GenerateRecord($this->getZeroTimeMode()) ;
    
                    //  Update the various counters
                    $f0count++ ;
                    $sdif_counters['f']++ ;
    
                    //  Track uninque swimmers
                    if (!in_array($swimmer->getId(), $unique_swimmers))
                        $unique_swimmers[] = $swimmer->getId() ;
                }

                //  All of the F0 records have been generated,
                //  need to update the E0 records then add them 
                //  all to the pile of SDIF records.

                if ($f0count > 0)
                {
                    $e0->setTotalAgeOfAthletes($totalage) ;
                    $e0->setNumberOfF0Records($f0count) ;

                    $sdif[] = $e0->GenerateRecord($this->getZeroTimeMode()) ;

                    //  Append all of the F0 records

                    foreach ($f0sdif as $key => $value)
                        $sdif[] = $value ;
                }
            }
            else
            {
                //  Intiialize the D0 record fields that are event based
                $d0->setEventGender($event->getGender()) ;
                $d0->setEventDistance($event->getDistance()) ;
                $d0->setCourseCode($event->getCourse()) ;
                $d0->setStrokeCode($event->getStroke()) ;
                $d0->setEventNumber($event->getEventNumber()) ;
                $d0->setEventAgeCode($event->getMinAge(), $event->getMaxAge()) ;

                foreach ($swimmerIds as $key => &$swimmerId)
                {
                    $roster->setSwimmerId($swimmerId['swimmerid']) ;
                    $roster->loadRosterBySeasonIdAndSwimmerId() ;
                    $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
                    
                    //  Initialize D0 record fields which are swimmer based
                    $d0->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
                    $d0->setBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), true) ;
                    $d3->setPreferredFirstName($swimmer->getFirstName($this->getUseNickName())) ;

                    //  How should the Swimmer Id appear in the SDIF file?
                    if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID)
                    {
                        $d0->setUSS($swimmer->getId()) ;
                        $d3->setUSS($swimmer->getId()) ;
                    }
                    if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                    {
                        $d0->setUSS($roster->getSwimmerLabel()) ;
                        $d3->setUSS($roster->getSwimmerLabel()) ;
                    }
                    else
                    {
                        $d0->setUSS($swimmer->getUSSNumber()) ;
                        $d3->setUSS($swimmer->getUSSNumber()) ;
                    }
    
                    if ($this->getUseAgeGroupAge() == WPST_NO)
                        $d0->setAgeOrClass($swimmer->getAge()) ;
                    else
                        $d0->setAgeOrClass($swimmer->getAgeGroupAge()) ;
                    $d0->setGender($swimmer->getGender()) ;
    
                    if ($this->getSDIFDebugFlag())
                    {
                        $sdif[] = WPST_SDIF_COLUMN_DEBUG1 ;
                        $sdif[] = WPST_SDIF_COLUMN_DEBUG2 ;
                    }
    
                    //  Generate SDIF and update the various counters

                    $sdif[] = $d0->GenerateRecord($this->getZeroTimeMode()) ;
                    $sdif_counters['d']++ ;

                    $sdif[] = $d3->GenerateRecord() ;
                    $sdif_counters['d']++ ;
    
                    //  Track uninque swimmers
                    if (!in_array($swimmer->getId(), $unique_swimmers))
                        $unique_swimmers[] = $swimmer->getId() ;
                }
            }
        }


        //  Construct the Z0 file termination record
 
        $z0 = new SDIFZ0Record() ;
        $z0->setOrgCode($this->getOrgCode()) ;
        $z0->setFileCode(WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $z0->setNotes(sprintf('Created:  %s', date('Y-m-d @ H:i'))) ;
        $z0->setBRecordCount($sdif_counters['b']) ;
        $z0->setMeetCount(1) ;
        $z0->setCRecordCount($sdif_counters['c']) ;
        $z0->setTeamCount(1) ;
        $z0->setDRecordCount($sdif_counters['d']) ;
        $z0->setSwimmerCount(count($unique_swimmers)) ;
        $z0->setERecordCount($sdif_counters['e']) ;
        $z0->setFRecordCount($sdif_counters['f']) ;
        $z0->setGRecordCount($sdif_counters['g']) ;
        $z0->setBatchNumber(1) ;
        $z0->setNewMemberCount(0) ;
        $z0->setRenewMemberCount(0) ;
        $z0->setChangeMemberCount(0) ;
        $z0->setDeleteMemberCount(0) ;

        $sdif[] = $z0->GenerateRecord() ;

        //  Need to go back and "update" the C2 record now that the 
        //  number of entries and types of records are now known.

        $c2->setNumberOfIndividualEntries($sdif_counters['d']) ;
        $c2->setNumberOfAthletes(count($unique_swimmers)) ;
        $c2->setNumberOfRelayEntries($sdif_counters['e']) ;
        $c2->setNumberOfRelaySwimmers($sdif_counters['f']) ;
        $c2->setNumberOfSplitRecords($sdif_counters['g']) ;

        //  Scan through the SDIF records and update the C2 record

        foreach ($sdif as $key => $value)
            if (substr($value, 0, 2) == 'C2')
                $sdif[$key] = $c2->GenerateRecord() ;

        //  Record the count of entries created

        $this->setSDIFCount($sdif_counters['d'] + $sdif_counters['e'] + $sdif_counters['f']) ;
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
     * File Code
     */
    var $_file_code ;

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
    var $_future_use_1 = WPST_SDIF_FUTURE_USE ;

    /**
     * Future Use #2
     */
    var $_future_use_2 = WPST_SDIF_FUTURE_USE ;

    /**
     * Future Use #3
     */
    var $_future_use_3 = WPST_SDIF_FUTURE_USE ;

    /**
     * Timestamp property
     */
    var $_timestamp ;

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
        //  Need to do some "parsing" to make sure the value
        //  is stored as the "SDIF" value and not what WPST uses.
 
        if ($txt == WPST_GENDER_MALE)
            $this->_gender = WPST_SDIF_SWIMMER_SEX_CODE_MALE_VALUE ;
        else if ($txt == WPST_GENDER_FEMALE)
            $this->_gender = WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_VALUE ;
        else
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
     * Set SDIF VersionNumber
     *
     * @param string meet name
     */
    function setSDIFVersionNumber($txt)
    {
        $this->_sdif_version_number = $txt ;
    }

    /**
     * Get SDIF VersionNumber
     *
     * @return string meet name
     */
    function getSDIFVersionNumber()
    {
        return $this->_sdif_version_number ;
    }

    /**
     * Set Software Name
     *
     * @param string software name
     */
    function setSoftwareName($txt)
    {
        $this->_software_name = $txt ;
    }

    /**
     * Get Software Name
     *
     * @return string software name
     */
    function getSoftwareName()
    {
        return $this->_software_name ;
    }

    /**
     * Set Software Version
     *
     * @param string software version
     */
    function setSoftwareVersion($txt)
    {
        $this->_software_version = $txt ;
    }

    /**
     * Get Software Version
     *
     * @return string software version
     */
    function getSoftwareVersion()
    {
        return $this->_software_version ;
    }

    /**
     * Set Contact Name
     *
     * @param string $txt contact name
     */
    function setContactName($txt)
    {
        $this->_contact_name = $txt ;
    }

    /**
     * Get Contact Name
     *
     * @return string contact name
     */
    function getContactName()
    {
        return $this->_contact_name ;
    }

    /**
     * Set Contact Phone
     *
     * @param string contact phone
     */
    function setContactPhone($txt)
    {
        $this->_contact_phone = $txt ;
    }

    /**
     * Get Contact Phone
     *
     * @return string contact phone
     */
    function getContactPhone()
    {
        return $this->_contact_phone ;
    }

    /**
     * Set File Creation or Update
     *
     * @param string file creation or update
     */
    function setFileCreationOrUpdate($txt)
    {
        $this->_file_creation_or_update = $txt ;
    }

    /**
     * Get File Creation or Update
     *
     * @return string file creation or update
     */
    function getFileCreationOrUpdate()
    {
        return $this->_file_creation_or_update ;
    }

    /**
     * Set Submitted by LSC
     *
     * @param string submitted by lsc
     */
    function setSubmittedByLSC($txt)
    {
        $this->_submitted_by_lsc = $txt ;
    }

    /**
     * Get Submitted by LSC
     *
     * @return string submitted by lsc
     */
    function getSubmittedByLSC()
    {
        return $this->_submitted_by_lsc ;
    }

    /**
     * Parse an A0 SDIF record
     *
     * @return void
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

        //$success = sscanf($this->_sdif_record, WPST_SDIF_A0_RECORD,
        //    $this->_org_code,
        //    $this->_sdif_version_number,
        //    $this->_file_code,
        //    $this->_future_use_1,
        //    $this->_software_name,
        //    $this->_software_version,
        //    $this->_contact_name,
        //    $this->_contact_phone,
        //    $this->_meet_state,
        //    $this->_file_creation_or_update,
        //    $this->_future_use_2,
        //    $this->_submitted_by_lsc,
        //    $this->_future_use_3) ;

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setSDIFVersionNumber(trim(substr($this->_sdif_record, 2, 8))) ;
        $this->setFileCode(trim(substr($this->_sdif_record, 11, 2))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 13, 30))) ;
        $this->setSoftwareName(trim(substr($this->_sdif_record, 43, 20))) ;
        $this->setSoftwareVersion(trim(substr($this->_sdif_record, 63, 10))) ;
        $this->setContactName(trim(substr($this->_sdif_record, 73, 20))) ;
        $this->setContactPhone(trim(substr($this->_sdif_record, 93, 12))) ;
        $this->setFileCreationOrUpdate(trim(substr($this->_sdif_record, 105, 8))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 113, 42))) ;
        $this->setSubmittedByLSC(trim(substr($this->_sdif_record, 155, 2))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 158, 3))) ;
    }

    /**
     * Generate an A0 SDIF record
     *
     * @return string - A0 SDIF record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_A0_RECORD,
            $this->getOrgCode(),
            $this->getSDIFVersionNumber(),
            $this->getFileCode(),
            $this->getFutureUse1(),
            $this->getSoftwareName(),
            $this->getSoftwareVersion(),
            $this->getContactName(),
            $this->getContactPhone(),
            $this->getFileCreationOrUpdate(),
            $this->getFutureUse2(),
            $this->getSubmittedByLSC(),
            $this->getFutureUse3()
        ) ;
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
     * Meet Phone
     */
    var $_meet_host_phone ;

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
     * Set Meet Host Phone
     *
     * @param string meet host phone
     */
    function setMeetHostPhone($txt)
    {
        $this->_meet_host_phone = $txt ;
    }

    /**
     * Get Meet Host Phone
     *
     * @return string meet host phone
     */
    function getMeetHostPhone()
    {
        return $this->_meet_host_phone ;
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
        /**
         * Build the B1 record
         */

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
        $this->setMeetStart(trim(substr($this->_sdif_record, 121, 8)), false) ;
        $this->setMeetEnd(trim(substr($this->_sdif_record, 129, 8)), false) ;
        $this->setPoolAltitude(trim(substr($this->_sdif_record, 137, 4))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 141, 8))) ;
        $this->setCourseCode(trim(substr($this->_sdif_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 150, 10))) ;
    }

    /**
     * Generate Record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_B1_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getMeetName(),
            $this->getMeetAddress1(),
            $this->getMeetAddress2(),
            $this->getMeetCity(),
            $this->getMeetState(),
            $this->getMeetPostalCode(),
            $this->getMeetCountryCode(),
            $this->getMeetCode(),
            $this->getMeetStart(false),
            $this->getMeetEnd(false),
            $this->getPoolAltitude(),
            $this->getFutureUse2(),
            $this->getCourseCode(),
            $this->getFutureUse3()
        ) ;
    }
}

/**
 * SDIF B2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFBxRecord
 */
class SDIFB2Record extends SDIFBxRecord
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
        $this->setMeetHostPhone(trim(substr($this->_sdif_record, 120, 1))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 141, 8))) ;
    }

    /**
     * Generate Record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_B2_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getMeetName(),
            $this->getMeetAddress1(),
            $this->getMeetAddress2(),
            $this->getMeetCity(),
            $this->getMeetState(),
            $this->getMeetPostalCode(),
            $this->getMeetCountryCode(),
            $this->getMeetHostPhone(),
            $this->getFutureUse2()
        ) ;
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
     * Team Name Abbreviation
     */
    var $_team_name_abrv ;

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
     * Team Name
     */
    var $_team_name ;

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

    /**
     * Generate Record
     *
     * @return sting - C1 SDIF record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_C1_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getTeamCode(),
            $this->getTeamName(),
            $this->getTeamNameAbrv(),
            $this->getTeamAddress1(),
            $this->getTeamAddress2(),
            $this->getTeamCity(),
            $this->getTeamState(),
            $this->getTeamPostalCode(),
            $this->getTeamCountryCode(),
            $this->getRegionCode(),
            $this->getFutureUse2(),
            $this->getTeamCode5(),
            $this->getFutureUse3()
        ) ;
    }
}

/**
 * SDIF C2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFC2Record extends SDIFCxRecord
{
    /**
     * Coach Name
     */
    var $_coach_name ;

    /**
     * Coach Phone
     */
    var $_coach_phone ;

    /**
     * Number of Individual Entries (D0 records)
     */
    var $_number_of_individual_entries ;

    /**
     * Number of Athletes
     */
    var $_number_of_athletes ;

    /**
     * Number of Relay Entries (E0 records)
     */
    var $_number_of_relay_entries ;

    /**
     * Number of Relay Swimmers (F0 records)
     */
    var $_number_of_relay_swimmers ;

    /**
     * Number of Split Records (G0 records)
     */
    var $_number_of_split_records ;

    /**
     * Set Coach Name
     *
     * @param string coach name
     */
    function setCoachName($txt)
    {
        $this->_coach_name = $txt ;
    }

    /**
     * Get Coach Name
     *
     * @return string coach name
     */
    function getCoachName()
    {
        return $this->_coach_name ;
    }

    /**
     * Set Coach Phone
     *
     * @param string coach phone
     */
    function setCoachPhone($txt)
    {
        $this->_coach_phone = $txt ;
    }

    /**
     * Get Coach Phone
     *
     * @return string coach phone
     */
    function getCoachPhone()
    {
        return $this->_coach_phone ;
    }

    /**
     * Set Number of Individual Entries
     *
     * @param int $cnt number of individual entries
     */
    function setNumberOfIndividualEntries($cnt)
    {
        $this->_number_of_individual_entries = $cnt ;
    }

    /**
     * Get Number of Individual Entries
     *
     * @return int number of individual entries
     */
    function getNumberOfIndividualEntries()
    {
        return $this->_number_of_individual_entries ;
    }

    /**
     * Get Number of Athletes
     *
     * @return int number of athletes
     */
    function getNumberOfAthletes()
    {
        return $this->_number_of_athletes ;
    }

    /**
     * Set Number of Athletes
     *
     * @param int $cnt number of athletes
     */
    function setNumberOfAthletes($cnt)
    {
        $this->_number_of_athletes = $cnt ;
    }

    /**
     * Set Number of Relay Entries
     *
     * @param int $cnt number of relay entries
     */
    function setNumberOfRelayEntries($cnt)
    {
        $this->_number_of_relay_entries = $cnt ;
    }

    /**
     * Get Number of Relay Entries
     *
     * @return int number of relay entries
     */
    function getNumberOfRelayEntries()
    {
        return $this->_number_of_relay_entries ;
    }

    /**
     * Set Number of Relay Swimmers
     *
     * @param int $cnt number of relay swimmers
     */
    function setNumberOfRelaySwimmers($cnt)
    {
        $this->_number_of_relay_swimmers = $cnt ;
    }

    /**
     * Get Number of Relay Swimmers
     *
     * @return int number of relay swimmers
     */
    function getNumberOfRelaySwimmers()
    {
        return $this->_number_of_relay_swimmers ;
    }

    /**
     * Set Number of Split Records
     *
     * @param int $cnt number of split records
     */
    function setNumberOfSplitRecords($cnt)
    {
        $this->_number_of_split_records = $cnt ;
    }

    /**
     * Get Number of Split Records
     *
     * @return int number of split records
     */
    function getNumberOfSplitRecords()
    {
        return $this->_number_of_split_records ;
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
            //print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 11, 6))) ;
        $this->setCoachName(trim(substr($this->_sdif_record, 17, 30))) ;
        $this->setCoachPhone(trim(substr($this->_sdif_record, 47, 12))) ;
        $this->setNumberOfIndividualEntries(trim(substr($this->_sdif_record, 59, 6))) ;
        $this->setNumberOfAthletes(trim(substr($this->_sdif_record, 65, 6))) ;
        $this->setNumberOfRelayEntries(trim(substr($this->_sdif_record, 71, 5))) ;
        $this->setNumberOfRelaySwimmers(trim(substr($this->_sdif_record, 76, 6))) ;
        $this->setNumberOfSplitRecords(trim(substr($this->_sdif_record, 82, 6))) ;
        $this->setTeamNameAbrv(trim(substr($this->_sdif_record, 88, 16))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 104, 45))) ;
        $this->setTeamCode5(trim(substr($this->_sdif_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 150, 10))) ;
    }

    /**
     * Generate Record
     *
     * @return sting - C2 SDIF record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_C2_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getTeamCode(),
            $this->getCoachName(),
            $this->getCoachPhone(),
            $this->getNumberOfIndividualEntries(),
            $this->getNumberOfAthletes(),
            $this->getNumberOfRelayEntries(),
            $this->getNumberOfRelaySwimmers(),
            $this->getNumberOfSplitRecords(),
            $this->getTeamNameAbrv(),
            $this->getFutureUse2(),
            $this->getTeamCode5(),
            $this->getFutureUse3()
        ) ;
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
     * Preferred First Name property
     */
    var $_preferred_first_name ;

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
        //  Need to do some "parsing" to make sure the value
        //  is stored as the "SDIF" value and not what WPST uses.
 
        if ($txt == WPST_GENDER_MALE)
            $this->_event_gender = WPST_SDIF_SWIMMER_SEX_CODE_MALE_VALUE ;
        else if ($txt == WPST_GENDER_FEMALE)
            $this->_event_gender = WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_VALUE ;
        else
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
    function setEventAgeCode($minage, $maxage)
    {
        $txt = sprintf('%02d', $minage) ;

        if ($maxage > 99)
            $txt .= UN ;
        else
            $txt .= sprintf('%02d', $maxage) ;

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
        if (empty($txt))
        {
            $this->_swim_date = WPST_NULL_STRING ;
            $this->_swim_date_db = WPST_NULL_STRING ;
        }
        else if ($db)
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
     * Get Seed Time and Course Code
     *
     * @return string seed time and course code
     */
    function getSeedTimeAndCourseCode($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($zerotimemode == WPST_SDIF_USE_BLANKS_VALUE)
            return WPST_NULL_STRING ;
        else if ($zerotimemode == WPST_SDIF_USE_NT_VALUE)
            return WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_VALUE . $this->getCourseCode() ;
        else
            return $this->_seed_time . $this->getCourseCode() ;
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
     * Get Prelim Time and Course Code
     *
     * @return string prelim time and course code
     */
    function getPrelimTimeAndCourseCode($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($zerotimemode == WPST_SDIF_USE_BLANKS_VALUE)
            return WPST_NULL_STRING ;
        else if ($zerotimemode == WPST_SDIF_USE_NT_VALUE)
            return WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_VALUE . $this->getCourseCode() ;
        else
            return $this->_prelim_time . $this->getCourseCode() ;
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
     * Get Swim Off Time and Course Code
     *
     * @return string swim_off time and course code
     */
    function getSwimOffTimeAndCourseCode($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($zerotimemode == WPST_SDIF_USE_BLANKS_VALUE)
            return WPST_NULL_STRING ;
        else if ($zerotimemode == WPST_SDIF_USE_NT_VALUE)
            return WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_VALUE . $this->getCourseCode() ;
        else
            return $this->_swim_off_time . $this->getCourseCode() ;
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
            $time = explode($txt, ':') ;
            $this->_finals_time_ft = $time[0] * 60 + $time[1] ;

        }
        //  Time in ss.ss?
        else if (preg_match('/[0-9][0-9]\.[0-9][0-9]/', $txt))
        {
            $this->_finals_time_ft = (float)$txt ;
        }
        else
        {
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
     * Get Finals Time and Course Code
     *
     * @return string finals time and course code
     */
    function getFinalsTimeAndCourseCode($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($zerotimemode == WPST_SDIF_USE_BLANKS_VALUE)
            return WPST_NULL_STRING ;
        else if ($zerotimemode == WPST_SDIF_USE_NT_VALUE)
            return WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_VALUE . $this->getCourseCode() ;
        else
            return $this->_finals_time . $this->getCourseCode() ;
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
     * Get Prelim Heat and Lane Numbers
     *
     * @return string prelim heat and lane numbers
     */
    function getPrelimHeatAndLaneNumbers($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($this->getPrelimTime() == 0.0)
            return WPST_NULL_STRING ;
        else
            return sprintf('%-2s%-2s', $this->getPrelimHeatNumber(), $this->PrelimLaneNumber()) ;
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
     * Get Finals Heat and Lane Numbers
     *
     * @return string finals heat and lane numbers
     */
    function getFinalsHeatAndLaneNumbers($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        if ($this->getFinalsTime() == 0.0)
            return WPST_NULL_STRING ;
        else
            return sprintf('%-2s%-2s', $this->getFinalsHeatNumber(), $this->FinalsLaneNumber()) ;
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

    /**
     * Set Preferred First Name
     *
     * @param string preferred first name
     */
    function setPreferredFirstName($txt)
    {
        $this->_preferred_first_name = $txt ;
    }

    /**
     * Get Preferred First Name
     *
     * @return string preferred first name
     */
    function getPreferredFirstName()
    {
        return $this->_preferred_first_name ;
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
    /**
     * Generate Record
     *
     * @return string SDIF D0 record
     */
    function GenerateRecord($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        return sprintf(WPST_SDIF_D0_GENERATE_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getSwimmerName(),
            $this->getUSS(),
            $this->getAttachCode(),
            $this->getCitizenCode(),
            $this->getBirthDate(),
            $this->getAgeOrClass(),
            $this->getGender(),
            $this->getEventGender(),
            $this->getEventDistance(),
            $this->getStrokeCode(),
            $this->getEventNumber(),
            $this->getEventAgeCode(),
            $this->getSwimDate(false),
            $this->getSeedTimeAndCourseCode($zerotimemode),
            $this->getPrelimTimeAndCourseCode($zerotimemode),
            $this->getSwimOffTimeAndCourseCode($zerotimemode),
            $this->getFinalsTimeAndCourseCode($zerotimemode),
            $this->getPrelimHeatAndLaneNumbers(),
            $this->getFinalsHeatAndLaneNumbers(),
            $this->getPrelimPlaceRanking(),
            $this->getFinalsPlaceRanking(),
            $this->getFinalsPoints(),
            $this->getEventTimeClassCode(),
            $this->getSwimmerFlightStatus(),
            $this->getFutureUse2()
        ) ;
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
     * Secondary Phone Number property
     */
    var $_secondary_phone_number ;

    /**
     * USS Registration Date property
     */
    var $_uss_registration_date ;

    /**
     * Member Code property
     */
    var $_member_code ;

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
     * Set Secondary Phone Number
     *
     * @param string secondary phone number
     */
    function setSecondaryPhoneNumber($txt)
    {
        $this->_secondary_phone_number = $txt ;
    }

    /**
     * Get Secondary Phone Number
     *
     * @return string secondary phone number
     */
    function getSecondaryPhoneNumber()
    {
        return $this->_secondary_phone_number ;
    }

    /**
     * Set USS Registration Date
     *
     * @param string uss registration date
     */
    function setUSSRegistrationDate($txt)
    {
        $this->_uss_registration_date = $txt ;
    }

    /**
     * Get USS Registration Date
     *
     * @return string uss registration date
     */
    function getUSSRegistrationDate()
    {
        return $this->_uss_registration_date ;
    }

    /**
     * Set Member Code
     *
     * @param string member code
     */
    function setMemberCode($txt)
    {
        $this->_member_code = $txt ;
    }

    /**
     * Get Member Code
     *
     * @return string member code
     */
    function getMemberCode()
    {
        return $this->_member_code ;
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
        $this->setPhoneNumber(trim(substr($this->_sdif_record, 124, 12))) ;
        $this->setSecondaryPhoneNumber(trim(substr($this->_sdif_record, 136, 12))) ;
        $this->setUSSRegistrationDate(trim(substr($this->_sdif_record, 148, 8))) ;
        $this->setMemberCode(trim(substr($this->_sdif_record, 156, 1))) ;
        $this->setFutureUse3(trim(substr($this->_sdif_record, 157, 3))) ;

        //  Construct 'new' and 'old' formats of the USS number
        //  from the name and birthdate fields.

        $this->setUSSNew() ;
        $this->setUSSOld() ;
    }
    /**
     * Generate Record
     *
     * @return string SDIF D1 record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_D1_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getTeamCode(),
            $this->getTeamCode5(),
            $this->getSwimmerName(),
            $this->getFutureUse2(),
            $this->getUSS(),
            $this->getAttachCode(),
            $this->getCitizenCode(),
            $this->getBirthDate(),
            $this->getAgeOrClass(),
            $this->getGender(),
            $this->getAdminInfo1(),
            $this->getAdminInfo4(),
            $this->getPhoneNumber(),
            $this->getSecondaryPhoneNumber(),
            $this->getUSSRegistrationDate(),
            $this->getMemberCode(),
            $this->getFutureUse3()
        ) ;
    }
}

/**
 * SDIF D2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFRecord
 */
class SDIFD2Record extends SDIFDxRecord
{
    /**
     * Swimmer Name
     */
    var $_swimmer_name ;

    /**
     * Swimmer Alternate Mailing Name property
     */
    var $_swimmer_alternate_mailing_name ;

    /**
     * Swimmer Address Line 1
     */
    var $_swimmer_address_1 ;

    /**
     * Swimmer Address Line 2
     */
    var $_swimmer_address_2 ;

    /**
     * Swimmer City
     */
    var $_swimmer_city ;

    /**
     * Swimmer State
     */
    var $_swimmer_state ;

    /**
     * Swimmer Postal Code
     */
    var $_swimmer_postal_code ;

    /**
     * Swimmer Country Code
     */
    var $_swimmer_country_code ;

    /**
     * Answer Code property
     */
    var $_answer_code ;

    /**
     * Season Code property
     */
    var $_season_code ;

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
     * Set Swimmer Alternate Mailing Name
     *
     * @param string alternate mailing name
     */
    function setSwimmerAlternateMailingName($txt)
    {
        $this->_swimmer_alternate_mailing_name = $txt ;
    }

    /**
     * Get Swimmer Alternate Mailing Name
     *
     * @return string alternate mailing name
     */
    function getSwimmerAlternateMailingName()
    {
        return $this->_swimmer_alternate_mailing_name ;
    }

    /**
     * Set Swimmer Address 1
     *
     * @param string swimmer address 1
     */
    function setSwimmerAddress1($txt)
    {
        $this->_swimmer_address_1 = $txt ;
    }

    /**
     * Get Swimmer Address 1
     *
     * @return string swimmer address 1
     */
    function getSwimmerAddress1()
    {
        return $this->_swimmer_address_1 ;
    }

    /**
     * Set Swimmer Address 2
     *
     * @param string swimmer address 2
     */
    function setSwimmerAddress2($txt)
    {
        $this->_swimmer_address_2 = $txt ;
    }

    /**
     * Get Swimmer Address 2
     *
     * @return string swimmer address 2
     */
    function getSwimmerAddress2()
    {
        return $this->_swimmer_address_2 ;
    }

    /**
     * Set Swimmer City
     *
     * @param string swimmer city
     */
    function setSwimmerCity($txt)
    {
        $this->_swimmer_city = $txt ;
    }

    /**
     * Get Swimmer City
     *
     * @return string swimmer city
     */
    function getSwimmerCity()
    {
        return $this->_swimmer_city ;
    }

    /**
     * Set Swimmer State
     *
     * @param string swimmer state
     */
    function setSwimmerState($txt)
    {
        $this->_swimmer_state = $txt ;
    }

    /**
     * Get Swimmer State
     *
     * @return string swimmer state
     */
    function getSwimmerState()
    {
        return $this->_swimmer_state ;
    }

    /**
     * Set Swimmer Postal Code
     *
     * @param string swimmer postal code
     */
    function setSwimmerPostalCode($txt)
    {
        $this->_swimmer_postal_code = $txt ;
    }

    /**
     * Get Swimmer Postal Code
     *
     * @return string swimmer postal code
     */
    function getSwimmerPostalCode()
    {
        return $this->_swimmer_postal_code ;
    }

    /**
     * Set Swimmer Country Code
     *
     * @param string swimmer country code
     */
    function setSwimmerCountryCode($txt)
    {
        $this->_swimmer_country_code = $txt ;
    }

    /**
     * Get Swimmer Country Code
     *
     * @return string swimmer country code
     */
    function getSwimmerCountryCode()
    {
        return $this->_swimmer_country_code ;
    }

    /**
     * Set Answer Code
     *
     * @param string answer code
     */
    function setAnswerCode($txt)
    {
        $this->_answer_code = $txt ;
    }

    /**
     * Get Answer Code
     *
     * @return string answer code
     */
    function getAnswerCode()
    {
        return $this->_answer_code ;
    }

    /**
     * Set Season Code
     *
     * @param string season code
     */
    function setSeasonCode($txt)
    {
        $this->_season_code = $txt ;
    }

    /**
     * Get Season Code
     *
     * @return string season code
     */
    function getSeasonCode()
    {
        return $this->_season_code ;
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
            //print $c->render() ;
        }

        //  Extract the data from the SDIF record by substring position

        $this->setOrgCode(trim(substr($this->_sdif_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 8))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 11, 6))) ;
        $this->setTeamCode5(trim(substr($this->_sdif_record, 17, 1))) ;
        $this->setSwimmerName(trim(substr($this->_sdif_record, 18, 28))) ;
        $this->setSwimmerAlternateMailingName(trim(substr($this->_sdif_record, 46, 30))) ;
        $this->setSwimmerAddress1(trim(substr($this->_sdif_record, 76, 30))) ;
        $this->setSwimmerCity(trim(substr($this->_sdif_record, 106, 20))) ;
        $this->setSwimmerState(trim(substr($this->_sdif_record, 126, 2))) ;
        $this->setSwimmerPostalCode(trim(substr($this->_sdif_record, 140, 10))) ;
        $this->setSwimmerCountryCode(trim(substr($this->_sdif_record, 150, 3))) ;
        $this->setRegionCode(trim(substr($this->_sdif_record, 153, 1))) ;
        $this->setAnswerCode(trim(substr($this->_sdif_record, 154, 1))) ;
        $this->setSeasonCode(trim(substr($this->_sdif_record, 155, 1))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 156, 4))) ;
    }

    /**
     * Generate Record
     *
     * @return sting - D2 SDIF record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_D2_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getTeamCode(),
            $this->getTeamCode5(),
            $this->getSwimmerName(),
            $this->getSwimmerAlternateMailingName(),
            $this->getSwimmerAddress1(),
            $this->getSwimmerCity(),
            $this->getSwimmerState(),
            $this->getSwimmerCountryCode(),
            $this->getSwimmerPostalCode(),
            $this->getSwimmerCountryCode(),
            $this->getRegionCode(),
            $this->getAnswerCode(),
            $this->getSeasonCode(),
            $this->getFutureUse2()
        ) ;
    }
}

/**
 * SDIF D3 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFDxRecord
 */
class SDIFD3Record extends SDIFDxRecord
{
    /**
     * Ethnicity Code property
     */
    var $_ethnicity_code ;

    /**
     * Junior High School property
     */
    var $_junior_high_school ;

    /**
     * Senior High School property
     */
    var $_senior_high_school ;

    /**
     * YMCA-YWCA property
     */
    var $_ymca_ywca ;

    /**
     * College property
     */
    var $_college ;

    /**
     * Summer Swim League property
     */
    var $_summer_swim_league ;

    /**
     * Masters property
     */
    var $_masters ;

    /**
     * Disabled Sports Organization property
     */
    var $_disabled_sports_org ;

    /**
     * Water Polo property
     */
    var $_water_polo ;

    /**
     * None property
     */
    var $_none ;

    /**
     * Set Ethnicity Code
     *
     * @param string ethnicity code
     */
    function setEthnicityCode($txt)
    {
        $this->_ethnicity_code = $txt ;
    }

    /**
     * Get Ethnicity Code
     *
     * @return string file code
     */
    function getEthnicityCode()
    {
        return $this->_ethnicity_code ;
    }

    /**
     * Set Junior High School
     *
     * @param string junior high school
     */
    function setJuniorHighSchool($txt)
    {
        $this->_junior_high_school = $txt ;
    }

    /**
     * Get Junior High School
     *
     * @return string junior high school
     */
    function getJuniorHighSchool()
    {
        return $this->_junior_high_school ;
    }

    /**
     * Set Senior High School
     *
     * @param string senior high school
     */
    function setSeniorHighSchool($txt)
    {
        $this->_senior_high_school = $txt ;
    }

    /**
     * Get Senior High School
     *
     * @return string senior high school
     */
    function getSeniorHighSchool()
    {
        return $this->_senior_high_school ;
    }

    /**
     * Set YMCAYWCA
     *
     * @param string ymca ywca
     */
    function setYMCAYWCA($txt)
    {
        $this->_ymca_ywca = $txt ;
    }

    /**
     * Get YMCAYWCA
     *
     * @return string ymca ywca
     */
    function getYMCAYWCA()
    {
        return $this->_ymca_ywca ;
    }

    /**
     * Set College
     *
     * @param string college
     */
    function setCollege($txt)
    {
        $this->_college = $txt ;
    }

    /**
     * Get College
     *
     * @return string college
     */
    function getCollege()
    {
        return $this->_college ;
    }

    /**
     * Set SummerSwimLeague
     *
     * @param string summer swim league
     */
    function setSummerSwimLeague($txt)
    {
        $this->_summer_swim_league = $txt ;
    }

    /**
     * Get SummerSwimLeague
     *
     * @return string summer swim league
     */
    function getSummerSwimLeague()
    {
        return $this->_summer_swim_league ;
    }

    /**
     * Set Masters
     *
     * @param string masters
     */
    function setMasters($txt)
    {
        $this->_masters = $txt ;
    }

    /**
     * Get Masters
     *
     * @return string masters
     */
    function getMasters()
    {
        return $this->_masters ;
    }

    /**
     * Set Disabled Sports Org
     *
     * @param string disabled sports org
     */
    function setDisabledSportsOrg($txt)
    {
        $this->_disabled_sports_org = $txt ;
    }

    /**
     * Get Disabled Sports Org
     *
     * @return string disabled sports org
     */
    function getDisabledSportsOrg()
    {
        return $this->_disabled_sports_org ;
    }

    /**
     * Set Water Polo
     *
     * @param string water polo
     */
    function setWaterPolo($txt)
    {
        $this->_water_polo = $txt ;
    }

    /**
     * Get Water Polo
     *
     * @return string water polo
     */
    function getWaterPolo()
    {
        return $this->_water_polo ;
    }

    /**
     * Set None
     *
     * @param string none
     */
    function setNone($txt)
    {
        $this->_none = $txt ;
    }

    /**
     * Get None
     *
     * @return string none
     */
    function getNone()
    {
        return $this->_none ;
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

        $this->setUSS(trim(substr($this->_sdif_record, 2, 14))) ;
        $this->setSwimmerName(trim(substr($this->_sdif_record, 16, 15))) ;
        $this->setEthnicityCode(trim(substr($this->_sdif_record, 31, 2))) ;
        $this->setJuniorHighSchool(trim(substr($this->_sdif_record, 33, 1))) ;
        $this->setSeniorHighSchool(trim(substr($this->_sdif_record, 34, 1))) ;
        $this->setYMCAYWCA(trim(substr($this->_sdif_record, 35, 1))) ;
        $this->setCollege(trim(substr($this->_sdif_record, 36, 1))) ;
        $this->setSummerSwimLeague(trim(substr($this->_sdif_record, 37, 1))) ;
        $this->setMasters(trim(substr($this->_sdif_record, 38, 1))) ;
        $this->setDisabledSportsOrg(trim(substr($this->_sdif_record, 39, 1))) ;
        $this->setWaterPolo(trim(substr($this->_sdif_record, 40, 1))) ;
        $this->setNone(trim(substr($this->_sdif_record, 41, 1))) ;
        $this->setFutureUse1(trim(substr($this->_sdif_record, 42, 118))) ;

        //  Construct 'new' and 'old' formats of the USS number
        //  from the name and birthdate fields.

        $this->setUSSNew() ;
        $this->setUSSOld() ;
    }
    /**
     * Generate Record
     *
     * @return string SDIF D0 record
     */
    function GenerateRecord($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        return sprintf(WPST_SDIF_D3_RECORD,
            $this->getUSS(),
            $this->getPreferredFirstName(),
            $this->getEthnicityCode(),
            $this->getJuniorHighSchool(),
            $this->getSeniorHighSchool(),
            $this->getYMCAYWCA(),
            $this->getCollege(),
            $this->getSummerSwimLeague(),
            $this->getMasters(),
            $this->getDisabledSportsOrg(),
            $this->getWaterPolo(),
            $this->getNone(),
            $this->getFutureUse1()
        ) ;
    }
}

/**
 * SDIF E0 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFDxRecord
 */
class SDIFE0Record extends SDIFDxRecord
{
    /**
     * Relay Team Name property
     */
    var $_relay_team_name ;

    /**
     * Number of F0 records property
     */
    var $_number_of_f0_records ;

    /**
     * Total Age of Athletes property
     */
    var $_total_age_of_athletes ;

    /**
     * Set Relay Team Name
     *
     * @param string relay team name
     */
    function setRelayTeamName($txt)
    {
        $this->_relay_team_name = $txt ;
    }

    /**
     * Get Relay Team Name
     *
     * @return string relay team name
     */
    function getRelayTeamName()
    {
        return $this->_relay_team_name ;
    }

    /**
     * Set Number of F0 Records
     *
     * @param string number of F0 records
     */
    function setNumberOfF0Records($txt)
    {
        $this->_number_of_f0_records = $txt ;
    }

    /**
     * Get Number of F0 Records
     *
     * @return string number of F0 records
     */
    function getNumberOfF0Records()
    {
        return $this->_number_of_f0_records ;
    }

    /**
     * Set Total Age of Athletes property
     *
     * @param int total age of athletes
     */
    function setTotalAgeOfAthletes($txt)
    {
        $this->_total_age_of_athletes = $txt ;
    }

    /**
     * Get Total Age of Athletes property
     *
     * @return int total age of athletes
     */
    function getTotalAgeOfAthletes()
    {
        return $this->_total_age_of_athletes ;
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
        $this->setRelayTeamName(trim(substr($this->_sdif_record, 11, 1))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 12, 6))) ;
        $this->setNumberOfF0Records(trim(substr($this->_sdif_record, 18, 2))) ;
        $this->setEventGender(trim(substr($this->_sdif_record, 20, 1))) ;
        $this->setEventDistance(trim(substr($this->_sdif_record, 21, 4))) ;
        $this->setStrokeCode(trim(substr($this->_sdif_record, 25, 1))) ;
        $this->setEventNumber(trim(substr($this->_sdif_record, 26, 4))) ;
        $this->setEventAgeCode(trim(substr($this->_sdif_record, 30, 4))) ;
        $this->setSwimTotalAgeOfSwimmers(trim(substr($this->_sdif_record, 34, 3))) ;
        $this->setSwimDate(trim(substr($this->_sdif_record, 37, 8))) ;
        $this->setSeedTime(trim(substr($this->_sdif_record, 45, 8))) ;
        $this->setSeedCourseCode(trim(substr($this->_sdif_record, 53, 1))) ;
        $this->setPrelimTime(trim(substr($this->_sdif_record, 54, 8))) ;
        $this->setPrelimCourseCode(trim(substr($this->_sdif_record, 62, 1))) ;
        $this->setSwimOffTime(trim(substr($this->_sdif_record, 63, 8))) ;
        $this->setSwimOffCourseCode(trim(substr($this->_sdif_record, 71, 1))) ;
        $this->setFinalsTime(trim(substr($this->_sdif_record, 72, 8))) ;
        $this->setFinalsCourseCode(trim(substr($this->_sdif_record, 80, 1))) ;
        $this->setPrelimHeatNumber(trim(substr($this->_sdif_record, 81, 2))) ;
        $this->setPrelimLaneNumber(trim(substr($this->_sdif_record, 83, 2))) ;
        $this->setFinalsHeatNumber(trim(substr($this->_sdif_record, 85, 2))) ;
        $this->setFinalsLaneNumber(trim(substr($this->_sdif_record, 87, 2))) ;
        $this->setPrelimPlaceRanking(trim(substr($this->_sdif_record, 89, 3))) ;
        $this->setFinalsPlaceRanking(trim(substr($this->_sdif_record, 92, 3))) ;
        $this->setFinalsPoints(trim(substr($this->_sdif_record, 95, 4))) ;
        $this->setEventTimeClassCode(trim(substr($this->_sdif_record, 99, 2))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 102, 59))) ;
    }
    /**
     * Generate Record
     *
     * @return string SDIF E0 record
     */
    function GenerateRecord($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        return sprintf(WPST_SDIF_E0_GENERATE_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getRelayTeamName(),
            $this->getTeamCode(),
            $this->getNumberOfF0Records(),
            $this->getEventGender(),
            $this->getEventDistance(),
            $this->getStrokeCode(),
            $this->getEventNumber(),
            $this->getEventAgeCode(),
            $this->getTotalAgeOfAthletes(),
            $this->getSwimDate(false),
            $this->getSeedTimeAndCourseCode(),
            $this->getPrelimTimeAndCourseCode(),
            $this->getSwimOffTimeAndCourseCode(),
            $this->getFinalsTimeAndCourseCode(),
            $this->getPrelimHeatNumber(),
            $this->getPrelimLaneNumber(),
            $this->getFinalsHeatNumber(),
            $this->getFinalsLaneNumber(),
            $this->getPrelimPlaceRanking(),
            $this->getFinalsPlaceRanking(),
            $this->getFinalsPoints(),
            $this->getEventTimeClassCode(),
            $this->getFutureUse2()
        ) ;
    }
}

/**
 * SDIF F0 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFE0Record
 */
class SDIFF0Record extends SDIFE0Record
{
    /**
     * Prelim Leg Order Code property
     */
    var $_prelim_leg_order_code ;

    /**
     * Swim Off Leg Order Code property
     */
    var $_swim_off_leg_order_code ;

    /**
     * Finals Leg Order Code property
     */
    var $_finals_leg_order_code ;

    /**
     * Leg Time property
     */
    var $_leg_time ;

    /**
     * Take-off Time property
     */
    var $_takeoff_time ;

    /**
     * Set Prelim Leg Order Code
     *
     * @param string prelim leg order code
     */
    function setPrelimLegOrderCode($txt)
    {
        $this->_prelim_leg_order_code = $txt ;
    }

    /**
     * Get Prelim Leg Order Code
     *
     * @return string prelim leg order code
     */
    function getPrelimLegOrderCode()
    {
        return $this->_prelim_leg_order_code ;
    }

    /**
     * Set Swim Off Leg Order Code
     *
     * @param string swim off leg order code
     */
    function setSwimOffLegOrderCode($txt)
    {
        $this->_swim_off_leg_order_code = $txt ;
    }

    /**
     * Get Swim Off Leg Order Code
     *
     * @return string swim off leg order code
     */
    function getSwimOffLegOrderCode()
    {
        return $this->_swim_off_leg_order_code ;
    }

    /**
     * Set Finals Leg Order Code
     *
     * @param string finals leg order code
     */
    function setFinalsLegOrderCode($txt)
    {
        $this->_finals_leg_order_code = $txt ;
    }

    /**
     * Get Finals Leg Order Code
     *
     * @return string leg time
     */
    function getFinalsLegOrderCode()
    {
        return $this->_finals_leg_order_code ;
    }

    /**
     * Set Leg Time
     *
     * @param string leg time
     */
    function setLegTime($txt)
    {
        $this->_leg_time = $txt ;
    }

    /**
     * Get Leg Time
     *
     * @return string leg time
     */
    function getLegTime()
    {
        return $this->_leg_time ;
    }

    /**
     * Set TakeOff Time
     *
     * @param int total age
     */
    function setTakeOffTime($txt)
    {
        $this->_total_age = $txt ;
    }

    /**
     * Get TakeOff Time
     *
     * @return string take-off time
     */
    function getTakeOffTime()
    {
        return $this->_takeoff_time ;
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
        $this->setFutureUse1(trim(substr($this->_sdif_record, 3, 12))) ;
        $this->setTeamCode(trim(substr($this->_sdif_record, 15, 6))) ;
        $this->setRelayTeamName(trim(substr($this->_sdif_record, 21, 1))) ;
        $this->setSwimmerName(trim(substr($this->_sdif_record, 22, 28))) ;
        $this->setUSS(trim(substr($this->_sdif_record, 50, 12))) ;
        $this->setCitizenCode(trim(substr($this->_sdif_record, 62, 3))) ;
        $this->setBirthDate(trim(substr($this->_sdif_record, 65, 8))) ;
        $this->setAgeOrClass(trim(substr($this->_sdif_record, 73, 2))) ;
        $this->setGender(trim(substr($this->_sdif_record, 75, 1))) ;
        $this->setPrelimLegOrderCode(trim(substr($this->_sdif_record, 76, 1))) ;
        $this->setSwimOffLegOrderCode(trim(substr($this->_sdif_record, 77, 1))) ;
        $this->setFinalsLegOrderCode(trim(substr($this->_sdif_record, 78, 1))) ;
        $this->setLegTime(trim(substr($this->_sdif_record, 79, 8))) ;
        $this->setCourseCode(trim(substr($this->_sdif_record, 87, 1))) ;
        $this->setTakeoffTime(trim(substr($this->_sdif_record, 88, 4))) ;
        $this->setUSSNew(trim(substr($this->_sdif_record, 92, 14))) ;
        $this->setPreferredFirstName(trim(substr($this->_sdif_record, 106, 15))) ;
        $this->setFutureUse2(trim(substr($this->_sdif_record, 121, 29))) ;

        //  Construct 'old' format of the USS number from the name
        //  and birthdate fields.  It should match USS in column 51.

        $this->setUSSOld() ;
    }

    /**
     * Generate Record
     *
     * @return string SDIF F0 record
     */
    function GenerateRecord($zerotimemode = WPST_SDIF_USE_BLANKS_VALUE)
    {
        return sprintf(WPST_SDIF_F0_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getTeamCode(),
            $this->getRelayTeamName(),
            $this->getSwimmerName(),
            $this->getUSS(),
            $this->getCitizenCode(),
            $this->getBirthDate(false),
            $this->getAgeOrClass(),
            $this->getGender(),
            $this->getPrelimLegOrderCode(),
            $this->getSwimOffLegOrderCode(),
            $this->getFinalsLegOrderCode(),
            $this->getLegTime(),
            $this->getCourseCode(),
            $this->getTakeoffTime(),
            $this->getUSSNew(),
            $this->getPreferredFirstName(),
            $this->getFutureUse2()
        ) ;
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

    /**
     * Generage Record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_SDIF_Z0_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getFileCode(),
            $this->getNotes(),
            $this->getBRecordCount(),
            $this->getMeetCount(),
            $this->getCRecordCount(),
            $this->getTeamCount(),
            $this->getDRecordCount(),
            $this->getSwimmerCount(),
            $this->getERecordCount(),
            $this->getFRecordCount(),
            $this->getGRecordCount(),
            $this->getBatchNumber(),
            $this->getNewMemberCount(),
            $this->getRenewMemberCount(),
            $this->getChangeMemberCount(),
            $this->getDeleteMemberCount(),
            $this->getFutureUse2()
        ) ;
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
     * Return an array of file format codes and their mappings
     *
     * @return array file format code mappings
     */
    function GetFileFormat()
    {
        $WPST_FILE_FORMAT = array(
            WPST_FILE_FORMAT_SDIF_SD3_LABEL => WPST_FILE_FORMAT_SDIF_SD3_VALUE
           ,WPST_FILE_FORMAT_HYTEK_TM_HY3_LABEL => WPST_FILE_FORMAT_HYTEK_TM_HY3_VALUE
           //,WPST_FILE_FORMAT_HYTEK_MM_HY3_LABEL => WPST_FILE_FORMAT_HYTEK_MM_HY3_VALUE
        ) ;

        return $WPST_FILE_FORMAT ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCourseCode($units = WPST_METERS, $length = 25)
    {
        if (($units == WPST_YARDS) && ($length == 25))
            return WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE ;
        else if (($units == WPST_METERS) && ($length == 25))
            return WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE ;
        else if (($units == WPST_METERS) && ($length == 50))
            return WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE  ;
        else
            return WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCountryCode($country)
    {
        $codes = SDIFCodeTableMappings::GetCountryCodes() ;

        if (array_key_exists($country, $codes))
            return $codes[$country] ;
        else
            return WPST_NA ;
    }

    /**
     * Return an array of zero time codes and their mappings
     *
     * @return array zero time code mappings
     */
    function GetZeroTimeMode()
    {
        $WPST_ZERO_TIME_MODES = array(
            WPST_SDIF_USE_BLANKS_LABEL => WPST_SDIF_USE_BLANKS_VALUE
           ,WPST_SDIF_USE_ZEROS_LABEL => WPST_SDIF_USE_ZEROS_VALUE
           ,WPST_SDIF_USE_NT_LABEL => WPST_SDIF_USE_NT_VALUE
        ) ;

        return $WPST_ZERO_TIME_MODES ;
    }

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
